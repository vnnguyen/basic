<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$gheList = [
    ['id'=>1, 'name'=>'Ghế trẻ em 1'],
    ['id'=>2, 'name'=>'Ghế trẻ em 2'],
    ['id'=>3, 'name'=>'Ghế trẻ em 3'],
];


Yii::$app->params['page_layout'] = '-t';
Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_title'] = 'Lịch sử dụng ghế trẻ em (3 ghế)';
Yii::$app->params['page_breadcrumbs'] = [
    ['Tools', 'tools'],
    ['Ghế trẻ em'],
];


?>
<style>
.ghe1 {background-color:#f60;}
.ghe2 {background-color:#60f;}
.ghe3 {background-color:#6f0;}
</style>
<div class="col-md-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Đăng ký sử dụng ghế trẻ em</h6>
        </div>
        <div class="panel-body">
            <p>Ai cũng có thể đăng ký sử dụng cho tour của mình (nếu còn ghế chưa dùng)</p>
            <?php $form = ActiveForm::begin() ?>
            <?= $form->field($theForm, 'ghe')->dropdownList(ArrayHelper::map($gheList, 'id', 'name'), ['prompt'=>Yii::t('app', '- Select -')]) ?>
            <?= $form->field($theForm, 'tour') ?>
            <?= $form->field($theForm, 'tu') ?>
            <?= $form->field($theForm, 'note') ?>
            <?= Html::submitButton(Yii::t('app', 'Submit'), ['class'=>'btn btn-primary btn-block']) ?>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Lịch sử dụng ghế trẻ em</h6>
        </div>
        <div class="panel-body" id="calendar"></div>
    </div>
</div>
<?php

$js = <<<'JS'
$.fn.datepicker.language['vi'] = {
    days: ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'],
    daysShort: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
    daysMin: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
    months: ['Tháng giêng','Tháng hai','Tháng ba','Tháng tư','Tháng năm','Tháng sáu', 'Tháng bảy','Tháng tám','Tháng chín','Tháng mười','Tháng mười một','Tháng mười hai'],
    monthsShort: ['Th 1', 'Th 2', 'Th 3', 'Th 4', 'Th 5', 'Th 6', 'Th 7', 'Th 8', 'Th 9', 'Th 10', 'Th 11', 'Th 12'],
    today: 'Hôm nay',
    clear: 'Xoá',
    dateFormat: 'dd/mm/yyyy',
    timeFormat: 'hh:ii aa',
    firstDay: 1
};
$('#dksdghetreemform-tu, #dksdghetreemform-den').datepicker({
    dateFormat: "yyyy-mm-dd",
    firstDay: 1,
    language: 'vi',
    timepicker: true,
    timeFormat: "hh:ii",
    maxMinutes: 0,
    range: true,
    multipleDatesSeparator: ' - ',
    todayButton: true,
    clearButton: true,
});

$('#calendar').fullCalendar({
    // header: {
    //     left: 'prev,next today',
    //     center: 'title',
    //     right: 'listDay,listWeek,month'
    // },
    events: '?action=load',
})
JS;

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/css/datepicker.min.css', ['depends'=>'app\assets\MainAsset']);

$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/fullcalendar.min.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/fullcalendar.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/locale/vi.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);