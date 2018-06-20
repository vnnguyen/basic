<?
namespace app\controllers;

use Yii;
use common\models\LoginForgotForm;
use common\models\LoginResetForm;
use common\models\LoginForm;
use common\models\User;
use yii\web\HttpException;
use yii\web\Cookie;
use Mailgun\Mailgun;

class LoginController extends MyController
{
	public function behaviors() {
		return [
			'AccessControl' => [
				'class' => \yii\filters\AccessControl::className(),
				'rules' => [
					[
						'actions'=>['index', 'forgot', 'reset', 'captcha'],
						'allow'=>true,
						//'roles'=>['?'],
					], [
						'actions'=>['logout'],
						'allow'=>true,
						'roles'=>['@'],
					],
				]
			]
		];
	}

	public function actionIndex()
	{
		$this->layout = 'login';
		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			// Save session to at_logins for old app to retrieve
			$uid = Yii::$app->security->generateRandomString();
			Yii::$app->session->set('uid', $uid);
			setcookie ('imswtf', $uid, time() + 360000, '/', 'amicatravel.com', 1);

			Yii::$app->db
				->createCommand()
				->insert('at_logins', [
    				'created_at' => NOW,
    				'user_id' => Yii::$app->user->identity->id,
    				'uid'=>Yii::$app->session->get('uid'),
    				'ip_address'=>isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : Yii::$app->request->getUserIP(),
    				'ua_string'=>Yii::$app->request->getUserAgent(),
				])
				->execute();
			// Send email
			return $this->redirect('@web/tours');
		} else {
			return $this->render('login', [
				'model' => $model,
			]);
		}
	}

	public function actionLogout()
	{
		// Delete session from at_logins
		$uid = Yii::$app->session->get('uid');
		Yii::$app->db
			->createCommand()
			->delete('at_logins', ['uid'=>$uid])
			->execute();
		Yii::$app->session->remove('uid');
		Yii::$app->getResponse()->getCookies()->remove('imswtf');
		Yii::$app->user->logout(false);

		$this->layout = 'login';
		return $this->render('logout');
	}

	public function actionForgot()
	{
		$model = new LoginForgotForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->sendEmail()) {
				Yii::$app->session->setFlash('success', 'Please check your email for instructions.');
			} else {
				Yii::$app->getSession()->setFlash('danger', 'There was an error sending email.');
			}
			return $this->redirect('@web/login');
		}

		$this->layout = 'login';
		return $this->render('login_forgot', array(
			'model' => $model,
		));
	}

	public function actionReset($token)
	{
		$theForm = new LoginResetForm($token);
		if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
			if ($theForm->resetPassword()) {
				Yii::$app->session->setFlash('success', 'Your password has been successfully changed.');
				$theForm->sendEmail();
			} else {
				Yii::$app->getSession()->setFlash('danger', 'There was an error resetting your password. Please try again later.');
			}
			return $this->redirect('@web/login');
		}

		$this->layout = 'login';
		return $this->render('login_reset', [
			'theForm'=>$theForm,
		]);
	}
}
