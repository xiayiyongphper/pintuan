<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/25
 * Time: 10:43
 */

namespace framework\core;

use yii\base\Request;


/**
 * Class SWRequest
 * @package framework\core
 */
abstract class SWRequest extends Request
{

    private $rawBody;

    /**
     * 文件描述符
     * @var int
     */
    private $fd;



    /**
     * @param mixed $rawBody
     * @return $this
     */
    public function setRawBody($rawBody)
    {
        $this->rawBody = $rawBody;
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

}