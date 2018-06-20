<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

require_once('_tours_inc.php');

$this->title = 'In chương trình và chi phí do hướng dẫn trả: '.$theTour['op_code'];

$this->params['breadcrumb'] = [
    ['Tour operation', '#'],
    ['Tours', 'tours'],
    [substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
    [$theTour['op_code'], 'tours/r/'.$theTourOld['id']],
    ['Chi phí HD', URI],
];

$dayIdList = explode(',', $theTour['day_ids']);
$dayCnt = [];
$cnt = 0;
foreach ($dayIdList as $id) {
    $cnt ++;
    $dayCnt[$cnt] = $cnt;
}

$languageList = [
    'en'=>'English',
    'fr'=>'Francais',
    'vi'=>'Tiếng Việt',
];

$optionList = [
    'cpt'=>'Không in cpt không liên quan',
    'ncc'=>'Không in danh sách địa chỉ NCC',
    'not'=>'Không in lời ghi chú cố định',
    'vnd'=>'Để nguyên tệ, không chuyển sang VND',
];

?>
<div class="col-md-8">
    <div class="alert alert-info">
Form này sẽ in cho [Tour guide] một bảng các chi phí do [Người thanh toán] phải trả trong khoảng ngày được chọn. Khoảng ngày có dạng vd <kbd>1-5,6,8-9</kbd>
    </div>
    <? $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4"><?= $form->field($theForm, 'language')->dropdownList($languageList) ?></div>
        <div class="col-md-4"><?= $form->field($theForm, 'days') ?></div>
    </div>
    <div class="row">
        <div class="col-md-4"><?= $form->field($theForm, 'tourguide')->dropdownList(ArrayHelper::map($tourguideList, 'guide_name', 'guide_name')) ?></div>
        <div class="col-md-4"><?= $form->field($theForm, 'payer')->dropdownList(ArrayHelper::map($payerList, 'payer', 'payer')) ?></div>
        <div class="col-md-4"><?= $form->field($theForm, 'driver')->dropdownList(ArrayHelper::map($driverList, 'driver_user_id', 'driver_name'), ['prompt'=>'( Để trống )']) ?></div>
    </div>
    <?= $form->field($theForm, 'options')->checkboxList($optionList)->label('Other options') ?>
    <?= $form->field($theForm, 'note')->textArea(['rows'=>10]) ?>
    
    <div class="text-right"><?= Html::submitButton('Print form', ['class'=>'btn btn-primary']) ?></div>
    <? ActiveForm::end(); ?>
</div>
<div class="col-md-4">
    <p><strong>TOUR ITINERARY</strong></p>
    <ul class="list-unstyled">
<?
$cnt = 0;
foreach ($dayIdList as $id) {
    foreach ($theTour['days'] as $day) {
        if ($id == $day['id']) {
?>
        <li><strong><?= $cnt + 1 ?></strong> (<?= date('j/n/Y', strtotime('+'.($cnt).' days', strtotime($theTour['day_from']))) ?>) <?= $day['name'] ?></li>
<?
            $cnt ++;
        }
    }
}
?>
    </ul>
</div>
<style>
#tourinhdform-options label {display:block;}
</style>