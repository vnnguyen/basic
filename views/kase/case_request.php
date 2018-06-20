<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use app\helpers\DateTimeHelper;

include('_kase_inc.php');

Yii::$app->params['page_title'] = 'Edit customer request: '.$theCase['name'];
Yii::$app->params['page_breadcrumbs'][] = ['View', '@web/cases/r/'.$theCase['id']];
Yii::$app->params['page_breadcrumbs'][] = ['Customer request'];

?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-body">
            <? $form = ActiveForm::begin(['layout'=>'horizontal']); ?>
            <?= $form->field($caseStats, 'pa_destinations')->checkboxList(ArrayHelper::map($countryList, 'code', 'name_en'))->label(Yii::t('k', 'Destination countries')) ?>
            <?= $form->field($caseStats, 'pa_pax')->label('Number of pax')->hint('10 or 10-13') ?>
            <?= $form->field($caseStats, 'pa_pax_ages')->label('Ages of pax')->hint('30 or 30-40 or 30,32,50') ?>
            <?= $form->field($caseStats, 'pa_days')->label('Number of days')->hint('10 or 20-25') ?>
            <?= $form->field($caseStats, 'pa_start_date') ?>
            <?= $form->field($caseStats, 'pa_tour_type') ?>
            <?= $form->field($caseStats, 'pa_group_type') ?>
            <?= $form->field($caseStats, 'pa_tags') ?>
            <div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
            <? ActiveForm::end(); ?>
        </div>
    </div>
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

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/js/bootstrap-select.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile(DIR.'assets/bootstrap-daterangepicker_1.3.9/daterangepicker-bs3.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile(DIR.'assets/moment/moment/moment.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile(DIR.'assets/bootstrap-daterangepicker_1.3.9/daterangepicker.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs(str_replace(['{dt}'], [$dt], $js));