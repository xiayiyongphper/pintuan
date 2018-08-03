<?php
/**
 * Created by product.
 * User: xyy
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\store\v1;

use common\models\WalletRecord;
use framework\components\ToolsAbstract;
use framework\data\Pagination;
use message\order\recordReq;
use message\store\WalletRecordListRes;
use service\resources\ResourceAbstract;
use service\resources\StoreException;
use service\tools\Tools;

/**
 * Class orderList
 * @package service\resources\order\v1
 */
class walletRecordList extends ResourceAbstract
{
    public function run($data)
    {
        /** @var recordReq $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();

        $page = $request->getPagination() ? $request->getPagination()->getPage() : 1;// 页码
        $pageSize = $request->getPagination() ? $request->getPagination()->getPageSize() : 5;// 每页条数

        // 查出钱包流水数据
        $recordInfo = WalletRecord::find()->select('record_number,bonus_type,import_remark,status,remark,create_at')
            ->addSelect(['TRUNCATE(ABS(amount)/100,2) as amount'])
            ->where(['store_id' => $request->getStoreId(), 'type' => $request->getType(), 'del' => 1])
            ->orderBy('create_at DESC')
            ->offset($pageSize * ($page - 1))->limit($pageSize);

        // 时间筛选
        if ($request->getStartDate() || $request->getEndDate()) {
            // 判断开始时间是否大于结束时间
            if ($request->getStartDate() > $request->getEndDate()) {
                StoreException::throwNewException(StoreException::TIME_ERROR);
            }

            // 时间补全
            $startDate = $request->getStartDate() . ' 00:00:00';
            $endDate = $request->getEndDate() . ' 23:59:59';

            $recordInfo->andWhere(['>=', 'create_at', $startDate])->andWhere(['<=', 'create_at', $endDate]);
        }

        // 分页
        $pagination = new Pagination();
        $pagination->setTotalCount($recordInfo->count());
        $pagination->setPageSize($pageSize);
        $pagination->setCurPage($page);

        $recordInfo = $recordInfo->asArray()->all();
        $responseData['wallet_info'] = $recordInfo;
        $responseData['pagination'] = Tools::getPagination($pagination);


        $response->setFrom(ToolsAbstract::pb_array_filter($responseData));
        return $response;
    }

    public static function request()
    {
        return new recordReq();
    }

    public static function response()
    {
        return new WalletRecordListRes();
    }
}