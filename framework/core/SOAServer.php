<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/25
 * Time: 15:40
 */

namespace framework\core;

use framework\components\es\Timeline;
use framework\components\ProxyAbstract;
use framework\components\ToolsAbstract;
use framework\Exception;
use framework\message\Message;
use message\common\ResponseHeader;


/**
 * Class SOAServer
 * @package framework\core
 */
class SOAServer extends SWServer
{
    protected $hostV6;
    protected $localHost;
    protected $localPort;
    protected $msgHost;
    protected $msgPort;
    protected $taskIpcMode;
    protected $msgQueueKey;
    protected $msgQueue;
    protected $avgMsgSize = 0;

    /**
     * range > 10
     * @var int
     */
    protected $factor = 12;
    protected $maxQueueLength = 1000;
    protected $msgQBytes;
    public $resources = [];
    const OPTION_APPCONFIG = 'appconfig';
    /**
     * @var SOARequest
     */
    protected $_handleRequest;

    /**
     * @inheritdoc
     */
    public function onReceive(\swoole_server $server, $fd, $from_id, $data)
    {
        $connection = $server->connection_info($fd, $from_id);

        $request = $this->getRequest();
        $request->setRawBody($data)->setFd($fd)->setRemoteIp($connection['remote_ip']);

        switch ($connection['server_port']) {
            case $this->localPort:
                $request->setRemote(false);
                $this->handleWorker($server, $fd, $request);
                break;
            case $this->msgPort:
                $json = Message::unpackJson($data);
                ToolsAbstract::getRedis()->lPush(ToolsAbstract::getRedisMsgQueueKey(), $json);
                $server->send($fd, Message::packJson(['success' => true]));
                $this->log($json);
                break;
            default:
                $request->setRemote(true)->setServer($server);
                $this->handleWorker($server, $fd, $request);
                break;
        }
    }

    public function handleWorker($server, $fd, $taskData)
    {
        //赋值全局request，在yii内部使用
        \Yii::$app->set('request', $taskData);
        \Yii::$app->set('soa_server', $this->server);
        /** @var \framework\protocolbuffers\Message|array|bool $data */
        /** @var \message\common\ResponseHeader $header */
        list($header, $data) = $this->handleRequest($taskData);
        if ($header instanceof \framework\protocolbuffers\Message) {
            $server->send($fd, Message::pack($header, $data));
        } else {
            $server->close($fd);
            $e = new \Exception('Task execute error.', 100);
            $this->logException($e);
        }
    }

    /**
     * @param \swoole_server $server
     * @param int $task_id
     * @param int $from_id
     * @param string $taskData | Array
     * @return mixed
     */
    public function onTask(\swoole_server $server, $task_id, $from_id, $taskData)
    {
        $cmd = $taskData['function'];
        $params = $taskData['data'];
        $className = 'service\task\\' . $cmd;
        $model = new $className();
        if (!method_exists($model, 'run')) {
            Exception::invalidRequestRoute();

        }
        // 调用方法
        $data = $model->run($params);
        return $data;
    }

    /**
     * @param \yii\base\Request $request
     * @return array|mixed
     */
    public function handleRequest($request)
    {
        /** @var \message\common\Header $header */
        list ($header, $params) = $request->resolve();
        if ($params instanceof \Exception) {
            $responseHeader = new ResponseHeader();
            $responseHeader->setTimestamp(date('Y-m-d'));
            $responseHeader->setCode(0);
            $responseHeader->setRoute($header->getRoute());
            if ($header->getRequestId()) {
                $responseHeader->setRequestId($header->getRequestId());
            }
            if ($params->getCode() > 0) {
                $responseHeader->setCode($params->getCode());
            } else {
                $responseHeader->setCode(999);
            }
            $responseHeader->setMsg($params->getMessage());
            return [$responseHeader, false];
        }
        $this->_handleRequest = $request;
        $this->requestedRoute = $header->getRoute();
        $result = $this->runAction($header, $params);
        return $result;
    }

    /**
     * @param string $route
     * @param string $version
     * @return string
     */
    public function getResource($route, $version = 1)
    {
        $parts = explode('.', $route);
        $version = 'v' . $version;
        if (count($parts) == 2) {
            $path = $parts[0];
            $fileName = $parts[1];
            if (isset($this->resources[$path])) {
                return $this->resources[$path] . '\\' . $version . '\\' . $fileName;
            } else {
                Exception::resourceNotFound();
            }
        } else {
            Exception::invalidRequestRoute();
        }
    }

    /**
     * @param \swoole_server $server
     * @param $worker_id
     */
    public function onWorkerStart(\swoole_server $server, $worker_id)
    {
        parent::onWorkerStart($server, $worker_id);
    }

    /**
     * Runs a controller action specified by a route.
     * This method parses the specified route and creates the corresponding child module(s), controller and action
     * instances. It then calls [[Controller::runAction()]] to run the action with the given parameters.
     * If the route is empty, the method will use [[defaultRoute]].
     * @param string $route the route that specifies the action.
     * @param array $params the parameters to be passed to the action
     * @return array
     */
    public function runAction($route, $params = [])
    {
        $timeStart = microtime(true);
        /** @var \message\common\Header $header */
        $header = $route;
        $methodName = 'run';
        $responseHeader = new ResponseHeader();
        $responseHeader->setTimestamp(date('Y-m-d'));
        $responseHeader->setCode(0);
        $responseHeader->setRoute($header->getRoute());
        $data = false;

        try {
            $className = $this->getResource($header->getRoute());
            /** @var  \framework\resources\ApiAbstract $model */
            $model = new $className();
            if (method_exists($model, $methodName)) {
                /** @var \framework\protocolbuffers\Message $data */
                $model->setHeader($header);
                $model->setRequest($this->_handleRequest);
                // 调用方法
                $data = $model->$methodName($params);
            } else {
                Exception::invalidRequestRoute();
            }
        } catch (\PDOException $e) {
            $responseHeader->setCode(Exception::SERVICE_NOT_AVAILABLE);
            $responseHeader->setMsg(Exception::SERVICE_NOT_AVAILABLE_TEXT);
            $this->logException($e);
        } catch (\yii\db\Exception $e) {
            $responseHeader->setCode(Exception::SERVICE_NOT_AVAILABLE);
            $responseHeader->setMsg(Exception::SERVICE_NOT_AVAILABLE_TEXT);
            $this->logException($e);
        } catch (\yii\base\ErrorException $e) {
            $responseHeader->setCode(Exception::SERVICE_NOT_AVAILABLE);
            $responseHeader->setMsg(Exception::SERVICE_NOT_AVAILABLE_TEXT);
            ToolsAbstract::logError($e);
        } catch (\Exception $e) {
            if ($e->getCode() > 0) {
                $responseHeader->setCode($e->getCode());
                $responseHeader->setMsg($e->getMessage());
            } else {
                $responseHeader->setCode(Exception::SERVICE_NOT_AVAILABLE);
                $responseHeader->setMsg(Exception::SERVICE_NOT_AVAILABLE_TEXT);
            }
            $this->logException($e);
        } catch (\Error $e) {
            $responseHeader->setCode(Exception::SERVICE_NOT_AVAILABLE);
            $responseHeader->setMsg(Exception::SERVICE_NOT_AVAILABLE_TEXT);
            ToolsAbstract::logError($e);
        }

        $timeEnd = microtime(true);
        $elapsed = $timeEnd - $timeStart;

        //Timeline::get()->report($header->getRoute(), 'runAction', ENV_SYS_NAME, $elapsed, $responseHeader->getCode());

        //返回当前服务器的时间戳
        $responseHeader->setTimestamp(date('Y-m-d'));
        return [$responseHeader, $data];
    }

    /**
     * @inheritdoc
     */
    protected function initServer(\swoole_server $server)
    {
        return parent::initServer($server);
    }

    /**
     * @inheritdoc
     */
    protected function parseServerConfig(array $config)
    {
        $serverConfig = \Yii::$app->params['soa_server_config'];
        if (!is_array($serverConfig)) {
            throw new \Exception('invalid ip port config');
        }

        $this->serverConfig = $serverConfig;
        $this->host = ENV_SERVER_IP;
        $this->port = ENV_SERVER_PORT;
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
}