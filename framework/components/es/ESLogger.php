<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/17
 * Time: 14:37
 */

namespace framework\components\es;


use framework\components\log\LogAbstract;
use framework\components\log\Logger;
use framework\components\ToolsAbstract;

/**
 * Class ESLogger
 * @package framework\components\es
 */
class ESLogger
{
    /**
     * @var string
     */
    const COMPONENT_ID = 'es_logger';
    /**
     * @var ESLogger
     */
    private static $instance;

    /**
     * @var LogAbstract
     */
    private $logger;

    /**
     * @param mixed $msg
     * @param string|int $level
     * @return mixed
     */
    public function log($msg, $level = LogAbstract::LEVEL_INFO)
    {
        $msg = [
            'message' => $msg,
            'host' => constant('ENV_SERVER_LOCAL_IP'),
            'level' => $level,
            'timestamp' => date('Y-m-d')
        ];
        return $this->logger->log($msg, $level);
    }

    /**
     * ESLogger constructor.
     */
    private function __construct()
    {
        $this->logger = Logger::getById(static::COMPONENT_ID);
    }

    /**
     * 获取ESLogger实例
     *
     * @throws \Exception
     * @return self
     */
    public static function get()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}