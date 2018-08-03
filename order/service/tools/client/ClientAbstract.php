<?php
namespace service\tools\client;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 14:33
 */
class ClientAbstract
{
	/** @var \swoole_client $_client */
	protected $_client;

	public function __construct()
	{
		$this->_client = new \swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
		$this->_client->on('Connect', array($this, 'onConnect'));
		$this->_client->on('Receive', array($this, 'onReceive'));
		$this->_client->on('Close', array($this, 'onClose'));
		$this->_client->on('Error', array($this, 'onError'));

		$this->_client->set(array(
			'open_length_check'     => 1,
			'package_length_type'   => 'N',
			'package_length_offset' => 0,       //第N个字节是包长度的值
			'package_body_offset'   => 4,       //第几个字节开始计算长度
			'package_max_length'    => 2000000,  //协议最大长度
			'socket_buffer_size'    => 1024 * 1024 * 2, //2M缓存区
		));

	}

	public function connect($host, $port, $timeout = 0.1)
	{
		$fp = $this->_client->connect($host, $port, $timeout);
		if (!$fp) {
			echo "Error:{$fp->errMsg} {$fp->errCode}" . PHP_EOL;

			return;
		}
	}

	public function onConnect($client)
	{
		echo "client connected" . PHP_EOL;
	}

	public function onReceive($client, $data)
	{
		echo $data . PHP_EOL;
	}

	public function onClose($client)
	{
		echo "client close connection" . PHP_EOL;
	}

	public function onError()
	{
		$this->close();
	}

	public function close()
	{
		$this->_client->close();
	}

	public function send($data)
	{
		$this->_client->send($data);
	}

	public function pack()
	{

	}
}