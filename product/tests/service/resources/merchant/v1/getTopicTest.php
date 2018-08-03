<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\getTopic;
use tests\service\ApplicationTest;

class getTopicTest extends ApplicationTest
{
    public function getModel()
    {
        return new getTopic();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\getTopic', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\merchant\thematicActivityRequest', getTopic::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\merchant\thematicActivityResponse', getTopic::response());
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
		$request = getTopic::request();
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$request->setIdentifier('featured_product_list');
		$request->setRuleId(150);
		$this->header->setRoute('merchant.getTopic');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\merchant\thematicActivityResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

	public function testSelectionTopic()
	{
		$this->request->setRemote(false);
		$request = getTopic::request();
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$request->setIdentifier('selectionTopic');
		$this->header->setRoute('merchant.getTopic');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\merchant\thematicActivityResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

	public function testNewArriveTopic()
	{
		$this->request->setRemote(false);
		$request = getTopic::request();
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$request->setIdentifier('newArriveTopic');
		$this->header->setRoute('merchant.getTopic');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\merchant\thematicActivityResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

}