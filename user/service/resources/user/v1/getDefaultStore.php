<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 18:31
 */

namespace service\resources\user\v1;

use common\models\User;
use common\models\UserStore;
use message\user\UserRequest;
use message\user\UserResponse;
use service\resources\ResourceAbstract;
use service\resources\UserException;

class getDefaultStore extends ResourceAbstract
{
    public function run($data)
    {
        /** @var UserRequest $request */
        $request = self::parseRequest($data);
        $response = self::response();

        $user = User::findOne(['id' => $request->getUserId()]);

        if (empty($user)) {
            UserException::userAuthTokenExpired();
        }

        $userStore = UserStore::findOne(['user_id' => $request->getUserId(), 'default_store' => 1, 'del' => User::NOT_DELETED]);

        $respData = [
            'store_id'     => $userStore ? $userStore->store_id : 0,
            'own_store_id' => $user->own_store_id,
        ];

        $response->setFrom($respData);
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