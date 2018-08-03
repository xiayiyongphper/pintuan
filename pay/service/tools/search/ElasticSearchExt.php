<?php

namespace service\components\search;

use common\models\Products;
use framework\data\Pagination;
use service\components\Tools;
use service\message\customer\CustomerResponse;
use service\message\merchant\pieceTogetherOrderAreaRequest;
use service\message\merchant\searchProductResponse;
use service\models\ProductHelper;
use service\resources\MerchantResourceAbstract;

/**
 * Author Jason Y.Wang
 * Class ElasticSearch
 * @package service\components\search
 */
class ElasticSearchExt extends Search
{

    public $client;
    public $params;
    public $city;
    public $area_id;

    public $product_sales_type = [
        1 => 8, //自营商品 0b1000
        2 => 2, //普通商品 0b0010
    ];

    public function __construct($customer, $searchRequest = null)
    {
        parent::__construct($customer, $searchRequest);

        $date = Tools::getDate()->date();

        $this->client = \Yii::$app->get('elasticSearch');
        $this->city = $this->customer->getCity();
        $this->area_id = $this->customer->getAreaId();

        //索引名称
        $this->params['index'] = 'products';
        //查询范围
        $this->params['type'] = $this->city;
        //过滤商品状态
        $this->params['body']['query']['bool']['must'][] = ['term' => ['status' => 1]];
        $this->params['body']['query']['bool']['must'][] = ['term' => ['state' => 2]];

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
     * Function: search
     * Author: Jason Y. Wang
     * 搜索功能
     * @return mixed
     */
    public function search()
    {
        //返回数据
        $result = [];
        //参数
        $keyword = $this->searchRequest->getKeyword();
        //过滤供应商
        $wholesaler_id = $this->searchRequest->getWholesalerId();
        //过滤品牌
        $brands = $this->searchRequest->getBrand();
        //product_sales_type_ids 自营商品
        $product_sales_type_ids = $this->searchRequest->getProductSalesTypeIds();
        $product_sales_type_filter = 0;
        foreach ($product_sales_type_ids as $product_sales_type_id) {
            if (isset($this->product_sales_type[$product_sales_type_id])) {
                $product_sales_type_filter |= $this->product_sales_type[$product_sales_type_id];
            }
        }

        //过滤分类
        $category_id = $this->searchRequest->getCategoryId();
        $category_level = $this->searchRequest->getCategoryLevel() ?: Tools::getCategoryLevelByID($category_id);
        $categories = $this->searchRequest->getCategory();


        //分页设置
        $page = $this->searchRequest->getPage() ?: 1;
        $pageSize = $this->searchRequest->getPageSize() ?: 20;
        $offset = ($page - 1) * $pageSize;
        $this->params['body']['size'] = $pageSize;
        $this->params['body']['from'] = $offset;

        //关键字查询
        if ($keyword) {
            if (is_numeric($keyword) && strlen($keyword) >= 4) {
                $this->params['body']['query']['bool']['must'][] = [
                    "wildcard" => [
                        "barcode" => "*{$keyword}*",
                    ]
                ];

            } else {
                $this->params['body']['query']['bool']['must'][] = [
                    "multi_match" => [
                        "query" => $keyword,
                        "fields" => [
                            'brand^8', 'name^3', 'first_category_name', 'second_category_name',
                            'third_category_name', 'specification_num_unit^2', 'promotion_text', 'specification_num', 'search_text'
                        ]
                    ]
                ];
            }

        }

        //筛选商品类型，自营商品等
        if ($product_sales_type_filter) {
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
                            'product_sales_type' => $product_sales_type_filter
                        ]
                    ]
                ]
            ];
        }


        //供应商查询
        if ($wholesaler_id > 0) {
            $term_wholesaler_id = ['terms' => ['wholesaler_id' => [$wholesaler_id]]];
            array_push($this->params['body']['query']['bool']['must'], $term_wholesaler_id);
        } else {
            // 否则就查该区域的商家id
            $wholesalerIds = MerchantResourceAbstract::getWholesalerIdsByAreaId($this->area_id);
            $term_wholesaler_ids = ['terms' => ['wholesaler_id' => $wholesalerIds]];
            array_push($this->params['body']['query']['bool']['must'], $term_wholesaler_ids);
        }


        // 品牌
        if ($brands) {
            $brands = array_filter(explode(';', $brands));
            $term_brands = ['terms' => ["brand_agg" => $brands]];
            array_push($this->params['body']['query']['bool']['must'], $term_brands);

        }

        /*if ($category_id) {
            //分类过滤
            $category = 'third_category_id';
            if ($category_id) {
                switch ($category_level) {
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
            }

            array_push($this->params['body']['query']['bool']['must'], ['term' => [$category => $category_id]]);
        }*/
        $categoryArr = [];
        if ($category_id) {
            Tools::setCategoryArr($categoryArr, $category_id, $category_level);
        }

        foreach ($categories as $item) {
            if ($item->getId()) {
                Tools::setCategoryArr($categoryArr, $item->getId(), $item->getLevel());
            }
        }
        foreach ($categoryArr as $k => $item) {
            if (empty($item)) continue;

            if (count($item) == 1) {
                array_push($this->params['body']['query']['bool']['must'], ['term' => [$k => current($item)]]);
            } else {
                array_push($this->params['body']['query']['bool']['must'], ['terms' => [$k => $item]]);
            }
        }


        //销量，价格排序
        $field = $this->searchRequest->getField();
        $sort = $this->searchRequest->getSort() == "asc" ? 'asc' : 'desc';

        //如果选了排序方式，按照选中的排序方式排序，不管是否有关键词；如果是综合排序（即没有选择排序方式）,有关键词按照相关排序，没有关键词按照权重排序
        if ($field == 'price') {
            //传过来的排序
            $this->params['body']['sort'] = [
                [
                    '_script' => [
                        'type' => 'number',
                        'script' => [
                            'inline' => '
                                        special_from_date = doc[\'special_from_date\'].value;
                                        special_to_date = doc[\'special_to_date\'].value;
                                        special_price = doc[\'special_price\'].value;
                                        price = doc[\'price\'].value;
                                        final_price = 0;
                                        if(special_from_date < date && special_to_date > date && special_price > 0) {
                                            final_price = special_price;
                                        }else{
                                            final_price = price;
                                        }
                                        return final_price;
                                    ',
                            'params' => [
                                'date' => floor(microtime(true) * 1000) //es中时间比较用毫秒级时间戳
                            ],
                            'lang' => 'groovy'
                        ],
                        'order' => $sort
                    ]
                ],   //自定义排序
                [
                    '_score' => 'desc'   //相关性排序
                ],
            ];

        } else if ($field == 'sold_qty') {
            $this->params['body']['sort'] = [
                [
                    $field => $sort
                ]
            ];
        } else if ($keyword) {
            //默认排序，自定义排序
            $this->params['body']['sort'] = [
                [
                    '_score' => 'desc'   //相关性排序
                ],
                [
                    '_script' => [
                        'type' => 'number',
                        'script' => [
                            'inline' => '
                                        special_from_date = doc[\'special_from_date\'].value;
                                        special_to_date = doc[\'special_to_date\'].value;
                                        special_price = doc[\'special_price\'].value;
                                        rule_id = doc[\'rule_id\'].value;
                                        wholesaler_weight = doc[\'wholesaler_weight\'].value;
                                        score = 0;
                                        if(special_from_date < date && special_to_date > date) {
                                            special_price_score = special_price > 0 ? 1 :0;
                                            score = score + special_price_score;
                                        }
                                        rule_score = rule_id > 0 ? 1 :0;
                                        score = score + rule_score;
                                        wholesaler_sort_score = 0;
                                        if(wholesaler_weight >= 1000 && wholesaler_weight < 2000){
                                            wholesaler_sort_score = 1;
                                        }else if(wholesaler_weight >= 2000 && wholesaler_weight < 5000){
                                            wholesaler_sort_score = 2;
                                        }else if(wholesaler_weight >= 5000){
                                            wholesaler_sort_score = 3;
                                        }
                                        score = score + wholesaler_sort_score;
                                        return score;
                                    ',
                            'params' => [
                                'date' => floor(microtime(true) * 1000) //es中时间比较用毫秒级时间戳
                            ],
                            'lang' => 'groovy'
                        ],
                        'order' => 'desc'
                    ]
                ],   //自定义排序
                [
                    'price' => 'asc'  //价格参加排序
                ],
                [
                    'sold_qty' => 'desc'  //销量排序
                ],
            ];
        } else {
            //综合排序，且没有关键词，用权重和相关性排序
            $this->params['body']['sort'] = [
                [//权重排序
                    '_script' => [
                        'type' => 'number',
                        'script' => [
                            'inline' => '
                                        sort_weights = doc[\'sort_weights\'].value;
                                        brand_weight = doc[\'brand_weight\'].value;
                                        wholesaler_weight = doc[\'wholesaler_weight\'].value;
                                        special_from_date = doc[\'special_from_date\'].value;
                                        special_to_date = doc[\'special_to_date\'].value;
                                        special_price = doc[\'special_price\'].value;
                                        rule_id = doc[\'rule_id\'].value;
                                        special_price_score = 0;
                                        if(special_from_date < date && special_to_date > date) {
                                            special_price_score = special_price > 0 ? 300 :0;
                                        }
                                        rule_score = rule_id > 0 ? 300 :0;
                                        sort_weights_score = sort_weights > 1000 ? 1000 : sort_weights;
                                        brand_weight_score = brand_weight > 1000 ? 1000 : brand_weight;
                                        wholesaler_sort_score = wholesaler_weight > 1000 ? 1000 : wholesaler_weight;
                                        score = (sort_weights_score * 0.5) + (brand_weight_score * 0.2) + (wholesaler_sort_score * 0.2) + special_price_score + rule_score;
                                        return score;
                                    ',
                            'params' => [
                                'date' => floor(microtime(true) * 1000) //es中时间比较用毫秒级时间戳
                            ],
                            'lang' => 'groovy'
                        ],
                        'order' => 'desc'
                    ]
                ],
                [
                    '_score' => 'desc'   //相关性排序
                ],
            ];
        }

        //折叠
        $this->params['body']['collapse'] = [
            'field' => 'lsin_package_num'
        ];

        //查询 聚合
        $this->params['body']['aggs'] = [
            'distinct_lsin' => [
                'terms' => [
                    'field' => 'lsin_package_num',
                    'size' => 50000,
                    "min_doc_count" => 2
                ],
                'aggs' => [
                    "agg_product_ids" => [
                        'terms' => [
                            'field' => 'entity_id',
                            'size' => 50000,
                        ]
                    ],
                ]
            ],
            'distinct_lsin_num' => [
                'cardinality' => [
                    'field' => 'lsin_package_num'
                ]
            ],
            'wholesaler_ids' => [
                'terms' => [
                    'field' => 'wholesaler_id',
                    'size' => 1000
                ]
            ],
        ];
//        Tools::log($this->params,'searchProduct2.log');
        $searchResult = $this->client->search($this->params);
//        Tools::log(json_encode($searchResult),'result.log');
        //组装商品
        $productsArray = $this->getProductSource($searchResult);
        //聚合结果分析
        $aggregationArray = $this->analyzeSearchResult($searchResult);

        //分页
        $pages = new Pagination(['totalCount' => $aggregationArray['result_num']]);
        $pages->setCurPage($page);
        $pages->setPageSize($pageSize);
        $result['pages'] = [
            'total_count' => $pages->getTotalCount(),
            'page' => $pages->getCurPage(),
            'last_page' => $pages->getLastPageNumber(),
            'page_size' => $pages->getPageSize(),
        ];

        $products = (new ProductHelper())->initWithProductArray($productsArray, $this->city)
            ->getTags()->getData();

        foreach ($products as &$product) {
            $lsinPackageNum = $product['lsin'] . '_' . $product['package_num'];
            if (isset($aggregationArray[$lsinPackageNum])) {
                $product['aggregation_num'] = $aggregationArray[$lsinPackageNum]['count'];
                $product['aggregation_product_ids'] = $aggregationArray[$lsinPackageNum]['product_ids'];
                //聚合商品
                if ($product['aggregation_num'] > 1) {
                    $product['status'] = Products::STATUS_ENABLED;//聚合商品状态都设为上架
                    $product['qty'] = 1;//库存一定大于零，因为客户端根据库存判断是否显示已卖光
                    $product['is_proprietary'] = 0;//聚合商品不打自营角标
                }
            }
        }

        $result['product_list'] = $products;
        //筛选供应商
        $wholesaler_ids = $this->getWholesalerIds($searchResult);
        $wholesaler_list = MerchantResourceAbstract::getStoreDetailBrief($wholesaler_ids, $this->customer->getAreaId(), 'sort desc');
        $result['wholesaler_list'] = $wholesaler_list;
        //返回
        $response = new searchProductResponse();
        $response->setFrom(Tools::pb_array_filter($result));
//        Tools::log($response->toArray(), 'searchProduct2.log');
        return $response;
    }

    private function analyzeSearchResult($searchResult)
    {

        $aggregations = $searchResult['aggregations'];
        $result_num = $aggregations['distinct_lsin_num']['value'];
        $aggregationArray['result_num'] = $result_num;
        $distinct_lsin_buckets = $aggregations['distinct_lsin']['buckets'];

        if (empty($distinct_lsin_buckets)) {
            return $aggregationArray;
        }
        foreach ($distinct_lsin_buckets as $distinct_lsin_bucket) {
            $lsin = $distinct_lsin_bucket['key'];
            $doc_count = $distinct_lsin_bucket['doc_count'];
            $aggregationArray[$lsin]['count'] = $doc_count;
            $aggregationArray[$lsin]['product_ids'] = [];
            $agg_product_id_buckets = $distinct_lsin_bucket['agg_product_ids']['buckets'];

            foreach ($agg_product_id_buckets as $agg_product_id_bucket) {
                $product_id = $agg_product_id_bucket['key'];
                array_push($aggregationArray[$lsin]['product_ids'], $product_id);
            }
        }
        return $aggregationArray;
    }

    private function getThirdCategoryIds($searchResult)
    {
        $third_category_buckets = $searchResult['aggregations']['third_category_ids']['buckets'];
        $third_category_ids = [];
        foreach ($third_category_buckets as $bucket) {
            array_push($third_category_ids, $bucket['key']);
        }
        return $third_category_ids;

    }

    /**
     * Author Jason Y.Wang
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
                $score_cal = isset($hit['sort'][1]) ? $hit['sort'][1] : 'null';;
                $score = $score_relation . ';' . $score_cal;
            }

            $product['score'] = $score;
            $products[] = $product;
        }
        return $products;
    }

    public function getCategory($filters, $aggs, $keys_only = true, $return_products = false)
    {
        // 商家id
//        $term_wholesaler_ids = ['terms' => ['wholesaler_id' => $wholesaler_ids]];
        if (!$return_products) {
            $this->params['body']['size'] = 0;
        }

        //过滤
        foreach ($filters as $k => $v) {
            if (empty($v)) continue;
            if (is_array($v)) {
                $this->params['body']['query']['bool']['must'] [] = ['terms' => [$k => $v]];
            } else {
                $this->params['body']['query']['bool']['must'] [] = ['term' => [$k => $v]];
            }
        }
//        $term_wholesaler_ids = ['terms' => $filters];
//        array_push($this->params['body']['query']['bool']['must'], $term_wholesaler_ids);

        //查询 聚合
        $this->params['body']['aggs'] = [
            'aggs_data' => [
                'terms' => [
                    'field' => $aggs,
                    'size' => 1000
                ]
            ]
        ];

//        Tools::log($this->params,'xxx.log');
        $categoryResult = $this->client->search($this->params);
//        Tools::log($categoryResult,'xxx.log');
        $buckets = $categoryResult['aggregations']['aggs_data']['buckets'];

        if ($keys_only) {
            $keys = [];
            foreach ($buckets as $bucket) {
                array_push($keys, $bucket['key']);
            }
            return $keys;
        }

        return $buckets;
//        $thirdCategoryIds = $this->getThirdCategoryIds($categoryResult);
//        $categoryData = Tools::getCategoryByThirdCategoryIds($thirdCategoryIds);
//
//        return $categoryData;

    }

    public function getBrand($wholesaler_id, $third_category_id)
    {
        $this->params['body']['size'] = 0;
        //供应商查询
        if ($wholesaler_id > 0) {
            $term_wholesaler_id = ['terms' => ['wholesaler_id' => [$wholesaler_id]]];
            array_push($this->params['body']['query']['bool']['must'], $term_wholesaler_id);
        } else {
            // 否则就查该区域的商家id
            $wholesalerIds = MerchantResourceAbstract::getWholesalerIdsByAreaId($this->area_id);
            $term_wholesaler_ids = ['terms' => ['wholesaler_id' => $wholesalerIds]];
            array_push($this->params['body']['query']['bool']['must'], $term_wholesaler_ids);
        }

        if ($third_category_id) {
            // 分类id
            $category_id = ['terms' => ['third_category_id' => [$third_category_id]]];
            array_push($this->params['body']['query']['bool']['must'], $category_id);
        }

        //查询 聚合
        $this->params['body']['aggs'] = [
            'brands' => [
                'terms' => [
                    'field' => 'brand_agg',
                    'size' => 1000
                ]
            ]
        ];

        Tools::log($this->params, 'getBrand.log');

        $brandResult = $this->client->search($this->params);

        $brand_buckets = $brandResult['aggregations']['brands']['buckets'];
        $brands = [];

        if (empty($brand_buckets)) {
            return $brands;
        }

        foreach ($brand_buckets as $bucket) {
            array_push($brands, $bucket['key']);
        }

        return $brands;

    }


    /**
     * Author Jason Y.Wang
     * @param $result
     * @return array
     * 获取查询结果的供应商
     */
    private function getWholesalerIds($result)
    {
        $buckets = $result['aggregations']['wholesaler_ids']['buckets'];
        $wholesaler_ids = [];

        if (empty($buckets)) {
            return $wholesaler_ids;
        }

        foreach ($buckets as $bucket) {
            array_push($wholesaler_ids, $bucket['key']);
        }
        return $wholesaler_ids;
    }

    /**
     * @param $keyword
     * @param $wholesaler_id
     * @return array
     * @deprecated since 0927
     */
    private function suggest($keyword, $wholesaler_id)
    {
        $suggest = [];

        //过滤商家
        if ($wholesaler_id) {
            $this->params['body']['query']['bool']['must'][] = ['term' => ['wholesaler_id' => $wholesaler_id]];
        }

        //查询 聚合
        $this->params['body']['aggs'] = [
            'brands' => [
                'terms' => [
                    'field' => 'brand_suggest',
                    'size' => 5
                ],
                'aggs' => [
                    'top_brand_hits' => [
                        'top_hits' => [
                            '_source' => [
                                "includes" => [
                                    'brand'
                                ]

                            ],
                            'size' => 1
                        ],

                    ]
                ]
            ],
            'names' => [
                'terms' => [
                    'field' => 'name_suggest',
                    'size' => 5
                ],
                'aggs' => [
                    'top_name_hits' => [
                        'top_hits' => [
                            '_source' => [
                                "includes" => [
                                    'brand'
                                ]
                            ],
                            'size' => 1
                        ],

                    ]
                ]
            ]
        ];

        //品牌提示
        $this->params['body']['query']['bool']['must'] = [
            [
                "multi_match" => [
                    "query" => $keyword,
                    "fields" => [
                        'brand'
                    ]
                ]
            ]

        ];

        $brand_result = $this->client->search($this->params);

        $brand_buckets = isset($brand_result['aggregations']['brands']['buckets']) ? $brand_result['aggregations']['brands']['buckets'] : [];
//        Tools::log($brand_buckets, 'searchSuggest.log');
        foreach ($brand_buckets as $bucket) {
            if (isset($bucket['key'])) {
                $suggest[] = $bucket['key'];
            }
        }

        //名称提示
        $this->params['body']['query']['bool']['must'] = [
            [
                "multi_match" => [
                    "query" => $keyword,
                    "fields" => [
                        'brand', 'name'
                    ]
                ]
            ]
        ];
        //查询 聚合
        $this->params['body']['aggs'] = [
            'names' => [
                'terms' => [
                    'field' => 'name_suggest',
                    'size' => 5
                ],
                'aggs' => [
                    'top_name_hits' => [
                        'top_hits' => [
                            '_source' => [
                                "includes" => [
                                    'brand', 'name'
                                ]
                            ],
                            'size' => 1
                        ],

                    ]
                ]
            ]
        ];

        $name_result = $this->client->search($this->params);
        $name_buckets = isset($name_result['aggregations']['names']['buckets']) ? $name_result['aggregations']['names']['buckets'] : [];
        foreach ($name_buckets as $bucket) {
            $hits = isset($bucket['top_name_hits']['hits']['hits']) ? $bucket['top_name_hits']['hits']['hits'] : null;
            if (is_array($hits)) {
                foreach ($hits as $hit) {
                    $name = isset($hit['_source']['name']) ? $hit['_source']['name'] : '';
                    $brand = isset($hit['_source']['brand']) ? $hit['_source']['brand'] : '';
                    if ($name && $brand) {
                        $suggest[] = $brand . ' ' . $name;
                    }
                }
            }
        }

        return array_unique($suggest);
    }

    /**
     * @param $lsin
     * @return mixed
     * 商品详情页更多同款商品
     */
    public function getAggregationProductsFromEs($lsin, $productId)
    {
        $this->params['body']['size'] = 20;

        //过滤商品状态
        $this->params['body']['query']['bool']['must'][] = ['term' => ['status' => 1]];
        $this->params['body']['query']['bool']['must'][] = ['term' => ['state' => 2]];

        $this->params['body']['query']['bool']['must'][] = ['terms' => ['lsin' => [$lsin]]];
        $this->params['body']['query']['bool']['must_not'][] = ['term' => ['entity_id' => $productId]];

        // 否则就查该区域的商家id
        $wholesalerIds = MerchantResourceAbstract::getWholesalerIdsByAreaId($this->area_id);
        $term_wholesaler_ids = ['terms' => ['wholesaler_id' => $wholesalerIds]];
        array_push($this->params['body']['query']['bool']['must'], $term_wholesaler_ids);

        $searchResult = $this->client->search($this->params);

        $productsArray = $this->getProductSource($searchResult);

        $products = (new ProductHelper())->initWithProductArray($productsArray, $this->city)
            ->getTags()->getData();
        return $products;
    }

    /**
     * @param $productIds
     * @param CustomerResponse $customer
     * @return mixed
     * 获取商品,按供应商权重排序
     */
    public function getProductsByIdsOrderByWholesalerWeight($productIds, $customer)
    {

        $term_product_ids = ['terms' => ['entity_id' => $productIds]];
        $this->params['body']['query']['bool']['must'][] = $term_product_ids;

        //传过来的排序
        $this->params['body']['sort'] = [
            [
                '_script' => [
                    'type' => 'number',
                    'script' => [
                        'inline' => '
                                        special_from_date = doc[\'special_from_date\'].value;
                                        special_to_date = doc[\'special_to_date\'].value;
                                        special_price = doc[\'special_price\'].value;
                                        price = doc[\'price\'].value;
                                        final_price = 0;
                                        if(special_from_date < date && special_to_date > date && special_price > 0) {
                                            final_price = special_price;
                                        }else{
                                            final_price = price;
                                        }
                                        return final_price;
                                    ',
                        'params' => [
                            'date' => floor(microtime(true) * 1000) //es中时间比较用毫秒级时间戳
                        ],
                        'lang' => 'groovy'
                    ],
                    'order' => 'asc'
                ]
            ],
            [
                'wholesaler_weight' => 'desc'   //相关性排序
            ],
        ];

        $searchResult = $this->client->search($this->params);
        $productsArray = $this->getProductSource($searchResult);
        $products = (new ProductHelper())->setCustomer($customer)->initWithProductArray($productsArray, $this->city)
            ->getTags()->getData();

        return $products;
    }

    /**
     * @param pieceTogetherOrderAreaRequest $request
     * @return mixed
     * 凑单专区商品
     */
    public function getPieceTogetherOrderAreaProducts($request)
    {
        //分页设置
        $page = $request->getPage() ?: 1;
        $pageSize = $request->getPageSize() ?: 20;
        $offset = ($page - 1) * $pageSize;
        $params['body']['size'] = $pageSize;
        $params['body']['from'] = $offset;

        $thematic_id = $request->getThematicId();

        //过滤商品状态
        $params['body']['query']['bool']['must'][] = ['term' => ['status' => 1]];
        $params['body']['query']['bool']['must'][] = ['term' => ['state' => 2]];

        // 商家id
        $term_wholesaler_ids = ['terms' => ['wholesaler_id' => [$request->getWholesalerId()]]];
        array_push($params['body']['query']['bool']['must'], $term_wholesaler_ids);

        if ($thematic_id == 1) {
            $range['min_price'] = 0;
            $range['max_price'] = 10;
        } else if ($thematic_id == 2) {
            $range['min_price'] = 10;
            $range['max_price'] = 30;
        } else if ($thematic_id == 3) {
            $range['min_price'] = 30;
            $range['max_price'] = 50;
        } else if ($thematic_id == 4) {
            $range['min_price'] = 50;
            $range['max_price'] = 1000000;
        } else {
            $range['min_price'] = 0;
            $range['max_price'] = 10;
        }

        $range['date'] = floor(microtime(true) * 1000); //es中时间比较用毫秒级时间戳

        $params['body']['query']['bool']['must'][] = [
            "script" => [
                "script" => [
                    "inline" => '
                        special_from_date = doc[\'special_from_date\'].value;
                        special_to_date = doc[\'special_to_date\'].value;
                        special_price = doc[\'special_price\'].value;
                        price = doc[\'price\'].value;
                        final_price = 0;
                        if(special_from_date < date && special_to_date > date) {
                            final_price = special_price;
                        }else{
                            final_price = price;
                        };
                        if(final_price > min_price && final_price <= max_price) {
                            return true;
                        }else{
                            return false;
                        }
                  ',
                    'lang' => 'groovy',
                    'params' => $range
                ]
            ]
        ];

        $params['body']['sort'] = [
            [
                '_script' => [
                    'type' => 'number',
                    'script' => [
                        'inline' => '
                                        special_from_date = doc[\'special_from_date\'].value;
                                        special_to_date = doc[\'special_to_date\'].value;
                                        special_price = doc[\'special_price\'].value;
                                        price = doc[\'price\'].value;
                                        final_price = 0;
                                        if(special_from_date < date && special_to_date > date && special_price > 0) {
                                            final_price = special_price;
                                        }else{
                                            final_price = price;
                                        }
                                        return final_price;
                                    ',
                        'params' => [
                            'date' => floor(microtime(true) * 1000) //es中时间比较用毫秒级时间戳
                        ],
                        'lang' => 'groovy'
                    ],
                    'order' => 'asc'
                ]
            ]
        ];

        $searchResult = $this->client->search($params);

        Tools::log($searchResult, 'getPieceTogetherOrderArea.log');

        $productsArray = $this->getProductSource($searchResult);

        $result['products'] = (new ProductHelper())->initWithProductArray($productsArray, $this->city)
            ->getTags()->getData();

        //分页
        $pages = new Pagination(['totalCount' => $searchResult['hits']['total']]);
        $pages->setCurPage($page);
        $pages->setPageSize($pageSize);
        $result['pagination'] = [
            'total_count' => $pages->getTotalCount(),
            'page' => $pages->getCurPage(),
            'last_page' => $pages->getLastPageNumber(),
            'page_size' => $pages->getPageSize(),
        ];

        return $result;
    }

    /**
     * @param array $lsins
     * @return mixed
     */
    public function getProductGroupProducts($lsins, $wholesalerId)
    {
        $this->params['body']['size'] = 20;

        $this->params['body']['query']['bool']['must'][] = ['terms' => ['lsin' => $lsins]];

        $this->params['body']['query']['bool']['must'][] = ['terms' => ['wholesaler_id' => [$wholesalerId]]];

        $searchResult = $this->client->search($this->params);

        $productsArray = $this->getProductSource($searchResult);

        $products = (new ProductHelper())->initWithProductArray($productsArray, $this->city)
            ->getTags()->getData();

        return $products;
    }

    /**
     * 获取供应商聚合结果
     * @param $filters 筛选条件
     * @param bool $keys_only 是否只返回供应商id列表
     * @return array
     */
    public function getStoresAggs($filters)
    {
//        if(!$return_products){
//            $this->params['body']['size'] = 0;
//        }
        $this->params['body']['size'] = 0;

        //过滤
        foreach ($filters as $k => $v) {
            if (empty($v)) continue;
            if (is_array($v)) {
                $this->params['body']['query']['bool']['must'] [] = ['terms' => [$k => $v]];
            } else {
                $this->params['body']['query']['bool']['must'] [] = ['term' => [$k => $v]];
            }
        }

        //查询 聚合
        $this->params['body']['aggs'] = [
            'aggs_data' => [
                'terms' => [
                    'field' => 'wholesaler_id',
                    'size' => 1000
                ],
                'aggs' => [
                    'sort' => [
                        'max' => [
                            'field' => 'wholesaler_weight'
                        ]
                    ]
                ]
            ]
        ];

//        Tools::log($this->params,'xxx.log');
        $categoryResult = $this->client->search($this->params);
//        Tools::log($categoryResult,'xxx.log');
        $buckets = $categoryResult['aggregations']['aggs_data']['buckets'];

        $data = [];
        foreach ($buckets as $bucket) {
            $data[$bucket['key']] = $bucket['sort']['value'];
        }
        arsort($data);
        $keys = array_keys($data);

        return $keys;
    }


}
