<?php

namespace framework;
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-7-26
 * Time: 下午4:19
 * Email: henryzxj1989@gmail.com
 */

/**
 * Class Exception
 * @package framework
 */
class Exception
{
    const DEFAULT_ERROR_CODE = 10001001;//default error code, none exception
    const OFFLINE = 10009999;
    const SERVICE_NOT_AVAILABLE = 10001002;
    const SERVICE_NOT_AVAILABLE_TEXT = '系统繁忙，请稍后重试！';
    const SYSTEM_MAINTENANCE = 10001003;
    const SYSTEM_MAINTENANCE_TEXT = '系统维护中，请稍后重试';
    const INVALID_REQUEST_ROUTE = 10001004;
    const INVALID_REQUEST_ROUTE_TEXT = '非法的请求';
    const INVALID_PARAMS = 10001005;
    const INVALID_PARAMS_TEXT = '参数错误';
    const RESOURCE_NOT_FOUND = 10001006;
    const RESOURCE_NOT_FOUND_TEXT = '找不到相关资源';
    const SYSTEM_NOT_FOUND = 10001007;
    const SYSTEM_NOT_FOUND_TEXT = '找不到相关信息';
    const SYSTEM_BUSY = 10001008;
    const SYSTEM_BUSY_TEXT = '系统繁忙，请稍后重试！';
    const ENCRYPTION_METHOD_ERROR = 10001009;
    const ENCRYPTION_METHOD_ERROR_TEXT = '非法的请求(505)！';
    const RATE_LIMITER_FORBIDDEN = 10001010;
    const RATE_LIMITER_FORBIDDEN_TEXT = '您操作频率太快，请稍后重试';
    const SYSTEM_REDIRECTION = 10001011;
    const SYSTEM_REDIRECTION_TEXT = '系统检测到需要重定向';
    const INVALID_PARAM = 10001012;
    const INVALID_PARAM_TEXT = '参数错误';


    public static function invalidParam()
    {
        throw new \Exception(self::INVALID_PARAM_TEXT, self::INVALID_PARAM);
    }

    public static function offline($text)
    {
        throw new \Exception($text, self::OFFLINE);
    }

    public static function rateLimiterForbidden()
    {
        throw new \Exception(self::RATE_LIMITER_FORBIDDEN_TEXT, self::RATE_LIMITER_FORBIDDEN);
    }

    public static function resourceNotFound()
    {
        throw new \Exception(self::RESOURCE_NOT_FOUND_TEXT, self::RESOURCE_NOT_FOUND);
    }

    public static function invalidRequestRoute()
    {
        throw new \Exception(self::INVALID_REQUEST_ROUTE_TEXT, self::INVALID_REQUEST_ROUTE);
    }

    public static function systemNotFound()
    {
        throw new \Exception(self::SYSTEM_NOT_FOUND_TEXT, self::SYSTEM_NOT_FOUND);
    }

    public static function serviceNotAvailable()
    {
        throw new \Exception(self::SERVICE_NOT_AVAILABLE_TEXT, self::SERVICE_NOT_AVAILABLE);
    }

    public static function systemMaintenance()
    {
        throw new \Exception(self::SYSTEM_MAINTENANCE_TEXT, self::SYSTEM_MAINTENANCE);
    }

    public static function EncryptionMethodError()
    {
        throw new \Exception(self::ENCRYPTION_METHOD_ERROR_TEXT, self::ENCRYPTION_METHOD_ERROR);
    }

    public static function throwException($message, $code = 9999)
    {
        throw new \Exception($message, $code);
    }
}
