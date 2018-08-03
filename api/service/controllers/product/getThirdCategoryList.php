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
use service\callService\product\GetThirdCategoryProxy;

/**
 * Class getThirdCategoryList
 */
class getThirdCategoryList extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);

//        Tool::log($wholesalerIds,'third_category.log');
        if (empty($this->_wholesalerIds)) {
            return $this->_result;
        }

        $params = [
            'wholesaler_ids'     => $this->_wholesalerIds,
            'second_category_id' => $this->_request['second_category_id']
        ];
        $result = (new GetThirdCategoryProxy($params))->sendRequest();
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
                ['second_category_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}