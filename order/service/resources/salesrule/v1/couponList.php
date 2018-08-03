<?php
/**
 * Created by Jason.
 * Author: Jason Y. Wang
 * Date: 2016/3/3
 * Time: 16:26
 */

namespace service\resources\salesrule\v1;

use common\models\SalesRule;
use common\models\SalesRuleUserCoupon;
use message\common\Coupon;
use message\common\Pagination;
use message\order\getUserCouponListRequest;
use message\order\getUserCouponListResponse;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\Tools;

/**
 * Author: Jason Y. Wang
 * Class orderNumber
 * @package service\resources\order\v1
 * 用户优惠券列表
 */
class couponList extends ResourceAbstract
{
    /** @var getUserCouponListRequest $request */
    protected $request;

    public function run($data)
    {
        $this->doInit($data);
        $response = self::response();

        $list_type = $this->request->getListType() ?: 1;
        $user_id = $this->request->getUserId();
        $page = $this->request->getPage() ?: 1;
        $pageSize = $this->request->getPageSize() ?: 20;

        if (!$user_id) {
            Exception::throwException(Exception::INVALID_PARAM);
        }

        $coupons = SalesRuleUserCoupon::find()->joinWith('rule')
            ->where(['user_id' => $user_id]);
        //1:可用  2:已使用 3:已失效
        $tag = 1; //可适用
        switch ($list_type) {
            case 1:
                $coupons->andWhere(['state' => SalesRuleUserCoupon::USER_COUPON_UNUSED])
                    ->andWhere(['>', 'expiration_date', Tools::getDate()]);
                break;
            case 2:
                $coupons->andWhere(['state' => SalesRuleUserCoupon::USER_COUPON_USED]);
                $tag = 2; //不可使用
                break;
            case 3:
                $coupons->andWhere(['state' => SalesRuleUserCoupon::USER_COUPON_UNUSED])
                    ->andWhere(['<', 'expiration_date', Tools::getDate()]);
                $tag = 2; //不可使用
                break;
            default:
                $coupons->andWhere(['state' => SalesRuleUserCoupon::USER_COUPON_UNUSED])
                    ->andWhere(['>', 'expiration_date', Tools::getDate()]);
                break;
        };

        $totalCount = $coupons->count();
        $coupon_list = $coupons->offset($pageSize * ($page - 1))->limit($pageSize)->all();

        /** @var SalesRuleUserCoupon $coupon */
        foreach ($coupon_list as $coupon) {
            $couponData = Tools::formatCoupon($coupon, $tag);
            $response->appendCouponList($couponData);
        }

        $pages = new \framework\data\Pagination;
        $pages->setTotalCount($totalCount);
        $pages->setPageSize($pageSize);
        $pages->setCurPage($page);
        $pagePb = new Pagination();
        $pagePb->setTotalCount($pages->getTotalCount());
        $pagePb->setPage($pages->getCurPage());
        $pagePb->setPageSize($pages->getPageSize());
        $pagePb->setLastPage($pages->getLastPageNumber());

        $response->setPages($pagePb);

        return $response;
    }

    public static function request()
    {
        return new getUserCouponListRequest();
    }

    public static function response()
    {
        return new getUserCouponListResponse();
    }

}
