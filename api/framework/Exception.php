<?php
namespace framework;

/**
 * 错误码由三段组成：
 * 1、系统编码，共2位；
 * 2、业务模块编码，共3位；
 * 3、具体错误编码，共3位。
 * api系统的系统编码为 10
 * Class Exception
 * @package framework
 */
class Exception
{
    /*=======================系统通用 001 =====================*/
    const NO_ERROR_CODE = 0;//default error code, none exception
    const UNDEFINED_ERROR = 10001001;
    const SERVICE_NOT_AVAILABLE = 10001002;
    const SYSTEM_MAINTENANCE = 10001003;
    const INVALID_REQUEST = 10001004;
    const INVALID_PARAM = 10001005;
    const RESOURCE_NOT_FOUND = 10001006;
    const SERVICE_NOT_FOUND = 10001007;
    const SERVICE_REQUEST_FAILED = 10001008;

    /*=======================系统通用 end=====================*/


    /*=======================登录模块 002 =====================*/
    const USER_AUTH_TOKEN_EXPIRED = 10002001;
    /*=======================登录模块 end=====================*/

    /*=======================订单模块 003 =====================*/
    const SPECIFICATION_PINTUAN_NEED_ONE = 10003001;
    const STORE_NOT_IN_DISTRIBUTE_RANGE = 10003002;
    const PINTUAN_ACTIVITY_NOT_SUPPORT_CURRENT_STORE = 10003003;
    const ORDER_INVALID = 10003004;
    const PINTUAN_NOT_BECOME_GROUP = 10003005;
    /*=======================订单模块 end=====================*/

    static $msg = [
        self::NO_ERROR_CODE => '',
        self::SERVICE_NOT_AVAILABLE => '系统繁忙，请稍后重试',
        self::SYSTEM_MAINTENANCE => '系统维护中，请稍后重试',
        self::RESOURCE_NOT_FOUND => '找不到相关资源',
        self::INVALID_REQUEST => '非法的请求',
        self::INVALID_PARAM => '参数错误',
        self::USER_AUTH_TOKEN_EXPIRED => '用户信息已过期，请重新登陆',
        self::UNDEFINED_ERROR => '未定义错误',
        self::SERVICE_NOT_FOUND => '请求的服务未找到',
        self::SERVICE_REQUEST_FAILED => '服务(#project#)请求失败',
        self::SPECIFICATION_PINTUAN_NEED_ONE => '规格id、拼团id和拼团活动id不能同时为空',
        self::STORE_NOT_IN_DISTRIBUTE_RANGE => '商品暂不支持配送到该自提点，是否逛逛其他可配送商品？',
        self::PINTUAN_ACTIVITY_NOT_SUPPORT_CURRENT_STORE => '该活动暂不支持配送到该自提点，是否逛逛其他可配送活动？',
        self::ORDER_INVALID => '订单不存在或订单状态已经改变',
        self::PINTUAN_NOT_BECOME_GROUP => '拼团已经成团，不能领取分享优惠券',
    ];

    /**
     * 抛出异常统一调用此方法
     * @param $code
     * @throws \Exception
     */
    public static function throwException($code,array $params = []){
        if(isset(self::$msg[$code])){
            $msg = self::$msg[$code];
            if(!empty($params)){
                foreach ($params as $k => $v){
                    $msg = str_replace("#{$k}#",$v,$msg);
                }
            }
            throw new \Exception($msg,$code);
        }else{
            self::throwException(self::UNDEFINED_ERROR);
        }
    }

}
