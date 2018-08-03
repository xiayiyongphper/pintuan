<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 18:31
 */

namespace service\resources\store\v1;

use common\models\WalletRecord;
use common\models\Store;
use framework\components\ToolsAbstract;
use message\store\WalletRecordRes;
use message\store\WalletRecord as WalletRecordReq;
use service\resources\ResourceAbstract;
use service\resources\StoreException;
use service\tools\Tools;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class walletRecordSave extends ResourceAbstract
{
    public function run($data)
    {
        /** @var WalletRecordReq $request */
        $request = self::parseRequest($data);
        $response = self::response();

        $walletRecord = new WalletRecord();
        $walletRecord->store_id = $request->getStoreId();
        $walletRecord->record_number = WalletRecord::recordNumber();
        $walletRecord->amount = $request->getAmount();
        $walletRecord->type = $request->getType();
        // 查询商家存入前的余额
        $store = Store::findOne(['id' => $request->getStoreId()]);
        $walletRecord->balance = $store->wallet;
        $walletRecord->after_balance = $store->wallet + $walletRecord->amount;
        if ($walletRecord->after_balance < 0) {
            StoreException::throwNewException(StoreException::WALLET_NOT_ENOUGH);
        }

        $walletRecord->status = $request->getStatus() ? $request->getStatus() : 0;
        $walletRecord->remark = $request->getRemark() ? $request->getRemark() : '';
        $walletRecord->commission_id = $request->getCommissionId() ? $request->getCommissionId() : 0;
        $walletRecord->create_at = date('Y-m-d H:i:s');
        $walletRecord->update_at = date('Y-m-d H:i:s');

        Tools::log($walletRecord->attributes, 'walletRecordSave.log');

        $trans = WalletRecord::getDb()->beginTransaction();
        try {
            if (!$walletRecord->save()) {
                Tools::log($walletRecord->errors, 'walletRecordSaveError.log');
                $trans->rollBack();
                StoreException::throwNewException(StoreException::WALLET_RECORD_SAVE_FAIL);
            }

            // 修改商家的钱包余额
            $store->wallet += $walletRecord->amount;
            // 判断余额是否够提现 不够则不允许提现
            if ($store->wallet < 0) {
                $trans->rollBack();
                StoreException::throwNewException(StoreException::WALLET_NOT_ENOUGH);
            }
            if (!$store->save(false)) {
                Tools::log($store->errors, 'storeWalletSaveError.log');
                $trans->rollBack();
                StoreException::throwNewException(StoreException::WALLET_CHANGE_ERROR);
            }
            $trans->commit();
        } catch (Exception $e) {
            Tools::log($e->getMessage(), 'walletSaveError.log');
            $trans->rollBack();
        }

        $responseData['wallet_record'] = ArrayHelper::toArray($walletRecord);

        $response->setFrom(ToolsAbstract::pb_array_filter($responseData));
        return $response;
    }


    public static function request()
    {
        return new WalletRecordReq();
    }

    public static function response()
    {
        return new WalletRecordRes();
    }

}