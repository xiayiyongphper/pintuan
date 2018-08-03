<?php
/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/7/6
 * Time: 15:46
 */

namespace framework;
use SensioLabs\Consul\ServiceFactory;

/**
 * Class Consul
 * @package framework
 */
class Consul
{
    private $consulIp;
    private $consul;
    private $consulPort;
    private static $instance=null;
    private static $config = [];

    const SERVER_NAME_KEY=2;  // gray-local-core  ，取 core 服务的key 为2
    const SEPARATOR_CHARACTER = "-"; //分隔符

    public function __construct($ip="127.0.0.1",$port = 8500)
    {
        $this->consulIp = $ip;
        $this->consulPort = $port;
        self::$config = array(
            'base_uri' => 'http://'.$this->consulIp.':'.$this->consulPort
        );

        $this->consul = self::getInstance();
    }

    /**
     * 实例
     * @return null|ServiceFactory
     */
    public static function getInstance(){
        if(!(self::$instance instanceof ServiceFactory)){
            self::$instance = new ServiceFactory(self::$config);
        }
        return self::$instance;
    }

    private function selectServiceByName($serviceName)
    {
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
    public function selectByTypeServices($envType,$envReleaseType,$envServiceName)
    {
        $data = [];
        $serviceName = $envType.self::SEPARATOR_CHARACTER.$envReleaseType.self::SEPARATOR_CHARACTER.$envServiceName;
//        Tool::log($serviceName,'consul.log');
        $serviceArray = $this->selectServiceByName($serviceName);
//        Tool::log($serviceArray,'consul.log');

        $count = count($serviceArray);
        if($count == 0) return $data;

        $rand = rand(0,$count-1); // 随机取一个
        $type_arr = explode(self::SEPARATOR_CHARACTER,$serviceName);
        if(!isset($serviceArray[$rand]["ServiceAddress"]) || !isset($serviceArray[$rand]["ServicePort"])){
            return $data;
        }
        $data[$type_arr[self::SERVER_NAME_KEY]] = $serviceArray[$rand]["ServiceAddress"].":".$serviceArray[$rand]["ServicePort"];

        return $data;
    }
}