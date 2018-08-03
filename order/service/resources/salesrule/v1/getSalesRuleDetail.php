<?php
/**
 * Created by Jason.
 * Author: Jason Y. Wang
 * Date: 2016/3/3
 * Time: 16:26
 */

namespace service\resources\salesrule\v1;

use common\models\SalesRule;
use common\models\SalesRuleProduct;
use message\order\getNewUserCouponRequest;
use message\order\getSalesRuleDetailRequest;
use service\resources\Exception;
use service\resources\ResourceAbstract;

/**
 * Author: Jason Y. Wang
 * Class orderNumber
 * @package service\resources\order\v1
 * 获取规则是否有效
 */
class getSalesRuleDetail extends ResourceAbstract
{
    /** @var getSalesRuleDetailRequest $request */
    protected $request;

    public function run($data)
    {
        $this->doInit($data);
        $response = self::response();
        $rule_id = $this->request->getRuleId();

        $salesRule = SalesRule::findOne(['id' => $rule_id, 'status' => SalesRule::STATUS_ENABLE, 'del' => SalesRule::NOT_DELETED]);

        if (!$salesRule) {
            Exception::throwException(Exception::SALES_RULE_NOT_EXIST);
        }

        $product_ids = SalesRuleProduct::find()->select('product_id')->where(['rule_id' => $rule_id])->column();

        $response->setFrom([
            'id' => $rule_id,
            'product_ids' => $product_ids,
        ]);

        return $response;

    }

    public static function request()
    {
        return new getSalesRuleDetailRequest();
    }

    public static function response()
    {
        return new \message\order\SalesRule();
    }

}
