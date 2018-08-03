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

class editUserInfo extends ResourceAbstract
{
    public function run($data)
    {
        /** @var UserRequest $request */
        $request = self::parseRequest($data);
        $response = self::response();

        if (!$request->getUserId()) {
            UserException::invalidParams();
        }

        $user = User::findById($request->getUserId());

        if (!$user) {
            UserException::invalidParams();
        }

        if ($request->getProvince()) {
            $user->province = $request->getProvince();
        }

        if ($request->getCity()) {
            $user->city = $request->getCity();
        }

        if ($request->getBirthday()) {
            $user->birthday = $request->getBirthday();
        }

        if ($request->getConstellation()) {
            $user->constellation = $request->getConstellation();
        }

        if ($request->getSignature()) {
            $user->signature = $request->getSignature();
        }

        if (!$user->save()) {
            Tools::log($user->errors, 'editUserInfo.log');
            UserException::saveError();
        }

        $response->setFrom(['user_id' => $user->id]);
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