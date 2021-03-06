<?php
/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/15
 * Time: 16:46
 */

namespace service\controllers\product;

use framework\ApiAbstract;
use framework\Tool;
use framework\validParam;
use service\callService\product\BuyChainsDetailProxy;
use service\callService\product\BuyChainsProductDetailProxy;

/**
 * Class buyChainsDetail
 */
class buyChainsDetail extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);

        $params = [
            'buy_chains_id' => $this->_request['buy_chains_id'],
            'user_id' => $this->_request['user_id'],
        ];

        $result = (new BuyChainsDetailProxy($params))->sendRequest();
        $this->_result = $result->toArray();
        Tool::log($this->_result,'buy_chains_product_detail.log');

        //目前接龙只有单规格
        $SKU = current($this->_result['specification']);
        $this->_result['price'] = Tool::fenToYuan($SKU['price']);
        $this->_result['activity_price'] = Tool::fenToYuan($SKU['activity_price']);
        $this->_result['sold_number'] = $SKU['sold_number'];
        $this->_result['qty'] = $SKU['qty'];
        $this->_result['limit_buy_num'] = empty($SKU['limit_buy_num']) ? 0 : $SKU['limit_buy_num'];
        $this->_result['already_buy_num'] = empty($SKU['already_buy_num']) ? 0 : $SKU['already_buy_num'];

        $attrParts = [];
        foreach ($SKU['attribute'] as $attr) {
            $attrParts[] = $attr['value'];
        }
        $this->_result['specification_desc'] = implode('、', $attrParts);
        Tool::log($this->_result,'buy_chains_detail.log');

        unset($this->_result['specification_item']);
        unset($this->_result['specification']);
        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['buy_chains_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['store_id', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}