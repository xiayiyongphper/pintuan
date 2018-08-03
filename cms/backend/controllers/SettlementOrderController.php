<?php

namespace backend\controllers;

use common\tools\Tools;
use Yii;
use backend\models\SettlementOrder;
use backend\models\SettlementOrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\tools\Excel;

/**
 * SettlementOrderController implements the CRUD actions for SettlementOrder model.
 */
class SettlementOrderController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * 新版的店铺结算
     * @return string
     */
    public function actionNewindex()
    {
        $data = [];
        return $this->render('newindex', $data);
    }

    /**
     * 新版的供货商结算
     * @return string
     */
    public function actionWholesaler()
    {
        $data = [];
        return $this->render('wholesaler', $data);
    }

    /**
     * 获取店铺结算列表
     * @return array
     */
    public function actionGetsettlementorders()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = new \backend\models\SettlementOrder();
        $storeModel = new \backend\models\Store();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $keys       = isset($get['key'])? $get['key'] : [];
        $pay_state  =  isset($keys['pay_state'])? $keys['pay_state'] : 0;
        $store_name =  isset($keys['store_name'])? $keys['store_name'] : '';
        $serachdate =  isset($keys['serachdate'])? $keys['serachdate'] : '';

        $where1 = [];
        $serachdate = trim($serachdate);
        if ($serachdate) {
            $serachdate = explode('~', $serachdate);
            $where1 = [
                'and',
                ['>=', 'created_at', $serachdate[0]],
                ['<=', 'created_at', $serachdate[1]],
            ];
        }

        $where2 = [];
        $store_name = trim($store_name);
        if ($store_name) {
             $storeModel = new \backend\models\Store();
             $storeWhere = [
                 'like','name',$store_name
             ];
             $storeSelect = 'id';
             $storeList = $storeModel::find()->select($storeSelect)->where($storeWhere)->asArray()->all();

             if (!empty($storeList)) {
                 $business_ids = [];
                 foreach ($storeList as $val) {
                     $business_ids[] = $val['id'];
                 }
                 $where2 = [
                     'in','business_id', $business_ids
                 ];
             } else {
                 $res = ['code'=>0,'count'=>0,'data'=>[]];
                 return $res;
             }
        }

        $where3 = [
            'settlement_type'=>1
        ];

        if ($pay_state) {
            $where3['pay_state'] = intval($pay_state);
        }

        $conut = $model::find()->where($where1)->andWhere($where2)->andWhere($where3)->count();
        $offset = $limit * ($page - 1);
        $data = $model::find()->where($where1)->andWhere($where2)->andWhere($where3)->orderBy('id desc')->limit($limit)->offset($offset)->asArray()->all();

        if ($data) {
            foreach ($data as $key=>$val) {
                $data[$key]['settlement_amount'] = sprintf("%.2f", $val['settlement_amount']/100);

                //获取店铺
                $storeInfo = $storeModel::findOne($val['business_id']);
                if ($storeInfo) {
                    $data[$key]['store_name'] = $storeInfo->name;
                } else {
                    $data[$key]['store_name'] = '';
                }

                //打款时间
                if (strtotime($val['pay_time'])) {
                    $data[$key]['pay_time'] = $val['pay_time'];
                } else {
                    $data[$key]['pay_time'] = '';
                }

                //打款状态
                if ($val['pay_state'] == 1) {
                    $data[$key]['pay_state_lable'] = '未打款';
                } else {
                    $data[$key]['pay_state_lable'] = '已打款';
                }
            }
        }
        $res = ['code'=>0,'count'=>$conut,'data'=>$data];
        return $res;
    }

    /**
     * 获取供货商结算列表
     * @return array
     */
    public function actionGetwholesalersettlement()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = new \backend\models\SettlementOrder();
        $wholesalerModel = new \backend\models\Wholesaler();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $keys       = isset($get['key'])? $get['key'] : [];
        $pay_state  =  isset($keys['pay_state'])? $keys['pay_state'] : 0;
        $wholesaler_name =  isset($keys['wholesaler_name'])? $keys['wholesaler_name'] : '';
        $serachdate =  isset($keys['serachdate'])? $keys['serachdate'] : '';

        $where1 = [];
        $serachdate = trim($serachdate);
        if ($serachdate) {
            $serachdate = explode('~', $serachdate);
            $where1 = [
                'and',
                ['>=', 'created_at', $serachdate[0]],
                ['<=', 'created_at', $serachdate[1]],
            ];
        }

        $where2 = [];
        $wholesaler_name = trim($wholesaler_name);
        if ($wholesaler_name) {
            $wholesalerWhere = [
                'like','name',$wholesaler_name
            ];
            $wholesalerSelect = 'id';
            $wholesalerList = $wholesalerModel::find()->select($wholesalerSelect)->where($wholesalerWhere)->asArray()->all();

            if (!empty($wholesalerList)) {
                $business_ids = [];
                foreach ($wholesalerList as $val) {
                    $business_ids[] = $val['id'];
                }
                $where2 = [
                    'in','business_id', $business_ids
                ];
            } else {
                $res = ['code'=>0,'count'=>0,'data'=>[]];
                return $res;
            }
        }

        $where3 = [
            'settlement_type'=>2
        ];

        if ($pay_state) {
            $where3['pay_state'] = intval($pay_state);
        }

        $conut = $model::find()->where($where1)->andWhere($where2)->andWhere($where3)->count();
        $offset = $limit * ($page - 1);
        $data = $model::find()->where($where1)->andWhere($where2)->andWhere($where3)->orderBy('id desc')->limit($limit)->offset($offset)->asArray()->all();

        if ($data) {
            foreach ($data as $key=>$val) {
                $data[$key]['settlement_amount'] = sprintf("%.2f", $val['settlement_amount']/100);

                //供货商
                $wholesalerInfo = $wholesalerModel::findOne($val['business_id']);
                if ($wholesalerInfo) {
                    $data[$key]['wholesaler_name'] = $wholesalerInfo->name;
                } else {
                    $data[$key]['wholesaler_name'] = '';
                }

                //打款时间
                if (strtotime($val['pay_time'])) {
                    $data[$key]['pay_time'] = $val['pay_time'];
                } else {
                    $data[$key]['pay_time'] = '';
                }

                //打款状态
                if ($val['pay_state'] == 1) {
                    $data[$key]['pay_state_lable'] = '未打款';
                } else {
                    $data[$key]['pay_state_lable'] = '已打款';
                }
            }
        }
        $res = ['code'=>0,'count'=>$conut,'data'=>$data];
        return $res;
    }

    /**
     * 设置状态
     * @return array
     */
    public function actionPaystate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = yii::$app->request->post();
        $ids = isset($post['id'])? trim($post['id']) : '';

        if (empty($ids)) {
            return ['code'=>1,'message'=> '参数错误'];
        }
        $idArr = explode(',', $ids);

        if (empty($idArr)) {
            return ['code'=>1,'message'=> '参数错误'];
        }

        $model = new \backend\models\SettlementOrder();
        $lastIds = [];
        foreach ($idArr as $id) {
            $currentId = intval($id);
            $info = $model::findOne($currentId);

            if (!$info) {
                return ['code'=>1,'message'=> '参数错误'];
            }

            if ($info->pay_state == 2) {
                return ['code'=>1,'message'=> $info->settlement_num . '已打款，请重新选择！'];
            }

            $lastIds[] = $currentId;
        }

        $curDate = date('Y-m-d H:i:s', time());
        $data = [
            'pay_state'=>2,
            'pay_time'=>$curDate,
            'updated_at'=>$curDate
        ];
        $where = ['in','id',$lastIds];
        $res = $model::updateAll($data, $where);

        if ($res == 0) {
            return ['code'=>1,'message'=> '网络繁忙，请稍后再尝试'];
        }

        return ['code'=>0,'message'=> '打款成功！'];
    }

    /**
     * Lists all SettlementOrder models.
     * @param int $settlement_type 单据的类型 1-超市 2-供货商
     * @return mixed
     */
    public function actionIndex($settlement_type = 1)
    {
        $searchModel = new SettlementOrderSearch();
        $getFilter = Yii::$app->request->queryParams;
        $getFilter['SettlementOrderSearch']['settlement_type'] = $settlement_type;
        $dataProvider = $searchModel->search($getFilter);
        // 赋值为后面导出做准备
        Yii::$app->session['SettlementOrderSearch'] = $getFilter;
//        print_r($getFilter);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'settlement_type' => $settlement_type == 1 ? '超市' : '供应商',
        ]);
    }

    /**
     * Displays a single SettlementOrder model.
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

    /**
     * Creates a new SettlementOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SettlementOrder();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SettlementOrder model.
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
     * Deletes an existing SettlementOrder model.
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
     * Finds the SettlementOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SettlementOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SettlementOrder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     *
     * 导出结算单数据
     * @throws \Exception
     */
    public function actionExport()
    {
        $params = Yii::$app->session['SettlementOrderSearch'];
//        $params = ['SettlementOrderSearch' => [
//            'settlement_time_from' => '2018-06-07 00:00:00',
//            'settlement_time_to' => '2018-06-07 00:00:00',
//            'business_name' => '张3',
//            'pay_state' => 2,
//            'settlement_type' => 1,
//        ]];
        $settlementName = $params['SettlementOrderSearch']['settlement_type'] == 1 ? '超市结算列表' : '供应商结算列表';
        $searchModel = new SettlementOrderSearch();
        $dataProvider = $searchModel->search($params);

        $step = 300;
        $count = $dataProvider->totalCount;
        $page_count = ceil($count / $step);

        $dataList = [];
        for ($i = 0; $i < $page_count; $i++) {
            set_time_limit(60);
            $items = $dataProvider->query->limit($step)->offset($i * $step);
            $items = $items->asArray()->all();
            /** @var SettlementOrder $item */
            foreach ($items as $item) {
                //#################
                $settlement_num = $item['settlement_num'];
                $business_name = $item['business_name'];
                $bank = $item['bank'];
                $account = '#' . $item['account'];
                $account_name = $item['account_name'];
                $settlement_amount = $item['settlement_amount'];
                $settlement_time = $item['settlement_time'];
                $pay_state = $item['pay_state'] == 1 ? '未打款' : '已打款';
                $pay_time = $item['pay_time'];

                $dataList[] = [
                    $settlement_num,
                    $business_name,
                    $bank,
                    $account,
                    $account_name,
                    $settlement_amount,
                    $settlement_time,
                    $pay_state,
                    $pay_time

                ];
            }
            unset($items);
        }

        $title = ['编号', '商家名称', '开户银行', '银行账号', '开户名称', '结算金额', '结算时间','打款状态', '打款时间'];
        $fileName = '店铺结算导出-'. date('Y-m-d H:i:s', time());

        if ($params['SettlementOrderSearch']['settlement_type'] ==2 ) {
            $title = ['编号', '供应商名称', '开户银行', '银行账号', '开户名称', '结算金额', '结算时间','打款状态', '打款时间'];
            $fileName = '供应商结算导出-'. date('Y-m-d H:i:s', time());
        } else {
            $title = ['编号', '商家名称', '开户银行', '银行账号', '开户名称', '结算金额', '结算时间','打款状态', '打款时间'];
            $fileName = '店铺结算导出-'. date('Y-m-d H:i:s', time());
        }

        Excel::exportExcel($title, $dataList, $fileName);
        exit;
    }

    /**
     *
     * 导入结算单数据
     * @throws \Exception
     */
    public function actionImport()
    {
        try {
            $model = new SettlementOrder();
            if (\Yii::$app->request->isPost) {
                ini_set('memory_limit','521M');
                set_time_limit(0);

                $sheetData = $this->_getExcelData('file');

                if (empty($sheetData)) {
                    echo  '请上传excel数据文件';
                    exit;
                }

                unset($sheetData[0]);
                $settlementAll = $sheetData;
                $transaction = Yii::$app->db->beginTransaction();

                foreach ($sheetData as $item) {
                    // 首先查询该单据号是否存在
                    /**@var SettlementOrder $settlementInfo * */
                    $settlementInfo = $model::find()->where(['settlement_num' => $item[0], 'business_name' => $item[1], 'bank' => $item[2], 'account' => str_replace('#', '', $item[3]), 'account_name' => $item[4]])->one();
                    if ($settlementInfo) {
                        // 若是该条记录已经打款 则跳过
                        if ($settlementInfo->pay_state == 2) {
                            continue;
                        }
                        // 更改打款状态 若是有打款时间 则填写打款时间
                        $settlementInfo->pay_state = 2;
                        if ($item[8] != '0000-00-00 00:00:00') {
                            $settlementInfo->pay_time = $item[8];
                        }
                        if (!$settlementInfo->validate() || !$settlementInfo->save()) {
                            Tools::log($settlementInfo->getErrors(),'xia.log');
                            $transaction->rollBack();
                            throw new \Exception('导入的数据结算编码为:' . $item[0] . '的记录导入失败', 0000);
                        }
                    } else {
                        $transaction->rollBack();
                        throw new \Exception('导入的数据结算编码为:' . $item[0] . '的记录不存在或者银行信息无法对应,请检查', 0000);
                    }
                }
                $transaction->commit();
            }
            echo  '导入成功';
            exit;
        } catch (\Exception $e) {
            Tools::logException($e);
        } catch
        (\Error $e) {
            Tools::logException($e);
        }
        echo  '导入成功';
        exit;
    }

    /**
     * 获取上传excel文件的数据
     * @param $fileName file的html控件名称
     */
    private function _getExcelData($fileName)
    {
        $tmp = UploadedFile::getInstanceByName($fileName);
        if (empty($tmp) || empty($tmp->tempName)) {
            return false;
        }
        $file_name = $tmp->tempName;
        return Excel::readExcelSheet($file_name);
    }


    /**
     * 获取店铺提现记录
     * @return array
     */
    public function actionGetwalletrecords()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\WalletRecord();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $keys = isset($get['key'])? $get['key'] : [];
        $storeName =  isset($keys['store_name'])? $keys['store_name'] : '';
        $status =  isset($keys['status'])? intval($keys['status']) : 0;
        $serachdate =  isset($keys['serachdate'])? $keys['serachdate'] : '';

        $where = [
            'wallet_record.type'=>2,
            'wallet_record.del'=>1
        ];

        $timeWhere = [];
        $serachdate = trim($serachdate);
        if ($serachdate) {
            $serachdate = explode('~', $serachdate);
            $timeWhere = [
                'and',
                ['>=', 'wallet_record.create_at', $serachdate[0]],
                ['<=', 'wallet_record.create_at', $serachdate[1]],
            ];
        }

        //打款状态
        $statusWhere = [
           'in','wallet_record.status',[1,2]
        ];

        if ($status) {
            $statusWhere = [
                'wallet_record.status'=>$status
            ];
        }

        $storeWhere = [];
        $storeName = trim($storeName);
        if ($storeName) {
            $storeWhere = [
                'like', 'store.name',trim($storeName)
            ];
        }

        if ($storeName) {
            $conut = $model::find()->leftJoin('store','store.id=wallet_record.store_id')
                                   ->where($where)->andWhere($timeWhere)->andWhere($storeWhere)->andWhere($statusWhere)->count();
        } else {
            $conut = $model::find()->where($where)->andWhere($timeWhere)->andWhere($statusWhere)->count();
        }

        $offset = $limit * ($page - 1);
        $select = 'wallet_record.*,store.name,store.bank,store.account,store.account_name';
        $data = $model::find()->leftJoin('store','store.id=wallet_record.store_id')
                              ->select($select)->where($where)->andWhere($timeWhere)->andWhere($storeWhere)->andWhere($statusWhere)
                              ->orderBy('wallet_record.id desc')->limit($limit)->offset($offset)->asArray()->all();

        if ($data) {
            $storeModel = new \backend\models\Store();
            foreach ($data as $key=>$val) {
                $data[$key]['amount'] = sprintf("%.2f", abs($val['amount'])/100);
                $data[$key]['balance'] =  sprintf("%.2f", $val['balance']/100);

                if (!strtotime($val['remit_at']) || strtotime($val['remit_at']) <= 0) {
                    $data[$key]['remit_at'] = '';
                }
            }
        }
        $res = ['code'=>0,'count'=>$conut,'data'=>$data];
        return $res;
    }

    /**
     * 导出店铺提现记录
     */
    public function actionExportwalletrecords()
    {
        $get = yii::$app->request->get();
        $model = new \backend\models\WalletRecord();

        $storeName =  isset($get['store_name'])? $get['store_name'] : '';
        $status =  isset($get['status'])? intval($get['status']) : 0;
        $serachdate =  isset($get['serachdate'])? $get['serachdate'] : '';


        $where = [
            'wallet_record.type'=>2,
            'wallet_record.del'=>1
        ];

        $timeWhere = [];
        $serachdate = trim($serachdate);
        if ($serachdate) {
            $serachdate = explode('~', $serachdate);
            $timeWhere = [
                'and',
                ['>=', 'wallet_record.create_at', $serachdate[0]],
                ['<=', 'wallet_record.create_at', $serachdate[1]],
            ];
        }

        //打款状态
        $statusWhere = [
            'in','wallet_record.status',[1,2]
        ];

        if ($status) {
            $statusWhere = [
                'wallet_record.status'=>$status
            ];
        }

        $storeWhere = [];
        $storeName = trim($storeName);
        if ($storeName) {
            $storeWhere = [
                'like', 'store.name',trim($storeName)
            ];
        }

        $select = 'wallet_record.*,store.name,store.bank,store.account,store.account_name';
        $data = $model::find()->leftJoin('store','store.id=wallet_record.store_id')
                       ->select($select)->where($where)->andWhere($timeWhere)->andWhere($storeWhere)->andWhere($statusWhere)
                       ->orderBy('wallet_record.id desc')->asArray()->all();

        if (empty($data)) {
            echo '尚未数据，请重新选择条件进行导出！';
            exit;
        }

        $dataList = [];
        foreach ($data as $key=>$val) {
            $remit_at = $val['remit_at'];
            if (!strtotime($remit_at) || strtotime($remit_at) <= 0) {
                $remit_at = '';
            }

            $statusMsg = '';
            if ($val['status'] == 1) {
                $statusMsg = '待打款';
            } else if ($val['status'] == 2) {
                $statusMsg = '已打款';
            }

            $dataList[] = [
                $val['id'],
                $val['record_number'] . ' ',
                $val['name'],
                $val['bank'],
                $val['account'] . ' ',
                $val['account_name'],
                sprintf("%.2f", abs($val['amount'])/100),
                $val['create_at'],
                $statusMsg,
                $remit_at
            ];
            unset($data[$key]);
        }

        $title = ['记录ID', '流水号', '店铺名称', '开户银行', '银行账号', '开户名称', '提现金额(元)', '提现时间','打款状态', '打款时间'];
        $fileName = '店铺结算记录-'. date('Y-m-d/H:i:s', time());

        Excel::exportExcel($title, $dataList, $fileName);
        exit;
    }


    /**
     * 导入店铺提现记录，进行打款的页面
     */
    public function actionImportstore()
    {
        $data = [];
        return $this->render('importstore',$data);
    }

    /**
     * 导入店铺提现记录，进行打款
     */
    public function actionUploadwalletrecords()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //获取上传的文件
        $files = $_FILES;

        if (!$files) {
            return ['code'=>1,'msg'=> '请上传文件！'];
        }

        if (!isset($files['file']) || !isset($files['file']['tmp_name'])) {
            return ['code'=>1,'msg'=> '请上传文件！'];
        }

        if (empty($files['file']['tmp_name'])) {
            return ['code'=>1,'msg'=> '请上传文件！'];
        }

        //获取文件后缀名
        $fileName =  $files['file']['name'];
        $fileNameArr = explode('.', $files['file']['name']);
        $suffix = end($fileNameArr);

        if ($suffix != 'xlsx') {
            return ['code'=>1,'msg'=> '请上传xlsx格式的excel文件！'];
        }

        $data = Excel::readExcelSheet($files['file']['tmp_name']);

        if (empty($data)) {
            return ['code'=>1,'msg'=> '请上传有数据的文件！'];
        }

        //验证数据模版
        $info = $data[0];
        if (count($info) != 10) {
            return ['code'=>1,'msg'=> '请上传规范模版的数据文件！'];
        }

        unset($data[0]);

        if (empty($data)) {
            return ['code'=>1,'msg'=> '请不要上传空文件！'];
        }

        ini_set('memory_limit','1024M');
        set_time_limit(0);

        if (count($data) > 5000) {
            return ['code'=>1,'msg'=> '最多只支持5K条记录导入！'];
        }

        $loginUser = Yii::$app->getUser();
        $ip = $_SERVER["REMOTE_ADDR"];
        $curDate = date('Y-m-d H:i:s', time());

        $userModel = new \backend\models\User();
        $userInfo  = $userModel::findOne($loginUser->id);
        $remark = '导入人:' . $userInfo->username . '(' . $loginUser->id . '),' . '导入IP:' . $ip  . ',导入时间:' . $curDate;

        $model = new \backend\models\WalletRecord();

        //开始导入打款
        $idArr = [];
        foreach ($data as $key=>$val) {
            $id = $val[0];
            $statusMsg = $val[8];

            if (!is_numeric($id) || $id <=0) {
                return ['code'=>1,'msg'=> '第'.$key.'行数据的记录ID异常，请认真检查'];
            }

            if (empty($statusMsg) || $statusMsg != '待打款') {
                return ['code'=>1,'msg'=> '第'.$key.'行数据的打款状态异常，请认真检查'];
            }

            $info = $model::findOne($id);
            if (empty($info)) {
                return ['code'=>1,'msg'=> '第'.$key.'行数据的记录不存在，请认真检查'];
            }

            if ($info->status == 2 ) {
                return ['code'=>1,'msg'=> '第'.$key.'行数据的记录已打款，请认真检查'];
            }

            if ($info->status == 0 ) {
                return ['code'=>1,'msg'=> '第'.$key.'行数据的记录为佣金转入，不是提现，请认真检查'];
            }

            $idArr[] = $id;
            unset($data[$key]);
        }

        //事务
        $tr = Yii::$app->wholesalerDb->beginTransaction();
        foreach ($idArr as $curid) {
            $sql = "update wallet_record set status=2,money_remark='{$remark}',remit_at='{$curDate}', update_at='{$curDate}' where id={$curid} and status=1 and type=2 limit 1";
            $effectRows = Yii::$app->wholesalerDb->createCommand($sql)->execute();//返回受影响行数
            if (!$effectRows) {
                $tr->rollBack();
                return ['code'=>1,'msg'=> '网络繁忙，请稍后再尝试！'];
            }
        }
        $tr->commit();

        return [
            'code'=>0,
            'msg'=> '导入成功！',
            'data'=>['src'=>'']
        ];
    }
}
