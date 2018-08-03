<?php
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 17-7-21
 * Time: 下午2:15
 */

namespace framework\components\cache;


interface CacheInterface
{
    public function get($key);

    public function mGet(array $array);

    public function rebuild();

}