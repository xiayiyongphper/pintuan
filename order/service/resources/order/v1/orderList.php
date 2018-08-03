<?php
/**
 * Created by Jason.
 * Author: Jason Y. Wang
 * Date: 2016/3/3
 * Time: 16:26
 */

namespace service\resources\order\v1;

use common\models\Order;
use common\models\OrderProduct;
use framework\components\ToolsAbstract;
use message\common\Pagination;
use message\order\OrderListRequest;
use message\order\OrderListResponse;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Author: Jason Y. Wang
 * Class orderNumber
 * @package service\resources\order\v1
 */
class orderList extends ResourceAbstract
{
    const ALL_ORDER = 1;
    const UNPAID_ORDER = 2;
    const PENDING_DELIVERY = 3;
    const PENDING_ARRIVED = 4;
    const CONFIRMED_ARRIVED = 5;
    const TO_SHARE = 6;

    public function run($data)
    {
        $this->doInit($data);
        /** @var OrderListRequest $request */
        $request = $this->request;
        $response = self::response();
        $page = $request->getPage() ?: 1;
        $pageSize = $request->getPageSize() ?: 20;
        $list_type = $request->getListType() ?: 1;
        $orders = Order::find()->where(['user_id' => $request->getUserId()])
            ->andWhere(['del' => Order::NOT_DELETED])
            ->offset($pageSize * ($page - 1))->limit($pageSize)
            ->orderBy('id desc');
        switch ($list_type) {
            case orderList::ALL_ORDER:
                //全部订单
                break;
            case orderList::UNPAID_ORDER:
                //待付款订单
                $orders->andWhere(['status' => Order::STATUS_UNPAID]);
                break;
            case orderList::PENDING_DELIVERY:
                //待发货订单
                $orders->andWhere(['status' => Order::STATUS_PAID])
                    ->andWhere(['!=','enable_deliver_time','0000-00-00 00:00:00']);
                break;
            case orderList::PENDING_ARRIVED:
                //待收货订单
                $orders->andWhere(['status' => [Order::STATUS_DELIVERED, Order::STATUS_ARRIVED]]);
                break;
            case orderList::CONFIRMED_ARRIVED:
                //已确认收货订单
                $orders->andWhere(['status' => Order::STATUS_CONFIRMED]);
                break;
            case orderList::TO_SHARE:
                $orders->andWhere(['status' => Order::STATUS_PAID,'enable_deliver_time'=>'0000-00-00 00:00:00']);
                break;
            default:
                break;
        }

//        ToolsAbstract::log($orders->createCommand()->rawSql,'order_list.log');
        $totalCount = $orders->count();
        $orders = $orders->all();

        /** @var Order $order */
        foreach ($orders as $order) {
            $orderPb = new \message\common\Order();
            $orderPb->setId($order->id);
            $orderPb->setStatus($order->status);
            $orderPb->setAmount($order->amount / 100);
            $orderPb->setPayableAmount($order->payable_amount / 100);
            $orderPb->setOrderNumber($order->order_number);
            $orderPb->setCreateAt($order->create_at);
            $orderPb->setType($order->type);
            $orderPb->setPintuanActivityId($order->pintuan_activity_id);
            $orderPb->setStoreName($order->store_name);
            //状态标签
            switch ($order->status){
                case Order::STATUS_UNPAID:
                    $orderPb->setStatusLabel('待付款');
                    break;
                case Order::STATUS_PAID:
                    if($order->enable_deliver_time == '0000-00-00 00:00:00'){
                        $orderPb->setStatusLabel('待成团');
                    }else{
                        $orderPb->setStatusLabel('待发货');
                    }
                    break;
                case Order::STATUS_DELIVERED:
                    $orderPb->setStatusLabel('待收货');
                    break;
                case Order::STATUS_ARRIVED:
                    $orderPb->setStatusLabel('待收货');
                    break;
                case Order::STATUS_CONFIRMED:
                    $orderPb->setStatusLabel('已收货');
                    break;
                case Order::STATUS_CANCELED:
                    $orderPb->setStatusLabel('已取消');
                    break;
                default:
                    $orderPb->setStatusLabel('未知');
                    break;
            }
            //成团时间
            if(in_array($order->type,[2,3]) && $order->enable_deliver_time != '0000-00-00 00:00:00'){
                $orderPb->setPintuanFullTime($order->enable_deliver_time);
            }
            //待收货订单才显示收货码
            if ($list_type == orderList::PENDING_ARRIVED) {
                $orderPb->setPickCode($order->pick_code);
            }
            $orderProducts = OrderProduct::find()->where(['order_id' => $order->id])->all();
            $orderPb->setOrderProductNum(count($orderProducts));
            /** @var OrderProduct $orderProduct */
            foreach ($orderProducts as $orderProduct) {
                //ToolsAbstract::log($orderProduct,'order_list.log');
                $orderProductPb = new \message\common\OrderProduct();
                $orderProductPb->setProductId($orderProduct->product_id);
                $orderProductPb->setPintuanId($orderProduct->pintuan_id);
                $orderProductPb->setSpecificationId($orderProduct->specification_id);
                $orderProductPb->setProductNum($orderProduct->number);
                $orderProductPb->setName($orderProduct->name);
                $image = Tools::getFirstImage($orderProduct->images);
                $orderProductPb->setImage($image);
                $orderProductPb->setPrice($orderProduct->price / 100);
                $orderProductPb->setDealPrice($orderProduct->deal_price / 100);
                $specification = json_decode($orderProduct->item_detail, true);
                $specification_str = implode('、', $specification);
                $orderProductPb->setItemDetail($specification_str);
                $orderPb->appendOrderProduct($orderProductPb);
            }
            $response->appendOrder($orderPb);
        }
        $pages = new \framework\data\Pagination();
        $pages->setTotalCount($totalCount);
        $pages->setPageSize($pageSize);
        $pages->setCurPage($page);
        $pagePb = new Pagination();
        $pagePb->setTotalCount($pages->getTotalCount());
        $pagePb->setPage($pages->getCurPage());
        $pagePb->setPageSize($pages->getPageSize());
        $pagePb->setLastPage($pages->getLastPageNumber());

        $response->setPages($pagePb);

        return $response;
    }

    public static function request()
    {
        return new OrderListRequest();
    }

    public static function response()
    {
        return new OrderListResponse();
    }

}
