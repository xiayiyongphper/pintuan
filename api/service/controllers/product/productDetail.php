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
use service\callService\product\ProductDetailProxy;

/**
 * Class productDetail
 */
class productDetail extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);

        $activityId = $this->getNewUserActivity();//新人活动

        $params = ['product_id' => $this->_request['product_id']];
        $activityId && $params['activity_id'] = $activityId;

        $result = (new ProductDetailProxy($params))->sendRequest();
        $this->_result = $result->toArray();

        $activityId && $this->_result['new_user'] = 1;
        //Tool::log($this->_result,'pro_detail.log');

        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['product_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['store_id', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}