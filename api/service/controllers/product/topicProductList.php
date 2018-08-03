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
use service\callService\product\ProductListProxy;

/**
 * Class topicProductList
 */
class topicProductList extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);

        if (empty($this->_wholesalerIds)) {
            return $this->_result;
        }

        $activityId = $this->getNewUserActivity();//新人活动ID
        $params = [
            'wholesaler_ids' => $this->_wholesalerIds,
            'topic_id'       => $this->_request['topic_id'],
            'activity_id'    => $activityId,
            'page'           => $this->_request['page'],
            'page_size'      => $this->_request['page_size'],
        ];
        $result = (new ProductListProxy('product', 'product.topicProductList', $params))->sendRequest();
        $this->_result = $result->toArray();

        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['store_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['topic_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['page', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['page_size', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}