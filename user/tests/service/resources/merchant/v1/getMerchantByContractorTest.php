<?php
namespace tests\service\resources\sales\v1;


use framework\message\Message;
use service\components\Tools;
use service\message\common\SourceEnum;
use service\resources\merchant\v1\getMerchantByContractor;
use tests\service\ApplicationTest;

class getMerchantByContractorTest extends ApplicationTest
{
    public function getModel()
    {
        return new getMerchantByContractor();
    }

    public function testModel()
    {
        $this->assertInstanceOf('service\resources\merchant\v1\getMerchantByContractor', $this->model);
    }

    public function testRequest()
    {
        $this->assertInstanceOf('service\message\merchant\ContractorMerchantRequest', getMerchantByContractor::request());
    }

    public function testResponse()
    {
        $this->assertInstanceOf('service\message\merchant\ContractorMerchantResponse', getMerchantByContractor::response());
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
		$request = getMerchantByContractor::request();

		$request->setFrom([
			'contractor_id'=>$this->_contractor_city_manager_id,
			'auth_token'=>$this->_contractor_city_manager_auth_token,
			//'key_word'=>'文城',
			'pagination'=>[
				'page'=>1,
				'page_size'=>10,
			],
		]);

		$this->header->setRoute('merchant.getMerchantByContractor');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\merchant\ContractorMerchantResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertTrue($data);

	}

	public function testContractor()
	{
		$this->request->setRemote(false);
		$request = getMerchantByContractor::request();

		$request->setFrom([
			'contractor_id'=>$this->_contractor_city_manager_id,
			'auth_token'=>$this->_contractor_city_manager_auth_token,
			//'key_word'=>'文城',
		]);

		$this->header->setRoute('merchant.getMerchantByContractor');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		$this->assertEquals(0, $header->getCode());
		$this->assertInstanceOf('service\message\merchant\ContractorMerchantResponse', $data);

		//$this->assertEquals(4601, $header->getCode());
		//$this->assertTrue($data);

	}


	public function testNoPermission()
	{
		$this->request->setRemote(false);
		$request = getMerchantByContractor::request();

		$request->setFrom([
			'contractor_id'=>$this->_contractor_id,
			'auth_token'=>$this->_contractor_auth_token,
			//'key_word'=>'文城',
		]);

		$this->header->setRoute('merchant.getMerchantByContractor');
		$this->header->setSource(SourceEnum::MERCHANT);
		$rawBody = Message::pack($this->header, $request);
		$this->request->setRawBody($rawBody);
		$response = $this->application->handleRequest($this->request);
		$this->assertNotEmpty($response);
		/** @var \service\message\common\ResponseHeader $header */
		list($header, $data) = $response;
		//$this->assertEquals(0, $header->getCode());
		//$this->assertInstanceOf('service\message\merchant\ContractorMerchantResponse', $data);

		$this->assertEquals(9004, $header->getCode());
		//$this->assertTrue($data);

	}

}