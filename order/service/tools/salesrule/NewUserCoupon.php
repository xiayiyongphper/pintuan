<?php
/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 18-7-11
 * Time: 下午3:03
 */

namespace service\tools\salesrule;


use common\models\SalesRule;
use service\resources\Exception;

class NewUserCoupon extends Coupon
{
    public function __construct($user_id)
    {
        $salesRule = SalesRule::find()
            ->where(['coupon_type' => SalesRule::NEW_USER_TYPE])
            ->andWhere(['status' => SalesRule::STATUS_ENABLE])
            ->andWhere(['del' => SalesRule::NOT_DELETED])
            ->one();
        if (!$salesRule) {
            Exception::throwException(Exception::SALES_RULE_NOT_EXIST);
        }

        $this->salesRule = $salesRule;
        $this->user_id = $user_id;
    }
}