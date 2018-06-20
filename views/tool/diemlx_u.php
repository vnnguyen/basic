<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_diemlx_inc.php');

if ($theEntry->isNewRecord) {
	$this->title = 'Thêm lái xe và điểm lái xe cho tour';
	// $this->params['breadcrumb'][] = ['New', '@web/invoices/c?booking_id='.$theBooking['id']];
} else {
	$this->title = 'Sửa điểm lái xe tour: '.$theEntry['tour']['code'];
	// $this->params['breadcrumb'][] = ['View', '@web/invoices/r/'.$theEntry['id']];
	// $this->params['breadcrumb'][] = ['Edit', '@web/invoices/u/'.$theEntry['id']];
}

?>
<? $form = ActiveForm::begin(); ?>
<div class="col-md-8">
	<div class="row">
		<div class="col-md-2"><?= $form->field($theEntry, 'tour_id')->dropdownList(ArrayHelper::map($tourList, 'id', 'name'), ['prompt'=>'- Chọn -']) ?></div>
		<div class="col-md-4"><?= $form->field($theEntry, 'driver_user_id')->dropdownList(ArrayHelper::map($driverList, 'id', 'name'), ['prompt'=>'- Chọn -']) ?></div>
		<div class="col-md-2"><?= $form->field($theEntry, 'from_dt') ?></div>
		<div class="col-md-2"><?= $form->field($theEntry, 'until_dt') ?></div>
		<div class="col-md-2"><?= $form->field($theEntry, 'points') ?></div>
	</div>
	<?= $form->field($theEntry, 'note')->textArea(['rows'=>5]) ?>
	<div class="text-right"><?= Html::submitButton('Ghi các thay đổi', ['class'=>'btn btn-primary']) ?></div>
</div>
<div class="col-md-4">
</div>
<?
ActiveForm::end();

$js = <<<TXT
$('#diemlx-from_dt, #diemlx-until_dt').daterangepicker({
	minDate:'2007-01-01',
	maxDate:'2027-01-01',
	//startDate:moment(),
	format:'YYYY-MM-DD HH:mm',
	showDropdowns:true,
	singleDatePicker:true,
	timePicker:true,
	timePicker12Hour:false,
	timePickerIncrement:1
});
TXT;
$js = str_replace('moment()', "'".$theEntry['from_dt']."'", $js);
$this->registerCssFile(DIR.'assets/bootstrap-daterangepicker_1.3.7/daterangepicker-bs3.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/moment_2.7.0/moment.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/bootstrap-daterangepicker_1.3.7/daterangepicker.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);

