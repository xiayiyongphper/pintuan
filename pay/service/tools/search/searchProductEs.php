<?php
/**
 * Created by PhpStorm.
 * User: Ryan Hong
 * Date: 2018/1/16
 * Time: 17:23
 */

namespace service\components\search;

use framework\components\ToolsAbstract;
use service\components\search\ElasticSearchExt2;
use service\message\customer\CustomerResponse;
use service\resources\MerchantResourceAbstract;
use common\models\Products;
use service\models\ProductHelper;

/**
 * Class searchProductEs
 * @package service\components\search
 */
class searchProductEs extends ElasticSearchExt2
{
    //排序方式
    const ORDER_PRICE_ASC = 1;
    const ORDER_PRICE_DESC = 2;
    const ORDER_SOLD_QTY_DESC = 3;
    const ORDER_DEFAULT_WITH_KEYWORD = 4;
    const ORDER_DEFAULT = 5;

    protected $areaId;

    /**
     * searchProductEs constructor.
     * @param CustomerResponse $customer
     */
    public function __construct($customer)
    {
        parent::__construct($customer->getCity());
        $this->areaId = $customer->getAreaId();
    }

    public function getMergeProducts($page, $pageSize, $sortType = null)
    {
        $this->setPageConf(0);
        //按lsin*package_num分组，取得每组价格最低的商品id
        $this->params['body']['aggs'] = [
            'distinct_lsin' => [
                'terms' => [
                    'field' => 'lsin_package_num',
                    'size' => 1000,
//                        "min_doc_count" => 2
                ],
                'aggs' => [
                    "agg_product_ids" => [
                        'terms' => [
                            'field' => 'entity_id',
                            'size' => 50000,
                        ]
                    ],
                    "min_price_hit" => [
                        'top_hits' => [
                            'sort' => [
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
                            ],
                            '_source' => ['includes' => ['entity_id']],
                            'size' => 1
                        ]
                    ],
                ],
            ],
            'distinct_lsin_num' => [
                'cardinality' => [
                    'field' => 'lsin_package_num'
                ]
            ],
            //供应商应该是所有符合筛选条件的商品的供应商，而不是分组中最低价的商品的供应商，所以在这里查出来
            'wholesaler_ids' => [
                'terms' => [
                    'field' => 'wholesaler_id',
                    'size' => 1000
                ]
            ],
        ];

        $aggsResult = $this->doSearch();
//        ToolsAbstract::log($aggsResult,'result.log');

        $minPriceProductIds = [];
        foreach ($aggsResult['aggregations']['distinct_lsin']['buckets'] as $bucket) {
            $minPriceProductIds [] = $bucket['min_price_hit']['hits']['hits'][0]['_source']['entity_id'];
        }

        //聚合结果分析
        $aggregationArray = $this->analyzeSearchResult($aggsResult);
        //筛选供应商
        $wholesaler_ids = $this->getWholesalerIds($aggsResult);
        $wholesaler_list = MerchantResourceAbstract::getStoreDetailBrief($wholesaler_ids, $this->areaId, 'sort desc');

        //用查出的分组中价格最低的商品id，再次筛选结果
        $this->addFilter('entity_id', $minPriceProductIds);
        unset($this->params['body']['aggs']);

        //分页设置
        $page = $page ?: self::DEFAULT_PAGE;
        $pageSize = $pageSize ?: self::DEFAULT_PAGE_SIZE;
        $this->setPageConf($pageSize, $page);

        //排序
        if ($sortType) {
            $this->setSort($sortType);
        }

        $searchResult = $this->doSearch();
//        ToolsAbstract::log($searchResult,'result.log');

        //组装商品
        $productsArray = $this->getProductSource($searchResult);
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
                    //需求又改了,聚合商品也可以有自营角标
                    //$product['is_proprietary'] = 0;//聚合商品不打自营角标
                }
            }
        }

        return [$products, $aggregationArray['result_num'], $wholesaler_list];
    }

    /**
     * 排序
     * @param $sortType
     * @param $sort
     */
    public function setSort($sortType)
    {
        //如果选了排序方式，按照选中的排序方式排序，不管是否有关键词；如果是综合排序（即没有选择排序方式）,有关键词按照相关排序，没有关键词按照权重排序
        if ($sortType == self::ORDER_PRICE_ASC || $sortType == self::ORDER_PRICE_DESC) {
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
                        'order' => $sortType == self::ORDER_PRICE_ASC ? 'asc' : 'desc'
                    ]
                ],   //自定义排序
                [
                    '_score' => 'desc'   //相关性排序
                ],
            ];

        } else if ($sortType == self::ORDER_SOLD_QTY_DESC) {
            $this->params['body']['sort'] = [
                [
                    'sold_qty' => 'desc'
                ]
            ];
        } else if ($sortType == self::ORDER_DEFAULT_WITH_KEYWORD) {
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
                $score_cal = isset($hit['sort'][1]) ? $hit['sort'][1] : 'null';
                $score = $score_relation . ';' . $score_cal;
            }

            $product['score'] = $score;
            $products[] = $product;
        }
        return $products;
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
            $lsin_package_num = $distinct_lsin_bucket['key'];
            $doc_count = $distinct_lsin_bucket['doc_count'];
            if ($doc_count < 2) continue;

            $aggregationArray[$lsin_package_num]['count'] = $doc_count;
            $aggregationArray[$lsin_package_num]['product_ids'] = [];
            $agg_product_id_buckets = $distinct_lsin_bucket['agg_product_ids']['buckets'];

            foreach ($agg_product_id_buckets as $agg_product_id_bucket) {
                $product_id = $agg_product_id_bucket['key'];
                array_push($aggregationArray[$lsin_package_num]['product_ids'], $product_id);
            }
        }

        return $aggregationArray;
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

    public function convertProductResult($searchResult)
    {
        return $this->getProductSource($searchResult);
    }
}