<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\Order;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\getStoresByAreaIdsV2;
use tests\service\ApplicationTest;

class getStoresByAreaIdsV2Test extends ApplicationTest
{
    public function getModel()
    {
        return new getStoresByAreaIdsV2();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\getStoresByAreaIdsV2', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\merchant\getStoresByAreaIdsRequest', getStoresByAreaIdsV2::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\merchant\getStoresByAreaIdsResponse', getStoresByAreaIdsV2::response());
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
		$request = getStoresByAreaIdsV2::request();
		$request->appendAreaIds(5);
		$request->setCustomerId($this->_customer_id);
		$request->setAuthToken($this->_auth_token);
        $this->header->setRoute('merchant.getStoresByAreaIdsV2');
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