<?php
namespace service\resources;

use framework\Exception;

class StoreException extends Exception
{
    /*=======================系统通用 001 =====================*/
    const NO_ERROR_CODE = 0;//default error code, none exception
    const UNDEFINED_ERROR = 13001001;
    const SERVICE_NOT_AVAILABLE = 13001002;
    const SYSTEM_MAINTENANCE = 13001003;
    const INVALID_REQUEST = 13001004;
    const INVALID_PARAM = 13001005;
    const RESOURCE_NOT_FOUND = 13001006;

    /*=======================系统通用 end=====================*/


    /*=======================自提点模块 002 =====================*/
    const STORE_NOT_FOUND = 13001001;
    /*=======================自提点模块 end=====================*/

    /*=======================商家模块 003 =====================*/
    const WALLET_RECORD_SAVE_FAIL = 13003001;
    const TIME_ERROR = 13003002;
    const WALLET_NOT_ENOUGH = 13003003;
    const WALLET_CHANGE_ERROR = 13003004;
    /*=======================商家模块 end=====================*/

    /*=======================公用配置相关 004 start=====================*/
    const MARKET_CONFIGURE_NOT_EXIST = 13004001;
    /*=======================公用配置相关 006 end=====================*/

    static $msg = [
        self::NO_ERROR_CODE => '',
        self::SERVICE_NOT_AVAILABLE => '系统繁忙，请稍后重试',
        self::SYSTEM_MAINTENANCE => '系统维护中，请稍后重试',
        self::RESOURCE_NOT_FOUND => '找不到相关资源',
        self::INVALID_REQUEST => '非法的请求',
        self::INVALID_PARAM => '参数错误',
        self::UNDEFINED_ERROR => '未定义错误',
        self::STORE_NOT_FOUND => '自提点未找到',
        self::WALLET_RECORD_SAVE_FAIL => '钱包流水记录失败',
        self::TIME_ERROR => '时间错误',
        self::WALLET_NOT_ENOUGH => '钱包余额不足',
        self::WALLET_CHANGE_ERROR => '钱包金额更改失败',
        self::MARKET_CONFIGURE_NOT_EXIST => '市场运营配置不存在',
    ];


    /**
     * 抛出异常统一调用此方法
     * @param $code
     * @throws \Exception
     */
    public static function throwNewException($code)
    {
        if (isset(self::$msg[$code])) {
            throw new \Exception(self::$msg[$code], $code);
        } else {
            self::throwException(self::UNDEFINED_ERROR);
        }
    }
}