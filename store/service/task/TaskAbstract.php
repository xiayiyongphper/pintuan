<?php
/**
 * Created by PhpStorm.
 * User: zgr0629
 * Date: 21/1/2016
 * Time: 5:58 PM
 */

namespace service\task;

abstract class TaskAbstract
{
    abstract public function run($data);
}