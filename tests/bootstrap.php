<?php
require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/vendor/yiisoft/yii2/Yii.php';

(new yii\web\Application([
    'id' => 'test',
    'basePath' => __DIR__ . '/_app',
    'components' => [
        'user' => [
            'identityClass' => 'eluhr\restApiUtilsTest\models\User'
        ]
    ],
    'controllerMap' => [
        'api1' => [
            'class' => 'eluhr\restApiUtilsTest\controllers\Api1Controller'
        ]
    ]
]));
