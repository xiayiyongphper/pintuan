<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\product\v1;

use common\models\BuyChains;
use common\models\BuyChainsSpecification;
use common\models\BuyChainsStore;
use common\models\NewActProduct;
use common\models\Pintuan;
use common\models\PintuanActivity;
use common\models\PintuanActivitySpecification;
use message\product\BuyItem;
use message\product\BuyItemsReq;
use message\product\Items;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\formatProductModel;
use service\tools\Tools;

/**
 * Class getProductList
 * @package service\resources\product\v1
 */
class buyItemsBack extends ResourceAbstract
{
    /** @var  BuyItemsReq */
    protected $request;
    /** @var  Items */
    protected $response;
    public function run($data)
    {
        $this->doInit($data);
        $wholesalerIds = $this->request->getWholesalerIds();
        $storeId = $this->request->getStoreId();
        $type = $this->request->getType();

        $result = [];
        $specificationNumberMap = [];
        $specificationIds = [];
        $includeNewUserProduct = 0;
//        Tools::log($this->request->getItems(),'buy_items.log');
        /** @var BuyItem $buyItem */
        foreach ($this->request->getItems() as $buyItem) {
            $specificationId = $buyItem->getSpecificationId();
            $pintuanActivityId = 0;
            $pinPrice = 0;
            /** @var PintuanActivity $pintuanActivity */
            $pintuanActivity = null;
            /** @var BuyChains $buyChains */
            $buyChains = null;
            if ($type == 4) {
                if (empty($buyItem->getBuyChainsId())) {
                    Exception::throwException(Exception::INVALID_PARAM);
                }

                $buyChains = BuyChains::findOne(['id' => $buyItem->getBuyChainsId(), 'del' => 1]);
                if (!$buyChains) {
                    Exception::throwException(Exception::BUY_CHAINS_NOT_FIND);
                }
            }
            if ($type == 3) {
                if (empty($buyItem->getPintuanActivityId())) {
                    Exception::throwException(Exception::INVALID_PARAM);
                }

                $pintuanActivity = PintuanActivity::findOne(['id' => $buyItem->getPintuanActivityId(), 'del' => 1]);
                if (!$pintuanActivity) {
                    Exception::throwException(Exception::PINTUAN_ACT_NOT_FIND);
                }
            } elseif ($type == 2) {
                if (empty($buyItem->getPintuanId())) {
                    Exception::throwException(Exception::INVALID_PARAM);
                }

                $pintuan = Pintuan::findOne(['id' => $buyItem->getPintuanId(), 'del' => 1]);
                if (!$pintuan) {
                    Exception::throwException(Exception::PINTUAN_NOT_FIND);
                }
                if (strtotime($pintuan->end_time) < time()) {
                    Exception::throwException(Exception::PINTUAN_END);
                }

                $pintuanActivity = PintuanActivity::findOne(['id' => $pintuan->pintuan_activity_id, 'del' => 1]);
                if (!$pintuanActivity) {
                    Exception::throwException(Exception::PINTUAN_ACT_NOT_FIND);
                }
            } elseif ($type == 1) {
//                $specificationId = $buyItem->getSpecificationId();
            } else {
                Tools::logException(new \Exception("specification_id,pintuan_id,pintuan_activity_id不能同时为空"));
                Exception::throwException(Exception::INVALID_PARAM);
            }

            if ($pintuanActivity) {
                //校验活动是否结束
                if (strtotime($pintuanActivity->start_time) > time()) {
                    Exception::throwException(Exception::PINTUAN_NOT_START);
                }
                if (strtotime($pintuanActivity->end_time) < time()) {
                    Exception::throwException(Exception::PINTUAN_END);
                }

                //校验指定自提点拼团活动是否支持当前自提点
                if ($storeId) {
                    $flag = Tools::pintuanWhetherInDeliveryRange($storeId, $wholesalerIds, $pintuanActivity->id);
                    if (!$flag) {
                        Exception::throwException(Exception::PINTUAN_ACTIVITY_NOT_SUPPORT_CURRENT_STORE);
                    }
                }

//                $specificationId = $pintuanActivity->specification_id;
                $pintuanActivityId = $pintuanActivity->id;
                $pintuanActivitySpecification = PintuanActivitySpecification::findOne([
                    'pintuan_activity_id' => $pintuanActivityId,
                    'specification_id' => $specificationId,
                    'del' => 1,
                ]);
                if (!$pintuanActivitySpecification) {
                    Exception::throwException(Exception::SPECIFICATION_NOT_JOIN_PINTUAN);
                }

                $pinPrice = $pintuanActivitySpecification->pin_price;
            }

            //新人活动
            $actProduct = [];
            if ($this->request->getActivityId()) {
                $actProduct = NewActProduct::findAll([
                    'act_id' => $this->request->getActivityId(),
                    'product_id' => $buyItem->getProductId(),
                    'del' => NewActProduct::NOT_DELETED,
                ]);
                $actProduct = array_column($actProduct, null, 'spec_id');
            }

            $item = (new formatProductModel($buyItem->getProductId()))
                ->getBasic()
                ->getTopImage('180x180')
                ->getSpecificationInfo($specificationId)
                ->getSpecificationDesc($specificationId)
                ->getData();
//            Tools::log($item,'buy_items.log');

            if ($item['status'] != 1) {
                Exception::throwException(Exception::PRODUCT_OFFLINE);
            }

            //校验配送范围
            //Tools::log($wholesalerIds,'buy_items.log');
            if ($wholesalerIds && (!isset($item['wholesaler_id']) || !in_array($item['wholesaler_id'], $wholesalerIds))) {
                Exception::throwException(Exception::STORE_NOT_IN_DISTRIBUTE_RANGE);
            }

            if ($buyItem->getPintuanId()) {
                $item['pintuan_id'] = $buyItem->getPintuanId();
            }

            $item['specification_id'] = $specificationId;

            //拼团活动
            if ($pintuanActivity) {
                $item['pintuan_activity_id'] = $pintuanActivityId;
                $item['pintuan_price'] = $pinPrice;
                $item['deal_price'] = $pinPrice;
            }

            //接龙
            if ($buyChains) {
                //校验活动是否结束
                if (strtotime($buyChains->start_time) > time()) {
                    Exception::throwException(Exception::BUY_CHAINS_NOT_START);
                }
                if (strtotime($buyChains->end_time) < time()) {
                    Exception::throwException(Exception::BUY_CHAINS_END);
                }

                //校验指定自提点接龙是否支持当前自提点
                if ($storeId) {
                    if($buyChains->place_type == 2){//指定自提点的活动
                        $model = BuyChainsStore::find()
                            ->where(['buy_chains_id' => $buyChains->id,'store_id' => $storeId, 'del' => 1])
                            ->one();

                        if(!$model){
                            Exception::throwException(Exception::BUY_CHAINS_NOT_SUPPORT_CURRENT_STORE);
                        }
                    }else{
                        if(!in_array($buyChains->wholesaler_id,$wholesalerIds)){
                            Exception::throwException(Exception::BUY_CHAINS_NOT_SUPPORT_CURRENT_STORE);
                        }
                    }
                }

                $buyChainsSpecification = BuyChainsSpecification::findOne([
                    'buy_chains_id' => $buyChains->id,
                    'specification_id' => $specificationId,
                    'del' => 1,
                ]);
                if (!$buyChainsSpecification) {
                    Exception::throwException(Exception::SPECIFICATION_NOT_JOIN_PINTUAN);
                }

                $item['buy_chains_id'] = $buyChains->id;
                $item['deal_price'] = $buyChainsSpecification->activity_price;
            }

            //新人活动
            if (isset($actProduct[$specificationId]['price']) && $actProduct[$specificationId]['price'] > 0) {
                $item['new_user_price'] = $actProduct[$specificationId]['price'];
                $item['deal_price'] = $actProduct[$specificationId]['price'];
                $includeNewUserProduct = 1;
            }


            $item['product_num'] = $buyItem->getProductNum();
            $specificationNumberMap[$specificationId] = $buyItem->getProductNum();
            $specificationIds[] = $specificationId;

            $result['items'][] = $item;
        }
        Tools::log($result, 'buyItems.log');
        $this->response->setFrom(Tools::pb_array_filter($result));
        $this->response->setIncludeNewUserProduct($includeNewUserProduct);
        return $this->response;
    }


    public static function request()
    {
        return new BuyItemsReq();
    }

    public static function response()
    {
        return new Items();
    }
}