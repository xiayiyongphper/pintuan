<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\getFirstCategoryFromPms;
use tests\service\ApplicationTest;

class getFirstCategoryFromPmsTest extends ApplicationTest
{
    public function getModel()
    {
        return new getFirstCategoryFromPms();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\getFirstCategoryFromPms', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\core\getCategoryRequest', getFirstCategoryFromPms::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\common\CategoryNode', getFirstCategoryFromPms::response());
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
		$request = getFirstCategoryFromPms::request();

		$request->setFrom([
			//'customer_id'=>$this->_customer_id,
			//'auth_token'=>$this->_auth_token,
			'wholesaler_id'=>$this->_wholesaler_id,
			'city'=>'440300',
		]);

		$this->header->setRoute('merchant.getFirstCategoryFromPms');
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
		//$this->assertTrue($data);

	}

}