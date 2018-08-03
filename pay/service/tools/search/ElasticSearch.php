<?php

namespace service\components\search;

use common\models\Products;
use Elasticsearch\ClientBuilder;
use framework\data\Pagination;
use service\components\Tools;
use service\message\merchant\searchProductResponse;
use service\models\ProductHelper;
use service\resources\MerchantResourceAbstract;
use yii\helpers\ArrayHelper;

/**
 * Author Jason Y.Wang
 * Class ElasticSearch
 * @package service\components\search
 */
class ElasticSearch extends Search
{
    public static $client = null;
    /**
     * Function: search
     * Author: Jason Y. Wang
     * 搜索功能
     * @return mixed
     */
    public function search()
    {
        //es设置
//        $hosts = \Yii::$app->params['es_cluster']['hosts'];
//        $client = ClientBuilder::create()
//                ->setHosts($hosts)
//                ->build();

        $client = \Yii::$app->get('elasticSearch');
        //返回数据
        $result = [];
        $params = [];
        //城市
        $city = $this->customer->getCity();
        $area_id = $this->customer->getAreaId();
        //参数
        $keyword = $this->searchRequest->getKeyword();
        //过滤供应商
        $wholesaler_id = $this->searchRequest->getWholesalerId();
        //过滤品牌
        $brands = $this->searchRequest->getBrand();

        //过滤分类
        $category_id = $this->searchRequest->getCategoryId();
        $category_level = $this->searchRequest->getCategoryLevel() ?: Tools::getCategoryLevelByID($category_id);

        //索引名称
        $params['index'] = 'products';
        //查询范围
        $params['type'] = $city;
        //过滤商品状态
        $params['body']['query']['bool']['must'][] = ['term' => ['status' => 1]];
        $params['body']['query']['bool']['must'][] = ['term' => ['state' => 2]];

        //分数计算方式
        $params['body']['explain'] = false;

        //加入上架开始结束时间
        $params['body']['query']['bool']['must'][] = [
            "script" => [
                "script" => [
                    "inline" => '
                        shelf_from_date = doc[\'shelf_from_date\'].value;
                        shelf_to_date = doc[\'shelf_to_date\'].value;
                        if(shelf_from_date < date && shelf_to_date > date) {
                            return true;
                        }else{
                            return false;
                        }   
                    ',
                    'lang' => 'groovy',
                    'params' => [
                        'date' => floor(microtime(true) * 1000) //es中时间比较用毫秒级时间戳
                    ],
                ]
            ]
        ];

        //分页设置
        $page = $this->searchRequest->getPage() ?: 1;
        $pageSize = $this->searchRequest->getPageSize() ?: 20;
        $offset = ($page - 1) * $pageSize;
        $params['body']['size'] = $pageSize;
        $params['body']['from'] = $offset;

        //关键字查询
        if ($keyword) {
            if (is_numeric($keyword) && strlen($keyword) >= 4) {
                $params['body']['query']['bool']['must'][] = [
                    "wildcard" => [
                        "barcode" => "*{$keyword}*",
                    ]
                ];
            } else {
                $params['body']['query']['bool']['must'][] = [
                    "multi_match" => [
                        "query" => $keyword,
                        "fields" => [
                            'brand^8', 'name^3', 'first_category_name', 'second_category_name',
                            'third_category_name', 'specification_num_unit^2', 'promotion_text','specification_num','search_text'
                        ]
                    ]
                ];
            }

        }

        //供应商查询
        if ($wholesaler_id > 0) {
            $term_wholesaler_id = ['terms' => ['wholesaler_id' => [$wholesaler_id]]];
            array_push($params['body']['query']['bool']['must'], $term_wholesaler_id);
        } else {
            // 否则就查该区域的商家id
            $wholesalerIds = MerchantResourceAbstract::getWholesalerIdsByAreaId($area_id);
            $term_wholesaler_ids = ['terms' => ['wholesaler_id' => $wholesalerIds]];
            array_push($params['body']['query']['bool']['must'], $term_wholesaler_ids);
        }


        // 品牌
        if ($brands) {
            $brands = array_filter(explode(';', $brands));
            $term_brands = ['terms' => ["brand" => $brands]];
            array_push($params['body']['query']['bool']['must'], $term_brands);

        }

        if ($category_id && $category_level) {
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

            array_push($params['body']['query']['bool']['must'], ['term' => [$category => $category_id]]);
        }

        //Tools::log($params,'searchProduct.log');

        //销量，价格排序
        $field = $this->searchRequest->getField();
        $sort = $this->searchRequest->getSort() == "asc" ? 'asc' : 'desc';

        //如果选了排序方式，按照选中的排序方式排序，不管是否有关键词；如果是综合排序（即没有选择排序方式）,有关键词按照相关排序，没有关键词按照权重排序
        if ($field == 'price') {
            //传过来的排序
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
                        'order' => $sort
                    ]
                ],   //自定义排序
                [
                    '_score' => 'desc'   //相关性排序
                ],
            ];

        } else if ($field == 'sold_qty') {
            $params['body']['sort'] = [
                [
                    $field => $sort
                ]
            ];
        } else if ($keyword) {
            //默认排序，自定义排序
            $params['body']['sort'] = [
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
            $params['body']['sort'] = [
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


        //查询 聚合
        $params['body']['aggs'] = [
            'wholesaler_ids' => [
                'terms' => [
                    'field' => 'wholesaler_id',
                    'size' => 1000
                ]
            ]
        ];


        $searchResult = $client->search($params);
//        Tools::log('__destruct','elastic.log');
//        Tools::log($searchResult, 'searchProduct.log');
        //分页
        $pages = new Pagination(['totalCount' => $searchResult['hits']['total']]);
        $pages->setCurPage($page);
        $pages->setPageSize($pageSize);
        $result['pages'] = [
            'total_count' => $pages->getTotalCount(),
            'page' => $pages->getCurPage(),
            'last_page' => $pages->getLastPageNumber(),
            'page_size' => $pages->getPageSize(),
        ];
        //组装商品
        $productsArray = $this->getProductSource($searchResult);
        $products = (new ProductHelper())->initWithProductArray($productsArray, $city)
            ->getTags()->getData();
        $result['product_list'] = $products;
        //筛选供应商
        $wholesaler_ids = $this->getWholesalerIds($searchResult);
        $wholesaler_list = MerchantResourceAbstract::getStoreDetailBrief($wholesaler_ids, $this->customer->getAreaId(), 'sort desc');
        $result['wholesaler_list'] = $wholesaler_list;
        //返回
        $response = new searchProductResponse();
        $response->setFrom(Tools::pb_array_filter($result));
        return $response;
    }

    /**
     * Author Jason Y.Wang
     * @param $searchResult
     * @return array
     * 从返回结果中拿到商品信息
     */
    protected function getProductSource($searchResult)
    {
        $hits = $searchResult['hits']['hits'];
        $products = [];
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

    /**
     * Author Jason Y.Wang
     * @param $result
     * @return array
     * 获取查询结果的供应商
     */
    protected function getWholesalerIds($result)
    {
        $buckets = $result['aggregations']['wholesaler_ids']['buckets'];
        $wholesaler_ids = [];
        foreach ($buckets as $bucket) {
            array_push($wholesaler_ids, $bucket['key']);
        }
        return $wholesaler_ids;
    }

    public static function suggest($customer, $keyword,$wholesaler_id)
    {
        $suggest = [];

        //es设置
        $hosts = \Yii::$app->params['es_cluster']['hosts'];
        if(!self::$client){
            self::$client = ClientBuilder::create()
                ->setHosts($hosts)
                ->build();
        }
        //索引名称
        $params['index'] = 'products';
        //查询范围
        $params['type'] = $customer->getCity();
        $params['body']['size'] = 0;
        //过滤商品状态
        $params['body']['query']['bool']['must'][] = ['term' => ['status' => 1]];
        $params['body']['query']['bool']['must'][] = ['term' => ['state' => 2]];
        //过滤商家
        if($wholesaler_id){
            $params['body']['query']['bool']['must'][] = ['term' => ['wholesaler_id' => $wholesaler_id]];
        }

        //查询 聚合
        $params['body']['aggs'] = [
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
        $params['body']['query']['bool']['must'] = [
            [
                "multi_match" => [
                    "query" => $keyword,
                    "fields" => [
                        'brand'
                    ]
                ]
            ]

        ];

        $brand_result = self::$client->search($params);

        $brand_buckets = isset($brand_result['aggregations']['brands']['buckets']) ? $brand_result['aggregations']['brands']['buckets'] : [];
//        Tools::log($brand_buckets, 'searchSuggest.log');
        foreach ($brand_buckets as $bucket) {
            if (isset($bucket['key'])) {
                $suggest[] = $bucket['key'];
            }
        }

        //名称提示
        $params['body']['query']['bool']['must'] = [
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
        $params['body']['aggs'] = [
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

        $name_result = self::$client->search($params);
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

    function __destruct()
    {
//        Tools::log('__destruct','elastic.log');
    }

}
