<?php
/**
 * Created by product.
 * User: xyy
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\store\v1;

use common\models\CommissionRecord;
use common\models\OrderProduct;
use framework\components\ToolsAbstract;
use framework\data\Pagination;
use message\order\commissionRecordListRes;
use message\order\recordReq;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Class orderList
 * @package service\resources\order\v1
 * 商家订单列表
 */
class commissionRecordList extends ResourceAbstract
{
    public function run($data)
    {
        /** @var recordReq $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();

        $page = $request->getPagination() ? $request->getPagination()->getPage() : 1;// 页码
        $pageSize = $request->getPagination() ? $request->getPagination()->getPageSize() : 5;// 每页条数

        // 查出佣金数据
        $recordInfo = CommissionRecord::find()->select(['order_id', 'TRUNCATE(amount/100,2) as amount', 'transfer_at'])
            ->where(['store_id' => $request->getStoreId(), 'status' => [2, 3], 'del' => 1])
            ->orderBy('transfer_at DESC')
            ->offset($pageSize * ($page - 1))->limit($pageSize);

        // 时间筛选
        if ($request->getStartDate() || $request->getEndDate()) {
            // 判断开始时间是否大于结束时间
            if ($request->getStartDate() > $request->getEndDate()) {
                Exception::throwException(Exception::STORE_TIME_ERROR);
            }

            // 时间补全
            $startDate = $request->getStartDate() . ' 00:00:00';
            $endDate = $request->getEndDate() . ' 23:59:59';

            $recordInfo->andWhere(['>=', 'transfer_at', $startDate])->andWhere(['<=', 'transfer_at', $endDate]);
        }

        // 分页
        $pagination = new Pagination();
        $pagination->setTotalCount($recordInfo->count());
        $pagination->setPageSize($pageSize);
        $pagination->setCurPage($page);

        $recordInfo = $recordInfo->asArray()->all();
        if (!empty($recordInfo)) {
            foreach ($recordInfo as $key => $value) {
                // 查询订单商品表order_product信息
                $orderProduct = OrderProduct::find()->select('images,name,deal_price,number,item_detail')->where(['order_id' => $value['order_id']])->asArray()->all();
                if (!empty($orderProduct)) {
                    foreach ($orderProduct as $k => $v) {
                        $recordInfo[$key]['product_info'][$k]['name'] = $v['name'] . implode('', json_decode($v['item_detail'], true));
                        $recordInfo[$key]['product_info'][$k]['number'] = $v['number'];
                        $recordInfo[$key]['product_info'][$k]['images'] = explode(';', $v['images']);
                        $recordInfo[$key]['product_info'][$k]['deal_price'] = $v['deal_price'] / 100;
                    }
                }
            }
        }

        $responseData['commission_info'] = $recordInfo;
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
        return new commissionRecordListRes();
    }
}