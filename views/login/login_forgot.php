<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Forgot your password?';

$form = ActiveForm::begin();

echo '<p>Please enter your login email. A link to reset your password will be emailed to you.</p>';
echo $form->field($model, 'email')->input('email');
echo $form->field($model, 'verifyCode')->widget(Captcha::className(), [
	'captchaAction'=>'login/captcha',
	'options' => ['class' => 'form-control'],
	'template' => '<div class="row"><div class="col-xs-6">{input}</div><div class="col-xs-6">{image}</div></div>',
]);
echo '<br><p>', Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-default btn-block']), '</p>';
ActiveForm::end(); ?>
<p class="text-center"><?= Html::a('Return to login', '@web/login') ?></p>