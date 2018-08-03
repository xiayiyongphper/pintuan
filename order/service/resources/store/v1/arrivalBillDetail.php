<?php
/**
 * Created by product.
 * User: xyy
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\store\v1;

use common\models\ArrivalBill;
use common\models\ArrivalBillDetail as ArrivalBillDetailModel;
use common\models\ArrivalBillOrder;
use common\models\CommissionRecord;
use common\models\Order;
use common\models\OrderAddress;
use framework\components\ToolsAbstract;
use message\order\arrivalBillDetailReq;
use message\order\arrivalBillDetailRes;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use yii\helpers\ArrayHelper;

/**
 * Class createOrder
 * @package service\resources\order\v1
 * 到货单详情
 */
class arrivalBillDetail extends ResourceAbstract
{
    public function run($data)
    {
        /** @var arrivalBillDetailReq $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();

        // 判断是否存在该到货单数据
        /**@var ArrivalBill $arrivalInfo * */
        $arrivalInfo = ArrivalBill::find()->select('id,arrival_code,sku_num,should_arrival_total,arrival_total,order_num,remark,create_at')
            ->where(['id' => $request->getArrivalId(), 'store_id' => $request->getStoreId(), 'del' => 1])->one();
        if (!$arrivalInfo) {
            Exception::throwException(Exception::STORE_NOT_FOUND_BILL);
        }
        $responseData['arrival_bill'] = ArrayHelper::toArray($arrivalInfo);

        // 查询到货单详情
        $billDetail = ArrivalBillDetailModel::find()->select('sku_name,images,should_arrival_num,arrival_num')
            ->where(['arrival_id' => $arrivalInfo->id])
            ->andWhere(['>', 'arrival_num', 0])
            ->asArray()->all();
        if (empty($billDetail)) {
            Exception::throwException(Exception::STORE_NOT_FOUND_BILL);
        }
        // 图片以数组返回
        foreach ($billDetail as $key => $item) {
            $billDetail[$key]['images'] = explode(';', $item['images']);
        }
        $responseData['arrival_bill_detail'] = $billDetail;

        $response->setFrom(ToolsAbstract::pb_array_filter($responseData));
        return $response;
    }

    public static function request()
    {
        return new arrivalBillDetailReq();
    }

    public static function response()
    {
        return new arrivalBillDetailRes();
    }
}