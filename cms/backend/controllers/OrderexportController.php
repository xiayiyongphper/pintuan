<?php

namespace backend\controllers;

use backend\models\Order;
use backend\models\OrderAddress;
use backend\models\OrderProduct;
use backend\models\OrderSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\tools\Excel;

/**
 * 订单导出控制器
 */
class OrderexportController extends Controller
{
    public $enableCsrfValidation = false;

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
     * @return string
     * Orderexport/index
     * http://pintuan.lelai.com/orderexport/index
     * http://localhost/cms/backend/web/orderexport/index
     */
    public function actionIndex()
    {
        //获取供货商
        $model = new \backend\models\Wholesaler();
        $wholesalerList = $model::find()->asArray()->all();

        $todayDate = date('Y-m-d', time());
        $yestodayDate = date('Y-m-d', strtotime('-1 day'));

        $data = [
            'wholesalerList'=>$wholesalerList,
            'todayDate'=>$todayDate,
            'yestodayDate'=>$yestodayDate,
        ];
        return $this->render('index', $data);
    }


    /**
     * 导出财务单据
     * http://pintuan.lelai.com/orderexport/finance
     * http://localhost/cms/backend/web/orderexport/finance
     */
    public function actionFinance()
    {
        //获取供货商
        $model = new \backend\models\Wholesaler();
        $wholesalerList = $model::find()->asArray()->all();

        $todayDate = date('Y-m-d', time());
        $yestodayDate = date('Y-m-d', strtotime('-1 day'));

        $data = [
            'wholesalerList'=>$wholesalerList,
            'todayDate'=>$todayDate,
            'yestodayDate'=>$yestodayDate,
        ];
        return $this->render('finance', $data);
    }

    /**
     * 导出订单单据
     */
    public function actionExport()
    {
        $data = yii::$app->request->get();

        //获取参数、验证参数
        $wholesaler_ids = isset($data['wholesaler_id'])? $data['wholesaler_id'] : [];
        $startday = isset($data['startday'])? $data['startday'] : '';
        $endday = isset($data['endday'])? $data['endday'] : '';
        $wholesaler_name = isset($data['wholesaler_name'])? $data['wholesaler_name'] : '';
        $wholesaler_name = trim($wholesaler_name, ',');

        if (!$wholesaler_ids) {
            echo '请正确选择供货商！';
            exit;
        }

        //查询时间
        $time_type = isset($data['time_type'])? intval($data['time_type']) : 0;
        if (!in_array($time_type, [1,2])) {
            echo '请选择查询时间类型！';
            exit;
        }

        if (!$startday || strtotime($startday) === false) {
            echo '请选择起始时间！';
            exit;
        }

        if (!$endday || strtotime($endday) === false) {
            echo '请选择截止时间！';
            exit;
        }

        if (strtotime($endday) <= strtotime($startday)) {
            echo '截止时间必须大于起始时间！';
            exit;
        }

        $orderModel = new \backend\models\Order();
        $statusArr = [2,3,4];
        $orderData = $orderModel->getOrderList2($wholesaler_ids, $startday, $endday, $statusArr, $time_type);

        if (empty($orderData)) {
            echo '很抱歉，暂无数据！';
            exit;
        }

        //配送单、详情订单
        $distributionData = [];

        //总数单
        $goodNumData = [];
        $total = 0;

        $storeModel = new \backend\models\Store();
        $wholesalerModel = new \backend\models\Wholesaler();

        //导出详情单
        foreach ($orderData as $key=>$val) {
            $store_id = $val['store_id'];
            $product_id = $val['product_id'];

            $purchase_price = sprintf("%.2f", $val['purchase_price']/100);
            $price          = sprintf("%.2f", $val['price']/100);

            //配送单
            if (!isset($distributionData[$store_id])) {
                $storeInfo = $storeModel::findOne($store_id);
                $distributionData[$store_id]['name'] = $storeInfo->name;
                $distributionData[$store_id]['owner_user_name'] = $storeInfo->owner_user_name;
                $distributionData[$store_id]['address'] = $storeInfo->address . $storeInfo->detail_address;
                $distributionData[$store_id]['store_phone'] = $storeInfo->store_phone;
                $distributionData[$store_id]['goodnum'] = 0;
                $distributionData[$store_id]['goodlsit'] = [];
            }

            //配送单商品
            if (!isset($distributionData[$store_id]['goodlsit'][$product_id])) {
                $distributionData[$store_id]['goodlsit'][$product_id] = [
                    'name'            => $val['productname'],
                    'num'             => $val['number'],
                    'create_at'       =>$val['create_at'],
                    'pay_at'          =>$val['pay_at'],
                    'purchase_price' => $purchase_price,
                    'price'           => $price,
                ];
            } else {
                $distributionData[$store_id]['goodlsit'][$product_id]['num'] = $distributionData[$store_id]['goodlsit'][$product_id]['num'] + $val['number'];
            }

            //详情单
            if (!isset($distributionData[$store_id]['detail'])) {
                $distributionData[$store_id]['detail'] = [];
            }

            $distributionData[$store_id]['detail'][] = [
                'order_number'   =>$val['order_number'],
                'username'       =>$val['username'],
                'userphone'      =>$val['userphone'],
                'productname'    =>$val['productname'],
                'number'          =>$val['number'],
                'create_at'       =>$val['create_at'],
                'pay_at'          =>$val['pay_at'],
                'purchase_price' =>$purchase_price,
                'price'           =>$price,
                'deal_amout'     =>sprintf("%.2f", $val['deal_price'] * $val['number'] / 100)//实付款
            ];

            //商品统计
            if (!isset($goodNumData[$product_id])) {
                $goodNumData[$product_id] = [
                    'name'            =>$val['productname'],
                    'num'             =>$val['number'],
                    'create_at'       =>$val['create_at'],
                    'pay_at'          =>$val['pay_at'],
                    'purchase_price' =>$purchase_price,
                    'price'           =>$price,
                ];
            } else {
                $goodNumData[$product_id]['num'] = $goodNumData[$product_id]['num'] + $val['number'];
            }
            $total += $val['number'];
        }

        $disList = [];
        $detailList = [];
        foreach ($distributionData as $index=>$dis) {
            $name             = $dis['name'];
            $owner_user_name  = $dis['owner_user_name'];
            $address          = $dis['address'];
            $store_phone      = $dis['store_phone'];

            $goodList = $dis['goodlsit'];
            foreach ($goodList as $good) {
                $disList[] = [
                    $name,
                    $good['name'],
                    $good['num'],
                    $good['create_at'],
                    $good['pay_at'],
                    $good['purchase_price'],
                    $good['price']
                ];
            }

            $disList[] = ['',$address,'','','','',''];
            $disList[] = ['',$store_phone,'','','','',''];
            $disList[] = ['','','','','','',''];

            $detailArr = $dis['detail'];
            foreach ($detailArr as $detail) {
                $detailList[] = [
                    $name,
                    ' ' . $detail['order_number'],
                    $detail['productname'],
                    $detail['number'],
                    $detail['create_at'],
                    $detail['pay_at'],
                    $detail['purchase_price'],
                    $detail['price'],
                    $detail['deal_amout'],//实付款
                    $detail['username'],
                    ' ' . $detail['userphone'],
                    ''
                ];
            }

            $detailList[] = ['',$address,'','','','',''];
            $detailList[] = ['',$store_phone,'','','','',''];
            $detailList[] = ['','','','','','',''];
        }

        //返回结果
        $res = [];

        //导出配送单
        $title = ['自提点','订单商品名称','数量','下单时间','支付时间','进货价(元)','售价(元)'];
        $fileName = '配送单-'. date('YmdHis', time());
        Excel::exportExcel2($title, $disList, $fileName);
        $res['fileName'] = $fileName;

        //详情单
        $title2 = ['自提点','订单编号','订单商品名称','数量','下单时间','支付时间','进货价(元)','售价(元)','实付款(元)','收货人','收货人电话','是否提货'];
        $fileName2 = '详情单-'. date('YmdHis', time());
        Excel::exportExcel2($title2, $detailList, $fileName2);
        $res['fileName2'] = $fileName2;

        //导出总数单
        $goodNumList = [];
        foreach ($goodNumData as $numVal) {
            $goodNumList[] = [
                $numVal['name'],
                $numVal['num'],
                $numVal['purchase_price'],
                $numVal['price']
            ];
        }
        $goodNumList[] = [
            '总计',
            $total,
            '',
            ''
        ];
        $title3 = ['产品名','数量','进货价(元)','售价(元)'];
        $fileName3 = '总数单-'. date('YmdHis', time());
        Excel::exportExcel2($title3, $goodNumList, $fileName3);
        $res['fileName3'] = $fileName3;

        $res['name'] = $wholesaler_name;
        $res['start'] = $startday;
        $res['end']   = $endday;
        return $this->render('success', $res);
    }

    /**
     * 导出财务单据
     */
    public function actionExportfinance()
    {
        $data = yii::$app->request->get();

        //获取参数、验证参数
        $wholesaler_ids = isset($data['wholesaler_id'])? $data['wholesaler_id'] : [];
        $startday = isset($data['startday'])? $data['startday'] : '';
        $endday = isset($data['endday'])? $data['endday'] : '';
        $wholesaler_name = isset($data['wholesaler_name'])? $data['wholesaler_name'] : '';
        $wholesaler_name = trim($wholesaler_name, ',');

        if (!$wholesaler_ids) {
            echo '请正确选择供货商！';
            exit;
        }

        //查询时间
        $time_type = isset($data['time_type'])? intval($data['time_type']) : 0;
        if (!in_array($time_type, [1,2])) {
            echo '请选择查询时间类型！';
            exit;
        }

        if (!$startday || strtotime($startday) === false) {
            echo '请选择起始时间！';
            exit;
        }

        if (!$endday || strtotime($endday) === false) {
            echo '请选择截止时间！';
            exit;
        }

        if (strtotime($endday) <= strtotime($startday)) {
            echo '截止时间必须大于起始时间！';
            exit;
        }

        $orderModel = new \backend\models\Order();

        $statusArr = [2,3,4,5,6];
        $orderData = $orderModel->getOrderList2($wholesaler_ids, $startday, $endday, $statusArr, $time_type);

        if (empty($orderData)) {
            echo '很抱歉，暂无数据！';
            exit;
        }

        //财务核对单
        $financeData = [];

        $storeModel = new \backend\models\Store();
        $wholesalerModel = new \backend\models\Wholesaler();
        $userModel = new \backend\models\PintuanUser();

        //供货商
        $wholesalerList = [];
        //店铺
        $storeList = [];
        //用户
        $userList = [];


        //状态：1-未支付，2-已支付，3-已发货，4-已到货，5-已确认收货，6-已取消
        $statusNames = [
            '2'=>'已支付',
            '3'=>'已发货',
            '4'=>'已到货',
            '5'=>'已确认收货',
            '6'=>'已取消'
        ];

        //导出详情单
        foreach ($orderData as $key=>$val) {
            $wholesaler_id = $val['wholesaler_id'];
            $store_id = $val['store_id'];
            $user_id = $val['user_id'];

            $purchase_price = sprintf("%.2f", $val['purchase_price']/100);//进价
            $price          = sprintf("%.2f", $val['price']/100);//原价

            //供货商
            if (!isset($wholesalerList[$wholesaler_id])) {
                $wholesalerInfo = $wholesalerModel::findOne($wholesaler_id);
                $wholesalerList[$wholesaler_id] = [
                    'name'=>$wholesalerInfo->name
                ];
            }

            //店铺
            if (!isset($storeList[$store_id])) {
                $storeInfo = $storeModel::findOne($store_id);
                $storeList[$store_id] = [
                    'name'=>$storeInfo->name,
                    'address'=>$storeInfo->address . $storeInfo->detail_address
                ];
            }

            //用户
            if (!isset($userList[$user_id])) {
                $userInfo = $userModel::findOne($user_id);
                $userList[$user_id] = [
                    'open_id'=>$userInfo->open_id
                ];
            }

            //财务单
            $financeData[] = [
                $wholesalerList[$wholesaler_id]['name'],
                $val['order_number'],
                $userList[$user_id]['open_id'],//用户微信OPENID
                $val['create_at'],
                $statusNames[$val['status']],//订单状态
                $val['pay_at'],
                $val['productname'],
                $val['item_detail'],
                $val['number'],
                $price,//原价
                sprintf("%.2f", $val['deal_price'] * $val['number'] / 100),
                sprintf("%.2f", $val['purchase_price'] / 100),
                sprintf("%.2f", $val['purchase_price'] * $val['number'] / 100),
                $storeList[$store_id]['name'],//店铺名称
                $storeList[$store_id]['address'],//自提点
                $val['username'],
                $val['userphone'] . ' '
            ];
        }

        //财务核对单
        $title = ['供应商','订单编号','用户微信OPENID','下单时间','订单状态','支付时间','订单商品名称','规格','数量','原价','实收款','进货价','进货总额','店铺名称','自提点','姓名','手机号'];
        $fileName = '财务核对单-'. date('YmdHis', time());
        Excel::exportExcel($title, $financeData, $fileName);
        eixt;
    }
}
