<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/17
 * Time: 11:46
 */
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'prod');

require(__DIR__ . '/config/env.php');
require(__DIR__ . '/framework/functions.php');
require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/config/bootstrap.php');
require(__DIR__ . '/protobuf/autoload.php');

$config = require(__DIR__ . '/config/server.php');

try {
    $app = new \framework\Server($config, $argc, $argv);
    $app->init();
} catch (\Exception $e) {
    echo $e;
} catch (\Error $e) {
    echo $e;
}