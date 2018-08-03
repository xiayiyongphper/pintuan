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

class editUserStore extends ResourceAbstract
{
    public function run($data)
    {
        /** @var UserStoreReq $request */
        $request = self::parseRequest($data);
        $response = self::response();

        if (!$request->getUserStoreId()) {
            if (!$request->getStoreId()) {
                UserException::invalidParams();
            }
            $userStore = UserStore::findOne(['user_id' => $request->getUserId(), 'store_id' => $request->getStoreId(), 'del' => UserStore::DELETED]);
            if (!$userStore) {
                $userStoreExist = UserStore::findOne(['user_id' => $request->getUserId()]);
                $userStore = new UserStore();
                if(!$userStoreExist){
                    $userStore->default_store = 1;
                }
            }
        } else {
            $userStore = UserStore::findOne(['id' => $request->getUserStoreId()]);
            if (!$userStore) {
                UserException::invalidParams();
            }
        }

        if ($userStore->id) {
            if ($request->getDel()) {
                if ($userStore->default_store) {
                    UserException::invalidParams();
                }
                $userStore->del = 2;
            } elseif ($request->getDefaultStore()) {
                $userStore->default_store = 1;
            } else {
                $userStore->del == UserStore::DELETED && $userStore->del = UserStore::NOT_DELETED;
            }
        } else {
            //若放开唯一限制 删除此判断即可
            if (UserStore::findOne(['user_id' => $request->getUserId(), 'store_id' => $request->getStoreId()])) {
                UserException::userStoreExisted();
            }
            $userStore->user_id = $request->getUserId();
        }

        if (!$request->getUserStoreId() || ($request->getStoreId() && $request->getStoreId() != $userStore->store_id)) {
            $userStore->store_id = $request->getStoreId();
        }
        $request->getName() && $userStore->name = $request->getName();
        if ($request->getPhone()) {
            $phone = $request->getPhone();
            if (strlen($phone) != 11 || $phone[0] !== '1') {
                UserException::invalidParams();
            }
            $userStore->phone = $phone;
        }


        if (!$userStore->save()) {
            Tools::log($userStore->errors, 'userSaveFail.log');
            UserException::userStoreError();
        }

        if ($request->getDefaultStore()) {
            UserStore::updateAll(['default_store' => 0], 'user_id = ' . $userStore->user_id . ' and id != ' . $userStore->id);
        }

        $respData = [
            'user_store_id' => $userStore->id
        ];

        $response->setFrom($respData);
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