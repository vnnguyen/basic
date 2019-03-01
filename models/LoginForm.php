<?php

namespace common\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
	public $email;
	public $password;
	public $rememberMe = true;
	public $verifyCode;

	private $_user = false;

	public function rules() {
		return [
			[['email', 'password'], 'required'],
			[['email'], 'email'],
			[['password'], 'validatePassword'],
			[['rememberMe'], 'boolean'],
			[['verifyCode'], 'captcha', 'captchaAction' => 'login/captcha'],
		];
	}

	public function attributeLabels() {
		return [
			'email'=>Yii::t('login', 'Email'),
			'password'=>Yii::t('login', 'Password'),
			'rememberMe'=>Yii::t('login', 'Remember me'),
			'verifyCode'=>Yii::t('login', 'Verification code'),
		];
	}


	public function validatePassword() {
		if (!$this->hasErrors()) {
			$user = $this->getUser();
			if (!$user || !$user->validatePassword($this->password)) {
				$this->addError('password', Yii::t('login', 'Incorrect email or password.'));
			}
		}
	}

	public function login() {
		if ($this->validate()) {
			return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
		} else {
			return false;
		}
	}

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User2::findByUsername($this->email);
        }
        return $this->_user;
    }
}
