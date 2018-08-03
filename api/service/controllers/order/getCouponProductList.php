<?php
/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/15
 * Time: 16:46
 */

namespace service\controllers\order;

use framework\ApiAbstract;
use framework\validParam;
use message\order\SalesRule;
use service\callService\order\getSalesRuleDetailProxy;
use service\callService\product\SalesRuleProductListProxy;

/**
 * Class getShareCoupon
 * 优惠券适用的商品
 */
class getCouponProductList extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);

        $request = [
            'user_id' => $this->_request['user_id'],
            'rule_id' => $this->_request['rule_id'],
        ];

        //判断规则是否生效
        /** @var SalesRule $salesRule */
        $salesRule = (new getSalesRuleDetailProxy($request))->sendRequest();

        $rule_id = $salesRule->getId();
        $product_ids = $salesRule->getProductIds();

        $request = ['rule_id' => $rule_id, 'product_ids' => $product_ids];

        //判断可领优惠券数量
        $result = (new SalesRuleProductListProxy($request))->sendRequest()->toArray();

        return $result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['rule_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}