<?php
/**
 * Created by Jason.
 * Author: Jason Y. Wang
 * Date: 2016/3/3
 * Time: 16:26
 */

namespace service\resources\order\v1;

use common\models\OrderProduct;
use framework\components\ToolsAbstract;
use message\order\orderAlreadyPinReq;
use message\order\orderAlreadyPinRes;
use service\resources\ResourceAbstract;

/**
 * Author: xyy
 * Class orderNumber
 * @package service\resources\order\v1
 */
class orderAlreadyPin extends ResourceAbstract
{
    public function run($data)
    {
        /** @var orderAlreadyPinReq $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();

        // 查询出拼团商品件数
        $number = OrderProduct::find()->select('SUM(number)  as total')->where(['pintuan_id' => $request->getPintuanId()])->asArray()->all();

        $response->setFrom(ToolsAbstract::pb_array_filter(['number' => isset($number[0]['total']) ? $number[0]['total'] : 0]));
        return $response;
    }

    public static function request()
    {
        return new orderAlreadyPinReq();
    }

    public static function response()
    {
        return new orderAlreadyPinRes();
    }

}
