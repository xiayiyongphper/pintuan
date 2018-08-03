<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset'
    ],
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager'
        ],
        'redisCache' => [
            'class' => 'common\tools\Redis',
            'options' => [
                'host' => '127.0.0.1',
                'port' => 6379,
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=172.16.30.111;dbname=pintuan_common',
            'username' => 'book',
            'password' => '123456',
            'charset' => 'utf8',
        ],
        'productDb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=172.16.30.111;dbname=pintuan_product',
            'username' => 'book',
            'password' => '123456',
            'charset' => 'utf8',
        ],
        'orderDb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=172.16.30.111;dbname=pintuan_order',
            'username' => 'book',
            'password' => '123456',
            'charset' => 'utf8',
        ],
        'userDb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=172.16.30.111;dbname=pintuan_user',
            'username' => 'book',
            'password' => '123456',
            'charset' => 'utf8',
        ],
        'wholesalerDb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=172.16.30.111;dbname=pintuan_wholesaler',
            'username' => 'book',
            'password' => '123456',
            'charset' => 'utf8',
        ],
        'merchantDb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=172.16.30.111;dbname=lelai_slim_merchant',
            'username' => 'book',
            'password' => '123456',
            'charset' => 'utf8',
        ],
        'customerDb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=172.16.30.111;dbname=lelai_slim_customer',
            'username' => 'book',
            'password' => '123456',
            'charset' => 'utf8',
        ],
        'RabbitMQ' => [
            'class' => 'common\components\RabbitMQ',
            'host' => '127.0.0.1',
            'port' => 5672,
            'user' => 'lelai',
            'password' => '123456',
        ],
    ],
];
