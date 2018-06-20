<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_bookings_inc.php');

$this->title = 'New proposal / booking';

?>
<? $form = ActiveForm::begin(); ?>
<div class="col-md-8">
	<div class="well well-sm">
		Product: <?= Html::a($theProduct['title'], 'products/sb/'.$theProduct['id']) ?>
	</div>
	<?= $form->field($theBooking, 'case_id')->dropdownList(ArrayHelper::map($theCases, 'id', 'name'), ['prompt'=>'- Select a case -']) ?>
	<div class="row">
		<div class="col-md-3"><?= $form->field($theBooking, 'pax') ?></div>
		<div class="col-md-3"><?= $form->field($theBooking, 'price') ?></div>
		<div class="col-md-3"><?= $form->field($theBooking, 'currency') ?></div>
	</div>
	<?= $form->field($theBooking, 'note')->textArea(['rows'=>5])->hint('Leave blank if no information') ?>
	<div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
</div>
<div class="col-md-4">
	<p><strong>NOTE</strong></p>
	<p>Price = total price for this booking</p>
	<p>Pax = total number of pax for this booking</p>
</div>
<? ActiveForm::end(); ?>