<?php

/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/30
 * Time: 11:51
 */

namespace service\processes;

use framework\components\ToolsAbstract;
use framework\core\ProcessInterface;
use framework\core\SWServer;

/**
 * Class TestProcess
 * @package service\processes
 */
class TestProcess implements ProcessInterface
{
    /**
     * @inheritdoc
     */
    public function run(SWServer $SWServer, \swoole_process $process)
    {
        while (true) {
            ToolsAbstract::log(time(), 'TestProcess.log');
            sleep(10);
        }

    }
}