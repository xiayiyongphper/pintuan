<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Redis;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\getFirstCategory;
use tests\service\ApplicationTest;

class getFirstCategoryTest extends ApplicationTest
{
    public function getModel()
    {
        return new getFirstCategory();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\getFirstCategory', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\core\getCategoryRequest', getFirstCategory::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\common\CategoryNode', getFirstCategory::response());
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
		$request = getFirstCategory::request();

		$request->setFrom([
			//'customer_id'=>$this->_customer_id,
			//'auth_token'=>$this->_auth_token,
			'wholesaler_id'=>$this->_wholesaler_id,
			'city'=>'440300',
		]);

		$this->header->setRoute('merchant.getFirstCategory');
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


	public function testWrongWholesalerId()
	{
		$this->request->setRemote(false);
		$request = getFirstCategory::request();

		$request->setFrom([
			//'customer_id'=>$this->_customer_id,
			//'auth_token'=>$this->_auth_token,
			'wholesaler_id'=>999999,
			'city'=>'440300',
		]);

		$this->header->setRoute('merchant.getFirstCategory');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		//$this->assertEquals(0, $header->getCode());
		//$this->assertInstanceOf('service\message\common\CategoryNode', $data);

		$this->assertEquals(1001, $header->getCode());
		//$this->assertTrue($data);

	}

	///*
	// * 此测试需要127.0.0.1 pms.lelai.com 模拟pms系统无响应
	// */
	//public function testPMSdown()
	//{
	//
	//	// 清理掉pms缓存
	//	$redis = Tools::getRedis();
	//	$redis->del(Redis::REDIS_KEY_PMS_CATEGORIES);
	//
	//	$this->request->setRemote(false);
	//	$request = getFirstCategory::request();
	//
	//	$request->setFrom([
	//		//'customer_id'=>$this->_customer_id,
	//		//'auth_token'=>$this->_auth_token,
	//		'wholesaler_id'=>$this->_merchant_id,
	//		'city'=>'440300',
	//	]);
	//
	//	$this->header->setRoute('merchant.getFirstCategory');
	//	$this->header->setSource(SourceEnum::MERCHANT);
	//	$rawBody = Message::pack($this->header, $request);
	//	$this->request->setRawBody($rawBody);
	//	$response = $this->application->handleRequest($this->request);
	//	$this->assertNotEmpty($response);
	//	/** @var \service\message\common\ResponseHeader $header */
	//	list($header, $data) = $response;
	//	//$this->assertEquals(0, $header->getCode());
	//	//$this->assertInstanceOf('service\message\common\CategoryNode', $data);
	//
	//	$this->assertEquals(1001, $header->getCode());
	//	//$this->assertTrue($data);
	//
	//}

}