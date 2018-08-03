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

/**
 * 店铺类型设置
 */
class CommissionController extends Controller
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
     * 店铺类型列表
     * @return string
     */
    public function actionIndex()
    {
        $data = [];
        return $this->render('index',$data);
    }

    /**
     * 创建店铺类型的页面
     * @return string
     */
    public function actionCreate()
    {
        $data = [];
        return $this->render('create',$data);
    }

    /**
     * 修改店铺类型的页面
     * @return string
     */
    public function actionUpdate($id)
    {
        $id = intval($id);
        $model = new \backend\models\StoreCommission();

        $info = $model::findOne($id);
        $data = [
            'info'=>$info,
        ];

        if ($info['commission_type'] == 2) {
            $info['commission_val'] = sprintf("%.2f", $info['commission_val']/100);
        }

        return $this->render('update',$data);
    }

    /**
     * 新增编辑
     * @return array
     */
    public function actionAdd()
    {
        yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = yii::$app->request->post();
        $model = new \backend\models\StoreCommission();

        $id = isset($post['id'])?  intval($post['id']): 0;
        $name = isset($post['name'])? $post['name'] : '';
        $commission_type = isset($post['commission_type'])? $post['commission_type'] : 0;
        $commission_val = isset($post['commission_val'])? $post['commission_val'] : 0;

        if (!isset($name) || empty($name)) {
            return ['code'=>1,'message'=>'请输入店铺类型名称'];
        }
        $name = trim($name);

        if (!is_numeric($commission_type) || !in_array($commission_type, [1,2])) {
            return ['code'=>1,'message'=>'请选择佣金类型'];
        }
        $commission_type = intval($commission_type);

        if (empty($commission_val) || !is_numeric($commission_val) || $commission_val<=0) {
            return ['code'=>1,'message'=>'请输入合法的佣金数值'];
        }

        if ($commission_type == 2) {
            $commission_val = bcmul($commission_val, 100, 0);//前端输入元，后台转换为：分
        } else {
            if ($commission_val > 100) {
                return ['code'=>1,'message'=>'佣金数值不能超过100'];
            }
            $commission_val = intval($commission_val);
        }

        if ($id) {
            $info = $model::findOne($id);
            if (!$info) {
                return ['code'=>1,'message'=>'要编辑的记录不存在！'];
            }
        }

        $curDate = date('Y-m-d H:i:s', time());

        if ($id) {
            $msg = '编辑';
            $data = [
                'name'=>$name,
                'commission_type'=>$commission_type,
                'commission_val'=>$commission_val,
            ];
            $result = $model::updateAll($data, ['id'=>$id]);
        } else {
            $model->name = $name;
            $model->commission_type = $commission_type;
            $model->commission_val = $commission_val;
            $model->create_at = $curDate;
            $model->update_at = $curDate;
            $model->del = 1;
            $result = $model->save();
            $id = $model->id;
            $msg = '新增';
        }

        if ($result !== false) {
            return ['code'=>0,'message'=> $msg . '成功'];
        }

        return ['code'=>1,'message'=>'网络繁忙，请稍后再尝试'];
    }

    /**
     * 获取列表
     * @return array
     */
    public function actionGetlist()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\StoreCommission();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $where = ['del'=>1];

        $conut = $model::find()->where($where)->count();
        $offset = $limit * ($page - 1);
        $data = $model::find()->where($where)->orderBy('id desc')->limit($limit)->offset($offset)->asArray()->all();

        if ($data) {
            foreach ($data as $key=>$val) {
                if ($val['commission_type'] == 1) {
                    $data[$key]['commission_type'] = '佣金系数';
                    $data[$key]['commission_val'] =  $val['commission_val'] . '%/单';
                } else {
                    $data[$key]['commission_type'] = '现金';
                    $data[$key]['commission_val'] = sprintf("%.2f", $val['commission_val']/100) . '元/单';
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
        $model = new \backend\models\StoreCommission();

        $id = isset($post['id'])? intval($post['id']) : 0;

        if (empty($id)) {
            return ['code'=>1,'message'=> '参数错误'];
        }

        $info = $model::findOne($id);
        if (!$info) {
            return ['code'=>1,'message'=> '参数错误'];
        }

        //是否有店铺已设置
        $storeModel = new \backend\models\Store();
        $where = [
            'commission_id'=>$id,
            'del'=>1
        ];
        $storeInfo = $storeModel::find()->where($where)->asArray()->one();
        if (!empty($storeInfo)) {
            return ['code'=>1,'message'=> '有店铺已设置，不能删除该店铺类型！'];
        }

        $info->del = 2;
        if (!$info->save()) {
            return ['code'=>1,'message'=> '网络繁忙，请稍后再尝试'];
        }

        return ['code'=>0,'message'=> '删除成功'];
    }
}
