<?php

namespace service\tasks\test;

use framework\components\ToolsAbstract;
use message\product\PintuanStartRes;
use service\callService\product\PintuanStartProxy;
use service\entity\test\TestEntity;
use service\tasks\TaskService;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/21
 * Time: 15:09
 */
class test extends TaskService
{
    /**
     * @param mixed $data
     * @return bool
     */
    public function run($data)
    {
        $params = [
            'pintuan_activity_id' => 12,
            'user_id' => 2065,
            'nick_name' => 'ryan',
            'avatar_url' => 'ssfafs'
        ];

        /** @var PintuanStartRes $res */
        $proxy = new PintuanStartProxy($params);
        $res = $proxy->sendRequest();
//        $pintuanId = $res->getPintuan()->getId();
        return true;
    }
}