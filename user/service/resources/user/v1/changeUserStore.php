<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 18:31
 */

namespace service\resources\user\v1;

use common\models\UserStore;
use message\user\UserStore as UserStoreReq;
use message\user\UserResponse;
use service\resources\ResourceAbstract;
use service\resources\UserException;
use service\tools\Tools;

class changeUserStore extends ResourceAbstract
{
    public function run($data)
    {
        /** @var UserStoreReq $request */
        $request = self::parseRequest($data);
        $response = self::response();

        if (!$request->getStoreId() || !$request->getUserId()) {
            UserException::invalidParams();
        }

        $userStore = UserStore::findOne(['user_id' => $request->getUserId(), 'store_id' => $request->getStoreId()]);
        if (!$userStore) {
            $userStore = new UserStore();
            $userStore->user_id = $request->getUserId();
            $userStore->store_id = $request->getStoreId();
        }
        $userStore->default_store = 1;
        $userStore->del == UserStore::DELETED && $userStore->del = UserStore::NOT_DELETED;

        if (!$userStore->save()) {
            Tools::log($userStore->errors, 'changeUserStore.log');
            UserException::userStoreError();
        }

        UserStore::updateAll(['default_store' => 0], 'user_id = ' . $userStore->user_id . ' and id != ' . $userStore->id);

        $response->setFrom(['store_id' => $userStore->store_id]);
        return $response;
    }

    public static function request()
    {
        return new UserStoreReq();
    }

    public static function response()
    {
        return new UserResponse();
    }

}