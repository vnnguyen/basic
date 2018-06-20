<?
namespace common\models;

use common\models\User;

use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class LoginForgotForm extends Model
{
	public $email;
	public $verifyCode;

	public function rules()
	{
		return [
			[['email'], 'trim'],
			[['email'], 'required'],
			[['email'], 'email'],
			[['email'], 'exist',
				'targetClass' => '\common\models\User',
				'filter' => ['status'=>'on', 'is_member'=>'yes'],
				'message' => 'There is no user with such email.'
			],
			[['verifyCode'], 'captcha', 'captchaAction' => 'login/captcha'],
		];
	}

	public function sendEmail()
	{
		$user = User::find()
			->where([
				'status' => 'on',
				'email' => $this->email,
			])
			->one();

		if (!$user) {
			return false;
		}

		// Xoa cac token hien co cua nguoi nay
		Yii::$app->db->createCommand()->delete('at_login_resets', ['user_id'=>$user['id']])->execute();

		$token = Yii::$app->security->generateRandomString();
		Yii::$app->db->createCommand()->insert('at_login_resets', [
			'created_dt'=>NOW,
			'expiry_dt'=>date('Y-m-d H:i', strtotime('+1 hour', strtotime(NOW))),
			'user_id'=>$user->id,
			'token'=>$token,
			])->execute();

		\Yii::$app->mail->htmlLayout = false;
		return \Yii::$app->mail->compose(['html'=>'@app/mail/login_forgot'], ['token' => $token])
			->setFrom(['noreply-ims@amicatravel.com' => \Yii::$app->name])
			->setTo([$this->email => $user->name])
			->setBcc(['hn.huan@gmail.com' => 'Huân H.'])
			->setSubject('Password reset for ' . \Yii::$app->name)
			->send();
	}
}