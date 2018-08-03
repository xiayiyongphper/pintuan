<?php
namespace tests\service\resources\sales\v1;


use common\models\DeviceToken;
use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\postDeviceToken;
use tests\service\ApplicationTest;

class postDeviceTokenTest extends ApplicationTest
{
    public function getModel()
    {
        return new postDeviceToken();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\postDeviceToken', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\customer\PostDeviceTokenRequest', postDeviceToken::request());
    }

    public function testResponse()
    {
        $this->assertTrue(postDeviceToken::response());
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
		$request = postDeviceToken::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$request->setSystem('1');
		$request->setToken('test');
		$request->setChannel('300001');
		$request->setTypequeue('123');

		$this->header->setRoute('merchant.postDeviceToken');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertTrue($data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

	public function testNew()
	{
		// 删掉这个token
		DeviceToken::deleteAll(['merchant_id' => $this->_wholesaler_id]);

		$this->request->setRemote(false);
		$request = postDeviceToken::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$request->setSystem('1');
		$request->setToken('test');
		$request->setChannel('300001');
		$request->setTypequeue('123');

		$this->header->setRoute('merchant.postDeviceToken');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertTrue($data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

	public function testAuthExpire()
	{
		$this->request->setRemote(false);
		$request = postDeviceToken::request();
		$request->setWholesalerId(0);
		$request->setAuthToken('expire token');
		$request->setSystem('1');
		$request->setToken('test');
		$request->setChannel('300001');
		$request->setTypequeue('123');

		$this->header->setRoute('merchant.postDeviceToken');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		//$this->assertEquals(0, $header->getCode());
		//$this->assertTrue($data);

		$this->assertEquals(8003, $header->getCode());
		//$this->assertFalse($data);

	}

}