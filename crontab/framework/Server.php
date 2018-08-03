<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 17:06
 */

namespace framework;

use framework\core\TaskServer;

class Server
{
    private $allow_cmd = ['reload', 'restart', 'stop', 'start', 'status'];
    private $get_master_cmd = "ps -eaf|grep \"pintuan " . ENV_SYS_NAME . " Server:Master\" | grep -v \"grep\"| awk '{print $2}'";
    private $config = null;
    private $cmd = null;

    public function __construct($config = [], $argc = null, $argv = [])
    {
        if (empty($config)) {
            exit;
        }

        if ($argc != 2 || (isset($argv[1]) && !in_array($argv[1], $this->allow_cmd))) {
            echo "- 请输入具体操作：" . PHP_EOL;
            echo "    reload:重启所有worker" . PHP_EOL;
            echo "    restart:重启服务" . PHP_EOL;
            echo "    stop:停止服务" . PHP_EOL;
            echo "    status:服务状态" . PHP_EOL;
            exit;
        }

        $this->config = $config;
        $this->cmd = $argv[1];
    }

    public function init()
    {
        echo ENV_PROJECT_NAME . '=>>>>>>>>' . ENV_SYS_NAME;
        echo PHP_EOL;
        try {
            switch ($this->cmd) {
                case 'reload':
                    $this->reload();
                    break;
                case 'restart':
                    $this->restart();
                    break;
                case 'stop':
                    $this->stop();
                    break;
                case 'start':
                    $this->start();
                    break;
                case 'status':
                    $this->status();
                    break;
                default:
                    echo "参数错误" . PHP_EOL;
                    break;
            }
        } catch (\Error $e) {
            echo $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function start()
    {
        echo "服务正在启动..." . PHP_EOL;
        $master_pid = shell_exec($this->get_master_cmd);
        if ($master_pid) {
            echo "服务已经在运行，无需再次启动" . PHP_EOL;
        } else {
            echo "服务启动成功" . PHP_EOL;
            $application = new TaskServer($this->config);
            $application->serve();
        }

    }

    private function status()
    {
        $status_cmd = "ps -eaf|grep \"pintuan " . ENV_SYS_NAME . " Server\" | grep -v \"grep\"";
        $status = shell_exec($status_cmd);
        if (empty($status)) {
            echo "服务未启动" . PHP_EOL;
        } else {
            print_r($status);
        }

    }

    private function restart()
    {
        $master_pid = shell_exec($this->get_master_cmd);

        if ($master_pid) {
            echo "服务正在停止..." . PHP_EOL;
            $reload_cmd = "kill -TERM {$master_pid}";
            shell_exec($reload_cmd);
            sleep(3);
            $master_pid = shell_exec($this->get_master_cmd);
            if (!$master_pid) {
                echo "服务已经停止..." . PHP_EOL;
            } else {
                echo "服务停止失败..." . PHP_EOL;
                exit;
            }
        } else {
            echo "服务未启动" . PHP_EOL;
        }
        $this->start();
    }

    private function stop()
    {
        $master_pid = shell_exec($this->get_master_cmd);

        if ($master_pid) {
            $reload_cmd = "kill -TERM {$master_pid}";
            shell_exec($reload_cmd);
            echo "服务已经停止..." . PHP_EOL;
        } else {
            echo "服务未启动" . PHP_EOL;
        }
    }

    private function reload()
    {
        $worker_pid_cmd = "ps -eaf|grep \"pintuan " . ENV_SYS_NAME . " Server\" | grep \"Worker\" | grep -v \"grep\" | head -1 | awk '{print $2}'";
        $master_pid = shell_exec($this->get_master_cmd);
        $worker_pid = shell_exec($worker_pid_cmd);

        if ($master_pid) {
            $reload_cmd = "kill -USR1 {$master_pid}";
            shell_exec($reload_cmd);
            echo "worker正在重启..." . PHP_EOL;
            $worker_pid_reload = shell_exec($worker_pid_cmd);
            sleep(3);
            if ($worker_pid == $worker_pid_reload) {
                echo "服务重启失败..." . PHP_EOL;
            } else {
                echo "服务重启成功..." . PHP_EOL;
            }

        } else {
            echo "服务未启动" . PHP_EOL;
        }
    }
}