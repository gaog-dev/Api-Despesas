<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'Despesas Pessoais',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        // ... outros componentes ...
        'assetManager' => [
            'bundles' => [
            'yii\bootstrap\BootstrapAsset' => [
                'css' => [],
            ],
            'yii\bootstrap\BootstrapPluginAsset' => [
                'js' => [],
            ],
        ],
    ],
        // ...
        'jwt' => [
            'class' => \app\components\JwtComponent::class,
            'key' => $_ENV['JWT_SECRET'] ?? 'sua_chave_super_secreta',
            'alg' => 'HS256',
        ],
        // ...
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'K_eTaLhAo8cA-dniBNMHugywKONokwGC',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                # ROTAS DO INDEX (Frontend web)
                '/' => 'site/index',

                # ROTAS DA API (JWT)
                'api/despesas/index' => 'despesa/index',
                'api/despesas/create' => 'despesa/create',
                'api/despesas/update' => 'despesa/update',
                'api/despesas/<id:\d+>' => 'despesa/view',
                'api/despesas/delete/<id:\d+>' => 'despesa/delete',

                # ROTAS DO DASHBOARD (Frontend web)
                'dashboard/despesas/index' => 'dashboard/despesas',
                'dashboard/despesas/create' => 'dashboard/despesas',
                'dashboard/despesas/update/<id:\d+>' => 'dashboard/update',
                'dashboard/despesas/delete/<id:\d+>' => 'dashboard/delete',
            ],
        ],
        // ...
        'container' => [
        'class' => 'yii\di\Container',
        'definitions' => [
            'app\services\DespesaService' => function ($container, $params, $config) {
                return new DespesaService();
            },
        ],
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
        'db' => $db,
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
