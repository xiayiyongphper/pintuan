<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\searchProduct;
use tests\service\ApplicationTest;

class searchProductTest extends ApplicationTest
{
    public function getModel()
    {
        return new searchProduct();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\searchProduct', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\merchant\searchProductRequest', searchProduct::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\merchant\searchProductResponse', searchProduct::response());
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
		$request = searchProduct::request();
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
		$request->setCategoryId(88);
		$request->setCategoryLevel(3);
		//$request->setBrand('好韵味');
		$request->setPage(1);
		$this->header->setRoute('merchant.searchProduct');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\merchant\searchProductResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

}