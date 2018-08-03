<?php
/**
 * Created by product.
 * User: xyy
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\store\v1;

use common\models\ArrivalBill;
use framework\components\ToolsAbstract;
use framework\data\Pagination;
use message\order\arrivalBillListReq;
use message\order\arrivalBillListRes;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Class createOrder
 * @package service\resources\order\v1
 * 到货单列表(含筛选)
 */
class arrivalBillList extends ResourceAbstract
{
    public function run($data)
    {
        /** @var arrivalBillListReq $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();

        $page = $request->getPagination() ? $request->getPagination()->getPage() : 1;// 页码
        $pageSize = $request->getPagination() ? $request->getPagination()->getPageSize() : 5;// 每页条数

        // 条件
        $where = '';
        if ($request->getStartDate() || $request->getEndDate() || $request->getArrivalCode()) {
            // 判断开始时间是否大于结束时间
            if ($request->getStartDate() > $request->getEndDate()) {
                Exception::throwException(Exception::STORE_TIME_ERROR);
            }

            // 时间补全
            $startDate = $request->getStartDate() . ' 00:00:00';
            $endDate = $request->getEndDate() . ' 23:59:59';

            $where = [
                'and',
                ['>=', 'create_at', $startDate],
                ['<=', 'create_at', $endDate],
                ['like', 'arrival_code', $request->getArrivalCode()],
            ];
        }

        $arrivalList = ArrivalBill::find()->select('id,arrival_code,sku_num,should_arrival_total,arrival_total,order_num,remark,create_at')
            ->where($where)
            ->andWhere(['store_id' => $request->getStoreId()])
            ->andWhere(['>', 'arrival_total', 0])
            ->orderBy('create_at DESC')
            ->offset($pageSize * ($page - 1))->limit($pageSize);

        // 分页
        $pagination = new Pagination();
        $pagination->setTotalCount($arrivalList->count());
        $pagination->setPageSize($pageSize);
        $pagination->setCurPage($page);

        $arrivalList = $arrivalList->asArray()->all();

        $responseData['arrival_bill'] = $arrivalList;
        $responseData['pagination'] = Tools::getPagination($pagination);

        $response->setFrom(ToolsAbstract::pb_array_filter($responseData));
        return $response;
    }

    public static function request()
    {
        return new arrivalBillListReq();
    }

    public static function response()
    {
        return new arrivalBillListRes();
    }
}