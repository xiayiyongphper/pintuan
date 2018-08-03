<?php

namespace framework\mq;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-7-29
 * Time: 上午10:32
 * Email: henryzxj1989@gmail.com
 */

use framework\components\ToolsAbstract;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use yii\base\Component;

/**
 * Class channel
 * @package framework\mq
 */
class RabbitMQ extends Component
{
    public $host;
    public $port = 5672;
    public $user;
    public $password;
    public $exchange = 'pintuan_messge_center';

    public $options;
    /**
     * @var AMQPChannel
     */
    protected $channel;

    public function init()
    {
        if (!$this->host) {
            throw new \Exception("set mq host please!");
        }

        if (!$this->user) {
            throw new \Exception("set mq user please!");
        }

        if (!$this->password) {
            throw new \Exception("set mq password please!");
        }
    }

    /**
     * RabbitMQ constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        if (!$this->channel) {
            try {
                $connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->password, ENV_RABBITMQ_VHOST);
                $channel = $connection->channel();
                $channel->exchange_declare($this->exchange, 'direct', false, false, false);
                $this->channel = $channel;
            } catch (\Exception $e) {
                ToolsAbstract::logException($e);
                $this->channel = null;
            }
        }

        return $this->channel;
    }

    public function consume(callable $callback, $queue = ENV_SYS_NAME)
    {
        ToolsAbstract::log("========memory_get_usage========",'mq.log');
        ToolsAbstract::log(memory_get_usage(),'mq.log');
        $this->channel->queue_declare($queue, false, true, false, false);

        $this->channel->queue_bind($queue, $this->exchange);

        $this->channel->basic_consume($queue, '', false, false, false, false, $callback);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    public function publish($data)
    {
        if (is_array($data)) {
            $message = json_encode($data);
        } else if (is_string($data)) {
            $message = $data;
        } else {
            return false;
        }

        $msg = new AMQPMessage(
            $message,
            array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
        );
        return $this->channel->basic_publish($msg, $this->exchange);
    }

}