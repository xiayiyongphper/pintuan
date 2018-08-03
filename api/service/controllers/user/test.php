<?php
namespace service\controllers\user;

use framework\ApiAbstract;
use framework\Tool;
use framework\validParam;
use service\callService\user\TestProxy;

/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/13
 * Time: 16:57
 */


/**
 * Class test
 */
class test extends ApiAbstract
{
    public function run($params){
        $this->doInit($params);

        $result = (new TestProxy('user','user.test',['test' => $this->_request['test']]))->sendRequest();
        $this->_result = $result->toArray();

        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['test',validParam::CHECK_TYPE_REQUIRE,validParam::VALUE_TYPE_STRING],
                ['user_id',validParam::CHECK_TYPE_REQUIRE,validParam::VALUE_TYPE_INT],
                ['auth_token',validParam::CHECK_TYPE_REQUIRE,validParam::VALUE_TYPE_STRING],
                ['wholesaler_id',validParam::CHECK_TYPE_REQUIRE,validParam::VALUE_TYPE_INT],
                ['payment_method',validParam::CHECK_TYPE_REQUIRE,validParam::VALUE_TYPE_STRING],
                ['name',validParam::CHECK_TYPE_REQUIRE,validParam::VALUE_TYPE_STRING],
                ['phone',validParam::CHECK_TYPE_REQUIRE,validParam::VALUE_TYPE_STRING],
                ['coupon_id',validParam::CHECK_TYPE_OPTIONAL,validParam::VALUE_TYPE_INT],
                ['comment',validParam::CHECK_TYPE_OPTIONAL,validParam::VALUE_TYPE_STRING],
                ['items',validParam::CHECK_TYPE_REPEATED_REQUIRE,'item']
            ],
            'item' => [
                ['product_id',validParam::CHECK_TYPE_REQUIRE,validParam::VALUE_TYPE_INT],
                ['num',validParam::CHECK_TYPE_REQUIRE,validParam::VALUE_TYPE_INT]
            ],
        ];
    }
}