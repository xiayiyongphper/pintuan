<?php
namespace framework\bench;

use framework\components\ToolsAbstract;
use framework\message\Message;
use service\message\common\Header;
use service\message\common\SourceEnum;
use service\message\core\ConfigRequest;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-10-13
 * Time: 下午3:17
 * Email: henryzxj1989@gmail.com
 */
class Benchmark
{
    protected $logFile = 'bench.log';
    /**
     * 并发数
     * @var integer
     */
    protected $concurrency = 100;

    /**请求次数
     * @var integer
     */
    protected $numReq = 300;

    /**
     * 每个进程处理的请求数
     * @var integer
     */
    protected $processReqNum = 1000;

    /**等待所有进程已就绪开始测试
     * @var bool
     */
    protected $waitAllProcessStarted = false;

    /**
     * @var string
     */
    protected $host = '172.16.10.201';

    /**
     * @var integer
     */
    protected $port = 9090;

    /**
     * @var array
     */
    protected $childPid;

    /**
     * @var integer
     */
    protected $mainPid;

    protected $pid;

    protected $data;

    protected $connTotal = 0;
    protected $connMin = 999;
    protected $connMax = 0;
    protected $reqTotal = 0;
    protected $reqMin = 999;
    protected $reqMax = 0;
    protected $sendBytes = 0;
    protected $recvBytes = 0;
    protected $error = 0;

    protected $redis;

    public function run()
    {
        $this->startProcess();
    }

    protected function startProcess()
    {
        $this->mainPid = posix_getpid();
        for ($i = 0; $i < $this->concurrency; $i++) {
            $this->childPid[] = $this->start();
        }

        for ($i = 0; $i < $this->concurrency; $i++) {
            $status = 0;
            $pid = pcntl_wait($status);
            $this->log("child $pid completed");
        }
    }

    protected function start()
    {
        $pid = pcntl_fork();
        if ($pid > 0) {
            return $pid;
        } elseif ($pid == 0) {
            $this->log('fork success:start worker');
            $this->worker();
            exit(0);
        } else {
            $this->log('Error:fork fail');
        }
    }

    protected function worker()
    {
        $this->error = 0;
        $this->pid = posix_getpid();

        for ($i = 0; $i < $this->processReqNum; $i++) {
            if (!$this->doTest()) {
                $this->error++;
            }
        }
        $this->logPid('reqMin:' . $this->reqMin, $this->pid);
        $this->logPid('reqMax:' . $this->reqMax, $this->pid);
        $this->logPid('reqTotal:' . $this->reqTotal, $this->pid);
        $this->logPid('connMin:' . $this->connMin, $this->pid);
        $this->logPid('connMax:' . $this->connMax, $this->pid);
        $this->logPid('connTotal:' . $this->connTotal, $this->pid);
        $this->logPid('sendBytes:' . $this->sendBytes, $this->pid);
        $this->logPid('recvBytes:' . $this->recvBytes, $this->pid);
        $this->logPid('error:' . $this->error, $this->pid);
        exit(0);
    }

    protected function doTest()
    {
        static $client = null;
        static $i;
        $start = microtime(true);
        if (empty($client)) {
            $client = new \swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_SYNC);
            $client->set($this->getClientConfig());
            $i = 0;
            if (!$client->connect($this->host, $this->port, 2)) {
                error:
                $this->logPid("Error: " . swoole_strerror($client->errCode) . "[{$client->errCode}]", $this->pid);
                $client = null;
                return false;
            }
        }


        $end = microtime(true);
        $connTime = $end - $start;

        $this->connTotal += $connTime;

        if ($connTime > $this->connMax) {
            $this->connMax = $connTime;
        }

        if ($connTime < $this->connMin) {
            $this->connMin = $connTime;
        }

        $start = $end;

        /*--------写入Sokcet-------*/
        if (!$client->send($this->data)) {
            goto error;
        }
        $this->sendBytes += strlen($this->data);

        /*--------读取Sokcet-------*/
        $ret = $client->recv();
        $i++;
        if (empty($ret)) {
            $this->logPid("#$i@ is lost", $this->pid);
            return false;
        }
        $this->recvBytes += strlen($ret);
        $end = microtime(true);
        $reqTime = $end - $start;
        $this->reqTotal += $reqTime;
        if ($reqTime > $this->reqMax) {
            $this->reqMax = $reqTime;
        }
        if ($reqTime < $this->reqMin) {
            $this->reqMin = $reqTime;
        }
        return true;
    }

    protected function getClientConfig()
    {
        return [
            'open_length_check' => true,
            'package_length_type' => 'N',
            'package_length_offset' => 0,       //第N个字节是包长度的值
            'package_body_offset' => 4,       //第几个字节开始计算长度
            'package_max_length' => 2000000,  //协议最大长度
            'socket_buffer_size' => 2097152, //2M缓存区
        ];
    }

    protected function logPid($msg, $pid = 0)
    {
        $file = null;
        if ($pid > 0) {
            $file = $pid . '.log';
        }
        $this->log($msg, $file);
    }

    protected function log($msg, $file = null)
    {
        if (is_null($file)) {
            $file = 'system.log';
        }
        ToolsAbstract::log($msg, $file);
    }

    public function __construct()
    {
        $header = new Header();
        $header->setVersion(1);
        $header->setSource(SourceEnum::CORE);
        $header->setRoute('core.config');
        $request = new ConfigRequest();
        $request->setVer(1);
        $request->setSystem(1);
        $request->setChannel(1);
        $this->data = Message::pack($header, $request);
    }
}