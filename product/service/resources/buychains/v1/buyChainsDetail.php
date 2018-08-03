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
use message\product\BuyChainsDetailReq;
use message\product\BuyChainsDetailRes;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\product\formatBuyChainsProduct;

/**
 * Class buyChainsDetail
 */
class buyChainsDetail extends ResourceAbstract
{
    /** @var BuyChainsDetailReq */
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
        $userId = $this->request->getUserId();

        $this->result = (new formatBuyChainsProduct($buychainsId))->getBasic()->getTopImage('180x180')->getSpecification($userId)->getData();
        if ($this->result['status'] == Product::STATUS_OFFLINE) {
            Exception::throwException(Exception::PRODUCT_OFFLINE);
        }

        ToolsAbstract::log($this->result, 'buy_chains_detail.log');
        $this->response->setFrom($this->result);
        return $this->response;
    }

    public static function request()
    {
        return new BuyChainsDetailReq();
    }

    public static function response()
    {
        return new BuyChainsDetailRes();
    }
}