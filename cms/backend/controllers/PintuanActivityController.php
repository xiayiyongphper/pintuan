<?php

namespace backend\controllers;

use backend\models\Pintuan;
use backend\models\PintuanActivity;
use backend\models\PintuanActivitySearch;
use backend\models\PintuanActivityStore;
use backend\models\PintuanTask;
use backend\models\PintuanUser;
use backend\models\Product;
use backend\models\ProductPintuanUser;
use backend\models\Region;
use backend\models\Store;
use backend\models\WholesalerDistrict;
use common\tools\Ftp;
use common\tools\Tools;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use common\tools\Common;

/**
 * PintuanActivityController implements the CRUD actions for PintuanActivity model.
 */
class PintuanActivityController extends Controller
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
     * Lists all PintuanActivity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PintuanActivitySearch();


        $getInfo = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'all' => isset($getInfo['PintuanActivitySearch']['all']) ? $getInfo['PintuanActivitySearch']['all'] : '',
        ]);
    }

    /**
     * Displays a single PintuanActivity model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        //拼团商品
        $productModel = new \backend\models\Product();
        $productInfo = $productModel::findOne(['id' => $model->product_id]);

        //获取拼团商品规格列表
        $pintuanModel = new \backend\models\PintuanActivity();
        $specifications = $pintuanModel->getSpecifications($id);
        if ($specifications) {
            //中午不转码
            foreach ($specifications as $key=>$item) {
                $specifications[$key]['item_detail'] = json_encode(json_decode($item['item_detail'],true),JSON_UNESCAPED_UNICODE);
            }
        }

        //获取自提点列表
        $storeModel = new \backend\models\PintuanActivityStore();
        $storeList = $storeModel->getStoreList($model->id);

        return $this->render('view', [
            'model' => $model,
            'productName'=>$productInfo->name,
            'specifications'=>$specifications,
            'storeList'=>$storeList,
        ]);
    }

    public function actionKk()
    {
        return 789;
    }

    /**
     * Creates a new PintuanActivity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PintuanActivity();
        $model->sort = 1;
        $model->place_type = 1;
        $model->effective_hours = 24;
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new PintuanActivity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDocreate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new PintuanActivity();
        $postInfo = yii::$app->request->post();

        if (!empty($postInfo)) {
            //拼团商品信息
            $product_id = $postInfo['PintuanActivity']['product_id'];
            $speIds = $postInfo['speIds'];
            $pin_prices = $postInfo['pin_prices'];
            unset($postInfo['PintuanActivity']['id'], $postInfo['speIds'], $postInfo['pin_prices']);


            if (!is_numeric($product_id) || $product_id <= 0) {
                return ['code'=>1,'message'=> '请选择拼团活动商品！'];
            }

            if (empty($speIds) || empty($pin_prices)) {
                return ['code'=>1,'message'=> '请选择拼团活动商品！'];
            }

            if (count($speIds) != count($pin_prices)) {
                return ['code'=>1,'message'=> '请选择拼团活动商品！'];
            }

            //新人专区商品的价格
            $newUserPrices = $this->_getNewuserPrices($speIds);

            foreach ($pin_prices as $index=>$price) {
                if (!is_numeric($price) || $price <= 0) {
                    return ['code'=>1,'message'=> '请认真填写拼团的价格！'];
                }

                $speId = $speIds[$index];
                if (!is_numeric($speId) || $speId <= 0) {
                    return ['code'=>1,'message'=> '请认真选择拼团商品！'];
                }

                //新人活动价格判断
                if ($newUserPrices) {
                    if (isset($newUserPrices[$speId])) {
                        $pricesInfo = $newUserPrices[$speId];
                        if ($price <= $pricesInfo['price']) {
                            $msg = '新人活动id：' . $pricesInfo['act_id'] . '，规格id：' . $pricesInfo['spec_id'];
                            return ['code'=>1,'message'=> '拼团价格必须大于新人价格！' . $msg];
                        }
                    }
                }
            }

            // 判断开始时间和当前时间
            if ($postInfo['PintuanActivity']['start_time'] <= date('Y-m-d H:i')) {
                return ['code'=>1,'message'=> '拼团活动开始时间必须大于当前时间！'];
            }

            // 判断开始时间和结束时间
            if ($postInfo['PintuanActivity']['start_time'] >= $postInfo['PintuanActivity']['end_time']) {
                return ['code'=>1,'message'=> '拼团活动开始时间不能大于结束时间！'];
            }

            //活动自提点  place_type  自提点类型，1同供货商配送范围，2手动选择自提点
            $place_type = $postInfo['PintuanActivity']['place_type'];
            if (empty($place_type) || !is_numeric($place_type) || !in_array($place_type, [1,2])) {
                return ['code'=>1,'message'=> '请选择自提点类型！'];
            }

            $storeIdArr = isset($postInfo['storeids'])? $postInfo['storeids'] : [];
            unset($postInfo['storeids']);
            $place_type = intval($place_type);
            if ($place_type == 2) {
                 if (empty($storeIdArr)) {
                     return ['code'=>1,'message'=> '请选择自提点！'];
                 }
            }

            //拼团活动封面图
            if (empty($postInfo['PintuanActivity']['cover_picture'])) {
                return ['code'=>1,'message'=> '请上传拼团活动的图片！'];
            }

            if (!isset($postInfo['PintuanActivity']['effective_hours']) || !is_numeric($postInfo['PintuanActivity']['effective_hours']) || $postInfo['PintuanActivity']['effective_hours'] <= 0) {
                return ['code'=>1,'message'=> '请认真填写有效时长！'];
            }

            $postInfo = $this->numberStrategy($postInfo);

            if (isset($postInfo['message'])) {
                return ['code'=>1,'message'=> $postInfo['message']];
            }

            $model->load($postInfo);
            if ($model->product_id) {
                // 根据商品id查询出供应商的id
                $productInfo = Product::findOne(['id' => $model->product_id]);
                $model->wholesaler_id = $productInfo ? $productInfo->wholesaler_id : 0;
            }
            $model->strategy = $postInfo['strategy'] ? json_encode($postInfo['strategy']) : '';// json化的人数策略数据
            $model->create_at = date('Y-m-d H:i:s');
            $model->update_at = date('Y-m-d H:i:s');
            $model->del = 1;
            $model->isNewRecord = true;
            $model->place_type = $place_type;

            //获取最后上传的一张图片
            $imgArr = explode(";", $postInfo['PintuanActivity']['cover_picture']);
            $count = count($imgArr);
            if ($count >= 2) {
                $model->cover_picture = $imgArr[$count - 1];
            } else {
                $model->cover_picture = $imgArr[0];
            }

            //虚拟已拼商品数量
            $model->already_pin = rand(1000, 10000);
            // 开启事物处理
            $transaction = PintuanActivity::getDb()->beginTransaction();

            if ($model->validate(false) && $model->save(false)) {
                //拼团活动商品的新增
                $goodRes = $this->_addProducts($model->id, $speIds, $pin_prices);
                if (!$goodRes) {
                    $transaction->rollBack();
                    return ['code'=>1,'message'=> '拼团活动商品保存失败！'];
                }

                if ($place_type == 2) {
                    $storeRes = $this->_addStores($model->id, $storeIdArr);
                    if (!$storeRes) {
                        $transaction->rollBack();
                        return ['code'=>1,'message'=> '拼团活动自提点保存失败'];
                    }
                }

                //新建拼团活动时候需要新增拼团
                $createRes = $this->createNewPintuan($model);
                if (is_array($createRes) && isset($createRes['message'])) {
                    return ['code'=>1,'message'=> $createRes['message']];
                }

                $transaction->commit();
                return ['code'=>0,'message'=> '操作成功！'];
            }
        } else {
            return ['code'=>0,'message'=> '参数错误！'];
        }

        return ['code'=>0,'message'=> '操作成功！'];
    }
    /**
     * Updates an existing PintuanActivity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = intval($id);
        $info = $this->findModel($id);

        //获取拼团商品
        $prodcutModel = new \backend\models\Product();
        $productInfo  = $prodcutModel::findOne($info->product_id);

        //获取拼团商品规格列表
        $model = new \backend\models\PintuanActivity();
        $specifications = $model->getSpecifications($id);
        if ($specifications) {
            foreach ($specifications as $key=>$item) {
                $specifications[$key]['item_detail'] = json_encode(json_decode($item['item_detail'],true),JSON_UNESCAPED_UNICODE);
            }
        }

        //获取自提点列表
        $storeModel = new \backend\models\PintuanActivityStore();
        $storeList = $storeModel->getStoreList($info->id);
        return $this->render('update', [
            'model' => $info,
            'productInfo'=>$productInfo,
            'specifications'=>$specifications,
            'storeList'=>$storeList,
            'operation' => 'edit'
        ]);
    }

    /**
     * Updates an existing PintuanActivity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDoupdate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $postInfo = Yii::$app->request->post();

        if (empty($postInfo)) {
            return ['code'=>1,'message'=> '参数错误'];
        }

        if (!empty($postInfo)) {
            if (!isset($postInfo['PintuanActivity']['id']) || !is_numeric($postInfo['PintuanActivity']['id'])) {
                return ['code'=>1,'message'=> '参数错误'];
            }

            $id = intval($postInfo['PintuanActivity']['id']);
            $model = $this->findModel($id);

            if (!$model) {
                return ['code'=>1,'message'=> '参数错误'];
            }

            //进行中的拼团：只能编辑排序
            $curTime = time();
            $curDate = date('Y-m-d H:i:s', $curTime);
            $start_time = strtotime($model->start_time);
            $end_time = strtotime($model->end_time);
            $status = $model->status;

            //尚未开启，而且拼团活动尚未结束，则只能：编辑排序
            if ($start_time <= $curTime && $end_time > $curTime && $status == 1) {
                  //只能编辑排序
                $sort = $postInfo['PintuanActivity']['sort'];
                if (!is_numeric($sort) || $sort<0) {
                    return ['code'=>1,'message'=> '排序参数错误！'];
                }
                $sortData = [
                    'sort'=> intval($sort),
                    'update_at'=>$curDate
                ];
                $pintuanActivityModel = new \backend\models\PintuanActivity();
                $res = $pintuanActivityModel::updateAll($sortData, ['id'=>$id]);
                if ($res) {
                    return ['code'=>1,'message'=> '编辑成功！拼团中的活动只能编辑排序！'];
                }
                return ['code'=>1,'message'=> '编辑排序失败！'];
            }

            //活动已结束了，不能再进行任何操作了
            if ($status == 2 || ($end_time < $curTime)) {
                return ['code'=>1,'message'=> '该拼团活动已结束，不能进行进行任何编辑！'];
            }

            //拼团商品信息
            $product_id = $postInfo['PintuanActivity']['product_id'];
            $speIds = $postInfo['speIds'];
            $pin_prices = $postInfo['pin_prices'];
            unset($postInfo['PintuanActivity']['id'], $postInfo['speIds'], $postInfo['pin_prices']);

            if (!is_numeric($product_id) || $product_id <= 0) {
                return ['code'=>1,'message'=> '请选择拼团活动商品！'];
            }

            if (empty($speIds) || empty($pin_prices)) {
                return ['code'=>1,'message'=> '请选择拼团活动商品！'];
            }

            if (count($speIds) != count($pin_prices)) {
                return ['code'=>1,'message'=> '请选择拼团活动商品！'];
            }

            //新人专区商品的价格
            $newUserPrices = $this->_getNewuserPrices($speIds);

            foreach ($pin_prices as $index=>$price) {
                if (!is_numeric($price) || $price <= 0) {
                    return ['code'=>1,'message'=> '请认真填写拼团的价格！'];
                }

                $speId = $speIds[$index];
                if (!is_numeric($speId) || $speId <= 0) {
                    return ['code'=>1,'message'=> '请认真选择拼团商品！'];
                }
                //新人活动价格判断
                if ($newUserPrices) {
                    if (isset($newUserPrices[$speId])) {
                         $pricesInfo = $newUserPrices[$speId];
                         if ($price <= $pricesInfo['price']) {
                             $msg = '新人活动id：' . $pricesInfo['act_id'] . '，规格id：' . $pricesInfo['spec_id'];
                             return ['code'=>1,'message'=> '拼团价格必须大于新人价格！' . $msg];
                         }
                    }
                }
            }

            // 判断开始时间和当前时间
            if ($postInfo['PintuanActivity']['start_time'] <= date('Y-m-d H:i')) {
                return ['code'=>1,'message'=> '拼团活动开始时间必须大于当前时间！'];
            }

            // 判断开始时间和结束时间
            if ($postInfo['PintuanActivity']['start_time'] >= $postInfo['PintuanActivity']['end_time']) {
                return ['code'=>1,'message'=> '拼团活动开始时间不能大于结束时间'];
            }

            //活动自提点  place_type  自提点类型，1同供货商配送范围，2手动选择自提点
            $place_type = $postInfo['PintuanActivity']['place_type'];
            if (empty($place_type) || !is_numeric($place_type) || !in_array($place_type, [1,2])) {
                return ['code'=>1,'message'=> '请选择自提点类型！'];
            }

            $storeIdArr = isset($postInfo['storeids'])? $postInfo['storeids'] : [];
            unset($postInfo['storeids']);
            $place_type = intval($place_type);
            if ($place_type == 2) {
                if (empty($storeIdArr)) {
                    return ['code'=>1,'message'=> '请选择自提点！'];
                }
            }

            //拼团活动封面图
            if (empty($postInfo['PintuanActivity']['cover_picture'])) {
                return ['code'=>1,'message'=> '请上传拼团活动的图片'];
            }

            if (!isset($postInfo['PintuanActivity']['effective_hours']) || !is_numeric($postInfo['PintuanActivity']['effective_hours']) || $postInfo['PintuanActivity']['effective_hours'] <= 0) {
                return ['code'=>1,'message'=> '请认真填写有效时长！'];
            }

            $baseNum = $model->member_num;
            $continue_pintuan = $model->continue_pintuan;
            $status = $model->status;

            $model->load($postInfo);
            if ($model->product_id) {
                // 根据商品id查询出供应商的id
                $productInfo = Product::findOne(['id' => $model->product_id]);
                $model->wholesaler_id = $productInfo ? $productInfo->wholesaler_id : 0;
            }
            $model->update_at = date('Y-m-d H:i:s');

            //获取最后上传的一张图片
            $imgArr = explode(";", $postInfo['PintuanActivity']['cover_picture']);
            $count = count($imgArr);
            if ($count >= 2) {
                $model->cover_picture = $imgArr[$count - 1];
            } else {
                $model->cover_picture = $imgArr[0];
            }

            $model->place_type = $place_type;

            // 开启事物处理
            $transaction = PintuanActivity::getDb()->beginTransaction();
            if ($model->validate(false) && $model->save(false)) {
                 //更新活动商品
                $goodRes = $this->_updateProducts($model->id, $speIds, $pin_prices);
                if (!$goodRes) {
                    $transaction->rollBack();
                    return ['code'=>1,'message'=> '拼团活动商品保存失败！'];
                }

                //拼团自提点
                if ($place_type == 2) {
                      $storeRes = $this->_updateStores($model->id, $storeIdArr);
                      if (!$storeRes) {
                          $transaction->rollBack();
                          return ['code'=>1,'message'=> '拼团自提点保存失败！'];
                      }
                } else {
                    $storeRes = $this->_delStores($model->id);
                    if (!$storeRes) {
                        $transaction->rollBack();
                        return ['code'=>1,'message'=> '拼团自提点操作失败！'];
                    }
                }

                // 判断拼团人数和可继续拼团是否有改变 触发修改定时任务pintuan_task表的数据
                if ($baseNum != $model->member_num) {
                    $taskRes = PintuanTask::updateAll(['pintuan_members' => $model->member_num], ['pintuan_activity_id' => $model->id]);
                    if (!$taskRes) {
                        $transaction->rollBack();
                        return ['code'=>1,'message'=> '拼团活动相关的定时任务表数据更新失败'];
                    }
                }
                if ($continue_pintuan != $model->continue_pintuan) {
                    $taskRes = PintuanTask::updateAll(['continue_pintuan' => $model->continue_pintuan], ['pintuan_activity_id' => $model->id]);
                    if (!$taskRes) {
                        $transaction->rollBack();
                        return ['code'=>1,'message'=> '拼团活动相关的定时任务表数据更新失败'];
                    }
                }
                if ($status != $model->status) {
                    $taskRes = PintuanTask::updateAll(['status' => $model->status], ['pintuan_activity_id' => $model->id]);
                    if (!$taskRes) {
                        $transaction->rollBack();
                        return ['code'=>1,'message'=> '拼团活动相关的定时任务表数据更新失败'];
                    }
                }
                $transaction->commit();
            } else {
                $transaction->rollBack();
                return ['code'=>1,'message'=> '拼团活动更新失败'];
            }
            return ['code'=>0,'message'=> '编辑成功'];
        } else {
            return ['code'=>1,'message'=> '参数错误'];
        }

        return ['code'=>1,'message'=> '编辑成功'];
    }

    /**
     * Updates an existing PintuanActivity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCopy($id)
    {
        //$model = new PintuanActivity();
        $model = $this->findModel($id);
        $postInfo = Yii::$app->request->post();

        if (!empty($postInfo)) {
            // 判断拼团价格
            if (!is_numeric($postInfo['PintuanActivity']['pin_price']) || floatval($postInfo['PintuanActivity']['pin_price']) <= 0) {
                throw new NotFoundHttpException('请填写合法的拼团价', 0000);
            }

            // 判断开始时间和当前时间
            if ($postInfo['PintuanActivity']['start_time'] <= date('Y-m-d H:i')) {
                throw new NotFoundHttpException('拼团活动开始时间必须大于当前时间', 0000);
            }

            // 判断开始时间和结束时间
            if ($postInfo['PintuanActivity']['start_time'] >= $postInfo['PintuanActivity']['end_time']) {
                throw new NotFoundHttpException('拼团活动开始时间不能大于结束时间', 0000);
            }
            $postInfo = $this->numberStrategy($postInfo);
            $model->load($postInfo);
            if ($model->product_id) {
                // 根据商品id查询出供应商的id
                $productInfo = Product::findOne(['id' => $model->product_id]);
                $model->wholesaler_id = $productInfo ? $productInfo->wholesaler_id : 0;
            }
            $model->strategy = $postInfo['strategy'] ? json_encode($postInfo['strategy']) : '';// json化的人数策略数据
            $model->create_at = date('Y-m-d H:i:s');
            $model->update_at = date('Y-m-d H:i:s');
            $model->del = 1;
            $model->isNewRecord = true;

            //拼团价格的处理
            $model->pin_price = intval($postInfo['PintuanActivity']['pin_price'] * 100);

            unset($model->id);
            // 开启事物处理
            $transaction = PintuanActivity::getDb()->beginTransaction();
            if ($model->validate() && $model->save()) {
                // 若是有store_id传过来 则表示自提点变更 需要存储在pintuan_activity_store表中
                if ($postInfo['store_id']) {
                    $storeArr = explode(',', $postInfo['store_id']);
                    foreach ($storeArr as $item) {
                        $pintuanActivityStore = new PintuanActivityStore();
                        $pintuanActivityStore->pintuan_activity_id = $model->id;
                        $pintuanActivityStore->store_id = $item;
                        $pintuanActivityStore->create_at = date('Y-m-d H:i:s');
                        $pintuanActivityStore->del = 1;
                        if (!$pintuanActivityStore->validate() || !$pintuanActivityStore->save()) {
                            $transaction->rollBack();
                            throw new NotFoundHttpException('拼团活动保存失败!');
                        }
                    }
                }
                //新建拼团活动时候需要新增拼团
                $this->createNewPintuan($model);
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        unset($model->id);

        //拼团价格的优化
        $model->pin_price = sprintf("%.2f", $model->pin_price / 100);

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PintuanActivity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEnd($id)
    {
        // 开启事物处理
        $transaction = PintuanActivity::getDb()->beginTransaction();
        $model = $this->findModel($id);
        if ($model->status == 2) {
            throw new NotFoundHttpException('该活动已经结束,不能重复结束', 0000);
        }
        $model->status = 2;
        $model->update_at = date('Y-m-d H:i:s');
        if (!$model->validate(false) || !$model->save(false)) {
            $transaction->rollBack();
            throw new NotFoundHttpException('拼团活动结束失败', 0000);
        }

        //验证定时任务是否存在
        $task_info = PintuanTask::find()->where(['pintuan_activity_id' => $model->id])->one();
        if (!empty($task_info)) {
            // 删除时候触发  修改拼团定时任务表
            $update_at = date('Y-m-d H:i:s', time());
            $res = PintuanTask::updateAll(['status' => 2,'update_at'=>$update_at], ['pintuan_activity_id' => $model->id]);
            if (!$res) {
                $transaction->rollBack();
                throw new NotFoundHttpException('结束关联拼团定时任务表数据失败', 0000);
            }
        }

        $transaction->commit();

        return $this->redirect(['newindex']);
    }

    /**
     * Deletes an existing PintuanActivity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // 开启事物处理
        $transaction = PintuanActivity::getDb()->beginTransaction();
        $delObject = $this->findModel($id);

        if (!$delObject) {
            throw new NotFoundHttpException('该活动不存在！', 0000);
        }

        if ($delObject->del == 2) {
            throw new NotFoundHttpException('该活动已经删除,不能重复删除', 0000);
        }

        $start_time = strtotime($delObject->start_time);
        if ($start_time <= time()) {
            throw new NotFoundHttpException('该活动已开始,不能删除', 0000);
        }

        $delObject->del = 2;
        if (!$delObject->validate() || !$delObject->save()) {
            $transaction->rollBack();
            throw new NotFoundHttpException('拼团活动删除失败', 0000);
        }

        //验证定时任务是否存在
        $task_info = PintuanTask::find()->where(['pintuan_activity_id' => $delObject->id])->one();
        if (!empty($task_info)) {
            // 删除时候触发  修改拼团定时任务表
            $res = PintuanTask::updateAll(['del' => 2], ['pintuan_activity_id' => $delObject->id]);
            if (!$res) {
                $transaction->rollBack();
                throw new NotFoundHttpException('删除关联拼团定时任务表数据失败', 0000);
            }
        }

        $transaction->commit();

        return $this->redirect(['newindex']);
    }

    /**
     * Finds the PintuanActivity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PintuanActivity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PintuanActivity::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.', 0000);
    }

    /*
     * 上传文件保存
     * */
    public function actionImageUpload()
    {
        try {
            $model = new PintuanActivity();
            $imageFile = UploadedFile::getInstance($model, 'cover_picture');
            $parts = explode('.', $imageFile->name);
            $extension = strtolower(end($parts));
            $fileName = md5($imageFile->name) . '.' . $extension;
            $result = Ftp::upload($imageFile->tempName, $fileName, 'pintuan', true);
            $result = json_decode($result, true);
            if ($result['code'] > 0) {
                throw new NotFoundHttpException($result['msg'], $result['code']);
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

    /*
     * 处理人数策略json数据
     * @params $base array
     * @return array
     * @return arrays
     */
    private function numberStrategy($base)
    {
        // 若是没选人数策略
        if (!isset($base['group']) || empty($base['group'])) {
            $base['strategy'] = '';
            return $base;
        }
        if (isset($base['group']) && !empty($base['group']) && !empty($base['strategy'])) {
            foreach ($base['strategy'] as $key => $item) {
                if (!in_array($key, $base['group'])) {
                    unset($base['strategy'][$key]);
                } else {
                    // 判断信息有效性
                    foreach ($item as $kitem => $vitem) {
                        if (!$vitem) {
                            return ['code'=>1,'message'=> '人数策略已经勾选项目必须填写对应值！'];
                            //throw new NotFoundHttpException('人数策略已经勾选项目必须填写对应值', 0000);
                        }
                    }
                }
            }
        }

        if (isset($base['group']) && !empty($base['group'])) {
            // 自动增加人数不能超过拼团人数
            if (isset($base['strategy'])) {
                if (isset($base['strategy']['base_member_num'])) {
                    if (isset($base['strategy']['base_member_num']['member_num']) && $base['strategy']['base_member_num']['member_num'] >= $base['PintuanActivity']['member_num']) {
                        return ['code'=>1,'message'=> '开团后自动增加人数(展示给客户):' . $base['strategy']['base_member_num']['member_num'] . '不能大于等于总参团人数:' . $base['PintuanActivity']['member_num']];
                        //throw new NotFoundHttpException('开团后自动增加人数(展示给客户):' . $base['strategy']['base_member_num']['member_num'] . '不能大于等于总参团人数:' . $base['PintuanActivity']['member_num'], 0000);
                    }
                }

                // 结束前n分钟  每m分钟增加一人 必须符合条件m<=n
                if (isset($base['strategy']['auto_increment'])) {
                    if (isset($base['strategy']['auto_increment']['before_end_min']) && isset($base['strategy']['auto_increment']['increment_cycle_min']) && $base['strategy']['auto_increment']['increment_cycle_min'] >= $base['strategy']['auto_increment']['before_end_min']) {
                        return ['code'=>1,'message'=>'结束前:' . $base['strategy']['auto_increment']['before_end_min'] . '分钟必须大于每:' . $base['strategy']['auto_increment']['increment_cycle_min'] . '分钟增加一人(基于真实参团人数)'];
                        //throw new NotFoundHttpException('结束前:' . $base['strategy']['auto_increment']['before_end_min'] . '分钟必须大于每:' . $base['strategy']['auto_increment']['increment_cycle_min'] . '分钟增加一人(基于真实参团人数)', 0000);
                    }
                }

                // 所有分钟数 均不能大于活动的开始和结束时间差
                $timeDiff = (strtotime($base['PintuanActivity']['end_time']) - strtotime($base['PintuanActivity']['start_time'])) / 60;

                if (isset($base['strategy']['base_member_num'])) {
                    if (isset($base['strategy']['base_member_num']['after_start_min']) && ($base['strategy']['base_member_num']['after_start_min'] >= $timeDiff)) {
                        return ['code'=>1,'message'=> '开团后:' . $base['strategy']['base_member_num']['after_start_min'] . '分钟必须小于活动时间:' . $timeDiff . '分钟'];
                        //throw new NotFoundHttpException('开团后:' . $base['strategy']['base_member_num']['after_start_min'] . '分钟必须小于活动时间:' . $timeDiff . '分钟', 0000);
                    }

                    if (isset($base['strategy']['base_member_num']['after_start_min']) && ($base['strategy']['base_member_num']['after_start_min'] >= $timeDiff)) {
                        return ['code'=>1,'message'=> '开团后:' . $base['strategy']['base_member_num']['after_start_min'] . '分钟必须小于活动时间:' . $timeDiff . '分钟'];
                        //throw new NotFoundHttpException('开团后:' . $base['strategy']['base_member_num']['after_start_min'] . '分钟必须小于活动时间:' . $timeDiff . '分钟', 0000);
                    }
                }

                if (isset($base['strategy']['auto_increment'])) {
                    if (isset($base['strategy']['auto_increment']['before_end_min']) && ($base['strategy']['auto_increment']['before_end_min'] >= $timeDiff)) {
                        return ['code'=>1,'message'=> '系统自动增加人数结束前:' . $base['strategy']['auto_increment']['before_end_min'] . '分钟必须小于活动时间:' . $timeDiff . '分钟'];
                        //throw new NotFoundHttpException('系统自动增加人数结束前:' . $base['strategy']['auto_increment']['before_end_min'] . '分钟必须小于活动时间:' . $timeDiff . '分钟', 0000);
                    }
                }

                if (isset($base['strategy']['fill_before_end'])) {
                    if (isset($base['strategy']['fill_before_end']['before_end_min']) && ($base['strategy']['fill_before_end']['before_end_min'] >= $timeDiff)) {
                        return ['code'=>1,'message'=> '保证成团结束前:' . $base['strategy']['fill_before_end']['before_end_min'] . '分钟必须小于活动时间:' . $timeDiff . '分钟'];
                        //throw new NotFoundHttpException('保证成团结束前:' . $base['strategy']['fill_before_end']['before_end_min'] . '分钟必须小于活动时间:' . $timeDiff . '分钟', 0000);
                    }
                }
            }
        }

        // 判断同一商品是否同时存在于多个有效的活动中
        $timeWhere = [
            'and',
            ['<=', 'start_time', $base['PintuanActivity']['start_time']],
            ['>=', 'end_time', $base['PintuanActivity']['end_time']]
        ];

        $hasIn = PintuanActivity::find()->where(['product_id' => $base['PintuanActivity']['product_id'], 'status' => 1, 'del' => 1])->andWhere($timeWhere)->all();
        if ($hasIn) {
            return ['code'=>1,'message'=> '同一个商品不能同时存在于多个有效的拼团活动当中'];
            //throw new NotFoundHttpException('同一个商品不能同时存在于多个有效的拼团活动当中');
        }
        return $base;
    }

    /*
     * 省市区联动json 选择多个自提点列表
     * */
    public function actionList()
    {
        $province = 0;
        $city = 0;
        $district = 0;
        $product_id = 0;// 商品的id 根据商品id查询出供应商id再查询出供应商覆盖的超市自提点
        if (isset($_GET['p'])) {//省份
            $province = $_GET['p'];
        }
        if (isset($_GET['c'])) {//城市
            $city = $_GET['c'];
        }
        if (isset($_GET['d'])) {//地区
            $district = $_GET['d'];
        }
        if (isset($_GET['product_id'])) {//商品id
            $product_id = $_GET['product_id'];
        }
        $page = 0;
        if (isset($_GET['page']) && $_GET['page']) {//分页
            $page = $_GET['page'] - 1;
        }
        $keyword = "";
        if (isset($_GET['w']) && $_GET['w']) {//关键词
            $keyword = $_GET['w'];
        }
        //查询自提点列表
        $where = array();
        if ($district) {
            $where['district'] = $district;
        } else if ($city) {
            $where['city'] = $city;
        } else if ($province) {
            $where['province'] = $province;
        }
        $query = Store::find()->where($where);
        // 商品的id 根据商品id查询出供应商id再查询出供应商覆盖的超市自提点
        if ($product_id > 0) {
            $productInfo = Product::findOne(['id' => $product_id]);
            $wholesaler_id = $productInfo->wholesaler_id;
            $districtWholesaler = WholesalerDistrict::find()->select('district')->where(['wholesaler_id' => $wholesaler_id])->column();
            $query->andWhere(['district' => $districtWholesaler]);
        }
        if ($keyword) {
            $query->andWhere(['like', 'store_phone', $keyword]);
            $query->orWhere(['like', 'name', $keyword]);
            $query->orWhere(['like', 'owner_user_name', $keyword]);
        }
        $query->orderBy('id desc');
        //总数量
        $count = $query->count();
        //当前分页的数据
        $stores = $query->limit(10)->offset($page * 10)->asArray()->all();
        //城市区域赋值
        $len = count($stores);

        //城市地区编码数组
        $codes = array();
        for ($index = 0; $index < $len; $index++) {
            $store = $stores[$index];
            $codes[] = $store['province'];
            $codes[] = $store['city'];
            $codes[] = $store['district'];
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
            $stores[$index]['province_name'] = $this->getRegionName($regionsMap, $stores[$index]['province']);
            $stores[$index]['city_name'] = $this->getRegionName($regionsMap, $stores[$index]['city']);
            $stores[$index]['district_name'] = $this->getRegionName($regionsMap, $stores[$index]['district']);
            $stores[$index]['status_label'] = Store::getStatusLabel($stores[$index]['status']);
        }

        $pages = $count % 10 == 0 ? (int)($count / 10) : (int)($count / 10 + 1);
        $params = array('provinces' => Region::regions(0, '请选择省份'),
            'cities' => Region::regions($province, '请选择城市', 'city'),
            'districts' => Region::regions($city, '请选择行政区', 'district'),
            'stores' => $stores, 'page' => $page, 'pages' => $pages);
        $this->layout = false;
        return $this->render('list', $params);
    }

    /*
     *
     * */
    public function getRegionName($map, $key)
    {
        if (isset($map[$key])) {
            return $map[$key]['name'];
        }
        return $key;
    }

    /*
     *新增拼团活动
     * @params PintuanActivity $activityModel
     * return bool
     * */
    private function createNewPintuan($activityModel)
    {
        // 插入pintuan数据表
        $pintuan = new Pintuan();
        /**@var  PintuanActivity $activityModel * */
        $pintuan->pintuan_activity_id = $activityModel->id;
        // 发起拼团的用户为随机机器人
        /**@var  PintuanUser $pintuanUser * */
        $pintuanUser = PintuanUser::find()->where(['is_robot' => 2])->orderBy('rand()')->one();
        if (empty($pintuanUser)) {
            return ['code'=>1,'message'=> '发起拼团的随机机器人不存在！'];
        }

        $pintuan->create_user_id = $pintuanUser->id;
        $pintuan->member_num = 1;
        $pintuan->create_at = $activityModel->start_time;
        $pintuan->end_time = $activityModel->end_time;
        $pintuan->status = 1;// 有效团
        $pintuan->del = 1;
        if (!$pintuan->validate() || !$pintuan->save()) {
            return ['code'=>1,'message'=> '根据拼团活动创建拼团失败！'];
        }

        // 同时写入pintuan_product.pintuan_user表中实际参团人的数据
        $productPintuanUser = new ProductPintuanUser();
        $productPintuanUser->pintuan_id = $pintuan->attributes['id'];
        $productPintuanUser->user_id = $pintuan->attributes['create_user_id'];
        $productPintuanUser->nick_name = $pintuanUser->nick_name;
        $productPintuanUser->avatar_url = $pintuanUser->avatar_url;
        $productPintuanUser->created_at = $pintuan->attributes['create_at'];
        if (!$productPintuanUser->validate() || !$productPintuanUser->save()) {
            return ['code'=>1,'message'=> '根据拼团创建拼团人员失败！'];
        }

        // 同时写入拼团定时任务表 pintuan_task
//        "{"base_member_num":{"after_start_min":1,"member_num":9},"auto_increment":{"before_end_min":60,"increment_cycle_min":10},"fill_before_end":{"before_end_min":5}}"
        $strategy = json_decode($activityModel->strategy, true);
        if (!empty($strategy)) {
            $pintaunTask = new PintuanTask();
            $pintaunTask->pintuan_activity_id = $activityModel->id;
            $pintaunTask->pintuan_id = $pintuan->id;
            $pintaunTask->pintuan_members = $activityModel->member_num;
            $pintaunTask->continue_pintuan = $activityModel->continue_pintuan;
            $pintaunTask->base_members = isset($strategy['base_member_num']) ? 1 : 2;
            $pintaunTask->system_autoadd_members = isset($strategy['auto_increment']) ? 1 : 2;
            $pintaunTask->promise_group = isset($strategy['fill_before_end']) ? 1 : 2;
            if (isset($strategy['base_member_num']['after_start_min'])) {
                $pintaunTask->base_members_aftertime = date('Y-m-d H:i', strtotime($pintuan->create_at) + $strategy['base_member_num']['after_start_min'] * 60);
            }

            $pintaunTask->base_members_aftertime_active = isset($strategy['base_member_num']['member_num']) ? $strategy['base_member_num']['member_num'] : 0;

            if (isset($strategy['auto_increment']['before_end_min'])) {
                $pintaunTask->system_autoadd_endtime = date('Y-m-d H:i', strtotime($activityModel->end_time) - $strategy['auto_increment']['before_end_min'] * 60);
            }

            $pintaunTask->system_autoadd_endtime_nums = isset($strategy['auto_increment']['increment_cycle_min']) ? $strategy['auto_increment']['increment_cycle_min'] : 0;

            if (isset($strategy['fill_before_end']['before_end_min'])) {
                $pintaunTask->promise_group_endtime = date('Y-m-d H:i', strtotime($activityModel->end_time) - $strategy['fill_before_end']['before_end_min'] * 60);
            }
            $pintaunTask->pintuan_end_autoadd_time = date("Y-m-d H:i",strtotime($activityModel->end_time) + 60);
            $pintaunTask->pintuan_activity_starttime = $activityModel->start_time;
            $pintaunTask->pintuan_activity_endtime = $activityModel->end_time;
            $pintaunTask->create_at = date('Y-m-d H:i:s');
            $pintaunTask->update_at = date('Y-m-d H:i:s');

            if (!$pintaunTask->validate() || !$pintaunTask->save()) {
                return ['code'=>1,'message'=> '写入拼团定时任务失败！'];
            }
        }
        return true;
    }

    /**
     * 编辑活动商品
     */
    private function _updateProducts($actId, $specIds, $priceArr)
    {
        $model = new \backend\models\PintuanActivitySpecification();
        $res = $model::deleteAll(['pintuan_activity_id'=>$actId]);
        if (!$res) {
            return false;
        }
        return $this->_addProducts($actId, $specIds, $priceArr);
    }

    /**
     * 新增活动商品
     */
    private function _addProducts($actId, $specIds, $priceArr)
    {
        $curDate = date('Y-m-d H:i:s', time());
        $insertData = [];
        foreach ($specIds as $key=>$speId) {
            $insertData[] = [
                'pintuan_activity_id' => intval($actId),
                'specification_id'    => intval($speId),
                'pin_price'            => bcmul($priceArr[$key], 100, 0),
                'create_at'            => $curDate,
                'update_at'            => $curDate,
                'del'=>1
            ];
        }
        $fields = ['pintuan_activity_id','specification_id','pin_price','create_at','update_at','del'];
        $res = Yii::$app->productDb->createCommand()->batchInsert('pintuan_activity_specification',$fields ,$insertData)->execute();
        return $res;
    }

    /**
     * 获取商品规格列表
     */
    public function actionGetspecification()
    {
        $get = yii::$app->request->get();
        $id = isset($get['id'])? intval($get['id']) : 0;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\Specification();
        $data = $model::find()->where(['product_id'=>$id,'del'=>1])->asArray()->all();
        if ($data) {
             foreach ($data as $key=>$val) {
                  //中文不转码
                 $data[$key]['item_detail'] = json_encode(json_decode($val['item_detail'],true),JSON_UNESCAPED_UNICODE);
             }
        }
        return ['code'=>0, 'data'=>$data];
    }

    /**
     * 获取新人专区的价格
     * @return array
     */
    private function _getNewuserPrices($spec_ids)
    {
        $newUserModel = new \backend\models\NewUserActivity();
        $prices = $newUserModel->getNewActProductPrices($spec_ids);
        $res = [];

        if ($prices) {
            foreach ($prices as $key=>$val) {
                $val['price'] = $val['price']/100;
                $res[$val['spec_id']] = $val;
            }
        }
        return $res;
    }

    /**
     * 新版的拼团列表
     */
    public function actionNewindex()
    {
        return $this->render('newindex');
    }

    /**
     * 获取拼团活动列表
     * @return array
     */
    public function actionGetpintuanactivitys()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\PintuanActivity();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $keys = isset($get['key'])? $get['key'] : [];
        $act_date =  isset($keys['act_date'])? $keys['act_date'] : '';
        $productName =  isset($keys['productName'])? $keys['productName'] : '';
        $title =  isset($keys['title'])? $keys['title'] : '';
        $status =  isset($keys['status'])? intval($keys['status']) : 0;

        $where = [
            'pintuan_activity.del'=>1
        ];
        if ($status) {
            $where['pintuan_activity.status'] = $status;
        }

        $andWhere1 = [];
        $title = trim($title);
        if ($title) {
            $andWhere1 = [
                'like', 'pintuan_activity.title', $title
            ];
        }

        $andWhere2 = [];
        if ($act_date) {
            $dateStr = trim($act_date);
            $dateArr = explode('~', $dateStr);
            $start_at = $dateArr[0];
            $end_at = $dateArr[1];
            $andWhere2 = [
                'and',
                ['>=', 'pintuan_activity.create_at', $start_at],
                ['<=', 'pintuan_activity.create_at', $end_at]
            ];
        }

        $andWhere3 = [];
        $productName = trim($productName);
        if ($productName) {
            $andWhere3 = [
                'like', 'product.name', $productName
            ];
        }


        if ($productName) {
            $conut = $model::find()->leftJoin('product','product.id=pintuan_activity.product_id')->where($andWhere1)->andWhere($andWhere2)->andWhere($andWhere3)->andWhere($where)->count();
        } else {
            $conut = $model::find()->where($andWhere1)->andWhere($andWhere2)->andWhere($where)->count();
        }

        $select = 'pintuan_activity.*,product.name,product.wholesaler_id';
        $offset = $limit * ($page - 1);
        $data = $model::find()->select($select)->leftJoin('product','product.id=pintuan_activity.product_id')
                        ->where($andWhere1)->andWhere($andWhere2)->andWhere($andWhere3)->andWhere($where)
                        ->orderBy('pintuan_activity.id desc')
                        ->limit($limit)->offset($offset)->asArray()->all();

        if ($data) {
            $currentTime = time();
            $wholesalerModel = new \backend\models\Wholesaler();
            foreach ($data as $key=>$val) {
                $wholesalerInfo = $wholesalerModel::findOne($val['wholesaler_id']);
                $data[$key]['wholesalerName'] = $wholesalerInfo->name;
                $status_lable = '';
                if ($val['status'] == 2) {
                    $status_lable = '<span style="color: red;">已结束</span>';
                } else {
                    $start_time = strtotime($val['start_time']);
                    $end_time = strtotime($val['end_time']);
                    if ($end_time <= $currentTime) {
                        $status_lable = '<span style="color: red;">已结束</span>';
                    } else {
                        if ($start_time > $currentTime) {
                            $status_lable = '<span style="color: #CC9999;">未开始</span>';
                        } else {
                            $status_lable = '<span style="color: #0066CC;">进行中</span>';
                        }
                    }
                }
                $data[$key]['status_lable'] = $status_lable;
            }
        }
        $res = ['code'=>0,'count'=>$conut,'data'=>$data];
        return $res;
    }

    /**
     * 删除拼团
     * @return array
     */
    public function actionDelpintuan()
    {
        $post = yii::$app->request->post();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = isset($post['id'])? intval($post['id']) : 0;

        if (empty($id)) {
            return ['code'=>1,'message'=> '参数错误'];
        }

        $model = new \backend\models\PintuanActivity();
        $delObject = $model::findOne($id);

        if (!$delObject) {
            return ['code'=>1,'message'=> '参数错误'];
        }

        //已结束的，不能删除
        if ($delObject->status == 2) {
            return ['code'=>1,'message'=> '该活动已结束，不能删除！'];
        }

        $curTime = time();
        $start_time = strtotime($delObject->start_time);
        $end_time = strtotime($delObject->end_time);

        //已结束的，不能删除
        if ($curTime >= $end_time) {
            return ['code'=>1,'message'=> '该活动已结束，不能删除！'];
        }

        //拼团中的活动，不能删除
        if ($start_time <= $curTime && $end_time > $curTime) {
            return ['code'=>1,'message'=> '该活动已进行中，不能删除！'];
        }

        if ($delObject->del == 2) {
            return ['code'=>1,'message'=> '该活动已经删除,不能重复删除'];
        }

        $delObject->del = 2;
        if (!$delObject->validate() || !$delObject->save()) {
            return ['code'=>1,'message'=> '拼团活动删除失败'];
        }

        //验证定时任务是否存在
        $task_info = PintuanTask::find()->where(['pintuan_activity_id' => $delObject->id])->one();
        if (!empty($task_info)) {
            // 删除时候触发  修改拼团定时任务表
            $res = PintuanTask::updateAll(['del' => 2], ['pintuan_activity_id' => $delObject->id]);
            if (!$res) {
                return ['code'=>1,'message'=> '删除关联拼团定时任务表数据失败'];
            }
        }

        return ['code'=>0,'message'=> '删除成功'];
    }

    /**
     * 新增自提点
     */
    private function _addStores($actId, $storeIdArr)
    {
        $curDate = date('Y-m-d H:i:s', time());
        $insertData = [];
        foreach ($storeIdArr as $key=>$store_id) {
            $insertData[] = [
                'pintuan_activity_store'=> $actId,
                'store_id'  => $store_id,
                'create_at' =>$curDate,
                'del'=>1
            ];
        }
        $res = Yii::$app->productDb->createCommand()->batchInsert('pintuan_activity_store', ['pintuan_activity_id','store_id','create_at','del'],$insertData)->execute();
        return $res;
    }

    /**
     * 编辑自提点
     */
    private function _updateStores($actId, $storeIdArr)
    {
        $storeModel = new \backend\models\PintuanActivityStore();
        $info = $storeModel::find()->where(['pintuan_activity_id'=>$actId])->asArray()->one();
        $res = true;
        if (!empty($info)) {
            $res = $storeModel::deleteAll(['pintuan_activity_id'=>$actId]);
        }
        if (!$res) {
            return false;
        }
        return $this->_addStores($actId, $storeIdArr);
    }

    /**
     * 删除自提点
     */
    private function _delStores($actId)
    {
        $storeModel = new \backend\models\PintuanActivityStore();
        $info = $storeModel::find()->where(['pintuan_activity_id'=>$actId])->asArray()->one();
        if (!empty($info)) {
            return  $storeModel::deleteAll(['pintuan_activity_id'=>$actId]);
        }
        return true;
    }

}
