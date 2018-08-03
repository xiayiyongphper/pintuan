<?php
namespace framework\components\es;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-7-6
 * Time: 下午12:15
 */
class Timeline extends EsAbstract
{
    protected $index = '.timeline';
    protected $type = 'es_timeline';
    protected static $instance;
    protected $properties_mapping = [
        'source' => [
            'type' => 'string',
        ],
        'cmd' => [
            'type' => 'string',
        ],
        'method' => [
            'type' => 'string',
        ],
        'code' => [
            'type' => 'integer'
        ],
        'request_id' => [
            'type' => 'string',
        ],
        'trace_id' => [
            'type' => 'string',
        ],
        'value' => [
            'type' => 'float',
        ],
        'tags' => [
            'type' => 'string',
        ],
        'timestamp' => [
            'type' => 'date',
            "format" => "yyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis"
        ],
    ];

    /**
     * @return Timeline
     */
    public static function get()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function report($cmd, $method, $source, $value, $code = 0, $traceId = '', $requestId = '')
    {
        $msgArr = [
            'cmd' => $cmd,
            'source' => $source,
            'method' => $method,
            'trace_id' => $traceId,
            'request_id' => $requestId,
            'code' => $code,
            'value' => $value,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        ESLogger::get()->log($msgArr);
//        $this->send($msgArr);
        return true;
    }

    public function getIndex()
    {
        return sprintf('%s-%s', $this->index, $this->getIndexSuffix());
    }

    public function getType()
    {
        return $this->type;
    }
}