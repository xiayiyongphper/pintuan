<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\Order;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\getProductBrief;
use tests\service\ApplicationTest;

class getProductBriefTest extends ApplicationTest
{
    public function getModel()
    {
        return new getProductBrief();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\getProductBrief', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\merchant\getProductBriefRequest', getProductBrief::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\merchant\getProductBriefResponse', getProductBrief::response());
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
		$request = getProductBrief::request();
		$request->setCity('440300');
		$request->appendProductIds(151);
		$request->appendProductIds(152);
		$request->appendProductIds(153);
		$request->appendProductIds(154);

        $this->header->setRoute('merchant.getProductBrief');
        $this->header->setSource(SourceEnum::MERCHANT);
        $rawBody = Message::pack($this->header, $request);
        $this->request->setRawBody($rawBody);
        $response = $this->application->handleRequest($this->request);
        $this->assertNotEmpty($response);
        /** @var Order $data */
        /** @var \service\message\common\ResponseHeader $header */
        list($header, $data) = $response;
        $this->assertEquals(0, $header->getCode());
        $this->assertInstanceOf('service\message\merchant\getProductBriefResponse', $data);
    }

}