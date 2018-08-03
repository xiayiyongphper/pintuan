<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/16
 * Time: 16:05
 */

namespace framework\components\log;

/**
 * Class ESLogger
 * @package framework\components\log
 */
class ESLogger extends LogAbstract
{
    /**
     * @var \Elasticsearch\Client
     */
    public $esClient;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->esClient && !is_object($this->esClient)) {
            $this->esClient = \Yii::createObject($this->esClient);
        }
    }

    /**
     * @inheritdoc
     */
    public function log($message, $level = LogAbstract::LEVEL_INFO)
    {
        return $this->esClient->bulk($this->processMessage($message));
    }
}