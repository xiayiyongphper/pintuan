<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 18:31
 */

namespace service\resources\user\v1;

use common\models\User;
use message\user\UserRequest;
use message\user\UserResponse;
use service\resources\ResourceAbstract;
use service\resources\UserException;
use service\tools\Tools;

class getUserInfo extends ResourceAbstract
{
    public function run($data)
    {
        /** @var UserRequest $request */
        $request = self::parseRequest($data);
        $response = self::response();

        if (!$request->getUserId()) {
            UserException::invalidParams();
        }

        $query = User::find()
            ->select('id as user_id,nick_name,gender,avatar_url,country,province,city,birthday,constellation,signature,is_robot')
            ->where(['id' => $request->getUserId(), 'del' => User::NOT_DELETED]);

        $user = $query->asArray()->one();

        $response->setFrom($user);
        return $response;

    }

    public static function request()
    {
        return new UserRequest();
    }

    public static function response()
    {
        return new UserResponse();
    }

}