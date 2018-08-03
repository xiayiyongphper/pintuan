<?php
/**
 * Created by product.
 * User: xyy
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\store\v1;

use common\models\CommissionRecord;
use common\models\Order;
use common\models\OrderProduct;
use framework\components\ToolsAbstract;
use message\order\storeSaleCountReq;
use message\order\storeSaleCountRes;
use service\resources\ResourceAbstract;

/**
 * Class createOrder
 * @package service\resources\order\v1
 */
class storeSaleCount extends ResourceAbstract
{
    public function run($data)
    {
        /** @var storeSaleCountReq $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();

        // 根据不同的统计维度进行统计
        $startDate = '';
        $endDate = '';
        switch ($request->getType()) {
            case 1:
                // 上周
                $startDate = date('Y-m-d 00:00:00', strtotime('last week monday', time()));//上一个有效周一
                $endDate = date('Y-m-d 23:59:59', strtotime('last week sunday', time()));//同样适用于其它星期
                break;
            case 2:
                // 本周
                $startDate = date('Y-m-d 00:00:00', strtotime('this week monday', time()));
                $endDate = date('Y-m-d 23:59:59', strtotime('this week sunday', time()));
                break;
            case 3:
                // 上月
                $startDate = date('Y-m-d 00:00:00', strtotime('-1 month', strtotime(date('Y-m', time()) . '-01 00:00:00'))); //本月一日直接strtotime上减一个月
                $endDate = date('Y-m-d 23:59:59', strtotime(date('Y-m', time()) . '-01 00:00:00') - 86400); //本月一日减一天即是上月最后一日
                break;
            case 4:
                // 本月
                $startDate = date('Y-m-d 00:00:00', strtotime(date('Y-m', time()) . '-01 00:00:00')); //直接以strtotime生成
                $endDate = date('Y-m-d 23:59:59', strtotime(date('Y-m', time()) . '-' . date('t', time()) . ' 00:00:00')); //t为当月天数,28至31天
                break;
            case 5:
                // 全部
                $startDate = '';
                $endDate = '';
                break;
        }

        $where = '';
        if ($startDate && $endDate) {
            $where = [
                'and',
                ['>=', 'o.create_at', $startDate],
                ['<=', 'o.create_at', $endDate],
            ];
        }


        // 订单数
        $orderTotal = Order::find()->select('o.id,o.create_at')
            ->addSelect(['TRUNCATE(o.payable_amount/100,2) as amount'])
            ->alias('o')
            ->leftJoin(['c' => CommissionRecord::tableName()], 'o.id = c.order_id')
            ->where(['o.store_id' => $request->getStoreId()])
            ->andWhere(['in', 'o.status', [Order::STATUS_PAID, Order::STATUS_DELIVERED, Order::STATUS_ARRIVED, Order::STATUS_CONFIRMED]])
            ->andWhere(['c.status' => [2, 3]])
            ->orderBy('o.create_at DESC')
            ->andWhere($where)->all();
        $responseData['order_count'] = count($orderTotal);
        $orderIds = [];
        $amount_count = 0;
        $firstDate = '';
        /**@var Order $item * */
        foreach ($orderTotal as $item) {
            $firstDate = $item->create_at;
            $orderIds[] = $item->id;
            $amount_count += $item->amount;
        }

        // 销售额
        $responseData['amount_count'] = $amount_count;

        // 商品数
        $product_count = OrderProduct::find()->select('SUM(number) as product_count')
            ->where(['order_id' => $orderIds, 'del' => 1])
            ->asArray()->one();
        $responseData['product_count'] = $product_count['product_count'] ? $product_count['product_count'] : 0;

        // 佣金
        $commission_count = CommissionRecord::find()->select(['TRUNCATE(SUM(amount)/100,2) as commission_count'])
            ->where(['order_id' => $orderIds, 'del' => 1])
            ->asArray()->one();
        $responseData['commission_count'] = $commission_count['commission_count'];

        // 时间返回
        if ($request->getType() == 5) {
            $responseData['start_date'] = $firstDate ? date('Y-m-d', strtotime($firstDate)) : date('Y-m-d');
            $responseData['end_date'] = date('Y-m-d');
        } else {
            $responseData['start_date'] = date('Y-m-d', strtotime($startDate));
            $responseData['end_date'] = date('Y-m-d', strtotime($endDate));
        }

        $response->setFrom(ToolsAbstract::pb_array_filter($responseData));
        return $response;
    }

    public static function request()
    {
        return new storeSaleCountReq();
    }

    public static function response()
    {
        return new storeSaleCountRes();
    }
}