<?php

/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/10/13
 * Time: 14:43
 */

namespace service\tasks\order;

use common\models\order\CommissionRecord;
use common\models\order\Order;
use common\models\wholesaler\WalletRecord;
use common\models\wholesaler\Store;
use framework\components\ToolsAbstract;
use service\tasks\TaskService;

/**
 * @see MQAbstract::MSG_GROUP_SUB_PRODUCT_UPDATE
 * @package service\mq_processor\product
 * 自动核销二天前的到货订单
 */
class AutoArrivalOrder extends TaskService
{
    /**
     * @inheritdoc
     */
    public function run($data)
    {
        //筛选二天前已到货并有佣金的订单
        $twoDaysAgo = date('Y-m-d 23:59:59', strtotime('-2 days'));
        $orders = Order::find()->select('o.id,o.store_id,c.id as commission_id,c.amount')->alias('o')
            ->leftJoin(['c' => CommissionRecord::tableName()], 'o.id = c.order_id')
            ->where(['o.status' => Order::STATUS_ARRIVED, 'o.del' => 1])
            ->andWhere(['<=', 'o.deliver_at', $twoDaysAgo])
            ->andWhere(['c.status' => 2])
            ->asArray()->all();

        // 查询出所有未核销的订单
        $orderAll = Order::find()->select('id')
            ->where(['status' => Order::STATUS_ARRIVED, 'del' => 1])
            ->andWhere(['<=', 'deliver_at', $twoDaysAgo])
            ->column();
        if (empty($orders) && empty($orderAll)) {
            return true;
        }

        // 组合要更新的数据
        $orderIds = [];// 订单数组
        $commissionIds = [];// 订单佣金数组
        $storeUpdate = [];// 更新钱包信息使用
        $walletStr = '';// 插入钱包流水的sql字符串
        $nowDate = date('Y-m-d H:i:s');
        foreach ($orders as $item) {
            $orderIds[] = $item['id'];
            $commissionIds[] = $item['commission_id'];
            if (isset($storeUpdate[$item['store_id']]['wallet'])) {
                $storeUpdate[$item['store_id']]['wallet'] += $item['amount'];
            } else {
                $storeUpdate[$item['store_id']]['wallet'] = $item['amount'];
            }
            // 查询出佣金转入前该商户的钱包余额
            $storeInfo = Store::findOne(['id' => $item['store_id']]);
            $record_number = WalletRecord::recordNumber();
            $walletStr = $walletStr . "(" . $item['store_id'] . ",'" . $record_number . "'," . $item['amount'] . ",1," . $storeInfo->wallet . ",
            0,'" . $nowDate . "'," . $item['commission_id'] . ",'" . $nowDate . "','" . $nowDate . "',1,'系统定时任务自动核销" . $twoDaysAgo . "前的订单',
                '" . ($storeInfo->wallet + $item['amount']) . "'),";
        }
        $walletStr = rtrim($walletStr, ',');

        // 和所有未核销的订单 求并集
        $orderIds = array_unique(array_merge($orderIds, $orderAll));

        ToolsAbstract::log($orders, 'AutoArrivalOrderParams.log');
        ToolsAbstract::log($orderAll, 'AutoArrivalOrderParams.log');
        ToolsAbstract::log($commissionIds, 'AutoArrivalOrderParams.log');
        ToolsAbstract::log($storeUpdate, 'AutoArrivalOrderParams.log');
        $walletSql = "INSERT INTO `wallet_record` (`store_id`,`record_number`,`amount`,`type`,`balance`,`status`,`remit_at`,`commission_id`,`create_at`,`update_at`,
`del`,`money_remark`,`after_balance`) VALUES " . $walletStr . ";";
        ToolsAbstract::log($walletSql, 'AutoArrivalOrderParams.log');
        ToolsAbstract::log($nowDate, 'AutoArrivalOrderParams.log');

        // 使用事物 保证数据的正确性
        $transaction = Order::getDb()->beginTransaction();

        // 更新订单表数据
        $orderRes = Order::updateAll(['status' => 5], ['id' => $orderIds]);
        if (!$orderRes) {
            $transaction->rollBack();
            ToolsAbstract::log('AutoArrivalOrder-Order-update-fail-' . $orderRes, 'AutoArrivalOrder.log');
            return true;
        }

        // 更新订单佣金表commission_record数据
        if (!empty($commissionIds)) {
            $commissionRes = CommissionRecord::updateAll(['status' => 3, 'transfer_at' => $nowDate], ['id' => $commissionIds]);
            if (!$commissionRes) {
                $transaction->rollBack();
                ToolsAbstract::log('AutoArrivalOrder-CommissionRecord-update-fail-' . $commissionRes, 'AutoArrivalOrder.log');
                return true;
            }
        }

        // 插入钱包流水
        if ($walletStr) {
            $walletSql = "INSERT INTO `wallet_record` (`store_id`,`record_number`,`amount`,`type`,`balance`,`status`,`remit_at`,`commission_id`,`create_at`,`update_at`,
`del`,`money_remark`,`after_balance`) VALUES " . $walletStr . ";";

            $walletRes = WalletRecord::getDb()->createCommand($walletSql)->execute();
            if (!$walletRes) {
                $transaction->rollBack();
                ToolsAbstract::log('AutoArrivalOrder-WalletRecord-insert-fail-' . $walletRes, 'AutoArrivalOrder.log');
                return true;
            }
        }

        // 更新店铺钱包余额
        foreach ($storeUpdate as $key => $value) {
            $store = Store::findOne(['id' => $key]);
            if (!$store) {
                $transaction->rollBack();
                ToolsAbstract::log('AutoArrivalOrder-Store-update-fail', 'AutoArrivalOrder.log');
            }
            $store->wallet += $value['wallet'];
            if (!$store->save(false)) {
                $transaction->rollBack();
                ToolsAbstract::log($store->errors, 'AutoArrivalOrder.log');
            }
        }
        // 提交事物
        $transaction->commit();

        return $orderIds;
    }
}