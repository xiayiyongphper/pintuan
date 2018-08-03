<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 12:24
 */
return [
    'soa_server_config' => __env_get_server_config(__FILE__),
    'custom_workers' => [
        'RedisJobWorker' => [
            'class' => 'service\workers\RedisJobWorker',
        ],
    ],
    'custom_processes' => [
        'GenerateProcess' => [
            'class' => 'service\processes\GenerateProcess',
        ],
        'MQProcess' => [
            'class' => 'service\processes\MQProcess',
        ],
        'TestProcess' => [
            'class' => 'service\processes\TestProcess',
        ],
    ],
    'soa_client_config' => __env_get_client_config(),
    'client_config' => __env_get_proxy_client_config(),
];