<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\merchantAuthentication;
use tests\service\ApplicationTest;

class merchantAuthenticationTest extends ApplicationTest
{
    public function getModel()
    {
        return new merchantAuthentication();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\merchantAuthentication', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\merchant\MerchantAuthenticationRequest', merchantAuthentication::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\common\Merchant', merchantAuthentication::response());
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
		$redis = Tools::getRedis();
		$redis->delete('merchant_info_collection');

		$this->request->setRemote(false);
		$request = merchantAuthentication::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$this->header->setRoute('merchant.merchantAuthentication');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\common\Merchant', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

	public function testRedis()
	{
		$this->request->setRemote(false);
		$request = merchantAuthentication::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$this->header->setRoute('merchant.merchantAuthentication');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\common\Merchant', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

	public function testWrong()
	{
		$this->request->setRemote(false);
		$request = merchantAuthentication::request();
		$request->setWholesalerId(0);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$this->header->setRoute('merchant.merchantAuthentication');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		//$this->assertEquals(0, $header->getCode());
		//$this->assertInstanceOf('service\message\common\Merchant', $data);

		$this->assertEquals(8001, $header->getCode());
		//$this->assertFalse($data);

	}

}