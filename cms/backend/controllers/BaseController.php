<?php

namespace backend\controllers;

use yii\filters\VerbFilter;
use yii\web\Controller;

class BaseController extends Controller
{

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

    public function init()
    {
        $this->enableCsrfValidation = false;
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
     * @param $data
     * @param int $code
     * @param string $msg
     */
    public function echoJson($data, $code = 0, $msg = '查询成功')
    {
        $res = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
