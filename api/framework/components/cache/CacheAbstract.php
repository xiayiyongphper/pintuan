<?php
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 17-7-21
 * Time: 下午2:15
 */

namespace framework\components\cache;


use framework\components\ToolsAbstract;

abstract class CacheAbstract implements CacheInterface
{
    /**
     * @return \Redis
     */
    protected function getCache()
    {
        return ToolsAbstract::getRedis();
    }
}