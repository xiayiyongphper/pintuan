<?php
/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 18-7-11
 * Time: 下午3:03
 */

namespace service\tools\salesrule;


use common\models\SalesRule;

abstract class Coupon
{
    /** @var SalesRule $salesRule */
    public $salesRule;
    public $user_id;

}