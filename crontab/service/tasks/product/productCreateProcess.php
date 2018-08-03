<?php

namespace service\tasks\product;

use common\helper\EsProductHelper;
use framework\components\ToolsAbstract;
use service\tasks\TaskService;

/**
 * @see MQAbstract::MSG_GROUP_SUB_PRODUCT_UPDATE
 * @package service\mq_processor\product
 */
class productCreateProcess extends TaskService
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
        (new EsProductHelper())->createByProductIds($productIds);
        return true;
    }
}