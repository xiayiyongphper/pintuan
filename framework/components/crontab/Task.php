<?php

namespace framework\components\crontab;
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 17-5-23
 * Time: 下午5:35
 */
class Task
{
    private $_route;
    private $_params;

    public function __construct($data)
    {
        $this->_route = $data['route'];
        $this->_params = $data['params'];
    }

    public function getRoute()
    {
        return $this->_route;
    }

    public function getParams()
    {
        return $this->_params;
    }
}