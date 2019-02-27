<?
// Get yii app params
function yap($key, $value = null, $get = null)
{
    // k get k
    // k v set k=v
    // k v g get k default=v
    if (!isset($value)) {
        return isset(Yii::$app->params[$key]) ? Yii::$app->params[$key] : null;
    } else {
        if (!isset($get) || $get === false) {
            Yii::$app->params[$key] = $value;
        } else {
            return isset(Yii::$app->params[$key]) ? Yii::$app->params[$key] : $value;
        }
    }
}

if (!defined('DFM')) define('DFM', 'j/n/Y');
if (!defined('ZERO_DATE')) define('ZERO_DATE', '0000-00-00');
if (!defined('ZERO_TIME')) define('ZERO_TIME', '00:00:00');
if (!defined('ZERO_DT')) define('ZERO_DT', '0000-00-00 00:00:00');
if (!defined('NOW')) define('NOW', date('Y-m-d H:i:s'));
if (!defined('MAILGUN_API_KEY')) define('MAILGUN_API_KEY', 'key-41qs3pbnff7i2k42jmsh9v6ch059jf76');
if (!defined('MAILGUN_API_DOMAIN')) define('MAILGUN_API_DOMAIN', 'amicatravel.com');
define('DIR', '/');
$_REQUEST_URI = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); define('URI', DIR == '/' ? $_REQUEST_URI : substr($_REQUEST_URI, strlen(trim(DIR, '/').'/'))); $_URI_SEGMENTS = explode('/', URI); define('SEGS', empty($_URI_SEGMENTS) ? 0 : count($_URI_SEGMENTS)); for ($i = 1; $i <= 9; $i ++) define('SEG'.$i, isset($_URI_SEGMENTS[$i - 1]) ? $_URI_SEGMENTS[$i - 1] : '');


// yii::setAlias('@web', 'localhost/basic/web');
// Yii::setAlias('common', 'D:/wamp/www/basic/common-ims/');
// Yii::setAlias('@web', 'http://amica.dev');

$params = array_merge(
    require(__DIR__ . '/../common-ims/config/params.php'),
    require(__DIR__ . '/params.php')
);

$config = [
    'id' => 'amica-ims',
    'name' => 'Amica Travel IMS',
    'language'=>'en',
    'basePath' => dirname(__DIR__),
    'vendorPath' => __DIR__ . '/../vendor',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@www'=>'amica.xyz',
    ],



    'layout' => 'limitless',
    'bootstrap' => [
        'queue', // The component registers its own console commands
    ],
    'components' => [
        'db' => $params['components.db'],
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
             // 'ttr' => 5 * 60, // Max time for job execution
            // 'attempts' => 3, // Max number of attempts

            'db' => 'db',
            //     'strictJobType' => false,
            //     'serializer' => \yii\queue\serializers\JsonSerializer::class,
            'tableName' => '{{queue}}', // Table name
            'channel' => 'default', // Queue channel key
            'mutex' => \yii\mutex\MysqlMutex::class, // Mutex used to sync queries
        ],
        'mailqueue' => [
            'class' => \yiicod\mailqueue\MailQueue::class,
            'modelMap' => [
                'mailQueue' => [
                    'class' => \app\models\MyMailQueueModel::class,
                ],
            ],
        ],
        // 'mailer' => [
        //     'class' => 'yii\swiftmailer\Mailer',
        //     'viewPath'         => '@app/mail',
        //     'useFileTransport' => false,
        //     'transport' => [
        //         'class' => 'Swift_SmtpTransport',
        //         'host' => 'smtp.gmail.com',
        //         'username' => 'nguyen.nv@amica-travel.com',
        //         'password' => 'app_gmail_9999',
        //         'port' => '587',
        //         'encryption' => 'tls',
        //     ],
        // ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath'         => '@app/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'nguyen.nv@amica-travel.com',
                'password' => 'app_gmail_9999',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
        'ntyModel' => [
            'class' => \app\models\UserNotification::class,
        ],
        'userNty' => [
            'class' => 'app\notifications\UserNotification',
        ],

        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => [
                        // "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js",
                        "https://code.jquery.com/jquery-3.3.1.min.js",
                        // 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js',
                        'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js',
                        // 'https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js',
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
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'cache' => $params['components.cache'],

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
        'request'=>[
            'cookieValidationKey' => '*&%78v x5a6754',
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
        'urlManager'=>[
            'enablePrettyUrl'=>true,
            'showScriptName'=>false,
            // 'suffix'=>'/',
            'rules'=>[
                ''=>'default/index',

                '<c_:assets/ckfinder/finder>'=>'ckfinder/finder',
                '<c_:assets/ckfinder/config>'=>'ckfinder/config',

                // HUAN, TEST DV CP
                '<c_:testdv>'=>'<c_>/index',

                // '<c>/<a>/<id:\d+>' => '<c>/<a>',
                'demo/<a>/<id:\d+>' => 'demo/<a>',
                'cptour/<a>/<id:\d+>' => 'cptour/<a>',

                'persons/ajax'=>'contacts/ajax',
                '<c_:files|tours|vendors>/r/<id:\d+>'=>'<c_>/r',

                '<c_:accounts|attachments|c___s|companies|contacts|countries|destinations|feedbacks|filebrowser|files|inquiries|mails|mentions|payments|places|posts|products|programs|projects|referrals|reports|spaces|tasks|tours|u___s|vendors>'=>'<c_>/index',
                '<c_:accounts|attachments|cases|companies|contacts|countries|destinations|feedbacks|filebrowser|files|inquiries|mails|mentions|payments|places|posts|products|programs|projects|referrals|reports|spaces|tasks|tours|users|vendors>/<id:\d+>'=>'<c_>/r',
                '<c_:accounts|attachments|cases|companies|contacts|countries|destinations|feedbacks|filebrowser|files|inquiries|mails|mentions|payments|places|posts|products|programs|projects|referrals|reports|spaces|tasks|tours|u___s|vendors>/<id:\d+>/<a_>'=>'<c_>/<a_>',
                '<c_:accounts|attachments|c___s|companies|contacts|countries|destinations|feedbacks|filebrowser|files|inquiries|mails|mentions|payments|places|posts|p______s|programs|projects|referrals|reports|spaces|tasks|tours|u___s|vendors>/<a_>'=>'<c_>/<a_>',

                // MONEY
                'm/<c_:accounts>'=>'m/<c_>/index',
                'm/<c_:accounts>/<id:\d+>'=>'m/<c_>/r',
                'm/<c_:accounts>/<id:\d+>/<a_>'=>'m/<c_>/<a_>',
                'm/<c_:accounts>/<a_:\w+>'=>'m/<c_>/<a_>',

                // NEW ROUTES
                '<c_:location|member|sample-tour-day|sample-tour-program|sample-tour-segment|space|venue>s'=>'<c_>/index',
                '<c_:location|member|sample-tour-day|sample-tour-program|sample-tour-segment|space|tour|venue>s/<id:\d+>'=>'<c_>/r',
                '<c_:location|member|sample-tour-day|sample-tour-program|sample-tour-segment|space|venue>s/r/<id:\d+>'=>'<c_>/r',
                '<c_:location|member|sample-tour-day|sample-tour-program|sample-tour-segment|space|tour|venue>s/<id:\d+>/<a_>'=>'<c_>/<a_>',
                '<c_:location|member|sample-tour-day|sample-tour-program|sample-tour-segment|space|venue>s/<a_:\w+>'=>'<c_>/<a_>',

                // Q = QHKH
                'q'=>'q/q/index',
                'q/<c_:account|item|itemgroup|price|product|service|transaction|vendor|warehouse>s'=>'q/<c_>/index',
                'q/<c_:account|item|itemgroup|price|product|service|transaction|vendor|warehouse>s/<id:\d+>'=>'q/<c_>/r',
                'q/<c_:account|item|itemgroup|price|product|service|transaction|vendor|warehouse>s/r/<id:\d+>'=>'q/<c_>/r',
                'q/<c_:account|item|itemgroup|price|product|service|transaction|vendor|warehouse>s/<id:\d+>/<a_>'=>'q/<c_>/<a_>',
                'q/<c_:account|item|itemgroup|price|product|service|transaction|vendor|warehouse>s/<a_:\w+>'=>'q/<c_>/<a_>',

                'qhkh/club-amba'=>'qhkh/club-amba',

                // X = PROJECT X : SERVICES & PRICES
                'x'=>'x/x/index',
                'x/<c_:price|product|service|vendor|venue>s'=>'x/<c_>/index',
                'x/<c_:price|product|service|vendor|venue>s/<id:\d+>'=>'x/<c_>/r',
                'x/<c_:price|product|service|vendor|venue>s/r/<id:\d+>'=>'x/<c_>/r',
                'x/<c_:price|product|service|vendor|venue>s/<id:\d+>/<a_>'=>'x/<c_>/<a_>',
                'x/<c_:price|product|service|vendor|venue>s/<a_:\w+>'=>'x/<c_>/<a_>',

                // T = TEMP./TEST
                't'=>'t/t/index',
                't/<c_:account|item|group|warehouse>s'=>'t/<c_>/index',
                't/<c_:account|item|group|warehouse>s/<id:\d+>'=>'t/<c_>/r',
                't/<c_:account|item|group|warehouse>s/r/<id:\d+>'=>'t/<c_>/r',
                't/<c_:account|item|group|warehouse>s/<id:\d+>/<a_>'=>'t/<c_>/<a_>',
                't/<c_:account|item|group|warehouse>s/<a_:\w+>'=>'t/<c_>/<a_>',

                // EB = ECOBUS
                'eb/<a_:\w+>'=>'eb/<a_>',

                // DIR
                'inv'=>'inv/inv/index',
                'inv/<c_:item|groups|warehouse>s'=>'inv/<c_>/index',
                'inv/<c_:item|groups|warehouse>s/<id:\d+>'=>'inv/<c_>/r',
                'inv/<c_:item|groups|warehouse>s/r/<id:\d+>'=>'inv/<c_>/r',
                'inv/<c_:item|groups|warehouse>s/<id:\d+>/<a_>'=>'inv/<c_>/<a_>',
                'inv/<c_:item|groups|warehouse>s/<a_:\w+>'=>'inv/<c_>/<a_>',

                // 'filebrowser'=>'default/ckfinder',
                // 'filebrowser/config'=>'default/ckfinder-config',
                'select/lang/<lang>'=>'default/select-lang',
                'cms'=>'cms/index',

                'debug/<controller>/<action>' => 'debug/<controller>/<action>',
                'gii/<controller>/<action>' => 'gii/<controller>/<action>',
                'admin/<controller>/<action>' => 'gii/<controller>/<action>',

                'logout'=>'login/logout',
                'help/report-a-bug'=>'help/bug',

                'groups/type/<type>'=>'group/index',

                // Account CP
                'acp'=>'acp/acp/index',
                'acp/log'=>'acp/acp/log',
                'acp/settings'=>'acp/settings',
                'acp/settings/<c_>'=>'acp-settings-<c_>/index',
                'acp/settings/<c_>/<_a>'=>'acp-settings-<c_>/<a_>',
                'acp/<c_:permission|role|user>s'=>'acp/<c_>/index',
                'acp/<c_:permission|role|user>s/<id:\d+>'=>'acp/<c_>/r',
                'acp/<c_:permission|role|user>s/<id:\d+>/<a_>'=>'acp/<c_>/<a_>',
                'acp/<c_:permission|role|user>s/<a_>'=>'acp/<c_>/<a_>',

                // Master CP
                'mcp'=>'mcp/mcp/index',
                'mcp/log'=>'mcp/mcp/log',
                'mcp/phpinfo'=>'mcp/mcp/phpinfo',
                'mcp/<c_>s'=>'mcp/<c_>/index',
                'mcp/<c_>s/<a_>'=>'mcp/<c_>/<a_>',
                'mcp/<c_>s/<a_>/<id>'=>'mcp/<c_>/<a_>',
                // 'mcp/<c_>'=>'mcp/<c_>/index',
                // 'mcp/<c_>/<a_>'=>'mcp/<c_>/<a_>',
                // 'mcp/<c_>/<a_>/<a2_>'=>'mcp/<c_>/<a_>-<a2_>',

                // B2B
                'sample-tour-days'=>'day/sample',

                'special'=>'special/special/index',
                'special/<_c>'=>'special/<_c>/index',
                'special/<_c>/<_a>'=>'special/<_c>/<_a>',

                'b2b'=>'b2b/b2b/index',
                'b2b/series'=>'b2b/b2b/series',

                'b2b/<_c:case>s'=>'b2b/kase/index',
                'b2b/<_c:case>s/<_a>/<id:\d+>'=>'b2b/kase/<_a>',
                'b2b/<_c:case>s/<_a>'=>'b2b/kase/<_a>',

                'b2b/<_c:client|day|lead|program|report|tool|tour>s'=>'b2b/<_c>/index',
                'b2b/<_c:client|day|lead|program|report|tool|tour>s/<_a>/<id:\d+>'=>'b2b/<_c>/<_a>',
                'b2b/<_c:client|day|lead|program|report|tool|tour>s/<_a>'=>'b2b/<_c>/<_a>',

                // Mme Xuan
                'cx'=>'cx/index',
                'cx/<c>s'=>'cx/<c>-index',
                'cx/<c>s/<a>'=>'cx/<c>-<a>',
                'cx/<c>s/<a>/<id:\d+>'=>'cx/<c>-<a>',

                'manager/reports'=>'report/index',
                'manager/reports/<name>'=>'report/<name>',

                // Gallery
                'gallery'=>'gallery/gallery/index',
                'gallery/manage'=>'gallery/gallery/manage',
                'gallery/collections'=>'gallery/gallery/index',
                'gallery/<c:collection>s/<a>/<id:\d+>'=>'gallery/<c>/<a>',
                'gallery/<c:collection>s/<a>'=>'gallery/<c>/<a>',

                'me/'=>'me/profile',
                'me/my-settings/password'=>'me/my-settings-password',
                'me/my-settings/preferences|me/my-settings'=>'me/my-settings-preferences',
                'me/vespa2013'=>'manager/vespa2013',
                'me/sales-results'=>'manager/sales-results',

                'auto/mg/<event>'=>'auto/mg-event',

                '<c:auto|client|cskh|default|help|huan|login|manager|me|qhkh|ref|search|test>'=>'<c>/index',
                '<c:auto|client|cskh|default|help|huan|login|manager|me|qhkh|ref|search|test>/<a>'=>'<c>/<a>',

                'cases'=>'kase/index',
                'cases/<a>'=>'kase/<a>',
                'cases/<a>/<id:\d+>'=>'kase/<a>',

                '<c:booking|cat|collection|complaint|day|event|group|incident|invoice|kase|message|node|note|option|permission|product|role|tag|proposal|venue|setting|xrate|user|destination|customer|campaign|package|promotion|setting|supplier|term>s'=>'<c>/index',
                '<c:booking|cat|collection|complaint|day|event|group|incident|invoice|kase|message|node|note|option|permission|product|role|tag|proposal|venue|setting|xrate|user|destination|customer|campaign|package|promotion|setting|supplier|term>s/<a>/<id:\d+>'=>'<c>/<a>',
                '<c:booking|cat|collection|complaint|day|event|group|incident|invoice|kase|message|node|note|option|permission|product|role|tag|proposal|venue|setting|xrate|user|destination|customer|campaign|package|promotion|setting|supplier|term>s/<a>'=>'<c>/<a>',

                '<c:cp|cpo|cpt|cpg|cpx|ct|diem|dv|dvc|dvd|dvo|dvt|dvg|dvx|nm|s|tm|tuyen>'=>'<c>/index',
                '<c:cp|cpo|cpt|cpg|cpx|ct|diem|dv|dvc|dvd|dvo|dvt|dvg|dvx|nm|s|tm|tuyen>/<a>/<id:\d+>'=>'<c>/<a>',
                '<c:cp|cpo|cpt|cpg|cpx|ct|diem|dv|dvc|dvd|dvo|dvt|dvg|dvx|nm|s|tm|tuyen>/<a>'=>'<c>/<a>',

                // KB
                'kb'=>'kb/index',

                'kb/posts'=>'kbpost/index',
                'kb/posts/c'=>'kbpost/c',
                'kb/posts/<a:r|u|d>/<id:\d+>'=>'kbpost/<a>',

                'kb/lists'=>'kblist/index',
                'kb/lists/c'=>'kblist/c',
                'kb/lists/<a:r|u|d>/<id:\d+>'=>'kblist/<a>',

                'kb/lists/<name>s'=>'kbl<name>/index',
                'kb/lists/<name>s/c'=>'kbl<name>/c',
                'kb/lists/<name>s/<a:r|u|d>/<id:\d+>'=>'kbl<name>/<a>',

                'kb/books'=>'kbbook/index',
                'kb/books/c'=>'kbbook/c',
                'kb/books/<a:r|u|d>/<id:\d+>'=>'kbbook/<a>',

                // Ketoan
                'ketoan'=>'ketoan/index',
                'ketoan/cpt'=>'cpt/ketoan',
                'ketoan/dvt/tour/<id:\d+>'=>'dvt/ketoan-tour',

                // Luot thanh toan, muc thanh toan
                'ketoan/ltt'=>'ltt/index',
                'ketoan/ltt/<a>'=>'ltt/<a>',
                'ketoan/ltt/<a>/<id:\d+>'=>'ltt/<a>',

                'ketoan/mtt'=>'mtt/index',
                'ketoan/mtt/<a>'=>'mtt/<a>',
                'ketoan/mtt/<a>/<id:\d+>'=>'mtt/<a>',

                // Ketoan
                'cpt/tt'=>'cpt/tt',
                'cpt/tt/<a>'=>'cpt/tt-<a>',
                'cpt/tt/<a>/<id:\d+>'=>'cpt/tt-<a>',

                // Alain Dung, Vuong Xuan
                'at'=>'at/index',
                'at/<c>'=>'at/at-<c>',
                'at/<c>/<a>/<id:\d+>'=>'at/at-<c>-<a>',
                'at/<c>/<id:\d+>'=>'at/at-<c>',

                // Blog
                'blog/manage'=>'blog/blog-manage/index',
                'blog/manage/x'=>'xxx',

                'blog/my-posts'=>'blog/blog-manage/my-posts',

                'blog'=>'blog/blogpost/index',
                'blog/posts'=>'blog/blogpost/index',
                'blog/posts/c'=>'blog/blogpost/c',
                'blog/posts/<a:r|u|d>/<id:\d+>'=>'blog/blogpost/<a>',

                // Events
                'eventful'=>'eventful/event/index',
                'eventful/events'=>'eventful/event/index',
                'eventful/events/<a>'=>'eventful/event/<a>',
                'eventful/events/<a>/<id:\d+>'=>'eventful/event/<a>',

                'eventful/manage'=>'eventful/event/manage',

                // Media
                'media'=>'media/index',

                'media/galleries'=>'gallery/index',
                'media/galleries/<a:c>'=>'gallery/<a>',
                'media/galleries/<a:r|u|d>/<id:\d+>'=>'gallery/<a>',

                'media/downloads'=>'download/index',
                'media/downloads/<a:r|u|d>/<id:\d+>'=>'download/<a>',

                'media/surveys'=>'survey/index',
                'media/surveys/<a:r|u|d>/<id:\d+>'=>'survey/<a>',

                // Forum
                'forum'=>'forum-topic/index',
                'forum/forums'=>'forum/index',
                'forum/forums/c'=>'forum/index',
                'forum/forums/<id:\d+>/<a>'=>'forum/<a>',

                'forum/topics'=>'forum-topic/index',
                'forum/topics/<id:\d+>'=>'forum-topic/r',
                'forum/topics/<id:\d+>/<a>'=>'forum-topic/<a>',
                'forum/topics/<a>'=>'forum-topic/<a>',

                'forum/posts'=>'forum-post/index',
                'forum/posts/<id:\d+>'=>'forum-post/<r>',
                'forum/posts/<id:\d+>/<a>'=>'forum-post/<a>',
                'forum/posts/<a>'=>'forum-post/<a>',

                'forum/replies'=>'forumreply/index',
                'forum/replies/<id:\d+>'=>'forumreply/<r>',
                'forum/replies/<id:\d+>/<a>'=>'forumreply/<a>',
                'forum/replies/<a>'=>'forumreply/<a>',

                'forums'=>'forum/index',

                // Tuyen diem
                'td'=>'td/default/index',
                'td/<c:tuyen|diem>'=>'about/<_c>',
                'td/<c:tuyen|diem>/<id:\d+>'=>'about/<_c>/index',
                'td/<c:tuyen|diem>/<id:\d+>/<a:r|u|d>'=>'about/<_c><_a>',

                // App-wide setting
                'app/change/site/<site>'=>'app/changesite',
                'app/change/lang/<lang>'=>'app/changelanguage',
                'app/change/color/<color>'=>'app/changecolor',

                // Ref tables
                'tools'=>'tool/index',
                'tools/<a>'=>'tool/<a>',

                'calendar'=>'calendar/index',
                'calendar/get'=>'calendar/index',
                'calendars'=>'calendar/index',

                // SYS
                'sys'=>'sys/sys/index',

                'sys/ims'=>'sys/ims/ims/index',
                'sys/ims/<c:account|client|file|project|user|valuation>s'=>'sys/ims/<c>/index',
                'sys/ims/<c:account|client|file|project|user|valuation>s/<a>'=>'sys/ims/<c>/<a>',
                'sys/ims/<c:account|client|file|project|user|valuation>s/<a>/<id:\d+>'=>'sys/ims/<c>/<a>',

                'sys/ims/<c:propert>ies'=>'sys/ims/<c>y/index',
                'sys/ims/<c:propert>ies/<a>'=>'sys/ims/<c>y/<a>',
                'sys/ims/<c:propert>ies/<a>/<id:\d+>'=>'sys/ims/<c>y/<a>',

                'sys/stats'=>'sys/stats',
                'sys/stats/phpinfo'=>'sys/stats-phpinfo',
            ],
        ],
        'user' => $params['components.user'],
    ],
    'modules' => [
        'notifications' => [
            'class' => 'webzop\notifications\Module',
            'channels' => [
                'screen' => [
                    'class' => 'webzop\notifications\channels\ScreenChannel',
                ],
                'email' => [
                    'class' => 'webzop\notifications\channels\EmailChannel',
                    'message' => [
                        'from' => 'example@email.com'//nguyen.nv@amica-travel.com
                    ],
                ],
                // 'voice' => [
                //     'class' => 'app\channels\VoiceChannel',
                // ],
            ],
        ],
        // 'admin' => [
        //     'class' => 'app\modules\admin\Module',
        // ],
    ],

    'params' => $params,
];
// $config['bootstrap'][] = 'debug';
// $config['modules']['debug'] = [
//     'class' => 'yii\debug\Module',
//     'allowedIPs' => ['*']
// ];
// $config['bootstrap'][] = 'gii';
// $config['modules']['gii'] = [
//     'class' => 'yii\gii\Module',
//     'allowedIPs' => ['*']
// ];

return $config;
