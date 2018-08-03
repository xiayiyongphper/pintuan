<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/8
 * Time: 14:29
 */

namespace framework;

use framework\components\ToolsAbstract;
use SensioLabs\Consul\ServiceFactory;
use SensioLabs\Consul\Services\KV;
use service\tools\Tools;


class Consul
{
    private $consul;
    private $serviceName;
    private $options;

    private static $_instance = null;

    const SEPARATOR_CHARACTER = "-"; //分隔符

    public function __construct()
    {
        if (!defined('ENV_SERVER')) {
            echo '必须配置为灰度或正式';
            exit();
        }

        if (!defined('ENV_PROJECT_NAME')) {
            echo '必须配置项目名称';
            exit();
        }

        if (!defined('ENV_SYS_NAME')) {
            echo '必须配置模块名称';
            exit();
        }

        if (!defined('ENV_SERVER_IP')) {
            echo '必须配置模块所在服务器内网ip';
            exit();
        }

        if (!defined('ENV_CONSUL_IP')) {
            define('ENV_CONSUL_IP', '127.0.0.1');
        }

        if (!defined('ENV_CONSUL_PORT')) {
            define('ENV_CONSUL_PORT', 8500);
        }

        $this->options = [
            'base_uri' => 'http://' . ENV_CONSUL_IP . ':' . ENV_CONSUL_PORT
        ];

        $this->serviceName = ENV_PROJECT_NAME . self::SEPARATOR_CHARACTER . ENV_SERVER . self::SEPARATOR_CHARACTER . ENV_SYS_NAME;

        $this->consul = self::getInstance($this->options);
    }

    //用于访问类的实例的公共的静态方法
    private static function getInstance($options)
    {
        if (!(self::$_instance instanceof ServiceFactory)) {
            self::$_instance = new ServiceFactory($options);
        }
        return self::$_instance;
    }

    /**
     * 先查询服务，再注册服务，一次性注册多个，由
     * @return bool
     * @internal param string $serviceName
     */
    public function consulService()
    {
        $this->registerService($this->serviceName, ENV_SERVER_IP, ENV_SERVER_PORT); // 注册服务
        return true;
    }

    public function consulKeyValue()
    {
        /** @var KV $kv */
        $kv = $this->consul->get("kv");
        $config_key = ENV_PROJECT_NAME . '-' . ENV_SERVER;
        $res = $kv->get($config_key, ['recurse' => true, 'raw' => true]);
        $result = json_decode($res->getBody(), true);
        foreach ($result as $config) {
            $key = str_replace($config_key . '/', '', $config['Key']);
            $value = base64_decode($config['Value']);
            define($key, $value);
        }
        return true;
    }


    /**
     * 注册服务
     * @param $serviceName
     * @param $ip ip
     * @param $port 端口
     * @return bool
     * @internal param 服务名 $serverName
     */
    private function registerService($serviceName, $ip, $port)
    {
        $serviceInfo = [
            'ID' => $serviceName,
            'Name' => $serviceName,
            'Tags' => [$serviceName],
            'Address' => $ip,
            'Port' => (int)$port,
            'EnableTagOverride' => true,
            'Check' => [
                "DeregisterCriticalServiceAfter" => "1m", //check失败后1分钟删除本服务
                "TCP" => $ip . ':' . (int)$port,
                "Interval" => "10s",
                "status" => "passing"
            ]
        ];
        $this->consul->get("agent")->registerService($serviceInfo)->getBody(); // 成功 返回 ''
        return true;
    }
}