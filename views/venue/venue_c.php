<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_venue_inc.php');

$this->title = 'New venue';


?>
<div class="col-md-8">
	<? $form = ActiveForm::begin(['class'=>'form-inline well well-sm']); ?>
	<?= $form->field($theVenue, 'name') ?>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theVenue, 'stype')->dropdownList($venueTypes, ['prompt'=>'- Select type -']) ?></div>
		<div class="col-md-6"><?= $form->field($theVenue, 'destination_id')->dropdownList(ArrayHelper::map($destinationList, 'id', 'name_en', 'country_code'), ['prompt'=>'- Select destination -']) ?></div>
	</div>
	<?= Html::submitButton('Save and continue', ['class'=>'btn btn-primary']) ?>
	<? ActiveForm::end(); ?>
</div>
