<?php

namespace service\controllers\product;

use framework\ApiAbstract;
use framework\validParam;
use service\callService\product\PintuanActivityListProxy;

class pintuanActicityList extends ApiAbstract
{
    public function run($params)
    {
        if (!empty($params)) {
            $this->doInit($params, false);
            $result = (new PintuanActivityListProxy('product', 'pintuan.PintuanActivityList', $this->_request))->sendRequest();
            $this->_result = $result->toArray();
        }
        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['store_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['wholesaler_id', validParam::CHECK_TYPE_REPEATED_REQUIRE, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}