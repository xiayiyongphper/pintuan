<?php

$params = require __DIR__ . '/params.php';
//$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'api',
    'vendorPath' => dirname(__DIR__) . '/vendor',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mainDb' => __env_get_mysql_db_config('lelai_slim_merchant'),
        'redisCache' => __env_get_redis_config(),
//        'routeRedisCache' => __env_get_route_redis_config(),
        'elasticSearch' => __env_get_elasticsearch_config(),
        'es_logger' => [
            'class' => '\framework\components\log\RedisLogger',
            'redis' => __env_get_elk_redis_config(),
            'logKey' => 'logstash-lelai-pintuan'
        ],
        'mq' => __env_get_mq_config(),
        'consumer_mq' => __env_get_mq_config(),
        'session' => __env_get_session_config(),
        'errorHandler' => [
            'class' => 'framework\ErrorHandler',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
        'urlManager' => [
            'enablePrettyUrl' => true, //转换目录访问
            'showScriptName' => false, //去除index
            'rules' => [
                '<controller:[\w+(-)?]+>/<action:[\w+(-)?]+>' => '<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
