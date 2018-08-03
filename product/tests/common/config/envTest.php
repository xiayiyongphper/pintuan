<?php
namespace tests\common\config;

use PHPUnit\Framework\TestCase;
use service\components\Tools;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-10-27
 * Time: 下午6:54
 * Email: henryzxj1989@gmail.com
 */
class envTest extends TestCase
{
	public function testRemoveLogs(){
		$log_path = Tools::getLogPath();
		$dir = dir($log_path);
		while($file = $dir->read())
		{
			$absolute_path = $log_path.DIRECTORY_SEPARATOR.$file;
			if((!is_dir($absolute_path)) AND ($file!=".") AND ($file!="..") AND pathinfo($absolute_path)['extension']=='log'){

				unlink($absolute_path);
			}
		}
		$dir->close();
	}
    public function testGetEnvServerIp()
    {
        $this->assertNotEmpty(ENV_SERVER_IP);
    }

    public function testGetEnvServerLocalIp()
    {
        $this->assertNotEmpty(ENV_SERVER_LOCAL_IP);
    }

    public function testGetEnvRedisHost()
    {
        $this->assertNotEmpty(ENV_REDIS_HOST);
    }

    public function testGetEnvRedisPort()
    {
        $this->assertNotEmpty(ENV_REDIS_PORT);
    }
}