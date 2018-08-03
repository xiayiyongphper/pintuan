<?php

namespace service\resources\product\v1;

use common\models\NewActProduct;
use message\product\NewUserActivityReq;
use message\product\ProductListRes;
use service\resources\ResourceAbstract;
use framework\data\Pagination;
use service\tools\search\productEs;
use service\tools\Tools;

/**
 * Class newUserProductList
 * @package service\resources\product\v1
 */
class newUserProductList extends ResourceAbstract
{
    /** @var  NewUserActivityReq */
    protected $request;

    public function run($data)
    {
        $this->doInit($data);

        $productIds = NewActProduct::find()
            ->select('product_id')
            ->where([
                'act_id' => $this->request->getActivityId(),
                'wholesaler_id' => $this->request->getWholesalerId(),
                'del' => NewActProduct::NOT_DELETED,
            ])->column();

        if (!$productIds) {
            $pages = new Pagination();
            $result['pages'] = Tools::getPagination($pages);
            $this->response->setFrom($result);
            return $this->response;
        }

        $params = [
            'id' => $productIds,
            'wholesaler_id' => $this->request->getWholesalerId(),
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
        return new NewUserActivityReq();
    }

    public static function response()
    {
        return new ProductListRes();
    }
}