<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

if ($model->isNewRecord) {
	$this->title = 'New campaign';
	$this->params['icon'] = 'plus';
	$this->params['breadcrumb'] = [
		['Campaigns', 'campaigns'],
		['Add'],
	];
} else {
	$this->title = 'Edit: '.$model['name'];
	$this->params['icon'] = 'edit';
	$this->params['breadcrumb'] = [
		['Campaigns', '@web/campaigns'],
		['View', '@web/campaigns/r/'.$model['id']],
		['Edit'],
	];
}
include('campaigns__inc.php');

?>
<div class="col-md-8">
	<? $form = ActiveForm::begin();?>
	<div class="row">
		<div class="col-md-6">
			<?=$form->field($model, 'name'); ?>
		</div>
		<div class="col-md-4">
			<?=$form->field($model, 'code'); ?>
		</div>
		<div class="col-md-2">
			<?=$form->field($model, 'status')->dropdownList($campaignStatusList); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?=$form->field($model, 'start_dt'); ?>
		</div>
		<div class="col-md-6">
			<?=$form->field($model, 'end_dt'); ?>
		</div>
	</div>
	<?=$form->field($model, 'info')->textArea(['rows'=>4]); ?>
	<div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?></div>
	<? ActiveForm::end(); ?>
</div>
<div class="col-md-4">
	<? if (!$model->isNewRecord) { ?>
	<h3>Info</h3>
	<ul>
		<li>Created at: <?=$model->created_at?></li>
		<li>Updated at: <?=$model->created_at?></li>
	</ul>
	<? } ?>
</div>
<?
$jsCode = <<<TXT
$('#campaign-start_dt, #campaign-end_dt').datetimepicker({
	format:'Y-m-d H:i',
	value:$(this).val()
});
TXT;
$this->registerCssFile('assets/plugins/xdan/datetimepicker/jquery.datetimepicker.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('assets/plugins/xdan/datetimepicker/jquery.datetimepicker.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($jsCode);