<?php

namespace common\tools;

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 18-6-4
 * Time: 上午11:29
 */

class Tools
{
    public static function log($data, $filename = null)
    {
        if (!$filename) {
            $filename = 'system.log';
        }
        $filename = self::getLogFilename($filename);
        $file = self::getLogPath() . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($file, '[' . date('Y-m-d H:i:s') . '] ' . print_r($data, true) . PHP_EOL, FILE_APPEND);
    }

    public static function getLogFilename($file)
    {
        $parts = explode('.', $file);
        $ext = array_pop($parts);
        array_push($parts, date('Y-m-d'));
        array_push($parts, $ext);
        $file = implode('.', $parts);
        return $file;
    }

    public static function getLogPath()
    {
        return \Yii::$app->getRuntimePath() . DIRECTORY_SEPARATOR . 'logs';
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
     * @return \Redis
     */
    public static function getRedis(){
        return \Yii::$app->get('redisCache');
    }

    /**
     * 走线上服务机器的redis
     * @return null|object
     * @throws \yii\base\InvalidConfigException
     */
    public static function getServerRedis(){
        return \Yii::$app->get('redisServerCache');
    }

    /**
     * 由第三方平台导入的sku格式，转化成 items 表所需要的 specification_name和specification_value
     * 以及 specification 表的 item_detail
     * @param $sku array
     * @ret $res array
     */
    public static function combineMulSkuItemdetails($skus){
        $data = [];
        $i=0;
        foreach ($skus as $v){
            $lable = $v['label'];
            foreach ($v['values'] as $val){
                $data[$i][] = $lable."--".$val['desc']."|";
            }
            $i++;
        }
        return $data;
    }


    /**
     * 计算多个集合的笛卡尔积
     * @param  Array $sets 集合数组
     * @return Array
     */
    public static function CartesianProduct($sets){
        // 保存结果
        $result = array();
        // 循环遍历集合数据
        for($i=0,$count=count($sets); $i<$count-1; $i++){
            // 初始化
            if($i==0){
                $result = $sets[$i];
            }
            // 保存临时数据
            $tmp = array();
            // 结果与下一个集合计算笛卡尔积
            foreach($result as $res){
                foreach($sets[$i+1] as $set){
                    $tmp[] = $res.$set;
                }
            }
            // 将笛卡尔积写入结果
            $result = $tmp;
        }
        return $result;
    }

    /**
     * 判断是否为json 格式的字符串
     * @param $str
     * @return bool ，true 为不是 json 格式，false 表示是json 格式
     */
    public static function is_not_json($str){
        return is_null(json_decode($str));
    }


    /**
     * 由商品详情的内容str ，抓取里面所有的img src 对应的图片地址
     * @param $imgstr string
     * @return $res array
     */
    public static function getImgsArr($imgstr,$platform = 4){
        $imgArrs = [];
        if($platform != 3){
            $imgpreg = "/<img (.*?) src=\"(.+?)\".*?>/";
            preg_match_all($imgpreg,$imgstr,$img);
            $imgArrs = $img[2];
        }else{ // 京东的详情图片额外处理
            $imgpreg = '/background-image:url\(([^^]*?)\)|src=\"\"([^^]*?)\"\"/i';
            preg_match_all($imgpreg,$imgstr,$img);
            $imgArrAll = $img[1];
            $imgArrs = [];
            foreach ($imgArrAll as $val){
                if(!empty($val) && strpos($val,"566010f4N01f5d17a") === false){  //
                    if(strpos($val,'//') === 0){
                        $imgArrs[] = str_replace("//","https://",$val);
                    }else{
                        $imgArrs[] = $val;
                    }
                }
            }
        }
        return $imgArrs;
    }

    /**
     * 判断一个数组是否是空数组
     * @param $arr
     * @return bool true 为空数组 ，false 不为空数组
     */
    public static function is_array_null($arr)
    {
        if (empty($arr)){
            return true;
        }else{
            $i = 0 ;
            foreach ($arr as $val){
                if(!empty($val)){
                    $i++;
                }
            }
            if($i > 0)
                return false;
            else
                return true;

        }
    }


}