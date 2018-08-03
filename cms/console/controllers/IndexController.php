<?php
namespace console\controllers;


use backend\models\User;
use yii\console\Controller;

class IndexController extends Controller
{

    public function actionIndex()
    {
        $user = new User();
        $user->username = 'lelai';
        $user->email = 'wangyang@lelai.com';
        $user->setPassword('123456');
        $user->save();
    }
}
