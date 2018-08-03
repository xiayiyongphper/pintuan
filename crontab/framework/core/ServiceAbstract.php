<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/9/1
 * Time: 17:45
 */

namespace framework\core;

/**
 * Class ServiceAbstract
 * @package framework\core
 */
abstract class ServiceAbstract
{
    /**
     * @var SWRequest
     */
    private $request;

    /**
     * @return SWRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param SWRequest $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @param string $route
     * @param mixed $params
     * @return bool
     */
    public function beforeAction($route, $params = [])
    {
        return true;
    }

    /**
     * @param string $route
     * @param mixed $params
     */
    public function afterAction($route, $params = [])
    {

    }

    /**
     * @param mixed $data
     * @return mixed 如果不成功请抛异常；其他情况都是认为是成功的。
     */
    abstract public function run($data);
}