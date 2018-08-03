<?php

namespace backend\controllers;

use backend\models\CommissionRecord;
use backend\models\Region;
use backend\models\Store;
use backend\models\StoreSearch;
use backend\models\WalletRecord;
use common\tools\Ftp;
use common\tools\Tools;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


/**
 * StoreController implements the CRUD actions for Store model.
 */
class StoreController extends Controller
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
     * Lists all Store models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StoreSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Store model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /*
     * 店铺的佣金
     */
    public function actionCommission($id)
    {
        $id = intval($id);
        $storeModel = new Store();
        $info = $storeModel::findOne($id);

        if (empty($info)) {
            echo '该店铺不存在';
            exit;
        }

        $model = new \backend\models\CommissionRecord();
        $where = [
            'store_id'=>$id,
            'del'=>1,
        ];
        $statusWhere1 = [
            'in','status',[1,2,3]
        ];
        $statusWhere2 = ['status'=>3];
        $statusWhere3 = [
            'in','status',[1,2]
        ];
        $total1 = $model::find()->where($where)->andWhere($statusWhere1)->sum('amount');
        $total2 = $model::find()->where($where)->andWhere($statusWhere2)->sum('amount');
        $total3 = $model::find()->where($where)->andWhere($statusWhere3)->sum('amount');

        if ($total1) {
            $total1 =  sprintf("%.2f", $total1/100);
        } else {
            $total1 = '0.00';
        }

        if ($total2) {
            $total2 =  sprintf("%.2f", $total2/100);
        } else {
            $total2 = '0.00';
        }

        if ($total3) {
            $total3 =  sprintf("%.2f", $total3/100);
        } else {
            $total3 = '0.00';
        }

        $data = [
            'store_id'=>intval($id),
            'info'=>$info,
            'total1'=>$total1,
            'total2'=>$total2,
            'total3'=>$total3
        ];

        return $this->render('commission',$data);
    }

    /*
     * 获取店铺的佣金
     */
    public function actionGetcommissions()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\CommissionRecord();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $keys = isset($get['key'])? $get['key'] : [];
        $store_id =  isset($keys['store_id'])? intval($keys['store_id']) : 0;
        $serachdate =  isset($keys['serachdate'])? $keys['serachdate'] : '';
        $status =  isset($keys['status'])? intval($keys['status']) : 0;//1已转入 2 待转入

        $where1 = [];
        $serachdate = trim($serachdate);
        if ($serachdate) {
            $serachdate = explode('~', $serachdate);
            $where1 = [
                'and',
                ['>=', 'commission_record.create_at', $serachdate[0]],
                ['<=', 'commission_record.create_at', $serachdate[1]],
            ];
        }

        $where2 = [];
        if ($status) {
            if ($status == 1) {
                $where2 = [
                    'commission_record.status'=>3
                ];
            } else if ($status == 2) {
                $where2 = [
                    'in', 'commission_record.status', [1,2]
                ];
            }
        } else {
            $where2 = [
                'in', 'commission_record.status', [1,2,3]
            ];
        }
        $andWhere = ['commission_record.del'=>1];
        if ($store_id) {
            $andWhere['commission_record.store_id'] = $store_id;
        }

        $conut = $model::find()->where($andWhere)->andWhere($where1)->andWhere($where2)->count();
        $offset = $limit * ($page - 1);
        $select = 'commission_record.*,order.order_number,order.amount as order_amount,order_address.name';
        $data = $model::find()->select($select)->where($andWhere)->andWhere($where1)->andWhere($where2)
            ->leftJoin('order','order.id=commission_record.order_id')
            ->leftJoin('order_address','order_address.order_id=commission_record.order_id')
            ->orderBy('commission_record.id desc')->limit($limit)->offset($offset)->asArray()->all();

        if ($data) {
            $storeModel = new \backend\models\Store();
            foreach ($data as $key=>$val) {
                $data[$key]['amount'] =  sprintf("%.2f", $val['amount']/100);
                $data[$key]['order_amount'] =  sprintf("%.2f", $val['order_amount']/100);
                if ($val['status'] <=2) {
                    $data[$key]['status_label'] = '待转入钱包';
                } else if ($val['status'] == 3) {
                    $data[$key]['status_label'] = '已转入钱包';
                }

                if (strtotime($val['transfer_at']) !== false && strtotime($val['transfer_at']) > 0) {
                    $data[$key]['transfer_at'] = $val['transfer_at'];
                } else {
                    $data[$key]['transfer_at'] = '';
                }

                //获取店铺的发送方式
                $data[$key]['delivery_type'] = '';
                $storeInfo = $storeModel::findOne($val['store_id']);
                if ($storeInfo) {
                    if ($storeInfo->delivery_type == 1) {
                        $data[$key]['delivery_type'] = '自提';
                    } else if ($storeInfo->delivery_type == ２) {
                        $data[$key]['delivery_type'] = '送货到家';
                    } else  {
                        $data[$key]['delivery_type'] = '';
                    }
                }
            }
        }
        $res = ['code'=>0,'count'=>$conut,'data'=>$data];
        return $res;
    }

    /**
     * 店铺的钱包
     * @param $id
     * @return string
     */
    public function actionWalletRecord($id)
    {
        $id = intval($id);
        $storeModel = new Store();
        $info = $storeModel::findOne($id);

        if (empty($info)) {
            echo '该店铺不存在';
            exit;
        }

        $model = new \backend\models\WalletRecord();
        $where = [
            'store_id'=>$id,
            'del'=>1,
        ];
        $typeWhere1 = [
            'in','type',[1,3]
        ];
        $typeWhere2 = ['type'=>2];

        $total3 = $info->wallet;
        $total2 = $model::find()->where($where)->andWhere($typeWhere2)->sum('amount');

        if (!$total3) {
            $total3 = 0;
        }

        if (!$total2) {
            $total2 = 0;
        } else {
            $total2 = abs($total2);
        }
        $total = $total3+$total2;
        $total = $total<=0? 0 : $total;

        if ($total) {
            $total =  sprintf("%.2f", $total/100);
        } else {
            $total = '0.00';
        }

        if ($total2) {
            $total2 =  sprintf("%.2f", $total2/100);
        } else {
            $total2 = '0.00';
        }

        if ($total3) {
            $total3 =  sprintf("%.2f", $total3/100);
        } else {
            $total3 = '0.00';
        }

        $data = [
            'store_id'=>intval($id),
            'info'=>$info,
            'total'=>$total,
            'total2'=>$total2,
            'total3'=>$total3
        ];

        return $this->render('wallet',$data);
    }

    /*
     * 获取店铺的钱包
     */
    public function actionGetwalletrecords()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\WalletRecord();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $keys = isset($get['key'])? $get['key'] : [];
        $store_id =  isset($keys['store_id'])? intval($keys['store_id']) : 0;

        $where = [];
        $where = ['del'=>1];
        if ($store_id) {
            $where['store_id'] = $store_id;
        }

        $conut = $model::find()->where($where)->count();
        $offset = $limit * ($page - 1);
        $select = '*';
        $data = $model::find()->select($select)->where($where)
            ->orderBy('id desc')->limit($limit)->offset($offset)->asArray()->all();

        $typeNames = [
            '1'=>'佣金转入',
            '2'=>'提现 ',
            '3'=>'后台导入奖金',
        ];
        if ($data) {
            $storeModel = new \backend\models\Store();
            foreach ($data as $key=>$val) {
                $data[$key]['amount'] =  sprintf("%.2f", $val['amount']/100);
                if (isset($typeNames[$val['type']])){
                    $data[$key]['type_label'] = $typeNames[$val['type']];
                } else {
                    $data[$key]['type_label'] = '';
                }
            }
        }
        $res = ['code'=>0,'count'=>$conut,'data'=>$data];
        return $res;
    }

    /**
     * Creates a new Store model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Store();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Store model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Store model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Store model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Store the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Store::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionList()
    {
        $req = \Yii::$app->request;
        $session = yii::$app->session;
        $provinces = $session->get('provinces', false);
        if (!$provinces) {
            $provinces = Region::listRegion();
            $session->set('provinces', $provinces);
        }
        $province = $req->get('province', '');
        $city = $req->get('city', '');
        $region = $req->get('region', '');
        $phone = $req->get('phone', '');

        $query = Store::find();

        if ($phone) {
            $query->where(['like', 'store_phone', $phone])->orWhere(['like', 'name', $phone]);
        }
        $pCode = '';
        $cCode = '';
        $rCode = '';

        if ($region) {
            $rRegion = Region::findOne(['id' => $region]);
            $query->andWhere(['district' => $rRegion->code]);
            if ($rRegion) {
                $rCode = $rRegion->code;
                $rName = $rRegion->name;
            }
            if ($rCode) {
                $query->andWhere(['district' => $rCode]);
            }
        } else {
            if ($city) {
                $cRegion = Region::findOne(['id' => $city]);
                if ($cRegion) {
                    $cCode = $cRegion->code;
                    $cName = $cRegion->name;
                }
                if ($cCode) {
                    $query->andWhere(['city' => $cCode]);
                }
            } else {
                if ($province) {
                    $pRegion = Region::findOne(['id' => $province]);
                    if ($pRegion) {
                        $pCode = $pRegion->code;
                        $pName = $pRegion->name;
                    }
                    if ($pCode) {
                        $query->andWhere(['province' => $pCode]);
                    }
                }
            }
        }
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 10]);
        $data = $query->offset($pages->offset)->orderBy('id desc')
            ->limit($pages->limit)->asArray()
            ->all();

        if ($data) {
            $curregion = new \backend\models\Region();
            foreach ($data as $key => $value) {
                //查询城市名称
                $data[$key]['city_name'] = $curregion::findName($value['city']);
                $data[$key]['district_name'] = $curregion::findName($value['district']);
            }
        }
        $params = array('res' => $data, 'pages' => $pages, 'phone' => $phone, 'provinces' => $provinces,
            'p' => $province, 'c' => $city, 'r' => $region);
        return $this->render('list', $params);
    }


    public function actionDetail($id)
    {
        $data = Store::findOne($id);
        $provice_info = Region::findOne(['code' => $data->province]);
        $province = $provice_info->name;
        $province_id = $provice_info->id;

        $city_info = Region::findOne(['code' => $data->city]);
        $city_id = $city_info->id;
        $city = $city_info->name;

        $region = Region::findOne(['code' => $data->district])->name;

        $region_info = Region::findOne(['code' => $data->district]);
        $region = $region_info->name;
        $region_id = $region_info->id;

        unset($provice_info, $city_info, $region_info);

        $provinces = Region::listRegion();

        $p1 = $p2 = [];
        $p1[] = [$data->business_license_img];
        $p2[] = [
            'url' => \yii\helpers\Url::to(['/store/image-delete']),
            'key' => $data->business_license_img
        ];

        $p3 = $p4 = [];
        $p3[] = [$data->store_front_img];
        $p4[] = [
            'url' => \yii\helpers\Url::to(['/store/image-delete']),
            'key' => $data->store_front_img
        ];

        return $this->render('detail',[
                'p1'=>$p1,
                'p2'=>$p2,
                'p3'=>$p3,
                'p4'=>$p4,
                'res' => $data,
                'provinces' => $provinces,
                'pcr' => $province . $city . $region,
                'province_id' => $province_id,
                'city_id' => $city_id,
                'region_id' => $region_id
            ]
        );
    }

    public function actionRegion()
    {
        $req = \Yii::$app->request;
        $id = $req->get('pid', 0);
        $res = Region::listRegion($id);
        echo json_encode($res);
        exit;
    }

    public function actionPintuanInfoSave()
    {
        yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $store_id = Yii::$app->request->post('id');
        $store = Store::findById($store_id);

        $data = Yii::$app->request->post();

        if (empty($data['Store']['bank'])) {
            return ['code'=>1,'message'=>'开户行不能为空'];
        }

        if (empty($data['Store']['account'])) {
            return ['code'=>1,'message'=>'银行卡号不能为空'];
        }

        if (empty($data['Store']['account_name'])) {
            return ['code'=>1,'message'=>'开户名称不能为空'];
        }

        if (empty($data['Store']['group_nickname'])) {
            return ['code'=>1,'message'=>'群昵称不能为空'];
        }

        $group_num = $data['Store']['group_num'];
        if (!is_numeric($group_num) || $group_num<=0) {
            return ['code'=>1,'message'=>'请填写大于0的群人数！'];
        }

        if ($group_num >= 1000000) {
            return ['code'=>1,'message'=>'请填写小于1000000的群人数！'];
        }

        if (empty($data['Store']['commission_id'])) {
            return ['code'=>1,'message'=>'请选择店铺佣金类型'];
        }

        if (empty($data['Store']['delivery_type'])) {
            return ['code'=>1,'message'=>'请选择配送方式'];
        }
        //$weixin = new \common\tools\Weixin();
        //小程序二维码
//        if (empty($store->mini_program_qrcode)) {
//            $file_name = 'store_xcx' . $store->id . time() . '.jpg';
//            $scene     = $store->id;
//            $page = 'pages/home/home';
//            $mini_program_qrcode = $weixin::setQrCode($file_name, $scene, $page);
//            if (!$mini_program_qrcode) {
//                echo '生成小程序二维码图片失败！';
//                exit;
//            }
//            $store->mini_program_qrcode = $mini_program_qrcode;
//        }

        //收货二维码
//        if (empty($store->receive_goods_qrcode)) {
//            $file_name = 'store_sh' . $store->id . time() . '.jpg';
//            $scene     = $store->id . ',' . $store->name . ',4';//4为待收货状态的订单
//            $page = 'pages/order/order';
//            $receive_goods_qrcode = $weixin::setQrCode($file_name, $scene, $page);
//            if ($receive_goods_qrcode) {
//                echo '生成收货二维码图片失败！';
//                exit;
//            }
//            $store->receive_goods_qrcode = $receive_goods_qrcode;
//        }


        if ($store->load($data) && $store->validate(false)) {
            $res = $store->save(false);
            if (!$res) {
                return ['code'=>1,'message'=>'网络繁忙，请稍后再尝试'];
            }
            return ['code'=>0,'message'=>'保存成功'];
        } else {
            return ['code'=>1,'message'=>'网络繁忙，请稍后再尝试'];
        }
    }

    public function actionPintuanInfo()
    {
        $store_id = \Yii::$app->request->get('id');
        $store = Store::findById($store_id);
        $wx_qrcode[] = $store->wx_qrcode;
        $wx_qrcode_op[] = [
            'url' => \yii\helpers\Url::to(['/store/image-delete']),
            'key' => $store->wx_qrcode,
        ];

        $owner_user_photo[] = $store->owner_user_photo;
        $owner_user_photo_op[] = [
            'url' => \yii\helpers\Url::to(['/store/image-delete']),
            'key' => $store->owner_user_photo,
        ];

        $bank_card_photo[] = $store->bank_card_photo;
        $bank_card_photo_op[] = [
            'url' => \yii\helpers\Url::to(['/store/image-delete']),
            'key' => $store->bank_card_photo,
        ];

        $mini_program_qrcode[] = $store->mini_program_qrcode;
        $mini_program_qrcode_op[] = [
            'url' => \yii\helpers\Url::to(['/store/image-delete']),
            'key' => $store->mini_program_qrcode,
        ];

        $receive_goods_qrcode[] = $store->receive_goods_qrcode;
        $receive_goods_qrcode_op[] = [
            'url' => \yii\helpers\Url::to(['/store/image-delete']),
            'key' => $store->receive_goods_qrcode,
        ];

        //获取店铺类型列表
        $storeModel = new \backend\models\StoreCommission();
        $store_type_list = $storeModel::find()->where(['del'=>1])->asArray()->all();
        if ($store_type_list) {
            foreach ($store_type_list as $key=>$commission) {
                if ($commission['commission_type'] == 1) {
                    $store_type_list[$key]['name'] = $commission['name'] . '----(' . $commission['commission_val'] . '%/单)';
                } else {
                    $store_type_list[$key]['name'] = $commission['name'] . '----(' . sprintf("%.2f", $commission['commission_val']/100) . '元/单)';
                }
            }
        }


        return $this->render('pintuan', [
            'store' => $store,
            'wx_qrcode' => $wx_qrcode,
            'wx_qrcode_op' => $wx_qrcode_op,
            'owner_user_photo' => $owner_user_photo,
            'owner_user_photo_op' => $owner_user_photo_op,
            'bank_card_photo' => $bank_card_photo,
            'bank_card_photo_op' => $bank_card_photo_op,
            'mini_program_qrcode' => $mini_program_qrcode,
            'mini_program_qrcode_op' => $mini_program_qrcode_op,
            'receive_goods_qrcode' => $receive_goods_qrcode,
            'receive_goods_qrcode_op' => $receive_goods_qrcode_op,
            'store_type_list'=>$store_type_list,
        ]);
    }

    public function actionImageUpload()
    {
        try {
            $model = new Store();
            $imageFile = UploadedFile::getInstance($model, 'img');
            Tools::log($imageFile, 'store.log');
            $parts = explode('.', $imageFile->name);
            $extension = strtolower(end($parts));
            $fileName = md5($imageFile->name) . '.' . $extension;
            $result = Ftp::upload($imageFile->tempName, $fileName, 'store', true);
            $result = json_decode($result, true);

            if ($result['code'] > 0) {
                throw new \Exception($result['msg'], $result['code']);
            }
            return json_encode([
                'files' => [
                    [
                        'name' => $fileName,
                        'size' => $imageFile->size,
                        'url' => $result['url'],
                        'thumbnailUrl' => str_replace('600x600', '180x180', $result['url']),
                        'deleteUrl' => 'image-delete?name=' . $fileName,
                        'deleteType' => 'POST',
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            Tools::logException($e);
        } catch (\Error $e) {

        }
        return '';
    }

    /**
     * 更新店铺信息
     */
    public function actionStoreSave()
    {
        $model = new Store();
        $data = Yii::$app->request->post();

        $id = intval($data['id']);
        if(!$id){
            $this->echoJson(1, '参数错误');
        }

        //参数验证
        if (empty($data['name'])) {
            $this->echoJson(1, '请填写店铺名称');
        }

        if (empty($data['store_phone'])) {
            $this->echoJson(1, '请填写手机号码');
        }

        if (empty($data['district'])) {
            $this->echoJson(1, '请选择省/市/县');
        }

        if (empty($data['address'])) {
            $this->echoJson(1, '请填写地址');
        }

        if (empty($data['detail_address'])) {
            $this->echoJson(1, '请填写详细地址');
        }

        if (empty($data['type'])) {
            $this->echoJson(1, '请选择客户类型');
        }

        $store_img = $data['Store'];
        unset($data['_csrf-frontend'], $data['id'], $data['Store']);

        if ($store_img['business_license_img']) {
            $data['business_license_img'] = $store_img['business_license_img'];
        }

        if ($store_img['store_front_img']) {
            $data['store_front_img'] = $store_img['store_front_img'];
        }


        if (($data['district'] == 0) || empty($data['district'])) {
            $this->echoJson(1, '请选择好城市！');
        }

        //获取城市编码
        $region_model = new\backend\models\Region();
        if ($data['province']) {
            $province_info = $region_model::findOne($data['province']);
            $data['province'] = $province_info->code;
            unset($province_info);
        }
        if ($data['city']) {
            $city_info = $region_model::findOne($data['city']);
            $data['city'] = $city_info->code;
            unset($city_info);
        }
        if ($data['district']) {
            $district_info = $region_model::findOne($data['district']);
            $data['district'] = $district_info->code;
            unset($district_info);
        }
        $info = $model::findOne($id);
        if (!$info) {
            $this->echoJson(1, '参数错误');
        }

        $res = $model::updateAll($data, ['id'=>$id]);

        if ($res){
            $this->echoJson(0, '编辑成功');
        } else {
            Tools::logException(new Exception(json_encode($model->errors)));
            $this->echoJson(1, '编辑失败');
        }
    }
    /**
     * 图片上传
     * @return string
     */
    public function actionImageUpload2()
    {
        $get = yii::$app->request->get();
        $fileTabName = $get['name'];

        try {
            $model = new Store();
            $imageFile = UploadedFile::getInstance($model, $fileTabName);
            Tools::log($imageFile, 'store.log');
            $parts = explode('.', $imageFile->name);
            $extension = strtolower(end($parts));
            $fileName = md5($imageFile->name) . '.' . $extension;
            $result = Ftp::upload($imageFile->tempName, $fileName, 'store', true);
            $result = json_decode($result, true);

            if ($result['code'] > 0) {
                throw new \Exception($result['msg'], $result['code']);
            }
            return json_encode([
                'files' => [
                    [
                        'name' => $fileName,
                        'size' => $imageFile->size,
                        'url' => $result['url'],
                        'thumbnailUrl' => str_replace('600x600', '180x180', $result['url']),
                        'deleteUrl' => 'image-delete?name=' . $fileName,
                        'deleteType' => 'POST',
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            Tools::logException($e);
        } catch (\Error $e) {

        }
        return '';
    }

    /**
     * 删除图片
     * @return array
     */
    public function actionImageDelete()
    {
        if ($id = \Yii::$app->request->post('key')) {
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['success' => true];
    }

    /**
     * 输出json
     * @param int $status
     * @param string $msg
     * @param null $data
     * @param string $url
     */
    private function echoJson($status=1, $msg='未知错误', $data=null, $url='')
    {
        $data = [
            'status' => $status,
            'msg'    => $msg,
            'data'   => $data,
            'url'    => $url,
        ];

        echo json_encode($data);
        exit;
    }
}
