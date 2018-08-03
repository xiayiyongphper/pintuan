<?php
/**
 * Created by product.
 * User: xyy
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\store\v1;

use common\models\ArrivalBill;
use common\models\ArrivalBillOrder;
use common\models\CommissionRecord;
use common\models\Order;
use common\models\OrderAddress;
use common\models\ArrivalBillDetail as ArrivalBillDetailModel;
use common\models\OrderProduct;
use framework\components\ToolsAbstract;
use framework\data\Pagination;
use message\order\arrivalBillDetailReq;
use message\order\arrivalOrderListReq;
use message\order\arrivalOrderListRes;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Class orderList
 * @package service\resources\order\v1
 * 商家订单列表
 */
class arrivalOrderList extends ResourceAbstract
{
    public function run($data)
    {
        /** @var arrivalBillDetailReq $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();

        $page = $request->getPagination() ? $request->getPagination()->getPage() : 1;// 页码
        $pageSize = $request->getPagination() ? $request->getPagination()->getPageSize() : 1000;// 每页条数

        // 判断是否存在该到货单数据
        /**@var ArrivalBill $arrivalInfo * */
        $arrivalInfo = ArrivalBill::find()->select('id,arrival_code,sku_num,should_arrival_total,arrival_total,order_num,remark,create_at')
            ->where(['id' => $request->getArrivalId(), 'store_id' => $request->getStoreId(), 'del' => 1])->one();
        if (!$arrivalInfo) {
            Exception::throwException(Exception::STORE_NOT_FOUND_BILL);
        }

        // 查询出订单id
        $orderIdAll = ArrivalBillOrder::find()->select('o.order_id')
            ->alias('o')
            ->leftJoin(['d' => ArrivalBillDetailModel::tableName()], 'o.arrival_detail_id = d.id')
            ->where(['o.arrival_id' => $arrivalInfo->id])
            ->andWhere(['>', 'd.arrival_num', 0])
            ->column();

        if (empty($orderIdAll)) {
            Exception::throwException(Exception::STORE_NOT_FOUND_BILL);
        }

        // 查出订单数据
        $orderInfo = Order::find()
            ->select('o.id,o.order_number,o.status,o.amount,o.create_at,p.name as product_name,p.number as product_number,p.item_detail,a.name,a.phone')
            ->alias('o')
            ->leftJoin(['p' => OrderProduct::tableName()], 'o.id = p.order_id')
            ->leftJoin(['a' => OrderAddress::tableName()], 'o.id = a.order_id')
            ->where(['o.id' => $orderIdAll, 'o.store_id' => $request->getStoreId(), 'o.del' => Order::NOT_DELETED])
            ->orderBy('o.create_at DESC')
            ->offset($pageSize * ($page - 1))->limit($pageSize);

        // 订单状态筛选
        if (empty($request->getStatus())) {
            $orderInfo->andWhere(['in', 'o.status', [Order::STATUS_ARRIVED, Order::STATUS_CONFIRMED]]);
        } else {
            $orderInfo->andWhere(['o.status' => $request->getStatus()]);
        }

        // 订单时间筛选
        if ($request->getStartDate() || $request->getEndDate()) {
            // 判断开始时间是否大于结束时间
            if ($request->getStartDate() > $request->getEndDate()) {
                Exception::throwException(Exception::STORE_TIME_ERROR);
            }

            // 时间补全
            $startDate = $request->getStartDate() . ' 00:00:00';
            $endDate = $request->getEndDate() . ' 23:59:59';

            $orderInfo->andWhere(['>=', 'o.create_at', $startDate])->andWhere(['<=', 'o.create_at', $endDate]);
        }

        // 订单号筛选 商品名称 客户姓名 手机号
        if ($request->getSearchAll()) {
            $orderInfo->andWhere([
                'or',
                ['like', 'o.order_number', $request->getSearchAll()],
                ['like', 'p.name', $request->getSearchAll()],
                ['like', 'a.name', $request->getSearchAll()],
                ['like', 'a.phone', $request->getSearchAll()],
            ]);
        }

        // 分页
        $pagination = new Pagination();
        $pagination->setTotalCount($orderInfo->count());
        $pagination->setPageSize($pageSize);
        $pagination->setCurPage($page);

        $orderInfo = $orderInfo->asArray()->all();
        if (!empty($orderInfo)) {
            foreach ($orderInfo as $key => $value) {
                // 查询出订单佣金
                $commissionRecord = CommissionRecord::find()->select('SUM(amount) as commission')->where(['order_id' => $value['id']])->asArray()->one();
                $orderInfo[$key]['commission'] = $commissionRecord ? $commissionRecord['commission'] : 0;

                // 组合商品信息
                $orderInfo[$key]['product_name'] = $value['product_name'] . '(' . implode('', json_decode($value['item_detail'], true)) . ')';
                $orderInfo[$key]['amount'] = $value['amount'] / 100;
                unset($orderInfo[$key]['item_detail']);
            }
        }
        $responseData['order_info'] = $orderInfo;
        $responseData['pagination'] = Tools::getPagination($pagination);


        $response->setFrom(ToolsAbstract::pb_array_filter($responseData));
        return $response;
    }

    public static function request()
    {
        return new arrivalOrderListReq();
    }

    public static function response()
    {
        return new arrivalOrderListRes();
    }
}