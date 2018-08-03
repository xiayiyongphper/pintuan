<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\product\v1;

use common\models\Product;
use message\product\ProductListReq;
use message\user\getRandProductRequest;
use message\user\getRandProductResponse;
use service\resources\ResourceAbstract;
use service\tools\Tools;
use yii\db\Expression;

/**
 * Class getProductList
 * @package service\resources\product\v1
 */
class getRandProduct extends ResourceAbstract
{
    /** @var  ProductListReq */
    protected $request;

    public function run($data)
    {
        $expression = new Expression('((SELECT MAX(id) FROM product) * RAND() - 100)');
        $products = Product::find()
            ->select(['id', 'name'])
            ->where(['>', 'id', $expression])
            ->limit(100)
            ->asArray()
            ->all();

        shuffle($products);
        $response = self::response();

        $response->setFrom(['product_list' => $products]);
        Tools::log($response->toArray(), 'getRandProduct.log');
        return $response;
    }

    public static function request()
    {
        return new getRandProductRequest();
    }

    public static function response()
    {
        return new getRandProductResponse();
    }
}