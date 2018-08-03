<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/6/19
 * Time: 11:45
 */

namespace service\tools\search;
use service\resources\Exception;
use service\tools\Tools;

/**
 * Class esBase
 * @package service\tools\search
 */
class esBase
{
    protected $client;
    protected $params;
    protected $index;//index
    protected $type;//type

    const DEFAULT_PAGE = 1;
    const DEFAULT_PAGE_SIZE = 30;

    public function __construct($index,$type)
    {
        Tools::log(ENV_ES_CLUSTER_HOSTS,'es.log');
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
     * @param $type
     */
    public function addFilter($field, $value,$type)
    {
        if (empty($value)) return;

        if (!is_array($value)) {
            $this->params['body']['query']['bool'][$type] [] = ['term' => [$field => $value]];
        } elseif (count($value) == 1) {
            $this->params['body']['query']['bool'][$type] [] = ['term' => [$field => current($value)]];
        } else {
            $this->params['body']['query']['bool'][$type] [] = ['terms' => [$field => $value]];
        }
    }

    /**
     * @param $field
     * @param $value
     */
    public function addMustFilter($field, $value)
    {
        $this->addFilter($field,$value,'must');
    }

    /**
     * @param $field
     * @param $value
     */
    public function addFilterMustNot($field, $value)
    {
        $this->addFilter($field,$value,'must_not');
    }

    /**
     * 翻页设置
     * @param $page
     * @param $pageSize
     */
    public function setPageConf($pageSize = null , $page = null)
    {
        if(is_null($pageSize)){
            $pageSize = static::DEFAULT_PAGE_SIZE;
        }
        if(is_null($page)){
            $page = static::DEFAULT_PAGE;
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
            Tools::logException(new \Exception(json_encode($result['errors']),10010));
            Exception::throwException(Exception::SERVICE_NOT_AVAILABLE);
        }

        return $result;
    }
}