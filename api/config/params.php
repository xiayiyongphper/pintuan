<?php

return [
    'http_server_config' => [
        'worker_num' => ENV_WORKER_NUM,   //工作进程数量
        'task_worker_num' => ENV_TASK_WORKER_NUM,
        'daemonize' => 1, //是否作为守护进程
        'log_file' => dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . ENV_LOG_FILE,
        'open_length_check' => ENV_OPEN_LENGTH_CHECK, //打开包长检测
        'package_max_length' => ENV_SERVER_PACKAGE_MAX_LENGTH, //最大的请求包长度,8M
        'package_length_type' => ENV_PACKAGE_LENGTH_TYPE, //长度的类型，参见PHP的pack函数
        'package_length_offset' => ENV_PACKAGE_LENGTH_OFFSET,   //第N个字节是包长度的值
        'package_body_offset' => ENV_PACKAGE_BODY_OFFSET,   //从第几个字节计算长度
        'heartbeat_check_interval' => ENV_HEARTBEAT_CHECK_INTERVAL,
        'heartbeat_idle_time' => ENV_HEARTBEAT_IDLE_TIME,
        'task_ipc_mode' => ENV_TASK_IPC_MODE,
        'message_queue_key' => ftok(__FILE__, 2),
        'discard_timeout_request' => true,
    ],
    'client_config' => __env_get_client_config(),
    'custom_processes' => [
        'ReportProcess' => [
            'class' => 'framework\process\ReportProcess',
        ],
        'MessageProcess' => [
            'class' => 'service\models\Process',
        ],
        'ESProcess' => [
            'class' => 'framework\process\ESProcess',
        ],
    ],
    'ip_port' => [
        'http_port' => ENV_SERVER_HTTP_PORT,
        'host' => ENV_SERVER_IP,
    ],
    'es_cluster' => [
        'hosts' => explode(',', ENV_ES_CLUSTER_HOSTS),
        'size' => ENV_ES_CLUSTER_BULK_SIZE,
    ],
    'rabbitmq' => [
        'host' => ENV_RABBITMQ_HOST,
        'port' => ENV_RABBITMQ_PORT,
        'user' => ENV_RABBITMQ_USER,
        'pwd' => ENV_RABBITMQ_PASSWORD,
        'vhost' => ENV_RABBITMQ_VHOST,
    ]
];
