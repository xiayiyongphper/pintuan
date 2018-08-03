<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 18:31
 */

namespace service\resources\user\v1;

use common\components\UserTools;
use common\models\User;
use common\models\UserStore;
use message\user\UserRequest;
use message\user\UserResponse;
use common\components\Tools;
use service\resources\ResourceAbstract;
use service\resources\UserException;

class login extends ResourceAbstract
{
    public function run($data)
    {
        /** @var UserRequest $request */
        $request = self::parseRequest($data);
        $response = self::response();

        $code = $request->getCode();
        Tools::log($request, 'login.log');

        if (empty($code)) {
            UserException::wxLoginFailed();
        }

        $weixinurl = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . WX_APPID . '&secret=' . WX_SECRET . '&js_code=' . $code . '&grant_type=authorization_code';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $weixinurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 180);

        $result = curl_exec($ch);
        $result = json_decode($result, true);

        Tools::log($result, 'login.log');
        if (isset($result['errcode'])) {
            UserException::wxLoginFailed();
        }

        $session_key = $result['session_key'];
        $openid = $result['openid'];

        $user = User::findOne(['open_id' => $openid]);
        Tools::log($user, 'login.log');
        if (!$user) {
            $user = new User();
        }

        $user->open_id = $openid;
        $user->session_key = $session_key;
        $user->auth_token = UserTools::getRandomString(16);

        if (!$user->save()) {
            Tools::log($user->errors, 'login.log');
            UserException::wxLoginFailed();
        }

        $userStore = UserStore::findOne(['user_id' => $user->id, 'default_store' => 1, 'del' => User::NOT_DELETED]);

        $respData = [
            'user_id' => $user->id,
            'auth_token' => $user->auth_token,
            'is_auth' => $user->union_id ? 1 : 0,
            'store_id' => $userStore ? $userStore->store_id : 0,
            'own_store_id' => $user->own_store_id,
        ];

        Tools::log($respData, 'login.log');

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