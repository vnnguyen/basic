<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_qhkh_inc.php');

$ratingList = [];
for ($i = 0; $i <= 10; $i ++) {
    $ratingList[$i * 10] = $i;
    if ($i != 10) {
        $ratingList[$i * 10 + 5] = $i + 0.5;
    }
}

Yii::$app->params['page_title'] = 'Chốt tour: '.$theTour['op_code'];
?>
<div class="col-md-8">
    <div class="card card-body">
        <? $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-md-6"><?= $form->field($theForm, 'qhkh_ketthuc')->dropdownList($qhkhChotKetthucList)->label('Tình trạng kết thúc tour') ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $form->field($theForm, 'qhkh_diem')->dropdownList($qhkhChotDiemList, ['prompt'=>'Không có điểm'])->label('Điểm hài lòng (QHKH đánh giá)') ?></div>
            <div class="col-md-6"><?= $form->field($theForm, 'khach_diem', ['inputOptions'=>['class'=>'form-control'/*, 'type'=>'number', 'min'=>0, 'max'=>10, 'step'=>0.5*/]])->label('Số điểm do khách đánh giá') ?></div>
        </div>
        <?= $form->field($theForm, 'qhkh_da_khaithac')->checkboxList($qhkhChotDaKhaithacList)->label('QHKH đã khai thác') ?>
        <hr>
        <?= $form->field($theForm, 'qhkh_dexuat_khaithac')->checkboxList($qhkhChotDeXuatKhaithacList)->label('QHKH đề xuất khai thác') ?>
        <hr>
        <?= $form->field($theForm, 'mkt_da_khaithac')->checkboxList($qhkhChotKhaithacList)->label('Marketing đã khai thác') ?>
        <div>
            <?= Html::submitButton(Yii::t('x', 'Save changes'), ['class'=>'btn btn-primary']) ?>
            <?= Html::a(Yii::t('x', 'Cancel'), '?') ?>
        </div>
        <? ActiveForm::end() ?>            
    </div>
</div>
<style>
.form-group.field-chottourform-qhkh_da_khaithac label,
.form-group.field-chottourform-qhkh_dexuat_khaithac label,
.form-group.field-chottourform-mkt_da_khaithac label {display:inline-block; width:32%;}</style>