<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/25
 * Time: 19:10
 */

namespace framework\core;

/**
 * Class SWResponse
 * @package framework\core
 */
abstract class SWResponse
{
    const STATUS_OK = 0;
    /**
     * @var \swoole_server
     */
    private $server;

    /**
     * @var SWResponseHeader
     */
    private $header;

    /**
     * @var int
     */
    private $statusCode;
    /**
     * @var mixed
     */
    private $data;

    /**
     * SWResponse constructor.
     */
    public function __construct()
    {
        $this->statusCode = static::STATUS_OK;
    }

    /**
     * @inheritdoc
     */
    abstract public function send();
    
    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return SWResponseHeader
     */
    public function getHeader()
    {
        if (!$this->header) {
            $this->header = new SWRequestHeader();
        }
        return $this->header;
    }

    /**
     * @param SWResponseHeader $header
     * @return $this
     */
    public function setHeader(SWResponseHeader $header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     *
     */
    public function __clone()
    {
        $this->statusCode = static::STATUS_OK;
        $this->data = null;
        $this->header = null;
    }
}