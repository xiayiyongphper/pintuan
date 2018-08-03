<?php

namespace service\resources\user\v1;

use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 18-6-13
 * Time: 下午4:32
 */
class test extends ResourceAbstract
{

    public function run($data)
    {
        /** @var \message\test\Test $request */
        $request = self::parseRequest($data);
        Tools::log($request->toArray(), 'test.log');
        return $request;
    }

    public static function request()
    {
        return new \message\test\Test();
    }

    public static function response()
    {
        return new \message\test\Test();
    }
}