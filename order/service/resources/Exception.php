<?php

namespace service\resources;

class Exception
{
    /*=======================系统通用 001 =====================*/
    const NO_ERROR_CODE = 0;//default error code, none exception
    const UNDEFINED_ERROR = 12001001;
    const SERVICE_NOT_AVAILABLE = 12001002;
    const SYSTEM_MAINTENANCE = 12001003;
    const INVALID_REQUEST = 12001004;
    const INVALID_PARAM = 12001005;
    const RESOURCE_NOT_FOUND = 12001006;

    /*=======================系统通用 end=====================*/


    /*=======================下单模块 002 =====================*/
    const CREATE_ORDER_FAILED = 12002001;
    const ORDER_NOT_EXIST = 12002002;
    const ORDER_PAY_AMOUNT_NOT_MATCH = 12002003;
    /*=======================下单模块 end=====================*/

    /*=======================商家端 到货确认模块 003 start=====================*/
    const STORE_CREATE_BILL_EMPTY = 12003001;
    const STORE_ARRIVAL_ORDER_EMPTY = 12003002;
    const STORE_ORDER_STATUS_FAIL = 12003003;
    const STORE_SAVE_BILL_DETAIL_FAIL = 12003004;
    const STORE_SAVE_BILL_FAIL = 12003005;
    const STORE_TIME_ERROR = 12003006;
    const STORE_NOT_FOUND_BILL = 12003007;
    const STORE_NOT_FOUND_ORDER = 12003008;
    const STORE_NOT_ALL_ZERO = 12003009;
    const STORE_SHOULD_ARRIVAL_NOT_ZERO = 12003010;
    /*=======================商家端 到货确认模块 003 end=====================*/

    /*=======================商家端 订单核销模块 004 start=====================*/
    const STORE_PICK_CODE_ERROR = 12004001;
    const STORE_NO_ARRIVAL = 12004002;
    const STORE_ORDER_PRODUCT_EMPTY = 12004003;
    const STORE_ORDER_VERIFICATION_FAIL = 12004004;
    /*=======================商家端 到货确认模块 004 end=====================*/

    /*=======================优惠相关 005 start=====================*/
    const SALES_RULE_NOT_EXIST = 12005001;
    const SALES_RULE_EFFECTIVE_DAY_ERROR = 12005002;
    const SALES_RULE_COUPON_RECEIVE_ERROR = 12005003;
    const SALES_RULE_COUPON_RECEIVE_MAX = 12005004;
    const SALES_RULE_COUPON_RECEIVE_ALREADY = 12005005;
    const SALES_RULE_COUPON_CANNOT_USE = 12005006;
    /*=======================商家端 到货确认模块 005 end=====================*/

    /*=======================接龙模块 006 start=====================*/
    const ORDER_NOT_BUY_CHAINS = 12006001;
    const ORDER_NOT_VALID = 12006002;
    /*=======================接龙模块 006 end=====================*/

    static $msg = [
        self::NO_ERROR_CODE => '',
        self::SERVICE_NOT_AVAILABLE => '系统繁忙，请稍后重试',
        self::SYSTEM_MAINTENANCE => '系统维护中，请稍后重试',
        self::RESOURCE_NOT_FOUND => '找不到相关资源',
        self::INVALID_REQUEST => '非法的请求',
        self::INVALID_PARAM => '参数错误',
        self::UNDEFINED_ERROR => '未定义错误',
        self::CREATE_ORDER_FAILED => '生成订单失败',
        self::STORE_CREATE_BILL_EMPTY => '到货单商品不能为空',
        self::STORE_ARRIVAL_ORDER_EMPTY => '商品没有未到货的订单',
        self::STORE_ORDER_STATUS_FAIL => '订单状态修改失败',
        self::STORE_SAVE_BILL_DETAIL_FAIL => '保存到货单详情失败',
        self::STORE_SAVE_BILL_FAIL => '保存到货单失败',
        self::STORE_TIME_ERROR => '时间错误',
        self::STORE_NOT_FOUND_BILL => '未找到该到货单或者到货单详情',
        self::STORE_NOT_FOUND_ORDER => '未找到该到货单的关联订单',
        self::ORDER_NOT_EXIST => '订单不存在',
        self::ORDER_PAY_AMOUNT_NOT_MATCH => '支付金额错误',
        self::STORE_PICK_CODE_ERROR => '当前提货码无效',
        self::STORE_NO_ARRIVAL => '当前订单未到货',
        self::STORE_ORDER_PRODUCT_EMPTY => '订单商品为空',
        self::STORE_ORDER_VERIFICATION_FAIL => '订单核销失败',
        self::SALES_RULE_NOT_EXIST => '优惠活动不存在或已经结束',
        self::SALES_RULE_EFFECTIVE_DAY_ERROR => '优惠设置错误',
        self::SALES_RULE_COUPON_RECEIVE_ERROR => '优惠券领取失败',
        self::SALES_RULE_COUPON_RECEIVE_ALREADY => '优惠券已经领取过',
        self::SALES_RULE_COUPON_RECEIVE_MAX => '优惠券已达到最大领取次数',
        self::SALES_RULE_COUPON_CANNOT_USE => '无法使用该优惠券',
        self::STORE_NOT_ALL_ZERO => '到货单商品数量不能全为0',
        self::STORE_SHOULD_ARRIVAL_NOT_ZERO => '应到货数量不能为0',
        self::ORDER_NOT_BUY_CHAINS => '订单非接龙订单',
        self::ORDER_NOT_VALID => '订单非有效订单',
    ];


    /**
     * 抛出异常统一调用此方法
     * @param $code
     * @throws \Exception
     */
    public static function throwException($code)
    {
        if (isset(self::$msg[$code])) {
            throw new \Exception(self::$msg[$code], $code);
        } else {
            self::throwException(self::UNDEFINED_ERROR);
        }
    }
}

