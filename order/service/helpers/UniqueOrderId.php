<?php
namespace service\helpers;

use service\tools\Tools;

class UniqueOrderId
{
    //开始时间,固定一个小于当前时间的毫秒数即可
    const twepoch =  1483228800000;//2017-01-01 00:00:00
    //const twepoch =  1483228800;

    //机器标识占的位数
    const workerIdBits = 6;
    const machineIdBits = 4;

    //毫秒内自增数点的位数
    const sequenceBits = 12;

    protected $workId = 0;
    protected $machineId = 0;

    //要用静态变量
    static $lastTimestamp = -1;
    static $sequence = 0;


    function __construct($machineId,$workId){
        //机器ID、workerId范围判断
        $maxMachineId = -1 ^ (-1 << self::machineIdBits);
        if($machineId > $maxMachineId || $machineId< 0){
            throw new \Exception("workerId can't be greater than ".$maxMachineId." or less than 0");
        }
        $maxWorkerId = -1 ^ (-1 << self::workerIdBits);
        if($workId > $maxWorkerId || $workId< 0){
            throw new \Exception("workerId can't be greater than ".$maxWorkerId." or less than 0");
        }
        //赋值
        $this->machineId = $machineId;
        $this->workId = $workId;
    }

    //生成一个ID
    public function nextId(){
        $timestamp = $this->timeGen();
        $lastTimestamp = self::$lastTimestamp;
        //判断时钟是否正常
        if ($timestamp < $lastTimestamp) {
            throw new \Exception("Clock moved backwards.  Refusing to generate id for %d milliseconds", ($lastTimestamp - $timestamp));
        }
        //生成唯一序列
        if ($lastTimestamp == $timestamp) {
            $sequenceMask = -1 ^ (-1 << self::sequenceBits);
            self::$sequence = (self::$sequence + 1) & $sequenceMask;
            if (self::$sequence == 0) {
                $timestamp = $this->tilNextMillis($lastTimestamp);
            }
        } else {
            self::$sequence = 0;
        }
        self::$lastTimestamp = $timestamp;

        $millisec = $timestamp % 1000;//毫秒数
        $timeStr = date("YmdHis",($timestamp - $millisec) / 1000);
        $timeStr = substr($timeStr,2);
        //时间毫秒/机器ID/workerId,要左移的位数
        $timestampLeftShift = self::sequenceBits + self::workerIdBits + self::machineIdBits;
        $machineIdShift     = self::sequenceBits + self::workerIdBits;
        $workerIdShift      = self::sequenceBits;
        //Tools::log(getmypid().'-'.$this->workId.'-'.self::$sequence,'pid.log');
        //时间毫秒、机器ID、workerId和序列号组合的二进制转十进制数字
        $serial = ($millisec << $timestampLeftShift) | ($this->machineId << $machineIdShift) | ($this->workId << $workerIdShift) | self::$sequence;
        $nextId = $timeStr.str_pad($serial,10,'0',STR_PAD_LEFT);
        return $nextId;
    }

    //取当前时间毫秒
    protected function timeGen(){
        $timestramp = (float)sprintf("%.0f", microtime(true) * 1000);
        return  $timestramp;
    }

    //取下一毫秒
    protected function tilNextMillis($lastTimestamp) {
        $timestamp = $this->timeGen();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->timeGen();
        }
        return $timestamp;
    }
}