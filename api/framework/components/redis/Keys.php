<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/31
 * Time: 11:34
 */

namespace framework\components\redis;


/**
 * 公用的key应该放这里，便于多个系统协作。不建议做删除操作。
 * @package framework\redis
 */
class Keys
{
    const CRONTAB_GENERATE_TASK_PRIFIX = 'crontab_task_';
}