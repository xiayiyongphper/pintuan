<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/9/5
 * Time: 10:49
 */
namespace framework\core;

use service\message\common\Protocol;

/**
 * Class SWHeader
 * @package framework\core
 */
abstract class SWHeader
{
    /**
     * json，为了兼容性，先和Message类的保持一致
     */
    const PROTOCOL_JSON = Protocol::JSON;
    /**
     * pb，为了兼容性，先和Message类的保持一致
     */
    const PROTOCOL_PB = Protocol::PB;

    /**
     * 文件描述符
     * @var int
     */
    private $fd;

    /**
     * @var int
     */
    private $protocol;

    /**
     * @return int
     */
    public function getFd()
    {
        return $this->fd;
    }

    /**
     * @param int $fd
     * @return  $this
     */
    public function setFd($fd)
    {
        $this->fd = $fd;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @param mixed $protocol
     * @return  $this
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
        return $this;
    }

    /**
     * @param mixed $encryptVersion
     */
    public function setEncryptVersion($encryptVersion)
    {
        $this->encryptVersion = $encryptVersion;
    }
}