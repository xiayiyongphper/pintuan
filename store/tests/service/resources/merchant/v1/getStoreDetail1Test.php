<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\Order;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\getStoreDetail1;
use tests\service\ApplicationTest;

class getStoreDetail1Test extends ApplicationTest
{
    public function getModel()
    {
        return new getStoreDetail1();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\getStoreDetail1', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\merchant\getStoreDetailRequest', getStoreDetail1::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\common\Store', getStoreDetail1::response());
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
		$request = getStoreDetail1::request();
		$request->setWholesalerId(3);
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$this->header->setRoute('merchant.getStoreDetail1');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\common\Store', $data);
	}

	public function testRunRemote()
	{
		$this->request->setRemote(true);
		$request = getStoreDetail1::request();
		$request->setWholesalerId(5);
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$this->header->setRoute('merchant.getStoreDetail1');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\common\Store', $data);
	}

	public function testRunRemoteInvalidWholesaler()
	{
		$this->request->setRemote(true);
		$request = getStoreDetail1::request();
		$request->setWholesalerId(3);
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$this->header->setRoute('merchant.getStoreDetail1');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(1002, $header->getCode());
		$this->assertFalse($data);
	}

	public function testNoWholesalerId()
	{
		$this->request->setRemote(false);
		$request = getStoreDetail1::request();
		$request->setWholesalerId(0);
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$this->header->setRoute('merchant.getStoreDetail1');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(3001, $header->getCode());
		$this->assertFalse($data);
	}

	public function testNoCustomer()
	{
		$this->request->setRemote(false);
		$request = getStoreDetail1::request();
		$request->setWholesalerId(3);
		$this->header->setRoute('merchant.getStoreDetail1');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\common\Store', $data);
	}

	public function testUNExistWholesalerId()
	{
		$this->request->setRemote(false);
		$request = getStoreDetail1::request();
		$request->setWholesalerId(123456);
		$this->header->setRoute('merchant.getStoreDetail1');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(3001, $header->getCode());
		$this->assertFalse($data);
	}

}