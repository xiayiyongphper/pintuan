<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 17:06
 */

namespace framework;

use framework\components\ToolsAbstract;
use framework\message\Message;
use service\message\common\Header;
use service\message\common\Protocol;

class HttpServer extends Application
{
    public function __construct($config = [])
    {
        parent::__construct($config);
        $serverConfig = \Yii::$app->params['http_server_config'];
        if (!is_array($serverConfig)) {
            throw new \Exception('invalid ip port config');
        }
        $this->serverConfig = $serverConfig;
        $this->log($this->serverConfig);

        $ipPort = \Yii::$app->params['ip_port'];
        if (!is_array($ipPort)) {
            throw new \Exception('ip port config not found');
        }
        if (!isset($ipPort['host'], $ipPort['port'], $ipPort['http_port'],
            $ipPort['localHost'], $ipPort['localPort'],
            $ipPort['msgHost'], $ipPort['msgPort'], $ipPort['hostV6'])
        ) {
            throw new \Exception('invalid ip port config');
        }
        $this->host = $ipPort['host'];
        $this->hostV6 = $ipPort['hostV6'];
        $this->port = $ipPort['http_port'];
        $this->localHost = $ipPort['localHost'];
        $this->localPort = $ipPort['localPort'];
        $this->msgHost = $ipPort['msgHost'];
        $this->msgPort = $ipPort['msgPort'];
        if (isset($this->serverConfig['task_ipc_mode'])) {
            $this->taskIpcMode = $this->serverConfig['task_ipc_mode'];
        }
        if ($this->taskIpcMode == 3 && isset($this->serverConfig['message_queue_key'])) {
            $this->msgQueueKey = $this->serverConfig['message_queue_key'];
        }

        if ($this->msgQueueKey) {
            $this->msgQueue = msg_get_queue($this->msgQueueKey);
        }
    }

    public function serve()
    {
        $server = new \swoole_http_server($this->host, $this->port);
        $server->on('request', function ($request, $response) {
            $response->header('Content-Type', 'application/json');
            $return = [
                'sta' => 0,
                'msg' => '',
                'data' => ''
            ];

            try {
                $data = json_decode($request->rawContent(), true);
                $requestArray = $data['header'];
                $route = $requestArray['route'];

                $requestHeader = new Header();
                $requestHeader->setFrom(ToolsAbstract::pb_array_filter($data['header']));

                if(isset($requestArray['protocol']) && $requestArray['protocol'] == Protocol::JSON){
                    $requestBody = $data['body'];
                }else{
                    $model = $this->getResource($route, $requestArray['version']);
                    $modelObj = new $model();
                    $requestBody = $modelObj->request();
                    $requestBody->setFrom(ToolsAbstract::pb_array_filter($data['body']));
                }

                $requestString = Message::pack($requestHeader, $requestBody);
                $requestEmu = $this->getRequest()->setRawBody($requestString)->setRemote(false);

                list($header, $responseData) = $this->handleRequest($requestEmu);
//                ToolsAbstract::log($header, 'http_server_header.log');
//                ToolsAbstract::log($responseData, 'http_server_responseData.log');


                if(isset($requestArray['protocol']) && $requestArray['protocol'] == Protocol::JSON){
                    if(!isset($responseData['code']) || !$responseData['code']){
                        $return['sta'] = 1;
                        $return['data'] = $responseData['data'];
                    }
                    $return['msg'] = $responseData['message'];
                }else{
                    if (!$header->getCode() && method_exists($responseData, 'toArray')) {
                        $return['sta'] = 1;
                        $return['data'] = $responseData->toArray();
                    } else {
                        $return['msg'] = $header->getMsg();
                    }
                }

            } catch (\Exception $e) {
                $return['msg'] = $e->getMessage();
                ToolsAbstract::log($e, 'http_server_error.log');
            }

//            ToolsAbstract::log($return, 'jun.log');
            $response->end(json_encode($return));
        });

        $server->on('Start', function (\swoole_http_server $server) {
            try {
                swoole_set_process_name(self::getProcessNamePrefix() . ':Master(HTTP)-' . $server->master_pid);
            } catch (\Exception $e) {

            }
        });

        // 每个 Worker 进程启动或重启时都会执行
        $server->on('WorkerStart', function (\swoole_http_server $server, $workerId) {
            if ($server->taskworker) {
                try {
                    swoole_set_process_name(self::getProcessNamePrefix() . ':Task Worker(HTTP)-' . $workerId);
                } catch (\Exception $e) {

                }
            } else {
                try {
                    swoole_set_process_name(self::getProcessNamePrefix() . ':Worker(HTTP)-' . $workerId);
                } catch (\Exception $e) {

                }
            }
        });
        $server->start();
    }
}