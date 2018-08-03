<?php

namespace framework\resources;

use framework\components\ToolsAbstract;
use message\common\Header;
use yii\base\Component;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-7-26
 * Time: 下午2:29
 * Email: henryzxj1989@gmail.com
 */

/**
 * Class ApiAbstract
 * @package framework\resources
 */
abstract class ApiAbstract extends Component implements ApiInterface
{

    private static $workerId = 0;

    /**新增
     * @var Header
     */
    private $_header;

    /**新增
     * @var \framework\core\SWRequest
     */
    private $_request;

    /**新增
     * @param $header
     * @return $this
     */
    public function setHeader($header)
    {
        $this->_header = $header;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isRemote()
    {
        return $this->_request->isRemote();
    }

    /**新增
     * @param $request
     * @return $this
     */
    public function setRequest($request)
    {
        $this->_request = $request;
        return $this;
    }

    /*
     *
     */
    public function getRequest()
    {
        return $this->_request;
    }

    public function isDebug()
    {
        return $this->_request->isDebug();
    }

    public function getLevel()
    {
        return $this->_request->getLevel();
    }

    /**
     * @return integer
     */
    public function getSource()
    {
        return $this->_header->getSource();
    }

    /**
     * @param null $source
     * @return string
     */
    public function getSourceCode($source = null)
    {
        return ToolsAbstract::getSourceCode($source);
    }

    public function getRemoteIp()
    {
        return $this->_request->getRemoteIp();
    }

    /**
     * 获取workerId
     * @return int
     */
    public static function getWorkerId()
    {
        return self::$workerId;
    }

    /**
     * 设置workerId
     * @param int $workerId
     * @return bool
     */
    public static function setWorkerId($workerId)
    {
        self::$workerId = $workerId;
        return true;
    }

}