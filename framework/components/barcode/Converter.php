<?php
namespace framework\components\barcode;
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-9-28
 * Time: 下午2:51
 * Email: henryzxj1989@gmail.com
 */
class Converter
{
    protected static $instance;

    /**
     * @return $this
     */
    public static function get()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function encode($id){
        $id = (string)$id;

        // 第一位转子位, 除第一位和最后一位混淆位,中间都转
        $rotor = $this->random(1, 9, 0);

        // 第二位订单id长度
        $length = strlen($id);

        // 第三部分混淆,和订单id加一起补足9位
        $confusionLength = 9 - $length;
        $confusion = '';
        if($confusionLength>0){
            $high = pow(10, $confusionLength)-1;
            $low = pow(10, $confusionLength-1);
            $confusion = $this->random($low, $high, 0);
        }

        // 开始转
        $code = (string)$length . $id . (string)$confusion;
        $codeArr = str_split($code);
        $newCode = '';
        foreach ($codeArr as $str) {
            $newCode .= (string)(((int)$str+$rotor)%10);
        }

        // 第四部分校验位, 同UPC
        $checksum = $this->calculateUPCCheckSum($rotor.$newCode);


        $result = $rotor.$newCode.$checksum;
        //echo '结果:'.$result.'--转子:'.$rotor.' 订单id长度:'.$length.' 订单id:'.$id.' 混淆:'.$confusion.' 校验位:'.$checksum.PHP_EOL;

        return $result;
    }


    public function decode($scanResult){
        // 校验
        if(!$this->check($scanResult)){
            return false;
        }

        // 第一位转子位, 除第一位和最后一位混淆位,中间都转
        $rotor = $scanResult[0];

        // 反向转子
        $newCode = substr($scanResult, 1, -1);
        $newCodeArr = str_split($newCode);
        $code = '';
        foreach ($newCodeArr as $str) {
            $code .= (string)(((int)$str+10-$rotor)%10);
        }

        // 订单id长度
        $length = substr($code, 0, 1);
        // 订单id
        $id = substr($code, 1, (int)$length);

        return $id;
    }

    private function check($scanResult){
        if (strlen($scanResult) !== 12) {
            return false;
        }
        $barcode = substr($scanResult, 0, -1);
        $checksum = $this->calculateUPCCheckSum($barcode);
        if($scanResult[11] != $checksum){
            return false;
        }
        return true;
    }

    private function random($low = 0, $high = 1, $decimals = 5){
        $decimals = abs($decimals);
        if($high<$low){
            $t = $high;
            $high = $low;
            $low = $t;
        }
        $length = ($high - $low) * pow(10, $decimals);
        $dt = rand(0, $length);
        return $low + floatval($dt / pow(10, $decimals));
    }

    private function calculateUPCCheckSum($preBarcode){
        $barcode = $preBarcode;
        $oddSum  = 0;
        $evenSum = 0;

        for ($i = 0; $i < 11; $i++) {
            if ($i % 2 === 0) {
                $oddSum += $barcode[$i] * 3;
            } elseif ($i % 2 === 1) {
                $evenSum += $barcode[$i];
            }
        }

        $calculation = ($oddSum + $evenSum) % 10;
        $checksum    = ($calculation === 0) ? 0 : 10 - $calculation;

        return $checksum;
    }
}