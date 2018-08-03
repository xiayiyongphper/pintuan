<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 18:31
 */

namespace service\resources\store\v1;

use common\models\Store;
use common\models\StoreLogin;
use framework\components\ToolsAbstract;
use message\store\StoreDetailReq;
use message\store\Store as StoreMessage;
use service\resources\ResourceAbstract;
use service\resources\StoreException;

class getStoreDetail extends ResourceAbstract
{
    public function run($data)
    {
        /** @var StoreDetailReq $request */
        $request = self::parseRequest($data);
        $response = self::response();

        $store = Store::find()
            ->select(['id as store_id', 'name as store_name', 'address', 'detail_address', 'lat', 'lng', 'city',
                'bank', 'account', 'account_name', 'store_phone', 'store_front_img', 'owner_user_name', 'mini_program_qrcode'])
            ->where(['id' => $request->getStoreId(), 'del' => Store::NOT_DELETED])
            ->asArray()
            ->one();

        // 若是有登录的token则查出登录的信息
        if ($request->getAuthToken()) {
            $login = StoreLogin::findOne(['store_id' => $request->getStoreId(), 'auth_token' => $request->getAuthToken(), 'del' => 1]);
            if($login){
                $store['nick_name'] = $login->nick_name;
                $store['avatar_url'] = $login->avatar_url;
            }
        }

        if (!$store) {
            StoreException::throwNewException(StoreException::STORE_NOT_FOUND);
        }

        $response->setFrom(ToolsAbstract::pb_array_filter($store));
        return $response;
    }

    public static function request()
    {
        return new StoreDetailReq();
    }

    public static function response()
    {
        return new StoreMessage();
    }

}