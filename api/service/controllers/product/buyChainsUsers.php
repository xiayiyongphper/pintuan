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
use service\callService\product\BuyChainsDetailProxy;
use service\callService\product\BuyChainsProductDetailProxy;
use service\callService\product\BuyChainsUsersProxy;
use service\callService\user\GetUserListProxy;

/**
 * Class buyChainsUsers
 */
class buyChainsUsers extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);

        $params = [
            'buy_chains_id' => $this->_request['buy_chains_id'],
            'store_id' => $this->_request['store_id'],
            'pagination' => [
                'page' => $this->_request['page'],
                'page_size' => $this->_request['page_size'],
            ]
        ];

        $result = (new BuyChainsUsersProxy($params))->sendRequest()->toArray();
        Tool::log($result,'buy_chains_users.log');

        if(empty($result['list'])) return $result;

        $userIds = [];
        foreach ($result['list'] as $item){
            $userIds[] = $item['user_id'];
        }

        $users = (new GetUserListProxy(['user_ids' => $userIds]))->sendRequest()->toArray();
        $userMap = [];
        foreach ($users['users'] as $user){
            $userMap[$user['user_id']] = [
                'nick_name' => $user['nick_name'],
                'avatar_url' => $user['avatar_url'],
            ];
        }

        foreach ($result['list'] as $k => $listItem){
            if(isset($userMap[$listItem['user_id']])){
                $result['list'][$k] = array_merge($listItem,$userMap[$listItem['user_id']]);
            }
        }

        return $result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['buy_chains_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['store_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['page', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['page_size', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}