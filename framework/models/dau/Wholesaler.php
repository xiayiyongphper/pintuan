<?php
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 17-7-26
 * Time: 下午3:53
 */

namespace framework\models\dau;

use framework\components\ToolsAbstract;

class Wholesaler extends DauAbstract
{
    public function getKey()
    {
        return 'dau.wholesaler.' . date('Y-m-d');
    }
}