<?php
namespace framework\process;

use framework\components\es\Collectd;
use framework\components\ToolsAbstract;
use framework\core\ProcessInterface;
use framework\core\SWServer;
use SysInfo\SysInfo;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-6-2
 * Time: 上午11:12
 */
class ESProcess implements ProcessInterface
{
    /**
     * @inheritdoc
     */
    public function run(SWServer $SWServer, \swoole_process $process)
    {
        while (true) {
            try {
                sleep(60);
                self::collectSysinfo();
            } catch (\Exception $e) {
                ToolsAbstract::logException($e);
                sleep(60);
            }
        }
    }

    /**
     * 收集系统信息
     */
    public static function collectSysinfo()
    {
        /** @var \SysInfo\SysInfoInterface $sysinfo */
        $sysinfo = SysInfo::factory();
        /** @var \SysInfo\Linux\Load $load */
        $load = $sysinfo->getLoad();
        $loads = $load->getAvg();
        Collectd::get()->report('load_avg_5', $loads[0]);
        Collectd::get()->report('load_avg_10', $loads[1]);
        Collectd::get()->report('load_avg_15', $loads[2]);
        /** @var \SysInfo\Linux\CPU $cpu */
        $cpu = $sysinfo->getCPU();
        Collectd::get()->report('cpu_user_time', $cpu->getUsertime());
        Collectd::get()->report('cpu_idle_time', $cpu->getIdletime());
        Collectd::get()->report('cpu_system_time', $cpu->getSystemtime());
        /** @var \SysInfo\Linux\Disk $disk */
        $disk = $sysinfo->getDisk();
        Collectd::get()->report('disk_reads', $disk->getReads());
        Collectd::get()->report('disk_writes', $disk->getWrites());
        Collectd::get()->report('disk_time_spend_reading', $disk->getTimeSpentReading());
        Collectd::get()->report('disk_time_spend_writing', $disk->getTimeSpentWriting());
        /** @var \SysInfo\Linux\Memory $memory */
        $memory = $sysinfo->getMemory();
        Collectd::get()->report('memory_total', $memory->getTotal());
        Collectd::get()->report('memory_used', $memory->getUsed());
        Collectd::get()->report('memory_free', $memory->getFree());
        /** @var \SysInfo\Linux\Uptime $upTime */
        $upTime = $sysinfo->getUptime();
        Collectd::get()->report('uptime', $upTime->getUptime());
        Collectd::get()->report('idle_time', $upTime->getIdletime());
    }
}