<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

if ($model->isNewRecord) {
	$this->title  = 'New entry';
	$this->params['icon'] = 'edit';
	$this->params['breadcrumb'] = [
		['Community', 'community'],
		['Knowledge base', 'kb'],
		['Lists', 'kb/lists'],
		['Bons plans', 'kb/lists/ssspots'],
		['Add', URI],
	];
} else {
	$this->title  = 'Edit: '.$model['name'];
	$this->params['icon'] = 'edit';
	$this->params['breadcrumb'] = [
		['Community', 'community'],
		['Knowledge base', 'kb'],
		['Lists', 'kb/lists'],
		['Bons plans', 'kb/lists/ssspots'],
		['View', 'kb/lists/ssspots/r/'.$model['id']],
		['Edit', URI],
	];
}

$this->params['active'] = 'kb';
$this->params['active2'] = 'kblist';

?>
<? $form = ActiveForm::begin(); ?>
<div class="col-lg-8">
	<?= $form->field($model, 'destination_id')->dropdownList(ArrayHelper::map($destinations, 'id', 'name_en', 'country_code')); ?>
	<?= $form->field($model, 'name') ?>
	<?= $form->field($model, 'url') ?>
	<?= $form->field($model, 'summary')->textArea(['rows'=>10]) ?>
</div>
<div class="col-lg-4">
	<?= $form->field($model, 'entry_order'); ?>
	<?= $form->field($model, 'status')->dropdownList(['on'=>'On', 'off'=>'Off', 'draft'=>'Draft', 'deleted'=>'Deleted']); ?>
	<div><?=Html::submitButton(Yii::t('mn', 'Save changes'), ['class' => 'btn btn-primary btn-block']); ?></div>
	<hr>
	<? if (!$model->isNewRecord) { ?>
	<p>Updated: <?= $model->updated_at ?> by <?= $model->updatedBy->name ?></p>
	<? } ?>
</div>
<? ActiveForm::end();
