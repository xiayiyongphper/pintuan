<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\orderDetail;
use tests\service\ApplicationTest;

class orderDetailTest extends ApplicationTest
{
    public function getModel()
    {
        return new orderDetail();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\orderDetail', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\sales\OrderDetailRequest', orderDetail::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\common\Order', orderDetail::response());
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
		$request = orderDetail::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$request->setOrderId(211);
		$this->header->setRoute('merchant.orderDetail');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\common\Order', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

	public function testOrderNotExisted()
	{
		$this->request->setRemote(false);
		$request = orderDetail::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$request->setOrderId(123123123123);
		$this->header->setRoute('merchant.orderDetail');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(5001, $header->getCode());
		//$this->assertInstanceOf('service\message\common\Order', $data);

		//$this->assertEquals(4601, $header->getCode());
		$this->assertFalse($data);

	}

	public function testNoOrderId()
	{
		$this->request->setRemote(false);
		$request = orderDetail::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		//$request->setOrderId(123123123123);
		$this->header->setRoute('merchant.orderDetail');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(5001, $header->getCode());
		//$this->assertInstanceOf('service\message\common\Order', $data);

		//$this->assertEquals(4601, $header->getCode());
		$this->assertFalse($data);

	}

	public function testNotYourOrder()
	{
		$this->request->setRemote(false);
		$request = orderDetail::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$request->setOrderId(191);
		$this->header->setRoute('merchant.orderDetail');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(8100, $header->getCode());
		//$this->assertInstanceOf('service\message\common\Order', $data);

		//$this->assertEquals(4601, $header->getCode());
		$this->assertFalse($data);

	}

}