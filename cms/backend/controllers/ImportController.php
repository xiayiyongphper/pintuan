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
use common\tools\Tools;

/**
 * 导入
 */
class ImportController extends Controller
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
     * 导入店铺
     * @return mixed
     * http://localhost/cms/backend/web/import/store
     */
    public function actionStore()
    {
        return $this->render('store');
    }

    /**
     * 导入供应商
     * @return mixed
     */
    public function actionMerchant()
    {
        return $this->render('merchant');
    }

    /**
     * 导入店铺
     */
    public function actionImportStore()
    {
        $post = Yii::$app->request->post();
        $ids = $post['ids'];

     if (empty($ids)) {
            $this->echoJson(1, '参数错误');
        }

        $id_arr = explode(',',trim($ids));

        if (empty($id_arr)) {
            $this->echoJson(1, '参数错误');
        }

        ini_set('memory_limit','521M');
        set_time_limit(0);

        $id_arr = array_unique($id_arr);

        //错误提示
        $msg = "";
        $msg2 = "";
        $msg3 = "";

        $model = new \backend\models\CustomerStore();
        $data = $model::find()->where(['in','entity_id',$id_arr])->asArray()->all();

        if ($data) {
             //店铺名称店主名字联系电话城市区域签约业务员
             $db = Yii::$app->get('wholesalerDb');
             $storeModel = new \backend\models\Store();
             $cur_time = date('Y-m-d H:i:s', time());

             foreach ($data as $key=>$val) {
                 $storeData = [
                     'name'                        => $val['store_name'],
                     'province'                    => $val['province'],
                     'city'                        => $val['city'],
                     'district'                    => $val['district'],
                     'address'                     => $val['address'],
                     'detail_address'             => $val['detail_address'],
                     'lat'                         => $val['lat'],
                     'lng'                         => $val['lng'],
                     'store_phone'                => $val['phone'],
                     'created_at'                 => $cur_time,
                     'owner_user_name'           => $val['storekeeper'],
                     'status'                     => $val['status']
                 ];

                 if ($val['business_license_img']) {
                     $storeData['business_license_img'] =  $val['business_license_img'];
                 }

                 if ($val['business_license_no']) {
                     $storeData['business_license_no'] =  $val['business_license_no'];
                 }

                 if ($val['storekeeper_instore_times']) {
                     $storeData['open_time_range'] =  $val['storekeeper_instore_times'];
                 }

                 if ($val['store_front_img']) {
                     $storeData['store_front_img'] =  $val['store_front_img'];
                 }

                 if ($val['apply_at']) {
                     $storeData['apply_at'] =  $val['apply_at'];
                 }

                 $info = $storeModel::find()->where(['store_phone'=>$val['phone']])->asArray()->one();

                 if (!empty($info)) {
                     $msg2 .= $val['store_name'] . ",";
                     unset($data[$key]);
                     continue;
                 }

                 $storeId = $this->addData($db, $storeData);
                 if (!$storeId) {
                     $msg .= $val['store_name'] . ",";
                     unset($data[$key]);
                     continue;
                 }

                 $qrcode = $this->_createQrcode($storeId);
                 if (!$qrcode) {
                     $msg3 .= $val['store_name'] .",";
                     unset($data[$key]);
                     continue;
                 }

                 $data = [
                     'mini_program_qrcode'=>$qrcode
                 ];
                 $where = [
                     'id'=>$storeId
                 ];
                 $res = $storeModel::updateAll($data, $where);
                 if (!$res) {
                     $msg3 .= $val['store_name'] .",";
                     unset($data[$key]);
                     continue;
                 }
             }

             $tip = "";
             if ($msg) {
                 $tip .= "导入失败的：" . $msg . "\n";
             }

            if ($msg2) {
                $tip .= "已经存在手机号的：" . $msg2 . "\n";
            }

            if ($msg3) {
                $tip .= "小程序码生成失败的：" . $msg3 . "\n";
            }

            $this->echoJson(0, "导入成功" . $tip);

        }
    }

    /**
     * 导入供货商
     */
    public function actionImportMerchant()
    {
        $post = Yii::$app->request->post();
        $ids = $post['ids'];

        if (empty($ids)) {
            $this->echoJson(1, '参数错误');
        }

        $id_arr = explode(',',trim($ids));

        if (empty($id_arr)) {
            $this->echoJson(1, '参数错误');
        }

        ini_set('memory_limit','521M');
        set_time_limit(0);

        $id_arr = array_unique($id_arr);

        //查询之前的供货商 [1013, 1021]
        $msg = "";
        $model = new \backend\models\MerchantStore();
        $data = $model::find()->where(['in','entity_id',$id_arr])->asArray()->all();

        if ($data) {
            $cur_time = date('Y-m-d H:i:s', time());
            //店铺名称店主名字联系电话城市区域签约业务员
            //$pintuan_store_model = new \backend\models\Store();
            $db = Yii::$app->get('wholesalerDb');
            foreach ($data as $key=>$val) {
                $storeData = [
                    'name'              => $val['store_name'],
                    'short_name'       =>$val['short_name'],
                    'province'         => $val['province'],
                    'city'              => $val['city'],
                    'district'          => $val['district'],
                    'store_address'    => $val['store_address'],
                    'lat'               => $val['lng'],
                    'lng'               => $val['lng'],
                    'phone'             => $val['contact_phone'],
                    'service_phone'    => $val['customer_service_phone'],
                    'created_at'       => $cur_time,
                    'updated_at'       => $cur_time
                ];

                $id = $this->addData($db, $storeData, 'wholesaler');

                if (!$id) {
                    $msg .= $val['store_name'] .",";
                    unset($data[$key]);
                    continue;
                }
                unset($data[$key]);
            }

            $tip = "";
            if ($msg) {
                $tip .= "导入失败的供货商：" . $msg;
            }
            $this->echoJson(0, "导入成功" . $tip);
        }
    }

    /**
     * 生成小程序二维码
     * @param $storeId
     * @return string
     * @throws \Exception
     */
    private function _createQrcode($storeId)
    {
        $weixin = new \common\tools\Weixin();
        //小程序二维码
        $file_name = 'store_xcx' . $storeId . time() . '.jpg';
        $scene     = $storeId;
        $page = 'pages/home/home';
        $mini_program_qrcode = $weixin::setQrCode($file_name, $scene, $page);
        if (!$mini_program_qrcode) {
             return '';
        }
        return $mini_program_qrcode;
    }

    /**
     * @param $data
     * @param string $table
     * @throws \yii\db\Exception
     */
    public function addData($db, $data, $table='store')
    {
        $res = $db->createCommand()->insert($table, $data)->execute();
        if (!$res) {
            return false;
        }
        return $db->getLastInsertID();
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
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        Yii::$app->response->data = [
            'status' => $status,
            'msg'    => $msg,
            'data'   => $data,
            'url'    => $url,
        ];
    }
}
