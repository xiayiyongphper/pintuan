<?php

namespace service\resources;

use framework\Exception;

class StoreLoginException extends Exception
{
    const STORE_AUTH_TOKEN_EXPIRED = 10002001;
    const STORE_AUTH_TOKEN_EXPIRED_TEXT = '用户信息已过期，请重新登陆！';
    const STORE_NOT_FOUND = 10002002;
    const STORE_NOT_FOUND_TEXT = '用户不存在';
    const STORE_STORE_ERROR = 10002003;
    const STORE_STORE_ERROR_TEXT = '自提点操作失败';

    const WX_VERIFY_DATA_ERROR = 10009001;
    const WX_VERIFY_DATA_ERROR_TEXT = '数据校验失败';
    const WX_AUTHORIZATION_ERROR = 10009002;
    const WX_AUTHORIZATION_ERROR_TEXT = '授权失败';
    const WX_STORE_LOGIN_FAILED = 10009003;
    const WX_STORE_LOGIN_FAILED_TEXT = '微信登录失败';

    public static function wxLoginFailed()
    {
        throw new \Exception(self::WX_STORE_LOGIN_FAILED_TEXT, self::WX_STORE_LOGIN_FAILED);
    }

    public static function invalidParams()
    {
        throw new \Exception(self::INVALID_PARAMS_TEXT, self::INVALID_PARAMS);
    }

    public static function wxVerifyDataError()
    {
        throw new \Exception(self::WX_VERIFY_DATA_ERROR_TEXT, self::WX_VERIFY_DATA_ERROR);
    }

    public static function wxAuthorizationError()
    {
        throw new \Exception(self::WX_VERIFY_DATA_ERROR_TEXT, self::WX_VERIFY_DATA_ERROR);
    }

    public static function storeAuthTokenExpired()
    {
        throw new \Exception(self::STORE_AUTH_TOKEN_EXPIRED_TEXT, self::STORE_AUTH_TOKEN_EXPIRED);
    }

    public static function storeNotExist()
    {
        throw new \Exception(self::STORE_NOT_FOUND_TEXT, self::STORE_NOT_FOUND);
    }

    public static function storeStoreError()
    {
        throw new \Exception(self::STORE_STORE_ERROR_TEXT, self::STORE_STORE_ERROR);
    }

}
