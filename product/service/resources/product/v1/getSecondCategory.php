<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\product\v1;
use common\models\Category;
use message\product\CategoryRes;
use message\product\SecondCategoryReq;
use service\resources\ResourceAbstract;
use service\tools\search\esBase;
use service\tools\search\productEs;
use service\tools\Tools;

/**
 * Class getSecondCategory
 * @package service\resources\product\v1
 */
class getSecondCategory extends ResourceAbstract
{
    /** @var  SecondCategoryReq */
    protected $request;

    public function run($data)
    {
        $this->doInit($data);

        $elasticSearch = new productEs();
        $elasticSearch->setPageConf(0);

        $elasticSearch->addMustFilter('wholesaler_id', $this->request->getWholesalerIds());
        $agg = "second_category_id";
        $elasticSearch->addAggs($agg);
        $categoryResult = $elasticSearch->doSearch();
        $buckets = $categoryResult['aggregations'][$agg]['buckets'];

        $categoryIds = [];//二级分类id集合
        foreach ($buckets as $bucket) {
            array_push($categoryIds, $bucket['key']);
        }

        $categories = Category::find()->where(['id' => $categoryIds])->asArray()->all();
        $this->response->setFrom(['category_list' => $categories]);
        return $this->response;
    }

    public static function request()
    {
        return new SecondCategoryReq();
    }

    public static function response()
    {
        return new CategoryRes();
    }
}