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


class Consul
{
    private $consul_ip;
    private $consul;
    private $consul_port = 8500;
    private static $config = [];
    //保存类的实例的静态成员变量
    private static $_instance=null;

    const SEPARATOR_CHARACTER = "-"; //分隔符
    const SEPARATOR_CHARACTER_MSG = "_"; // msg 连接符，eg merchant_msg
    const KEY_LOCAL_SERVICE = 'local.service';
    const KEY_REMOTE_SERVICE = 'remote.service';
    const LOCAL = 'local';
    const REMOTE = 'remote';
    const MSG = 'msg';
    const HTTP = 'http';
    const SERVER_NAME_KEY=2;  // gray-local-core  ，取 core 服务的key 为2



    public function __construct()
    {
        self::$config = array(
            'base_uri' => 'http://'.ENV_CONSUL_IP.':'.$this->consul_port
        );

        if(!defined('ENV_SERVER')){
            define("ENV_SERVER","prod");
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

        $this->consul = self::getInstance();
    }

    //用于访问类的实例的公共的静态方法
    public static function getInstance(){
        if(!(self::$_instance instanceof ServiceFactory)){
            self::$_instance = new ServiceFactory(self::$config);
        }
        return self::$_instance;
    }

    /**
     * 先查询服务，再注册服务，一次性注册多个，由 
     * @param string $serviceName
     * @return bool
     */
    public function consulService()
    {
        $serviceMapping = isset(\Yii::$app->params['service_mapping']) ? \Yii::$app->params['service_mapping']:'';
//        Tool::log(__CLASS__."--".__METHOD__." serviceMapping: ".var_export($serviceMapping,true),"consul.log");
        if (!is_array($serviceMapping)) {
            return false;
        }
        foreach ($serviceMapping as $key => $services) {
            foreach ($services as $typeService) {
                if (isset($typeService['module'], $typeService['ip'], $typeService['port'])) {
//                    $typeServiceArray[$typeService['module']] = $typeService['ip'] . ':' . $typeService['port'];
                    $serviceName = ENV_SERVER.self::SEPARATOR_CHARACTER.$key.self::SEPARATOR_CHARACTER.$typeService['module'];
//                    Tool::log(__CLASS__."--".__METHOD__." serviceName: ".var_export($serviceName,true),"consul.log");
                    $res = $this->selectServiceByName($serviceName);  // 查询服务
//                    Tool::log(__CLASS__."--".__METHOD__." res111: ".var_export($res,true),"consul.log");
                    if(empty($res)){
                        $this->registerService($serviceName,$typeService['ip'],$typeService['port']); // 注册服务
                        continue;
                    }
                    $flag = true; // 需要注册
                    foreach ($res as $v){
                        $server_ip = strpos($v["ServiceName"],self::REMOTE) !== false ? ENV_SERVER_IP:ENV_SERVER_LOCAL_IP;
                        if($v["ServiceAddress"] == $server_ip && $v["ServiceName"] == $serviceName){ // 有了不需要注册
                            $flag = false; // 不需要注册
                        }
                    }
                    if($flag === true){
                        $this->registerService($serviceName,$typeService['ip'],$typeService['port']); // 注册服务
                    }
                }
            }
        }
        return true;
    }


    /**
     * 注册服务
     * @param $serverName 服务名
     * @param $ip ip
     * @param $port 端口
     */
    private function registerService($serviceName='',$ip=ENV_SERVER_IP,$port=ENV_SERVER_LOCAL_PORT)
    {
        if(empty($serviceName)){
            $serviceName = ENV_SERVER.self::SEPARATOR_CHARACTER.self::LOCAL.self::SEPARATOR_CHARACTER.ENV_SYS_NAME;
        }
        $serviceInfo = [
            'ID'    => $serviceName,
            'Name'  => $serviceName,
            'Tags'  => [$serviceName],
            'Address' => $ip,
            'Port'  => (int)$port,
            'EnableTagOverride' => true,
            'Check' => [
                "DeregisterCriticalServiceAfter" => "1m", //check失败后1分钟删除本服务
                "TCP"      => $ip.':'.(int)$port,
                "Interval"  => "10s",
                "status"    => "passing"
            ]
        ];
        $res = $this->consul->get("agent")->registerService($serviceInfo)->getBody(); // 成功 返回 ''
//        Tool::log(__CLASS__."--".__METHOD__." res: ".var_export($res,true),"consul.log");
//        Tool::log(__CLASS__."--".__METHOD__." serviceInfo: ".print_r($serviceInfo,true),"consul.log");
        return true;
    }

    /**
     * 查询服务 catalog
     * @param $serverName 服务名
     */
    private function selectServiceByName($serviceName='')
    {
        if(empty($serviceName)){
            $serviceName = ENV_SERVER.self::SEPARATOR_CHARACTER.self::LOCAL.self::SEPARATOR_CHARACTER.ENV_SYS_NAME;
        }
        // 获取 $serviceName 对应的 服务相关信息
        $res = (new ServiceFactory(self::$config))->get("catalog")->service($serviceName)->getBody();   // json 格式
        $res = json_decode($res,true); // json 转 array  // abcService 有两个元素， res 数组即也有两个元素
        return $res;
    }


    /**
     * 查询相关条件的服务 catalog
     * $env_type 环境类型，默认为 remote
     * $env_release_type 发布类型，默认为 prod, 此外 还有 gray
     * $env_serviceName 服务名称为all ，则是查询所有，为customer 则只查询customer 的一个ip 和 port
     */
    public function selectByTypeServices($env_type="remote",$env_release_type="prod",$env_serviceName="all")
    {
        $res = (new ServiceFactory(self::$config))->get("catalog")->services()->getBody();   // json 格式
        $res = json_decode($res,true); // json 转 array  // abcService 有两个元素， res 数组即也有两个元素
//        Tool::log(__CLASS__."--".__METHOD__." res_body111: ".var_export($res,true),"consul.log");
        $data = [];
        if(!empty($res)){
            foreach ($res as $key =>$val){
                if($env_serviceName == "all"){
//                    Tool::log(__CLASS__."--".__METHOD__." all: ","consul.log");
                    if($key != "consul" && strpos($key,$env_type)!==false && strpos($key,$env_release_type)!==false){
                        $serviceInfos = $this->selectServiceByName($key);
                        $serviceCounts = count($serviceInfos);
//                        Tool::log(__CLASS__."--".__METHOD__." count_all: ".$serviceCounts,"consul.log");
                        if($serviceCounts == 0) return $data;
                        $rand = rand(0,$serviceCounts-1); // 随机取一个
                        $type_arr = explode(self::SEPARATOR_CHARACTER,$key);
                        $data[$type_arr[self::SERVER_NAME_KEY]] = $serviceInfos[$rand]["ServiceAddress"].":".$serviceInfos[$rand]["ServicePort"];
                    }
                }else{
//                    Tool::log(__CLASS__."--".__METHOD__." serviceName: ".$env_serviceName,"consul.log");
                    // 找 merchant 不能找到 merchant_msg,判断字符串长度
                    if($key != "consul" && strpos($key,$env_type)!==false && strpos($key,$env_release_type)!==false && substr($key,-strlen($env_serviceName)) == $env_serviceName){
//                        Tool::log(__CLASS__."--".__METHOD__." key: ".$key."-- serviceInfos_solo begin: ".var_export($this->consul,true),"consul.log");
                        $serviceInfos_solo = $this->selectServiceByName($key);
//                        Tool::log(__CLASS__."--".__METHOD__." key: ".$key."-- serviceInfos_solo: end".var_export($serviceInfos_solo,true),"consul.log");
                        $serviceCounts_solo = count($serviceInfos_solo);
                        if($serviceCounts_solo >= 5){
//                            Tool::log(__CLASS__."--".__METHOD__." consul_error: ".$serviceCounts_solo,"consul_error.log");
                        }
//                        Tool::log(__CLASS__."--".__METHOD__." count_solo: ".$serviceCounts_solo,"consul.log");
                        if($serviceCounts_solo == 0) return $data;
                        $rand = rand(0,$serviceCounts_solo-1); // 随机取一个
//                        Tool::log(__CLASS__."--".__METHOD__." rand_solo: ".$rand,"consul.log");
                        $type_arr = explode(self::SEPARATOR_CHARACTER,$key);
                        if(!isset($serviceInfos_solo[$rand]["ServiceAddress"]) || !isset($serviceInfos_solo[$rand]["ServicePort"])){
                            return $data;
                        }
                        $data[$type_arr[self::SERVER_NAME_KEY]] = $serviceInfos_solo[$rand]["ServiceAddress"].":".$serviceInfos_solo[$rand]["ServicePort"];
                        break;
                    }
                }

            }
        }
//        Tool::log(__CLASS__."--".__METHOD__." data: ".var_export($data,true),"consul.log");
        return $data;
    }


    /**
     * 查询所有的服务 catalog
     * $env_type 环境类型，默认为 remote
     */
    public function selectAllServices()
    {
        // 获取所有的服务名
        $res = $this->consul->get("catalog")->services()->getBody();
        $res = json_decode($res,true); // json 转 array  // abcService 有两个元素， res 数组即也有两个元素
//        Tool::log(__CLASS__."--".__METHOD__." res_body111: ".var_export($res,true),"consul.log");
        $data = [];
        if(!empty($res)){
            foreach ($res as $key =>$val){
                if($key != "consul"){
                    $serviceInfos = $this->selectServiceByName($key);
                    $serviceCounts = count($serviceInfos);
                    $rand = rand(0,$serviceCounts-1); // 随机取一个
                    $data[$key] = $serviceInfos[$rand]["ServiceAddress"].":".$serviceInfos[$rand]["ServicePort"];
                }
            }
        }
//        Tool::log(__CLASS__."--".__METHOD__." data: ".var_export($data,true),"consul.log");
        return $data;
    }

    /**
     * 注销服务 route.service agent /v1/agent/service/deregister/<serviceID>
     * @param $serverName 服务名
     */
    private function deregisterService($serviceName='')
    {
        if(empty($serviceName)){
            $serviceName = ENV_SERVER.self::SEPARATOR_CHARACTER.self::LOCAL.self::SEPARATOR_CHARACTER.ENV_SYS_NAME;
        }
        $this->consul->get("agent")->deregisterService($serviceName); // ->getBody(); // 成功 返回 ''
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
}