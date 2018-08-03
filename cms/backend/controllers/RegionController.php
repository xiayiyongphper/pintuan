<?php

namespace backend\controllers;

use backend\models\Region;
use yii\filters\VerbFilter;

class RegionController extends BaseController
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * 获取省份、城市、区域列表。
     * 参数pid，如果传则获取该id的子列表，例如某个省的城市列表，某个城市的区域列表，
     * 不过不传参数pid，表示获取省份列表
     */
    public function actionList()
    {
        $where = array();
        if (isset($_GET['pid']) && $_GET['pid']) {
            $where['parent_id'] = $_GET['pid'];
        } else {
            $where['level'] = 1;
        }
        $regions = Region::find()->where($where)->asArray()->all();
        $this->echoJson($regions);
    }

    /**
     * 根据编码获取下一级地区列表
     */
    public function actionRegions()
    {
        $code = 0;
        $type = 'province';
        $first = 1;
        if (isset($_REQUEST['c'])) {
            $code = $_REQUEST['c'];
        }
        if (isset($_REQUEST['t'])) {
            $type = $_REQUEST['t'];
        }
        if (isset($_REQUEST['f'])) {
            $first = $_REQUEST['f'];
        }
        $firstName = '';
        if ($first) {
            switch ($type) {
                case 'province':
                    $firstName = "请选择省份";
                    break;
                case 'city':
                    $firstName = "请选择城市";
                    break;
                case 'district':
                    $firstName = "请选择行政区";
                    break;
            }
        }
        $regions = Region::regions($code, $firstName, $type);
        $this->echoJson($regions);
    }
}
