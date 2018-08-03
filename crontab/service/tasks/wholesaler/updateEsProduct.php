<?php

namespace service\tasks\wholesaler;

use common\helper\EsProductHelper;
use framework\components\ToolsAbstract;
use service\tasks\TaskService;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/21
 * Time: 15:09
 */
class updateEsProduct extends TaskService
{
    public function run($data = null)
    {
        try {
            (new EsProductHelper())->update();
        } catch (\Exception $e) {
            ToolsAbstract::logException($e);
        } catch (\Error $e) {
            ToolsAbstract::logError($e);
        }
    }

}