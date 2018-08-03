<?php
/**
 * Created by product.
 * User: Ryan Hong
 * Date: 2018/6/19
 * Time: 14:34
 */

namespace service\resources\product\v1;

use common\models\Topic;
use framework\Exception;
use message\product\ProductListReq;
use message\product\ProductListRes;
use service\resources\ResourceAbstract;
use service\tools\search\productEs;
use service\tools\Tools;

/**
 * Class getProductList
 * @package service\resources\product\v1
 */
class topicProductList extends ResourceAbstract
{
    /** @var  ProductListReq */
    protected $request;

    public function run($data)
    {
        $this->doInit($data);

        $topic = Topic::find()->select('products')
            ->where(['id' => $this->request->getTopicId(), 'status' => 1])
            ->asArray()->one();

        if (!$topic || !$topic['products']) {
            Exception::systemNotFound();
        }

        $productIds = array_map('intval', explode(',', $topic['products']));

        $params = [
            'id'            => $productIds,
            'wholesaler_id' => $this->request->getWholesalerIds(),
            'activity_id'   => $this->request->getActivityId(),
            'page'          => $this->request->getPage(),
            'page_size'     => $this->request->getPageSize(),
        ];
        $es = new productEs();
        $result = $es->search($params);

        $this->response->setFrom(Tools::pb_array_filter($result));

        return $this->response;
    }

    public static function request()
    {
        return new ProductListReq();
    }

    public static function response()
    {
        return new ProductListRes();
    }
}