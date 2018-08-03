<?php

namespace service\controllers\product;

use framework\ApiAbstract;
use framework\validParam;
use service\callService\user\UserProxy;

class pintuanStart extends ApiAbstract
{
    public function run($params)
    {
        // 此接口暂时停用
        return $this->_result;
//        if (!empty($params)) {
//            $this->doInit($params, true);
//            // 调用user服务查询用户的昵称和头像
//            $userInfoReq = $result = (new UserProxy('user', 'user.getUserInfo', ['user_id' => $this->_request['user_id']]))->sendRequest();
//            $userInfo = $userInfoReq->toArray();
//            if (!empty($userInfo)) {
//                $this->_request['nick_name'] = isset($userInfo['nick_name']) ? $userInfo['nick_name'] : '';
//                $this->_request['avatar_url'] = isset($userInfo['avatar_url']) ? $userInfo['avatar_url'] : '';
//            }
//
//            $result = (new PintuanStartProxy('product', 'pintuan.PintuanStart', $this->_request))->sendRequest();
//            $this->_result = $result->toArray();
//        }
//        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['pintuan_activity_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['nick_name', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['avatar_url', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
            ],
        ];
    }
}