<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'id' => 'app-product',
    'basePath' => dirname(__DIR__),
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'commonDb' => __env_get_mysql_db_config('pintuan_common'),
        'productDb' => __env_get_mysql_db_config('pintuan_product'),
        'redisCache' => __env_get_redis_config(),
        'rabbitMq' => __env_get_mq_config(),
        'elasticSearch' => __env_get_elasticsearch_config(),
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true, //转换目录访问
            'showScriptName' => false, //去除index
            'rules' => [
                '<controller:[\w+(-)?]+>/<action:[\w+(-)?]+>' => '<controller>/<action>',
            ],
        ],
    ],
];
