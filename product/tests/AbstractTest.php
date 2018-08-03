<?php
namespace tests;

use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-10-28
 * Time: 上午9:41
 * Email: henryzxj1989@gmail.com
 */
abstract class AbstractTest extends TestCase
{
    /**
     * @var array
     */
    protected $config;

    /**
     * Set up
     */
    public function setUp()
    {
        global $config;
        $this->config = $config;
    }

    public function tearDown()
    {
        $this->config = null;
    }
}