<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\getProduct;
use tests\service\ApplicationTest;

class getProductTest extends ApplicationTest
{
    public function getModel()
    {
        return new getProduct();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\getProduct', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\merchant\getProductRequest', getProduct::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\merchant\getProductResponse', getProduct::response());
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
		$request = getProduct::request();
		$request->setWholesalerId(2);
		$request->appendProductIds(151);
		$request->appendProductIds(152);
		$request->appendProductIds(153);
		$request->appendProductIds(154);
		$request->setRecommendNum(3);
		$this->header->setRoute('merchant.getProduct');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\merchant\getProductResponse', $data);
	}

	public function testWrongWholesaler()
	{
		// 请求一个不在配送区域内的店铺
		$this->request->setRemote(false);
		$request = getProduct::request();
		$request->setWholesalerId(9);
		$request->appendProductIds(349);
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$this->header->setRoute('merchant.getProduct');
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

	public function testRunWithCustomer()
	{
		//
		$this->request->setRemote(false);
		$request = getProduct::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->appendProductIds(151);
		$request->appendProductIds(152);
		$request->appendProductIds(123456789);
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$this->header->setRoute('merchant.getProduct');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\merchant\getProductResponse', $data);
	}

	public function testProductIdsAllWrong()
	{
		// 全填错
		$this->request->setRemote(false);
		$request = getProduct::request();
		$request->setWholesalerId(5);
		$request->appendProductIds(123456789);
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$this->header->setRoute('merchant.getProduct');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(4501, $header->getCode());
		$this->assertFalse($data);
	}

	public function testNoProductIds()
	{
		// 不填商品
		$this->request->setRemote(false);
		$request = getProduct::request();
		$request->setWholesalerId(5);
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$this->header->setRoute('merchant.getProduct');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(4501, $header->getCode());
		$this->assertFalse($data);
    }

}