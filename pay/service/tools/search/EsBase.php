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
class EsBase
{
    protected $client;
    protected $params;
    protected $index;//index
    protected $type;//type

    const DEFUALT_PAGE = 1;
    const DEFUALT_PAGE_SIZE = 30;

    public function __construct($index,$type)
    {
        $this->client = \Yii::$app->get('elasticSearch');
        $this->index = $index;
        $this->type = $type;

        //索引名称
        $this->params['index'] = $this->index;
        //查询范围
        $this->params['type'] = $this->type;
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
     * 翻页设置
     * @param $page
     * @param $pageSize
     */
    public function setPageConf($pageSize = null , $page = null)
    {
        if(is_null($pageSize)){
            $pageSize = static::DEFUALT_PAGE_SIZE;
        }
        if(is_null($page)){
            $page = static::DEFUALT_PAGE;
        }

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
            'field' => $field
        ];
    }

    /**
     * 聚合
     * @param $field
     */
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
        Tools::log($this->params, 'params.log');
        $result = $this->client->search($this->params);
        if(!empty($result['errors'])){
            ToolsAbstract::logException(new \Exception(json_encode($result['errors']),10010));
            Exception::serviceNotAvailable();
        }

        return $result;
    }

}
