<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\login;
use tests\service\ApplicationTest;

class loginTest extends ApplicationTest
{
    public function getModel()
    {
        return new login();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\login', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\customer\LoginRequest', login::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\common\Merchant', login::response());
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
		$request = login::request();
		$request->setUsername('文城');
		$request->setPassword(md5('123456'));
		$this->header->setRoute('merchant.login');
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

	public function testWrongPassword()
	{
		$this->request->setRemote(false);
		$request = login::request();
		$request->setUsername('文城');
		$request->setPassword(md5('11111'));
		$this->header->setRoute('merchant.login');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(8002, $header->getCode());
		$this->assertFalse($data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}


	public function testMerchantNotFound()
	{
		$this->request->setRemote(false);
		$request = login::request();
		$request->setUsername('文城111');
		$request->setPassword(md5('11111'));
		$this->header->setRoute('merchant.login');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(8001, $header->getCode());
		$this->assertFalse($data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

}