<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/30
 * Time: 10:35
 */

namespace framework\core;

/**
 * Interface ProcessInterface
 * @package framework\core
 */
interface ProcessInterface
{
    /**
     * @param SWServer $SWServer
     * @param \swoole_process $process
     * @return mixed
     */
    public function run(SWServer $SWServer, \swoole_process $process);
}