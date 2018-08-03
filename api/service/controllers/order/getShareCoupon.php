<?php
/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/15
 * Time: 16:46
 */

namespace service\controllers\order;

use framework\ApiAbstract;
use framework\Exception;
use framework\Tool;
use framework\validParam;
use message\product\Pintuan;
use service\callService\order\CouponListProxy;
use service\callService\order\getNewUserCouponProxy;
use service\callService\order\getShareCouponCountProxy;
use service\callService\order\getShareCouponProxy;
use service\callService\product\PintuanDetailBriefProxy;

/**
 * Class getShareCoupon
 * 获取分享优惠券
 */
class getShareCoupon extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);

        $request = [
            'user_id' => $this->_request['user_id'],
            'pintuan_id' => $this->_request['pintuan_id'],
        ];
        //判断拼团有没有成团
        /** @var Pintuan $pintuan */
        $pintuan = (new PintuanDetailBriefProxy($request))->sendRequest();
        Tool::log($request,'getShareCoupon.log');
        Tool::log($pintuan->toArray(),'getShareCoupon.log');
        //拼团没成团，或者用户没有参与此拼团，则不能领取优惠券
        if ($pintuan->getBecomeGroupStatus() != 1 || $pintuan->getJoinThisPintuan() != 1) {
            Exception::throwException(Exception::PINTUAN_NOT_BECOME_GROUP);
        }

        //判断可领优惠券数量
        $result = (new getShareCouponProxy($request))->sendRequest()->toArray();

        return $result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['pintuan_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}