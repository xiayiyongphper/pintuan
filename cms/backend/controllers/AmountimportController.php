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
use yii\web\UploadedFile;
use common\tools\Excel;

/**
 * 金额导入控制器
 */
class AmountimportController extends Controller
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
     * 导入明细列表
     * @return string
     */
    public function actionIndex()
    {
        $data = [];
        return $this->render('index',$data);
    }

    /**
     * 导入页面
     * @return string
     */
    public function actionImport()
    {
        $data = [];
        return $this->render('import',$data);
    }

    /**
     * 上传文件
     */
    public function actionUpload()
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
        if (count($info) != 5) {
            return ['code'=>1,'msg'=> '请上传规范模版的数据文件！'];
        }

        if ($info[0] != '店铺ID' || $info[1] != '店铺名称' || $info[2] != '导入类型' || $info[3] != '金额' || $info[4] !='备注') {
            return ['code'=>1,'msg'=> '请上传规范模版的数据文件！'];
        }

        unset($data[0]);

        if (empty($data)) {
            return ['code'=>1,'msg'=> '请不要上传空文件！'];
        }

        ini_set('memory_limit','1024M');
        set_time_limit(0);

        if (count($data) > 1000) {
            return ['code'=>1,'msg'=> '最多只支持1000条记录导入！'];
        }

        $loginUser = Yii::$app->getUser();
        $ip = $_SERVER["REMOTE_ADDR"];
        $curDate = date('Y-m-d H:i:s', time());

        $model = new \backend\models\ImprotStoreWallet();
        $storeModel = new \backend\models\Store();

        $importData = [];
        //验证数据
        foreach ($data as $key=>$val) {
               $storeid = $val[0];
               $type = trim($val[2]);
               $wallet = $val[3];

               if (!is_numeric($storeid) || $storeid <=0) {
                   return ['code'=>1,'msg'=> '第'.$key.'行数据的店铺id异常，请认真检查'];
               }

                if (empty($type) || ($type!='奖金转入' && $type!='罚金扣除')) {
                    return ['code'=>1,'msg'=> '第'.$key.'行数据的导入类型异常，请认真检查'];
                }

                if (!is_numeric($wallet) || $wallet < 0.01) {
                    return ['code'=>1,'msg'=> '第'.$key.'行数据的金额异常，请认真检查'];
                }

                $info = $storeModel::findOne($storeid);
                if (empty($info)) {
                   return ['code'=>1,'msg'=> '第'.$key.'行数据的店铺不存在，请认真检查'];
                }

                $import_type =  ($type=='罚金扣除') ? 4:3;
                $wallet = bcmul($wallet, 100, 0);
                if ( $import_type == 4 ) {
                       if ($wallet > $info->wallet ) {
                           return ['code'=>1,'msg'=> '第'.$key.'行数据的店铺钱包不够罚金扣除，请认真检查'];
                       }
                }

                $impotData[$key] = [
                    'store_id'=>$storeid,
                    'record_number'=>self::createNumber($import_type),
                    'amount'=>$wallet,
                    'type'=>$import_type,
                    'balance'=>0,
                    'status'=>2,
                    'remit_at'=>'0000-00-00 00:00:00',
                    'bonus_type'=>$type,
                    'remark'=>'',
                    'commission_id'=>0,
                    'create_at'=>$curDate,
                    'update_at'=>$curDate,
                    'del'=>1,
                    'money_remark'=>'',
                    'after_balance'=>0,
                    'user_id'=>$loginUser->id,
                    'import_remark'=>trim($val[4]),
                    'import_ip'=>$ip,
                ];
                unset($data[$key]);
        }

        //事务
        $tr = Yii::$app->wholesalerDb->beginTransaction();

        foreach ($impotData as $index=>$item) {
            $storeid = $item['store_id'];
            $wallet = $item['amount'];
            $storeInfo = $storeModel::findOne($storeid);
            $oldWallet = $storeInfo->wallet;
            $impotData[$index]['balance'] = $oldWallet;
            if ($item['type'] == 3) {
                //奖金
                $after_wallet = $wallet + $oldWallet;
                $sql = "update store set wallet=wallet+{$wallet} where id={$storeid} limit 1";
            } else {
                //罚金
                $after_wallet = $oldWallet-$wallet;
                $sql = "update store set wallet=wallet-{$wallet} where id={$storeid} and wallet>={$wallet} limit 1";
                $impotData[$index]['amount'] = 0-$wallet;
            }
            $impotData[$index]['after_balance'] = $after_wallet;

            $effectRows = Yii::$app->wholesalerDb->createCommand($sql)->execute();//返回受影响行数
            if (!$effectRows) {
                $tr->rollBack();
                return ['code'=>1,'msg'=> '网络繁忙，请稍后再尝试！'];
            }
        }

        $table = 'wallet_record';
        $fields = ['store_id','record_number','amount','type','balance','status','remit_at','bonus_type','remark','commission_id','create_at','update_at','del','money_remark','after_balance','user_id','import_remark','import_ip'];
        $updateRes = Yii::$app->wholesalerDb->createCommand()->batchInsert($table, $fields, $impotData)->execute();

        if (!$updateRes) {
            $tr->rollBack();
            return ['code'=>1,'msg'=> '网络繁忙，请稍后再尝试！'];
        }
        $tr->commit();

        return [
            'code'=>0,
            'msg'=> '导入成功！',
            'data'=>['src'=>'']
        ];
    }

    /**
     * 获取导入明细列表
     * @return array
     */
    public function actionGetrecords()
    {
        $get = yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new \backend\models\WalletRecord();
        $storeModel = new \backend\models\Store();
        $userModel = new \backend\models\User();

        $page = isset($get['page'])? intval($get['page']) : 1;
        $limit = isset($get['limit'])? intval($get['limit']) : 20;

        $keys = isset($get['key'])? $get['key'] : [];
        $importDate =  isset($keys['importDate'])? $keys['importDate'] : '';
        $import_type =  isset($keys['import_type'])? intval($keys['import_type']) : 0;
        $userName =  isset($keys['userName'])? $keys['userName'] : '';
        $storeName =  isset($keys['storeName'])? $keys['storeName'] : '';

        $where = [
            'wallet_record.del'=>1
        ];
        $typeWhere = [
            'in','wallet_record.type',[3,4]
        ];
        if ($import_type) {
            $typeWhere = [];
            $where['wallet_record.type'] = $import_type;
        }

        $dateWhere = [];
        if ($importDate) {
            $dateStr = trim($importDate);
            $dateArr = explode('~', $dateStr);
            $start_at = $dateArr[0];
            $end_at = $dateArr[1];
            $dateWhere = [
                'and',
                ['>=', 'wallet_record.create_at', $start_at],
                ['<=', 'wallet_record.create_at', $end_at]
            ];
        }

        $importUserWhere = [];
        $userName = trim($userName);
        if ($userName) {
            $userSelect = 'id';
            $userWhere = [
                'like', 'username', $userName
            ];
            $userList = $userModel::find()->select($userSelect)->where($userWhere)->asArray()->all();
            if (!empty($userList)) {
                $userIds = [];
                foreach ($userList as $user) {
                    $userIds[] = $user['id'];
                }
                $importUserWhere = [
                    'in', 'wallet_record.user_id', $userIds
                ];
            } else {
                $res = ['code'=>0,'count'=>0,'data'=>[]];
                return $res;
            }
        }

        $importStoreWhere = [];
        $storeName = trim($storeName);
        if ($storeName) {
            $importStoreWhere = [
                'like', 'store.name', $storeName
            ];
        }
        $select = 'wallet_record.*,store.name';
        $conut = $model::find()->leftJoin('store','store.id=wallet_record.store_id')
                               ->where($dateWhere)->andWhere($importUserWhere)
                               ->andWhere($importStoreWhere)->andWhere($typeWhere)
                               ->andWhere($where)->count();
        $offset = $limit * ($page - 1);
        $data = $model::find()->select($select)->leftJoin('store','store.id=wallet_record.store_id')
                            ->where($dateWhere)->andWhere($importUserWhere)
                            ->andWhere($importStoreWhere)->andWhere($typeWhere)
                            ->andWhere($where)->orderBy('wallet_record.id desc')
                            ->limit($limit)->offset($offset)->asArray()->all();

        if ($data) {
            $importTypeNames = [
                '3'=>'奖金转入',
                '4'=>'罚金扣除'
            ];

            foreach ($data as $key=>$val) {
                $userInfo = $userModel::findOne($val['user_id']);
                $data[$key]['username'] = $userInfo->username;

                $type_msg = $val['type']==3? '+':'';

                $data[$key]['before_wallet'] = sprintf("%.2f", $val['balance'] / 100);
                $data[$key]['wallet'] = $type_msg . sprintf("%.2f", $val['amount'] / 100);
                $data[$key]['after_wallet'] = sprintf("%.2f", $val['after_balance'] / 100);
            }
        }
        $res = ['code'=>0,'count'=>$conut,'data'=>$data];
        return $res;
    }

    /**
     * 生成流水号
     * @param string $lable
     * @return string
     */
    public static function createNumber($type=3)
    {
        $num = mt_rand(10000,99999);
        if ($type == 3) {
            $lable = 'JJZR';
        } else if ($type == 4){
            $lable = 'FKKC';
        } else {
            $lable = '';
        }

        $record_number =  $lable . $num . substr(self::getMillisecond(), -11, 11);
        return $record_number;
    }

    /**
     * 获取毫秒级时间戳
     * @return float
     */
    public static function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }
}
