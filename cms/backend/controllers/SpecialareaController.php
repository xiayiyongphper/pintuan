<?php

namespace backend\controllers;

use backend\models\NewUserActivity;
use backend\models\Store;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\tools\Common;

/**
 * 新人专区---控制器
 */
class SpecialareaController extends Controller
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
     * 新人专区列表
     * @return string
     */
    public function actionIndex()
    {
        $data = [];
        return $this->render('index',$data);
    }

    /**
     * 创建新人专区
     * @return string
     */
    public function actionCreate()
    {
        $data = [];
        //获取省份
        $province = $this->getRegionData(1);
        $data['province'] = $province;
        return $this->render('create',$data);
    }

    /**
     * 编辑新人专区
     * @return string
     */
    public function actionUpdate($id)
    {
        $id = intval($id);
        $model = new \backend\models\NewUserActivity();
        $productModel = new \backend\models\NewActProduct();
        $storeModel = new \backend\models\NewActStore();

        $info = $model::findOne($id);
        $goodList = $productModel->getGoodList($id);

        $storeList = [];
        if ($info->place_type == 2) {
            $storeList = $storeModel->getStoreList($id);
        }

        $regionModel = new \backend\models\Region();
        //获取活动省份、省份列表
        $provinceInfo = $regionModel::find()->where(['code'=>$info->province])->asArray()->one();
        $provinceList = $this->getRegionData(1);
        $data['provinceList'] = $provinceList;

        //获取活动城市、城市列表
        $cityInfo = $regionModel::find()->where(['code'=>$info->city])->asArray()->one();
        $cityList = $this->getRegionData(2, $provinceInfo['id']);
        $data['cityList'] = $cityList;

        $data = [
            'info'=>$info,
            'goodList'=>$goodList,
            'storeList'=>$storeList,
            'provinceList'=>$provinceList,
            'cityList'=>$cityList,
            'provinceCode'=>$provinceInfo['code'],
            'cityCode'=>$cityInfo['code'],
        ];

        return $this->render('update',$data);
    }

    /**
     * 新增编辑新人活动
     * @return array
     */
    public function actionAdd()
    {
        yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = yii::$app->request->post();

        $model = new \backend\models\Salesrule();
        $prodModel = new \backend\models\SalesruleProduct();

        $id = isset($post['id'])?  intval($post['id']): 0;
        $remark = isset($post['remark'])? $post['remark'] : '';
        $prodids = isset($post['prodids'])? $post['prodids'] : [];

        //活动名称
        if (!isset($post['act_name']) || empty($post['act_name'])) {
            return ['code'=>1,'message'=>'请输入活动名称'];
        }
        $act_name = trim($post['act_name']);

        //活动名称
        if (!isset($post['date']) || empty($post['date'])) {
            return ['code'=>1,'message'=>'请选择起止时间'];
        }
        $dateStr = trim($post['date']);
        $dateArr = explode('~', $dateStr);

        if (empty($dateArr) || count($dateArr) != 2) {
            return ['code'=>1,'message'=>'请正确选择起止时间'];
        }

        $start_at = trim($dateArr[0]);
        $end_at   = trim($dateArr[1]);

        if (strtotime($start_at) === false) {
            return ['code'=>1,'message'=>'请正确选择起止时间'];
        }

        if (strtotime($end_at) === false) {
            return ['code'=>1,'message'=>'请正确选择起止时间'];
        }

        //省份
        if (!isset($post['province']) || empty($post['province'])) {
            return ['code'=>1,'message'=>'请选择省份'];
        }
        $province = intval($post['province']);

        //城市
        if (!isset($post['city']) || empty($post['city'])) {
            return ['code'=>1,'message'=>'请选择省份'];
        }
        $city = intval($post['city']);

        //自提点选择
        if (!isset($post['place_type']) || !is_numeric($post['place_type']) || !in_array($post['place_type'], [1,2])) {
            return ['code'=>1,'message'=>'请选择自提点类型'];
        }
        $place_type = intval($post['place_type']);

        //活动状态
        if (!isset($post['status']) || !is_numeric($post['status']) || !in_array($post['status'], [1,2])) {
            return ['code'=>1,'message'=>'请选择活动状态'];
        }
        $status = intval($post['status']);

        //活动商品、活动自提点
        $prodIdArr = isset($post['prodids'])? $post['prodids'] : [];
        $priceArr = isset($post['prices'])? $post['prices'] : [];
        $speIdArr = isset($post['speids'])? $post['speids'] : [];
        $storeIdArr = isset($post['storeids'])? $post['storeids'] : [];
        $wholesaler_ids = isset($post['wholesaler_ids'])? $post['wholesaler_ids'] : [];

        if (empty($prodIdArr) || empty($wholesaler_ids) || empty($speIdArr) || empty($priceArr)) {
            return ['code'=>1,'message'=>'请添加活动商品!'];
        }

        if (count($prodIdArr) != count($speIdArr)) {
            return ['code'=>1,'message'=>'请正确添加活动商品!'];
        }

        if (count($speIdArr) != count($priceArr)) {
            return ['code'=>1,'message'=>'请正确添加活动商品!'];
        }

        if (count($priceArr) != count($wholesaler_ids)) {
            return ['code'=>1,'message'=>'请正确添加活动商品!'];
        }

        if ($place_type == 2) {
            if (empty($storeIdArr)) {
                return ['code'=>1,'message'=>'请添加活动自提点!'];
            }
        }

        $model = new \backend\models\NewUserActivity();

        $where = [
            'city'=>$city,
            'del'=>1
        ];
        $where1 = [
            '>', 'end_at', $start_at
        ];

        $infoList = $model::find()->where($where1)->andWhere($where)->asArray()->all();

        if (!empty($infoList)) {
             $len = count($infoList);
             if ( $len >= 2 ) {
                 return ['code'=>1,'message'=>'该城市'.$dateStr.'这段时间内已有活动存在!'];
             } else {
                 $info = $infoList[0];
                 if (!empty($info) && $info['id'] != $id) {
                     return ['code'=>1,'message'=>'该城市'.$dateStr.'这段时间内已有活动存在!'];
                 }
             }
        }


        //活动商品的验证
        $productModel = new \backend\models\Product();
        foreach ($prodIdArr as $prid) {
            $productInfo = $productModel::findOne($prid);
            if (!$productInfo) {
                return ['code'=>1,'message'=>'活动商品异常，商品id为：'. $prid];
            }
        }

        //活动商品规格的验证
        $speModel = new \backend\models\Specification();
        foreach ($speIdArr as $speid) {
            $speInfo = $speModel::findOne($speid);
            if (!$speInfo) {
                return ['code'=>1,'message'=>'活动商品规格异常，商品规格id为：'. $speid];
            }
        }

        //活动商品价格的验证
        $pintuanPrices = $this->_getPintuanPrices($speIdArr);

        foreach ($priceArr as $index=>$price) {
            if (!is_numeric($price) || $price <=0) {
                return ['code'=>1,'message'=>'活动商品价格异常，请认真检查！'];
            }

            if ($pintuanPrices) {
                 if (isset($pintuanPrices[$speIdArr[$index]])) {
                         $ptPrice = $pintuanPrices[$speIdArr[$index]];
                         if ($ptPrice['pin_price'] <= $price) {
                             $msg = '拼团活动id' . $ptPrice['pintuan_activity_id'] . ',规格id：' . $ptPrice['specification_id'];
                             return ['code'=>1,'message'=>'拼团的价格必须大于新人的价格！' . $msg];
                         }
                 }
            }
        }

        if ($id) {
            $info = $model::findOne($id);
            if (!$info) {
                return ['code'=>1,'message'=>'要编辑的记录不存在！'];
            }

            $curTime = time();
            $start_time  = strtotime($info['start_at']);
            $end_at_time = strtotime($info['end_at']);

            if ($info['status'] == 1) {
                if ($curTime >= $end_at_time) {
                    return ['code'=>1,'message'=>'已结束的活动只能查看或者删除，不能编辑！'];
                }

                if ($start_time<= $curTime && $end_at_time >= $curTime) {
                     return ['code'=>1,'message'=>'进行中的活动只能查看，不能编辑！'];
                }
            }
        }

        $curDate = date('Y-m-d H:i:s', time());
        $insertFlag = true;
        if ($id) {
            $insertFlag = false;
            $msg = '编辑';
            $data = [
                'act_name'=>$act_name,
                'start_at'=>$start_at,
                'end_at'=>$end_at,
                'province'=>$province,
                'city'=>$city,
                'place_type'=>$place_type,
                'status'=>$status,
                'created_at'=>$curDate,
                'updated_at'=>$curDate,
            ];
            $result = $model::updateAll($data, ['id'=>$id]);
        } else {
            $model->act_name = $act_name;
            $model->start_at = $start_at;
            $model->end_at = $end_at;
            $model->province = $province;
            $model->city = $city;
            $model->status = $status;
            $model->place_type = $place_type;
            $model->created_at = $curDate;
            $model->updated_at = $curDate;
            $model->operate_status = 1;
            $model->order_num = 0;
            $model->browse_num = 0;
            $model->act_code = '';
            $result = $model->save();
            $id = $model->id;
            $msg = '新增';
        }

        if ($result !== false) {
            if ($insertFlag == false) {
                //编辑商品
                $res = $this->_updateProducts($id, $prodIdArr, $speIdArr, $priceArr, $wholesaler_ids);
                if (!$res) {
                    return ['code'=>1,'message'=> $msg . '成功，但'.$msg.'商品失败！'];
                }

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

            } else {
                //回写活动编码
                $act_code = $this->_createActCode($id);
                $model->act_code = $act_code;
                $res = $model->save();
                if (!$res) {
                    return ['code'=>1,'message'=> $msg . '成功，但回写活动编码失败！'];
                }

                $res = $this->_addProducts($id, $prodIdArr, $speIdArr, $priceArr, $wholesaler_ids);
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
     * 获取新人活动列表
     * @return array
     */
    public function actionGetactivitys()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\NewUserActivity();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $keys = isset($get['key'])? $get['key'] : [];
        $act_date =  isset($keys['act_date'])? $keys['act_date'] : '';
        $act_code =  isset($keys['act_code'])? $keys['act_code'] : '';
        $act_name =  isset($keys['act_name'])? $keys['act_name'] : '';

        $where = ['del'=>1];

        $andWhere1 = [];
        $act_code = trim($act_code);
        if ($act_code) {
            $andWhere1 = [
                'like', 'act_code', $act_code
            ];
        }

        $andWhere2 = [];
        $act_name = trim($act_name);
        if ($act_name) {
            $andWhere2 = [
                'like', 'act_name', $act_name
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
                ['>=', 'start_at', $start_at],
                ['<=', 'end_at', $end_at]
            ];
        }

        $conut = $model::find()->where($where)->andWhere($andWhere1)->andWhere($andWhere2)->andWhere($andWhere3)->count();
        $offset = $limit * ($page - 1);
        $data = $model::find()->where($where)->andWhere($andWhere1)
                              ->andWhere($andWhere2)->andWhere($andWhere3)->orderBy('id desc')
                              ->limit($limit)->offset($offset)->asArray()->all();

        if ($data) {
            $regionModel = new \backend\models\Region();
            $curTime = time();
            foreach ($data as $key=>$val) {
                //获取城市
                $cityInfo = $regionModel::find()->where(['code'=>$val['city']])->asArray()->one();
                $data[$key]['cityName'] = $cityInfo['name'];

                //是否已结束
                $start_time = strtotime($val['start_at']);
                $end_time = strtotime($val['end_at']);
                if ($end_time <= $curTime) {
                    $operate_status = '<span style="color: red;">已结束</span>';
                } else {
                    if ($start_time > $curTime) {
                        $operate_status = '<span style="color: #CC9999;">未开始</span>';
                    } else {
                        $operate_status = '<span style="color: #0066CC;">进行中</span>';
                    }
                }
                $data[$key]['operate_label'] = $operate_status;
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
        $model = new \backend\models\NewUserActivity();

        $id = isset($post['id'])? intval($post['id']) : 0;

        if (empty($id)) {
            return ['code'=>1,'message'=> '参数错误'];
        }

        $info = $model::findOne($id);
        if (!$info) {
            return ['code'=>1,'message'=> '参数错误'];
        }

        $curTime = time();
        $start_time  = strtotime($info['start_at']);
        $end_at_time = strtotime($info['end_at']);

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
     * 设置状态
     * @return array
     */
    public function actionSetstatus()
    {
        $post = yii::$app->request->post();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\NewUserActivity();

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
            return ['code'=>1,'message'=> '当前状态为要操作的状态'];
        }

        $data = ['status'=>$status];
        $res = $model::updateAll($data, ['id'=>$id]);
        if ($res == 0) {
            return ['code'=>1,'message'=> '网络繁忙，请稍后再尝试'];
        }

        return ['code'=>0,'message'=> '操作成功'];
    }

    /**
     * 获取地区数据
     * @param int $level
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getRegionData($level=1, $pid=0)
    {
        //获取省份
        $regionModel = new \backend\models\Region();
        $level = intval($level);
        $where = ['level'=>$level,'del'=>1];
        if ($pid) {
            $where['parent_id'] = $pid;
        }
        $data = $regionModel::find()->where($where)->asArray()->all();
        return $data;
    }

    /**
     * 编辑新人活动商品
     */
    private function _updateProducts($actId, $prodIdArr, $specIdArr, $priceArr, $wholesaler_ids)
    {
        $productModel = new \backend\models\NewActProduct();
        $res = $productModel::deleteAll(['act_id'=>$actId]);
        if (!$res) {
            return false;
        }
        return $this->_addProducts($actId, $prodIdArr, $specIdArr, $priceArr, $wholesaler_ids);
    }

    /**
     * 新增新人活动的商品
     */
    private function _addProducts($actId, $prodIdArr, $specIdArr, $priceArr, $wholesaler_ids)
    {
        $insertData = [];
        foreach ($specIdArr as $key=>$speId) {
            $insertData[] = [
                'act_id'    => intval($actId),
                'product_id'=> intval($prodIdArr[$key]),
                'spec_id'   => intval($speId),
                'price'     => bcmul($priceArr[$key], 100, 0),
                'wholesaler_id'=> intval($wholesaler_ids[$key]),
                'del'=>1
            ];
        }
        $res = Yii::$app->productDb->createCommand()->batchInsert('new_act_product', ['act_id','product_id','spec_id','price','wholesaler_id','del'],$insertData)->execute();
        return $res;
    }

    /**
     * 新增自提点
     */
    private function _addStores($actId, $storeIdArr)
    {
        $insertData = [];
        foreach ($storeIdArr as $key=>$store_id) {
            $insertData[] = [
                'act_id'    => $actId,
                'store_id'  => $store_id,
                'del'=>1
            ];
        }
        $res = Yii::$app->productDb->createCommand()->batchInsert('new_act_store', ['act_id','store_id','del'],$insertData)->execute();
        return $res;
    }

    /**
     * 编辑自提点
     */
    private function _updateStores($actId, $storeIdArr)
    {
        $storeModel = new \backend\models\NewActStore();
        $info = $storeModel::find()->where(['act_id'=>$actId])->asArray()->one();
        $res = true;
        if (!empty($info)) {
            $res = $storeModel::deleteAll(['act_id'=>$actId]);
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
        $storeModel = new \backend\models\NewActStore();
        $info = $storeModel::find()->where(['act_id'=>$actId])->asArray()->one();
        if (!empty($info)) {
            return  $storeModel::deleteAll(['act_id'=>$actId]);
        }
        return true;
    }

    /**
     * 创建活动编码
     * @param $id
     * @return string
     */
    private function _createActCode($id)
    {
        $len = strlen($id);
        if ($len >= 4) {
            $numberStr = $id + 1;
        } else {
            $numberStr = str_pad($id, 4,'0', STR_PAD_LEFT);
        }

        return 'XR' . date('Ymd') . $numberStr;
    }

    /**
     * 获取拼团价格
     * @return array
     */
    private function _getPintuanPrices($spec_ids)
    {
        $pintuanModel = new \backend\models\PintuanActivity();
        $pintuanPrices = $pintuanModel->getSpecificationPrices($spec_ids);

        $res = [];

        if ($pintuanPrices) {
             foreach ($pintuanPrices as $key=>$val) {
                 $val['pin_price'] = $val['pin_price']/100;
                 $res[$val['specification_id']] = $val;
             }
        }
        return $res;
    }
}
