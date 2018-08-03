<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/25
 * Time: 10:43
 */

namespace framework\core;


/**
 * Class SWRequest
 * @package framework\core
 */
abstract class SWRequest
{
    /**
     * 原始数据
     * @var string
     */
    private $rawBody;

    /**
     * @var SWRequestHeader
     */
    private $header;

    /**
     * 文件描述符
     * @var int
     */
    private $fd;

    /**
     * 是否是远程请求
     * @var bool
     */
    protected $remote;

    /**
     * 客户端IP
     * @var string
     */
    protected $remoteIp;

    /**
     * 是否debug模式
     * @var bool
     */
    protected $debug = false;

    /**
     * 调试级别
     * @var int
     */
    protected $level = 0;

    protected $server = null;

    public function setServer($server){
        $this->server = $server;
    }

    /**
     * Resolves the current request into a route and the associated parameters.
     * @return array the first element is the route, and the second is the associated parameters.
     */
    abstract public function resolve();

    /**
     * 原始数据
     * @param mixed $rawBody
     * @return $this
     */
    public function setRawBody($rawBody)
    {
        $this->rawBody = $rawBody;
        return $this;
    }

    /**
     * @return string
     */
    public function getRawBody()
    {
        return $this->rawBody;
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
     * @return boolean
     */
    public function isRemote()
    {
        return $this->remote;
    }

    /**
     * @param $remote
     * @return $this
     */
    public function setRemote($remote)
    {
        $this->remote = $remote;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRemoteIp()
    {
        return $this->remoteIp;
    }

    /**
     * @param string $remoteIp
     * @return $this
     */
    public function setRemoteIp($remoteIp)
    {
        $this->remoteIp = $remoteIp;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @param bool $isDebug
     * @return $this
     */
    public function setDebug($isDebug = false)
    {
        $this->debug = $isDebug ? true : false;
        return $this;
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

    /**
     * @return SWRequestHeader
     */
    public function getHeader()
    {
        if (!$this->header) {
            $this->header = new SWRequestHeader();
        }
        return $this->header;
    }

    /**
     * @param SWRequestHeader $header
     */
    public function setHeader(SWRequestHeader $header)
    {
        $this->header = $header;
    }
}