<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->title = 'Edit special: '.$theVenue['name'];
$this->params['breadcrumb'] = [
	['Special', 'huan'],
	['Check venue stype', 'huan/check-venue-stype'],
	['Edit', URI],
];
?>
<div class="col-lg-8">
	<? $form = ActiveForm::begin();?>
	<div class="row">
		<div class="col-md-4"><?= $form->field($theVenue, 'name'); ?></div>
		<div class="col-md-4"><?= $form->field($theVenue, 'about'); ?></div>
		<div class="col-md-4"><?= $form->field($theVenue, 'abbr'); ?></div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?= $form->field($theVenue, 'stype')->dropdownList($stypeList, ['prompt'=>'- Select -']) ?>
		</div>
		<div class="col-md-6">
			<?= $form->field($theVenue, 'destination_id')->dropdownList(ArrayHelper::map($destinationList, 'id', 'name_vi', 'country_code'), ['prompt'=>'- Select -']) ?>
		</div>
	</div>	
	<div class="text-right"><?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>
