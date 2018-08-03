<?php
namespace framework\components\es;
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-7-6
 * Time: 下午12:15
 */
class Product extends EsAbstract
{
    protected $index = '.product';
    protected $type = 'product';
    protected static $instance;
    protected $properties_mapping = [
        'level' => [
            'type' => 'integer',
        ],
        'trace_id' => [
            'type' => 'string',
        ],
        'tags' => [
            'type' => 'string'
        ],
        'data' => [
            'type' => 'string',
        ],
        'timestamp' => [
            'type' => 'date',
            "format" => "yyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis"
        ],
    ];

    /**
     * @return Console
     */
    public static function get()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $traceId
     * @param \Exception $e
     * @param array $tags
     * @param int $level
     * @return bool
     */
    public function logException(\Exception $e, $traceId = null, $tags = [], $level = self::ES_LEVEL_ERROR)
    {
        if (is_null($traceId)) {
            $traceId = $this->getTraceId();
        }

        $saveArr = [
            'level' => $level,
            'trace_id' => $traceId,
            'tags' => $tags,
            'data' => $e->__toString(),
            'timestamp' => date('Y-m-d H:i:s')
        ];
        ESLogger::get()->log($saveArr);
//        $this->send($saveArr);
        return true;
    }

    /**
     * @param $data
     * @param null $traceId
     * @param array $tags
     * @param int $level
     * @return bool
     */
    public function log($data, $traceId = null, $tags = [], $level = self::ES_LEVEL_INFO)
    {
        if (!is_string($data)) {
            $data = json_encode($data);
        }
        if (is_null($traceId)) {
            $traceId = $this->getTraceId();
        }
        $msgArr = [
            'level' => $level,
            'trace_id' => $traceId,
            'tags' => $tags,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        ESLogger::get()->log($msgArr, $level);
//        $this->send($msgArr);
        return true;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function getType()
    {
        return $this->type;
    }
}