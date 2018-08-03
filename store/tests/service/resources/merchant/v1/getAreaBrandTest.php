<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\Order;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\getAreaBrand;
use tests\service\ApplicationTest;

class getAreaBrandTest extends ApplicationTest
{
    public function getModel()
    {
        return new getAreaBrand();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\getAreaBrand', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\merchant\getAreaBrandRequest', getAreaBrand::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\merchant\getAreaBrandResponse', getAreaBrand::response());
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
		$request = getAreaBrand::request();
		$request->setWholesalerId(2);
		$request->setCategoryId(103);
		$request->setCategoryLevel(1);
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
        $this->header->setRoute('merchant.getAreaBrand');
        $this->header->setSource(SourceEnum::MERCHANT);
        $rawBody = Message::pack($this->header, $request);
        $this->request->setRawBody($rawBody);
        $response = $this->application->handleRequest($this->request);
        $this->assertNotEmpty($response);
        /** @var \service\message\common\ResponseHeader $header */
        list($header, $data) = $response;
        $this->assertEquals(0, $header->getCode());
        $this->assertInstanceOf('service\message\merchant\getAreaBrandResponse', $data);


		$this->request->setRemote(false);
		$request = getAreaBrand::request();
		$request->setWholesalerId(2);
		$request->setCategoryId(104);
		$request->setCategoryLevel(2);
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$this->header->setRoute('merchant.getAreaBrand');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\merchant\getAreaBrandResponse', $data);


		$this->request->setRemote(false);
		$request = getAreaBrand::request();
		$request->setWholesalerId(2);
		$request->setCategoryId(109);
		$request->setCategoryLevel(3);
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$this->header->setRoute('merchant.getAreaBrand');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\merchant\getAreaBrandResponse', $data);


		$this->request->setRemote(false);
		$request = getAreaBrand::request();
		$request->setCategoryId(123456);
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$this->header->setRoute('merchant.getAreaBrand');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(4601, $header->getCode());
		$this->assertFalse($data);


    }

}