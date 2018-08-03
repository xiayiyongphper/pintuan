<?php

namespace console\controllers;

use common\models\order\CommissionRecord;
use common\models\order\Order;
use common\models\wholesaler\Store;
use common\models\wholesaler\StoreCommission;
use framework\components\ToolsAbstract;
use yii\console\Controller;

/**
 * Site controller
 */
class IndexController extends Controller
{
    public function actionIndex()
    {
        $timeStamp = Tools::getDate()->timestamp();
        $yesterday = date('Y-m-d', strtotime('-1 day', $timeStamp));
        $today = Tools::getDate()->date('Y-m-d');
        print_r($yesterday);
        echo PHP_EOL;
        print_r($today);
    }

    public function actionCommissionModify()
    {
        $orders = Order::find()->where(['>', 'create_at', date('Y-m-d')])->andWhere(['status' => [2, 3, 4, 5]])->all();
        print_r(count($orders));
        exit();
        /** @var Order $order */
        foreach ($orders as $order) {
            //加入小店佣金统计
            $store = Store::findOne(['id' => $order->store_id]);
            if (!$store) {
                return;
            }

            $commission_id = $store->commission_id;
            $commission = StoreCommission::findOne(['id' => $commission_id]);
            if (!$commission) {
                return;
            }
            $payable_amount = $order->payable_amount;
            $commission_type = $commission->commission_type;
            $commission_val = $commission->commission_val;
            switch ($commission_type) {
                case 1:
                    $commission_amount = intval($payable_amount * $commission_val / 100);
                    break;
                case 2:
                    $commission_amount = $commission_val;
                    break;
                default:
                    $commission_amount = 0;
                    break;
            }

            $order_commission = CommissionRecord::find()->where(['order_id' => $order->id])->exists();
            if ($order_commission) {
                continue;
            }

            if ($commission_amount > 0) {
                $commissionRecord = new CommissionRecord();
                $commissionRecord->order_id = $order->id;
                $commissionRecord->store_id = $order->store_id;
                $commissionRecord->amount = $commission_amount;
                $commissionRecord->status = 2;//已获得
                $commissionRecord->create_at = ToolsAbstract::getDate();
                $commissionRecord->effect_at = ToolsAbstract::getDate();
                if (!$commissionRecord->save()) {
                    ToolsAbstract::log($commissionRecord->errors, 'orderPayProcess.log');
                }
            }
        }

    }
}
