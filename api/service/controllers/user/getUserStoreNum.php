<?php

namespace service\controllers\user;

use framework\ApiAbstract;
use framework\Tool;
use framework\validParam;
use service\callService\user\GetUserStoreNumListProxy;
use service\callService\user\UserStoreProxy;

class getUserStoreNum extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);
        $request = [
            'user_id' => $this->_request['user_id'],
            'store_id' => $this->_request['store_id'],
        ];
        $userStore = (new UserStoreProxy('user', 'user.getUserStoreNum', $request))->sendRequest()->toArray();
        $users = (new GetUserStoreNumListProxy($request))->sendRequest()->toArray();

        $result['tips'] = '已有' . $userStore['store_count'] . '位邻居加入该自提点，您是第' . $userStore['position'] . '位邻居。';
        $result += $userStore;
        $result += $users;

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