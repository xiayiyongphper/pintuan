<?php

namespace service\components\search;

use common\models\Synonyms;
use framework\components\ToolsAbstract;
use service\components\bg2gb\big2gb;
use service\components\Tools;
use service\message\customer\CustomerResponse;
use service\resources\Exception;

/**
 * Author Jason Y.Wang
 * Class ElasticSearch
 * @package service\components\search
 */
class ElasticSearchExt2
{
    public $client;
    public $params;
    public $city;

    const DEFAULT_PAGE = 1;
    const DEFAULT_PAGE_SIZE = 20;

    public $product_sales_type = [
        1 => 8, //自营商品 0b1000
        2 => 2, //普通商品 0b0010
    ];

    /**
     * ElasticSearchExt2 constructor.
     * @param $city
     */
    public function __construct($city, $platform = PLATFORM_APP)
    {
        $date = Tools::getDate()->date();

        $this->client = \Yii::$app->get('elasticSearch');
        $this->city = $city;

        //索引名称
        $this->params['index'] = 'products';
        if($platform == PLATFORM_MINI_PROGRAM){
            $this->params['index'] = 'mini_products';
        }

        //查询范围
        $this->params['type'] = $this->city;
        //过滤商品状态
        $this->addFilter('status', 1);
        $this->addFilter('state', 2);

        $this->params['body']['query']['bool']['must'][] = [
            "range" => [
                "shelf_from_date" => [
                    "lt" => $date,
                ]
            ]
        ];

        $this->params['body']['query']['bool']['must'][] = [
            "range" => [
                "shelf_to_date" => [
                    "gt" => $date,
                ]
            ]
        ];

        $this->params['body']['query']['bool']['must'][] = [
            "script" => [
                "script" => [
                    "inline" => '
                        type = doc[\'type\'].value;
                        if(type == 2) {
                            return false;
                        }else{
                            return true;
                        }
                  ',
                    'lang' => 'groovy',
                ]
            ]
        ];

        //分数计算方式 类似mysql explain
        $this->params['body']['explain'] = false;
    }

    /**
     * @param $field
     * @param $value
     */
    public function addFilter($field, $value)
    {
        if (empty($value)) return;

        if (!is_array($value)) {
            $this->params['body']['query']['bool']['must'] [] = ['term' => [$field => $value]];
        } elseif (count($value) == 1) {
            $this->params['body']['query']['bool']['must'] [] = ['term' => [$field => current($value)]];
        } else {
            $this->params['body']['query']['bool']['must'] [] = ['terms' => [$field => $value]];
        }
    }

    /**
     * @param $field
     * @param $value
     */
    public function addFilterMustNot($field, $value)
    {
        if (empty($value)) return;

        if (!is_array($value)) {
            $this->params['body']['query']['bool']['must_not'] [] = ['term' => [$field => $value]];
        } elseif (count($value) == 1) {
            $this->params['body']['query']['bool']['must_not'] [] = ['term' => [$field => current($value)]];
        } else {
            $this->params['body']['query']['bool']['must_not'] [] = ['terms' => [$field => $value]];
        }
    }

    /**
     * @param $salesTypeIds
     */
    public function setSalesTypeFilter($salesTypeIds)
    {
        if (empty($salesTypeIds)) return;

        $filter = 0;
        foreach ($salesTypeIds as $id) {
            if (isset($this->product_sales_type[$id])) {
                $filter |= $this->product_sales_type[$id];
            }
        }

        if ($filter) {
            $this->params['body']['query']['bool']['must'][] = [
                "script" => [
                    "script" => [
                        "inline" => '
                        sales_type = doc[\'sales_type\'].value;
                        filter = product_sales_type & sales_type;
                        if(filter > 0) {
                            return true;
                        }else{
                            return false;
                        }
                  ',
                        'lang' => 'groovy',
                        'params' => [
                            'product_sales_type' => $filter
                        ]
                    ]
                ]
            ];
        }
    }

    /**
     * @param $keyword
     */
    public function setKeywordFilter($keyword)
    {
        if (empty($keyword)) return;

        if (is_numeric($keyword) && strlen($keyword) >= 4) {
            $this->params['body']['query']['bool']['must'][] = [
                "wildcard" => [
                    "barcode" => "*{$keyword}*",
                ]
            ];
        } else {
            //繁体转简体
            $convert = new big2gb();
            ToolsAbstract::log($keyword."==========1",'keyword.log');
            $keyword = $convert->chg_utfcode($keyword,'gb2312');
//            $keyword = MediaWikiZhConverter::convert($keyword, "zh-cn");
            ToolsAbstract::log($keyword,'keyword.log');
            //替换关键词
            $synonyms = Synonyms::findOne(['keyword' => $keyword]);
            if ($synonyms && $synonyms->replace_words) {
                $keyword = $synonyms->replace_words;
            }

            ToolsAbstract::log($keyword."==========3",'keyword.log');
            $this->params['body']['query']['bool']['must'][] = [
                "multi_match" => [
                    "query" => $keyword,
                    "type" => "most_fields",
                    "fields" => [
                        'brand^8', 'name^3', 'first_category_name', 'second_category_name',
                        'third_category_name', 'specification_num_unit^2', 'promotion_text', 'specification_num', 'search_text'
                    ]
                ]
            ];
        }
    }

    /**
     * 翻页设置
     * @param $page
     * @param $pageSize
     */
    public function setPageConf($pageSize = self::DEFAULT_PAGE_SIZE, $page = self::DEFAULT_PAGE)
    {
        $offset = ($page - 1) * $pageSize;
        $this->params['body']['size'] = $pageSize;

        unset($this->params['body']['from']);
        if ($offset > 0) {
            $this->params['body']['from'] = $offset;
        }
    }

    /**
     * 折叠
     * @param $field
     */
    public function setCollapse($field)
    {
        $this->params['body']['collapse'] = [
//            'field' => 'lsin_package_num',
            'field' => $field
        ];
    }

    public function addAggs($field)
    {
        $this->params['body']['aggs'][$field] = [
            'terms' => [
                'field' => $field,
                'size' => 1000
            ]
        ];
    }

    public function doSearch()
    {
//        Tools::log($this->params, 'params.log');
        $result = $this->client->search($this->params);
        if(!empty($result['errors'])){
            ToolsAbstract::logException(new \Exception(json_encode($result['errors']),10010));
            Exception::serviceNotAvailable();
        }

        return $result;
    }

    /**获取聚合分类
     * @param $filters
     * @param $aggs
     * @param bool $keys_only
     * @param bool $return_products
     * @return array
     */
    public function getCategory($filters, $aggs, $keys_only = true, $return_products = false)
    {
        if (!$return_products) {
            $this->setPageConf(0);
        }

        //过滤
        if (isset($filters['keyword'])) {
            $this->setKeywordFilter($filters['keyword']);
            unset($filters['keyword']);
        }
        foreach ($filters as $k => $v) {
            $this->addFilter($k, $v);
        }

        //查询 聚合
        $this->addAggs($aggs);

        Tools::log($this->params,'xxx.log');
        $categoryResult = $this->doSearch();
        Tools::log($categoryResult,'xxx.log');
        $buckets = $categoryResult['aggregations'][$aggs]['buckets'];

        if ($keys_only) {
            $keys = [];
            foreach ($buckets as $bucket) {
                array_push($keys, $bucket['key']);
            }
            return $keys;
        }

        return $buckets;
    }

    /**获取聚合品牌
     * @param $wholesaler_id
     * @param $third_category_id
     * @return array
     */
//    public function getBrand($wholesaler_id, $third_category_id)
//    {
//        $this->setPageConf(0);
//
//        $this->addFilter('wholesaler_id',$wholesaler_id);
//        $this->addFilter('third_category_id',$third_category_id);
//
//        //查询 聚合
//        $this->addAggs('brand_agg');
//
//        Tools::log($this->params, 'getBrand.log');
//        $brandResult = $this->doSearch();
//
//        $brand_buckets = $brandResult['aggregations']['brand_agg']['buckets'];
//        $brands = [];
//
//        if (empty($brand_buckets)) {
//            return $brands;
//        }
//
//        foreach ($brand_buckets as $bucket) {
//            array_push($brands, $bucket['key']);
//        }
//
//        return $brands;
//    }

    /**
     * 获取供应商聚合结果
     * @param $filters 筛选条件
     * @param bool $keys_only 是否只返回供应商id列表
     * @return array
     */
//    public function getStoresAggs($filters)
//    {
//        $this->setPageConf(0);
//
//        //过滤
//        foreach ($filters as $k=>$v){
//            $this->addFilter($k,$v);
//        }
//
//        //查询 聚合
//        $this->params['body']['aggs'] = [
//            'aggs_data' => [
//                'terms' => [
//                    'field' => 'wholesaler_id',
//                    'size' => 1000
//                ],
//                'aggs' => [
//                    'sort' => [
//                        'max' => [
//                            'field' => 'wholesaler_weight'
//                        ]
//                    ]
//                ]
//            ]
//        ];
//
////        Tools::log($this->params,'xxx.log');
//        $categoryResult = $this->doSearch();
////        Tools::log($categoryResult,'xxx.log');
//        $buckets = $categoryResult['aggregations']['aggs_data']['buckets'];
//
//        $data = [];
//        foreach ($buckets as $bucket) {
//            $data[$bucket['key']] = $bucket['sort']['value'];
//        }
//        arsort($data);
//        $keys = array_keys($data);
//
//        return $keys;
//    }

//    public function getProductsByLsinPackageNum($lsinPackageNumArr,$filters=[]){
//        $this->setPageConf(1000);
//
//        //过滤
//        foreach ($filters as $k=>$v){
//            $this->addFilter($k,$v);
//        }
//
//        //查询 聚合
//        $this->addAggs('brand_agg');
//
//        Tools::log($this->params, 'getBrand.log');
//        $brandResult = $this->doSearch();
//
//        $brand_buckets = $brandResult['aggregations']['brand_agg']['buckets'];
//        $brands = [];
//
//        if (empty($brand_buckets)) {
//            return $brands;
//        }
//
//        foreach ($brand_buckets as $bucket) {
//            array_push($brands, $bucket['key']);
//        }
//
//        return $brands;
//    }

}
