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
        echo '用法：php swoole_client.php [route | task_id] [json_data]', PHP_EOL;
        echo 'task_id时可以在后面加!强制执行。', PHP_EOL;
        echo '如：php swoole_client.php taskTest.test', PHP_EOL;
        echo '如：php swoole_client.php 16', PHP_EOL;
        echo '如：php swoole_client.php taskTest.test \'{"key1":"val1"}\'', PHP_EOL;
        return;
    }

    $route = isset($argv[1]) ? $argv[1] : 'taskTest.test';
    $data = isset($argv[2]) ? json_decode($argv[2], 1) : [];
    $server = (new \framework\core\TaskServer($config));    // 这句暂时不能去掉

    $job = null;
    $curDateTime = date('Y-m-d H:i:s');
    if (is_numeric($route[0])) {
        $taskId = intval($route);
        echo 'find by ID! ID=', $taskId, PHP_EOL;
        $job = \common\models\Crontab::findOne(['entity_id' => $taskId]);
    } else {
        echo 'find by route! route=', $route, PHP_EOL;
        if ($jobs = \common\models\Crontab::getAvailaleJobsByRoute($route)) {
            $job = $jobs[0];
        }
    }

    if ($isForce = (substr($route, -1, 1) == '!')) {
        echo "force=true!!!", PHP_EOL;
    }

    if (empty($job)) {
        echo '不存在的任务', PHP_EOL;
        return;
    }

    if (!$isForce && !($job->from_time <= $curDateTime && $curDateTime <= $job->to_time)) {
        echo '任务已过期!', PHP_EOL;
        return;
    }
    if (!$isForce && !$job->status == \common\models\Crontab::STATUS_ENABLED) {
        echo '任务已被禁用!', PHP_EOL;
        return;
    }

    /* 配置 */
    $client = new \swoole_client(SWOOLE_SOCK_UNIX_STREAM, SWOOLE_SOCK_SYNC);

    $clientConfig = \Yii::$app->params['soa_client_config'];
    $client->set($clientConfig);

    /* 连接和发送 */
    $client->connect(ENV_SERVER_UNIX_SOCKET, ENV_SERVER_PORT, 180);
    $job->scheduledTimestamp = time();
    $message = new \framework\message\Message();
    $message->setRoute($job->route);
    $message->setParams($job->params);
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