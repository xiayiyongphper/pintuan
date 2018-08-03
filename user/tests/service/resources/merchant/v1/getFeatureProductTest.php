<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\getFeatureProduct;
use tests\service\ApplicationTest;

class getFeatureProductTest extends ApplicationTest
{
    public function getModel()
    {
        return new getFeatureProduct();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\getFeatureProduct', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\merchant\getFeatureProductsRequest', getFeatureProduct::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\merchant\getFeatureProductsResponse', getFeatureProduct::response());
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
		$request = getFeatureProduct::request();

		$request->setFrom([
			'customer_id'=>$this->_customer_id,
			'auth_token'=>$this->_auth_token,
			'wholesaler_id'=>$this->_wholesaler_id,
		]);

		$this->header->setRoute('merchant.getFeatureProduct');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\merchant\getFeatureProductsResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertTrue($data);

	}

}