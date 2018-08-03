<?php
namespace tests\service;

use framework\Application;
use framework\Request;
use framework\resources\ApiAbstract;
use PHPUnit\Framework\TestCase;
use service\message\common\Header;
use service\resources\ResourceAbstract;
use tests\AbstractTest;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-10-27
 * Time: 下午6:08
 * Email: henryzxj1989@gmail.com
 */
abstract class ApplicationTest extends AbstractTest
{
    /**
     * @var ResourceAbstract
     */
    protected $model;

    /**
     * @var Header
     */
    protected $header;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Application
     */
    protected $application;

	public $_customer_id = 72;
	public $_auth_token = 'FOwLs6prG2g8JTYz';

	public $_wholesaler_id = 2;
	public $_wholesaler_auth_token = 'HzdNWeMTy8szNXjH';

	public $_contractor_city_manager_id = 28;
	public $_contractor_city_manager_auth_token = '2CgyIYxVAlKIcqQi';

	public $_contractor_id = 27;
	public $_contractor_auth_token = '2CgyIYxVAlKIcqQi';

    /**
     * @return ApiAbstract
     */
    abstract protected function getModel();

    /**
     * Set up
     */
    public function setUp()
    {
        parent::setUp();
        $this->model = $this->getModel();
        $this->header = new Header();
        $this->request = new Request();
        $this->application = new Application($this->config);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->model = null;
        $this->header = null;
        $this->request = null;
    }
}