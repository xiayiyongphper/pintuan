<?php

namespace framework\message;

use framework\components\Encrypt;
use framework\components\TStringFuncFactory;
use service\message\common\EncryptionMethod;
use service\message\common\Header;
use service\message\common\Protocol;
use service\message\common\ResponseHeader;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 14:44
 */
class Message
{

    protected $route;
    protected $params;

    private $fd;

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    public function getFd()
    {
        return $this->fd;
    }

    /**
     * @param mixed $fd
     */
    public function setFd($fd)
    {
        $this->fd = $fd;
    }

}