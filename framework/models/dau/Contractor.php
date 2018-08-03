<?php
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 17-7-26
 * Time: 下午3:53
 */

namespace framework\models\dau;

class Contractor extends DauAbstract
{
    public function getKey()
    {
        return 'dau.contractor.' . date('Y-m-d');
    }
}