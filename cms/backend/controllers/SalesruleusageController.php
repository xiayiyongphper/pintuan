<?php

namespace backend\controllers;

use backend\models\Store;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * 优惠卷领取情况控制器
 */
class SalesruleusageController extends Controller
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
     * 优惠卷领取情况列表
     * @return string
     */
    public function actionIndex()
    {
        $get = yii::$app->request->get();
        $id = isset($get['id'])? intval($get['id']) : 0;

        //获取优惠卷的数据统计
        $model = new \backend\models\Salesrule();
        $info = $model::findOne($id);

        //目前共领取：XX张    已经使用：M张
        $userCouponModel = new \backend\models\SalesruleUserCoupon();
        $where = [
            'rule_id'=>$id,
        ];
        $total = $userCouponModel::find()->where($where)->count();

        $where['state'] = 2;
        $useNum = $userCouponModel::find()->where($where)->count();

        $data = [
            'id'=>$id,
            'name'=>$info->title,
            'total'=>$total,
            'useNum'=>$useNum
        ];
        return $this->render('index',$data);
    }

    /**
     * 获取领取情况列表
     * @return array
     */
    public function actionGetusage()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\SalesruleUserCoupon();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $keys = isset($get['key'])? $get['key'] : [];
        $id =  isset($keys['id'])? intval($keys['id']) : 0;

        $where = ['del'=>1];
        if ($id) {
            $where['rule_id'] = $id;
        }

        $conut = $model::find()->where($where)->count();
        $offset = $limit * ($page - 1);
        $data = $model::find()->where($where)->limit($limit)->offset($offset)->asArray()->all();

        if ($data) {
            $userModel = new \backend\models\PintuanUser();
            $i = 0;
            foreach ($data as $key=>$val) {
                //序号
                $i++;
                $data[$key]['number'] = $i;
                //获取用户信息
                $userInfo = $userModel::findOne($val['user_id']);
                if ($userInfo) {
                    $data[$key]['nick_name'] = $userInfo->nick_name;
                    $data[$key]['real_name'] = $userInfo->real_name;
                } else {
                    $data[$key]['nick_name'] = '';
                    $data[$key]['real_name'] = '';
                }

                if (strtotime($val['used_at']) == 0) {
                    $data[$key]['used_at'] = '';
                }

                if (strtotime($val['expiration_date']) == 0) {
                    $data[$key]['expiration_date'] = '';
                }

                if (strtotime($val['created_at']) == 0) {
                    $data[$key]['created_at'] = '';
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
        $model = new \backend\models\SalesruleUserCoupon();

        $id = isset($post['id'])? intval($post['id']) : 0;

        if (empty($id)) {
            return ['code'=>1,'message'=> '参数错误'];
        }

        $info = $model::findOne($id);
        if (!$info) {
            return ['code'=>1,'message'=> '参数错误'];
        }

        $info->del = 2;
        if (!$info->save()) {
            return ['code'=>1,'message'=> '网络繁忙，请稍后再尝试'];
        }

        return ['code'=>0,'message'=> '删除成功'];
    }
}
