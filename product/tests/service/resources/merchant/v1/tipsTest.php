<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\tips;
use tests\service\ApplicationTest;

class tipsTest extends ApplicationTest
{
    public function getModel()
    {
        return new tips();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\tips', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\merchant\tipsRequest', tips::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\merchant\tipsResponse', tips::response());
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
		$request = tips::request();

		$request->setFrom([
			'identifier'=>'10001',
			'format'=>'text',
		]);

		$this->header->setRoute('merchant.tips');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\merchant\tipsResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

}