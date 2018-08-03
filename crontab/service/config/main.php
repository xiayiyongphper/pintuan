<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php'),
    require(__DIR__ . '/server-config.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'service\controllers',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'request' => ['class' => 'framework\core\TaskRequest'],
        'response' => ['class' => 'framework\core\TaskResponse'],
        'errorHandler' => [
            'class' => 'framework\ErrorHandler',
        ],
    ],
    'taskResources' => [
        'taskCommon' => 'service\tasks\common',
        'taskPintuan' => 'service\tasks\pintuan',
        'taskTest' => 'service\tasks\test',
        'taskWholesaler' => 'service\tasks\wholesaler',
        'taskProduct' => 'service\tasks\product',
        'taskOrder' => 'service\tasks\order',
    ],
    'params' => $params,
];
