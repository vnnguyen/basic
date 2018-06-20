<?
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Change password';
$this->params['icon'] = 'key';
$this->params['breadcrumb'] = [
	['Me', 'me'],
	['My account', 'me/account'],
];

?>
<div class="col-md-6">
	<div class="panel panel-white">
		<div class="panel-heading">
			<h6 class="panel-title">Change your password</h6>
		</div>
		<div class="panel-body">
			<? $form = ActiveForm::begin(['layout'=>'horizontal']) ?>
			<div class="row">
				<div class="col-lg-12">
					<?=$form->field($model, 'login', ['inputOptions'=>['readonly'=>'readonly']])->hint('You cannot change this'); ?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<?=$form->field($model, 'rawPassword', ['inputOptions'=>['class'=>'form-control', 'autocomplete'=>'off']])->passwordInput(); ?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<?=$form->field($model, 'rawPasswordAgain', ['inputOptions'=>['class'=>'form-control', 'autocomplete'=>'off']])->passwordInput(); ?>
				</div>
			</div>
			<div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?></div>
			<? ActiveForm::end(); ?>
		</div>
	</div>
</div>


