<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'cookieValidationKey' => 'lihb'
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'story'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'star'],
                '/' => 'site/login',
                'userLogin' => 'site/user-login',
                'starStory' => 'star/star',
                'myStory' => 'star/stories',
                'getStory' => 'star/story',
                'addPV' => 'star/pv',
                'delStar' => 'star/del',
                'addPianTou' => 'star/pt',
                'addPianWei' => 'star/pw',
                'childrenStory' => 'star/children',
                'getAccessToken' => 'star/access-token',
                'motherStory' => 'story/story',
                'storyList' => 'story/stories',
                'wxLogin' => 'story/login',
                'editStory' => 'site/edit-story',
                'addStory' => 'site/add-story',
                'delStory' => 'site/del-story'
            ],
        ],
        'wechat' => [
            'class' => 'callmez\wechat\sdk\Wechat',
            'appId' => 'wx4069e1635ae1be38',
            'appSecret' => '4578c042ea9361b6e16626f1aa3d7e52',
            'token' => 'listenstory'
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
