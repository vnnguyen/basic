<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->title = 'Reservation form';

$this->params['breadcrumb'] = [
	['Tools', '@web/tools'],
	['Reservation form', '@web/tools/fiche'],
];
$dow = ['Hai', 'Ba', 'Tư', 'Năm', 'Sáu', 'Bảy', 'CN'];
?>
<div class="col-md-6">
	<? $form = ActiveForm::begin(); ?>
	<p><strong>PASSPORT INFORMATION</strong></p>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theForm, 'ppt_country') ?></div>
		<div class="col-md-6"><?= $form->field($theForm, 'ppt_number') ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theForm, 'ppt_fname') ?></div>
		<div class="col-md-6"><?= $form->field($theForm, 'ppt_lname') ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theForm, 'ppt_gender') ?></div>
		<div class="col-md-6"><?= $form->field($theForm, 'ppt_bday') ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?//= $form->field($theForm, 'ppt_issue') ?></div>
		<div class="col-md-6"><?//= $form->field($theForm, 'ppt_expiry') ?></div>
	</div>
	<div class="text-right"><?= Html::submitButton('Save', ['class'=>'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>