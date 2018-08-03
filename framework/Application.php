<?php

namespace framework;

use framework\components\crontab\Parser;
use framework\components\crontab\Task;
use framework\components\es\Collectd;
use framework\components\es\Console;
use framework\components\es\Timeline;
use framework\components\ProxyAbstract;
use framework\components\ToolsAbstract;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 16:19
 */

/**
 * Class Application
 * @package framework
 */
class Application extends \yii\base\Application
{
    protected $serverConfig = [];
    /**
     * @var \swoole_server
     */
    protected $server;
    protected $processes = [];
    protected $host;
    protected $hostV6;
    protected $port;
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
    public $taskResources = [];
    const OPTION_APPCONFIG = 'appconfig';
    /**
     * @var Request
     */
    protected $_handleRequest;

    /**
     * @param \yii\base\Request $request
     * @return array
     */
    public function handleRequest($request)
    {
        /** @var \service\message\common\Header $header */
        list ($header, $params) = $request->resolve();
        if ($params instanceof \Exception) {
            $responseHeader = new ResponseHeader();
            $responseHeader->setTimestamp(date('Y-m-d H:i:s'));
            $responseHeader->setCode(0);
            $responseHeader->setRoute($header->getRoute());
            $responseHeader->setEncrypt($header->getEncrypt());
            $responseHeader->setEncryptVersion($header->getEncryptVersion());
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
        /** @var \service\message\common\Header $header */
        $header = $route;
        $methodName = 'run';
        $responseHeader = new ResponseHeader();
        $responseHeader->setTimestamp(date('Y-m-d H:i:s'));
        $responseHeader->setCode(0);
        $responseHeader->setRoute($header->getRoute());
        $responseHeader->setEncrypt($header->getEncrypt());
        $responseHeader->setEncryptVersion($header->getEncryptVersion());
        $responseHeader->setProtocol($header->getProtocol());
        $data = false;
        if ($header->getRequestId()) {
            $responseHeader->setRequestId($header->getRequestId());
        }
        $this->log($header->getTraceId());
        try {

            $className = $this->getResource($header->getRoute(), $header->getVersion());
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
            $this->logException($e, $header->getTraceId());
        } catch (\yii\db\Exception $e) {
            $responseHeader->setCode(Exception::SERVICE_NOT_AVAILABLE);
            $responseHeader->setMsg(Exception::SERVICE_NOT_AVAILABLE_TEXT);
            $this->logException($e, $header->getTraceId());
        } catch (\Exception $e) {
            if ($e->getCode() > 0) {
                $responseHeader->setCode($e->getCode());
            } else {
                $responseHeader->setCode(999);
            }
            $responseHeader->setMsg($e->getMessage());
            $this->logException($e, $header->getTraceId());
        } catch (\Error $e) {
            if ($e->getCode() > 0) {
                $responseHeader->setCode($e->getCode());
            } else {
                $responseHeader->setCode(888);
            }
            $responseHeader->setMsg($e->getMessage());
            ToolsAbstract::logError($e);
        }
        $timeEnd = microtime(true);
        $elapsed = $timeEnd - $timeStart;
        Timeline::get()->report($header->getRoute(), 'runAction', ENV_SYS_NAME, $elapsed, $responseHeader->getCode(), $header->getTraceId(), $header->getRequestId());
        return [$responseHeader, $data];
    }

    /**
     * @param Task $task
     */
    public function handleTask(Task $task)
    {
        $this->runTask($task);
    }

    public function runTask(Task $task)
    {
        $timeStart = microtime(true);
        try {
            $className = $this->getTaskResource($task->getRoute());
            /** @var  \framework\tasks\TaskInterface $model */
            $model = new $className();
            if (method_exists($model, 'run')) {
                $model->run($task->getParams());
            } else {
                Exception::invalidRequestRoute();
            }
        } catch (\PDOException $e) {
            // 记录调试信息
            ToolsAbstract::logException($e);
        } catch (\yii\db\Exception $e) {
            ToolsAbstract::logException($e);
        } catch (\Exception $e) {
            ToolsAbstract::logException($e);
        } catch (\Error $e) {
            ToolsAbstract::logError($e);
        }
        $timeEnd = microtime(true);
        $elapsed = $timeEnd - $timeStart;
        Timeline::get()->report($task->getRoute(), 'runTask', ENV_SYS_NAME, $elapsed, 0, 0, 0);
        return true;
    }

    public function getResource($route, $version)
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
     * get task resource
     * @param $route
     * @return string
     */
    public function getTaskResource($route)
    {
        $parts = explode('.', $route);
        if (count($parts) == 2) {
            $path = $parts[0];
            $fileName = $parts[1];
            if (isset($this->taskResources[$path])) {
                return $this->taskResources[$path] . '\\' . $fileName;
            } else {
                Exception::resourceNotFound();
            }
        } else {
            Exception::invalidRequestRoute();
        }
    }

    /**
     * Returns the request component.
     * @return Request|object|null the request component.
     */
    public function getRequest()
    {
        return $this->get('request');
    }

    public function onConnect($server, $fd)
    {
        $this->log("Client:Connect. client id: " . $fd);
    }

    public function onClose($server, $fd)
    {
        $this->log("Client: Close.");
    }

    public function onFinish(\swoole_server $server, $task_id, $data)
    {
        $this->log("Task#$task_id finished, data_len=" . strlen($data));
    }

    public function onShutdown(\swoole_server $server)
    {
        $this->log('Server shutdown');
    }

    public function onWorkerStop(\swoole_server $server, $worker_id)
    {
        $this->log("Worker stop:{$worker_id}");
    }

    public function onWorkerError(\swoole_server $server, $worker_id, $worker_pid, $exit_code)
    {
        $this->log("onWorkerError.worker_id:{$worker_id},worker_pid:{$worker_pid},exit_code:{$exit_code}");
    }

    public function onManagerStop(\swoole_server $server)
    {
        $this->log('onManagerStop');
    }

    public function onStart(\swoole_server $server)
    {
        try {
            swoole_set_process_name(self::getProcessNamePrefix() . ':Master-' . $server->master_pid);
        } catch (\Exception $e) {

        }
        $this->log("MasterPid={$server->master_pid}|Manager_pid={$server->manager_pid}");
        $this->log("Server: start.Swoole version is [" . SWOOLE_VERSION . "]");
    }

    public function onWorkerStart(\swoole_server $server, $worker_id)
    {
        if ($worker_id == 0) {
            if (ToolsAbstract::getSysName() !== 'route') {
                $this->registerLocalService();
            }
            $this->reload($server);
            $this->collectd($server);
            if (ENV_CRONTAB_STATUS) {
                $this->scheduleTask($server);
            }
            /**  此数组中的文件表示进程启动前就加载了，所以无法reload **/
        }
        ToolsAbstract::log(get_included_files());
        if ($server->taskworker) {
            try {
                swoole_set_process_name(self::getProcessNamePrefix() . ':Task Worker-' . $worker_id);
            } catch (\Exception $e) {

            }
        } else {
            try {
                swoole_set_process_name(self::getProcessNamePrefix() . ':Worker-' . $worker_id);
            } catch (\Exception $e) {

            }
        }
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
     * 计划任务
     * @param \swoole_server $server
     * @return integer
     */
    protected function scheduleTask(\swoole_server $server)
    {
        try {
            swoole_timer_tick(1000, function () use ($server) {
                $taskKey = ToolsAbstract::getCrontabKey();
                $redis = ToolsAbstract::getRedis();
                //处理redis中的定时任务列表
                $time = time(); //比对时间时间戳
                ToolsAbstract::log($time, 'tick.log');
                $list = $redis->hGetAll($taskKey);
                if (empty($list)) {
                    return false;
                }
                $second = intval(date("s", $time));
                foreach ($list as $routeKey => $json) {
                    $taskData = json_decode($json, true);
                    if (empty($taskData)) {
                        $redis->hDel($taskKey, $routeKey);
                        continue;
                    }
                    //检查任务时间格式，返回 false/null/array
                    $validate = Parser::parse($taskData['time'], $time);

                    if (!empty($validate) && isset($validate[$second])) {
                        if ($taskData['type'] == 1) {
                            //移除一次执行的任务
                            $redis->hDel($taskKey, $routeKey);
                        }
                        $task = new Task($taskData['data']);
                        $server->task($task);
//                        ToolsAbstract::log($row_arr['data']);
                    }
                }
                unset($list);
                return 0;
            });
        } catch (\Exception $e) {
            ToolsAbstract::logException($e);
            $this->scheduleTask($server);
        }
        return 0;
    }


    protected function collectd(\swoole_server $server)
    {
        swoole_timer_tick(30000, function () use ($server) {
            Collectd::get()->report('connections_count', count($server->connections));
        });
    }

    protected function reload(\swoole_server $server)
    {
        $file = \Yii::getAlias('@service') . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'reload';
        swoole_timer_tick(10000, function () use ($server, $file) {
            if (!file_exists($file)) {
                file_put_contents($file, 0);
                chmod($file, 0777);
                $this->log('Reset reload file.');
            } else {
                $data = file_get_contents($file);
                if (intval($data) === 1) {
                    $this->log('Reload command received,server begin to reload.');
                    $server->reload();
                    file_put_contents($file, 0);
                    $this->log('Reset reload file.');
                }
            }
        });
    }

    public function onManagerStart(\swoole_server $server)
    {
        try {
            swoole_set_process_name(self::getProcessNamePrefix() . ':Manager Process-' . $server->manager_pid);
        } catch (\Exception $e) {

        }
        $this->log('onManagerStart-' . $server->manager_pid);
    }

    protected function log($data)
    {
        ToolsAbstract::log($data);
        Console::get()->log($data);
    }

    protected function logException(\Exception $e, $traceId = null)
    {
        ToolsAbstract::logException($e);
        Console::get()->logException($e, $traceId);
    }

    public static function getProcessNamePrefix()
    {
        return 'pintuan ' . ENV_SYS_NAME . ' Server';
    }
}