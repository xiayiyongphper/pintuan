<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 18:31
 */

namespace service\resources\user\v1;

use common\models\UserStore;
use message\user\UserResponse;
use message\user\UserStore as UserStorePb;
use service\resources\ResourceAbstract;
use service\resources\UserException;
use service\tools\Tools;

class getUserStoreNum extends ResourceAbstract
{
    public function run($data)
    {
        /** @var UserStorePb $request */
        $request = self::parseRequest($data);
        $response = self::response();

        if (!$request->getUserId() || !$request->getStoreId()) {
            UserException::invalidParams();
        }

        $query = UserStore::find()->where(['store_id' => $request->getStoreId()]);
        $storeCount = $query->count();
        $query2 = clone $query;
        $userStore = $query->andWhere(['user_id' => $request->getUserId()])->one();
        if (!$userStore) {
            UserException::invalidParams();
        }

        $position = $query2->andWhere(['<=', 'id', $userStore->id])->count();


        $response->setFrom([
            'store_count' => $storeCount + UserStore::FAKE_NUM,
            'position' => $position + UserStore::FAKE_NUM,
        ]);
        return $response;

    }

    public static function request()
    {
        return new UserStorePb();
    }

    public static function response()
    {
        return new UserResponse();
    }

}