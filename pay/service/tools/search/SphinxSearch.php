<?php

namespace service\components\search;

use framework\data\Pagination;
use service\components\Tools;
use service\message\merchant\searchProductResponse;
use service\resources\MerchantResourceAbstract;

/**
 * Created by Jason.
 * Author: Jason Y. Wang
 * Date: 2016/4/15
 * Time: 16:09
 */
class SphinxSearch extends Search
{

    const MAX_WHOLESALER_NUM = 15;
    const MAX_PRODUCT_NUM = 5;

    private function getSource($city)
    {
        return "product_{$city}_main";
    }

    /**
     * Function: search
     * Author: Jason Y. Wang
     * 搜索功能
     * @return mixed
     */
    public function search()
    {
        $keyword = $this->searchRequest->getKeyword();
        $wholesaler_id = $this->searchRequest->getWholesalerId();
        $customer = $this->customer;
        /** 搜索引擎 Client */
        $sphinx = new \SphinxClient();
        $sphinx->setServer(ENV_SPHINX_HOST, ENV_SPHINX_PORT);

        //设置匹配模式
        $sphinx->setMatchMode(SPH_MATCH_EXTENDED2);   //查询方式  扩展查询语法
        $sphinx->SetRankingMode(SPH_RANK_SPH04);  //匹配方式
        //$sphinx->setMaxQueryTime(3000); //单位是毫秒，坑爹,默认不限

        //状态过滤 审核通过且上架   解决上架不生效
        $sphinx->SetFilter('status', array(1));
        $sphinx->SetFilter('state', array(2));
        //商家过滤
        if ($wholesaler_id > 0) {
            // 查指定的商家
            $sphinx->SetFilter('wholesaler_id', array($wholesaler_id));
        } else {
            // 否则就查该区域的商家id
            $wholesalerIds = MerchantResourceAbstract::getWholesalerIdsByAreaId($customer->getAreaId());
            $sphinx->SetFilter('wholesaler_id', $wholesalerIds);
        }

        //分类过滤
        //通过分类过滤商品
        $categoryId = $this->searchRequest->getCategoryId();
        $categoryLevel = $this->searchRequest->getCategoryLevel() ?: Tools::getCategoryLevelByID($categoryId);
        if ($categoryId) {
            switch ($categoryLevel) {
                case 1:
                    $category = 'first_category_id';
                    break;
                case 2:
                    $category = 'second_category_id';
                    break;
                case 3:
                    $category = 'third_category_id';
                    break;
                default :
                    $category = 'third_category_id';
                    break;
            }
            $sphinx->SetFilter($category, [$categoryId]);
        }

        // 排序
        //搜索结果多字段排序,后面使用
        //$sphinx->SetSortMode(SPH_SORT_EXTENDED,"@weight DESC,wholesaler_weight DESC,sort_weights DESC");
        //$sphinx->SetSortMode(SPH_SORT_EXTENDED,"@weight DESC");
        //$sphinx->SetSortMode(SPH_SORT_EXPR,"@weight + sort_weights * 0.3 + wholesaler_sort_weights * 0.3");

        //销量，价格
        $allowedSortField = array('sold_qty', 'price');
        $field = $this->searchRequest->getField();
        if (in_array($field, $allowedSortField)) {
            $sort = $this->searchRequest->getSort() == "asc" ? 'asc' : 'desc';
            if ($field == 'price') {
                //搜索引擎中的字段
                $field = 'final_price';
            }
            $sort_field = $field . ' ' . $sort;
            //Tools::log($sort_field,'wangyang.log');
            $sphinx->SetSortMode(SPH_SORT_EXTENDED, $sort_field);
        }

        //分页设置
        $page = $this->searchRequest->getPage() ?: 1;
        $pageSize = $this->searchRequest->getPageSize() ?: 20;
        $sphinx->setLimits(($page - 1) * $pageSize, $pageSize, 10000);

        ////匹配表达式，在名称和品牌中搜索
        //大于4位纯数字搜索条码
        //Tools::log($keyword,'wangyang.log');
//        $timeStart = microtime(true);

        // 品牌
        $brand = $this->searchRequest->getBrand();
        $brands = array_filter(explode(';', $brand));
        foreach ($brands as &$brand_condition){
            $brand_condition = '"'.$brand_condition.'"';
        }
        $brands = implode('|',$brands);

//        Tools::log($brands,'wangyang.log');
        if ($brands) {
            //品牌进行过滤时，一定是没有输入关键字的
            $res = $sphinx->query("@brand {$brands}", $this->getSource($customer->getCity()));
        }else{
            //先进行分词，然后用分词查询
            $words = '';
            if ($keyword) {
                $words = $this->prepare($sphinx, $keyword, $this->getSource($customer->getCity()));
            }
            if (is_numeric($keyword) && strlen($keyword) >= 4) {
                $res = $sphinx->query("@barcode {$keyword}", $this->getSource($customer->getCity()));
            } else {
                $res = $sphinx->query($words, $this->getSource($customer->getCity()));
            }
        }

//        $timeEnd = microtime(true);
//        Tools::log($timeEnd-$timeStart,'wangyang.log');
        $err = $sphinx->GetLastError();
//        Tools::log('result','wangyang.log');
//        Tools::log($res,'wangyang.log');
        //Tools::log($err,'wangyang.log');
        $product_ids = [];
        if (isset($res['matches']) && count($res['matches'])) {
            $product_ids = array_keys($res['matches']);
        }

        //搜索无结果
//        Tools::log(count($product_ids),'search_product.log');
        if (count($product_ids) == 0) {
            $response = new searchProductResponse();
            return $response;
        }

        $pages = new Pagination(['totalCount' => $res['total']]);
        $pages->setCurPage($page);
        $pages->setPageSize($pageSize);
        $this->pagination = $pages;

        //搜索有结果
        $this->productIds = $product_ids;
        $wordSegments = '';
        if (isset($res['words'])) {
            $wordSegments = is_array($res['words']) ? array_keys($res['words']) : '';
        }
        $this->words = $wordSegments;

        return $this->packagingResponse();
    }

    /**
     * Function: prepare
     * Author: Jason Y. Wang
     * 先进行分词
     * @param $sphinx
     * @param $keyword
     * @param $index
     * @return array|string
     * @internal param $query
     */
    private function prepare($sphinx, $keyword, $index)
    {
        $keywords = $sphinx->buildKeywords($keyword, $index, false);
        if (!is_array($keywords)) {
            return $keyword;
        }
        $query = array();
        foreach ($keywords as $key) {
            $query[] = $key["tokenized"];
        }
        $query = implode("|", $query);
        return $query;
    }

    public static function suggest($customer, $keyword,$wholesalerId = null)
    {
        $suggest = [];
        /** 搜索引擎 Client */
        $sphinx = new \SphinxClient();
        $sphinx->setServer(ENV_SPHINX_HOST, ENV_SPHINX_PORT);

        //设置匹配模式
        $sphinx->setMatchMode(SPH_MATCH_EXTENDED2);   //查询方式  扩展查询语法
        $sphinx->SetRankingMode(SPH_RANK_SPH04);  //匹配方式
        if($wholesalerId){
            $sphinx->SetFilter('wholesaler_id', array($wholesalerId));
        }
        $city = "product_{$customer->getCity()}_main";
        $sphinx->setLimits(0, 5, 1000);

        $sphinx->SetGroupBy("brand", SPH_GROUPBY_ATTR, "@count desc");
        $brand_result = $sphinx->query("@brand {$keyword}", $city);
        if(isset($brand_result['matches']) && is_array($brand_result['matches'])) {
            foreach ($brand_result['matches'] as $match) {
                $suggest[] = $match['attrs']['brand'];
            }
        }

        $sphinx->setLimits(0, 10, 1000);
        $sphinx->ResetGroupBy();
        $sphinx->SetGroupBy("name", SPH_GROUPBY_ATTR, "@count desc");
        $name_result = $sphinx->query($keyword, $city);
        if(isset($name_result['matches']) && is_array($name_result['matches'])){
            foreach ($name_result['matches'] as $match) {
                $suggest[] = $match['attrs']['brand'] . ' ' . $match['attrs']['name'];
            }
        }


        return array_unique($suggest);
    }
}
