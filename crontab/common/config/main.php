<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'id' => 'app-crontab',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mainDb' => __env_get_mysql_db_config('pintuan_common'),
        'orderDb' => __env_get_mysql_db_config('pintuan_order'),
        'productDb' => __env_get_mysql_db_config('pintuan_product'),
        'userDb' => __env_get_mysql_db_config('pintuan_user'),
        'wholesalerDb' => __env_get_mysql_db_config('pintuan_wholesaler'),
        'redisCache' => __env_get_redis_config(),
        'elasticSearch' => __env_get_elasticsearch_config(),
        'urlManager' => [
            'enablePrettyUrl' => true, //转换目录访问
            'showScriptName' => false, //去除index
            'rules' => [
                '<controller:[\w+(-)?]+>/<action:[\w+(-)?]+>' => '<controller>/<action>',
            ],
        ],
        'rabbitMq' => __env_get_mq_config(),
    ],
];
