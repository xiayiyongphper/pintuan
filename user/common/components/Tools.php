<?php

namespace common\components;

use framework\components\es\Console;
use framework\components\ToolsAbstract;
use Yii;


/**
 * public function
 */
class Tools extends ToolsAbstract
{
    /**
     * 取商品价格
     * 如果是特价商品返回特价,不然返回原价
     */
    public static function getPrice($val)
    {
        $specialPrice = $val['special_price'];
        $price = $val['price'];
        if ($specialPrice == 0) {
            $finalPrice = $price;
        } elseif ($price > $specialPrice) {
            $finalPrice = $specialPrice;
        } else {
            $finalPrice = $price;
        }
        return self::numberFormat($finalPrice, 2);
    }

    public static function getImage($gallery, $size = '600x600', $single = true)
    {
        $gallery = explode(';', $gallery);
        $search = ['source', '600x600', '180x180'];
        if ($single) {
            return str_replace($search, $size, $gallery[0]);
        } else {
            $images = array();
            foreach ($gallery as $image) {
                $images[] = str_replace($search, $size, $image);
            }
            return $images;
        }
    }

    public static function formatPrice($price)
    {
        return number_format($price, 2, '.', '');
    }

    public static function getAssetsFile($file, $decode = false)
    {
        $file = Yii::getAlias('@service') . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . $file;
        if (file_exists($file)) {
            $json = file_get_contents($file);
            if ($decode) {
                $json = json_decode($json, true);
            }
            return $json;
        } else {
            $e = new \Exception('Assets file not existed.', 999);
            Console::get()->logException($e);
        }
        return false;
    }

    public static function random($low = 0, $high = 1, $decimals = 5)
    {
        $decimals = abs($decimals);
        if ($high < $low) {
            $t = $high;
            $high = $low;
            $low = $t;
        }
        $length = ($high - $low) * pow(10, $decimals);
        $dt = rand(0, $length);
        return $low + floatval($dt / pow(10, $decimals));
    }

    /**
     * assortmentArray
     * Author Jason Y. wang
     * 把key拿出来，覆盖key相同的
     * @param array $array
     * @param string $key
     * @return array
     */
    public static function conversionKeyArray($array, $key)
    {
        $newArray = array();
        foreach ($array as $k => $v) {
            $newKey = $v[$key];
            unset($v[$key]);
            $newArray[$newKey] = $v;
        }
        return $newArray;
    }

    /**
     * 过滤字符串中的特殊字符 只保留中文 英文
     * @param string $str
     * @return string
     */
    public static function filterStr($str)
    {
        if (!$str) {
            return $str;
        }
        $str = trim($str);
        $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\（|\）|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\||\s+/";
        $strParam = preg_replace($regex, "", $str);
        preg_match('/[\w\x{4e00}-\x{9fa5}]+/u', $strParam, $matches);
        if (empty($matches[0])) {
            return '';
        }
        return $matches[0];
    }


}