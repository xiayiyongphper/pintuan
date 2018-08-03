<?php

namespace backend\controllers;

use common\components\RabbitMQ;
use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use common\tools\Tools;
use common\tools\WeChatBaseApi;
use common\tools\Common;
use common\tools\Weixin;

class WjhtestController extends Controller
{
    /**
     * 测试控制器
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    /**
     * http://pintuan.laile.com/wjhtest/index
     * 测试小程序
     */
    public function actionIndex()
    {
        var_dump('测试提交');
        exit;
    }
}
