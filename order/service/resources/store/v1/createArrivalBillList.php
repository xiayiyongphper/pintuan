<?php
/**
 * Created by product.
 * User: xyy
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\store\v1;

use common\models\Order;
use common\models\OrderProduct;
use framework\components\ToolsAbstract;
use framework\data\Pagination;
use message\order\createArrivalBillListReq;
use message\order\createArrivalBillListRes;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Class createOrder
 * @package service\resources\order\v1
 * 新增到货单列表
 */
class createArrivalBillList extends ResourceAbstract
{
    public function run($data)
    {
        /** @var createArrivalBillListReq $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();
//        $page = $request->getPagination() ? $request->getPagination()->getPage() : 1;// 页码
//        $pageSize = $request->getPagination() ? $request->getPagination()->getPageSize() : 5;// 每页条数

        // 查询今天以前所有未到货的订单
        $zeroTime = date('Y-m-d 00:00:00');
        $skuArr = OrderProduct::find()->select('p.specification_id,p.images,p.name,p.item_detail,SUM(p.number) AS should_arrival_num')
            ->alias('p')->leftJoin(['o' => Order::tableName()], 'p.order_id = o.id')
            ->where(['o.status' => 3, 'o.del' => Order::NOT_DELETED])
            ->andWhere(['<=', 'o.create_at', $zeroTime])
            ->andWhere(['o.store_id' => $request->getStoreId()])
            ->groupBy('p.specification_id');
//            ->offset($pageSize * ($page - 1))->limit($pageSize);

//        Tools::log($skuArr->createCommand()->getRawSql(), 'createArrivalBillList.log');

//        $pagination = new Pagination();
//        $pagination->setTotalCount($skuArr->count());
//        $pagination->setPageSize($pageSize);
//        $pagination->setCurPage($page);

        $skuArr = $skuArr->asArray()->all();
        $result = [];
        if (count($skuArr) > 0) {
            foreach ($skuArr as $key => $item) {
                $result[$key]['sku_id'] = $item['specification_id'];
                $result[$key]['sku_name'] = $item['name'] . '(' . implode(',', json_decode($item['item_detail'], true)) . ')';
                $result[$key]['images'] = explode(';', $item['images']);
                $result[$key]['should_arrival_num'] = $item['should_arrival_num'];
                $result[$key]['arrival_num'] = $item['should_arrival_num'];
            }
        }


        $responseData['sku_arr'] = $result;
//        $responseData['pagination'] = Tools::getPagination($pagination);

        $response->setFrom(ToolsAbstract::pb_array_filter($responseData));
        return $response;
    }

    public static function request()
    {
        return new createArrivalBillListReq();
    }

    public static function response()
    {
        return new createArrivalBillListRes();
    }
}