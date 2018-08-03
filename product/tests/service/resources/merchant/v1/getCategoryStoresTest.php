<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\getCategoryStores;
use tests\service\ApplicationTest;

class getCategoryStoresTest extends ApplicationTest
{
	public function getModel()
	{
		return new getCategoryStores();
	}

	public function testModel()
	{
		$this->assertInstanceOf('service\resources\merchant\v1\getCategoryStores', $this->model);
	}

	public function testRequest()
	{
		$this->assertInstanceOf('service\message\merchant\getCategoryStoresRequest', getCategoryStores::request());
	}

	public function testResponse()
	{
		$this->assertInstanceOf('service\message\merchant\getCategoryStoresResponse', getCategoryStores::response());
	}

	public function testGetHeader()
	{
		$this->assertInstanceOf('service\message\common\Header', $this->header);
	}

	public function testGetRequest()
	{
		$this->assertInstanceOf('framework\Request', $this->request);
	}

	public function testRun()
	{
		$this->request->setRemote(false);
		$request = getCategoryStores::request();
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$this->header->setRoute('merchant.getCategoryStores');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\merchant\getCategoryStoresResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

	public function testRunCategory1()
	{
		$this->request->setRemote(false);
		$request = getCategoryStores::request();
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$request->setCategoryLevel(1);
		$request->setCategoryId(103);
		$this->header->setRoute('merchant.getCategoryStores');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\merchant\getCategoryStoresResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

	public function testRunCategory2()
	{
		$this->request->setRemote(false);
		$request = getCategoryStores::request();
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$request->setCategoryLevel(2);
		$request->setCategoryId(104);
		$this->header->setRoute('merchant.getCategoryStores');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\merchant\getCategoryStoresResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

	public function testRunCategory3()
	{
		$this->request->setRemote(false);
		$request = getCategoryStores::request();
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$request->setCategoryLevel(3);
		$request->setCategoryId(109);
		$this->header->setRoute('merchant.getCategoryStores');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\merchant\getCategoryStoresResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

}