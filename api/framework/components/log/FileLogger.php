<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/17
 * Time: 11:46
 */

namespace framework\components\log;


·/**
 * Class FileLogger
 * @package framework\components\log
 */
class FileLogger extends LogAbstract
{
    /**
     * @var string
     */
    public $filePath = __DIR__ . '/FileLogger.log';

    /**
     * @inheritdoc
     */
    public function log($message, $level = LogAbstract::LEVEL_INFO)
    {
        @file_put_contents($this->filePath, $this->processMessage($message, $level), FILE_APPEND);
    }
}