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
use service\callService\product\PintuanActivityListProxy;

/**
 * Class topicPintuanList
 */
class topicPintuanList extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);

        if (empty($this->_wholesalerIds)) {
            return $this->_result;
        }

        $params = [
            'store_id'      => $this->_request['store_id'],
            'wholesaler_id' => $this->_wholesalerIds,
            'topic_id'      => $this->_request['topic_id'],
            'page'          => $this->_request['page'],
            'page_size'     => $this->_request['page_size'],
        ];
        $result = (new PintuanActivityListProxy('product', 'pintuan.topicPintuanList', $params))->sendRequest();
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