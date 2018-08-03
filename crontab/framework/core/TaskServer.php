<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/25
 * Time: 18:19
 */

namespace framework\core;

use framework\components\Pack;
use framework\components\ToolsAbstract;
use framework\Exception;
use framework\message\Message;

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
        //receive需要解包，必须要request
        /** @var Message $message */
        $message = Pack::unpack($data);
        $message->setFd($fd);
        $server->task($message);
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
        return parent::onWorkerStart($server, $worker_id);
    }

    /**
     * @inheritdoc
     */
    protected function initServer(\swoole_server $server)
    {
        return parent::initServer($server);
    }

    /**
     * @param \swoole_server $server
     * @param int $task_id
     * @param int $from_id
     * @param Message $message
     */
    public function onTask(\swoole_server $server, $task_id, $from_id, $message)
    {
        try {
            $fd = $message->getFd();
            $this->state = self::STATE_BEFORE_REQUEST;
            $this->trigger(self::EVENT_BEFORE_REQUEST);
            $this->state = self::STATE_HANDLING_REQUEST;
            $response = $this->handleRequest($message);

            $this->state = self::STATE_AFTER_REQUEST;
            $this->trigger(self::EVENT_AFTER_REQUEST);

            $this->state = self::STATE_SENDING_RESPONSE;
            $server->send($fd, Pack::pack($response));
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
     * @param Message $message
     * @return \Exception|mixed|TaskResponse|\Throwable
     */
    public function handleRequest($message)
    {
        try {
            $route = $message->getRoute();
            $params = $message->getParams();
            $data = $this->runAction($route, $params);
        } catch (\Throwable $e) {
            $data = $e;
            ToolsAbstract::log($e->__toString());
            if ($e instanceof \Exception) {
                ToolsAbstract::logException($e);
            } else if ($e instanceof \Error) {
                ToolsAbstract::logError($e);
            }
        }

        if ($data instanceof \Throwable) {
            $response = new TaskResponse();
            $response->setCode($data->getCode());
            $response->setData($data);
        } else if ($data instanceof SWResponse) {
            $response = $data;
        } else {
            $response = new TaskResponse();
            $response->setCode(SWResponse::STATUS_OK);
            $response->setData($data);
        }

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
        ToolsAbstract::log($parts, 'getResource.log');
        if (!$parts || !is_array($parts) || !isset($this->taskResources[$parts[0]])) {
            throw new \Exception('invalid route');
        }

        $parts[0] = $this->taskResources[$parts[0]];
        return implode('\\', $parts);
    }
}