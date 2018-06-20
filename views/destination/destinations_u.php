<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

if ($theDest->isNewRecord) {
	$this->title = 'New destination';
	$this->params['icon'] = 'icon-plus';
	$this->params['breadcrumb'] = [
		['Destinations', 'destinations'],
		['Add', 'destinations/c'],
	];
} else {
	$this->title = 'Edit: '.$theDest['name_en'];
	$this->params['icon'] = 'icon-edit';
	$this->params['breadcrumb'] = [
		['Destinations', 'destinations'],
		['View', 'destinations/r/'.$theDest['id']],
		['Edit', 'destinations/u/'.$theDest['id']],
	];
	$this->params['actions'] = [
		['View', 'destinations/r/'.$theDest['id'], 'icon-eye-open'],
		['Edit', 'destinations/u/'.$theDest['id'], 'icon-edit'],
		['Delete', 'destinations/d/'.$theDest['id'], 'icon-trash'],
	];
}
?>
<div class="col-lg-8">
	<? $form = ActiveForm::begin();?>
	<div class="row">
		<div class="col-lg-6">
			<?=$form->field($theDest, 'name_en'); ?>
		</div>
		<div class="col-lg-6">
			<?=$form->field($theDest, 'name_vi'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6">
			<?=$form->field($theDest, 'name_fr'); ?>
		</div>
		<div class="col-lg-6">
			<?=$form->field($theDest, 'name_local'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6">
			<?=$form->field($theDest, 'latlng'); ?>
		</div>
		<div class="col-lg-6">
			<?=$form->field($theDest, 'country_code')->dropDownList(ArrayHelper::map($allCountries, 'code', 'name_en'), ['prompt'=>'- Select -',]) ?>
		</div>
	</div>
	<div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?></div>
	<? ActiveForm::end(); ?>
</div>
<div class="col-lg-4">
	<h3>Info</h3>
	<ul>
		<li>Created at: <?=$theDest->created_at?></li>
		<li>Updated at: <?=$theDest->created_at?></li>
	</ul>
</div>
<style type="text/css">
.field-meta-k label, .field-meta-v label, .field-meta-x label {display: none}
</style>