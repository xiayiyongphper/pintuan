<?php
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 17-7-26
 * Time: 下午3:53
 */

namespace framework\models\dau;

use framework\components\ToolsAbstract;

/**
 * 用户日活
 * Class Customer
 * @package framework\models\dau
 */
class Customer extends DauAbstract
{
    public function getKey()
    {
        return 'dau.customer.' . date('Y-m-d');
    }

    /**
     * Y-m-d
     * @param $date
     * @return string
     */
    public function getKeyByDate($date){
        return 'dau.customer.' . $date;
    }
}