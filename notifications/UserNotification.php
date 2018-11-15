<?php
namespace app\notifications;

use Yii;
use webzop\notifications\Notification;

class UserNotification extends Notification
{
    const KEY_NEW_ACCOUNT = 'new_account';

    const KEY_RESET_PASSWORD = 'reset_password';

    /**
     * @var \yii\web\User the user object
     */
    public $datas;

    /**
     * @inheritdoc
     */
    public function getTitle(){
        switch($this->key){
            case self::KEY_NEW_ACCOUNT:
                return Yii::t('app', 'New account {user} created', ['user' => '#'.$this->datas['user']->id]);
            case self::KEY_RESET_PASSWORD:
                return Yii::t('app', 'Instructions to reset the password');
        }
    }

    /**
     * @inheritdoc
     */
    public function getRoute(){
        return ['/users/edit', 'id' => $this->datas['user']->id];
    }

    /**
     * Get the notification's delivery channels.
     * @return boolean
     */
    public function shouldSend($channel)
    {
        // var_dump($channel);die();
        if($channel->id == 'screen'){
            if(!in_array($this->key, [self::KEY_NEW_ACCOUNT])){
                return false;
            }
        }
        return true;
    }
    /**
     * Override send to email channel
     *
     * @param $channel the email channel
     * @return void
     */
    public function toEmail($channel){
        switch($this->key){
            case self::KEY_NEW_ACCOUNT:
                $subject = 'Welcome to MySite';
                $template = 'test_email';
                break;
            case self::KEY_RESET_PASSWORD:
                $subject = 'Password reset for MySite';
                $template = 'resetPassword';
                break;
        }

        $message = $channel->mailer->compose($template, [
            'user' => $this->datas['user'],
            'notification' => $this,
        ]);
        Yii::configure($message, $channel->message);

        $message->setTo($this->datas['user']->email);
        $message->setSubject($subject);
        $message->send($channel->mailer);
    }
    public function send(){
        Yii::$app->getModule('notifications')->send($this, $this->datas['channels']);
    }
}
?>