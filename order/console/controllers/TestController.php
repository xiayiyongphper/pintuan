<?php

namespace console\controllers;

use common\models\Order;
use yii\console\Controller;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 17-2-9
 * Time: 上午10:16
 */
class TestController extends Controller
{
    public function actionIndex()
    {
        $orders = Order::find()
            ->limit(10)->all();
        print_r($orders);

    }

}