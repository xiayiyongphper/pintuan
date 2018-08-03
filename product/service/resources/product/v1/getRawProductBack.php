<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\product\v1;

use common\models\NewActProduct;
use common\models\Pintuan;
use common\models\PintuanActivity;
use common\models\PintuanActivitySpecification;
use common\models\PintuanActivityStore;
use common\models\Specification;
use message\product\RawProductItem;
use message\product\RawProductReq;
use message\product\RawProductRes;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\formatProductModel;
use service\tools\Tools;

/**
 * Class getProductList
 * @package service\resources\product\v1
 */
class getRawProductBack extends ResourceAbstract
{
    /** @var RawProductReq */
    protected $request;
    /** @var  RawProductRes */
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
//        Tools::log($this->request->getItems(),'raw_pro.log');
        /** @var RawProductItem $rawItem */
        foreach ($this->request->getItems() as $rawItem) {
            $specificationId = $rawItem->getSpecificationId();
            $pintuanActivityId = 0;
            $pinPrice = 0;
            /** @var PintuanActivity $pintuanActivity */
            $pintuanActivity = null;
            if ($type == 3) {
                if (empty($rawItem->getPintuanActivityId())) {
                    Exception::throwException(Exception::INVALID_PARAM);
                }

                $pintuanActivity = PintuanActivity::findOne(['id' => $rawItem->getPintuanActivityId()]);
                if (!$pintuanActivity) {
                    Exception::throwException(Exception::PINTUAN_ACT_NOT_FIND);
                }
            } elseif ($type == 2) {
                if (empty($rawItem->getPintuanId())) {
                    Exception::throwException(Exception::INVALID_PARAM);
                }

                $pintuan = Pintuan::findOne(['id' => $rawItem->getPintuanId()]);
                if (!$pintuan) {
                    Exception::throwException(Exception::PINTUAN_NOT_FIND);
                }
                if (strtotime($pintuan->end_time) < time()) {
                    Exception::throwException(Exception::PINTUAN_END);
                }

                $pintuanActivity = PintuanActivity::findOne(['id' => $pintuan->pintuan_activity_id]);
                if (!$pintuanActivity) {
                    Exception::throwException(Exception::PINTUAN_ACT_NOT_FIND);
                }
            } elseif ($type == 1) {
//                $specificationId = $rawItem->getSpecificationId();
            } else {
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
                    $storeIds = $pintuanActStores = PintuanActivityStore::find()
                        ->select(['store_id'])
                        ->where([
                            'pintuan_activity_id' => $pintuanActivity->id,
                            'del'                 => 1,
                        ])->column();
//                    Tools::log($storeIds,'raw_pro.log');
                    if (!empty($storeIds) && !in_array($storeId, $storeIds)) {
                        Exception::throwException(Exception::PINTUAN_ACTIVITY_NOT_SUPPORT_CURRENT_STORE);
                    }
                }

                $pintuanActivityId = $pintuanActivity->id;
                $pintuanActivitySpecification = PintuanActivitySpecification::findOne([
                    'pintuan_activity_id' => $pintuanActivityId,
                    'specification_id'    => $specificationId,
                    'del'                 => 1,
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
                    'act_id'     => $this->request->getActivityId(),
                    'product_id' => $rawItem->getProductId(),
                    'del'        => NewActProduct::NOT_DELETED,
                ]);
                $actProduct = array_column($actProduct, null, 'spec_id');
            }

            $item = (new formatProductModel($rawItem->getProductId()))
                ->getBasic()
                ->getImages()
                ->getDescription()
                ->getSpecificationInfo($specificationId)
                ->getData();

            if ($item['status'] != 1) {
                Exception::throwException(Exception::PRODUCT_OFFLINE);
            }

            //校验配送范围
            //Tools::log($wholesalerIds,'raw_pro.log');
            if (!isset($item['wholesaler_id']) || !in_array($item['wholesaler_id'], $wholesalerIds)) {
                Exception::throwException(Exception::STORE_NOT_IN_DISTRIBUTE_RANGE);
            }

            $item['images'] = is_array($item['images']) ? implode(';', $item['images']) : $item['images'];
            $item['description'] = is_array($item['description']) ? implode(';', $item['description']) : $item['description'];

            if ($rawItem->getPintuanId()) {
                $item['pintuan_id'] = $rawItem->getPintuanId();
            }

            $item['specification_id'] = $specificationId;

            if ($pintuanActivity) {
                $item['pintuan_activity_id'] = $pintuanActivityId;
                $item['pintuan_price'] = $pinPrice;
                $item['deal_price'] = $pinPrice;
            }

            //新人活动
            if (isset($actProduct[$specificationId]['price']) && $actProduct[$specificationId]['price'] > 0) {
                $item['new_user_price'] = $actProduct[$specificationId]['price'];
                $item['deal_price'] = $actProduct[$specificationId]['price'];
                $includeNewUserProduct = 1;
            }

            $item['product_num'] = $rawItem->getProductNum();
            $specificationNumberMap[$specificationId] = $rawItem->getProductNum();
            $specificationIds[] = $specificationId;

            $result['items'][] = $item;
        }

        //这里直接减库存
        $this->reduceQty($result['items']);

        Tools::log($result,'raw_pro.log');
        $this->response->setFrom(Tools::pb_array_filter($result));
        $this->response->setIncludeNewUserProduct($includeNewUserProduct);
        return $this->response;
    }

    private function reduceQty($items)
    {
        $transaction = Specification::getDb()->beginTransaction();
        try {
            foreach ($items as $item) {
                $specification = Specification::findOne(['id' => $item['specification_id']]);
                $specification->qty = $specification->qty - $item['product_num'];
                $specification->save();
                if (!$specification->save()) {
                    Tools::logException(new \Exception(json_encode($specification->errors)));
                    Exception::throwException(Exception::REDUCE_QTY);
                }
            }
        } catch (\Exception $e) {
            Tools::logException($e);
            $transaction->rollBack();
            throw $e;
        } catch (\Error $e) {
            Tools::logError($e);
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            Tools::logError($e);
            $transaction->rollBack();
            throw $e;
        }

        $transaction->commit();
    }


    public static function request()
    {
        return new RawProductReq();
    }

    public static function response()
    {
        return new RawProductRes();
    }
}