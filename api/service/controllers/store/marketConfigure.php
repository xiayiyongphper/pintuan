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
use service\callService\store\MarketConfigureProxy;

/**
 * 获取市场运营配置的接口
 */
class marketConfigure extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);
        $type = isset($params['type'])? $params['type'] : 3;
        //Tool::log("marketConfigure: ".var_export($params,true), 'wjhtest.txt');
        //Tool::log("marketConfigure1111: ".var_export($this->_request,true), 'wjhtest.txt');
        $result = (new MarketConfigureProxy(['type'=>$type]))->sendRequest()->toArray();
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