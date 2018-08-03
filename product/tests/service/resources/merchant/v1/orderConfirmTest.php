<?php
namespace tests\service\resources\sales\v1;


use common\models\SalesFlatOrder;
use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\orderConfirm;
use tests\service\ApplicationTest;

class orderConfirmTest extends ApplicationTest
{
    public function getModel()
    {
        return new orderConfirm();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\orderConfirm', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\common\OrderAction', orderConfirm::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\common\Order', orderConfirm::response());
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
		// 把订单置为刚下单
		$order_id = 234381;
		$order = SalesFlatOrder::findOne(['entity_id'=>$order_id]);
		$order->setAttribute('state', 'processing');
		$order->setAttribute('status', 'processing');
		$order->save();

		$this->request->setRemote(false);
		$request = orderConfirm::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$request->setOrderId($order_id);
		$this->header->setRoute('merchant.orderConfirm');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		/** @var \service\message\common\Order $data*/
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\common\Order', $data);
		$this->assertEquals('processing_receive', $data->getStatus());


		//$this->assertEquals(4601, $header->getCode());
		//$this->assertFalse($data);

	}

	public function testNoOrder()
	{
		$this->request->setRemote(false);
		$request = orderConfirm::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		//$request->setOrderId($order_id);
		$this->header->setRoute('merchant.orderConfirm');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		/** @var \service\message\common\Order $data*/
		list($header, $data) = $response;

		$this->assertEquals(5001, $header->getCode());

	}

	public function testOrderNotExisted()
	{
		$order_id = 99999;

		$this->request->setRemote(false);
		$request = orderConfirm::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$request->setOrderId($order_id);
		$this->header->setRoute('merchant.orderConfirm');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		/** @var \service\message\common\Order $data*/
		list($header, $data) = $response;

		$this->assertEquals(5001, $header->getCode());

	}

	public function testNotYourOrder()
	{
		$order_id = 191;

		$this->request->setRemote(false);
		$request = orderConfirm::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$request->setOrderId($order_id);
		$this->header->setRoute('merchant.orderConfirm');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		/** @var \service\message\common\Order $data*/
		list($header, $data) = $response;

		$this->assertEquals(8100, $header->getCode());


	}

	public function testOrderCantConfirm()
	{
		// 把订单置为已接单
		$order_id = 234381;
		$order = SalesFlatOrder::findOne(['entity_id'=>$order_id]);
		$order->setAttribute('state', 'processing');
		$order->setAttribute('status', 'processing_receive');
		$order->save();

		$this->request->setRemote(false);
		$request = orderConfirm::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$request->setOrderId($order_id);
		$this->header->setRoute('merchant.orderConfirm');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		/** @var \service\message\common\Order $data*/
		list($header, $data) = $response;
		$this->assertEquals(8200, $header->getCode());

	}

}