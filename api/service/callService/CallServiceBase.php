<?php
/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/13
 * Time: 11:08
 */

namespace service\callService;

use framework\Exception;
use service\callService\Message;
use framework\Consul;
use framework\Tool;
use message\common\Header;


/**
 * Class CallServiceBase
 */
abstract class CallServiceBase
{
    protected $header;
    protected $request;
    protected $ip;
    protected $port;
    protected $project;//服务项目，比如product、order
    protected $route;
    protected $data;//请求参数，数组格式
    protected static $client = [];

    public function __construct($project, $route, $data)
    {
        //Tool::log($data,'call.log');
        $this->project = $project;
        $this->route = $route;
        $this->data = $data;
        $this->setIpPort();
    }

    public function setIpPort()
    {
        $service = (new Consul(CONSUL_IP))->selectByTypeServices(CONSUL_PROJECT_NAME, CONSUL_ENV_NAME, $this->project);
//        Tool::log($service,'service.log');

        if (isset($service[$this->project])) {
            list($ip, $port) = explode(':', $service[$this->project]);
            $this->ip = $ip;
            $this->port = $port;
        } else {
            Exception::throwException(Exception::SERVICE_NOT_FOUND);
        }
    }

    /**
     * @param $ip
     * @param $port
     *
     * @return \swoole_client
     * @throws \Exception
     */
    protected function getClient($ip, $port)
    {

        $client = new \swoole_client(SWOOLE_SOCK_TCP);
        // 加上跟SOAClient一样的结束符检测
        $client->set(\Yii::$app->params['client_config']);

        $ret = $client->connect($ip, $port, 10);
        if (!$ret) {
            $e = new \Exception(sprintf("connect failed. Error: %s", $client->errCode));
            Tool::logException($e);
            throw $e;
        }

        return $client;
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    abstract function request();

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    abstract function response();

    public function setHeader()
    {
        $this->header = new Header();
        $this->header->setRoute($this->route);
    }

    public function setRequestBody()
    {
        $this->request = $this->request();
        if ($this->request instanceof \framework\protocolbuffers\Message) {
            $this->request->setFrom($this->data);
        }
    }

    public function sendRequest()
    {
        if (is_null($this->header)) {
            $this->setHeader();
        }
        if (is_null($this->request)) {
            $this->setRequestBody();
        }

        $client = $this->getClient($this->ip, $this->port);

        $start = microtime(true);
        try {

            $client->send(Message::pack($this->header, $this->request));
            $result = $client->recv();
            $end = microtime(true);
            Tool::log($this->route . ': ' . ($end - $start), 'time.log');
            if (empty($result)) {
                throw new \Exception('connect error');
            }
        } catch (\Error $e) {
            $client->close();
            $end = microtime(true);
            Tool::log($this->route . ': ' . ($end - $start), 'time.log');
            Tool::logError($e);
        } catch (\Exception $e) {
            $client->close();
            $end = microtime(true);
            Tool::log($this->route . ': ' . ($end - $start), 'time.log');
            Tool::logException($e);
        }

        $client->close();
        $message = new Message();
        $message->unpackResponse($result);
        if ($message->getHeader()->getCode() > 0) {
            $e = new \Exception($message->getHeader()->getMsg(), $message->getHeader()->getCode());
            Tool::logException($e);
            throw $e;
        }

        $response = $this->response();
        $response->parseFromString($message->getPackageBody());
        return $response;
    }

}