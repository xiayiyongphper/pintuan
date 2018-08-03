<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/30
 * Time: 19:55
 */

namespace service\processes;

use framework\components\Pack;
use framework\components\ToolsAbstract;
use framework\core\ProcessInterface;
use framework\core\SWServer;
use framework\core\TaskResponse;
use framework\message\Message;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class MQProcess
 * @package service\processes
 */
class MQProcess implements ProcessInterface
{
    private $server;
    private $process;
    /**
     * @var resource[]
     */
    private $clients = [];

    /**
     * 默认的client ID
     */
    const DEFAULT_CLIENT_ID = 10000;

    const LOG_FILE = 'mq_process.log';

    /**
     * @inheritdoc
     */
    public function run(SWServer $SWServer, \swoole_process $process)
    {
        $this->server = $SWServer;
        $this->process = $process;
        while (1) {
            try {
                $this->consume();
            } catch (\Throwable $throwable) {
                sleep(1);
                if ($throwable instanceof \Exception) {
                    ToolsAbstract::logException($throwable);
                } elseif ($throwable instanceof \Error) {
                    ToolsAbstract::logError($throwable);
                }
            }

        }
    }

    /**
     * 消费MQ消息
     */
    private function consume()
    {
        ToolsAbstract::getRabbitMq()->consume(function ($msg) {
            $body = [];
            ToolsAbstract::log(memory_get_usage(),'MQProcess.log');
            try {
                //收到消息后直接确认，有异常记录日志
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);

                /** @var  AMQPMessage $msg */
                $body = json_decode($msg->body, true);

                ToolsAbstract::log($body,'MQProcess.log');

                if(empty($body['route'] || empty($body['params']))){
                    self::log('mq message invalid');
                    self::log($body);
                    return false;
                }

                $message = new Message();
                $message->setRoute($body['route']);
                $message->setParams($body['params']);

                $data = Pack::pack($message);

                if (!$client = $this->getClient()) {
                    throw new \Exception('Unable to connect to the host!');
                }

                if (!$this->sendByFd($client, $data)) {
                    $this->closeByFd($client);
                    throw new \Exception('Send data failed');
                }

                $this->receive();
            } catch (\Exception $e) {
                ToolsAbstract::logException($e);
                self::log($body);
            } catch (\Error $error) {
                ToolsAbstract::logError($error);
                self::log($body);
            }
        });
    }

    /**
     * @param int $retryTimes 重试次数。默认30次
     * @return null|resource
     */
    private function getClient($retryTimes = 30)
    {
        $isConnected = false;
        $socket = null;
        while (!$isConnected && $retryTimes-- > 0) {
            $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
            if ($isConnected = socket_connect($socket, ENV_SERVER_UNIX_SOCKET, ENV_SERVER_PORT)) {
                $this->clients[(int)$socket] = $socket;
                break;
            }
            usleep(10000);  // 10ms
        }
        return $isConnected ? $socket : null;
    }

    /**
     * 收所有准备好的连接的数据
     */
    private function receive()
    {
        if (empty($this->clients)) {
            return;
        }

        $write = $error = [];
        $read = $this->clients;
        if (false === ($n = socket_select($read, $write, $error, 0, 10000))) { // 10ms
            ToolsAbstract::log('socket_select() ERROR:' . socket_last_error(), 'exception.log');
            return;
        }

        if ($n <= 0) {
            return;
        }

        foreach ($read as $fd) {
            try {
                /** @var  $body */
                $this->recvByFd($fd);
            } catch (\Exception $e) {
                ToolsAbstract::logException($e);
            } catch (\Error $error) {
                ToolsAbstract::logError($error);
            } finally {
                $this->closeByFd($fd);
            }
        }
    }

    /**
     * 收某个连接的数据
     * @param resource $fd
     * @throws \Exception
     * @return TaskResponse
     */
    private function recvByFd($fd)
    {
        $serverConfig = \Yii::$app->params['soa_server_config'];
        $headerLen = $serverConfig['package_length_offset'] + 4;
        if (socket_recv($fd, $buf, $headerLen, MSG_WAITALL) < $headerLen) {
            throw new \Exception('socket_recv() ERROR:' . socket_last_error());
        }

        $len = unpack('N', $buf)[1];
        $rawContent = $buf;
        if ($len > 0) {
            if (($res = socket_recv($fd, $buf, $len, MSG_WAITALL)) < $len) {
                throw new \Exception('socket_recv() ERROR:' . socket_last_error());
            }
            $rawContent .= $buf;
        }
        /** @var TaskResponse $response */
        $response = Pack::unpack($rawContent);
        return $response;
    }

    /**
     * 关闭连接
     * @param resource $fd
     */
    private function closeByFd($fd)
    {
        self::log('close #' . (int)$fd);
        socket_close($fd);
        unset($this->clients[(int)$fd]);
    }

    /**
     * 发数据
     * @param $fd
     * @param $data
     * @return bool
     */
    private function sendByFd($fd, $data)
    {
        $len = strlen($data);
        $socketErr = '';
        $res = '';

        while ($len > 0) {
            $res = socket_send($fd, $data, $len, 0);
            if ($res < 0 && ($socketErr = socket_last_error())
                && !in_array($socketErr, [SOCKET_EWOULDBLOCK, SOCKET_EAGAIN])
            ) {
                ToolsAbstract::log('socket_send() ERROR:' . $socketErr, 'exception.log');
                break;
            }

            $len -= $res;
            if ($len <= 0) {
                break;
            }
        }
        return $len > 0 ? false : true;
    }

    private static function log($data)
    {
        ToolsAbstract::log($data, MQProcess::LOG_FILE);
    }

}
