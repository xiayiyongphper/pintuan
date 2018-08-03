<?php
/**
 * Created by master.
 * User: Ryan Hong
 * Date: 2018/4/10
 * Time: 16:58
 */

namespace common\helper;

use common\models\Category;
use common\models\product\Product;
use common\models\product\Specification;
use Elasticsearch\Client;
use framework\components\ToolsAbstract;

/**
 * Class EsproductHelper
 * @package common\helpers
 */
class EsProductHelper
{
    const STEP = 1000;
    const INDEX_PINTUAN_PRODUCT = 'pintuan_products';
    const TYPE = 'product';

    private $max_id;
    /** @var Client $client */
    private $client;

    public function __construct()
    {
        $this->max_id = Product::find()->max('id');
        $this->client = \Yii::$app->get('elasticSearch');
    }

    public function updateByProductIds($productIds)
    {
        $products = $this->getProductByIds($productIds);

        $params = $this->formatRequest('update', $products);

        if (empty($params)) {
            return false;
        }

        $result = $this->client->bulk($params);
        if(!empty($result['errors'])){
            ToolsAbstract::log($params, 'updateProductCache.log');
            ToolsAbstract::log($result, 'updateProductCache.log');
        }

        return true;
    }

    public function createByProductIds($productIds)
    {
        $products = $this->getProductByIds($productIds);

        $params = $this->formatRequest('index', $products);

        if (empty($params)) {
            return false;
        }

        $result = $this->client->bulk($params);

        if(!empty($result['errors'])){
            ToolsAbstract::log($params, 'updateProductCache.log');
            ToolsAbstract::log($result, 'updateProductCache.log');
        }
        return true;
    }

    public function deleteByProductIds($productIds)
    {
        foreach ($productIds as $productId) {
            $this->client->delete([
                'id' => $productId,
                'index' => self::INDEX_PINTUAN_PRODUCT,
                'type' => self::TYPE,
            ]);
        }
        return true;
    }

    public function index()
    {
        $this->action('index');
    }

    public function update()
    {
        $this->action('update');
    }

    protected function action($action)
    {
        //批量更新
        for ($i = 0; $i <= $this->max_id; $i += self::STEP) {
            $products = $this->getProductByIdRange($i);
            $params = $this->formatRequest($action, $products);
            if (empty($params)) {
                continue;
            }
            $result = $this->client->bulk($params);
            if (!empty($result['errors'])) {
                ToolsAbstract::log($result, 'EsProductHelper.log');
            }
        }
    }

    protected function formatRequest($action, $products)
    {
        $params = [];
        foreach ($products as $product) {
            if ($action == 'index') {
                $updateParam = $this->indexParams($product);
            } else {
                $updateParam = $this->updateParams($product);
            }
            $params['body'][] = $updateParam['action'];
            $params['body'][] = $updateParam['data'];
        }
        return $params;
    }

    private function getProductByIds($product_ids)
    {
        $speSubQuery = Specification::find()->select('product_id,min(price) as min_price')
            ->where(['product_id' => $product_ids])
            ->groupBy('product_id');
        $products = Product::find()
            ->alias('p')
            ->leftJoin(['c' => Category::tableName()], 'p.third_category_id = c.id')
            ->leftJoin(['s' => $speSubQuery], 'p.id = s.product_id')
            ->select("p.*,c.path,s.min_price")
            ->where(['p.id' => $product_ids]);

        $products = $products->asArray()->all();

        foreach ($products as &$product) {

            if (empty($product['path'])) continue;

            $parts = explode('/', $product['path']);
            if ($parts[0] == 1) {
                array_shift($parts);
            }

            if (isset($parts[0])) {
                $product['first_category_id'] = $parts[0];
            }
            if (isset($parts[1])) {
                $product['second_category_id'] = $parts[1];
            }
            unset($product['path']);
        }

        return $products;
    }

    private function getProductByIdRange($start)
    {
        $speSubQuery = Specification::find()->select('product_id,min(price) as min_price')
            ->where(['between', 'product_id', $start, $start + self::STEP])
            ->groupBy('product_id');
        $products = Product::find()
            ->alias('p')
            ->leftJoin(['c' => Category::tableName()], 'p.third_category_id = c.id')
            ->leftJoin(['s' => $speSubQuery], 'p.id = s.product_id')
            ->select("p.*,c.path,s.min_price")
            ->where(['between', 'p.id', $start, $start + self::STEP]);

        $products = $products->asArray()->all();

        foreach ($products as &$product) {

            if (empty($product['path'])) continue;

            $parts = explode('/', $product['path']);
            if ($parts[0] == 1) {
                array_shift($parts);
            }

            if (isset($parts[0])) {
                $product['first_category_id'] = $parts[0];
            }
            if (isset($parts[1])) {
                $product['second_category_id'] = $parts[1];
            }
            unset($product['path']);
        }

        return $products;
    }

    private function updateParams($product)
    {
        $action = [
            'update' => [
                '_index' => self::INDEX_PINTUAN_PRODUCT,
                '_type' => self::TYPE,
                '_id' => $product['id']
            ]
        ];
        $data = [
            'doc' => $product,
        ];
        return ['action' => $action, 'data' => $data];
    }

    private function indexParams($product)
    {
        $action = [
            'index' => [
                '_index' => self::INDEX_PINTUAN_PRODUCT,
                '_type' => self::TYPE,
                '_id' => $product['id']
            ]
        ];
        return ['action' => $action, 'data' => $product];
    }
}