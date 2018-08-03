<?php

namespace framework\components;

use framework\components\es\Console;
use framework\components\es\Timeline;
use framework\Exception;
use framework\message\Message;
use service\message\common\EncryptionMethod;
use service\message\common\Header;
use service\message\common\SourceEnum;
use service\message\core\ReportRouteRequest;
use SysInfo\SysInfo;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-7-26
 * Time: 下午2:39
 * Email: henryzxj1989@gmail.com
 */
abstract class ProxyAbstract
{
    const KEY_LOCAL_SERVICE = 'local_service';
    const KEY_INTERNAL_SERVICE = 'local.service';
    const KEY_REMOTE_SERVICE = 'remote_service';
    const KEY_HTTP_SERVICE = 'http_service';
    const ROUTE_ROUTE_FETCH = 'route.fetch';
    const ROUTE_ROUTE_REPORT = 'route.report';
    const ROUTE_FETCH_TOKEN = 'yggBfivOTkMOFNDm';
    const PING_TABLE = 'ping_table';
    const SERVER_IP_SET = 'server_ip_set';
    const SERVICE_PREFIX = 'service_';
    const LOCAL = 'local';
    const REMOTE = 'remote';
    const HTTP = 'http';
    protected static $client = [];

    /**
     * @param $route
     * @return array|bool|mixed|null|string
     * @throws \Exception
     */
    public static function getRoute($route)
    {
        $parts = explode('.', $route);
        if (count($parts) != 2) {
            Exception::systemNotFound();
        }
        $modelName = $parts[0];
        if ($route == self::ROUTE_ROUTE_REPORT) {
            $ipPort = \Yii::$app->params['proxy_ip_port'];
            if (!is_array($ipPort)) {
                throw new \Exception('ip port config not found');
            }
            $localHost = $ipPort['localHost'];
            $localPort = $ipPort['localPort'];
            return [$localHost, $localPort];
        }

        $redis = ToolsAbstract::getRouteRedis();
        $tableName = self::KEY_INTERNAL_SERVICE;
        if ($redis->hExists($tableName, $modelName)) {
            $dsn = $redis->hGet($tableName, $modelName);
            list($ip, $port) = explode(':', $dsn);
            if (isset($ip, $port)) {
                return [$ip, $port];
            }
        }
        Exception::systemNotFound();
    }

    /**
     * sendRequest蜕化为节点内部的通信工具，不能跨机器请求
     * @param Header $header
     * @param $request
     * @return Message
     * @throws \Exception
     */
    public static function sendRequest($header, $request)
    {
        $timeStart = microtime(true);
        // 服务器间通讯为内网，不加密
        $header->setEncrypt(EncryptionMethod::ORG);
        $result = false;
        try {
            list($ip, $port) = self::getRoute($header->getRoute());
            $client = self::getClient($ip, $port);
            $client->send(Message::pack($header, $request));
            $result = $client->recv();
        } catch (\Exception $e) {
            $timeEnd = microtime(true);
            $elapsed = $timeEnd - $timeStart;
            $code = $e->getCode() > 0 ? $e->getCode() : 999;
            Timeline::get()->report($header->getRoute(), 'sendRequest', ENV_SYS_NAME, $elapsed, $code, $header->getTraceId(), $header->getRequestId());
            Console::get()->logException($e);
            throw $e;
        } catch (\Error $e) {
            ToolsAbstract::logError($e);
        }
        // swoole 1.8.1有bug,close之后此task也退出了. https://github.com/swoole/swoole-src/issues/522
        //$client->close();
        $message = new Message();
        $message->unpackResponse($result);
        $timeEnd = microtime(true);
        $elapsed = $timeEnd - $timeStart;
        if ($message->getHeader()->getCode() > 0) {
            $e = new \Exception($message->getHeader()->getMsg(), $message->getHeader()->getCode());
            Console::get()->logException($e);
            throw $e;
        }
        Timeline::get()->report($header->getRoute(), 'sendRequest', ENV_SYS_NAME, $elapsed, 0, $header->getTraceId(), $header->getRequestId());
        return $message;
    }

    /**
     * @param string $eventName eg.:customer_msg.customer_registered
     * @param mixed $eventData
     * @param bool $remote
     * @return bool
     * @throws \Exception
     */
    public static function sendMessage($eventName, $eventData, $remote = false)
    {
        $timeStart = microtime(true);
        $result = false;
        try {
            Console::get()->log($eventName, 'sendMessage.log');
            Console::get()->log($eventData, 'sendMessage.log');
            list($ip, $port) = self::getRoute($eventName);
            Console::get()->log($ip, 'sendMessage.log');
            Console::get()->log($port, 'sendMessage.log');
            $client = self::getClient($ip, $port);
            $client->send(Message::packJson($eventData));
            $result = $client->recv();
        } catch (\Exception $e) {
            $timeEnd = microtime(true);
            $elapsed = $timeEnd - $timeStart;
            $code = $e->getCode() > 0 ? $e->getCode() : 999;
            Timeline::get()->report($eventName, 'sendMessage', ToolsAbstract::getSysName(), $elapsed, $code);
            throw $e;
        } catch (\Error $e) {
            ToolsAbstract::logError($e);
        }
        // swoole 1.8.1有bug,close之后此task也退出了. https://github.com/swoole/swoole-src/issues/522
        //$client->close();
        $result = Message::unpackJson($result);
        $timeEnd = microtime(true);
        $elapsed = $timeEnd - $timeStart;
        Timeline::get()->report($eventName, 'sendMessage', ToolsAbstract::getSysName(), $elapsed);
        return true;
    }

    /**
     * @param $ip
     * @param $port
     *
     * @return \swoole_client
     * @throws \Exception
     */
    protected static function getClient($ip, $port)
    {
        $client = new \swoole_client(SWOOLE_SOCK_TCP);
        // 加上跟SOAClient一样的结束符检测
        $client->set(\Yii::$app->params['soa_client_config']);
        $ret = $client->connect($ip, $port, 10);
        if (!$ret) {
            $e = new \Exception(sprintf("connect failed. Error: %s", $client->errCode));
            Console::get()->logException($e);
            throw $e;
        }
        $sockName = $client->getsockname();
        Console::get()->log("[Server]: New Proxy Client:[{$sockName['host']}:{$sockName['port']}]<->to:{$ip}:[{$port}]");
        return $client;
    }

    public static function reportServices($serviceMapping)
    {
        $request = new ReportRouteRequest();
        $requestData = [];
        foreach ($serviceMapping as $key => $services) {
            switch ($key) {
//                case self::LOCAL:
                //内部的路由不上报
//                    $requestData[self::KEY_LOCAL_SERVICE] = $services;
//                    break;
                case self::REMOTE:
                    $requestData[self::KEY_REMOTE_SERVICE] = $services;
                    break;
//                case self::HTTP:
                //http的路由不上报
//                    $requestData[self::KEY_HTTP_SERVICE] = $services;
//                    break;
                default:

            }
        }
        if (count($requestData) > 0) {
            $requestData['auth_token'] = self::ROUTE_FETCH_TOKEN;

            // 防止拿不到系统信息结果不上报地址的bug
            $sysInfoData = false;
            try {
                /** @var \SysInfo\SysInfoInterface $sysinfo */
                $sysinfo = SysInfo::factory();
                $sysInfoData = $sysinfo->getLoad()->getAvg();
            } catch (\Exception $exception) {
                ToolsAbstract::logException($exception);
            }

            $serverData = [
                'memory' => ENV_SERVER_MEMORY,
                'cpu' => ENV_SERVER_CPU,
                'cores' => ENV_SERVER_CPU_CORES,
                //'loads' => $sysinfo->getLoad()->getAvg(),
                'ip' => ENV_SERVER_IP,
                'local_ip' => ENV_SERVER_LOCAL_IP,
            ];
            // 若有系统信息则填写
            if ($sysInfoData) {
                $serverData['loads'] = $sysInfoData;
            }
            $requestData['server'] = $serverData;
            //ToolsAbstract::log($requestData, 'report.log');
            $requestData['version'] = ENV_NODE_VERSION;
            $request->setFrom($requestData);
            $header = new Header();
            switch (ToolsAbstract::getSysName()) {
                case 'core':
                    $header->setSource(SourceEnum::CORE);
                    break;
                case 'merchant':
                    $header->setSource(SourceEnum::MERCHANT);
                    break;
                case 'customer':
                    $header->setSource(SourceEnum::CUSTOMER);
                    break;
                case 'contractor':
                    $header->setSource(SourceEnum::CONTRACTOR);
                    break;
                case 'crontab':
                    $header->setSource(SourceEnum::CRONTAB);
                    break;
                default:
                    $header->setSource(SourceEnum::CORE);
            }
            $header->setRoute(self::ROUTE_ROUTE_REPORT);
            self::sendRequest($header, $request);
        }
    }
}