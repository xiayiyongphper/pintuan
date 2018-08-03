<?php
namespace console\controllers;

use common\models\Product;
use yii\console\Controller;
use yii\db\Expression;

class IndexController extends Controller
{
    public function actionIndex()
    {
        $expression = new Expression('((SELECT MAX(id) FROM product) * RAND() - 100)');
        $products = Product::find()
            ->select(['id','name'])
            ->where(['>', 'id', $expression])
            ->limit(100)
            ->asArray()
            ->all();
        print_r($products);
    }
}