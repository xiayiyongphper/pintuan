<?php
namespace service\resources;

use framework\protocolbuffers\Message;
use framework\resources\ApiAbstract;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/21
 * Time: 15:10
 */
abstract class ResourceAbstract extends ApiAbstract
{
    protected $request;
    /** @var  Message $response */
    protected $response;
    protected $result = [];

    /**
     * @param $data
     */
    protected function doInit($data){
        $this->parseRequest($data);
        $this->response = static::response();
    }

    /**
     * @param $data
     */
    protected function parseRequest($data)
    {
        /** @var Message $request */
        $request = static::request();
        $request->parseFromString($data);
        $this->request = $request;
    }
}
