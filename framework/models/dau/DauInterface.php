<?php
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 17-7-26
 * Time: 下午3:53
 */

namespace framework\models\dau;

interface DauInterface
{
    public function add();

    public function getKey();

    public function count();
}