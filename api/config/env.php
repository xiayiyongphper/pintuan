<?php
define('ENV_SYS_NAME', 'api');
define('CONSUL_PROJECT_NAME', 'pintuan');
//define('CONSUL_ENV_NAME', 'wangyang');
define('CONSUL_ENV_NAME', 'xyy');
define('CONSUL_IP', '127.0.0.1');
define('ENV_SERVER_IP', '172.16.10.182');
define('ENV_WORKER_NUM', 4);
define('ENV_TASK_WORKER_NUM', 4);
define('ENV_SERVER_HTTP_PORT', 5555);

// 接口请求时间上报需要的常量
define('ENV_REDIS_HOST', '172.16.30.114');//redis host
define('ENV_REDIS_PORT', 6379);//redis port
define('ENV_ELK_REDIS_HOST', '172.16.30.114');//redis host
define('ENV_ELK_REDIS_PORT', 6379);//redis port
define('ENV_REDIS_CLASS', 'framework\components\redis\Cache');
define('ENV_SERVER_LOCAL_IP', '192.168.1.100');//内网IP
define('ENV_SERVER_LOCAL_PORT', 9090);//服务内网端口

