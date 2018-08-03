<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\updateItems;
use tests\service\ApplicationTest;

class updateItemsTest extends ApplicationTest
{
    public function getModel()
    {
        return new updateItems();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\updateItems', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\customer\UpdateCartItemsRequest', updateItems::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\common\UniversalResponse', updateItems::response());
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
		$request = updateItems::request();

		$request->setFrom([
			'customer_id'=>$this->_customer_id,
			'auth_token'=>$this->_auth_token,
			'products'=>[
				['product_id' =>210, 'wholesaler_id' =>$this->_wholesaler_id, 'num' =>1,],
				['product_id' =>211, 'wholesaler_id' =>$this->_wholesaler_id, 'num' =>2,],
			],
		]);

		$this->header->setRoute('merchant.updateItems');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\common\UniversalResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertTrue($data);

	}

	public function testSoldOut()
	{
		$this->request->setRemote(false);
		$request = updateItems::request();

		$request->setFrom([
			'customer_id'=>$this->_customer_id,
			'auth_token'=>$this->_auth_token,
			'products'=>[
				['product_id' =>210, 'wholesaler_id' =>$this->_wholesaler_id, 'num' =>99999,],
				['product_id' =>211, 'wholesaler_id' =>$this->_wholesaler_id, 'num' =>99999,],
			],
		]);

		$this->header->setRoute('merchant.updateItems');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\common\UniversalResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertTrue($data);

	}

}