<?php

namespace service\controllers\store;

use framework\ApiAbstract;
use framework\validParam;
use service\callService\store\GetStoreDetailProxy;

class getStoreDetail extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params, true, 2);

        $storeInfo = (new GetStoreDetailProxy(['store_id' => $params['store_id'], 'auth_token' => $params['auth_token']]))
            ->sendRequest();
        $this->_result = $storeInfo->toArray();

        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['store_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
            ],
        ];
    }
}