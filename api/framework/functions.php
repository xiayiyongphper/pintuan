<?php
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-6-2
 * Time: 下午1:01
 */
//initGlobalConfig();
require_once 'env_defaults.php';
/**
 * @param $dbName
 * @param int $node
 * @param int $port
 * @return array
 */
function __env_get_mysql_db_config($dbName, $node = 0, $port = ENV_MYSQL_DB_PORT)
{
    switch ($node) {
        case 0:
        default:
            $config = [
                'class' => ENV_DB_CONNECTION_CLASS,
                'dsn' => sprintf('mysql:host=%s;dbname=%s;port=%s', ENV_MYSQL_DB_HOST, $dbName, $port),
                'username' => ENV_MYSQL_DB_USER,
                'password' => ENV_MYSQL_DB_PWD,
                'charset' => 'utf8',
                'enableSlaves' => ENV_MYSQL_DB_ENABLE_SLAVE,
                // 配置从服务器
                'slaveConfig' => [
                    'username' => ENV_MYSQL_SLAVE_DB_USER,
                    'password' => ENV_MYSQL_SLAVE_DB_PWD,
                    'attributes' => [
                        // use a smaller connection timeout
                        PDO::ATTR_TIMEOUT => 10,
                    ],
                    'charset' => 'utf8',
                ],

                // 配置从服务器组
                'slaves' => [
                    ['dsn' => sprintf('mysql:host=%s;dbname=%s;port=%s', ENV_MYSQL_SLAVE_DB_HOST, $dbName, $port),],
                ],
            ];
    }
    return $config;
}

/**
 * @param $dbName
 * @param int $node
 * @param int $port
 * @return array
 */
function __env_get_mysql_readonly_db_config($dbName, $node = 0, $port = ENV_MYSQL_DB_PORT)
{
    switch ($node) {
        case 0:
        default:
            $config = [
                'class' => ENV_DB_CONNECTION_CLASS,
                'dsn' => sprintf('mysql:host=%s;dbname=%s;port=%s', ENV_MYSQL_SLAVE_DB_HOST, $dbName, $port),
                'username' => ENV_MYSQL_SLAVE_DB_USER,
                'password' => ENV_MYSQL_SLAVE_DB_PWD,
                'charset' => 'utf8',
            ];
    }
    return $config;
}

/**
 * @param $dbName
 * @param int $node
 * @param int $port
 * @return array
 */
function __env_get_mysql_slave_db_config($dbName, $node = 0, $port = ENV_MYSQL_DB_PORT)
{
    switch ($node) {
        case 0:
        default:
            $config = [
                'class' => ENV_DB_CONNECTION_CLASS,
                'dsn' => sprintf('mysql:host=%s;dbname=%s;port=%s', ENV_MYSQL_SLAVE_DB_HOST, $dbName, $port),
                'username' => ENV_MYSQL_SLAVE_DB_USER_CAN_WRITE,
                'password' => ENV_MYSQL_SLAVE_DB_PWD_CAN_WRITE,
                'charset' => 'utf8',
            ];
    }
    return $config;
}

/**
 * @param int $port
 * @param int $db
 * @return array
 */
function __env_get_redis_config($port = ENV_REDIS_PORT, $db = 0)
{
    return [
        'class' => ENV_REDIS_CLASS,
        'options' => [
            'host' => ENV_REDIS_HOST,
            'port' => $port,
            'database' => $db,
        ],
    ];
}

/**
 * @param int $port
 * @param int $db
 * @return array
 */
function __env_get_elk_redis_config($port = ENV_ELK_REDIS_PORT, $db = 0)
{
    return [
        'class' => ENV_REDIS_CLASS,
        'options' => [
            'host' => ENV_ELK_REDIS_HOST,
            'port' => $port,
            'database' => $db,
        ],
    ];
}


/**
 * @param int $port
 * @param int $db
 * @return array
 */
//function __env_get_route_redis_config($port = ENV_ROUTE_REDIS_PORT, $db = 0)
//{
//    return [
//        'class' => ENV_REDIS_CLASS,
//        'options' => [
//            'host' => ENV_ROUTE_REDIS_HOST,
//            'port' => $port,
//            'database' => $db,
//        ],
//    ];
//}


function __env_get_elasticsearch_config()
{
    return [
        'class' => ENV_ELASTICSEARCH_CLASS,
        'host' => explode(',', ENV_ES_CLUSTER_HOSTS)
    ];
}

/**
 * @param int $port
 * @param int $db
 * @return array
 */
function __env_get_session_config($port = ENV_REDIS_PORT, $db = 0)
{
    return [
        'class' => ENV_REDIS_SESSION_CLASS,
        'redis' => [
            'hostname' => ENV_REDIS_HOST,
            'port' => $port,
            'database' => $db,
        ]
    ];
}

function __env_get_server_config($file)
{
    return [
        'worker_num' => ENV_WORKER_NUM,   //工作进程数量
        'task_worker_num' => ENV_TASK_WORKER_NUM,
        'daemonize' => ENV_DAEMONIZE, //是否作为守护进程
        'log_file' => dirname(dirname(dirname($file))) . DIRECTORY_SEPARATOR . ENV_LOG_FILE,
        'open_length_check' => ENV_OPEN_LENGTH_CHECK, //打开包长检测
        'package_max_length' => ENV_SERVER_PACKAGE_MAX_LENGTH, //最大的请求包长度,8M
        'package_length_type' => ENV_PACKAGE_LENGTH_TYPE, //长度的类型，参见PHP的pack函数
        'package_length_offset' => ENV_PACKAGE_LENGTH_OFFSET,   //第N个字节是包长度的值
        'package_body_offset' => ENV_PACKAGE_BODY_OFFSET,   //从第几个字节计算长度
        'heartbeat_check_interval' => ENV_HEARTBEAT_CHECK_INTERVAL,
        'heartbeat_idle_time' => ENV_HEARTBEAT_IDLE_TIME,
        'task_ipc_mode' => ENV_TASK_IPC_MODE,
        'dispatch_mode' => ENV_DISPATCH_MODE,
        'message_queue_key' => ftok($file, 1),
        'discard_timeout_request' => true,
    ];
}

function __env_get_http_server_config($file)
{
    return [
        'worker_num' => ENV_WORKER_NUM,   //工作进程数量
        'task_worker_num' => ENV_TASK_WORKER_NUM,
        'daemonize' => 1, //是否作为守护进程
        'log_file' => dirname(dirname(dirname($file))) . DIRECTORY_SEPARATOR . ENV_LOG_FILE,
        'open_length_check' => ENV_OPEN_LENGTH_CHECK, //打开包长检测
        'package_max_length' => ENV_SERVER_PACKAGE_MAX_LENGTH, //最大的请求包长度,8M
        'package_length_type' => ENV_PACKAGE_LENGTH_TYPE, //长度的类型，参见PHP的pack函数
        'package_length_offset' => ENV_PACKAGE_LENGTH_OFFSET,   //第N个字节是包长度的值
        'package_body_offset' => ENV_PACKAGE_BODY_OFFSET,   //从第几个字节计算长度
        'heartbeat_check_interval' => ENV_HEARTBEAT_CHECK_INTERVAL,
        'heartbeat_idle_time' => ENV_HEARTBEAT_IDLE_TIME,
        'task_ipc_mode' => ENV_TASK_IPC_MODE,
        'message_queue_key' => ftok($file, 2),
        'discard_timeout_request' => true,
    ];
}

function __env_get_client_config()
{
    return [
        'open_length_check' => ENV_OPEN_LENGTH_CHECK,
        'package_length_type' => ENV_PACKAGE_LENGTH_TYPE,
        'package_length_offset' => ENV_PACKAGE_LENGTH_OFFSET,       //第N个字节是包长度的值
        'package_body_offset' => ENV_PACKAGE_BODY_OFFSET,       //第几个字节开始计算长度
        'package_max_length' => ENV_CLIENT_PACKAGE_MAX_LENGTH,  //协议最大长度
        'socket_buffer_size' => ENV_SOCKET_BUFFER_SIZE, //2M缓存区
    ];
}

/**
 * @return array
 */
function __env_get_mq_config()
{
    return [
        'class' => 'framework\mq\RabbitMQ',
    ];
}


function initGlobalConfig()
{
    $data = parse_ini_file(ENV_GLOBAL_ENV_FILE, true);
    $commonConfig = $data['common'];
    $modelConfig = $data[ENV_SYS_NAME];
    $config = array_merge($commonConfig, $modelConfig);
    foreach ($config as $key => $value) {
        if (!defined($key)) {
            define($key, $value);
        }
    }
}