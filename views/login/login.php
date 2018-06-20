<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('login', 'Log in to continue');
foreach(Yii::$app->session->getAllFlashes() as $key=>$message) {
	if (Yii::$app->session->hasFlash($key)) { ?>
				<div class="alert alert-<?= $key ?>"><?= $message ?></div><?
	}
}
$form = ActiveForm::begin();
echo $form->field($model, 'email')->input('email');
echo $form->field($model, 'password')->passwordInput();
echo $form->field($model, 'verifyCode')->widget(Captcha::className(), [
	'captchaAction'=>'login/captcha',
	'options' => ['class' => 'form-control'],
	'template' => '<div class="row"><div class="col-xs-6">{input}</div><div class="col-xs-6">{image}</div></div>',
]);
echo $form->field($model, 'rememberMe')->checkbox();
echo '<p>', Html::submitButton(Yii::t('login', 'Log in'), ['class' => 'btn btn-default btn-block']), '</p>';
ActiveForm::end(); ?>
<p class="text-center"><?= Html::a('Forgot your password?', '@web/login/forgot') ?></p>
