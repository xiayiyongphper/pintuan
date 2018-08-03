<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\order\v1;

use common\models\SalesRuleProduct;
use message\common\BuyItem;
use message\order\OrderReviewRequest;
use message\order\OrderReviewResponse;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\quote\Item;
use service\tools\quote\Quote;
use service\tools\Tools;

/**
 * Class orderReview
 * @package service\resources\order\v1
 */
class orderReview extends ResourceAbstract
{
    /** @var  OrderReviewRequest $request */
    protected $request;

    public function run($data)
    {
        $this->doInit($data);

        if (!$this->request->getItemsCount() || !$this->request->getUserId()) {
            Exception::throwException(Exception::INVALID_PARAM);
        }

        $user_id = $this->request->getUserId();
        $coupon_id = $this->request->getCouponId();

        $include_new_user_product = 0;
        foreach ($this->request->getItems() as $product) {
            if($product->getNewUserPrice()){
                $include_new_user_product = 1;
                break;
            }
        }

        $quote = new Quote();
        $quote->setUserId($user_id);
        $quote->setCouponId($coupon_id);
        $quote->setIncludeNewUserProduct($include_new_user_product);

        $product_ids = [];
        /** @var BuyItem $product */
        foreach ($this->request->getItems() as $product) {
            $item = new Item();
            $product_ids[] = $product->getProductId();
            $item->setDealPrice($product->getDealPrice());
            $item->setNumber($product->getProductNum());
            $quote->addItem($item);
        }

        $rule_ids = SalesRuleProduct::find()->select('rule_id')->where(['product_id' => $product_ids])->column();

        $quote->setRuleIds($rule_ids);

        $quote->init()->collectTotals();
        $response = self::response();
//        Tools::log($quote->getAmount(), 'orderReview.log');
//        Tools::log($quote->getPayableAmount(), 'orderReview.log');
//        Tools::log($quote->getDiscountAmount(), 'orderReview.log');
        $response->setAmount($quote->getAmount() / 100);
        $response->setPaymentAmount($quote->getPayableAmount() / 100);
        $response->setDiscountAmount($quote->getDiscountAmount() / 100);

//        Tools::log($response->toArray(), 'orderReview.log');

        foreach ($quote->getAvailableCoupons() as $available_coupon) {
            $response->appendAvailableCoupons($available_coupon);
        }

        foreach ($quote->getUnavailableCoupons() as $unavailable_coupon) {
            $response->appendUnavailableCoupons($unavailable_coupon);
        }
        return $response;
    }

    public static function request()
    {
        return new OrderReviewRequest();
    }

    public static function response()
    {
        return new OrderReviewResponse();
    }
}