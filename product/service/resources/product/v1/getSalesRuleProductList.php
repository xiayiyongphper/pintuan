<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\product\v1;

use message\product\ProductListReq;
use message\product\ProductListRes;
use service\resources\ResourceAbstract;
use service\tools\search\productEs;
use service\tools\Tools;

/**
 * Class getSalesRuleProductList.php
 * @package service\resources\product\v1
 */
class getSalesRuleProductList extends ResourceAbstract
{
    /** @var  ProductListReq $request */
    protected $request;

    public function run($data)
    {
        $this->doInit($data);
        $productIds = $this->request->getProductIds();
        $params = [
            'id' => $productIds,
            'page' => $this->request->getPage(),
            'page_size' => $this->request->getPageSize(),
        ];
        $elasticSearch = new productEs();
        $result = $elasticSearch->search($params);
        $this->response->setFrom(Tools::pb_array_filter($result));
        return $this->response;
    }

    public static function request()
    {
        return new ProductListReq();
    }

    public static function response()
    {
        return new ProductListRes();
    }
}