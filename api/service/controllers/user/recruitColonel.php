<?php

namespace service\controllers\user;

use framework\ApiAbstract;
use framework\Tool;
use framework\validParam;
use message\store\Store;
use service\callService\store\GetStoreDetailProxy;
use service\callService\user\UserProxy;

/**
 * 招募团长
 * Class recruitColonel
 * @package service\controllers\user
 */
class recruitColonel extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params, false);
        $user = [
            'name'=>'我是团长'
        ];
        return $user;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['code', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
            ],
        ];
    }
}