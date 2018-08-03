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

class getUserStoreList extends ResourceAbstract
{
    public function run($data)
    {
        /** @var UserStoreReq $request */
        $request = self::parseRequest($data);
        $response = self::response();

        if (!$request->getUserId()) {
            UserException::invalidParams();
        }

        $query = UserStore::find()
            ->select('id as user_store_id, store_id, default_store, has_order, name, phone')
            ->where(['user_id' => $request->getUserId(), 'del' => UserStore::NOT_DELETED]);

        if ($request->getUserStoreId()) {
            $query->andWhere(['id' => $request->getUserStoreId()]);
        }

        if ($request->getDefaultStore()) {
            $query->andWhere(['default_store' => 1]);
        }

        $userStore = $query->asArray()->all();

        $respData = [
            'user_store' => $userStore
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