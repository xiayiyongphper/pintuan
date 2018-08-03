<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/6/14
 * Time: 14:20
 */

namespace service\resources\product\v1;

use common\models\NewActProduct;
use common\models\PintuanActivity;
use common\models\PintuanActivitySpecification;
use common\models\Product;
use message\product\ProductDetailReq;
use message\product\ProductDetailRes;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use framework\protocolbuffers\Message;
use service\tools\formatProductModel;
use service\tools\Tools;


/**
 * Class productDetail
 */
class productDetail extends ResourceAbstract
{
    /** @var  ProductDetailReq */
    protected $request;

    /**
     * 仅当客返回值为\framework\protocolbuffers\Message类型时，消息才能传递到客户端
     * @param string $bytes
     * @return mixed
     */
    public function run($data)
    {
        $this->doInit($data);
//        Tools::log($this->request,'pro.log');
        $productId = $this->request->getProductId();

        //新人活动
        $actProduct = [];
        if ($this->request->getActivityId()) {
            $actProduct = NewActProduct::findAll([
                'act_id'     => $this->request->getActivityId(),
                'product_id' => $productId,
                'del'        => NewActProduct::NOT_DELETED,
            ]);
            $actProduct = array_column($actProduct, null, 'spec_id');
        }

        $product = (new formatProductModel($productId))
            ->getBasic()
            ->getImages()
            ->getDescription()
            ->getSpecification($actProduct)
            ->getData();
//        Tools::log($product,'pro.log');
        if (!$product) {
            Exception::throwException(Exception::PRODUCT_NOT_FIND);
        }

        if ($product['status'] != Product::STATUS_ONLINE) {
            Exception::throwException(Exception::PRODUCT_OFFLINE);
        }

        $this->result = $product;

        if (!empty($product['specification']) && !$this->request->getActivityId()) {
            $this->setPintuanInfo($productId);
        }

        $this->response->setFrom($this->result);
        return $this->response;
    }

    /**
     * 查询拼团活动，并设置拼团价
     * @param $productId
     */
    private function setPintuanInfo($productId){
        //获取拼团信息
        /** @var PintuanActivity $pintuanActivity */
        $pintuanActivity = PintuanActivity::getByProductId($productId);
        if(!$pintuanActivity) return;

        $pintuanPriceMap = [];
        $pintuanActivityId = $pintuanActivity->id;
        $pintuanSpecArr = PintuanActivitySpecification::findAll(['pintuan_activity_id' => $pintuanActivityId]);
        if(!$pintuanSpecArr) return;

        /** @var PintuanActivitySpecification $pintuanSpec */
        foreach ($pintuanSpecArr as $pintuanSpec) {
            $pintuanPriceMap[$pintuanSpec->specification_id] = $pintuanSpec->pin_price ? round($pintuanSpec->pin_price / 100, 2) : 0;
        }
        //Tools::log($pintuanPriceMap,'pro_detail.log');

        foreach ($this->result['specification'] as $k => $v) {
            if (isset($pintuanPriceMap[$v['specification_id']])) {
                $this->result['specification'][$k]['pintuan_price'] = $pintuanPriceMap[$v['specification_id']];
                $this->result['specification'][$k]['pintuan_activity_id'] = $pintuanActivityId;
            }
        }
    }

    public static function request()
    {
        return new ProductDetailReq();
    }

    public static function response()
    {
        return new ProductDetailRes();
    }
}