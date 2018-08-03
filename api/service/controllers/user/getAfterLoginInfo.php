<?php

namespace service\controllers\user;

use framework\ApiAbstract;
use framework\validParam;
use message\store\Store;
use message\user\UserResponse;
use service\callService\store\GetStoreDetailProxy;
use service\callService\store\GetWholesalerDistrictListProxy;
use service\callService\user\getDefaultStoreProxy;

class getAfterLoginInfo extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);
        /** @var UserResponse $user */
        $user = (new getDefaultStoreProxy('user', 'user.getDefaultStore', $this->_request))->sendRequest();

        $storeId = $user->getStoreId();
        $response = [
            'store_id'     => $storeId,
            'own_store_id' => $user->getOwnStoreId(),
            'store_name'   => '',
            'new_user'     => 0,
        ];
        if (!$storeId)
            return $response;

        try {
            /** @var Store $store */
            $store = (new GetStoreDetailProxy(['store_id' => $storeId]))->sendRequest();
            $store_name = $store->getStoreName();

            $this->initWholesalersIdByStoreId($storeId);
            $activityId = $this->getNewUserActivity();//新人活动
            $activityId && $response['new_user'] = 1;

        } catch (\Exception $e) {
            $store_name = '';
        } catch (\Error $e) {
            $store_name = '';
        }
        $response['store_name'] = $store_name;

        return $response;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
            ],
        ];
    }
}