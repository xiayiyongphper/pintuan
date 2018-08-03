<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\Order;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\getStoreDetail1;
use service\resources\merchant\v1\getStoresByAreaIds;
use tests\service\ApplicationTest;

class getStoresByAreaIdsTest extends ApplicationTest
{
    public function getModel()
    {
        return new getStoresByAreaIds();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\getStoresByAreaIds', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\merchant\getStoresByAreaIdsRequest', getStoresByAreaIds::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\merchant\getStoresByAreaIdsResponse', getStoresByAreaIds::response());
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
		$request = getStoresByAreaIds::request();
		$request->appendAreaIds(5);

        $this->header->setRoute('merchant.getStoresByAreaIds');
        $this->header->setSource(SourceEnum::MERCHANT);
        $rawBody = Message::pack($this->header, $request);
        $this->request->setRawBody($rawBody);
        $response = $this->application->handleRequest($this->request);
        $this->assertNotEmpty($response);
        /** @var Order $data */
        /** @var \service\message\common\ResponseHeader $header */
        list($header, $data) = $response;
        $this->assertEquals(0, $header->getCode());
        $this->assertInstanceOf('service\message\merchant\getStoresByAreaIdsResponse', $data);
    }

}