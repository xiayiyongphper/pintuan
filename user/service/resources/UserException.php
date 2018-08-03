<?php

namespace service\resources;

use framework\Exception;

class UserException extends Exception
{
    const USER_AUTH_TOKEN_EXPIRED = 10002001;
    const USER_AUTH_TOKEN_EXPIRED_TEXT = '用户信息已过期，请重新登陆！';
    const USER_NOT_FOUND = 10002002;
    const USER_NOT_FOUND_TEXT = '用户不存在';
    const USER_STORE_ERROR = 10002003;
    const USER_STORE_ERROR_TEXT = '自提点操作失败';
    const SAVE_ERROR = 10002004;
    const SAVE_ERROR_TEXT = '保存失败';
    const USER_STORE_EXISTED = 10002005;
    const USER_STORE_EXISTED_TEXT = '该自提点已存在';

    const WX_VERIFY_DATA_ERROR = 10009001;
    const WX_VERIFY_DATA_ERROR_TEXT = '数据校验失败';
    const WX_AUTHORIZATION_ERROR = 10009002;
    const WX_AUTHORIZATION_ERROR_TEXT = '授权失败';
    const WX_USER_LOGIN_FAILED = 10009003;
    const WX_USER_LOGIN_FAILED_TEXT = '微信登录失败';



    public static function wxLoginFailed()
    {
        throw new \Exception(self::WX_USER_LOGIN_FAILED_TEXT, self::WX_USER_LOGIN_FAILED);
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

    public static function userAuthTokenExpired()
    {
        throw new \Exception(self::USER_AUTH_TOKEN_EXPIRED_TEXT, self::USER_AUTH_TOKEN_EXPIRED);
    }

    public static function userNotExist()
    {
        throw new \Exception(self::USER_NOT_FOUND_TEXT, self::USER_NOT_FOUND);
    }

    public static function userStoreError()
    {
        throw new \Exception(self::USER_STORE_ERROR_TEXT, self::USER_STORE_ERROR);
    }

    public static function userStoreExisted()
    {
        throw new \Exception(self::USER_STORE_EXISTED_TEXT, self::USER_STORE_EXISTED);
    }

    public static function saveError()
    {
        throw new \Exception(self::SAVE_ERROR_TEXT, self::SAVE_ERROR);
    }

}
