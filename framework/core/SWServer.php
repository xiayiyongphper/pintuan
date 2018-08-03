<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/25
 * Time: 11:59
 */

namespace framework\core;


use framework\components\es\Console;
use framework\components\ToolsAbstract;
use framework\Consul;
use yii\base\Application;
use framework\resources\ApiAbstract;

/**
 * SWServer，YII2与swoole的结合。
 * @package framework\core
 * @property SWRequest $request The request component. This property is read-only.
 * @method SWRequest getRequest() Returns the request component.
 * @method SWResponse getResponse() Returns the response component.
 */
abstract class SWServer extends Application
{
    /**
     * 配置
     * @var array
     */
    protected $serverConfig = [];
    /**
     * @var \swoole_server
     */
    protected $server;
    /**
     * @var string
     */
    protected $host;
    /**
     * @var int
     */
    protected $port;
    /**
     * @var ServerWokerInterface[]
     */
    protected $userServerWorkers = [];
    /**
     * @var ProcessInterface[]
     */
    protected $userProcesses = [];

    /**
     * SWServer constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->initUserWorkersAndProcesses();
        $this->parseServerConfig($config);
    }

    /**
     * 初始化用户自定义的worker和process
     */
    private function initUserWorkersAndProcesses()
    {
        if (isset($this->params['custom_processes'])) {
            if (!is_array($this->params['custom_processes'])) {
                throw new \Exception('invalid custom_processes config');
            }
            foreach ($this->params['custom_processes'] as $cpKey => $custom_process) {
                $tmp = \Yii::createObject($custom_process);
                if (!$tmp instanceof ProcessInterface) {
                    throw new \Exception('invalid custom_processes config');
                }
                $this->userProcesses[$cpKey] = $tmp;
            }
        }

        if (isset($this->params['custom_workers'])) {
            if (!is_array($this->params['custom_workers'])) {
                throw new \Exception('invalid custom_workers config');
            }
            foreach ($this->params['custom_workers'] as $cwKey => $custom_worker) {
                $tmp = \Yii::createObject($custom_worker);
                if (!$tmp instanceof ServerWokerInterface) {
                    throw new \Exception('invalid custom_workers config');
                }
                $this->userServerWorkers[$cwKey] = $tmp;
            }
        }
    }

    /**
     * 有新的连接进入时，在worker进程中回调。
     *
     * @param \swoole_server $server
     * @param int $fd 连接的文件描述符，发送数据/关闭连接时需要此参数
     */
    public function onConnect(\swoole_server $server, $fd)
    {
        $this->callUserServerWorkerHooks($server, __FUNCTION__, func_get_args());
        $this->log("Client:Connect. client id: " . $fd);
    }

    /**
     * TCP客户端连接关闭后，在worker进程中回调此函数。
     *
     * @param \swoole_server $server
     * @param int $fd 连接的文件描述符，发送数据/关闭连接时需要此参数
     */
    public function onClose(\swoole_server $server, $fd)
    {
        $this->callUserServerWorkerHooks($server, __FUNCTION__, func_get_args());
        $this->log("Client: Close.");
    }

    /**
     * 当worker进程投递的任务在task_worker中完成时，task进程会通过swoole_server->finish()方法将任务处理的结果发送给worker进程。
     * task进程的onTask事件中没有调用finish方法或者return结果，worker进程不会触发onFinish。
     * 执行onFinish逻辑的worker进程与下发task任务的worker进程是同一个进程
     *
     * @param \swoole_server $server
     * @param int $task_id 任务的ID
     * @param string|SWResponse $data 任务处理的结果内容
     */
    public function onFinish(\swoole_server $server, $task_id, $data)
    {
        $this->callUserServerWorkerHooks($server, __FUNCTION__, func_get_args());
        $this->log("Task#$task_id finished");
        $this->log("Task data#$data finished");
    }

    /**
     * 此事件在Server结束时发生。
     * 强制kill进程不会回调onShutdown，如kill -9
     * 需要使用kill -15来发送SIGTREM信号到主进程才能按照正常的流程终止。
     *
     * @param \swoole_server $server
     */
    public function onShutdown(\swoole_server $server)
    {
        $this->log('Server shutdown');
    }

    /**
     * 此事件在worker进程终止时发生。在此函数中可以回收worker进程申请的各类资源。
     *
     * @param \swoole_server $server
     * @param int $worker_id worker进程的ID
     */
    public function onWorkerStop(\swoole_server $server, $worker_id)
    {
        $this->callUserServerWorkerHooks($server, __FUNCTION__, func_get_args());
        $this->log("Worker stop:{$worker_id}");
    }

    /**
     * 当worker/task_worker进程发生异常后会在Manager进程内回调此函数。
     *
     * @param \swoole_server $server
     * @param int $worker_id 异常进程的编号
     * @param int $worker_pid 异常进程的ID
     * @param int $exit_code 退出的状态码，范围是 1 ～255
     */
    public function onWorkerError(\swoole_server $server, $worker_id, $worker_pid, $exit_code)
    {
        $this->log("onWorkerError.worker_id:{$worker_id},worker_pid:{$worker_pid},exit_code:{$exit_code}");
    }

    /**
     * 当管理进程启动时调用它。
     * @param \swoole_server $server
     */
    public function onManagerStart(\swoole_server $server)
    {
        try {
            swoole_set_process_name(self::getProcessNamePrefix() . ':Manager Process-' . $server->manager_pid);
        } catch (\Exception $e) {

        }
        $this->log('onManagerStart-' . $server->manager_pid);
    }

    /**
     * 当管理进程结束时调用它。
     *
     * @param \swoole_server $server
     */
    public function onManagerStop(\swoole_server $server)
    {
        $this->log('onManagerStop');
    }

    /**
     * Server启动在主进程的主线程回调此函数。
     * onStart回调中，仅允许echo、打印Log、修改进程名称。不得执行其他操作。
     * onWorkerStart和onStart回调是在不同进程中并行执行的，不存在先后顺序。
     * @param \swoole_server $server
     */
    public function onStart(\swoole_server $server)
    {
        try {
            swoole_set_process_name(self::getProcessNamePrefix() . ':Master-' . $server->master_pid);
        } catch (\Exception $e) {
            $this->logException($e);
        }
        $this->log("MasterPid={$server->master_pid}|Manager_pid={$server->manager_pid}");
        $this->log("Server: start.Swoole version is [" . SWOOLE_VERSION . "]");
    }

    /**
     * 此事件在worker进程/task进程启动时发生。这里创建的对象可以在进程生命周期内使用。
     * swoole1.6.11之后task_worker中也会触发onWorkerStart。
     * 可以将公用的，不易变的php文件放置到onWorkerStart之前。
     * 这样虽然不能重载入代码，但所有worker是共享的，不需要额外的内存来保存这些数据。
     * onWorkerStart之后的代码每个worker都需要在内存中保存一份
     *
     * @param \swoole_server $server
     * @param int $worker_id
     */
    public function onWorkerStart(\swoole_server $server, $worker_id)
    {
        try {
            ApiAbstract::setWorkerId($worker_id);

            if (extension_loaded('zend opcache') && ini_get('opcache.enable_cli')) {
                opcache_reset();
            }

            if ($server->taskworker) {
                swoole_set_process_name(self::getProcessNamePrefix() . ':Task Worker-' . $worker_id);
            } else {
                swoole_set_process_name(self::getProcessNamePrefix() . ':Worker-' . $worker_id);
            }
        } catch (\Exception $e) {
            // NOTHING TO DO
        }
        $this->callUserServerWorkerHooks($server, __FUNCTION__, func_get_args());
    }

    /**
     * 运行服务
     */
    public function serve()
    {
        if (empty($this->host) || empty($this->port) || empty($this->serverConfig)) {
            throw new \Exception('配置信息错误');
        }

        // 如果host为ip，则为TCP，不然为UNIX SOCKET
        if (filter_var($this->host, FILTER_VALIDATE_IP) !== false) {
            $server = new \swoole_server($this->host, $this->port, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);


        } else {
            $server = new \swoole_server($this->host, $this->port, SWOOLE_PROCESS, SWOOLE_SOCK_UNIX_STREAM);
        }

        $this->server = $server;
        $this->initServer($server);
        $server->set($this->serverConfig);
        $this->initServerCallBack($server);
        $this->consulService();// 执行consul 写入服务
        $this->beforeStart($server);

        $server->start();
    }

    /**
     * 初始化swoole_server回调函数
     *
     * @param \swoole_server $server
     * @return true
     */
    protected function initServerCallBack(\swoole_server $server)
    {
        $server->on('connect', [$this, 'onConnect']);
        $server->on('receive', [$this, 'onReceive']);
        $server->on('close', [$this, 'onClose']);
        $server->on('task', [$this, 'onTask']);
        $server->on('finish', [$this, 'onFinish']);
        $server->on('start', [$this, 'onStart']);
        $server->on('workerstart', [$this, 'onWorkerStart']);
        $server->on('workerstop', [$this, 'onWorkerStop']);
        $server->on('shutdown', [$this, 'onShutdown']);
        $server->on('workererror', [$this, 'onWorkerError']);
        $server->on('ManagerStart', [$this, 'onManagerStart']);
        $server->on('ManagerStop', [$this, 'onManagerStop']);
        return true;
    }

    /**
     * 解析配置信息
     * @param array $config
     */
    abstract protected function parseServerConfig(array $config);

    /**
     * 执行$server->start()前执行的动作
     *
     * @param \swoole_server $server
     * @return true
     */
    protected function beforeStart(\swoole_server $server)
    {
        return true;
    }

    /**
     * 这里new \swoole_server()后的其他初始化操作，比如$server->addListener();、$server->addProcess();
     *
     * @param \swoole_server $server
     * @return bool
     */
    protected function initServer(\swoole_server $server)
    {
        $that = $this;
        foreach ($this->userProcesses as $upKey => $userProcess) {
            $process = new \swoole_process(function (\swoole_process $p) use ($that, $upKey, $userProcess) {
                try {
                    swoole_set_process_name(static::getProcessNamePrefix() . ':' . $upKey . '-' . $p->pid);
                } catch (\Exception $e) {
                    // NOTHING TO DO
                }
                $userProcess->run($that, $p);
            });
            $server->addProcess($process);
        }
    }

    /**
     * 调用用户自定义的worker方法
     *
     * @param string $method
     * @param array $args
     */
    protected function callUserServerWorkerHooks(\swoole_server $server, $method, $args = [])
    {
        /* taskworker不执行 */
        if (!$server->taskworker) {
            foreach ($this->userServerWorkers as $userServerWorker) {
                try {
                    $args[0] = $this;
                    call_user_func_array([$userServerWorker, $method], $args);
                } catch (\Exception $e) {
                    $this->logException($e);
                } catch (\Error $e) {
                    $this->log($e->__toString());
                }
            }
        }
    }

    /**
     * 接收到数据时回调此函数，发生在worker进程中。
     *
     * @param \swoole_server $server
     * @param int $fd TCP客户端连接的唯一标识符
     * @param int $from_id TCP连接所在的Reactor线程ID
     * @param string $data 收到的数据内容，可能是文本或者二进制内容
     */
    abstract public function onReceive(\swoole_server $server, $fd, $from_id, $data);

    /**
     * 记录日志
     *
     * @param mixed $data
     */
    protected function log($data)
    {
        try {
            ToolsAbstract::log($data, 'server.log');
        } catch (\Exception $e) {
            // NOTHING TO DO
        }
    }

    /**
     * 记录异常日志
     *
     * @param \Exception $e
     */
    protected function logException(\Exception $e)
    {
        try {
            ToolsAbstract::logException($e);
        } catch (\Exception $e) {
            // NOTHING TO DO
        }
    }

    /**
     * 获取进程前缀
     *
     * @return string
     */
    public static function getProcessNamePrefix()
    {
        return 'pintuan ' . ENV_SYS_NAME . ' Server';
    }

    /**
     * @return \swoole_server
     */
    public function getServer()
    {
        return $this->server;
    }

    protected function consulService()
    {
        (new Consul())->consulService();// 执行consul 写入服务
    }

}