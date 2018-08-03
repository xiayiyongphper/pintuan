<?php

/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/10/13
 * Time: 14:43
 */

namespace service\tasks\product;

use common\helper\EsProductHelper;
use common\models\product\Product;
use framework\components\ToolsAbstract;
use service\tasks\TaskService;


class productUpdateProcess extends TaskService
{
    /**
     * @inheritdoc
     */
    public function run($data)
    {
        if (empty($data['product_id'])) {
            ToolsAbstract::log('商品id为空', 'product_update_process.log');
            return '商品id为空';
        }
        $productIds = $data['product_id'];

        //删除商品缓存
        $redis = ToolsAbstract::getRedis();
		foreach ($productIds as $productId) {
            $num = $redis->hDel(Product::PRODUCT_KEY, $productId);
            ToolsAbstract::log('redis更新数量:' . $num, 'product_update_process.log');
        }

        //更新es
        (new EsProductHelper())->updateByProductIds($productIds);

        return true;
    }
}