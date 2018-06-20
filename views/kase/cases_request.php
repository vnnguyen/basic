<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;
use app\helpers\DateTimeHelper;

include('_cases_inc.php');

$this->title = 'Edit customer request: '.$theCase['name'];
$this->params['breadcrumb'][] = ['View', '@web/cases/r/'.$theCase['id']];
$this->params['breadcrumb'][] = ['Customer request', '@web/cases/request/'.$theCase['id']];

?>
<div class="col-md-6">
	<p><strong>THE REQUEST</strong></p>
	<? $form = ActiveForm::begin(); ?>
	<?= $form->field($caseStats, 'pa_destinations') ?>
	<div class="row">
		<div class="col-md-3"><?= $form->field($caseStats, 'pa_pax') ?></div>
		<div class="col-md-3"><?= $form->field($caseStats, 'pa_pax_ages') ?></div>
		<div class="col-md-3"><?= $form->field($caseStats, 'pa_days') ?></div>
		<div class="col-md-3"><?= $form->field($caseStats, 'pa_start_date') ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?= $form->field($caseStats, 'pa_tour_type') ?></div>
		<div class="col-md-6"><?= $form->field($caseStats, 'pa_group_type') ?></div>
	</div>
	<?= $form->field($caseStats, 'pa_tags') ?>
	<div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>
<div class="col-md-6">
</div>
<?
$js = <<<TXT
$('#kasestats-avail_from_date').daterangepicker({
	minDate:'2007-01-01',
	maxDate:'2050-01-01',
	startDate:'{dt}',
	endDate:'{dt}',
	format:'YYYY-MM-DD',
	showDropdowns:true,
	singleDatePicker:true,
	//timePicker:true,
	//timePicker12Hour:false,
	//timePickerIncrement:1
});

TXT;

if ($caseStats['avail_from_date'] == '0000-00-00') {
	$dt = date('Y-m-d', strtotime('+ 1 month'));
} else {
	$dt = $caseStats['avail_from_date'];
}

$this->registerCssFile(DIR.'assets/bootstrap-daterangepicker_1.3.9/daterangepicker-bs3.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/moment/moment/moment.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/bootstrap-daterangepicker_1.3.9/daterangepicker.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs(str_replace(['{dt}'], [$dt], $js));