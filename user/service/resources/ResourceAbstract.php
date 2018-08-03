<?php

namespace service\resources;

use common\models\User;
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

    function getUserModel($userId, $token)
    {
        if (!$userId) {
            UserException::userNotExist();
        }

        $user = User::findById($userId);

        // 用户不存在
        if (!$user) {
            UserException::userNotExist();
        }
        // token错（PCWEB不验证token）
        if ($token != $user->auth_token) {
            UserException::userAuthTokenExpired();
        }

        return $user;
    }
}
