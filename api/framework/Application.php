<?php

namespace framework;

use framework\Tool;

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
    protected $host;
    protected $port;

    public function handleRequest($request)
    {
        // TODO: Implement handleRequest() method.
    }

    public function getResource($route)
    {
        if(empty($route)){
            Exception::throwException(Exception::RESOURCE_NOT_FOUND);
        }

        $parts = array_filter(explode('/', $route));
        $parts = array_values($parts);
        if (count($parts) != 2) {
            Exception::throwException(Exception::RESOURCE_NOT_FOUND);
        }

        $path = $parts[0];
        $fileName = $parts[1];
        $class = "service\\controllers\\".$path."\\" . $fileName;

        if(!class_exists($class)){
            Exception::throwException(Exception::RESOURCE_NOT_FOUND);
        }
        return $class;
    }

    public function onClose($server, $fd)
    {
        $this->log("Client: Close.");
    }

    public function onFinish(\swoole_http_server $server,$task_id,$src_worker_id,$data)
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
            swoole_set_process_name(self::getProcessNamePrefix() . ':Master(HTTP)-' . $server->master_pid);
        } catch (\Exception $e) {

        }
    }

    public function onWorkerStart(\swoole_server $server, $worker_id)
    {
        if ($server->taskworker) {
            try {
                swoole_set_process_name(self::getProcessNamePrefix() . ':Task Worker(HTTP)-' . $worker_id);
            } catch (\Exception $e) {

            }
        } else {

            \Yii::$app->set('soa_server', $server);

            try {
                swoole_set_process_name(self::getProcessNamePrefix() . ':Worker(HTTP)-' . $worker_id);
            } catch (\Exception $e) {

            }
        }
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
        Tool::log($data);
    }

    public static function getProcessNamePrefix()
    {
        return 'pintuan ' . ENV_SYS_NAME . ' Server';
    }
}