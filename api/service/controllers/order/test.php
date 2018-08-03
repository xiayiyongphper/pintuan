<?php
/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/12
 * Time: 15:41
 */

namespace service\controllers\order;
use framework\ApiAbstract;
use framework\Tool;
use service\callService\product\ProductDetailProxy;
use service\callService\user\GetUserListProxy;

/**
 * Class test
 */
class test extends ApiAbstract
{
    public function run($params){

        $result = (new GetUserListProxy(['user_ids' => [26,27,1590]]))->sendRequest();
        $this->_result = $result->toArray();
        Tool::log($this->_result,'pro_detail.log');

        return $this->_result;
    }

    protected function getRules()
    {
        // TODO: Implement getRules() method.
    }
}