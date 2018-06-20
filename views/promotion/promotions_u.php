<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

if ($model->isNewRecord) {
	$this->title = 'New promotion';
	$this->params['icon'] = 'plus';
	$this->params['breadcrumb'] = [
		['Promotions', 'promotions'],
		['Add', 'promotions/c'],
	];
} else {
	$this->title = 'Edit: '.$model['name'];
	$this->params['icon'] = 'edit';
	$this->params['breadcrumb'] = [
		['Promotions', 'promotions'],
		['View', 'promotions/r/'.$model['id']],
		['Edit', 'promotions/u/'.$model['id']],
	];
	$this->params['actions'] = [
		['View', 'promotions/r/'.$model['id'], 'eye'],
		['Edit', 'promotions/u/'.$model['id'], 'edit'],
		['Delete', 'promotions/d/'.$model['id'], 'trash-o'],
	];
}
$this->params['active'] = 'sales';
$this->params['active2'] = 'promotions';

?>
<div class="col-lg-8">
	<? $form = ActiveForm::begin();?>
	<div class="row">
		<div class="col-lg-8">
			<?=$form->field($model, 'name'); ?>
		</div>
		<div class="col-lg-4">
			<?=$form->field($model, 'code'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6 datepicker">
			<?=$form->field($model, 'start_dt'); ?>
		</div>
		<div class="col-lg-6 datepicker">
			<?=$form->field($model, 'end_dt'); ?>
		</div>
	</div>
	<?=$form->field($model, 'info')->textArea(['rows'=>4]); ?>
	<div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?></div>
	<? ActiveForm::end(); ?>
</div>
<div class="col-lg-4">
	<h3>Info</h3>
	<ul>
		<li>Created at: <?=$model->created_at?></li>
		<li>Updated at: <?=$model->created_at?></li>
	</ul>
</div>
<script>
$(function(){
	$('.datepicker input').datepicker({
	changeYear: true,
	changeMonth: true,
	//yearRange: '-0y-0m_:+5y',
	//minDate: '+1 d',
	showOtherMonths: true,
	showButtonPanel: true,
	firstDay: 1,
	duration: 0,
	dateFormat: 'yy-mm-dd'
	});
})
</script>
