<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/17
 * Time: 11:46
 */
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

require(__DIR__ . '/common/config/env.php');
require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/lelaisoft/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/common/config/bootstrap.php');
require(__DIR__ . '/service/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/common/config/main.php'),
    require(__DIR__ . '/common/config/main-local.php'),
    require(__DIR__ . '/service/config/main.php'),
    require(__DIR__ . '/service/config/main-local.php')
);
try {
    $client = new \service\tools\client\SOAClient();

    $client->connect('127.0.0.1', 8050);
    //$client->connect('172.16.30.104', 18050);
    //$client->connect('172.16.30.106', 18050);
//    $client->connect('172.16.30.113', 18050);

} catch (\Exception $e) {
    echo $e;
} catch (\Error $e) {
    echo $e;
}