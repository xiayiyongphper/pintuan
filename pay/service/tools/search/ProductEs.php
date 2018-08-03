<?php

namespace service\components\search;

use common\models\Synonyms;
use framework\components\ToolsAbstract;
use framework\helper\MiniProHelper;
use service\components\bg2gb\big2gb;
use service\components\Tools;
use service\models\ProductHelper;
use service\resources\Exception;

/**
 * Author Jason Y.Wang
 * Class ElasticSearch
 * @package service\components\search
 */
class ProductEs extends EsBase
{
    protected $city;
    protected $platform;


    const DEFAULT_PAGE = 1;
    const DEFAULT_PAGE_SIZE = 30;
    //排序方式
    const ORDER_PRICE_ASC = 1;
    const ORDER_PRICE_DESC = 2;
    const ORDER_SOLD_QTY_DESC = 3;
    const ORDER_DEFAULT_WITH_KEYWORD = 4;
    const ORDER_DEFAULT = 5;

    public $product_sales_type = [
        1 => 8, //自营商品 0b1000
        2 => 2, //普通商品 0b0010
    ];


    public function __construct($city,$platform = PLATFORM_APP)
    {

        $index = 'products';
        if($platform == PLATFORM_MINI_PROGRAM){
            $index = 'mini_products';
        }

        Tools::log($city, 'ProductEs.log');
        Tools::log($index, 'ProductEs.log');

        parent::__construct($index,$city);
        $this->city = $city;

        $this->platform = $platform;
        $date = Tools::getDate()->date();
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
            $keyword = $convert->chg_utfcode($keyword,'gb2312');
            //替换关键词
            $synonyms = Synonyms::findOne(['keyword' => $keyword]);
            if ($synonyms && $synonyms->replace_words) {
                $keyword = $synonyms->replace_words;
            }

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
     * 折叠
     * @param $field
     */
    public function setCollapse($field)
    {
        $this->params['body']['collapse'] = [
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

    public function getProducts($page, $pageSize, $sortType = null){
        //分页设置
        $page = $page ?: self::DEFAULT_PAGE;
        $pageSize = $pageSize ?: self::DEFAULT_PAGE_SIZE;
        $this->setPageConf($pageSize, $page);

        //排序
        if ($sortType) {
            $this->setSort($sortType);
        }

        $searchResult = $this->doSearch();
        ToolsAbstract::log($searchResult,'result.log');

        $total = $searchResult['hits']['total'];
        //组装商品
        $productsArray = $this->getProductSource($searchResult);
        $products = (new ProductHelper(PLATFORM_MINI_PROGRAM))->initWithProductArray($productsArray, $this->city)
            ->getTags()->getData();

        return [$total,$products];
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
            //es分开了，但是数据库没分开，查出来的商品还要加上小程序特有字段
            $diffFields = MiniProHelper::getProductDiffFields();
            foreach ($diffFields as $fieldName){
                $product['wx_'.$fieldName] = $product[$fieldName];
            }
//            $product['wx_price'] = $product['price'];
//            $product['wx_special_price'] = $product['special_price'];
//            $product['wx_special_from_date'] = $product['special_from_date'];
//            $product['wx_special_to_date'] = $product['special_to_date'];
//            $product['wx_status'] = $product['status'];
//            $product['wx_restrict_daily'] = $product['restrict_daily'];
//            $product['wx_shelf_from_date'] = $product['shelf_from_date'];
//            $product['wx_shelf_to_date'] = $product['shelf_to_date'];
//            $product['wx_minimum_order'] = $product['minimum_order'];
//            $product['wx_subsidies_wholesaler'] = $product['subsidies_wholesaler'];
//            $product['wx_rule_id'] = $product['rule_id'];
            $products[] = $product;
        }
        return $products;
    }

}
