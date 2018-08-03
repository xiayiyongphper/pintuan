<?php

namespace service\controllers\product;

use framework\ApiAbstract;
use framework\Tool;
use framework\validParam;
use service\callService\product\NewUserProductListProxy;

/**
 * Class newUserProductList
 */
class newUserProductList extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);

        $activityId = $this->getNewUserActivity();
        if (!$activityId) {
            return $this->_result;
        }

        $params = [
            'wholesaler_id' => $this->_wholesalerIds,
            'activity_id'   => $activityId,
            'page'          => $this->_request['page'],
            'page_size'     => $this->_request['page_size'],
        ];
        $result = (new NewUserProductListProxy($params))->sendRequest()->toArray();
        $result['image'] = 'http://assets.lelai.com/images/files/merchant/20180723/source/dd07498e443a10a39fe2b3ba38dd5c66_11b394071f73531255a4dcb844164c71.jpg';

        return $result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['store_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['page', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['page_size', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}