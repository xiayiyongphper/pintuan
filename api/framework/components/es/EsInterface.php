<?php
namespace framework\components\es;
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-7-6
 * Time: 下午12:19
 */
interface EsInterface
{
    public function getIndex();

    public function getType();
}