<?
yii::setAlias('@web', 'localhost/basic/web');
// Yii::setAlias('@web', 'http://amica.dev');

$params = array_merge(
    require(__DIR__ . '/../common-ims/config/params.php'),
    require(__DIR__ . '/params.php')
);

$config = [
    'id' => 'basic-console',
    'bootstrap' => ['log'],
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\commands',
    'vendorPath' => __DIR__ . '../vendor',


    'layout' => 'limitless',
    'bootstrap' => [
        'queue',
    ],
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,

        ],
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db',
            'tableName' => '{{queue}}',
            'channel' => 'default',
            'mutex' => \yii\mutex\MysqlMutex::class,
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => [
                        "https://code.jquery.com/jquery-3.3.1.min.js"
                    ],
                ],
            ],
        ],
        'easyimage' => [
            'class' => 'yiicod\easyimage\EasyImage',
            'webrootAlias' => '@basic/web',
            'cachePath' => '/uploads/easyimage/',
            'imageOptions' => [
                'quality' => 100
            ],
        ],
        // 'authManager' => [
        //     'class' => 'yii\rbac\DbManager',
        // ],
        'cache' => $params['components.cache'],
        'db' => $params['components.db'],
        'errorHandler' => [
            'errorAction' => 'default/error',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
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
        'mail' => $params['components.mail'],
        'request'=>[
            'enableCsrfValidation'=>false,
            'cookieValidationKey' => '*&%78v x5a6754',
        ],
        'urlManager'=>[
            'enablePrettyUrl'=>true,
            'showScriptName'=>false,
            'rules'=>[
                ''=>'default/index',
                'filebrowser'=>'default/ckfinder',
                'filebrowser/config'=>'default/ckfinder-config',
                'select/lang/<lang>'=>'default/select-lang',
                'cms'=>'cms/index',

                '<c>/<a>/<id:\d+>' => '<c>/<a>',
                'demo/<a>/<id:\d+>' => 'demo/<a>',
                'cptour/<a>/<id:\d+>' => 'cptour/<a>',
            ],
        ],
        'user' => $params['components.user'],
    ],

    'params' => $params,
];

return $config;
