<?php

namespace backend\controllers;

use backend\models\Order;
use backend\models\OrderAddress;
use backend\models\OrderProduct;
use backend\models\OrderSearch;
use backend\models\Store;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $params = Yii::$app->request->queryParams;

        Yii::$app->session['order_search_filter'] = $params;
        $dataProvider = $searchModel->search($params);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = intval($id);

        $order = Order::getOrder($id);

        if (empty($order)) {
            throw new NotFoundHttpException('订单不存在！', 0000);
        }

        $orderProduct = OrderProduct::orderProductDataProvider($id);
        $orderAddress = OrderAddress::find()->where(['order_id'=>$id])->one();
        $store = Store::findOne($order->store_id);

        if (empty($orderAddress)) {
            throw new NotFoundHttpException('订单收货地址不存在', 0000);
        }

        if (empty($store)) {
            throw new NotFoundHttpException('订单提货地址不存在', 0000);
        }
        return $this->render('view', [
            'orderProduct' => $orderProduct,
            'orderAddress' => $orderAddress,
            'model' => $order,
            'store' => $store,
        ]);
    }

    /**
     * 新版的订单列表
     */
    public function actionNewindex()
    {
        return $this->render('newindex');
    }

    /**
     * 获取订单列表
     * @return array
     */
    public function actionGetorders()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\Order();
        $userModel = new \backend\models\PintuanUser();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $keys = isset($get['key'])? $get['key'] : [];
        $orderDate =  isset($keys['orderDate'])? $keys['orderDate'] : '';
        $orderNumber =  isset($keys['orderNumber'])? $keys['orderNumber'] : '';
        //$wholesalerName =  isset($keys['wholesalerName'])? $keys['wholesalerName'] : '';
        $userName =  isset($keys['userName'])? $keys['userName'] : '';
        $storeName =  isset($keys['storeName'])? $keys['storeName'] : '';
        //状态：1-未支付，2-已支付，3-已发货，4-已到货，5-已确认收货，6-已取消
        $status =  isset($keys['status'])? intval($keys['status']) : 0;

        $where = [
            'del'=>1
        ];
        if ($status) {
            $where['status'] = $status;
        }

        $andWhere0 = [];
        $orderNumber = trim($orderNumber);
        if ($orderNumber) {
            $andWhere0 = [
                'like', 'order_number', $orderNumber
            ];
        }

        $andWhere1 = [];
        $storeName = trim($storeName);
        if ($storeName) {
            $andWhere1 = [
                'like', 'store_name', $storeName
            ];
        }

        $andWhere2 = [];
        if ($orderDate) {
            $dateStr = trim($orderDate);
            $dateArr = explode('~', $dateStr);
            $start_at = $dateArr[0];
            $end_at = $dateArr[1];
            $andWhere2 = [
                'and',
                ['>=', 'create_at', $start_at],
                ['<=', 'create_at', $end_at]
            ];
        }

        $andWhere3 = [];
        $userName = trim($userName);
        if ($userName) {
            $userSelect = 'id';
            $userWhere = [
                'like', 'nick_name', $userName
            ];
            $userList = $userModel::find()->select($userSelect)->where($userWhere)->asArray()->all();
            if (!empty($userList)) {
                  $userIds = [];
                 foreach ($userList as $user) {
                     $userIds[] = $user['id'];
                 }
                $andWhere3 = [
                    'in', 'user_id', $userIds
                ];
            } else {
                $res = ['code'=>0,'count'=>0,'data'=>[]];
                return $res;
            }
        }

        $conut = $model::find()->where($andWhere0)->andWhere($andWhere1)->andWhere($andWhere2)->andWhere($andWhere3)->andWhere($where)->count();
        $select = '*';
        $offset = $limit * ($page - 1);
        $data =  $model::find()->select($select)->where($andWhere0)->andWhere($andWhere1)
                               ->andWhere($andWhere2)->andWhere($andWhere3)->andWhere($where)
                               ->orderBy('id desc')
                               ->limit($limit)->offset($offset)->asArray()->all();

        if ($data) {
            $statusNames = [
                '1'=>'未支付',
                '2'=>'已支付',
                '3'=>'已发货',
                '4'=>'已到货',
                '5'=>'已确认收货',
                '6'=>'已取消',
            ];

            //1-未退款，2-已申请退款，3-已同意退款，4-已到账
            $refund_Names = [
                '1'=>'未退款',
                '2'=>'已申请退款',
                '3'=>'已同意退款',
                '4'=>'已到账',
            ];

            foreach ($data as $key=>$val) {
                $userInfo = $userModel::findOne($val['user_id']);
                $data[$key]['user_info'] = '';
                if (!empty($userInfo)) {
                    $data[$key]['user_info'] = $userInfo->id . '(' . $userInfo->nick_name . ')';
                }

                $data[$key]['status_lable']         = $statusNames[$val['status']];
                $data[$key]['refund_status_lable'] = $refund_Names[$val['refund_status']];

                $data[$key]['amount'] = sprintf("%.2f", $val['amount'] / 100);
                $data[$key]['discount_amount'] = sprintf("%.2f", $val['discount_amount'] / 100);
                $data[$key]['payable_amount'] = sprintf("%.2f", $val['payable_amount'] / 100);
                $data[$key]['real_amount'] = sprintf("%.2f", $val['real_amount'] / 100);

                if (strtotime($val['pay_at']) && strtotime($val['pay_at'])> 0) {
                    $data[$key]['pay_at'] = $val['pay_at'];
                } else {
                    $data[$key]['pay_at'] = '';
                }
            }
        }
        $res = ['code'=>0,'count'=>$conut,'data'=>$data];
        return $res;
    }
}
