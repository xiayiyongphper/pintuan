<?php

namespace framework\resources;

use framework\components\ToolsAbstract;
use message\common\Header;
use yii\base\Component;
use framework\resources\ApiInterface;

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