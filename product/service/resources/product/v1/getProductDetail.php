<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/6/14
 * Time: 14:20
 */

namespace service\resources\product\v1;

use common\models\Product;
use message\product\ProductDetailReq;
use message\product\ProductDetailRes;
use service\resources\ResourceAbstract;

/**
 * Class productDetail
 */
class getProductDetail extends ResourceAbstract
{
    /** @var  ProductDetailReq */
    protected $request;

    public function run($data)
    {
        $this->doInit($data);
        $productId = $this->request->getProductId();

        $query = Product::find()
            ->select(['id as product_id', 'name', 'wholesaler_id'])
            ->where(['id' => $productId, 'status' => 1, 'del' => 1]);

        $product = $query->asArray()->one();
        if (!$product) {
            return false;
        }
        $this->response->setFrom($product);
        return $this->response;
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