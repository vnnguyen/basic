<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_payments_inc.php');

if ($thePayment->isNewRecord) {
	$this->title = 'New payment';
	$this->params['breadcrumb'][] = ['New', 'payments/c?booking_id='.$theBooking['id']];
} else {
	$this->title = 'Edit payment: '.number_format($thePayment['amount'], 2).' '.$thePayment['currency'];
	$this->params['breadcrumb'][] = ['View', 'payments/r/'.$thePayment['id']];
	$this->params['breadcrumb'][] = ['Edit', 'payments/u/'.$thePayment['id']];
}

?>
<? $form = ActiveForm::begin(); ?>
<div class="col-md-8">
	<div class="well well-sm">
		<? if ($thePayment->isNewRecord) { ?>
		Booking: (<?= Html::a('ID '.$theBooking['id'], 'bookings/r/'.$theBooking['id']) ?>) <?= Html::a($theBooking['product']['title'], 'products/r/'.$theBooking['product']['id']) ?> @<i class="fa fa-briefcase text-muted"></i> <?= Html::a($theBooking['case']['name'], 'cases/r/'.$theBooking['case']['id']) ?> by  <?= $theBooking['createdBy']['name'] ?>
		<? } else { ?>
		Booking <?= Html::a($thePayment['booking']['id'], 'bookings/r/'.$thePayment['booking']['id']) ?> | Tour <?= Html::a($thePayment['booking']['product']['tour']['code'], 'tours/r/'.$thePayment['booking']['product']['tour']['id']) ?> | Product <?= Html::a($thePayment['booking']['product']['title'], 'products/r/'.$thePayment['booking']['product']['id']) ?> by <?= $thePayment['booking']['createdBy']['name'] ?>
		<? } ?>
	</div>
	<div class="row">
		<div class="col-md-6"><?= $form->field($thePayment, 'payment_dt') ?></div>
		<div class="col-md-6"><?= $form->field($thePayment, 'ref') ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?= $form->field($thePayment, 'payer') ?></div>
		<div class="col-md-6"><?= $form->field($thePayment, 'payee') ?></div>
	</div>
	<div class="row">
		<div class="col-md-4"><?= $form->field($thePayment, 'method') ?></div>
		<div class="col-md-3"><?= $form->field($thePayment, 'amount') ?></div>
		<div class="col-md-2"><?= $form->field($thePayment, 'currency')->dropdownList(['EUR'=>'EUR', 'USD'=>'USD', 'VND'=>'VND'], ['prompt'=>'- Select -']) ?></div>
		<div class="col-md-3"><?= $form->field($thePayment, 'xrate') ?></div>
	</div>
	<?= $form->field($thePayment, 'note')->textArea(['rows'=>5]) ?>
	<div class="text-right"><?= Html::submitButton('Save changes', ['class'=>'btn btn-primary']) ?></div>
</div>
<div class="col-md-4">
</div>
<? ActiveForm::end(); ?>
<?
$js = <<<TXT

$('#payment-payment_dt').daterangepicker({
	minDate:'2007-01-01',
	maxDate:'2050-01-01',
	startDate:moment(),
	format:'YYYY-MM-DD HH:mm',
	showDropdowns:true,
	singleDatePicker:true,
	timePicker:true,
	timePicker12Hour:false,
	timePickerIncrement:5
});
TXT;
$this->registerCssFile(DIR.'assets/dangrossman/bootstrap-daterangepicker/daterangepicker-bs3.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/moment/moment/moment.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/dangrossman/bootstrap-daterangepicker/daterangepicker.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);