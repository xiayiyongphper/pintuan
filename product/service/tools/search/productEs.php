<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/6/19
 * Time: 15:42
 */

namespace service\tools\search;

use common\models\NewActProduct;
use framework\data\Pagination;
use service\tools\Tools;

/**
 * Class productEs
 * @package service\tools\search
 */
class productEs extends esBase
{
    const ES_INDEX_PINTUAN_PRODUCT = 'pintuan_products';
    const ES_TYPE_PINTUAN_PRODUCT = 'product';

    public function __construct()
    {
        parent::__construct(self::ES_INDEX_PINTUAN_PRODUCT, self::ES_TYPE_PINTUAN_PRODUCT);

        $this->addMustFilter('status', 1);
        $this->addMustFilter('del', 1);
    }

    public function search($params = [])
    {
        $this->setPageConf(0);

        //新人活动
        $actProduct = [];

        if (isset($params['wholesaler_id'])) {
            $this->addMustFilter('wholesaler_id', $params['wholesaler_id']);

            if (isset($params['activity_id']) && $params['activity_id']) {
                $actProduct = NewActProduct::findAll([
                    'act_id' => $params['activity_id'],
                    'wholesaler_id' => $params['wholesaler_id'],
                    'del' => NewActProduct::NOT_DELETED,
                ]);
                $actProduct = array_column($actProduct, null, 'product_id');
            }
        }

        if (isset($params['id'])) {
            $this->addMustFilter('id', $params['id']);
        }

        if (isset($params['third_category_id'])) {
            $this->addMustFilter('third_category_id', $params['third_category_id']);
        }

        $page = isset($params['page']) && $params['page'] ? $params['page'] : self::DEFAULT_PAGE;
        $pageSize = isset($params['page_size']) && $params['page_size'] ? $params['page_size'] : self::DEFAULT_PAGE_SIZE;
        $this->setPageConf($pageSize, $page);

        $searchResult = $this->doSearch();
        //Tools::log($searchResult,'pro_list.log');

        $total = $searchResult['hits']['total'];
        //组装商品
        $products = $this->getProductSource($searchResult);

        foreach ($products as &$product) {
            //只要第一张图片
            $product['image'] = '';
            if ($product['images']) {
                $images = explode(';', $product['images']);
                if (!empty($images)) {
                    $product['image'] = isset($images[1]) ? $images[1] : current($images);
                }
                $product['image'] = Tools::getImage($product['image'], '388x388');
            }
            unset($product['images']);
            //价格转为元为单位
            $product['min_price'] = $product['min_price'] ? $product['min_price'] / 100 : 0;

            //新人活动
            if ($actProduct && isset($actProduct[$product['id']])) {
                $product['new_price'] = $actProduct[$product['id']]['price'] / 100;
                $product['spec_id'] = $actProduct[$product['id']]['spec_id'];
            }
        }

        $pages = new Pagination();
        $pages->setTotalCount($total);
        $pages->setPageSize($pageSize);
        $pages->setCurPage($page);

        $result['pages'] = Tools::getPagination($pages);
        $result['product_list'] = array_values($products);

        return $result;
    }

    /**
     * @param $searchResult
     * @return array
     * 从返回结果中拿到商品信息
     */
    private function getProductSource($searchResult)
    {
        $hits = $searchResult['hits']['hits'];
        $products = [];
        if (empty($hits)) {
            return $products;
        }

        foreach ($hits as $hit) {
            $product = $hit['_source'];
            $score = 0;
            if (isset($hit['sort'])) {
                $score_relation = isset($hit['sort'][0]) ? $hit['sort'][0] : 'null';
                $score_cal = isset($hit['sort'][1]) ? $hit['sort'][1] : 'null';
                $score = $score_relation . ';' . $score_cal;
            }

            $product['score'] = $score;
            $products[] = $product;
        }

        return $products;
    }

}