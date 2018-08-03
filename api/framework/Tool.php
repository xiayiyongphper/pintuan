<?php

namespace framework;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-7-26
 * Time: 下午2:45
 * Email: henryzxj1989@gmail.com
 */
abstract class Tool
{
    //生成随机数
    const CHARS_LOWERS = 'abcdefghijklmnopqrstuvwxyz';
    const CHARS_UPPERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const CHARS_DIGITS = '0123456789';

    protected static $date;

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

    public static function getTemporaryFilePath($filename)
    {
        $dir = \Yii::$app->getRuntimePath() . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR;
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir . $filename;
    }

    /**
     * Author Jason Y. wang
     *
     * @return \Redis
     */
    public static function getRedis()
    {
        return \Yii::$app->get('redisCache');
    }

    public static function getSysName()
    {
        return ENV_SYS_NAME;
    }

    public static function getLogPath()
    {
        return \Yii::$app->getRuntimePath() . DIRECTORY_SEPARATOR . 'logs';
    }

    public static function log($data, $filename = null)
    {
        if (!$filename) {
            $filename = 'system.log';
        }
        $filename = self::getLogFilename($filename);
        $file = self::getLogPath() . DIRECTORY_SEPARATOR . $filename;
        $date = date('Y-m-d H:i:s');
        file_put_contents($file, '[' . $date . '] ' . print_r($data, true) . PHP_EOL, FILE_APPEND);
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
        $date = date('Y-m-d H:i:s');
        $file = self::getLogPath() . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($file, '[' . $date . '] ' . $e->__toString() . PHP_EOL, FILE_APPEND);
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
        $date = date('Y-m-d H:i:s');
        $file = self::getLogPath() . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($file, '[' . $date . '] ' . $e->__toString() . PHP_EOL, FILE_APPEND);
    }

    public static function logToFile($data, $filename)
    {
        $file = self::getLogPath() . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($file, $data);
    }


    public static function getUniqueFileName($prefix, $ext)
    {
        $prefix = $prefix . '-' . date('YmdHis') . '-';
        return uniqid($prefix) . ".$ext";
    }

    public static function getRandomString($len, $chars = null)
    {
        if (is_null($chars)) {
            $chars = self::CHARS_LOWERS . self::CHARS_UPPERS . self::CHARS_DIGITS;
        }
        for ($i = 0, $str = '', $lc = strlen($chars) - 1; $i < $len; $i++) {
            $str .= $chars[mt_rand(0, $lc)];
        }
        return $str;
    }

    /**
     * 生成编号
     * @param string $prefix
     * @return string
     */
    public static function generateUniqueNumber($prefix = 'JHD')
    {
        $genNumberKey = 'gen_saas_number_key';
        $redis = \Yii::$app->get('redisCache');
        if ($redis->exists($genNumberKey)) {
            if ($number = $redis->incr($genNumberKey)) {
                $number = str_pad($number, 13, '0', STR_PAD_LEFT);
                return $prefix . $number;
            }
        }
        return -1;
    }

    /**
     * 分转为元,字符串格式
     * @param $fen
     * @return float
     */
    public static function fenToYuan($fen)
    {
        $yuan = round((intval($fen) / 100), 2);
        return strval($yuan);
    }
}