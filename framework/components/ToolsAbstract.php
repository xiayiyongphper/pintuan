<?php

namespace framework\components;

use framework\data\Pagination;
use framework\models\CoreConfigData;
use framework\mq\RabbitMQ;
use message\common\Header;
use message\common\Protocol;
use message\common\SourceEnum;

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

    public static function getDate($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }

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
        return \Yii::$app->runtimePath . DIRECTORY_SEPARATOR . 'logs';
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
     * 定时任务需要区分业务系统以及机器IP，否则可能出现重复执行的情况
     * @return string
     */
    public static function getCrontabKey()
    {
        return ENV_SYS_NAME . '_crontab_' . ENV_SERVER_IP;
    }

    /**
     * @param Pagination $pagination
     * @return array
     */
    public static function getPagination($pagination)
    {
        return [
            'total_count' => $pagination->getTotalCount(),
            'page'        => $pagination->getCurPage(),
            'last_page'   => $pagination->getLastPageNumber(),
            'page_size'   => $pagination->getPageSize(),
        ];
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
            $date1 = time();
        } else {
            return false;
        }

        if (is_string($date2)) {
            $date2 = strtotime($date2);
        } elseif (is_numeric($date2)) {
            //to do nothing
        } elseif (is_null($date2)) {
            $date2 = time();
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
     *
     * 返回magento系统中的system_config信息,需要传入path
     *
     * @param $path
     *
     * @return bool|string
     */
    public static function getSystemConfigByPath($path)
    {
        $config = CoreConfigData::findOne(['path' => $path]);
        if ($config) {
            return $config->value;
        } else {
            return false;
        }
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

    /**
     * @param null $source
     * @return string
     */
    public static function getSourceCode($source = null)
    {
        switch ($source) {
            case SourceEnum::CORE:
                $code = 'core';
                break;
            case SourceEnum::MERCHANT:
                $code = 'merchant';
                break;
            case SourceEnum::CUSTOMER:
                $code = 'customer';
                break;
            case SourceEnum::ANDROID_CASH:
                $code = 'android_cash';
                break;
            case SourceEnum::ANDROID_SHOP:
                $code = 'android_shop';
                break;
            case SourceEnum::IOS_SHOP:
                $code = 'ios_shop';
                break;
            case SourceEnum::SYNC:
                $code = 'sync';
                break;
            case SourceEnum::SYNC_PROCESS:
                $code = 'sync_process';
                break;
            case SourceEnum::PCWEB:
                $code = 'pcweb';
                break;
            default:
                $code = 'other';
        }
        return $code;
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
     * 扣减库存函数，使用脚本操作redis保证操作的原子性
     * @param array $products ['qty_441800_1'=>1,'qty_441800_2'=>10,'qty_{城市CODE}_{商品ID}'=>{购买数量}]
     * @return integer | boolean
     */
    public static function subtractInventory(array $products)
    {
        $script = <<<SCRIPT
--检查商品库存可用性，商品库存不足时返回对应的index位置
local function checkAvailability(_keys, _values)
    local flag = 0
    for k, v in pairs(_keys) do
        if redis.call("EXISTS", v) == 1 then
            local qty = tonumber(redis.call("GET", v))
            local num = tonumber(_values[k])
            if qty < num then
                flag = -k
            end
        else
            flag = -k
        end
        if flag < 0 then
            break
        end
    end
    return flag
end

--直接扣减库存
local function subtractInventory(_keys, _values)
    for k, v in pairs(_keys) do
        local num = tonumber(_values[k])
        redis.call("DECRBY", v, num)
    end
    return 1
end

--库存可用性
local availability = checkAvailability(KEYS, ARGV)
if availability == 0 then
    --扣减库存
    subtractInventory(KEYS, ARGV)
    return 0
else
    return availability
end
SCRIPT;
        //$ret = $redis->eval($script, ['qty_441800_1', 3], 1);
        $args = array_keys($products);
        $numKey = count($args);
        foreach ($products as $key => $num) {
            $args[] = $num;
        }
        $ret = ToolsAbstract::getRedis()->eval($script, $args, $numKey);
        if (is_numeric($ret)) {
            return $ret;
        }
        return false;
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

    /**
     * 获取秒杀商品库存
     *
     * @author zqy
     * @since 2.6.6
     * @param integer $actId
     * @param integer[] $proIds
     * @return boolean
     */
    public static function getSecKillProductsStocks($actId, $proIds)
    {
        $script = <<<'EOT'
--
-- Created by IntelliJ IDEA.
-- User: ZQY
-- Date: 2017/6/22
-- Time: 17:40
-- 获取秒杀商品库存
--
-- KEYS : actId, [proId, proId2, ...]

local function getSecKillProductStock(actId, proId)
    local totalKey = "sk_total_" .. actId .. "_" .. proId;
    local total = tonumber(redis.call("GET", totalKey));
    if (total == nil or total <= 0) then
        return 0;
    end

    local productKey = "sk_p_" .. actId .. "_" .. proId;
    local customerProKeys = redis.call("SMEMBERS", productKey);
    local sum = 0;
    if (customerProKeys ~= nil) then
        for _, customerProKey in ipairs(customerProKeys) do
            local proNum = tonumber(redis.call("GET", customerProKey));
            if (proNum ~= nil) then
                sum = sum + proNum;
            end
        end
    end

    if (total <= sum) then
        return 0;
    else
        return total - sum;
    end
end

local function getSecKillProductsStock(ARGS)
    local retData = {};
    local actId = tonumber(ARGS[1]);

    for i = 2, #ARGS do
        retData[ARGS[i]] = getSecKillProductStock(actId, ARGS[i]);
    end

    return cjson.encode(retData);
end
return getSecKillProductsStock(KEYS);
EOT;
        $scriptArgs = $proIds;
        array_unshift($scriptArgs, $actId);
        $res = ToolsAbstract::getRedis()->eval($script, $scriptArgs, count($scriptArgs));
        if ($res != 'false') {
            return json_decode($res, 1);
        }
        return false;
    }

    /**
     * 获取用户购物车秒杀商品列表
     * @author zqy
     * @since 2.6.6
     * @param integer $actId 秒杀活动id
     * @param integer $userId 用户id
     * @param integer $areaId 用户所在区域id
     * @return bool|mixed
     */
    public static function getUserCartSecKillProducts($actId, $userId, $areaId)
    {
        $script = <<<'SCRIPT'
--
-- Created by IntelliJ IDEA.
-- User: ZQY
-- Date: 2017/6/21
-- Time: 15:44
-- 获取用户秒杀商品
--
-- KEYS : actId customerId areaId

--字符串分割函数
--传入字符串和分隔符，返回分割后的table
local function split(str, d)
    local lst = { }
    local n = string.len(str)
    local start = 1
    while start <= n do
        local i = string.find(str, d, start)
        if i == nil then
            table.insert(lst, string.sub(str, start, n))
            break
        end
        table.insert(lst, string.sub(str, start, i-1))
        if i == n then
            table.insert(lst, "")
            break
        end
        start = i + 1
    end
    return lst
end

local function getUserSecKillItems(KEYS)
    local actId = tonumber(KEYS[1]);
    local customerId = tonumber(KEYS[2]);
    local areaId = tonumber(KEYS[3]);

    local retData = { };
    local customerKey = "sk_c_" .. actId .. "_" .. customerId;
    local customerProKeys = redis.call("SMEMBERS", customerKey);
    if (customerProKeys ~= nil) then
        local tmpTable = {};
        local ttl = 0;
        local num = 0;
        for _, customerProKey in ipairs(customerProKeys) do
            tmpTable = split(customerProKey, "_");
            if (next(tmpTable) ~= nil) then
                ttl = redis.call("TTL", customerProKey);
                num = tonumber(redis.call("GET", customerProKey));
                if (num ~= nil and ttl > 0 and num > 0) then
                    retData[tmpTable[4]] = {t=ttl, n=num};
                end
            end
        end
    end
    return cjson.encode(retData);
end
return getUserSecKillItems(KEYS);
SCRIPT;
        $res = ToolsAbstract::getRedis()->eval($script, [$actId, $userId, $areaId], 3);
        if ($res != 'false') {
            return json_decode($res, 1);
        }
        return false;
    }

    /**
     * 删除秒杀商品
     *
     * @author zqy
     * @since 2.6.6
     * @param integer $actId 活动id
     * @param integer $productId 商品id
     * @param integer $customerId 用户id
     * @param integer $areaId 区域id
     * @return boolean
     */
    public static function removeSecKillCartProduct($actId, $productId, $customerId, $areaId)
    {
        $script = <<<'SCRIPT'
--
-- Created by IntelliJ IDEA.
-- User: ZQY
-- Date: 2017/6/21
-- Time: 12:55
-- 删除购物车秒杀商品
--
local function removeItem(KEYS)
    local actId = tonumber(KEYS[1]);
    local proId = tonumber(KEYS[2]);
    local customerId = tonumber(KEYS[3]);
    local areaId = tonumber(KEYS[4]);

    local customerProKey = "sk_pc_" .. actId .. "_" .. proId .. "_" .. customerId .. "_" .. areaId ;
    local proKey = "sk_p_" .. actId .. "_" .. proId;
    local customerKey = "sk_c_" .. actId .. "_" .. customerId;

    local removeKeys = redis.call("DEL", customerProKey);
    if (removeKeys <= 0) then
        return 0;
    end

    redis.call("SREM", proKey, customerProKey);
    redis.call("SREM", customerKey, customerProKey);
    return 1;
end
return removeItem(KEYS);
SCRIPT;
        $res = ToolsAbstract::getRedis()->eval($script, [$actId, $productId, $customerId, $areaId], 4);
        if ($res != 'false') {
            return $res ? true : false;
        }
        return false;
    }

    /**
     * 更新购物车秒杀商品数量
     *
     * @author zqy
     * @since 2.6.6
     * @param integer $actId 活动id
     * @param integer $productId 商品id
     * @param integer $num 商品数量
     * @param integer $customerId 用户id
     * @param integer $areaId 区域id
     * @param integer $leftSeconds 活动距离结束剩余时间（秒数）
     * @return boolean
     */
    public static function updateCartSecKillProduct($actId, $productId, $num, $customerId, $areaId, $leftSeconds, $wholesalerId = 0)
    {
        $script = <<<'SCRIPT'
--
-- Created by IntelliJ IDEA.
-- User: ZQY
-- Date: 2017/6/21
-- Time: 10:23
-- 更新购物车秒杀商品数量
--
-- KEYS : actId, proId, num, customerId, areaId, leftSeconds
local function updateItem(KEYS)
    local actId = tonumber(KEYS[1]);
    local proId = tonumber(KEYS[2]);
    local num = tonumber(KEYS[3]);
    local customerId = tonumber(KEYS[4]);
    local areaId = tonumber(KEYS[5]);
    local leftSeconds = tonumber(KEYS[6]);
    local wholesalerId = tonumber(KEYS[7]);
    if (wholesalerId > 0) then
        local cur_time = tonumber(KEYS[8]);
        local cartKey = "shopping_cart_" .. customerId;
        redis.call("ZADD", cartKey, cur_time, wholesalerId);
    end
    
    local expiredTime = tonumber(redis.call("GET", "sk_cart_expired_time"));
    if (expiredTime == nil or expiredTime <= 0) then
        expiredTime = 1200;
    end
    
    if (leftSeconds ~= nil and leftSeconds < expiredTime) then
        expiredTime = leftSeconds;
    end
    
    local totalKey = "sk_total_" .. actId .. "_" .. proId;
    local customerProKey = "sk_pc_" .. actId .. "_" .. proId .. "_" .. customerId .. "_" .. areaId ;
    local proKey = "sk_p_" .. actId .. "_" .. proId;
    local customerKey = "sk_c_" .. actId .. "_" .. customerId;

    local total = tonumber(redis.call("GET", totalKey));
    if (total == nil or total <= 0) then
        return 0;
    end

    local cartKeys = redis.call("SMEMBERS", proKey);
    if (cartKeys ~= nil) then
        local sum = 0;
        for _, cartKey in ipairs(cartKeys) do
            if (cartKey ~= customerProKey) then
                local proNum = tonumber(redis.call("GET", cartKey));
                if (proNum ~= nil) then
                    sum = sum + proNum;
                end
            end
        end
        if (total < (sum + num)) then
            return 0;
        end
    end

    if (total >= num) then
        local preExpiredTime = redis.call("TTL", customerProKey);
        if (preExpiredTime > 0) then
            expiredTime = preExpiredTime;
        end
        if (expiredTime > 0) then
            redis.call("SADD", proKey, customerProKey);
            redis.call("EXPIRE", proKey, 86400);
            redis.call("SADD", customerKey, customerProKey);
            redis.call("EXPIRE", customerKey, 86400);
            redis.call("SET", customerProKey, num, "EX", expiredTime);
        end
        return 1;
    else
        return 0;
    end
end
return updateItem(KEYS);
SCRIPT;
        $cur_time = time();
        $res = ToolsAbstract::getRedis()->eval($script, [
            $actId, $productId, $num, $customerId, $areaId, $leftSeconds, $wholesalerId, $cur_time
        ], 8);

        if ($res != 'false') {
            return $res ? true : false;
        }
        return false;
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

    local prefix = "crontab_task_";
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

    public static function getDataByJson($route, $requestData, $source, $version = null)
    {
        $header = new Header();
        $header->setProtocol(Protocol::JSON);
        $header->setSource($source);
        $header->setRoute($route);
        if ($version) {
            $header->setVersion($version);
        }
        $rulesResponse = ProxyAbstract::sendRequest($header, $requestData)->getPackageBody();
        $rulesArray = json_decode($rulesResponse, true);
        return $rulesArray;
    }


    /*
   * 中国正常GCJ02坐标---->百度地图BD09坐标
   * 腾讯地图用的也是GCJ02坐标
   * @param double $lat 纬度
   * @param double $lng 经度
   */

    public static function Convert_GCJ02_To_BD09($lat, $lng)
    {
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng;
        $y = $lat;
        $z = sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta) + 0.0065;
        $lat = $z * sin($theta) + 0.006;
        return array('lng' => $lng, 'lat' => $lat);
    }


    /*
    * 百度地图BD09坐标---->中国正常GCJ02坐标
    * 腾讯地图用的也是GCJ02坐标
    * @param double $lat 纬度
    * @param double $lng 经度
    * @return array();
    */

    public static function Convert_BD09_To_GCJ02($lat, $lng)
    {
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng - 0.0065;
        $y = $lat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta);
        $lat = $z * sin($theta);
        return array('lng' => $lng, 'lat' => $lat);
    }


}