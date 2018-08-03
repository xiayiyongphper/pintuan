<?php
namespace tests\common\config;

use PHPUnit\Framework\TestCase;
use tests\AbstractTest;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-10-27
 * Time: 下午7:01
 * Email: henryzxj1989@gmail.com
 */
class mainTest extends AbstractTest
{
    public function testGetVendorPath()
    {
        $this->assertEquals(\Yii::getAlias('@framework') . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'vendor', $this->config['vendorPath']);
    }
}