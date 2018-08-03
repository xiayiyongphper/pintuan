<?php

namespace service\controllers\store;

use framework\ApiAbstract;
use framework\Tool;
use framework\validParam;
use service\callService\store\StoreLoginProxy;

class saveStoreUnionId extends ApiAbstract
{
    public function run($params)
    {
        Tool::log($params, 'saveStoreUnionId.log');
        $this->doInit($params, false, 2);
        $result  = (new StoreLoginProxy('store', 'store.saveStoreUnionId', $this->_request))->sendRequest();
        $this->_result = $result->toArray();

        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['store_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['raw_data', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['signature', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['encrypted_data', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['iv', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_STRING],
            ],
        ];
    }
}