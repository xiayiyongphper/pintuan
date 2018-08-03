<?php

namespace service\controllers\product;

use framework\ApiAbstract;
use framework\validParam;
use service\callService\product\PintuanDetailProxy;
use service\callService\user\GetUserListProxy;

class pintuanDetail extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);

        $result = (new PintuanDetailProxy('product', 'pintuan.PintuanDetail', $this->_request))->sendRequest()->toArray();

        if(!empty($result['else_pintuan'])){
            $userIds = [];
            foreach ($result['else_pintuan'] as $item){
                $userIds[] = $item['create_user_id'];
            }

            $users = (new GetUserListProxy(['user_ids' => $userIds]))->sendRequest()->toArray();
            $userMap = [];
            foreach ($users['users'] as $user){
                $userMap[$user['user_id']] = [
                    'nick_name' => $user['nick_name'],
                    'avatar_url' => $user['avatar_url'],
                ];
            }

            foreach ($result['else_pintuan'] as $k => $item){
                if(isset($userMap[$item['create_user_id']])){
                    $result['else_pintuan'][$k] = array_merge($item,$userMap[$item['create_user_id']]);
                }
            }
        }

//        $this->_result = $result;

        return $result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['pintuan_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
            ],
        ];
    }
}