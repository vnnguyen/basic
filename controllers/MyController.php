<?

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use common\models\Message;
use Mailgun\Mailgun;

class MyController extends Controller
{
    public function handlerViewEvent($event)
    {
        //$event->output = str_replace(['http://my.amica'], ['https://my.amica'], $event->output);
    }

    public function __construct($id, $module, $config = [])
    {
        // To https
        //\yii\base\Event::on(\yii\web\View::className(), \yii\web\View::EVENT_AFTER_RENDER,  [$this, 'handlerViewEvent']);

        // Active Language
        if (Yii::$app->user->isGuest) {
            $activeLanguage = Yii::$app->session->get('active_language', 'en');
        } else {
            $activeLanguage = Yii::$app->user->identity->language;
        }
        if (!in_array($activeLanguage, Yii::$app->params['active_languages'])) {
            $activeLanguage = Yii::$app->params['active_languages'][0];
        }
        Yii::$app->language = $activeLanguage;

        // Mobile device

        $isMobile = Yii::$app->session->get('is_mobile', 'unknown');
        if (isset($_GET['mobile']) && $_GET['mobile'] == 'yes')
            $isMobile = 'yes';
        if (isset($_GET['mobile']) && $_GET['mobile'] == 'no')
            $isMobile = 'no';
        if ($isMobile == 'unknown') {
            $isMobile = 'no';
            $detect = new \Mobile_Detect;
            if ($detect->isMobile() && !$detect->isTablet()) {
                $isMobile = 'yes';
            }
        }
        Yii::$app->session->set('is_mobile', $isMobile);
        if (!defined('IS_MOBILE')) {
            define('IS_MOBILE', $isMobile == 'yes' ? true : false);
        }

        if (!defined('MY_ID')) {
            if (Yii::$app->user->isGuest) {
                define('MY_ID', 0);
            } else {
                define('MY_ID', Yii::$app->user->identity->id);
            }
        }

        if (!defined('USER_ID')) {
            define('USER_ID', MY_ID);
        }

        if (!defined('ACCOUNT_ID')) {
            define('ACCOUNT_ID', 1);
        }

        if (!defined('USER_IP')) {
            define('USER_IP', $_SERVER['HTTP_CF_CONNECTING_IP'] ?? Yii::$app->request->getUserIP());
        }

        if (!defined('USER_COUNTRY_CODE')) {
            define('USER_COUNTRY_CODE', $_SERVER['HTTP_CF_IPCOUNTRY'] ?? '');
        }

        Yii::$app->params['active_asset'] = 'app\assets\MainAsset';

        // Hits
        Yii::$app->db->createCommand('INSERT INTO hits (hit_dt, account_id, user_id, action, uri, ip, country_code) VALUES (:hit_dt, :account_id, :user_id, :action, :uri, :ip, :country_code)', [
            ':hit_dt'=>NOW,
            ':account_id'=>ACCOUNT_ID,
            ':user_id'=>USER_ID,
            ':action'=>Yii::$app->request->method, // browser method
            ':uri'=>Yii::$app->request->getUrl(),
            ':ip'=>USER_IP,
            ':country_code'=>strtolower(USER_COUNTRY_CODE),
        ])->execute();

        // Online user list
        if (USER_ID != 0) {
            $uq = USER_ID.' | '.USER_IP.' | '.Yii::$app->request->userAgent;
            $sql = 'INSERT INTO at_online_users (dt, user_id, id_ip_ua) VALUES (:dt, :id, :uq) ON DUPLICATE KEY UPDATE dt=:dt';
            Yii::$app->db->createCommand($sql, [
                ':dt'=>NOW,
                ':id'=>USER_ID,
                ':uq'=>$uq,
            ])->execute();
        }

        if (USER_ID == 1 && !isset($_GET['x'])) {
            //$this->layout = 'main_m454';
            //$this->layout = 'main_r210';
        }

        // Prevent accidental upload
        // Yii::$app->session->set('ckfinder_authorized', false);
        
        parent::__construct($id, $module, $config);

    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'width'=>100,
                'height'=>34,
                'foreColor'=>0x747474,
                'minLength'=>4,
                'maxLength'=>4,
                'offset'=>2,
                //'transparent'=>true,
            ],
        ];
    }

    public function behaviors() {
        return [
            'AccessControl' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow'=>true,
                        'roles'=>array('@'),
                    ], [
                        'allow'=>false,
                    ],
                ]
            ]
        ];
    }

    // Send email using Mailgun's HTTP API
    public function mgIt($subject, $body, $vars = [], $args = [])
    {
        $mg = new Mailgun(MAILGUN_API_KEY);
        $mb = $mg->MessageBuilder();

        $setFrom = false;
        $setTo = false;

        $files['attachment'] = [];

        foreach ($args as $arg) {
            if (is_array($arg)) {
                if ($arg[0] == 'from') {
                    $setFrom = true;
                    $mb->setFromAddress($arg[1], ['first'=>isset($arg[2]) ? $arg[2] : null, 'last'=>isset($arg[3]) ? $arg[3] : null]);
                } elseif ($arg[0] == 'to') {
                    $setTo = true;
                    $mb->addToRecipient($arg[1], ['first'=>isset($arg[2]) ? $arg[2] : null, 'last'=>isset($arg[3]) ? $arg[3] : null]);
                } elseif ($arg[0] == 'cc') {
                    $mb->addCcRecipient($arg[1], ['first'=>isset($arg[2]) ? $arg[2] : null, 'last'=>isset($arg[3]) ? $arg[3] : null]);
                } elseif ($arg[0] == 'bcc') {
                    $mb->addBccRecipient($arg[1], ['first'=>isset($arg[2]) ? $arg[2] : null, 'last'=>isset($arg[3]) ? $arg[3] : null]);
                } elseif ($arg[0] == 'reply-to') {
                    $mb->setReplyToAddress($arg[1], ['first'=>isset($arg[2]) ? $arg[2] : null, 'last'=>isset($arg[3]) ? $arg[3] : null]);
                } elseif ($arg[0] == 'attachment') {
                    $files['attachment'][] = '/var/www/my.amicatravel.com/'.$arg[1];
                }
            }
        }

        if (!$setFrom) {
            $mb->setFromAddress('noreply-ims@amicatravel.com', ['first'=>'Amica Travel', 'last'=>'IMS']);
        }
        if (!$setTo) {
            // $mb->addToRecipient('hn.huan@gmail.com', ['first'=>'Huân', 'last'=>'H.']);
        }
        //if (isset($args))

        $mb->setSubject($subject);
        // $mb->setTextBody($body, $vars);
        $mb->setHtmlBody($this->renderPartial($body, $vars));

        # Other Optional Parameters.
        //$mb->addCampaignId("My-Awesome-Campaign");
        //$mb->addCustomHeader("Customer-Id", "12345");
        //$mb->addAttachment('@@/var/www/my.amicatravel.com/120303-help.pdf');
        //$files['attachment'] = [];
        //$files['attachment'][] = '/var/www/my.amicatravel.com/120303-help.pdf';
        
        //$mb->addAttachment('@@/var/www/my.amicatravel.com/120303-help.pdf');
        //$mb->setDeliveryTime("tomorrow 8:00AM", "PST");
        //$mb->setClickTracking(true);

        # Finally, send the message.
        // $mg->post(MAILGUN_API_DOMAIN.'/messages', $mb->getMessage(), $files);
        $mg->post(MAILGUN_API_DOMAIN.'/messages', $mb->getMessage(), $files);
        return true;
    }
}
