<?php

namespace framework\components;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-7-26
 * Time: 下午2:45
 * Email: henryzxj1989@gmail.com
 */
abstract class ToolsAbstract
{
    protected static $date;

    const REDIS_KEY_DEBUG_DEVICE_TABLE = 'debug_device_table';

    /**
     * filter
     * Author Jason Y. wang
     * pb数据赋值前去掉null和null字符串
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public static function pb_array_filter($data)
    {
        if (!is_array($data)) {
            throw new \Exception('传入参数不是数组', 9999);
        }
        foreach ($data as $key => $b) {
            if (is_array($b)) {
                $result = self::pb_array_filter($b);
                if (is_array($result) && count($result) > 0) {
                    $data[$key] = $result;
                } else {
                    unset($data[$key]);
                }
            } else {

                //0字符串单独拿出来，可以赋值
                if ($b === '0') {
                    continue;
                }
                //0数字单独拿出来，可以赋值
                if ($b === 0) {
                    continue;
                }

                if (empty($b)) {
                    //预防其他空值
                    unset($data[$key]);
                    continue;
                } else if (is_string($b) && strtolower($b) == 'null') {
                    //去掉'null'字符串
                    unset($data[$key]);
                    continue;
                } else if (is_null($b)) {
                    //去掉null
                    unset($data[$key]);
                    continue;
                }

                // 过滤非utf8字符串
                if (is_string($b)) {
                    $data[$key] = self::filterUtf8($b);
                }
            }
        }
        return $data;
    }

    public static function getLogPath()
    {
        return \Yii::getAlias('@service') . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR . 'logs';
    }

    public static function getESConsolePath()
    {
        return self::getLogPath() . DIRECTORY_SEPARATOR . '.es_console';
    }

    public static function getESOrderPath($type = null)
    {
        if (isset($type)) {
            return self::getLogPath() . DIRECTORY_SEPARATOR . '.es_order' . DIRECTORY_SEPARATOR . strtolower($type);
        }
        return self::getLogPath() . DIRECTORY_SEPARATOR . '.es_order';
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
        $date = new Date();
        $file = self::getLogPath() . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($file, '[' . $date->date() . '] ' . print_r($data, true) . PHP_EOL, FILE_APPEND);
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
        $date = new Date();
        $file = self::getLogPath() . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($file, '[' . $date->date() . '] ' . $e->__toString() . PHP_EOL, FILE_APPEND);
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
        $date = new Date();
        $file = self::getLogPath() . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($file, '[' . $date->date() . '] ' . $e->__toString() . PHP_EOL, FILE_APPEND);
    }

    public static function logToFile($data, $filename)
    {
        $file = self::getLogPath() . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($file, $data);
    }

    /**
     * @return \Redis
     * @throws \yii\base\InvalidConfigException
     */
    public static function getRedis()
    {
        return \Yii::$app->get('redisCache');
    }

    /**
     * @return \Redis
     */
    public static function getRouteRedis()
    {
        return \Yii::$app->get('routeRedisCache');
    }

    public static function getSphinx()
    {
        return \Yii::$app->get('sphinx');
    }

    public static function numberFormat($number, $precision = 0)
    {
        return number_format($number, $precision, null, '');
    }

    public static function getSysName()
    {
        return ENV_SYS_NAME;
    }

    public static function getRedisMsgQueueKey()
    {
        return ENV_SYS_NAME . '_msg_queue';
    }

    /**
     * 将slim的消息转发到消息队列
     * @return string
     */
    public static function getRedisForwardMsgKey()
    {
        return 'slim_to_swoole_message_v3.0';
    }

    /**
     * @return Date
     */
    public static function getDate()
    {
        if (!self::$date) {
            self::$date = new Date();
        }
        return self::$date;
    }

    /**
     * @param string|integer|null $date1 null instead of now
     * @param string|integer|null $date2 null instead of now
     * @return bool|int invalid parameter will cause the boolean return
     */
    public static function dateSub($date1 = null, $date2 = null)
    {
        if (is_string($date1)) {
            $date1 = strtotime($date1);
        } elseif (is_numeric($date1)) {
            //to do nothing
        } elseif (is_null($date1)) {
            $date1 = self::getDate()->timestamp();
        } else {
            return false;
        }

        if (is_string($date2)) {
            $date2 = strtotime($date2);
        } elseif (is_numeric($date2)) {
            //to do nothing
        } elseif (is_null($date2)) {
            $date2 = self::getDate()->timestamp();
        } else {
            return false;
        }

        return $date1 - $date2;
    }

    /**
     * @param $array
     * @param $key
     * @param $defaultValue
     * @return mixed
     */
    public static function arrayGet($array, $key, $defaultValue)
    {
        $value = $defaultValue;
        if (isset($array[$key])) {
            $value = $array[$key];
        }
        return $value;
    }

    /**
     * @param $array
     * @param $key
     * @param int $defaultValue
     * @return int
     */
    public static function arrayGetInteger($array, $key, $defaultValue = 0)
    {
        return self::arrayGet($array, $key, $defaultValue);
    }

    /**
     * @param $array
     * @param $key
     * @param float $defaultValue
     * @return float
     */
    public static function arrayGetFloat($array, $key, $defaultValue = 0.0)
    {
        return self::arrayGet($array, $key, $defaultValue);
    }

    /**
     * @param $array
     * @param $key
     * @param string $defaultValue
     * @return mixed
     */
    public static function arrayGetString($array, $key, $defaultValue = '')
    {
        return self::arrayGet($array, $key, $defaultValue);
    }

    /**
     * @param $array
     * @param $key
     * @param bool $defaultValue
     * @return mixed
     */
    public static function arrayGetBoolean($array, $key, $defaultValue = false)
    {
        return self::arrayGet($array, $key, $defaultValue);
    }

    /**
     * check if the string contains Full - width alpha - numeric
     * @param $str
     * @return bool
     */
    public static function isAscii($str)
    {
        if (strlen($str) == mb_strlen($str, "utf-8")) {
            return true;
        }
        return false;
    }

    /**
     * check if the color value is valid
     * @param $str
     * @return bool
     */
    public static function isColor($str)
    {
        $regex = '/^#?[0-9A-F]{6}$/i';
        if (preg_match($regex, $str)) {
            return true;
        }
        return false;
    }

    public static function filterUtf8($string)
    {
        if ($string) {
//先把正常的utf8替换成英文逗号
            $result = preg_replace('%(
[\x09\x0A\x0D\x20-\x7E]
| [\xC2-\xDF][\x80-\xBF]
| \xE0[\xA0-\xBF][\x80-\xBF]
| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}
| \xED[\x80-\x9F][\x80-\xBF]
| \xF0[\x90-\xBF][\x80-\xBF]{2}
| [\xF1-\xF3][\x80-\xBF]{3}
| \xF4[\x80-\x8F][\x80-\xBF]{2}
)%xs', ',', $string);
//转成字符数字
            $charArr = explode(',', $result);
//过滤空值、重复值以及重新索引排序
            $findArr = array_values(array_flip(array_flip(array_filter($charArr))));
            // 找到非utf8字符串则log一下
            if ($findArr) {
                self::log("not valid utf8 string:\"{$string}\"" . PHP_EOL, "utf8.log");
                //self::log("data:".PHP_EOL.print_r($data, true).PHP_EOL, "utf8.log");
            }
            return $findArr ? str_replace($findArr, "", $string) : $string;
        }
        return $string;

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
     * 是否测试用户
     * @param null $customerId
     *
     * @return bool
     */
    public static function isCustomerDebug($customerId = null)
    {
        if ($customerId === null || !is_numeric($customerId)) {
            return false;
        }
        $is_debug = false;
        $redis = self::getRedis();
        if ($redis->hExists(self::REDIS_KEY_DEBUG_DEVICE_TABLE, $customerId)) {
            $is_debug = true;
        }
        return $is_debug;
    }

    /**
     * 非测试用户返回false
     * 测试用户返回log level
     * @param null $customerId
     *
     * @return bool|int
     */
    public static function getCustomerDebugLevel($customerId = null)
    {
        if ($customerId === null || !is_numeric($customerId)) {
            return false;
        }
        $level = false;
        $redis = self::getRedis();
        if (self::isCustomerDebug()) {
            $level = $redis->hGet(self::REDIS_KEY_DEBUG_DEVICE_TABLE, $customerId);
        }
        return $level;
    }

    /**
     * identifier最好是以md5签名的
     * @param $identifier
     * @param $window
     * @param $size
     * @return bool
     */
    public static function rateLimiter($identifier, $window, $size)
    {
        $script = <<<SCRIPT
local function checkLimiter(_keys, _values)
    local flag = 1
    if  table.getn(_keys) == 3 then
        local id = _values[1]
        local window = tonumber(_values[2])
        local size = tonumber(_values[3])
        if redis.call("EXISTS", id) == 1 then
           if redis.call("INCR", id) > size then
               redis.call("DECR", id)
               redis.call("EXPIRE", id, window)
               flag = -2
           end
        else
            if redis.call("INCR", id) <= size then
               redis.call("EXPIRE", id, window)
            else
                redis.call("DEL", id)
                flag = -3
            end
        end
    else
        flag = -1
    end
    return flag
end

local availability = checkLimiter(KEYS, ARGV)
return availability
SCRIPT;

        if (strlen($identifier) !== 32) {
            $identifier = md5($identifier);
        }

        $ret = ToolsAbstract::getRedis()->eval($script, ['id', 'w', 's', $identifier, $window, $size], 3);

        if ($ret === 1) {
            return true;
        }

        if ($ret === false) {
            //lua脚本出错，检查不通过返回<0
            ToolsAbstract::log('.', 'rateLimiter_error.log');
        }
        return false;
    }


    /*
   * 中国正常GCJ02坐标---->百度地图BD09坐标
   * 腾讯地图用的也是GCJ02坐标
   * @param double $lat 纬度
   * @param double $lng 经度
   */

    public static function Convert_GCJ02_To_BD09($lat,$lng){
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng;
        $y = $lat;
        $z =sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta) + 0.0065;
        $lat = $z * sin($theta) + 0.006;
        return array('lng'=>$lng,'lat'=>$lat);
    }


    /*
    * 百度地图BD09坐标---->中国正常GCJ02坐标
    * 腾讯地图用的也是GCJ02坐标
    * @param double $lat 纬度
    * @param double $lng 经度
    * @return array();
    */

    public static function Convert_BD09_To_GCJ02($lat,$lng){
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng - 0.0065;
        $y = $lat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta);
        $lat = $z * sin($theta);
        return array('lng'=>$lng,'lat'=>$lat);
    }


}