<?php
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 17-7-26
 * Time: 下午3:58
 */

namespace framework\models\dau;

use service\message\common\Header;

/**
 * 日活用户工厂类
 * Class Factory
 * @package framework\models\dau
 */
class Factory
{

    /**
     * @param Header $header
     * @return bool|DauInterface
     */
    public static function getInstance(Header $header)
    {
        $instance = false;
        if ($header->getCustomerId()) {
            //用户
            $instance = new Customer();
            $instance->setId($header->getCustomerId());
        } elseif ($header->getContractorId()) {
            //业务员
            $instance = new Contractor();
            $instance->setId($header->getContractorId());
        } elseif ($header->getWholesalerId()) {
            //商家
            $instance = new Wholesaler();
            $instance->setId($header->getWholesalerId());
        } elseif ($header->getDriverId()) {
            //司机
            $instance = new Driver();
            $instance->setId($header->getDriverId());
        }
        return $instance;
    }
}