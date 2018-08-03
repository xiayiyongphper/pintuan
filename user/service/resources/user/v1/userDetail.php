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
use common\components\Tools;
use service\resources\ResourceAbstract;
use service\resources\UserException;

class userDetail extends ResourceAbstract
{
    public function run($data)
    {
        /** @var User $user */
        /** @var UserRequest $request */
        $request = self::parseRequest($data);
        $response = self::response();

        $userId = $request->getUserId();
        if (empty($userId)) {
            UserException::invalidParams();
        }

        $user = User::findOne(['id' => $userId, 'del' => 1]);
        if (empty($user)) {
            UserException::userNotExist();
        }

        $respData = [
            'user_id'    => $user->id,
            'nick_name'  => $user->nick_name,
            'gender'     => $user->gender,
            'phone'      => $user->phone,
            'province'   => $user->province,
            'city'       => $user->city,
            'country'    => $user->country,
            'avatar_url' => $user->avatar_url,
            'is_robot'   => $user->is_robot,
        ];

        $response->setFrom(Tools::pb_array_filter($respData));
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