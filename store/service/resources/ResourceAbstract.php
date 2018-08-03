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
    public static function parseRequest($data)
    {
        /** @var Message $request */
        $request = get_called_class()::request();
        $request->parseFromString($data);
        return $request;
    }
}
