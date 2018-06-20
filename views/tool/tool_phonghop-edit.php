<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_layout'] = '-t';
Yii::$app->params['page_title'] = 'Thêm sự kiện';
if ($action == 'edit') {
    Yii::$app->params['page_title'] = 'Sửa sự kiện';
} elseif ($action == 'reg') {
    Yii::$app->params['page_title'] = 'Đăng ký sử dụng phòng họp';
}
Yii::$app->params['page_breadcrumbs'] = [
    ['Tools', 'tools'],
    ['Sử dụng phòng họp', 'tools/phonghop'],
];

$timeList = [
    't'=>'Cụ thể',
    'm'=>'Sáng',
    'a'=>'Chiều',
    'e'=>'Chưa biết',
];

$purpose = ['#c'];
$purposeList = [
    't'=>'Thu/Trả lại tiền',
    's'=>'Tổ chức SN',
    'q'=>'Tặng quà SN',
    'c'=>'Tặng quà khách cũ',
];
$table = '#1';
$tableList = [
    ''=>'Không có',
    '1'=>'Bàn 1',
    '2'=>'Bàn 2',
    '3'=>'Bàn 3',
    '4'=>'Bàn 4',
];

$atList = [
    'all'=>'All locations',
    'hanoi'=>'Hanoi office',
    'saigon'=>'Saigon office',
    'luangprabang'=>'Luang Prabang office',
];

$eventStatusList = [
    'on'=>'Active',
    'off'=>'Canceled',
    'draft'=>'Pending approval',
    'deleted'=>'Deleted',
];

$venueList = [
    ['id'=>'hn/02/01', 'venue'=>'VP Hà Nội, Tầng 2, Bàn 1', 'location'=>'Hà Nội', ],
    ['id'=>'hn/02/02', 'venue'=>'VP Hà Nội, Tầng 2, Bàn 2', 'location'=>'Hà Nội', ],
    ['id'=>'hn/02/03', 'venue'=>'VP Hà Nội, Tầng 2, Bàn 3', 'location'=>'Hà Nội', ],
    ['id'=>'hn/02/04', 'venue'=>'VP Hà Nội, Tầng 2, Bàn 4', 'location'=>'Hà Nội', ],
    ['id'=>'hn/03/01', 'venue'=>'VP Hà Nội, Tầng 3', 'location'=>'Hà Nội', ],
    ['id'=>'hn/05/01', 'venue'=>'VP Hà Nội, Tầng 5', 'location'=>'Hà Nội', ],
    ['id'=>'hn/06/01', 'venue'=>'VP Hà Nội, Tầng 6', 'location'=>'Hà Nội', ],
    ['id'=>'sg', 'venue'=>'VP Sài Gòn', 'location'=>'Sài Gòn', ],
    ['id'=>'sr', 'venue'=>'VP Siem Reap', 'location'=>'Siem Reap', ],
    ['id'=>'lp', 'venue'=>'VP Luang Prabang', 'location'=>'Luang Prabang', ],
];

$form = ActiveForm::begin();

?>
<div class="col-md-8">
    <div class="panel panel-body">
        <div class="row">
            <div class="col-md-8"><?= $form->field($theForm, 'name') ?></div>
            <div class="col-md-4"><?= $form->field($theForm, 'attendee_count', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>1, 'max'=>999]])->label('Approx. number of attendees') ?></div>
        </div>
        <?= $form->field($theForm, 'info')->textArea(['rows'=>3])->label('Description') ?>
        <div class="row">
            <div class="col-md-5"><?= $form->field($theForm, 'venue')->dropdownList(ArrayHelper::map($venueList, 'id', 'venue', 'location')) ?></div>
            <div class="col-md-3"><?= $form->field($theForm, 'start_date')->label('Start date (yyyy-mm-dd)') ?></div>
            <div class="col-md-2"><?= $form->field($theForm, 'start_time')->label('Time (hh:mm)') ?></div>
            <div class="col-md-2"><?= $form->field($theForm, 'mins', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>0, 'max'=>480]])->label('Length (mins)') ?></div>
        </div>
        <? if ($action == 'reg') { ?>
        <p class="text-warning"><i class="fa fa-info-circle"></i> Your registration info will be sent to Khang Ha for review. You will get a reply soon.</p>
        <? } else { ?>
        <div class="row">
            <div class="col-md-5"><?= $form->field($theForm, 'status')->dropdownList($eventStatusList) ?></div>
        </div>
        <? } ?>
        <?= Html::submitButton('Save changes', ['class'=>'btn btn-primary']) ?>
        <?= Html::a('Cancel', '?') ?>
    </div>
</div>
<?

ActiveForm::end();

$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/locales/bootstrap-datepicker.'.Yii::$app->language.'.min.js', ['depends'=>'yii\web\JqueryAsset']);

$js = <<<'TXT'
$('#sukienphonghopform-start_date').datepicker({
    format: "yyyy-mm-dd",
    weekStart: 1,
    todayBtn: "linked",
    clearBtn: true,
    language: "vi",
    autoclose: true
});
TXT;

$this->registerJs($js);