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
use message\store\StoreDetailReq;
use message\store\WalletInfoRes;
use service\resources\ResourceAbstract;

class walletInfo extends ResourceAbstract
{
    public function run($data)
    {
        /** @var StoreDetailReq $request */
        $request = self::parseRequest($data);
        $response = self::response();

        // 查询商家存入前的余额
        $store = Store::findOne(['id' => $request->getStoreId()]);
        $responseData['wallet'] = $store->wallet / 100;

        // 提现中
        $in_cash = WalletRecord::find()->select(['TRUNCATE(ABS(SUM(amount))/100,2) as in_cash'])
            ->where(['store_id' => $request->getStoreId(), 'type' => 2, 'status' => 1, 'del' => 1])->asArray()->one();
        $responseData['in_cash'] = $in_cash['in_cash'] ? $in_cash['in_cash'] : 0;

        // 累计提现
        $total_cash = WalletRecord::find()->select(['TRUNCATE(ABS(SUM(amount))/100,2) as total_cash'])
            ->where(['store_id' => $request->getStoreId(), 'type' => 2, 'status' => 2, 'del' => 1])->asArray()->one();
        $responseData['total_cash'] = $total_cash['total_cash'] ? $total_cash['total_cash'] : 0;

        $response->setFrom(ToolsAbstract::pb_array_filter($responseData));
        return $response;
    }


    public static function request()
    {
        return new StoreDetailReq();
    }

    public static function response()
    {
        return new WalletInfoRes();
    }

}