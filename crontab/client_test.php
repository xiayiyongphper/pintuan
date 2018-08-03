<?php

/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/9/22
 * Time: 20:50
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

require(__DIR__ . '/common/config/env.php');
require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/framework/functions.php');
require(__DIR__ . '/common/config/bootstrap.php');
require(__DIR__ . '/service/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/common/config/main.php'),
    require(__DIR__ . '/service/config/main.php'),
    require(__DIR__ . '/service/config/main-local.php')
);

try {
    $argv = $_SERVER['argv'];

    if (count($argv) < 2) {
        echo '用法：php client_test.php [route | task_id] [json_data]', PHP_EOL;
        echo 'task_id时可以在后面加!强制执行。', PHP_EOL;
        echo '如：php client_test.php taskTest.test', PHP_EOL;
        echo '如：php client_test.php 16', PHP_EOL;
        echo '如：php client_test.php taskTest.test \'{"key1":"val1"}\'', PHP_EOL;
        return;
    }

    $route = isset($argv[1]) ? $argv[1] : 'taskPintuan.testUpdateBecomeGroupTime';
    $data = isset($argv[2]) ? json_decode($argv[2], 1) : [];
    $server = (new \framework\core\TaskServer($config));    // 这句暂时不能去掉


    /* 配置 */
    $client = new \swoole_client(SWOOLE_SOCK_UNIX_STREAM, SWOOLE_SOCK_SYNC);

    $clientConfig = \Yii::$app->params['soa_client_config'];
    $client->set($clientConfig);

    /* 连接和发送 */
    $client->connect(ENV_SERVER_UNIX_SOCKET, ENV_SERVER_PORT, 180);
    $message = new \framework\message\Message();
    $message->setRoute($route);
    $message->setParams("");
    $data = \framework\components\Pack::pack($message);
    $client->send($data);

    /* 接收 */
    $recvMsg = $client->recv();
    /** @var \framework\core\TaskResponse $response */
    $response = \framework\components\Pack::unpack($recvMsg);
    print_r($response->getCode());
    echo PHP_EOL;
    print_r($response->getData());
    echo PHP_EOL;
    $client->close();
} catch (\Exception $e) {
    echo $e;
} catch (\Error $e) {
    echo $e;
}