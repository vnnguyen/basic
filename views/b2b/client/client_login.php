<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

Yii::$app->params['page_title'] = 'Account login for: '.$theAccount['name'];

$this->params['breadcrumb'] = [
	['B2B', '@web/b2b'],
	['Clients', '@web/b2b/clients'],
	['View', '@web/b2b/clients/r/'.$theAccount['id']],
	['Edit', '@web/b2b/clients/login/'.$theAccount['id']],
];

$form = ActiveForm::begin();
?>
<div class="col-md-8">
	<div class="alert alert-info">
		<strong>NOTE:</strong> The login page for B2B clients is <?= Html::a('https://client.secretindochina.com', 'https://client.secretindochina.com', ['class'=>'alert-link', 'target'=>'_blank']) ?>. Client can change their password after login. This login is not related to SecretIndochina.com website login.
	</div>
	<?= $form->field($theProfile, 'name') ?>
	<div class="row">
		<div class="col-sm-6"><?= $form->field($theProfile, 'login') ?></div>
		<div class="col-sm-6"><?= $form->field($theProfile, 'newpassword', ['inputOptions'=>['autocomplete'=>'off', 'class'=>'form-control']]) ?></div>
	</div>
	<div class="text-right"><?= Html::submitButton(Yii::t('mn', 'Save change'), ['class' => 'btn btn-primary']) ?></div>
</div>
<?
ActiveForm::end();