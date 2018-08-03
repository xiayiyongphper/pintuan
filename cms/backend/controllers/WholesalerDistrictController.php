<?php

namespace backend\controllers;

use backend\models\Region;
use backend\models\WholesalerDistrict;
use yii;

class WholesalerDistrictController extends BaseController
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => yii\filters\VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }

    public function actionSave()
    {
        $province = 0;
        if (isset($_REQUEST['p'])) {
            $province = $_REQUEST['p'];
        }
        $city = 0;
        if (isset($_REQUEST['c'])) {
            $city = $_REQUEST['c'];
        }
        //需要保存的配送区域
        $districts = array();
        if (isset($_REQUEST['d'])) {
            $districts = $_REQUEST['d'];
        }
        $wholesalerId = 0;
        if (isset($_REQUEST['wid'])) {
            $wholesalerId = $_REQUEST['wid'];
        }
        //供货商已有配送区域
        $distincts = array();
        $distinctRegions = WholesalerDistrict::getDistinctList($wholesalerId, $city);
        foreach ($distinctRegions as $distinctRegion) {
            $distincts[] = $distinctRegion['district'];
        }
        //取交集，需要更新状态为正常
        $updates = array_intersect($districts, $distincts);

        //取差集，新增的
        $adds = array_diff($districts, $updates);
        //取差集，删除的
        $dels = array_diff($distincts, $updates);

        //批量新增
        if (0 != count($adds)) {
            $addSql = "INSERT INTO wholesaler_district (wholesaler_id,province,city,district,del) VALUES ";
            foreach ($adds as $district) {
                $addSql .= "("
                    . "$wholesalerId,"
                    . $province . ","
                    . $city . ","
                    . $district . ","
                    . 'del=1'
                    . "),";
            }
            $addSql = substr($addSql, 0, strlen($addSql) - 1);
        }

        if (0 != count($updates)) {
            $updatesIn = implode(",", $updates);
            $updateSql = "UPDATE wholesaler_district set del=1 WHERE wholesaler_id=$wholesalerId AND district IN ($updatesIn)";
        }

        if (0 != count($dels)) {
            $delsIn = implode(",", $dels);
            $delSql = "UPDATE wholesaler_district set del=2 WHERE wholesaler_id=$wholesalerId AND district IN ($delsIn)";
        }
        //事务处理
        $connection = yii::$app->wholesalerDb;
        $transaction = $connection->beginTransaction();
        try {
            if (isset($addSql)) {
                $connection->createCommand($addSql)->execute();
            }
            if (isset($updateSql)) {
                $connection->createCommand($updateSql)->execute();
            }
            if (isset($delSql)) {
                $connection->createCommand($delSql)->execute();
            }
            $transaction->commit();            //只有执行了commit(),对于上面数据库的操作才会真正执行
        } catch (Exception $e) {
            $error = $e->getMessage();  //获取抛出的错误
            $transaction->rollBack();
            $this->echoJson(null, 1, $error);
        }
        $this->echoJson(array('param' => $districts, 'add' => $adds, 'update' => $updates, 'del' => $dels));
    }

    /**
     * 供货商配送区域列表
     */
    public function actionDistricts()
    {
        $wid = 0;
        if (isset($_REQUEST['wid'])) {
            $wid = $_REQUEST['wid'];
        }
        $wholesalerRegions = WholesalerDistrict::getList($wid, 10, 0);
        //城市地区编码数组
        $codes = array();
        foreach ($wholesalerRegions as $region) {
            $codes[] = $region['province'];
            $codes[] = $region['city'];
            $codes[] = $region['district'];
        }

        //去除重复的字符串
        $codes = array_unique($codes);
        $regions = array();
        if (0 != count($codes)) {
            $regions = Region::getRegionByIn($codes);
        }

        //以code为key的数组
        $regionsMap = array();
        foreach ($regions as $region) {
            $code = $region['code'];
            $regionsMap[$code] = $region;
        }
        $len = count($wholesalerRegions);
        //设置省、城市、行政区、状态
        for ($index = 0; $index < $len; $index++) {
            $wholesalerRegions[$index]['province_name'] = $this->getRegionName($regionsMap, $wholesalerRegions[$index]['province']);
            $wholesalerRegions[$index]['city_name'] = $this->getRegionName($regionsMap, $wholesalerRegions[$index]['city']);
            $wholesalerRegions[$index]['district_name'] = $this->getRegionName($regionsMap, $wholesalerRegions[$index]['district']);
        }
        $this->echoJson($wholesalerRegions);
    }

    /**
     * 根据编码获取供货商选择地区列表
     */
    public function actionRegions()
    {
        $code = 0;
        $type = 'province';
        if (isset($_REQUEST['c'])) {
            $code = $_REQUEST['c'];
        }
        if (isset($_REQUEST['t'])) {
            $type = $_REQUEST['t'];
        }
        $regions = Region::regions($code, false, $type);
        $wholesalerRegions = WholesalerDistrict::getGroupList($type, 0, $_REQUEST['wid']);
        $wholesalerRegionMap = array();
        foreach ($wholesalerRegions as $region) {
            $regionCode = $region[$type];
            $wholesalerRegionMap[$regionCode] = $region;
        }
        $len = count($regions);
        for ($index = 0; $index < $len; $index++) {
            $regionCode = $regions[$index]['code'];
            if (isset($wholesalerRegionMap[$regionCode])) {
                $regions[$index]['sel'] = 1;
            } else {
                $regions[$index]['sel'] = 0;
            }
        }
        $this->echoJson($regions);
    }

    public function getRegionName($map, $key)
    {
        if (isset($map[$key])) {
            return $map[$key]['name'];
        }
        return $key;
    }
}
