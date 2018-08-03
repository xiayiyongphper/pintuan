<?php

namespace backend\controllers;

use backend\models\Store;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\tools\Common;
use common\tools\Weixin;

/**
 * 优惠卷控制器
 */
class CouponController extends Controller
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
     * 优惠卷列表
     * @return string
     */
    public function actionIndex()
    {
        $data = [];
        return $this->render('index',$data);
    }

    /**
     * 创建优惠卷
     * @return string
     */
    public function actionCreate()
    {
        $data = [];
        return $this->render('create',$data);
    }

    /**
     * 创建优惠卷
     * @return string
     */
    public function actionUpdate($id)
    {
        $id = intval($id);
        $model = new \backend\models\Salesrule();
        $sproductModel = new \backend\models\SalesruleProduct();

        $info = $model::findOne($id);
        $goodList = $sproductModel->getGoodList($id);

        $data = [
            'info'=>$info,
            'goodList'=>$goodList
        ];

        $info['condition'] = sprintf("%.2f", $info['condition']/100);
        $info['discount_amount'] = sprintf("%.2f", $info['discount_amount']/100);
        return $this->render('update',$data);
    }

    /**
     * 新增编辑优惠卷
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

        //优惠券名称
        if (!isset($post['title']) || empty($post['title'])) {
            return ['code'=>1,'message'=>'请输入优惠券名称'];
        }
        $title = trim($post['title']);

        //优惠券类型
        if (!isset($post['coupon_type']) || !is_numeric($post['coupon_type']) || !in_array($post['coupon_type'], [1,2,3])) {
            return ['code'=>1,'message'=>'请选择优惠券类型'];
        }
        $coupon_type = intval($post['coupon_type']);


        //优惠金额
        if (!isset($post['discount_amount']) || empty($post['discount_amount']) || !is_numeric($post['discount_amount']) || $post['discount_amount']<=0) {
            return ['code'=>1,'message'=>'请输入合法的优惠金额'];
        }
        $discount_amount = bcmul($post['discount_amount'], 100, 0);//前端输入元，后台转换为：分

        //门槛金额
        if (!isset($post['condition']) || !is_numeric($post['condition']) || $post['condition']<0) {
            return ['code'=>1,'message'=>'请输入合法的门槛金额'];
        }
        $condition = bcmul($post['condition'], 100, 0);//前端输入元，后台转换为：分

        //发放总量
        if (!isset($post['uses_per_coupon']) || !is_numeric($post['uses_per_coupon']) || $post['uses_per_coupon']<0) {
            return ['code'=>1,'message'=>'请输入合法的发放总量'];
        }
        $uses_per_coupon = intval($post['uses_per_coupon']);

        //每人限制领取总量
        if (!isset($post['uses_per_customer']) || !is_numeric($post['uses_per_customer']) || $post['uses_per_customer']<=0) {
            return ['code'=>1,'message'=>'请输入合法的每人限制领取总量'];
        }
        $uses_per_customer = intval($post['uses_per_customer']);

        //是否与新人专区商品互斥1是2否
        if (!isset($post['activity_exclude']) || !is_numeric($post['activity_exclude']) || !in_array($post['activity_exclude'], [1,2])) {
            return ['code'=>1,'message'=>'请选择新人专区商品互斥'];
        }
        $activity_exclude = intval($post['activity_exclude']);

        //优惠卷的有效天数
        if (!isset($post['effective_day']) || !is_numeric($post['effective_day']) || $post['effective_day']<=0) {
            return ['code'=>1,'message'=>'请输入合法的领取后失效时间('];
        }
        $effective_day = intval($post['effective_day']);

        //1:全场通用 2:部分商品可用
        $sales_rule_scope = intval($post['sales_rule_scope']);
        //部分商品可用
        if ($sales_rule_scope == 2) {
            //优惠卷商品的验证
            if (empty($prodids)) {
                return ['code'=>1,'message'=>'请选择优惠卷商品'];
            }
            $productModel = new \backend\models\Product();

            foreach ($prodids as $prid) {
                $productInfo = $productModel::findOne($prid);
                if (!$productInfo) {
                    return ['code'=>1,'message'=>'选择的优惠卷商品异常，商品id为：'. $prid];
                }
            }
        }

        if ($id) {
            $info = $model::findOne($id);
            if (!$info) {
                return ['code'=>1,'message'=>'要编辑的记录不存在！'];
            }
        }

        $insertFlag = true;
        if ($id) {
            $insertFlag = false;
            $msg = '编辑';
            $data = [
                'title'=>$title,
                'coupon_type'=>$coupon_type,
                'condition'=>$condition,
                'discount_amount'=>$discount_amount,
                'uses_per_customer'=>$uses_per_customer,
                'uses_per_coupon'=>$uses_per_coupon,
                'activity_exclude'=>$activity_exclude,
                'remark'=>$remark,
                'effective_day'=>$effective_day,
                'sales_rule_scope'=>$sales_rule_scope
            ];
            $result = $model::updateAll($data, ['id'=>$id]);
        } else {
            $model->title = $title;
            $model->coupon_type = $coupon_type;
            $model->condition = $condition;
            $model->discount_amount = $discount_amount;
            $model->uses_per_customer = $uses_per_customer;
            $model->uses_per_coupon = $uses_per_coupon;
            $model->activity_exclude = $activity_exclude;
            $model->remark = $remark;
            $model->effective_day = $effective_day;
            $model->create_at = date('Y-m-d H:i:s', time());
            $model->del = 1;
            $model->status = 2;
            $model->sales_rule_scope = $sales_rule_scope;
            $result = $model->save();
            $id = $model->id;
            $msg = '新增';
        }

        if ($result !== false) {
            if ($insertFlag == false) {
                //编辑商品
                $res = $this->_updateSalesruleProducts($id, $sales_rule_scope, $prodids);
            } else {
                //新增
                $res = $this->_addSalesruleProducts($id, $sales_rule_scope, $prodids);

                //若促销卷 生成小程序码
                if ($coupon_type == 2) {
                    $qrcode = $this->_createQrcode($id);
                    if (!$qrcode) {
                        return ['code'=>0,'message'=> '生成小程序码失败！'];
                    }
                    $model->qrcode = $qrcode;
                    $result = $model->save();
                    if (!$result) {
                        return ['code'=>0,'message'=> '回写生成小程序码失败！'];
                    }
                }
            }

            if (!$res) {
                return ['code'=>1,'message'=> $msg . '成功，但'.$msg.'商品失败！'];
            }
            return ['code'=>0,'message'=> $msg . '成功'];
        }

        return ['code'=>1,'message'=>'网络繁忙，请稍后再尝试'];
    }

    /**
     * 获取优惠卷列表
     * @return array
     */
    public function actionGetsalesrules()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\Salesrule();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $keys = isset($get['key'])? $get['key'] : [];
        $title =  isset($keys['title'])? $keys['title'] : '';
        $coupon_type =  isset($keys['coupon_type'])? intval($keys['coupon_type']) : 0;

        $andWhere = ['del'=>1];
        if ($coupon_type) {
            $andWhere['coupon_type'] = $coupon_type;
        }

        $where = [];
        $title = trim($title);
        if ($title) {
            $where = [
                'like', 'title', $title
            ];
        }

        $conut = $model::find()->where($where)->andWhere($andWhere)->count();
        $offset = $limit * ($page - 1);
        $data = $model::find()->where($where)->andWhere($andWhere)->orderBy('id desc')->limit($limit)->offset($offset)->asArray()->all();

        if ($data) {
            foreach ($data as $key=>$val) {
                if ($val['condition'] > 0) {
                    $data[$key]['condition'] =  '金额满：' . sprintf("%.2f", $val['condition']/100) . '元';
                } else {
                    $data[$key]['condition'] = '无门槛';
                }

                $data[$key]['discount_amount'] = sprintf("%.2f", $val['discount_amount']/100) . '元';
                $data[$key]['effective_day'] = $val['effective_day'] . '天';
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
        $model = new \backend\models\Salesrule();

        $id = isset($post['id'])? intval($post['id']) : 0;

        if (empty($id)) {
            return ['code'=>1,'message'=> '参数错误'];
        }

        $info = $model::findOne($id);
        if (!$info) {
            return ['code'=>1,'message'=> '参数错误'];
        }

        //有人领取了优惠券，则不能删除
        $orderModel = new \backend\models\Order();
        $orderWhere = ['coupon_id'=>$id];
        $orderInfo = $orderModel::find()->where($orderWhere)->asArray()->one();
        if (!empty($orderInfo)) {
            return ['code'=>1,'message'=> '该优惠券已有关联订单，不能删除'];
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
        $model = new \backend\models\Salesrule();

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
     * 编辑优惠卷商品
     */
    private function _updateSalesruleProducts($salesruleId, $sales_rule_scope, $prodids)
    {
        $sproductModel = new \backend\models\SalesruleProduct();
        $where = ['rule_id'=>$salesruleId];
        $goods = $sproductModel::find()->where($where)->asArray()->one();
        if (!empty($goods)) {
            $res = $sproductModel::deleteAll($where);
            if (!$res) {
                return false;
            }
        }

        //全场通用
        if ($sales_rule_scope == 1) {
            return true;
        }

        return $this->_addSalesruleProducts($salesruleId, $sales_rule_scope, $prodids);
    }

    /**
     * 新增优惠卷商品
     */
    private function _addSalesruleProducts($salesruleId, $sales_rule_scope, $prodids)
    {
        //全场通用
        if ($sales_rule_scope == 1) {
            return true;
        }

        $insertData = [];
        foreach ($prodids as $productId) {
            $insertData[] = [
                'rule_id'=>$salesruleId,
                'product_id'=>$productId
            ];
        }
        $res = Yii::$app->orderDb->createCommand()->batchInsert('salesrule_product', ['rule_id','product_id'],$insertData)->execute();
        return $res;
    }

    /**
     * 生成卷的小程序码
     * @param $coupon_id
     * @return bool|string
     * @throws \Exception
     */
    private function _createQrcode($coupon_id)
    {
        $weixin = new Weixin();
        $file_name = 'coupon_xcx' . $coupon_id . time() . '.jpg';
        $scene     = $coupon_id;
        $page = 'pages/getcoupon/getcoupon';
        $qrcode = $weixin::setQrCode($file_name, $scene, $page);

        if (empty($qrcode)) {
            return false;
        }

        return $qrcode;
    }
}
