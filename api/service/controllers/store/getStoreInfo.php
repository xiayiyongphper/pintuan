<?php

namespace service\controllers\store;

use framework\ApiAbstract;
use framework\validParam;
use message\store\Store;
use service\callService\store\GetStoreDetailProxy;

class getStoreInfo extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);

        /** @var Store $store */
        $store = (new GetStoreDetailProxy(['store_id' => $params['store_id']]))
            ->sendRequest();

        $result['store_id'] = $store->getStoreId();
        $result['store_name'] = $store->getStoreName();
        $result['address'] = $store->getAddress();
        $result['store_phone'] = $store->getStorePhone();
        $result['store_front_img'] = $store->getStoreFrontImg();
        $result['owner_user_name'] = $store->getOwnerUserName();

        return $result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['store_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}