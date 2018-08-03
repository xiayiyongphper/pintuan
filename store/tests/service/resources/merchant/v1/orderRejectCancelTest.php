<?php
namespace tests\service\resources\sales\v1;


use common\models\SalesFlatOrder;
use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\orderRejectCancel;
use tests\service\ApplicationTest;

class orderRejectCancelTest extends ApplicationTest
{
    public function getModel()
    {
        return new orderRejectCancel();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\orderRejectCancel', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\common\OrderAction', orderRejectCancel::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\common\Order', orderRejectCancel::response());
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
		// 把订单置为申请取消
		$order_id = 234381;
		$order = SalesFlatOrder::findOne(['entity_id'=>$order_id]);
		$order->setAttribute('hold_before_state', 'processing');
		$order->setAttribute('hold_before_status', 'processing_receive');
		$order->setAttribute('state', 'holded');
		$order->setAttribute('status', 'holded');
		$order->save();

		$this->request->setRemote(false);
		$request = orderRejectCancel::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$request->setOrderId($order_id);
		$this->header->setRoute('merchant.orderRejectCancel');
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
		$request = orderRejectCancel::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		//$request->setOrderId($order_id);
		$this->header->setRoute('merchant.orderRejectCancel');
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
		$request = orderRejectCancel::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$request->setOrderId($order_id);
		$this->header->setRoute('merchant.orderRejectCancel');
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
		$request = orderRejectCancel::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$request->setOrderId($order_id);
		$this->header->setRoute('merchant.orderRejectCancel');
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

	public function testOrderCantRejectCancel()
	{
		// 把订单置为已拒单
		$order_id = 234381;
		$order = SalesFlatOrder::findOne(['entity_id'=>$order_id]);
		$order->setAttribute('state', 'closed');
		$order->setAttribute('status', 'rejected_closed');
		$order->save();

		$this->request->setRemote(false);
		$request = orderRejectCancel::request();
		$request->setWholesalerId($this->_wholesaler_id);
		$request->setAuthToken($this->_wholesaler_auth_token);
		$request->setOrderId($order_id);
		$this->header->setRoute('merchant.orderRejectCancel');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		/** @var \service\message\common\Order $data*/
		list($header, $data) = $response;
		$this->assertEquals(8204, $header->getCode());

	}

}