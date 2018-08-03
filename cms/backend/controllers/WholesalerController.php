<?php

namespace backend\controllers;

use backend\models\Region;
use backend\models\Wholesaler;
use yii;

/**
 * Site controller
 */
class WholesalerController extends BaseController
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

    /**
     * 供货商管理二级页面
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionList()
    {
        $province = 0;
        $city = 0;
        $district = 0;
        if (isset($_GET['p'])) {//省份
            $province = $_GET['p'];
        }
        if (isset($_GET['c'])) {//城市
            $city = $_GET['c'];
        }
        if (isset($_GET['d'])) {//地区
            $district = $_GET['d'];
        }
        $page = 0;
        if (isset($_GET['page']) && $_GET['page']) {//分页
            $page = $_GET['page'] - 1;
        }
        $keyword = "";
        if (isset($_GET['w']) && $_GET['w']) {//关键词
            $keyword = $_GET['w'];
        }
        //查询供货商列表
        $where = array();
        if ($district) {
            $where['district'] = $district;
        } else if ($city) {
            $where['city'] = $city;
        } else if ($province) {
            $where['province'] = $province;
        }
        $query = Wholesaler::find()->where($where);
        if ($keyword) {
            $query->andWhere(['like', 'phone', $keyword]);
            $query->orWhere(['like', 'name', $keyword]);
        }
        $query->orderBy('created_at desc');
        //总数量
        $count = $query->count();
        //当前分页的数据
        $wholesalers = $query->limit(10)->offset($page * 10)->asArray()->all();
        //城市区域赋值
        $len = count($wholesalers);

        //城市地区编码数组
        $codes = array();
        for ($index = 0; $index < $len; $index++) {
            $wholesaler = $wholesalers[$index];
            $codes[] = $wholesaler['province'];
            $codes[] = $wholesaler['city'];
            $codes[] = $wholesaler['district'];
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
        //设置省、城市、行政区、状态
        for ($index = 0; $index < $len; $index++) {
            $wholesalers[$index]['province_name'] = $this->getRegionName($regionsMap, $wholesalers[$index]['province']);
            $wholesalers[$index]['city_name'] = $this->getRegionName($regionsMap, $wholesalers[$index]['city']);
            $wholesalers[$index]['district_name'] = $this->getRegionName($regionsMap, $wholesalers[$index]['district']);
            $wholesalers[$index]['status_label'] = Wholesaler::getStatusLabel($wholesalers[$index]['status']);
        }

        $pages = $count % 10 == 0 ? (int)($count / 10) : (int)($count / 10 + 1);
        $params = array('provinces' => Region::regions(0, '请选择省份'),
            'cities' => Region::regions($province, '请选择城市', 'city'),
            'districts' => Region::regions($city, '请选择行政区', 'district'),
            'wholesalers' => $wholesalers, 'page' => $page, 'pages' => $pages);
        return $this->render('list', $params);
    }

    public function getRegionName($map, $key)
    {
        if (isset($map[$key])) {
            return $map[$key]['name'];
        }
        return $key;
    }

    /**
     * 新增供应商
     */
    public function actionAdd()
    {
        $province = 0;
        $city = 0;
        $params = array('provinces' => Region::regions(0, '请选择省份'),
            'cities' => Region::regions($province, '请选择城市', 'city'),
            'districts' => Region::regions($city, '请选择行政区', 'district')
        );
        return $this->render('add', $params);
    }

    /*
     * ajax新增供应商
     */
    public function actionInsert()
    {
        //验证
        $post = Yii::$app->request->post();
        $wholesaler = $post['Wholesaler'];
        $this->validParam($wholesaler, 'name', '请填写供货商名称');
        $this->validParam($wholesaler, 'phone', '请填写联系电话');
        $this->validParam($wholesaler, 'province', '请选择省份');
        $this->validParam($wholesaler, 'city', '请选择城市');
        $this->validParam($wholesaler, 'district', '请选择行政区');
        $this->validParam($wholesaler, 'store_address', '请填写详细地址');

        $this->validParam($wholesaler, 'settlement_cycle', '请填写结算周期(天）');
        $this->validParam($wholesaler, 'margin', '请填写保证金（分）');
        $this->validParam($wholesaler, 'bank', '请填写开户行');
        $this->validParam($wholesaler, 'account', '请填写银行账号');
        $this->validParam($wholesaler, 'account_name', '请填写开户名称');

        $model = new Wholesaler();

        $wholesaler['margin'] = 100 * $wholesaler['margin'];
        $cur_time = date('Y-m-d H:i:s', time());
        $wholesaler['created_at'] = $cur_time;
        $wholesaler['updated_at'] = $cur_time;

        $id = $model->add($wholesaler);
        if ($id) {
             $this->echoJson(array(), 0, $id);
        }

        $this->echoJson(array(), 1, '修改失败');
    }

    public function actionDetail($id)
    {
        $model = $this->findModel($id);
        $province = 0;
        if ($model['province']) {
            $province = $model['province'];
        }
        $city = 0;
        if ($model['city']) {
            $city = $model['city'];
        }
        $params = array('provinces' => Region::regions(0, '请选择省份'),
            'cities' => Region::regions($province, '请选择城市', 'city'),
            'districts' => Region::regions($city, '请选择行政区', 'district'),
            'model' => $model,);

        return $this->render('detail', $params);
    }

    public function actionUpdate()
    {
        $id = $_POST['id'];
        //验证
        $wholesaler = $_POST['Wholesaler'];
        $this->validParam($wholesaler, 'name', '请填写供货商名称');
        $this->validParam($wholesaler, 'phone', '请填写联系电话');
        $this->validParam($wholesaler, 'province', '请选择省份');
        $this->validParam($wholesaler, 'city', '请选择城市');
        $this->validParam($wholesaler, 'district', '请选择行政区');
        $this->validParam($wholesaler, 'store_address', '请填写详细地址');
        $model = $this->findModel($id);
        if (!$model) {
            $this->echoJson(array(), 1, '供货商不存在');
        }
        $model->load($_POST);
        if ($model->save()) {
            $this->echoJson(array(), 0, '修改成功');
        }
        $this->echoJson(array(), 1, '修改失败');
    }

    public function actionPin($id)
    {
        $model = $this->findModel($id);
        return $this->render('pin', [
            'model' => $model,
        ]);
    }

    public function actionPinUpdate()
    {
        $id = $_POST['id'];
        //验证
        $wholesaler = $_POST['Wholesaler'];
        $this->validParam($wholesaler, 'settlement_cycle', '请填写结算周期');
        if (!$this->isPositiveNum($wholesaler['settlement_cycle'])) {
            $this->echoJson(array(), 1, '结算周期必须为正整数');
        }
        if (!is_numeric($wholesaler['margin'])) {
            $this->echoJson(array(), 1, '保证金必须为整数');
        }
        if (0 > $wholesaler['margin']) {
            $this->echoJson(array(), 1, '保证金必须大于等于0');
        }
        //页面显示为元，数据库为分
        $_POST['Wholesaler']['margin'] = 100 * $wholesaler['margin'];
        $this->validParam($wholesaler, 'bank', '请填写开户银行');
        $this->validParam($wholesaler, 'account_name', '请填写开户名称');
        $this->validParam($wholesaler, 'account', '请填写开户账号');
        $model = $this->findModel($id);
        if (!$model) {
            $this->echoJson(array(), 1, '供货商不存在');
        }
        $model->load($_POST);
        if ($model->save()) {
            $this->echoJson(array(), 0, '修改成功');
        }
        $this->echoJson(array(), 1, '修改失败');
    }

    public function actionProducts()
    {
        $province = 0;
        $city = 0;
        $district = 0;
        if (isset($_GET['p'])) {//省份
            $province = $_GET['p'];
        }
        if (isset($_GET['c'])) {//城市
            $city = $_GET['c'];
        }
        if (isset($_GET['d'])) {//地区
            $district = $_GET['d'];
        }
        $page = 0;
        if (isset($_GET['page']) && $_GET['page']) {//分页
            $page = $_GET['page'] - 1;
        }
        $keyword = "";
        if (isset($_GET['w']) && $_GET['w']) {//关键词
            $keyword = $_GET['w'];
        }
        //查询供货商列表
        $where = array();
        if ($district) {
            $where['district'] = $district;
        } else if ($city) {
            $where['city'] = $city;
        } else if ($province) {
            $where['province'] = $province;
        }
        $query = Wholesaler::find()->where($where);
        if ($keyword) {
            $query->andWhere(['like', 'phone', $keyword]);
            $query->orWhere(['like', 'name', $keyword]);
        }
        $query->orderBy('created_at desc');
        //总数量
        $count = $query->count();
        //当前分页的数据
        $wholesalers = $query->limit(10)->offset($page * 10)->asArray()->all();
        //城市区域赋值
        $len = count($wholesalers);

        //城市地区编码数组
        $codes = array();
        for ($index = 0; $index < $len; $index++) {
            $wholesaler = $wholesalers[$index];
            $codes[] = $wholesaler['province'];
            $codes[] = $wholesaler['city'];
            $codes[] = $wholesaler['district'];
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
        //设置省、城市、行政区、状态
        for ($index = 0; $index < $len; $index++) {
            $wholesalers[$index]['province_name'] = $this->getRegionName($regionsMap, $wholesalers[$index]['province']);
            $wholesalers[$index]['city_name'] = $this->getRegionName($regionsMap, $wholesalers[$index]['city']);
            $wholesalers[$index]['district_name'] = $this->getRegionName($regionsMap, $wholesalers[$index]['district']);
            $wholesalers[$index]['status_label'] = Wholesaler::getStatusLabel($wholesalers[$index]['status']);
        }

        $pages = $count % 10 == 0 ? (int)($count / 10) : (int)($count / 10 + 1);
        $params = array('provinces' => Region::regions(0, '请选择省份'),
            'cities' => Region::regions($province, '请选择城市', 'city'),
            'districts' => Region::regions($city, '请选择行政区', 'district'),
            'wholesalers' => $wholesalers, 'page' => $page, 'pages' => $pages);
        return $this->render('products', $params);
    }

    public function isPositiveNum($word)
    {
        if (preg_match("/^[1-9][0-9]*$/", $word)) {
            return true;
        }
        return false;
    }

    public function validParam($param, $key, $tip)
    {
        //非空验证
        if (!isset($param[$key]) || '' == $param[$key]) {
            $this->echoJson(array(), 1, $tip);
        }
    }

    protected function findModel($id)
    {
        if (($model = Wholesaler::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * @param $data
     * @param int $code
     * @param string $msg
     */
    public function echoJson($data, $code = 0, $msg = '查询成功')
    {
        $res = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];

        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function actionSelectList($q = '')
    {
        $req = \Yii::$app->getRequest();
        if (!$req->getIsAjax()) {
            return $this->redirect('/site/error');
        }

        $out = ['results' => []];
        if (!empty($q)) {
            $models = Wholesaler::find()->where(['like', 'name', $q])->all();
            if ($models) {
                foreach ($models as $model) {
                    $out['results'][] = [
                        'id' => $model->id,
                        'text' => $model->name
                    ];
                }
            }
        }

        return json_encode($out);
    }
}
