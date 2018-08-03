<?php
namespace console\controllers;

use service\components\search\ElasticSearchExt2;
use yii\console\Controller;
use service\components\Tools;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 17-2-9
 * Time: 上午10:16
 */
class TestController extends Controller
{
    public function actionIndex()
    {
//        for($i=1;$i<=10;$i++){
//            echo $this->f($i);
//            echo PHP_EOL;
//        }

//        var_dump($this->getSubSets(['a','b','c','d']));

        $a = '哈啤';
        $b = '哈尔滨啤酒';

        $aa = substr(md5($a),-4);
        echo $aa;
        echo PHP_EOL;
        $bb = substr(md5($b),-4);
        echo $bb;
        echo PHP_EOL;
        if($aa > $bb){
            echo $aa.$bb;
        }else{
            echo $bb.$aa;
        }
        echo PHP_EOL;
    }

    public function actionRun(){
//        $a = array(3, 2, 5, 6, 1);
//
//        usort($a, [$this,"cmp"]);
//
//        foreach ($a as $key => $value) {
//            echo "$key: $value\n";
//        }
    }

    public function f($n){
        if($n<=0) return false;
        if($n == 1) return 1;
        if($n == 2) return 1;

        $f1 = 1;
        $f2 = 1;
        for($i=3;$i<=$n;$i++){
            $tmp = $f1 + $f2;
            $f1 = $f2;
            $f2 = $tmp;
        }

        return $f2;
    }

    public function actionEs(){
        $elasticSearch = new ElasticSearchExt2(441800);
        $elasticSearch->setPageConf(5);
        $elasticSearch->setKeywordFilter("哈啤");
        print_r($elasticSearch->doSearch());
    }

    public function getSubSets(array $arr){
        if(count($arr) == 0) return [[]];

//        var_dump($arr);
        $element = array_pop($arr);
//        var_dump($element);
        $nextSubSets = $this->getSubSets($arr);
//        var_dump($nextSubSets);

        $subSets = $nextSubSets;
        foreach ($nextSubSets as $item){
            array_push($item,$element);
            $subSets []= $item;
        }

        return $subSets;
    }

    public function cmp($a, $b)
    {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? 1 : -1;
    }

}