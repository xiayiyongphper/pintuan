<?php

namespace framework\components;

use framework\data\Pagination;
use framework\models\CoreConfigData;
use framework\mq\RabbitMQ;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use service\message\common\Header;
use service\message\common\Protocol;
use service\message\common\SourceEnum;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-7-26
 * Time: 下午2:45
 * Email: henryzxj1989@gmail.com
 */
abstract class ToolsAbstract
{

    public static function getDate($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }

    public static function getLogPath()
    {
        return \Yii::$app->runtimePath . DIRECTORY_SEPARATOR . 'logs';
    }

    public static function getLogFilename($file)
    {
        $file = empty($file) ? 'system.log' : $file;
        $parts = explode('.', $file);
        $ext = array_pop($parts);
        array_push($parts, date('Y-m-d'));
        array_push($parts, $ext);
        $file = implode('.', $parts);
        return $file;
    }

    public static function log($data, $filename = null)
    {
        if (!$filename) {
            $filename = 'system.log';
        }
        $filename = self::getLogFilename($filename);
        $file = self::getLogPath() . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($file, '[' . date('Y-m-d H:i:s') . '] ' . print_r($data, true) . PHP_EOL, FILE_APPEND);
    }

    /**
     * @param \Exception $e
     * @param null $filename
     */
    public static function logException($e, $filename = null)
    {
        if (!$filename) {
            $filename = 'exception.log';
        }
        $filename = self::getLogFilename($filename);
        $file = self::getLogPath() . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($file, '[' . date('Y-m-d H:i:s') . '] ' . $e->__toString() . PHP_EOL, FILE_APPEND);
    }

    /**
     * @param \Error $e
     * @param null $filename
     */
    public static function logError($e, $filename = null)
    {
        if (!$filename) {
            $filename = 'error.log';
        }
        $filename = self::getLogFilename($filename);
        $file = self::getLogPath() . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($file, '[' . date('Y-m-d H:i:s') . '] ' . $e->__toString() . PHP_EOL, FILE_APPEND);
    }

    public static function logToFile($data, $filename)
    {
        $file = self::getLogPath() . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($file, $data);
    }

    /**
     * @return \Redis  | object
     * @throws \yii\base\InvalidConfigException
     */
    public static function getRedis()
    {
        return \Yii::$app->get('redisCache');
    }

    /**
     * @return RabbitMQ | object
     */
    public static function getRabbitMq()
    {
        $rabbitMq = \Yii::$app->get('rabbitMq');
        return $rabbitMq;
    }

    /**
     * 计算地图上两个点的距离，单位：米
     * @param float $lng1 经度 -180°->180°
     * @param float $lat1 纬度：-90°->90°
     * @param float $lng2
     * @param float $lat2
     * @return integer
     */
    public static function getDistance($lng1, $lat1, $lng2, $lat2)
    {
        $lng1 = floatval($lng1);
        $lat1 = floatval($lat1);
        $lng2 = floatval($lng2);
        $lat2 = floatval($lat2);
        //将角度转为狐度
        $radLat1 = deg2rad($lat1);//deg2rad()函数将角度转换为弧度
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
        return intval($s);
    }

    /**
     * @param int $curTimestamp
     * @param array $jobIds
     * @return bool|array
     */
    public static function getTimeReachedJobs($curTimestamp, $jobIds)
    {
        /* 参见 framework/lib/lua/getTimeReachedJobs.lua */
        $script = <<<'SCRIPT'
local function getTimeReachedJobs(KEYS)
    local curTimestamp = tonumber(KEYS[1]);
    local taskIds = cjson.decode(KEYS[2]);
    local retData = {};

    if (taskIds == nil) then
        return retData;
    end

    local prefix = "pintuan_task_";
    for _, taskId in ipairs(taskIds) do
        local timestamp = tonumber(redis.call("LPOP", prefix .. taskId));
        if (timestamp ~= nil and timestamp ~= false) then
            if (timestamp < curTimestamp) then
                table.insert(retData, taskId .. '#' .. timestamp);
            else
                redis.call("LPUSH", prefix .. taskId, timestamp);
            end
        end
    end
    return cjson.encode(retData);
end
return getTimeReachedJobs(KEYS);
SCRIPT;

        $res = ToolsAbstract::getRedis()->eval($script, [$curTimestamp, json_encode($jobIds)], 2);
        if ($res != 'false') {
            return json_decode($res, 1);
        }
        return false;
    }

}