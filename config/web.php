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


yii::setAlias('@web', 'localhost/basic/web');
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
    'vendorPath' => __DIR__ . '../vendor',
    'aliases'=>[
        '@www'=>'amica.xyz',
    ],



    'layout' => 'limitless',
    'components' => [
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => [
                        // "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js",
                        "https://code.jquery.com/jquery-3.3.1.min.js"
                        // 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'
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
            // 'suffix'=>'/',
            'rules'=>[
                ''=>'default/index',
                'filebrowser'=>'default/ckfinder',
                'filebrowser/config'=>'default/ckfinder-config',
                'select/lang/<lang>'=>'default/select-lang',
                'cms'=>'cms/index',

                'debug/<controller>/<action>' => 'debug/<controller>/<action>',
                'gii/<controller>/<action>' => 'gii/<controller>/<action>',
                'admin/<controller>/<action>' => 'gii/<controller>/<action>',

                // '<c>/<a>/<id:\d+>' => '<c>/<a>',
                'demo/<a>/<id:\d+>' => 'demo/<a>',
                'cptour/<a>/<id:\d+>' => 'cptour/<a>',

                'logout'=>'login/logout',
                'help/report-a-bug'=>'help/bug',
                'groups/type/<type>'=>'group/index',

                 // NEW ROUTES
                '<c_:account|contact|destination|location|member|program|sample-tour-day|sample-tour-program|sample-tour-segment|space|vendor>s'=>'<c_>/index',
                '<c_:account|contact|destination|location|member|program|sample-tour-day|sample-tour-program|sample-tour-segment|space|tour|vendor>s/<id:\d+>'=>'<c_>/r',
                '<c_:account|contact|destination|location|member|program|sample-tour-day|sample-tour-program|sample-tour-segment|space|vendor>s/r/<id:\d+>'=>'<c_>/r',
                '<c_:account|contact|destination|location|member|program|sample-tour-day|sample-tour-program|sample-tour-segment|space|tour|vendor>s/<id:\d+>/<a_>'=>'<c_>/<a_>',
                '<c_:account|contact|destination|location|member|program|sample-tour-day|sample-tour-program|sample-tour-segment|space|vendor>s/<a_:\w+>'=>'<c_>/<a_>',

                // Account CP
                'acp'=>'acp/acp/index',
                'acp/log'=>'acp/acp/log',
                'acp/settings'=>'acp/settings',
                'acp/settings/<c_>'=>'acp-settings-<c_>/index',
                'acp/settings/<c_>/<_a>'=>'acp-settings-<c_>/<a_>',
                'acp/<c_:user>s'=>'acp/<c_>/index',
                'acp/<c_:user>s/<a_>'=>'acp/<c_>/<a_>',
                'acp/<c_:user>s/<a_>/<a2_>'=>'acp/<c_>/<a_>-<a2_>',

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
                'me/vespa2013'=>'manager/vespa2013',
                'me/sales-results'=>'manager/sales-results',

                'auto/mg/<event>'=>'auto/mg-event',

                '<c:auto|client|cskh|default|help|huan|login|manager|me|qhkh|ref|search|test>'=>'<c>/index',
                '<c:auto|client|cskh|default|help|huan|login|manager|me|qhkh|ref|search|test>/<a>'=>'<c>/<a>',

                'cases'=>'kase/index',
                'cases/<a>'=>'kase/<a>',
                'cases/<a>/<id:\d+>'=>'kase/<a>',

                '<c:compan|countr|inquir|taxonom>ies'=>'<c>y/index',
                '<c:compan|countr|inquir|taxonom>ies/<a>/<id:\d+>'=>'<c>y/<a>',
                '<c:compan|countr|inquir|taxonom>ies/<a>'=>'<c>y/<a>',

                '<c:baccount|booking|cat|collection|day|driver|event|feedback|file|group|incident|complaint|invoice|kase|mail|member|message|node|note|option|payment|permission|person|product|referral|report|role|tag|task|tour|proposal|venue|setting|xrate|user|destination|customer|tourguide|campaign|package|promotion|space|supplier|term>s'=>'<c>/index',
                '<c:baccount|booking|cat|collection|day|driver|event|feedback|file|group|incident|complaint|invoice|kase|mail|member|message|node|note|option|payment|permission|person|product|referral|report|role|tag|task|tour|proposal|venue|setting|xrate|user|destination|customer|tourguide|campaign|package|promotion|space|supplier|term>s/<a>/<id:\d+>'=>'<c>/<a>',
                '<c:baccount|booking|cat|collection|day|driver|event|feedback|file|group|incident|complaint|invoice|kase|mail|member|message|node|note|option|payment|permission|person|product|referral|report|role|tag|task|tour|proposal|venue|setting|xrate|user|destination|customer|tourguide|campaign|package|promotion|space|supplier|term>s/<a>'=>'<c>/<a>',

                '<c:cp|cpo|cpt|cpg|cpx|ct|dv|dvc|dvd|dvo|dvt|dvg|dvx|nm|tm>'=>'<c>/index',
                '<c:cp|cpo|cpt|cpg|cpx|ct|dv|dvc|dvd|dvo|dvt|dvg|dvx|nm|tm>/<a>/<id:\d+>'=>'<c>/<a>',
                '<c:cp|cpo|cpt|cpg|cpx|ct|dv|dvc|dvd|dvo|dvt|dvg|dvx|nm|tm>/<a>'=>'<c>/<a>',

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

                // About
                'about'=>'about/index',
                'about/<name>'=>'about/<name>',
                'about/<name>/<id:\d+>'=>'about/<name>r',
                'about/<name>/<a:r|u|d>/<id:\d+>'=>'about/<name><a>',


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

                // ORGANIZATION
                'org'=>'member/index',
                'org/<c:member>s'=>'<c>/index',
                'org/<c:member>s/<a>/<id:\d+>'=>'<c>/<a>',
                'org/<c:member>s/<a>'=>'<c>/<a>',

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
