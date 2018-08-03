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
use message\common\Coupon;
use message\order\OrderReviewResponse;
use message\store\Store;
use service\callService\order\OrderReviewProxy;
use service\callService\product\BuyItemsProxy;
use service\callService\store\GetStoreDetailProxy;

/**
 * Class productDetail
 */
class orderReview extends ApiAbstract
{
    const TYPE_NORMAL = 1;//普通购买
    const TYPE_JOIN_PINTUAN = 2;//参与拼团
    const TYPE_LAUNCH_PINTUAN = 3;//发起拼团

    public function run($params)
    {
        $this->doInit($params);

        if (!in_array($this->_request['type'], [self::TYPE_NORMAL, self::TYPE_JOIN_PINTUAN, self::TYPE_LAUNCH_PINTUAN])) {
            Exception::throwException(Exception::INVALID_PARAM);
        }
        foreach ($this->_request['products'] as $proItem) {
            if ($this->_request['type'] == self::TYPE_JOIN_PINTUAN) {
                if (empty($proItem['pintuan_id'])) {
                    Exception::throwException(Exception::INVALID_PARAM);
                }
            } elseif ($this->_request['type'] == self::TYPE_LAUNCH_PINTUAN) {
                if (empty($proItem['pintuan_activity_id'])) {
                    Exception::throwException(Exception::INVALID_PARAM);
                }
            }
        }

        $this->_result['type'] = $this->_request['type'];

        //获取商品信息
        foreach ($this->_request['products'] as $item) {
            if (empty($item['specification_id']) && empty($item['pintuan_id']) && empty($item['pintuan_activity_id'])) {
                Exception::throwException(Exception::SPECIFICATION_PINTUAN_NEED_ONE);
            }
        }

        $activityId = $this->getNewUserActivity();//新人活动ID
        $productParams = [
            'type' => $this->_request['type'],
            'items' => $this->_request['products'],
            'activity_id' => $activityId,
        ];

        if (!empty($this->_request['store_id'])) {
            $productParams['store_id'] = $this->_request['store_id'];
            $productParams['wholesaler_ids'] = $this->_wholesalerIds;
        }
        $buyItems = (new BuyItemsProxy($productParams))->sendRequest()->toArray();


        $orderReviewRequest = [
            'user_id' => $this->_request['user_id'],
            'items' => $buyItems['items'],
            'coupon_id' => $this->_request['coupon_id'],
            'include_new_user_product' => $buyItems['include_new_user_product'],
        ];

        Tool::log($orderReviewRequest, 'orderReview.log');

        /** @var OrderReviewResponse $orderReviewResponse */
        $orderReviewResponse = (new OrderReviewProxy($orderReviewRequest))->sendRequest();

        //计算金额
        $this->_result['amount'] = $orderReviewResponse->getAmount();
        $this->_result['discount_amount'] = $orderReviewResponse->getDiscountAmount();
        $this->_result['payment_amount'] = $orderReviewResponse->getPaymentAmount();
        $this->_result['available_coupons'] = [];
        /** @var Coupon $availableCoupon */
        foreach ($orderReviewResponse->getAvailableCoupons() as $availableCoupon) {
            $this->_result['available_coupons'][] = $availableCoupon->toArray();
        }
        $this->_result['unavailable_coupons'] = [];
        foreach ($orderReviewResponse->getUnavailableCoupons() as $unavailableCoupon) {
            $this->_result['unavailable_coupons'][] = $unavailableCoupon->toArray();
        }

        //返回给
        foreach ($buyItems['items'] as &$buy_item){
            $buy_item['price'] = $buy_item['price'] / 100;
            $buy_item['deal_price'] = $buy_item['deal_price'] / 100;
        }

        $this->_result['products'] = $buyItems['items'];


        if (!empty($this->_request['store_id'])) {
            $storeId = $this->_request['store_id'];
            //获取自提点信息
            /** @var Store $store */
            $store = (new GetStoreDetailProxy(['store_id' => $storeId]))->sendRequest();
            $this->_result['store_id'] = $store->getStoreId();
            $this->_result['store_name'] = $store->getStoreName();
            $this->_result['address'] = $store->getAddress() . $store->getDetailAddress();
        }
        Tool::log($this->_result, 'orderReview.log');
        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['store_id', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
                ['coupon_id', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
                ['type', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['products', validParam::CHECK_TYPE_REPEATED_REQUIRE, 'item'],
            ],
            'item' => [
                ['product_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['pintuan_activity_id', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
                ['specification_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['pintuan_id', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
                ['product_num', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
            ]
        ];
    }
}