<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 18:31
 */

namespace service\resources\store\v1;

use common\models\StoreLogin;
use common\models\StoreLoginRelay;
use service\tools\StoreTools;
use message\store\StoreLoginReq;
use message\store\StoreLoginRes;
use service\resources\ResourceAbstract;
use service\resources\StoreLoginException;
use service\tools\Tools;

class login extends ResourceAbstract
{
    public function run($data)
    {
        /** @var StoreLoginReq $request */
        $request = self::parseRequest($data);
        $response = self::response();

        $code = $request->getCode();
        Tools::log($request, 'storeLogin.log');

        if (empty($code)) {
            StoreLoginException::wxLoginFailed();
        }

        $weixinurl = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . WX_APPID . '&secret=' . WX_SECRET . '&js_code=' . $code . '&grant_type=authorization_code';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $weixinurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 180);

        $result = curl_exec($ch);
        $result = json_decode($result, true);

        Tools::log($result, 'storeLogin.log');
        if (isset($result['errcode'])) {
            StoreLoginException::wxLoginFailed();
        }

        $session_key = $result['session_key'];
        $openid = $result['openid'];

        /** @var StoreLogin $store */
        // 若是用户名和头像不存在 则再取一遍
        $store = StoreLogin::find()->where(['open_id' => $openid, 'del' => 1])->andWhere(['!=', 'nick_name', ''])->andWhere(['!=', 'avatar_url', ''])->one();
        $is_merchant = 1;
        if (empty($store)) {
            $is_merchant = 0;
            $store = StoreLoginRelay::findOne(['open_id' => $openid]);
            if (empty($store)) {
                $store = new StoreLoginRelay();
            }
        }

        Tools::log($store, 'login_store.log');

        $store->open_id = $openid;
        $store->session_key = $session_key;
        $store->auth_token = StoreTools::getRandomString(16);
        if ($is_merchant == 1) {
            $store->update_at = date('Y-m-d H:i:s');
        }

        if (!$store->save(false)) {
            Tools::log($store->errors, 'login.log');
            StoreLoginException::wxLoginFailed();
        }


        $respData = [
            'store_id' => isset($store->store_id) ? $store->store_id : $store->id,
            'auth_token' => $store->auth_token,
            'is_merchant' => $is_merchant,// 是否真正的商户 1是 2否 0无法判断
            'is_auth' => (isset($store->union_id) && $store->union_id) ? 1 : 0,//0表示未授权 1 已经授权
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