<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\getAreaCategory;
use tests\service\ApplicationTest;

class getAreaCategoryTest extends ApplicationTest
{
    public function getModel()
    {
        return new getAreaCategory();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\getAreaCategory', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\merchant\getAreaCategoryRequest', getAreaCategory::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\common\CategoryNode', getAreaCategory::response());
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
		$request = getAreaCategory::request();
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$this->header->setRoute('merchant.getAreaCategory');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\common\CategoryNode', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

	public function testRunWithWholesalerId()
	{
		$this->request->setRemote(false);
		$request = getAreaCategory::request();
		$request->setWholesalerId(3);
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$this->header->setRoute('merchant.getAreaCategory');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\common\CategoryNode', $data);

	}

}