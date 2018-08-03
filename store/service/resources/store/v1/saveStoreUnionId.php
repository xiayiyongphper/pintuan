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
use service\tools\wxBizDataCrypt\WXBizDataCrypt;
use message\store\StoreLoginReq;
use message\store\StoreLoginRes;
use service\tools\Tools;
use service\resources\ResourceAbstract;
use service\resources\StoreLoginException;

class saveStoreUnionId extends ResourceAbstract
{
    public function run($data)
    {
        /** @var StoreLoginReq $request */
        $request = self::parseRequest($data);
        $response = self::response();

        $storeId = $request->getStoreId();
        $authToken = $request->getAuthToken();
        if (empty($storeId) || empty($authToken)) {
            StoreLoginException::invalidParams();
        }

        /** @var StoreLogin $store */
        $store = StoreLogin::findOne(['id' => $storeId, 'auth_token' => $authToken, 'del' => 1]);
        $storeRelay = '';
        if (empty($store)) {
            // 查询中间表是否有数据 若是也没有 则说明过期
            $storeRelay = StoreLoginRelay::findOne(['id' => $storeId, 'auth_token' => $authToken]);
            if (empty($storeRelay)) {
                StoreLoginException::storeAuthTokenExpired();
            }
        }

        if (!isset($store->union_id) || !$store->union_id) {
            $rawData = $request->getRawData();
            $signature = $request->getSignature();
            $encryptedData = $request->getEncryptedData();
            $iv = $request->getIv();

            if (!$store && $storeRelay) {
                $sessionKey = $storeRelay->session_key;
            } else {
                $sessionKey = $store->session_key;
            }


            $signature2 = sha1($rawData . $sessionKey);

            //验证数据签名
            if ($signature != $signature2) {
                StoreLoginException::wxVerifyDataError();
            }

            $pc = new WXBizDataCrypt(WX_APPID, $sessionKey);
            $errCode = $pc->decryptData($encryptedData, $iv, $dataDecrypt);

            if ($errCode != 0) {
                StoreLoginException::wxAuthorizationError();
            }

            $dataDecrypt = json_decode($dataDecrypt, true);
            Tools::log($dataDecrypt, 'saveStoreUnionId.log');

            $appidVerify = $dataDecrypt['watermark']['appid'];

            //校验水印
            if ($appidVerify != WX_APPID) {
                StoreLoginException::wxAuthorizationError();
            }

            // 若是临时表中没有unionid 则去正式表查询是否有该用户存在 若是不存在  则表示不是商家 直接返回
            $store = StoreLogin::findOne(['union_id' => @$dataDecrypt['unionId']]);
            if (!$store) {
                $response->setFrom(Tools::pb_array_filter([
                    'store_id' => 0,
                ]));

                return $response;
            }

            $store->union_id = @$dataDecrypt['unionId'];
            $store->nick_name = $dataDecrypt['nickName'];
            $store->gender = $dataDecrypt['gender'];
            $store->language = $dataDecrypt['language'];
            $store->country = $dataDecrypt['country'];
            $store->avatar_url = $dataDecrypt['avatarUrl'];
            if ($storeRelay) {
                $store->open_id = $storeRelay->open_id;// 将临时表的数据转存过来
                $store->session_key = $storeRelay->session_key;
                $store->auth_token = $storeRelay->auth_token;
            }

            if (!$store->save(false)) {
                Tools::log($store->errors, 'storeSaveFail.log');
                StoreLoginException::wxLoginFailed();
            }
            // 同时保存真正的store_login_id入零时表
            if ($storeRelay) {
                $storeRelay->store_login_id = $store->id;
                if (!$storeRelay->save()) {
                    Tools::log($storeRelay->errors, 'storeRelay.log');
                    StoreLoginException::wxAuthorizationError();
                }
            }
        }

        $response->setFrom(Tools::pb_array_filter([
            'store_id' => $store->store_id,
        ]));

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