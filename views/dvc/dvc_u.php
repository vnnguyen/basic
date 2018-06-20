<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_dvc_inc.php');

Yii::$app->params['page_breadcrumbs'][] = ['CPDV', 'dv'];
Yii::$app->params['page_breadcrumbs'][] = ['Service contract', 'dvc'];
Yii::$app->params['page_breadcrumbs'][] = [$theVenue['name'], 'venues/r/'.$theVenue['id']];
Yii::$app->params['page_breadcrumbs'][] = ['Hợp đồng'];

if ($theDvc->isNewRecord) {
    Yii::$app->params['page_title'] = 'Thêm hợp đồng dịch vụ';
} else {
    Yii::$app->params['page_title'] = 'Sửa hợp đồng dịch vụ: '.$theDvc['name'];
    Yii::$app->params['page_breadcrumbs'][] = ['Xem', 'dvc/r/'.$theDvc['id']];
}

// \fCore::expose($theVenue); exit;

if ($theDvc->signed_dt == '0000-00-00 00:00:00') {
    $theDvc->signed_dt = '';
}

?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Service contract</h6>
        </div>
        <div class="panel-body">
            <? $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Service venue/supplier</label>
                        <p class="form-control-static"><?= Html::a($theVenue['name'], '/venues/r/'.$theVenue['id']) ?></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"><?= $form->field($theDvc, 'name')->label('Name') ?></div>
                <div class="col-md-3"><?= $form->field($theDvc, 'number')->label('Ref No.') ?></div>
                <div class="col-md-3"><?= $form->field($theDvc, 'signed_dt')->label('Date signed') ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theDvc, 'valid_from_dt')->label('Validity from-to') ?></div>
                <div class="col-md-3"><?//= $form->field($theDvc, 'valid_until_dt')->label('Valid until') ?></div>
            </div>
            <?= $form->field($theDvc, 'body')->textArea(['rows'=>10])->label('Contract body') ?>

            <div class="form-group">
                <label class="control-label">Upload files</label>
                <?= $form->field($model, 'uploadFiles[]')->fileInput(['multiple' => true, 'accept' => '.pdf,.doc,.docx,.docm,.jpg,.jpeg,.png,.gif,.tiff,.zip,.rar,.gz,.xls,.xlsx,.xlsm,.txt']) ?>
            </div>

            <?=$form->field($theDvc, 'note')->textArea(['rows'=>5])->label('Note (for Amica)') ?>
            <div class="text-right"><?= Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']) ?></div>
            <? ActiveForm::end(); ?>            
        </div>
    </div>
</div>
<div class="col-md-4">
    <? if (isset($theVenue)) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Related contracts</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-xxs">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theVenue['dvc'] as $dvc) { ?>
                    <tr>
                        <td class="text-nowrap"><?= $dvc['name'] ?></td>
                        <td><?= $dvc['description'] ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
    <? } // if isset theVenues ?>
</div>
<style>
.datepicker>div {display:block;}
</style>
<?
// app\assets\CkeditorAsset::register($this);
// $this->registerJs(app\assets\CkeditorAsset::ckeditorJs());

$js = <<<'TXT'

$('#dvc-body').ckeditor({
    allowedContent: 'p sub sup strong em s a i u ul ol li img blockquote;',
    entities: false,
    entities_greek: false,
    entities_latin: false,
    uiColor: '#ffffff',
    height:400,
    contentsCss: '/assets/css/ckeditor_160828.css'
    //contentCss:'https://my.amicatravel.com/assets/css/ckeditor_160828.css'
});

$('#dvc-valid_from_dt').datepicker({
    firstDay: 1,
    todayButton: true,
    clearButton: true,
    autoClose: true,
    range: true,
    multipleDatesSeparator: ' - ',
    language: 'en',
    dateFormat: 'yyyy-mm-dd'
});

$('#dvc-signed_dt').datepicker({
    firstDay: 1,
    todayButton: true,
    clearButton: true,
    autoClose: true,
    language: 'en',
    dateFormat: 'yyyy-mm-dd'
});

$('#dvc-signed_dt, #dvc-valid_from_dt').on('cancel.daterangepicker', function(ev, picker) {
    // $(this).val('');
});



TXT;
$this->registerJs($js);

$this->registerJsFile('https://cdn.ckeditor.com/4.6.2/basic/ckeditor.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdn.ckeditor.com/4.6.2/basic/adapters/jquery.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24/daterangepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.en.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/css/datepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);