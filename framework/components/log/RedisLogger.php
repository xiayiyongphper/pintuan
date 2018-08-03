<?php
namespace framework\components\log;

/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/16
 * Time: 16:43
 */
class RedisLogger extends LogAbstract
{
    const DEFAULT_LOG_KEY = 'RedisLogger';
    const TYPE_LPUSH = 'lpush';
    const TYPE_RPUSH = 'rpush';
    /**
     * @var \Redis
     */
    public $redis;
    public $logKey = self::DEFAULT_LOG_KEY;
    public $type = self::TYPE_RPUSH;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->redis && !is_object($this->redis)) {
            $this->redis = \Yii::createObject($this->redis);
        }
        if (!($this->type === self::TYPE_LPUSH || $this->type === self::TYPE_RPUSH)) {
            $this->type = self::TYPE_RPUSH;
        }
    }

    /**
     * @inheritdoc
     */
    public function log($message, $level = LogAbstract::LEVEL_INFO)
    {
        if ($this->type === self::TYPE_LPUSH) {
            return $this->redis->lPush($this->logKey, $this->processMessage($message, $level));
        }
        return $this->redis->rPush($this->logKey, $this->processMessage($message, $level));
    }
}