<?php
namespace framework\components\log;
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/16
 * Time: 15:52
 */

/**
 * Class Logger
 * @package framework\components\log
 */
class Logger
{
    /**
     * @param string $componentId 组件ID
     * @return LogAbstract
     * @throws \Exception
     */
    public static function getById($componentId = 'llLogger')
    {
        return \Yii::$app->get($componentId);
    }

    /**
     * @param $type
     * @param array $params
     * @return LogAbstract
     */
    public static function get($type, array $params = [])
    {
        return \Yii::createObject($type, $params);
    }
}