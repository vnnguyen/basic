<?php

namespace app\controllers;

use Yii;
use common\models\LoginForm;
use common\models\LoginForgotForm;
use common\models\LoginResetForm;
use common\models\User;
use yii\web\HttpException;

class LoginController extends McpController
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

	public function actionIndex() {
		$theForm = new LoginForm();

		if ($theForm->load(Yii::$app->request->post()) && $theForm->login()) {
			return $this->redirect('@web/');
		}

		$this->layout = 'login';
		return $this->render('login', [
			'theForm' => $theForm,
		]);
	}

	public function actionLogout()
	{
		Yii::$app->user->logout(false);

		$this->layout = 'login';
		return $this->render('logout');
	}

	public function actionForgot()
	{
		$theForm = new LoginForgotForm();
		if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
			if ($theForm->sendEmail()) {
				Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
				return $this->redirect('@web/login');
			} else {
				Yii::$app->getSession()->setFlash('error', 'There was an error sending email.');
			}
		}

		$this->layout = 'login';
		return $this->render('login_forgot', array(
			'theForm' => $theForm,
		));
	}

	public function actionReset($token)
	{
		try {
			$theForm = new LoginResetForm($token);
		} catch (InvalidParamException $e) {
			throw new BadRequestHttpException($e->getMessage());
		}

		if ($theForm->load(Yii::$app->request->post()) && $theForm->resetPassword()) {
			Yii::$app->session->setFlash('success', 'Your new password has been saved.');
			return $this->redirect('@web/login');
		}

		$this->layout = 'login';
		return $this->render('login_reset', [
			'theForm' => $theForm,
		]);
	}
}
