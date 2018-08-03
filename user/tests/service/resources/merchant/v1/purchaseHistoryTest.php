<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\purchaseHistory;
use tests\service\ApplicationTest;

class purchaseHistoryTest extends ApplicationTest
{
    public function getModel()
    {
        return new purchaseHistory();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\purchaseHistory', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\merchant\purchaseHistoryRequest', purchaseHistory::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\merchant\searchProductResponse', purchaseHistory::response());
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
		$request = purchaseHistory::request();

		$request->setFrom([
			'customer_id'=>$this->_customer_id,
			'auth_token'=>$this->_auth_token,
			'wholesaler_id'=>$this->_wholesaler_id,
			//'category_id'
			//'category_level'
			//'key_word'=>'文城',
		]);

		$this->header->setRoute('merchant.purchaseHistory');
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
		//$this->assertTrue($data);

	}

}