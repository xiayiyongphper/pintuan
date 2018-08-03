<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\product\v1;

use common\models\Specification;
use message\product\CreateOrderItem;
use message\product\CreateOrderProductsReq;
use message\product\CreateOrderProductsRes;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\buyItems\buyChainsBuyItem;
use service\tools\buyItems\normalBuyItem;
use service\tools\buyItems\pintuanBuyItem;
use service\tools\Tools;

/**
 * Class CreateOrderProducts
 * @package service\resources\product\v1
 */
class CreateOrderProducts extends ResourceAbstract
{
    /** @var CreateOrderProductsReq */
    protected $request;
    /** @var  CreateOrderProductsRes */
    protected $response;

    public function run($data)
    {
        $this->doInit($data);
        $wholesalerIds = $this->request->getWholesalerIds();
        $storeId = $this->request->getStoreId();
        $type = $this->request->getType();
        $userId = $this->request->getUserId();

        $items = [];
        /** @var CreateOrderItem $buyItem */
        foreach ($this->request->getItems() as $buyItem) {
            $specificationId = $buyItem->getSpecificationId();
            switch ($type) {
                case 4:
                    if (empty($buyItem->getBuyChainsId())) {
                        Exception::throwException(Exception::INVALID_PARAM);
                    }
                    $item = (new buyChainsBuyItem($buyItem->getBuyChainsId(),$specificationId,$userId,$buyItem->getProductNum(),$storeId))->getItem();
                    break;
                case 3:
                    if (empty($buyItem->getPintuanActivityId())) {
                        Exception::throwException(Exception::INVALID_PARAM);
                    }
                    $item = (new pintuanBuyItem(0, $buyItem->getPintuanActivityId(), $specificationId, $storeId))->getItem();
                    break;
                case 2:
                    if (empty($buyItem->getPintuanId())) {
                        Exception::throwException(Exception::INVALID_PARAM);
                    }
                    $item = (new pintuanBuyItem($buyItem->getPintuanId(), 0, $specificationId, $storeId))->getItem();
                    break;
                case 1:
                    $item = (new normalBuyItem($buyItem->getProductId(), $specificationId, $storeId, $this->request->getActivityId()))->getItem();
                    break;
                default:
                    Exception::throwException(Exception::INVALID_PARAM);
            }

            //校验配送范围,选了自提点才要校验
            if ($storeId && (!isset($item['wholesaler_id']) || !in_array($item['wholesaler_id'], $wholesalerIds))) {
                Exception::throwException(Exception::STORE_NOT_IN_DISTRIBUTE_RANGE);
            }

            $item['product_num'] = $buyItem->getProductNum();
            $items[] = $item;
        }

        //这里直接减库存
        $this->reduceQty($items);

        Tools::log($items, 'raw_pro.log');
        $this->response->setFrom(Tools::pb_array_filter(['items' => $items]));
//        $this->response->setIncludeNewUserProduct($includeNewUserProduct);
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
        return new CreateOrderProductsReq();
    }

    public static function response()
    {
        return new CreateOrderProductsRes();
    }
}