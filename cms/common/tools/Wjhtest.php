<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/26
 * Time: 17:00
 */

namespace common\tools;


class Wjhtest
{

    /**
     * 根据json字符串获取商品的规格数据
     * @param string $json_str
     * @return array
     * 测试：
     *  $wjh = new \common\tools\Wjhtest();
        $data = $wjh::getSpecificationData();
        var_dump($data);
        exit;
     */
    public static function getSpecificationData($json_str='')
    {
        $lastRes = [];
        $skuDatas = self::getData($json_str);
        $res = self::zuhe($skuDatas);
        $data = $res[0];

        foreach ($data as $item) {
            $item_arr = explode('_', $item);
            $cur_data = [];
            foreach ($item_arr as $item2) {
                $item2_arr = explode('-', $item2);
                $cur_data[$item2_arr[0]] = $item2_arr[1];
            }
            $lastRes[] = $cur_data;
        }

        return $lastRes;
//        var_dump(json_encode($lastRes,JSON_UNESCAPED_UNICODE));
//        exit;
    }

    /**
     * json字符串转换为数组
     * @param string $json_str
     * @return array
     */
    public static function getData($json_str='')
    {
        //测试数据
        $json_str = '[
        {
            "label":"颜色",
            "values":[
                {
                    "desc":"摩卡金",
                    "image":"http://img13.360buyimg.com/n9/s1200x800_jfs/t10441/89/1225104555/201624/74411210/59ddfcb1Nc3edb8f1.jpg"
                },
                {
                    "desc":"亮黑色",
                    "image":"http://img13.360buyimg.com/n9/s1200x800_jfs/t10177/326/1292333258/167702/38059e69/59ded62eN64a9784c.jpg"
                },
                {
                    "desc":"香槟金",
                    "image":"http://img13.360buyimg.com/n9/s1200x800_jfs/t10414/363/1280355182/335902/18c2b152/59ded64fNfdb4e9da.jpg"
                },
                {
                    "desc":"樱粉金",
                    "image":"http://img14.360buyimg.com/n9/s1200x800_jfs/t10339/114/1284476657/363678/ec5189c9/59ded682N99d4efcc.jpg"
                }
            ]
        },
        {
            "label":"版本",
            "values":[
                {
                    "desc":"标准版",
                    "image":""
                },
                {
                    "desc":"套装版",
                    "image":""
                }
            ]
        },
        {
            "label":"内存",
            "values":[
                {
                    "desc":"64GB",
                    "image":""
                },
                {
                    "desc":"128GB",
                    "image":""
                }
            ]
        }
    ]';
        $skus_arr = json_decode($json_str, true);
        $count = count($skus_arr);

        $arrList = [];

        for ($i=0; $i<$count;$i++) {
            $list = $skus_arr[$i];
            $label  = $list['label'];
            $values = $list['values'];
            $current = [];
            foreach ($values as $val) {
                $current[] = $label .'-'. $val['desc'];
            }
            $arrList[] = $current;
        }

        return $arrList;
    }

    /**
     * 数组排列组合
     * @param $arr
     * @return mixed
     */
    public static function zuhe($arr){
        if(count($arr) >= 2){
            $tmparr = [];
            $arr1 = array_shift($arr);
            $arr2 = array_shift($arr);
            foreach($arr1 as $k1 => $v1){
                foreach($arr2 as $k2 => $v2){
                    $tmparr[] = $v1. '_' .$v2;
                }
            }
            array_unshift($arr, $tmparr);
            $arr = self::zuhe($arr);
        }else{
            return $arr;
        }
        return $arr;
    }
}