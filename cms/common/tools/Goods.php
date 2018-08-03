<?php
namespace common\tools;

/**
 * 商品模块公用方法
 * Class Product
 * @package common\tools
 */

class Goods
{
    /**
     * 生成商品编码
     * @param string $lable
     * @return string
     */
     public static function createBarcode($lable = 'P')
     {
         //商品编码自动生成，规则：P+10位随机数
         $num = mt_rand(10000,99999);
         $barcode =  $lable . $num . substr(self::getMillisecond(), -5, 5);

         $model = new \backend\models\Specification();
         $where = [
             'barcode'=>$barcode
         ];
         $info = $model::find()->where($where)->asArray()->one();

         if (empty($info)) {
             return $barcode;
         } else {
             self::createBarcode();
         }
     }

    /**
     * 获取毫秒级时间戳
     * @return float
     */
    public static function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }
}