<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 18:31
 */

namespace service\resources\user\v1;

use common\components\wxBizDataCrypt\WXBizDataCrypt;
use common\models\User;
use message\user\UserRequest;
use message\user\UserResponse;
use common\components\Tools;
use service\resources\ResourceAbstract;
use service\resources\UserException;

class saveUserUnionId extends ResourceAbstract
{
    public function run($data)
    {
        /** @var User $user */
        /** @var UserRequest $request */
        $request = self::parseRequest($data);
        $response = self::response();

        $userId = $request->getUserId();
        $authToken = $request->getAuthToken();
        if (empty($userId) || empty($authToken)) {
            UserException::invalidParams();
        }

        $user = User::findOne(['id' => $userId, 'auth_token' => $authToken, 'del' => User::NOT_DELETED]);
        if (empty($user)) {
            UserException::userAuthTokenExpired();
        }

        if (!$user->union_id) {
            $rawData = $request->getRawData();
            $signature = $request->getSignature();
            $encryptedData = $request->getEncryptedData();
            $iv = $request->getIv();

            $sessionKey = $user->session_key;

            $signature2 = sha1($rawData . $sessionKey);

            //验证数据签名
            if ($signature != $signature2) {
                UserException::wxVerifyDataError();
            }

            $pc = new WXBizDataCrypt(WX_APPID, $sessionKey);
            $errCode = $pc->decryptData($encryptedData, $iv, $dataDecrypt);

            if ($errCode != 0) {
                UserException::wxAuthorizationError();
            }

            $dataDecrypt = json_decode($dataDecrypt, true);
            Tools::log($dataDecrypt, 'saveUserUnionId.log');

            $appidVerify = $dataDecrypt['watermark']['appid'];

            //校验水印
            if ($appidVerify != WX_APPID) {
                UserException::wxAuthorizationError();
            }

            $user->union_id = @$dataDecrypt['unionId'];
            $user->nick_name = $dataDecrypt['nickName'];
            $user->gender = $dataDecrypt['gender'];
            $user->language = $dataDecrypt['language'];
            $user->city = $dataDecrypt['city'];
            $user->province = $dataDecrypt['province'];
            $user->country = $dataDecrypt['country'];
            $user->avatar_url = $dataDecrypt['avatarUrl'];

            if (!$user->save()) {
                Tools::log($user->errors, 'userSaveFail.log');
                UserException::wxAuthorizationError();
            }
        }

        $response->setFrom(Tools::pb_array_filter([
            'user_id' => $userId
        ]));

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