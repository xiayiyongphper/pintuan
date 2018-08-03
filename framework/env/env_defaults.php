<?php
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-9-29
 * Time: 下午5:18
 * Email: henryzxj1989@gmail.com
 */
/**
 * must define
 */

if (!defined('ENV_SYS_NAME')) {
    define('ENV_SYS_NAME', 'not found');
    throw new Exception('ENV_SYS_NAME not define', 500);
}

if (!defined('ENV_MYSQL_DB_HOST')) {
    define('ENV_MYSQL_DB_HOST', 'not found');
    throw new Exception('ENV_MYSQL_DB_HOST not define', 500);
}

if (!defined('ENV_MYSQL_DB_USER')) {
    define('ENV_MYSQL_DB_USER', 'not found');
    throw new Exception('ENV_MYSQL_DB_USER not define', 500);
}

if (!defined('ENV_MYSQL_DB_PWD')) {
    define('ENV_MYSQL_DB_PWD', 'not found');
    throw new Exception('ENV_MYSQL_DB_PWD not define', 500);
}


/**
 * defaults
 */


if (!defined('ENV_RABBITMQ_VHOST')) {
    define('ENV_RABBITMQ_VHOST', '/');
}

if (!defined('ENV_RABBITMQ_PORT')) {
    define('ENV_RABBITMQ_PORT', '5672');
}

if (!defined('ENV_RABBITMQ_USER')) {
    define('ENV_RABBITMQ_USER', 'lelai');
}

if (!defined('ENV_RABBITMQ_PASSWORD')) {
    define('ENV_RABBITMQ_PASSWORD', 123456);
}

if (!defined('ENV_RABBITMQ_HOST')) {
    define('ENV_RABBITMQ_HOST', '127.0.0.1');
}

if (!defined('ENV_REDIS_HOST')) {
    define('ENV_REDIS_HOST', '127.0.0.1');
}

if (!defined('ENV_TASK_WORKER_NUM')) {
    define('ENV_TASK_WORKER_NUM', 16);
}

if (!defined('ENV_WORKER_NUM')) {
    define('ENV_WORKER_NUM', 16);
}

if (!defined('ENV_MYSQL_DB_PORT')) {
    define('ENV_MYSQL_DB_PORT', 3306);
}

if (!defined('ENV_REDIS_PORT')) {
    define('ENV_REDIS_PORT', 6379);
}

if (!defined('ENV_HEARTBEAT_CHECK_INTERVAL')) {
    define('ENV_HEARTBEAT_CHECK_INTERVAL', 60);//heartbeat check interval
}

if (!defined('ENV_HEARTBEAT_IDLE_TIME')) {
    define('ENV_HEARTBEAT_IDLE_TIME', 600);//heartbeat idle time
}

if (!defined('ENV_DAEMONIZE')) {
    define('ENV_DAEMONIZE', true);//daemonize
}

if (!defined('ENV_LOG_FILE')) {
    define('ENV_LOG_FILE', 'swoole.log');//log file name
}

if (!defined('ENV_TASK_IPC_MODE')) {
    define('ENV_TASK_IPC_MODE', 3);//task mode
}

if (!defined('ENV_DISPATCH_MODE')) {
    define('ENV_DISPATCH_MODE', 3);//reactor与worker通信机制
}

if (!defined('ENV_ELASTICSEARCH_CLASS')) {
    define('ENV_ELASTICSEARCH_CLASS', 'framework\elasticsearch\ElasticSearch');
}

/** ELASTIC SEARCH  */
if (!defined('ENV_ES_CLUSTER_HOSTS')) {
    define('ENV_ES_CLUSTER_HOSTS', '172.16.30.101:9200');//ES集群的地址
}

if (!defined('ENV_SERVER_PACKAGE_MAX_LENGTH')) {
    define('ENV_SERVER_PACKAGE_MAX_LENGTH', 8192000);//server package max length
}

if (!defined('ENV_CLIENT_PACKAGE_MAX_LENGTH')) {
    define('ENV_CLIENT_PACKAGE_MAX_LENGTH', 2000000);//client package max length
}


if (!defined('ENV_SOCKET_BUFFER_SIZE')) {
    define('ENV_SOCKET_BUFFER_SIZE', 2097152);//socket buffer size
}

if (!defined('ENV_PACKAGE_LENGTH_TYPE')) {
    define('ENV_PACKAGE_LENGTH_TYPE', 'N');//package length type
}


if (!defined('ENV_PACKAGE_LENGTH_OFFSET')) {
    define('ENV_PACKAGE_LENGTH_OFFSET', 0);//package length offset
}


if (!defined('ENV_PACKAGE_BODY_OFFSET')) {
    define('ENV_PACKAGE_BODY_OFFSET', 4);//package body offset
}

if (!defined('ENV_OPEN_LENGTH_CHECK')) {
    define('ENV_OPEN_LENGTH_CHECK', true);//open length check
}


if (!defined('ENV_ES_CLUSTER_BULK_SIZE')) {
    define('ENV_ES_CLUSTER_BULK_SIZE', 5242880);//5Mb
}


if (!defined('ENV_ES_CLUSTER_BULK_SIZE_MAX')) {
    define('ENV_ES_CLUSTER_BULK_SIZE_MAX', 10485760);//10Mb
}

if (!defined('ENV_SERVER_MEMORY')) {
    define('ENV_SERVER_MEMORY', 16);//unit:Gb
}

if (!defined('ENV_SERVER_CPU')) {
    define('ENV_SERVER_CPU', 1);
}

if (!defined('ENV_SERVER_CPU_CORES')) {
    define('ENV_SERVER_CPU_CORES', 16);
}


