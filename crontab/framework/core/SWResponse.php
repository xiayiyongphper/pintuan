<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/25
 * Time: 10:43
 */

namespace framework\core;

use yii\base\Response;


/**
 * Class SWRequest
 * @package framework\core
 */
abstract class SWResponse extends Response
{
    const STATUS_OK = 0;
    /**
     * 路由
     * @var string
     */
    private $code;

    /**
     * 数据
     * @var string
     */
    private $data;

    /**
     * 文件描述符
     * @var int
     */
    private $fd;


    /**
     * 调试级别
     * @var int
     */
    protected $level = 0;


    /**
     * 原始数据
     * @param mixed $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param $fd
     * @return $this
     */
    public function setFd($fd)
    {
        $this->fd = $fd;
        return $this;
    }

    /**
     * @return int
     */
    public function getFd()
    {
        return $this->fd;
    }

    /**
     * @param  mixed $level
     * @return $this
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }


}