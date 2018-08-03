<?php

namespace service\resources\pintuan\v1;

use common\models\Pintuan;
use common\models\PintuanActivity;
use common\models\PintuanUser;
use message\product\PintuanStartReq;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\product\formatProduct;
use service\tools\Tools;

/**
 * Created by PhpStorm.
 * User: xiayiyong
 * Date: 2018/06
 * Time: 15:09
 * purpose: 开团
 */
class PintuanStart extends ResourceAbstract
{
    public function run($data)
    {
        /** @var PintuanStartReq $request */
        $request = self::request();
        $request->parseFromString($data);
        $response = self::response();

        // 根据自提点id查询自定义的自提点的活动id
        /**@var  PintuanActivity $activityModel * */
        $activityModel = PintuanActivity::find()->where(['id' => $request->getPintuanActivityId(), 'status' => 1, 'del' => 1])->andWhere(['<=', 'start_time', date('Y-m-d H:i:s')])->andWhere(['>', 'end_time', date('Y-m-d H:i:s')])->one();
        if (!$activityModel) {
            Exception::throwException(Exception::PINTUAN_ACT_NOT_FIND);
        }

        // 插入pintuan数据表
        $pintuan = new Pintuan();
        $pintuan->pintuan_activity_id = $activityModel->id;
        $pintuan->create_user_id = $request->getUserId();
        $pintuan->member_num = 1;
        $pintuan->create_at = date('Y-m-d H:i:s');
        $pintuan->status = 2;// 开团均是无效团
        $pintuan->del = 1;

        $endTime = date('Y-m-d H:i:s',time() + 24 * 3600);
        if(strtotime($endTime) > strtotime($activityModel->end_time)){
            $endTime = $activityModel->end_time;
        }
        $pintuan->end_time = $endTime;

        if (!$pintuan->validate() || !$pintuan->save()) {
            Exception::throwException(Exception::PINTUAN_CREATE_FAILURE);
        }

        // 同时写入pintuan_product.pintuan_user表中实际参团人的数据
        $productPintuanUser = new PintuanUser();
        $productPintuanUser->pintuan_id = $pintuan->attributes['id'];
        $productPintuanUser->user_id = $pintuan->attributes['create_user_id'];
        $productPintuanUser->nick_name = !empty($request->getNickName()) ? $request->getNickName() : '';
        $productPintuanUser->avatar_url = !empty($request->getAvatarUrl()) ? $request->getAvatarUrl() : '';
        $productPintuanUser->created_at = $pintuan->attributes['create_at'];
        if (!$productPintuanUser->validate() || !$productPintuanUser->save()) {
            Tools::log($productPintuanUser->errors, 'productPintuanUser.log');
            Exception::throwException(Exception::PINTUAN_CREATE_USER_FAILURE);
        }

        // 不插入拼团定时任务表 等支付回调修改团状态为有效时候才插入定时任务表pintuan_task

        // 调用商品接口查询商品详情
        $product = (new formatProduct($activityModel->product_id))->getBasic()->getData();
        if (!$product) {
            Exception::throwException(Exception::PRODUCT_NOT_FIND);
        }
        $response->setFrom($pintuan->toArray());
        return $response;
    }

    public static function request()
    {
        return new PintuanStartReq();
    }

    public static function response()
    {
        return new \message\product\Pintuan();
    }
}