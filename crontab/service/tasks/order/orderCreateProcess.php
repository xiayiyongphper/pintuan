<?php
/**
 * Created by crontab.
 * User: Ryan Hong
 * Date: 2018/6/28
 * Time: 20:29
 */

namespace service\tasks\order;

use framework\components\ToolsAbstract;
use service\tasks\TaskService;

/**
 * Class orderCreateProcess
 * @package service\tasks\order
 */
class orderCreateProcess extends TaskService
{
    /**
     * @param mixed $data
     * @return mixed 如果不成功请抛异常；其他情况都是认为是成功的。
     * @throws \Exception
     */
    public function run($data)
    {
        ToolsAbstract::log($data, 'order_create_process.log');
        if (empty($data['order_product']) || empty($data['order'])) {
            throw new \Exception("参数格式错误");
        }

        return true;
    }

}