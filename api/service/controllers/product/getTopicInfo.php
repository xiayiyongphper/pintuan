<?php

namespace service\controllers\product;

use framework\ApiAbstract;
use framework\Tool;
use framework\validParam;
use service\callService\product\GetTopicInfoProxy;

/**
 * Class getTopicInfo
 */
class getTopicInfo extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);

        $params = [
            'id' => $this->_request['topic_id']
        ];
        $result = (new GetTopicInfoProxy($params))->sendRequest();
        $this->_result = $result->toArray();

        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['topic_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}