<?php
/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/15
 * Time: 16:46
 */

namespace service\controllers\store;

use framework\ApiAbstract;
use framework\Tool;
use framework\validParam;
use service\callService\store\GetGuideJoinMessageProxy;
use service\callService\order\GetPayOrderCountProxy;

/**
 * 支付完成后引导加群
 */
class getGuideJoinMessage extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);

        //获取是否该自提点下的下单次数
        $where = [
            'user_id'=>  $this->_request['user_id'],
            'store_id'=> $params['store_id']
        ];
        $countRes = (new GetPayOrderCountProxy($where))->sendRequest()->toArray();

        if ($countRes['order_count'] >= 2) {
            $res = [
                'title'=>'',
                'nick_name'=>'',
                'qrcode'=>'',
                'message'=>'',
            ];
            return $res;
        }

        $result = (new GetGuideJoinMessageProxy(['store_id'=>$params['store_id']]))->sendRequest()->toArray();
        return $result;
    }

    /**
     * 请求的参数验证
     * @return array
     */
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