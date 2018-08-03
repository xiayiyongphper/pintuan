<?php
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-9-29
 * Time: 下午5:18
 * Email: henryzxj1989@gmail.com
 */
/**
 * common defaults
 * 通用配置,默认值
 */


if (!defined('ENV_SYS_NAME')) {
    define('ENV_SYS_NAME', 'not found');
    throw new Exception('ENV_SYS_NAME not define', 500);
}

if (!defined('ENV_SERVER_IP')) {
    define('ENV_SERVER_IP', '127.0.0.1');//公网IP
}

if (!defined('ENV_SERVER_UNIX_SOCKET')) {
    define('ENV_SERVER_UNIX_SOCKET', '/tmp/crontab.sock');//系统内部使用Unix Socket通信
}

if (!defined('ENV_SERVER_HTTP_PORT')) {
    define('ENV_SERVER_HTTP_PORT', 5555);//公网端口,包括ipv4,ipv6
}




/** ELASTIC SEARCH  */
if (!defined('ENV_ES_CLUSTER_HOSTS')) {
    define('ENV_ES_CLUSTER_HOSTS', '172.16.10.239:9200;172.16.10.240:9200');//ES集群的地址
}

if (!defined('ENV_ES_REPORT_STATUS')) {
    define('ENV_ES_REPORT_STATUS', true);//上报状态
}

/**
 * redis defaults
 */
if (!defined('ENV_REDIS_HOST')) {
    define('ENV_REDIS_HOST', '127.0.0.1');//redis host
}

if (!defined('ENV_REDIS_PORT')) {
    define('ENV_REDIS_PORT', 6379);//redis port
}

if (!defined('ENV_ELK_REDIS_HOST')) {
    define('ENV_ELK_REDIS_HOST', '127.0.0.1');//redis host
}

if (!defined('ENV_ELK_REDIS_PORT')) {
    define('ENV_ELK_REDIS_PORT', 6379);//redis port
}

//if (!defined('ENV_ROUTE_REDIS_HOST')) {
//    define('ENV_ROUTE_REDIS_HOST', '127.0.0.1');//redis host
//}

//if (!defined('ENV_ROUTE_REDIS_PORT')) {
//    define('ENV_ROUTE_REDIS_PORT', 6379);//redis port
//}

if (!defined('ENV_REDIS_CLASS')) {
    define('ENV_REDIS_CLASS', 'framework\redis\Cache');
}

if (!defined('ENV_ELASTICSEARCH_CLASS')) {
    define('ENV_ELASTICSEARCH_CLASS', 'framework\elasticsearch\ElasticSearch');
}

if (!defined('ENV_REDIS_SESSION_CLASS')) {
    define('ENV_REDIS_SESSION_CLASS', 'yii\redis\Session');
}

/***
 * mysql defaults
 */
if (!defined('ENV_MYSQL_DB_HOST')) {
    define('ENV_MYSQL_DB_HOST', '127.0.0.1');//mysql db host
}

if (!defined('ENV_MYSQL_DB_USER')) {
    define('ENV_MYSQL_DB_USER', 'root');//mysql db user
}

if (!defined('ENV_MYSQL_REPLICATION_USER')) {
    define('ENV_MYSQL_REPLICATION_USER', 'replication');//mysql replication user
}

if (!defined('ENV_MYSQL_DB_PWD')) {
    define('ENV_MYSQL_DB_PWD', '123456');//mysql db password
}

if (!defined('ENV_MYSQL_REPLICATION_PWD')) {
    define('ENV_MYSQL_REPLICATION_PWD', '123456');//mysql replication password
}

if (!defined('REPLICATION_SLAVE_ID')) {
    define('REPLICATION_SLAVE_ID', '6667');//mysql replication slave_id
}

if (!defined('ENV_MYSQL_DB_PORT')) {
    define('ENV_MYSQL_DB_PORT', 3306);//mysql db port
}

if (!defined('ENV_DB_CONNECTION_CLASS')) {
    define('ENV_DB_CONNECTION_CLASS', 'framework\db\Connection');
}

if (!defined('ENV_MYSQL_SLAVE_DB_HOST')) {
    define('ENV_MYSQL_SLAVE_DB_HOST', '127.0.0.1');//mysql db host
}

if (!defined('ENV_MYSQL_SLAVE_DB_USER')) {
    define('ENV_MYSQL_SLAVE_DB_USER', 'root');//mysql db user
}

if (!defined('ENV_MYSQL_SLAVE_DB_PWD')) {
    define('ENV_MYSQL_SLAVE_DB_PWD', '123456');//mysql db password
}

if (!defined('ENV_MYSQL_SLAVE_DB_USER_CAN_WRITE')) {
    define('ENV_MYSQL_SLAVE_DB_USER_CAN_WRITE', 'root');//mysql db user
}

if (!defined('ENV_MYSQL_SLAVE_DB_PWD_CAN_WRITE')) {
    define('ENV_MYSQL_SLAVE_DB_PWD_CAN_WRITE', '123456');//mysql db password
}

if (!defined('ENV_MYSQL_SLAVE_DB_PORT')) {
    define('ENV_MYSQL_SLAVE_DB_PORT', 3306);//mysql db port
}

if (!defined('ENV_MYSQL_DB_ENABLE_SLAVE')) {
    define('ENV_MYSQL_DB_ENABLE_SLAVE', false);//enable mysql slave
}


/**
 * swoole defaults
 */

//if (!defined('ENV_WORKER_NUM')) {
//    define('ENV_WORKER_NUM', 15);//swoole worker num
//}

//if (!defined('ENV_TASK_WORKER_NUM')) {
//    define('ENV_TASK_WORKER_NUM', 8);//swoole task work num
//}

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

/**
 * server hardware parameter defaults
 */
if (!defined('ENV_SERVER_MEMORY')) {
    define('ENV_SERVER_MEMORY', 16);//unit:Gb
}

if (!defined('ENV_SERVER_CPU')) {
    define('ENV_SERVER_CPU', 1);
}

if (!defined('ENV_SERVER_CPU_CORES')) {
    define('ENV_SERVER_CPU_CORES', 16);
}

/**
 * dynamic round robin status
 */
if (!defined('ENV_SERVER_DYNAMIC_ROUND_ROBIN_STATUS')) {
    define('ENV_SERVER_DYNAMIC_ROUND_ROBIN_STATUS', false);
}

/**
 * rabbitmq配置信息
 */
if (!defined('ENV_RABBITMQ_HOST')) {
    define('ENV_RABBITMQ_HOST', '127.0.0.1');
}

if (!defined('ENV_RABBITMQ_PORT')) {
    define('ENV_RABBITMQ_PORT', 5672);
}

if (!defined('ENV_RABBITMQ_USER')) {
    define('ENV_RABBITMQ_USER', 'lelai');
}

if (!defined('ENV_RABBITMQ_PASSWORD')) {
    define('ENV_RABBITMQ_PASSWORD', '123456');
}

if (!defined('ENV_RABBITMQ_VHOST')) {
    define('ENV_RABBITMQ_VHOST', '/');
}

if (!defined('ENV_RABBITMQ_QUEUE_NAME_SUFFIX')) {
    define('ENV_RABBITMQ_QUEUE_NAME_SUFFIX', '');
}

// 订单所属平台 1-APP 2-小程序
define('PLATFORM_APP', 1);
define('PLATFORM_MINI_PROGRAM', 2);

if(!defined('ENV_SERVER')){
    define("ENV_SERVER","prod");
}


