<?php

namespace console\controllers;

use common\models\AvailableCity;
use framework\components\ToolsAbstract;
use yii\console\Controller;

/**
 * Site controller
 */
class IndexController extends Controller
{
    public function actionIndex(){
        $mq = ToolsAbstract::getRabbitMq();
        $data = [
            'route' => 'product.update',
            'params' => 123
        ];

        $mq->publish($data);
    }
}
