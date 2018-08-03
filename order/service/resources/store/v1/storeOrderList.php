<?php
/**
 * Created by product.
 * User: xyy
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\store\v1;

use common\models\Order;
use common\models\OrderAddress;
use common\models\OrderProduct;
use framework\components\ToolsAbstract;
use framework\data\Pagination;
use message\order\arrivalBillDetailReq;
use message\order\storeOrderListReq;
use message\order\storeOrderListRes;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Class orderList
 * @package service\resources\order\v1
 * 商家订单列表
 */
class storeOrderList extends ResourceAbstract
{
    public function run($data)
    {
        /** @var arrivalBillDetailReq $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();

        $page = $request->getPagination() ? $request->getPagination()->getPage() : 1;// 页码
        $pageSize = $request->getPagination() ? $request->getPagination()->getPageSize() : 5;// 每页条数

        // 查出订单数据
        $orderInfo = Order::find()
            ->select('o.id,o.order_number,o.status,o.amount,o.create_at,p.name as product_name,p.number as product_number,p.item_detail,a.name,a.phone')
            ->alias('o')
            ->leftJoin(['p' => OrderProduct::tableName()], 'o.id = p.order_id')
            ->leftJoin(['a' => OrderAddress::tableName()], 'o.id = a.order_id')
            ->where(['o.store_id' => $request->getStoreId(), 'o.del' => Order::NOT_DELETED])
            ->orderBy('o.create_at DESC')
            ->offset($pageSize * ($page - 1))->limit($pageSize);

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

        // 订单状态筛选
        if (empty($request->getStatus())) {
            $orderInfo->andWhere(['in', 'o.status', [Order::STATUS_PAID, Order::STATUS_DELIVERED, Order::STATUS_ARRIVED, Order::STATUS_CONFIRMED]]);
        } else {
            $orderInfo->andWhere(['o.status' => $request->getStatus()]);
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
        return new storeOrderListReq();
    }

    public static function response()
    {
        return new storeOrderListRes();
    }
}