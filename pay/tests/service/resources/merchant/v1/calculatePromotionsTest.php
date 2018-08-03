<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\calculatePromotions;
use tests\service\ApplicationTest;

class calculatePromotionsTest extends ApplicationTest
{
    public function getModel()
    {
        return new calculatePromotions();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\calculatePromotions', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\sales\CalculatePromotionsRequest', calculatePromotions::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\sales\CalculatePromotionsResponse', calculatePromotions::response());
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
		$request = calculatePromotions::request();

		$request->setFrom([
			'customer_id'=>$this->_customer_id,
			'auth_token'=>$this->_auth_token,
			'items'=>[
				[
					'wholesaler_id'=>$this->_wholesaler_id,
					'product_list'=>[
						['product_id'=>210,'num'=>10],
						['product_id'=>211,'num'=>20],
					],
				],
			],
		]);

		$this->header->setRoute('merchant.calculatePromotions');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\sales\CalculatePromotionsResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertTrue($data);

	}

}