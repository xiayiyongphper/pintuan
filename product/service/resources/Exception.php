<?php

namespace service\resources;

class Exception
{
    /*=======================系统通用 001 =====================*/
    const NO_ERROR_CODE = 0;//default error code, none exception
    const UNDEFINED_ERROR = 11001001;
    const SERVICE_NOT_AVAILABLE = 11001002;
    const SYSTEM_MAINTENANCE = 11001003;
    const INVALID_REQUEST = 11001004;
    const INVALID_PARAM = 11001005;
    const RESOURCE_NOT_FOUND = 11001006;
//    const PINTUAN_NON_EXISTENT = 11001007;

    /*=======================系统通用 end=====================*/


    /*=======================商品模块 002 =====================*/
    const PRODUCT_NOT_FIND = 11002001;
    const PRODUCT_OFFLINE = 11002002;
    const PRODUCT_UNDERSTOCK = 11002003;
    const SPECIFICATION_NOT_FIND = 11002004;
    const STORE_NOT_IN_DISTRIBUTE_RANGE = 11002005;
    const PINTUAN_ACTIVITY_NOT_SUPPORT_CURRENT_STORE = 11002006;
    const REDUCE_QTY = 11002007;
    const SPECIFICATION_NOT_JOIN_PINTUAN = 11002008;
    /*=======================商品模块 end=====================*/

    /*=======================拼团模块 003 =====================*/
    const PINTUAN_ACT_NOT_FIND = 11003001;
    const PINTUAN_NOT_FIND = 11003002;
    const PINTUAN_NOT_START = 11003003;
    const PINTUAN_END = 11003004;
    const PINTUAN_CHANGE_FAIL = 11003005;
    const PINTUAN_CREATE_FAILURE = 11003006;
    const PINTUAN_OPERATION_TASK_FAILURE = 11003007;
    const PINTUAN_CREATE_USER_FAILURE = 110030008;
    const PINTUAN_STOP = 110030009;
    /*=======================拼团模块 end=====================*/

    /*=======================接龙模块 004 =====================*/
    const BUY_CHAINS_NOT_FIND = 11004001;
    const BUY_CHAINS_END = 11004002;
    const BUY_CHAINS_NOT_START = 11004003;
    const BUY_CHAINS_NOT_SUPPORT_CURRENT_STORE = 11004004;
    const SPECIFICATION_NOT_JOIN_BUY_CHAINS = 11004005;
    const BUY_CHAINS_OVER_LIMIT = 11004006;
    /*=======================接龙模块 end=====================*/

    static $msg = [
        self::NO_ERROR_CODE => '',
        self::SERVICE_NOT_AVAILABLE => '系统繁忙，请稍后重试',
        self::SYSTEM_MAINTENANCE => '系统维护中，请稍后重试',
        self::RESOURCE_NOT_FOUND => '找不到相关资源',
        self::INVALID_REQUEST => '非法的请求',
        self::INVALID_PARAM => '参数错误',
        self::PRODUCT_NOT_FIND => '找不到商品',
        self::UNDEFINED_ERROR => '未定义错误',
        self::PRODUCT_OFFLINE => '商品已下架',
        self::PRODUCT_UNDERSTOCK => '商品库存不足',
        self::SPECIFICATION_NOT_FIND => '商品规格未找到',
        self::STORE_NOT_IN_DISTRIBUTE_RANGE => '商品暂不支持配送到该自提点，是否逛逛其他可配送商品？',
        self::PINTUAN_ACTIVITY_NOT_SUPPORT_CURRENT_STORE => '该活动暂不支持配送到该自提点，是否逛逛其他可配送活动？',
        self::REDUCE_QTY => '扣减库存失败',
        self::SPECIFICATION_NOT_JOIN_PINTUAN => '该商品规格未参与拼团',

        /*=======================拼团模块 start=====================*/
        self::PINTUAN_ACT_NOT_FIND => '拼团活动不存在或已结束',
        self::PINTUAN_NOT_FIND => '拼团不存在',
        self::PINTUAN_CREATE_FAILURE => '拼团创建失败',
        self::PINTUAN_OPERATION_TASK_FAILURE => '操作拼团相关定时任务失败',
        self::PINTUAN_CREATE_USER_FAILURE => '拼团人员加入失败',
        self::PINTUAN_NOT_START => '拼团未开始',
        self::PINTUAN_END => '拼团已结束',
        self::PINTUAN_CHANGE_FAIL => '拼团状态更新失败',
        /*=======================拼团模块 end=====================*/

        /*=======================接龙模块 start=====================*/
        self::BUY_CHAINS_NOT_FIND => '接龙活动不存在',
        self::BUY_CHAINS_END => '您来晚了，特价活动已结束',
        self::BUY_CHAINS_NOT_START => '活动未开始',
        self::BUY_CHAINS_NOT_SUPPORT_CURRENT_STORE => '该活动暂不支持配送到该自提点，是否逛逛其他可配送活动？',
        self::SPECIFICATION_NOT_JOIN_BUY_CHAINS => '该商品规格未参与接龙活动',
        self::BUY_CHAINS_OVER_LIMIT => '购买数量超过限购数量'
        /*=======================接龙模块 end=====================*/
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
