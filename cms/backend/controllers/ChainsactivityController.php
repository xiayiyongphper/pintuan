<?php

namespace backend\controllers;

use backend\models\BuyChains;
use backend\models\NewUserActivity;
use backend\models\Store;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\tools\Common;
use common\tools\Ftp;
use yii\web\UploadedFile;

/**
 * 接龙活动
 */
class ChainsactivityController extends Controller
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
     * 接龙活动列表
     * @return string
     */
    public function actionIndex()
    {
        $data = [];
        return $this->render('index',$data);
    }

    /**
     * 创建接龙活动
     * @return string
     */
    public function actionCreate()
    {
        $data = [];
        return $this->render('create',$data);
    }

    /**
     * 编辑接龙活动
     * @return string
     */
    public function actionUpdate($id)
    {
        $id = intval($id);
        $model = new \backend\models\BuyChains();
        $productModel = new \backend\models\Product();
        $storeModel = new \backend\models\BuyChainsStore();

        $info = $model::findOne($id);

        if (empty($info)) {
            echo '参数错误！';
            exit;
        }

        //获取商品信息
        $buySpecificationModel = new \backend\models\BuyChainsSpecification();
        $select = 'buy_chains_specification.specification_id,buy_chains_specification.fake_sold_base,buy_chains_specification.limit_buy_num,buy_chains_specification.qty,buy_chains_specification.activity_price,specification.item_detail';
        $chainsSpecification = $buySpecificationModel::find()->select($select)
                                                     ->leftJoin('specification', 'specification.id=buy_chains_specification.specification_id')
                                                     ->where(['buy_chains_specification.buy_chains_id'=>$id,'buy_chains_specification.del'=>1])->asArray()->one();

        $good = $productModel::findOne($info->product_id);
        $chainsSpecification['name'] = $good->name;
        unset($good);
        $goodList = [];
        $goodList[] = $chainsSpecification;
        unset($chainsSpecification);

        $storeList = [];
        if ($info->place_type == 2) {
            $storeList = $storeModel->getStoreList($id);
        }

        $data = [
            'info'=>$info,
            'goodList'=>$goodList,
            'storeList'=>$storeList,
        ];

        return $this->render('update',$data);
    }

    /**
     * 新增编辑接龙活动
     * @return array
     */
    public function actionAdd()
    {
        yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = yii::$app->request->post();

        $model = new BuyChains();

        $id = isset($post['id'])?  intval($post['id']): 0;
        $title = isset($post['title'])? $post['title'] : '';
        $weight = isset($post['weight'])? intval($post['weight']) : -1;
        $image = isset($post['image'])? $post['image'] : '';

        //活动名称
        if (empty($title)) {
            return ['code'=>1,'message'=>'请填写活动名称'];
        }
        $title = trim($title);

        //权重
        if (!is_numeric($weight) || empty($weight) || $weight<=0) {
            return ['code'=>1,'message'=>'请正确填写权重'];
        }

        //活动时间
        $curTime = time();
        if (!isset($post['date']) || empty($post['date'])) {
            return ['code'=>1,'message'=>'请选择起止时间'];
        }
        $dateStr = trim($post['date']);
        $dateArr = explode('~', $dateStr);

        if (empty($dateArr) || count($dateArr) != 2) {
            return ['code'=>1,'message'=>'请正确选择起止时间'];
        }

        $start_time = trim($dateArr[0]);
        $end_time   = trim($dateArr[1]);

        if (strtotime($start_time) === false || strtotime($start_time) <= 0) {
            return ['code'=>1,'message'=>'请正确选择起止时间'];
        }

        if (strtotime($end_time) === false || strtotime($end_time) <= 0) {
            return ['code'=>1,'message'=>'请正确选择起止时间'];
        }

        if (strtotime($start_time) >= strtotime($end_time)) {
            return ['code'=>1,'message'=>'结束时间必须大于起止时间'];
        }

        //自提点选择
        if (!isset($post['place_type']) || !is_numeric($post['place_type']) || !in_array($post['place_type'], [1,2])) {
            return ['code'=>1,'message'=>'请选择自提点'];
        }
        $place_type = intval($post['place_type']);

        //活动状态
        if (!isset($post['status']) || !is_numeric($post['status']) || !in_array($post['status'], [1,2])) {
            return ['code'=>1,'message'=>'请选择活动状态'];
        }
        $status = intval($post['status']);

        //活动商品、活动自提点
        $specification_ids = isset($post['specification_id'])? $post['specification_id'] : [];
        $activity_prices = isset($post['activity_price'])? $post['activity_price'] : [];
        $qtys = isset($post['qty'])? $post['qty'] : [];
        $storeIdArr = isset($post['storeids'])? $post['storeids'] : [];
        $fakeSalesArr = isset($post['fake_sold_base'])? $post['fake_sold_base'] : [];
        $limitArr = isset($post['limit_buy_num'])? $post['limit_buy_num'] : [];

        if (empty($specification_ids) || empty($activity_prices) || empty($qtys) || empty($fakeSalesArr) || empty($limitArr)) {
            return ['code'=>1,'message'=>'请添加接龙商品规格!'];
        }

        if (count($specification_ids) != count($activity_prices)) {
            return ['code'=>1,'message'=>'请添加接龙商品规格!'];
        }

        if (count($activity_prices) != count($qtys)) {
            return ['code'=>1,'message'=>'请添加接龙商品规格!'];
        }

        if (count($qtys) != count($fakeSalesArr)) {
            return ['code'=>1,'message'=>'请添加接龙商品规格!'];
        }

        if (count($fakeSalesArr) != count($limitArr)) {
            return ['code'=>1,'message'=>'请添加接龙商品规格!'];
        }

        if ($place_type == 2) {
            if (empty($storeIdArr)) {
                return ['code'=>1,'message'=>'请添加活动自提点!'];
            }
        }

        //当前时间
        $curDate = date('Y-m-d H:i:s', time());

        //活动商品的验证
        $goods = [];
        $newProductModel = new \backend\models\NewActProduct();
        $specificationModel = new \backend\models\Specification();
        $buyChainsSpecificationModel = new \backend\models\BuyChainsSpecification();
        $specificationInfo = null;

        foreach ($specification_ids as $key=>$specification_id) {
            $specification_id = intval($specification_id);
            $specificationInfo = $specificationModel::findOne($specification_id);
            if (!$specificationInfo) {
                return ['code'=>1,'message'=>'商品异常，规格id为：'. $specification_id];
            }

            $price = $activity_prices[$key];
            $qty   = $qtys[$key];
            $fake_sold_base = $fakeSalesArr[$key];
            $limit_buy_num   = $limitArr[$key];

            if (!is_numeric($price) || $price<=0) {
                return ['code'=>1,'message'=>'商品价格填写非法，请认真检查！'];
            }

            if ($price < 0.01) {
                return ['code'=>1,'message'=>'商品价格必须不能小于1分钱！'];
            }

            if (!is_numeric($qty) || $qty<=0) {
                return ['code'=>1,'message'=>'商品库存填写非法，请认真检查！'];
            }

            if (!is_numeric($fake_sold_base) || $fake_sold_base<0) {
                return ['code'=>1,'message'=>'假销售数量非法，请认真填写！'];
            }

            if (!is_numeric($limit_buy_num) || $limit_buy_num<0) {
                return ['code'=>1,'message'=>'限购的数量填写非法，请认真填写！'];
            }

            $price = bcmul($price, 100, 0);
            //和新人活动比较价格
            $newProductWhere = [
                'new_act_product.spec_id'=>$specification_id,
                'new_act_product.del'=>1,
                'new_user_activity.status'=>1,
                'new_user_activity.del'=>1,
            ];
            $andWhere = [
                'and',
                ['>=', 'new_act_product.price', $price],
                ['<=', 'new_user_activity.start_at', $curDate],
                ['>', 'new_user_activity.end_at', $curDate],
            ];
            $newUserSelect = 'new_act_product.price';
            $newProductInfo = $newProductModel::find()->select($newUserSelect)->leftJoin('new_user_activity','new_user_activity.id=new_act_product.act_id')
                ->where($newProductWhere)->andWhere($andWhere)->asArray()->one();
            if ($newProductInfo) {
                return ['code'=>1,'message'=>'价格不能低于新人价(' . $newProductInfo['price']/100 . '元)，规格id为：'. $specification_id];
            }

            $goods[] = [
                'specification_id'=>$specification_id,
                'activity_price'=>$price,
                'qty'=> bcadd($qty, '0'),
                'fake_sold_base'=>$fake_sold_base,
                'limit_buy_num'=>$limit_buy_num,
            ];
        }
        $newChainsSpecifications = $goods[0];

        //请上传图片
        if (empty($image)) {
            return ['code'=>1,'message'=>'请上传封面图片！'];
        }

        //获取商品信息
        $productModel = new \backend\models\Product();
        $productInfo = $productModel::findOne($specificationInfo->product_id);

        //编辑限制
        $limit_edit = false;
        if ($id) {
            $model = BuyChains::findOne($id);
            if (!$model) {
                return ['code'=>1,'message'=>'要编辑的记录不存在！'];
            }

            if ($model->status == 2) {
                return ['code'=>1,'message'=>'已结束的活动只能查看或者删除，不能编辑！'];
            } else {
                $start  = strtotime($model->start_time);
                $end_at = strtotime($model->end_time);

                if ($curTime >= $end_at) {
                    return ['code'=>1,'message'=>'已结束的活动只能查看或者删除，不能编辑！'];
                }

                //进行中的活动，编辑限制
                if ($start <= $curTime && $end_at > $curTime) {
                    $limit_edit = true;
                }
            }
        }

        //验证时间
        if ($limit_edit === false) {
            if (strtotime($start_time) <= $curTime) {
                return ['code'=>1,'message'=>'活动开始时间必须大于当前时间！'];
            }
        }

        $insertFlag = true;
        $oldId = 0;

        if ($id) {
            $insertFlag = false;
            $msg = '编辑';
            $model = BuyChains::findOne($id);

            $oldWhere = [
                'buy_chains_id'=>$id,
                'del'=>1
            ];

            $oldChainsSpecifications = $buyChainsSpecificationModel::find()->where($oldWhere)->asArray()->one();
            $oldId = $oldChainsSpecifications['id'];
            if ($limit_edit === true) {
                if ($oldChainsSpecifications['qty'] >= $newChainsSpecifications['qty']) {
                    return ['code'=>1,'message'=>'进行中的活动，更新的库存必须大于之前的库存！'];
                }
                unset($newChainsSpecifications['activity_price'],$newChainsSpecifications['fake_sold_base']);
                unset($newChainsSpecifications['limit_buy_num'],$newChainsSpecifications['specification_id']);
            }

        } else {
            $limit_edit = false;
            $msg = '新增';
            $model->create_at = $curDate;
        }

        //进行中
        if ($limit_edit == true) {
            $model->status = $status;
            $model->update_at = $curDate;
        } else {
            $model->title = $title;
            $model->weight = $weight;
            $model->product_id = $productInfo->id;
            $model->wholesaler_id = $productInfo->wholesaler_id;
            $model->start_time = $start_time;
            $model->end_time = $end_time;
            $model->image = $image;
            $model->place_type = $place_type;
            $model->status = $status;
            $model->update_at = $curDate;
            $model->del = 1;
        }

        $result = $model->save();
        $id = $model->id;

        if ($result !== false) {
            if ($insertFlag == false) {
                $res = $this->_updateProducts($oldId, $newChainsSpecifications);
                if (!$res) {
                    return ['code'=>1,'message'=> $msg . '成功，但'.$msg.'商品规格失败！'];
                }

                if ($limit_edit == false) {
                    if ($place_type == 2) {
                        $res = $this->_updateStores($id, $storeIdArr);
                        if (!$res) {
                            return ['code'=>1,'message'=> $msg . '成功，但'.$msg.'自提点失败！'];
                        }
                    } else {
                        $res = $this->_delStores($id);
                        if (!$res) {
                            return ['code'=>1,'message'=> $msg . '成功，但'.$msg.'自提点失败！'];
                        }
                    }
                }
            } else {
                $res = $this->_addProducts($id, $newChainsSpecifications);
                if (!$res) {
                    return ['code'=>1,'message'=> $msg . '成功，但'.$msg.'商品失败！'];
                }

                if ($place_type == 2) {
                    $res = $this->_addStores($id, $storeIdArr);
                    if (!$res) {
                        return ['code'=>1,'message'=> $msg . '成功，但'.$msg.'自提点失败！'];
                    }
                }
            }
            return ['code'=>0,'message'=> $msg . '成功'];
        }

        return ['code'=>1,'message'=>'网络繁忙，请稍后再尝试'];
    }

    /**
     * 获取接龙活动列表
     * @return array
     */
    public function actionGetactivitys()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\BuyChains();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $keys = isset($get['key'])? $get['key'] : [];
        $act_date =  isset($keys['act_date'])? $keys['act_date'] : '';
        $good_name =  isset($keys['good_name'])? $keys['good_name'] : '';
        $title =  isset($keys['title'])? $keys['title'] : '';

        $where = ['buy_chains.del'=>1];

        $andWhere1 = [];
        $title = trim($title);
        if ($title) {
            $andWhere1 = [
                'like', 'buy_chains.title', $title
            ];
        }

        $andWhere2 = [];
        $good_name = trim($good_name);
        if ($good_name) {
            $andWhere2 = [
                'like', 'product.name', $good_name
            ];
        }

        $andWhere3 = [];
        if ($act_date) {
            $dateStr = trim($act_date);
            $dateArr = explode('~', $dateStr);
            $start_at = $dateArr[0];
            $end_at = $dateArr[1];
            $andWhere3 = [
                'and',
                ['>=', 'buy_chains.start_at', $start_at],
                ['<=', 'buy_chains.end_at', $end_at]
            ];
        }

        if ($good_name) {
            $conut = $model::find()->leftJoin('product', 'product.id=buy_chains.product_id')
                                   ->where($andWhere3)->andWhere($andWhere1)->andWhere($andWhere2)->andWhere($where)->count();
        } else {
            $conut = $model::find()->where($andWhere3)->andWhere($andWhere1)->andWhere($where)->count();
        }

        $select = 'buy_chains.*,product.name,buy_chains_specification.specification_id,buy_chains_specification.activity_price';

        $offset = $limit * ($page - 1);
        $data = $model::find()->select($select)
                       ->leftJoin('buy_chains_specification', 'buy_chains_specification.buy_chains_id=buy_chains.id')
                       ->leftJoin('product', 'product.id=buy_chains.product_id')
                       ->where($andWhere3)->andWhere($andWhere1)->andWhere($andWhere2)->andWhere($where)
                       ->orderBy('buy_chains.id desc')
                       ->limit($limit)->offset($offset)->asArray()->all();

        if ($data) {
            $specificationModel = new \backend\models\Specification();
            $curTime = time();
            foreach ($data as $key=>$val) {
                if  ($val['status'] == 2) {
                    $operate_status = '<span style="color: red;">已结束</span>';
                } else {
                    //是否已结束
                    $start_time = strtotime($val['start_time']);
                    $end_time = strtotime($val['end_time']);
                    if ($end_time <= $curTime) {
                        $operate_status = '<span style="color: red;">已结束</span>';
                    } else {
                        if ($start_time > $curTime) {
                            $operate_status = '<span style="color: #CC9999;">未开始</span>';
                        } else {
                            $operate_status = '<span style="color: #0066CC;">进行中</span>';
                        }
                    }
                }

                $data[$key]['operate_label'] = $operate_status;
                //接龙价格
                $data[$key]['activity_price'] = sprintf("%.2f", $val['activity_price']/100);

                //获取规格信息
                $specificationInfo = $specificationModel::findOne($val['specification_id']);
                if ($specificationInfo) {
                    $data[$key]['item_detail'] = $specificationInfo->item_detail;
                } else {
                    $data[$key]['item_detail'] = '';
                }
            }
        }
        $res = ['code'=>0,'count'=>$conut,'data'=>$data];
        return $res;
    }

    /**
     * 删除
     * @return array
     */
    public function actionDel()
    {
        $post = yii::$app->request->post();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\BuyChains();

        $id = isset($post['id'])? intval($post['id']) : 0;

        if (empty($id)) {
            return ['code'=>1,'message'=> '参数错误'];
        }

        $info = $model::findOne($id);
        if (!$info) {
            return ['code'=>1,'message'=> '参数错误'];
        }

        //进行中的活动：不能删除
        $curTime = time();
        $start_time  = strtotime($info['start_time']);
        $end_at_time = strtotime($info['end_time']);

        if ($info['status'] == 1) {
            if ($start_time<= $curTime && $end_at_time >= $curTime) {
                return ['code'=>1,'message'=>'进行中的活动不能删除！'];
            }
        }

        $info->del = 2;
        if (!$info->save()) {
            return ['code'=>1,'message'=> '网络繁忙，请稍后再尝试'];
        }

        return ['code'=>0,'message'=> '删除成功'];
    }

    /**
     * 结束和开启
     * @return array
     */
    public function actionSetstatus()
    {
        $post = yii::$app->request->post();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\BuyChains();

        $id = isset($post['id'])? intval($post['id']) : 0;
        $status = isset($post['status'])? intval($post['status']) : 0;

        if (empty($id)) {
            return ['code'=>1,'message'=> '参数错误'];
        }

        if (empty($status) || !in_array($status, [1,2])) {
            return ['code'=>1,'message'=> '参数错误'];
        }

        $info = $model::findOne($id);
        if (!$info) {
            return ['code'=>1,'message'=> '参数错误'];
        }

        if ($info->status == $status) {
            return ['code'=>1,'message'=> '不能重复操作！'];
        }

        $curDate = date('Y-m-d H:i:s', time());
        $data = ['status'=>$status,'update_at'=>$curDate];

        $res = $model::updateAll($data, ['id'=>$id]);
        if ($res == 0) {
            return ['code'=>1,'message'=> '网络繁忙，请稍后再尝试'];
        }

        return ['code'=>0,'message'=> '操作成功'];
    }

    /**
     * 编辑活动商品
     */
    private function _updateProducts($oldId, $newSpecification)
    {
        $curDate = date('Y-m-d H:i:s', time());
        $productModel = new \backend\models\BuyChainsSpecification();
        $where = [
            'id'=>$oldId
        ];
        $newSpecification['update_at'] = $curDate;
        $newSpecification['del'] = 1;
        $res = $productModel::updateAll($newSpecification, $where);
        if (!$res) {
            return false;
        }
        return true;
    }

    /**
     * 新增活动的商品
     */
    private function _addProducts($buy_chains_id, $newSpecification)
    {
        $curDate = date('Y-m-d H:i:s', time());
        $insertData[] = [
            'buy_chains_id'    => $buy_chains_id,
            'specification_id' => intval($newSpecification['specification_id']),
            'activity_price'   => $newSpecification['activity_price'],
            'qty'               => $newSpecification['qty'],
             'sold_num'=>0,
            'fake_sold_base'=>$newSpecification['fake_sold_base'],
            'limit_buy_num'=>$newSpecification['limit_buy_num'],
            'create_at'=>$curDate,
            'update_at'=>$curDate,
            'del'=>1
        ];

        $fields = ['buy_chains_id','specification_id','activity_price','qty','sold_num','fake_sold_base','limit_buy_num','create_at','update_at','del'];
        $res = Yii::$app->productDb->createCommand()->batchInsert('buy_chains_specification', $fields, $insertData)->execute();
        return $res;
    }

    /**
     * 新增自提点
     */
    private function _addStores($buy_chains_id, $storeIdArr)
    {
        $curDate = date('Y-m-d H:i:s', time());
        $insertData = [];
        foreach ($storeIdArr as $key=>$store_id) {
            $insertData[] = [
                'act_id'    => $buy_chains_id,
                'store_id'  => $store_id,
                'create_at'  => $curDate,
                'del'=>1
            ];
        }
        $res = Yii::$app->productDb->createCommand()->batchInsert('buy_chains_store', ['buy_chains_id','store_id','create_at','del'],$insertData)->execute();
        return $res;
    }

    /**
     * 编辑自提点
     */
    private function _updateStores($buy_chains_id, $storeIdArr)
    {
        $storeModel = new \backend\models\BuyChainsStore();
        $info = $storeModel::find()->where(['buy_chains_id'=>$buy_chains_id])->asArray()->one();
        $res = true;
        if (!empty($info)) {
            $res = $storeModel::deleteAll(['buy_chains_id'=>$buy_chains_id]);
        }
        if (!$res) {
            return false;
        }
        return $this->_addStores($buy_chains_id, $storeIdArr);
    }

    /**
     * 删除自提点
     */
    private function _delStores($buy_chains_id)
    {
        $storeModel = new \backend\models\BuyChainsStore();
        $info = $storeModel::find()->where(['buy_chains_id'=>$buy_chains_id])->asArray()->one();
        if (!empty($info)) {
            return  $storeModel::deleteAll(['buy_chains_id'=>$buy_chains_id]);
        }
        return true;
    }

    /**
     * 上传图片
     * @return array
     */
    public function actionUpload()
    {
        yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            $files = $_FILES['file'];
            $imgName = explode('.', $files['name']);
            $extension = $imgName[1];

            //后缀名验证
            if (!in_array($extension, ['png','jpg'])) {
                return [
                    'code'=>1,
                    'msg'=> '只支持png和jpg格式！',
                    'data'=>['src'=>'']
                ];
            }

            $fileName = md5($files['name']) . '.' . $extension;
            $result = Ftp::upload($files['tmp_name'], $fileName, 'chainsactivity');
            $result = json_decode($result, true);
            if ($result['code'] > 0) {
                return [
                    'code'=>1,
                    'msg'=> '上传失败！请重新上传',
                    'data'=>['src'=>'']
                ];
            }
            return [
                'code'=>0,
                'msg'=> '上传成功！',
                'data'=>['src'=>$result['url']]
            ];
        } catch (\Exception $e) {
            return [
                'code'=>1,
                'msg'=> '上传失败！请重新上传',
                'data'=>['src'=>'']
            ];
        }
    }
}
