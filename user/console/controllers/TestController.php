<?php

namespace console\controllers;

use common\models\User;
use yii\console\Controller;
use yii\db\Expression;

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
        $expression = new Expression('((SELECT MAX(id) FROM user) * RAND() - 100)');
        $names = User::find()
            ->select(['avatar_url','nick_name'])
            ->where(['>', 'id', $expression])
            ->andWhere(['!=','avatar_url',''])
            ->andWhere(['!=','nick_name',''])
            ->limit(100)
            ->asArray()
            ->all();
        print_r($names);
    }

}