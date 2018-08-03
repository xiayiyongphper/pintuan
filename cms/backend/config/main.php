<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'cms-backend',
    'name' => '拼团管理后台',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu'
        ],
        'redactor' => [
            'class' => 'yii\redactor\RedactorModule',
//            'uploadDir' => '上传目录',
//            'uploadUrl' => '图片可访问地址',
            'imageAllowExtensions'=>['jpg','png','gif']
        ],
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'login',//允许访问的节点，可自行添加
        ]
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'assetManager' => [
            'assetMap' => [
                'jquery.js' => '@web/js/jquery.js', // jquery v3.2.1 和 jQuery UI 1.11.4 版本冲突 @https://stackoverflow.com/questions/37914869/jquery-ui-error-f-getclientrects-is-not-a-function
                'jquery.min.js' => '@web/js/jquery.js',
                'vue.js' => '@web/js/vue.js',
            ],
        ],
        'user' => [
            'identityClass' => 'backend\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager'
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'i18n'=>[
            'translations'=>[
                '*'=>[
                    'class'=>'yii\i18n\PhpMessageSource',
                    'fileMap'=>[
                        'common'=>'common.php',
                    ],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager'=>[
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules'=>[
                '<controller:[\w+(-)?]+>/<action:[\w+(-)?]+>'=>'<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];
