<?php
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-10-27
 * Time: 下午4:46
 * Email: henryzxj1989@gmail.com
 */
require(__DIR__ . '/../common/config/env.php');
require(__DIR__ . '/../framework/autoload.php');
require(__DIR__ . '/../common/config/bootstrap.php');
require(__DIR__ . '/../service/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../common/config/main.php'),
    require(__DIR__ . '/../common/config/main-local.php'),
    require(__DIR__ . '/../service/config/main.php'),
    require(__DIR__ . '/../service/config/main-local.php')
);