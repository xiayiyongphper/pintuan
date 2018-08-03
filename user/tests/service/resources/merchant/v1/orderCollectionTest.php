<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\orderCollection;
use tests\service\ApplicationTest;

class orderCollectionTest extends ApplicationTest
{
    public function getModel()
    {
        return new orderCollection();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\orderCollection', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\sales\OrderCollectionRequest', orderCollection::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\sales\OrderCollectionResponse', orderCollection::response());
    }

    public function testGetHeader()
    {
        $this->assertInstanceOf('service\message\common\Header', $this->header);
    }

    public function testGetRequest()
    {
        $this->assertInstanceOf('framework\Request', $this->request);
    }

	public function testNew()
	{
		$this->request->setRemote(false);
		$request = orderCollection::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$request->setState('new');
		$request->setKeyword('佰');
		$this->header->setRoute('merchant.orderCollection');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\sales\OrderCollectionResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

	public function testShipping()
	{
		$this->request->setRemote(false);
		$request = orderCollection::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$request->setState('shipping');
		$request->setKeyword('佰');
		$this->header->setRoute('merchant.orderCollection');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\sales\OrderCollectionResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

	public function testApplyCancel()
	{
		$this->request->setRemote(false);
		$request = orderCollection::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$request->setState('apply_cancel');
		$request->setKeyword('佰');
		$this->header->setRoute('merchant.orderCollection');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\sales\OrderCollectionResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

	public function testAll()
	{
		$this->request->setRemote(false);
		$request = orderCollection::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$request->setState('all');
		$request->setKeyword('佰');
		$this->header->setRoute('merchant.orderCollection');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\sales\OrderCollectionResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

	public function testDefaultWithTime()
	{
		$this->request->setRemote(false);
		$request = orderCollection::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$request->setState('a wrong stat');
		$request->setKeyword('佰');
		$request->setTime('2016-01-01 00:00:00');
		$this->header->setRoute('merchant.orderCollection');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\sales\OrderCollectionResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}


}