<?php
/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/15
 * Time: 16:46
 */

namespace service\controllers\order;

use framework\ApiAbstract;
use framework\Tool;
use framework\validParam;
use message\order\getShareCouponResponse;
use message\product\Pintuan;
use service\callService\order\getShareCouponCountProxy;
use service\callService\product\PintuanDetailBriefProxy;

/**
 * Class orderList
 */
class getShareCouponCount extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);
        $result = new getShareCouponResponse();
        $request = [
            'user_id' => $this->_request['user_id'],
            'pintuan_id' => $this->_request['pintuan_id'],
        ];
        Tool::log($request, 'getShareCouponCount.log');
        /** @var Pintuan $pintuan */
        $pintuan = (new PintuanDetailBriefProxy($request))->sendRequest();
        Tool::log($pintuan->toArray(), 'getShareCouponCount.log');
        //拼团没成团，或者用户没有参与此拼团，则不能领取优惠券
        if ($pintuan->getBecomeGroupStatus() != 1 || $pintuan->getJoinThisPintuan() != 1) {
            $result->setCount(0);
            return $result->toArray();
        }

        //判断可领优惠券数量
        $result = (new getShareCouponCountProxy($request))->sendRequest()->toArray();

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