<?php

namespace service\controllers\user;

use framework\ApiAbstract;
use framework\validParam;
use message\store\Store;
use service\callService\store\GetStoreDetailProxy;
use service\callService\user\UserStoreProxy;

class changeUserStore extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);

        /** @var Store $store */
        $store = (new GetStoreDetailProxy(['store_id' => $this->_request['store_id']]))->sendRequest();

        $userStoreReq = $this->_request;
        unset($userStoreReq['auth_token']);
        $userStore = (new UserStoreProxy('user', 'user.changeUserStore', $userStoreReq))->sendRequest()->toArray();
        $userStore['store_name'] = $store->getStoreName();

        $activityId = $this->getNewUserActivity();//新人活动
        $activityId && $userStore['new_user'] = 1;

        return $userStore;
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