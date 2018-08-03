<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'id' => 'app-user',
    'basePath' => dirname(__DIR__),
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'commonDb' => __env_get_mysql_db_config('pintuan_common'),
        'wholesalerDb' => __env_get_mysql_db_config('pintuan_wholesaler'),
        'redisCache' => __env_get_redis_config(),
        'urlManager' => [
            'enablePrettyUrl' => true, //转换目录访问
            'showScriptName' => false, //去除index
            'rules' => [
                '<controller:[\w+(-)?]+>/<action:[\w+(-)?]+>' => '<controller>/<action>',
            ],
        ],
    ],
];
