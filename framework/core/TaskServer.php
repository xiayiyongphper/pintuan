<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/25
 * Time: 18:19
 */

namespace framework\core;

use framework\components\es\Timeline;
use framework\components\ProxyAbstract;
use framework\components\ToolsAbstract;
use framework\Exception;
use service\message\common\ResponseHeader;

/**
 * 计划任务服务
 * @package framework\core
 */
class TaskServer extends SWServer
{
    public $tickTime = 1000;
    public $taskResources;
    /** @var SWRequest */
    protected $handleRequest;

    protected $localHost;
    protected $localPort;

    /**
     * @param \swoole_server $server
     * @param int $fd
     * @param int $from_id
     * @param string $data
     */
    public function onReceive(\swoole_server $server, $fd, $from_id, $data)
    {
        $request = $this->getRequest();
        $connection = $server->connection_info($fd, $from_id);
        $request->setRawBody($data)->setFd($fd)
            ->setRemote(true)->setRemoteIp($connection['remote_ip']);
        $request->getHeader()->setFd($fd);
        $server->task($request);
        $this->callUserServerWorkerHooks($server, __FUNCTION__, func_get_args());
    }

    /**
     * @param array $config
     * @throws \Exception
     */
    protected function parseServerConfig(array $config)
    {
        $serverConfig = \Yii::$app->params['soa_server_config'];
        if (!is_array($serverConfig)) {
            throw new \Exception('invalid ip port config');
        }
        $this->serverConfig = $serverConfig;
        $this->host = '/tmp/crontab.sock';
        $this->port = '9090';

        /* tick time */
        if (filter_var($this->tickTime, FILTER_VALIDATE_INT) === false || $this->tickTime <= 0) {
            $this->tickTime = 1000;
        }
    }

    /**
     * @param \swoole_server $server
     * @param $worker_id
     */
    public function onWorkerStart(\swoole_server $server, $worker_id)
    {
        if (defined('ENV_CRONTAB_STATUS') && ENV_CRONTAB_STATUS) {
            if (!$server->taskworker && $this->userServerWorkers) {
                $index = 0;
                $self = $this;
                foreach ($this->userServerWorkers as $worker) {
                    if ($index % $server->setting['worker_num'] == $worker_id) {
                        \swoole_timer_tick($this->tickTime,
                            function ($timerId, $params = null) use ($self, $worker_id, $worker) {
                                /* FIXME! call_user_func好像说有问题的？？ */
                                try {
                                    call_user_func([$worker, 'onTick'], $self, $worker_id, $timerId, $params);
                                } catch (\Exception $e) {
                                    ToolsAbstract::logException($e);
                                } catch (\Error $e) {
                                    ToolsAbstract::log($e->__toString());
                                }
                            });
                    }
                    $index++;
                }
            }
        }

        if ($worker_id == 0) {
            $this->registerLocalService();
        }

        return parent::onWorkerStart($server, $worker_id);
    }

    /**
     * @inheritdoc
     */
    protected function initServer(\swoole_server $server)
    {
        $server->addlistener($this->localHost, $this->localPort, SWOOLE_SOCK_TCP);
        return parent::initServer($server);
    }

    /**
     * 注册内部服务，用于内部系统路由
     */
    protected function registerLocalService()
    {
        try {
            $serviceMapping = \Yii::$app->params['service_mapping'];
            if (!is_array($serviceMapping)) {
                Exception::throwException('service_mapping配置有误！');
            }
            //使用路由的redis
            $redis = ToolsAbstract::getRouteRedis();
            foreach ($serviceMapping as $key => $services) {
                switch ($key) {
                    case ProxyAbstract::LOCAL:
                        //内部的路由不上报
                        $localServiceArray = [];
                        foreach ($services as $localService) {
                            if (isset($localService['module'], $localService['ip'], $localService['port'])) {
                                $localServiceArray[$localService['module']] = $localService['ip'] . ':' . $localService['port'];
                            } else {
                                Exception::throwException('service_mapping配置有误！');
                            }
                        }
                        if (count($localServiceArray) > 0) {
                            $redis->hMset('local.service', $localServiceArray);
                        }
                        break;
                    default:
                }
            }
        } catch (\Exception $e) {
            ToolsAbstract::logException($e);
        }
    }

    /**
     * @param \swoole_server $server
     * @param int $task_id
     * @param int $from_id
     * @param mixed $taskData
     */
    public function onTask(\swoole_server $server, $task_id, $from_id, $taskData)
    {

        if (!$taskData instanceof SWRequest) {
            ToolsAbstract::log('invalid request, it must be a instance of SWRequest');
            $server->finish('Task Finish!');
            return;
        }

        try {
            $this->state = self::STATE_BEFORE_REQUEST;
            $this->trigger(self::EVENT_BEFORE_REQUEST);

            $this->state = self::STATE_HANDLING_REQUEST;
            $response = $this->handleRequest($taskData);

            $this->state = self::STATE_AFTER_REQUEST;
            $this->trigger(self::EVENT_AFTER_REQUEST);

            $this->state = self::STATE_SENDING_RESPONSE;

            $response->send();
            $this->state = self::STATE_END;
            $server->finish($response);
            return;
        } catch (\Exception $e) {
            ToolsAbstract::logException($e);
        } catch (\Error $e) {
            ToolsAbstract::logError($e);
        }
        $server->finish('Task Finish!');
    }

    /**
     * @param SWRequest $request
     * @return SWResponse
     */
    public function handleRequest($request)
    {
        $timeStart = microtime(true);
        try {
            /** @var ResponseHeader $header */
            list ($header, $params) = $request->resolve();
            $this->handleRequest = $request;
            $this->requestedRoute = $header->getRoute();

            $result = $this->runAction($this->requestedRoute, $params);
        } catch (\Throwable $e) {
            $result = $e;
            ToolsAbstract::log($e->__toString());
            if ($e instanceof \Exception) {
                ToolsAbstract::logException($e);
            } else if ($e instanceof \Error) {
                ToolsAbstract::logError($e);
            }
        }

        $response = null;
        if ($result instanceof SWResponse) {
            $response = $result;
        } else {
            $header = isset($header) ? $header : new ResponseHeader();
            $response = $this->newResponseInstance($request, $header);
            if ($result instanceof \Throwable) {
                $response->setStatusCode($result->getCode() ? $result->getCode() : -1);
                $response->setData($result->getMessage());
            } else {
                $response->setStatusCode(SWResponse::STATUS_OK);
                $response->setData($result);
            }
        }

        /** @var SWResponse $response */
        try {
            $timeEnd = microtime(true);
            $elapsed = $timeEnd - $timeStart;
            Timeline::get()->report($this->requestedRoute, 'runAction', ENV_SYS_NAME, $elapsed, $response->getStatusCode());
        } catch (\Exception $e) {
            // nothing to do
        }
        return $response;
    }

    /**
     * @param SWRequest $request
     * @param ResponseHeader $header
     * @return SWResponse
     */
    protected function newResponseInstance(SWRequest $request, $header)
    {
        $response = clone $this->getResponse();
        $response->getHeader()->setFd($request->getHeader()->getFd());
        $response->getHeader()->setProtocol($header->getProtocol());
        $response->getHeader()->setEncrypt($header->getEncrypt());
        $response->getHeader()->setEncryptVersion($header->getEncryptVersion());
        return $response;
    }

    /**
     * @inheritdoc
     */
    public function runAction($route, $params = [])
    {
        $className = $this->getResource($route);
        if (!class_exists($className)) {
            Exception::resourceNotFound();
        }

        $serviceObj = new $className();
        if (!$serviceObj instanceof ServiceAbstract) {
            throw new \Exception('invalid route');
        }

        $serviceObj->setRequest($this->handleRequest);
        $result = null;
        if ($serviceObj->beforeAction($route, $params)) {
            $result = $serviceObj->run($params);
            $serviceObj->afterAction($route, $params);
        }
        return $result;
    }

    /**
     * @param string $route
     * @throws \Exception
     * @return string
     */
    protected function getResource($route)
    {
        $parts = explode('.', $route);
        if (!$parts || !is_array($parts) || !isset($this->taskResources[$parts[0]])) {
            throw new \Exception('invalid route');
        }

        $parts[0] = $this->taskResources[$parts[0]];
        return implode('\\', $parts);
    }
}