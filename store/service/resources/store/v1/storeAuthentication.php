<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 18:31
 */

namespace service\resources\store\v1;


use common\models\StoreLogin;
use message\store\StoreLoginReq;
use message\store\StoreLoginRes;
use service\resources\ResourceAbstract;
use service\resources\StoreLoginException;
use service\tools\Tools;

class storeAuthentication extends ResourceAbstract
{
    public function run($data)
    {
        /** @var StoreLoginReq $request */
        $request  = self::parseRequest($data);
        $response = self::response();

        $storeId   = $request->getStoreId();
        $authToken = $request->getAuthToken();
        if (empty($storeId) || empty($authToken)) {
            StoreLoginException::invalidParams();
        }

        /** @var StoreLogin $store */
        $store = StoreLogin::findOne(['store_id' => $storeId, 'auth_token' => $authToken, 'del' => 1]);
        if (empty($store)) {
            StoreLoginException::storeAuthTokenExpired();
        }

        $respData = [
            'store_id'   => $store->store_id,
            'auth_token' => $store->auth_token,
            'nick_name'  => $store->nick_name,
            'gender'     => $store->gender,
            'country'    => $store->country,
            'avatar_url' => $store->avatar_url,
        ];

        $response->setFrom(Tools::pb_array_filter($respData));
        return $response;
    }

    public static function request()
    {
        return new StoreLoginReq();
    }

    public static function response()
    {
        return new StoreLoginRes();
    }

}