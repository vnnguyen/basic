<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->title = 'Add old tour';
$this->params['breadcrumb'] = [
	['Special', 'huan'],
	['Add old tour'],
];
?>
<div class="col-md-8">
	<? $form = ActiveForm::begin();?>
	<div class="row">
		<div class="col-md-4"><?= $form->field($theForm, 'code'); ?></div>
		<div class="col-md-4"><?= $form->field($theForm, 'name'); ?></div>
		<div class="col-md-4"><?= $form->field($theForm, 'destinations'); ?></div>
	</div>
	<div class="row">
		<div class="col-md-4"><?= $form->field($theForm, 'start_date'); ?></div>
		<div class="col-md-4"><?= $form->field($theForm, 'days'); ?></div>
		<div class="col-md-4"><?= $form->field($theForm, 'pax'); ?></div>
	</div>
	<div class="row">
		<div class="col-md-4"><?= $form->field($theForm, 'bh')->dropdownList(ArrayHelper::map($staffList, 'id', 'name'), ['prompt'=>'- Select -']); ?></div>
		<div class="col-md-4"><?= $form->field($theForm, 'dh'); ?></div>
		<div class="col-md-4"><?= $form->field($theForm, 'cskh'); ?></div>
	</div>
	<div class="text-right"><?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>
