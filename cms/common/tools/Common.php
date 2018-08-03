<?php
namespace common\tools;

/**
 * 公用函数库
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/13
 * Time: 11:17
 */
class Common
{
    /**
     * 元转换为分
     * @param $price
     * @return int
     */
    public static function ncPriceYuan2fen($price){
        return bcmul($price, 100, 0);
    }

    /**
     * 浮点数运算
     * @param $n1
     * @param $symbol
     * @param $n2
     * @param string $scale
     * @return string
     */
    public static function ncPriceCalculate($n1,$symbol,$n2,$scale = '2'){
        $res = "";
        switch ($symbol){
            case "+"://加法
                $res = bcadd($n1,$n2,$scale);break;
            case "-"://减法
                $res = bcsub($n1,$n2,$scale);break;
            case "*"://乘法
                $res = bcmul($n1,$n2,$scale);break;
            case "/"://除法
                $res = bcdiv($n1,$n2,$scale);break;
            case "%"://求余、取模
                $res = bcmod($n1,$n2,$scale);break;
            default:
                $res = "";break;
        }
        return $res;
    }

    /**
     * 格式化
     * @param $price
     * @return string
     */
    public static function ncPriceFormat($price) {
        $price_format	= number_format($price,2,'.','');
        return $price_format;
    }
}