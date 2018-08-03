<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/16
 * Time: 15:57
 */

namespace framework\components\log;

use framework\components\ToolsAbstract;
use yii\base\Object;

abstract class LogAbstract extends Object
{
    const LEVEL_ERROR = 0x01;
    const LEVEL_WARNING = 0x02;
    const LEVEL_INFO = 0x04;
    const LEVEL_TRACE = 0x08;
    const LEVEL_DEBUG = 0x10;
    const LEVEL_NOTICE = 0x20;
    const LEVEL_PROFILE = 0x40;

    /**
     * LogAbstract constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (!empty($config)) {
            \Yii::configure($this, $config);
        }
        $this->init();
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
    }

    /**
     * @param mixed $message
     * @param string|int $level
     * @return string
     */
    protected function processMessage($message, $level)
    {
        if (is_array($message) || is_object($message)) {
            if (is_object($message)) {
                $message->level = $level;
            } else {
                $message['level'] = $level;
            }
            return json_encode($message);
        } else {
            return sprintf("[%s][%s] %s\r\n", date('Y-m-d'), $level, $message);
        }
    }

    public abstract function log($message, $level = LogAbstract::LEVEL_INFO);
}