<?php

namespace framework\redis;

use framework\components\ToolsAbstract;
use yii\base\Component;
use Yii;

/**
 * Class Cache
 * @package framework\redis
 */
class Cache extends Component
{
    /**
     * host
     * @var string
     */
    protected $_host;
    /**
     * port
     * @var int
     */
    protected $_port;
    /**
     * redis
     * @var \Redis
     */
    protected $_redis = null;

    public $options;
    /**
     * @var $this
     */
    protected static $_instance;

    public function init()
    {
        if (is_array($this->options)) {
            if (!extension_loaded('redis')) {
                throw new \Exception('Redis extension not loaded');
            }
            $options = $this->options;
            if (!isset($options['host']) || !$options['host']) {
                throw new \Exception('Redis host undefined.');
            }
            if (!isset($options['port']) || !$options['port']) {
                throw new \Exception('Redis port undefined.');
            }
            $this->_host = $options['host'];
            $this->_port = $options['port'];
        } else {
            throw new \Exception("Cache::redis must be either a Redis connection instance or the application component ID of a Redis connection.");
        }
    }

    public function __construct($config = [])
    {
        parent::__construct($config);
        if (!$this->_redis) {
            try {
                $this->_redis = new \Redis();
                $this->_redis->connect($this->_host, $this->_port);
            } catch (\Exception $e) {
                ToolsAbstract::logException($e);
                $this->_redis = null;
            }
        }
        return $this->_redis;
    }

    public static function gzdeflate($data, $level = 9)
    {
        return gzdeflate($data, $level);
    }

    public static function gzinflate($data)
    {
        return gzinflate($data);
    }

    public function __call($funcName, $arguments)
    {
        $result = false;
        try {
            if (!$this->_redis) {
                //renew instance
                $this->_redis = new \Redis();
                $this->_redis->connect($this->_host, $this->_port);
            }
            $result = call_user_func_array(array($this->_redis, $funcName), $arguments);
        } catch (\RedisException $e) {
            ToolsAbstract::logException($e);
            try {
                $ping = $this->_redis->ping();//expect "+PONG"
            } catch (\Exception $e) {
                $exception = new \RedisException('ping failed!', 0, $e);
                ToolsAbstract::logException($exception);
                $ping = false;
            }

            if ($ping === false) {
                //connection lost
                $this->_redis = null;
            }
        } catch (\Exception $e) {
            ToolsAbstract::logException($e);
        }
        return $result;
    }
}
