<?php
/**
 * Created by product.
 * User: xyy
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\store\v1;

use common\models\ArrivalBill;
use common\models\ArrivalBillDetail;
use common\models\ArrivalBillOrder;
use common\models\Order;
use common\models\OrderProduct;
use framework\components\ToolsAbstract;
use message\order\createArrivalBillSaveReq;
use message\order\createArrivalBillSaveRes;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Class createOrder
 * @package service\resources\order\v1
 * 新增到货单保存
 */
class createArrivalBillSave extends ResourceAbstract
{
    public function run($data)
    {
        /** @var createArrivalBillSaveReq $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();

        // 所有的商品id和商品确认到货数量
        if (empty($request->getSkuArr())) {
            Exception::throwException(Exception::STORE_CREATE_BILL_EMPTY);
        }


        $transaction = Order::getDb()->beginTransaction();// 开启事物 本身库
        $arrivalTotal = 0;// 实到总数量
        $shouldArrivalTotal = 0;// 应到总数量
        $orderNum = 0;// 订单总数量
        $arrivalArr = [];
        $mqOrderId = [];// 需要发到货消息的订单id
        // 循环查询出该商品未确认到货的订单
        foreach ($request->getSkuArr() as $item) {
            // 若是确认数量为0 则跳过
            if ($item->getArrivalNum() == 0) {
                continue;
            }
            // 查询sku商品的图片存储起来
            $sku_images = OrderProduct::findOne(['specification_id' => $item->getSkuId()]);
            $arrivalArrKey = $item->getSkuId() . '|' . $item->getSkuName() . '|' . $item->getArrivalNum() . '|' . $item->getShouldArrivalNum() . '|' . $sku_images->images;// 规格id + 商品名称 + 确认数量组成的key
            $arrivalArr[$arrivalArrKey] = []; // 此sku已确认到货的订单id json格式
            $order_num = 0; // 	此sku的到货订单数量
            $specification_id = $item->getSkuId();// 规格id
            $arrivalNumberBse = $item->getArrivalNum();// 实到数量
            $shouldArrivalNumberBse = $item->getShouldArrivalNum();// 应到数量
            // 应到货数量永远不能为空
            if ($shouldArrivalNumberBse <= 0) {
                $transaction->rollBack();
                Exception::throwException(Exception::STORE_SHOULD_ARRIVAL_NOT_ZERO);
            }
            $arrivalNumber = $arrivalNumberBse;// 实到商品数量递减的数据
            $arrivalTotal += $arrivalNumberBse;// 实到总数量
            $shouldArrivalTotal += $shouldArrivalNumberBse;// 实到总数量
            $orderAll = OrderProduct::find()->select('p.order_id,SUM(p.number) AS arrival_num')->alias('p')
                ->leftJoin(['o' => Order::tableName()], 'p.order_id = o.id')
                ->where(['p.specification_id' => $specification_id, 'o.status' => 3])
                ->andWhere(['o.store_id' => $request->getStoreId()])
                ->groupBy('p.order_id')
                ->orderBy('o.create_at ASC');
//            Tools::log($orderAll->createCommand()->getRawSql(), 'orderAll.log');
            $orderAll = $orderAll->asArray()->all();
            if (empty($orderAll)) {
                $transaction->rollBack();
                Exception::throwException(Exception::STORE_ARRIVAL_ORDER_EMPTY);
            }

            foreach ($orderAll as $key => $value) {
                // 判断商品数量是否足够 足够则直接改状态为已到货
                if ($arrivalNumber > 0) {
                    $order = Order::findOne(['id' => $value['order_id']]);
                    $order->status = 4;// 已到货
                    $order->update_at = date('Y-m-d H:i:s');// 已到货
                    if (!$order->save()) {
                        $transaction->rollBack();
                        Exception::throwException(Exception::STORE_ORDER_STATUS_FAIL);
                    }
                    $arrivalNumber -= $value['arrival_num'];// 数量更改
                    $arrivalArr[$arrivalArrKey][$key]['order_id'] = $value['order_id'];// 已经到货的订单id保存
                    $mqOrderId[] = $value['order_id'];// 需要发到货消息的订单id
                    $arrivalArr[$arrivalArrKey][$key]['number'] = $value['arrival_num'];// 已经到货的订单数量保存
                    $order_num++;// 此sku的到货订单数量
                }
            }
            $orderNum += $order_num;
        }

//        Tools::log($arrivalArr, 'arrivalArr.log');

        // 若是到货单所有商品均为0 则不能到货
        if ($arrivalTotal == 0) {
            $transaction->rollBack();
            Exception::throwException(Exception::STORE_NOT_ALL_ZERO);
        }

        // 第一步 生成一张到货单
        $arrivalBill = new ArrivalBill();
        $arrivalBill->store_id = $request->getStoreId();// 店铺的id
        $arrivalBill->arrival_code = $this->createArrivalCode();
        $arrivalBill->sku_num = count($request->getSkuArr());// 到货商品种类
        $arrivalBill->arrival_total = $arrivalTotal;
        $arrivalBill->should_arrival_total = $shouldArrivalTotal;
        $arrivalBill->order_num = $orderNum;
        $arrivalBill->remark = $request->getRemark();
        $arrivalBill->create_at = date('Y-m-d H:i:s');
        $arrivalBill->del = 1;
        if (!$arrivalBill->validate() || !$arrivalBill->save()) {
            $transaction->rollBack();
            Tools::log($arrivalBill->errors, 'createArrivalBillSaveError.log');
            Exception::throwException(Exception::STORE_SAVE_BILL_DETAIL_FAIL);
        }


        // 第二步 保存该商品的到货单详情
        foreach ($arrivalArr as $k2 => $v2) {
            $skuInfo = explode('|', $k2);
            $sku_id = $skuInfo[0];// 商品id
            $sku_name = $skuInfo[1];// 商品名称
            $arrival_num = $skuInfo[2];// 确认数量
            $shouldArrival_num = $skuInfo[3];// 应到数量
            $images = $skuInfo[4];// sku的图片
            $arrivalBillDetail = new ArrivalBillDetail();
            $arrivalBillDetail->arrival_id = $arrivalBill->attributes['id'];
            $arrivalBillDetail->sku_id = $sku_id;
            $arrivalBillDetail->sku_name = $sku_name;
            $arrivalBillDetail->images = $images;
            $arrivalBillDetail->arrival_num = $arrival_num;
            $arrivalBillDetail->should_arrival_num = $shouldArrival_num;
            if (!$arrivalBillDetail->validate() || !$arrivalBillDetail->save()) {
                Tools::log($arrivalBillDetail->errors, 'createArrivalBillDetailSaveError.log');
                $transaction->rollBack();
                Exception::throwException(Exception::STORE_SAVE_BILL_DETAIL_FAIL);
            }

            // 第三步插入arrival_bill_order数据
            foreach ($v2 as $v3) {
                $arrivalBillOrder = new ArrivalBillOrder();
                $arrivalBillOrder->arrival_id = $arrivalBill->attributes['id'];
                $arrivalBillOrder->arrival_detail_id = $arrivalBillDetail->attributes['id'];
                $arrivalBillOrder->order_id = $v3['order_id'];
                $arrivalBillOrder->number = $v3['number'];
                if (!$arrivalBillOrder->validate() || !$arrivalBillOrder->save()) {
                    $transaction->rollBack();
                    Exception::throwException(Exception::STORE_ORDER_STATUS_FAIL);
                }
            }
        }

        $transaction->commit();// 提交事物

        $rabbitMq = ToolsAbstract::getRabbitMq();
//        //根据order_id 发送模板消息
//        $mqData = [
//            'route' => 'taskOrder.orderReceiveNotifyProcess',
//            'params' => [
//                'order_id' => $mqOrderId
//            ],
//        ];
//        $rabbitMq->publish($mqData);

//        Tools::log($mqData, 'ArrivalBillSaveMqData.log');

        // 返回数据
        $result = [
            'id' => $arrivalBill->attributes['id'],
            'arrival_code' => $arrivalBill->attributes['arrival_code'],
        ];


        $response->setFrom(ToolsAbstract::pb_array_filter($result));
        return $response;
    }

    public static function request()
    {
        return new createArrivalBillSaveReq();
    }

    public static function response()
    {
        return new createArrivalBillSaveRes();
    }

    // 生成到货单编码方法
    private function createArrivalCode()
    {
        $repeat = true;
        while ($repeat) {
            $arrivalCode = 'SH' . date('Ymd') . rand(10000000, 99999999);
            // 查询是否重复
            $hasRepeat = ArrivalBill::findOne(['arrival_code' => $arrivalCode]);
            if (!$hasRepeat) {
                $repeat = false;
                return $arrivalCode;
            }
        }
        return false;
    }
}