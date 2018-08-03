<?php
namespace service\resources\buychains\v1;

/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/7/27
 * Time: 14:56
 */

use common\models\Product;
use framework\components\ToolsAbstract;
use message\product\BuyChainsProductDetailReq;
use message\product\BuyChainsProductDetailRes;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\product\formatBuyChainsProduct;

/**
 * Class productDetail
 */
class productDetail extends ResourceAbstract
{
    /** @var BuyChainsProductDetailReq  */
    protected $request;

    /**
     * 仅当客返回值为\framework\protocolbuffers\Message类型时，消息才能传递到客户端
     * @param string $bytes
     * @return mixed
     */
    public function run($data)
    {
        $this->doInit($data);
        $buychainsId = $this->request->getBuyChainsId();

        $buyChainsProduct = (new formatBuyChainsProduct($buychainsId))->getBasic()->getImages()->getDescription()->getSpecification()->getData();
//        ToolsAbstract::log($buyChainsProduct,'buy_chains_product_detail.log');
        if($buyChainsProduct['status'] == Product::STATUS_OFFLINE){
            Exception::throwException(Exception::PRODUCT_OFFLINE);
        }

        ToolsAbstract::log($buyChainsProduct,'buy_chains_detail.log');
        $this->response->setFrom($buyChainsProduct);
        return $this->response;
    }

    public static function request()
    {
        return new BuyChainsProductDetailReq();
    }

    public static function response()
    {
        return new BuyChainsProductDetailRes();
    }
}