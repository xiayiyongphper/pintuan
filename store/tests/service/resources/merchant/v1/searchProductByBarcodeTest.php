<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\searchProductByBarcode;
use tests\service\ApplicationTest;

class searchProductByBarcodeTest extends ApplicationTest
{
    public function getModel()
    {
        return new searchProductByBarcode();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\searchProductByBarcode', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\merchant\searchProductRequest', searchProductByBarcode::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\merchant\searchProductByBarcodeResponse', searchProductByBarcode::response());
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
		$request = searchProductByBarcode::request();

		$request->setFrom([
			'customer_id' => $this->_customer_id,
			'auth_token' => $this->_auth_token,
			'wholesaler_id' => $this->_wholesaler_id,
			'category_id' => 103,
			'category_level' => 1,
			'keyword' => '牛奶',
			//'field' => 7, // 可选，排序字段，不传默认按id排序
			//'sort' => 8, // 可选，排序顺序，填asc或desc，仅当field有值时生效，不传默认按desc排
			//'brand' => 9, // 可选，筛选品牌
			//'page' => 10, // 可选，不传默认为1
			//'page_size' => 11, // 可选，不传默认为10

		]);

		$this->header->setRoute('merchant.searchProductByBarcode');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\merchant\searchProductByBarcodeResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertTrue($data);

	}

}