<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_dv_inc.php');

Yii::$app->params['page_title'] = 'Test form DV/CP';

$muaList = [
    'tructiep'=>'Trực tiếp',
    'giantiep'=>'Qua phân phối',
];

$yesNoList = [
    'yes'=>'Có',
    'no'=>'Không',
];

$tienList = [
    'USD'=>'USD',
    'VND'=>'VND',
];

$form = ActiveForm::begin();
?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Dịch vụ / Chi phí</h6>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4"><?= $form->field($theForm, 'mua')->dropdownList($muaList)->label('Mua qua') ?></div>
                <div class="col-md-4"><?= $form->field($theForm, 'mua_cty')->label('Mua qua công ty') ?></div>
                <div class="col-md-4"><?= $form->field($theForm, 'mua_hd')->label('Số hợp đồng') ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?= $form->field($theForm, 'gia')->label('Giá') ?></div>
                <div class="col-md-4"><?= $form->field($theForm, 'tien')->dropdownList($tienList)->label('Tiền') ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?= $form->field($theForm, 'dat')->dropdownList($yesNoList)->label('Cần đặt trước') ?></div>
                <div class="col-md-4"><?= $form->field($theForm, 'dat_dk')->label('Điều kiện đặt trước') ?></div>
                <div class="col-md-4"><?= $form->field($theForm, 'dat_qua')->label('Đặt trước qua (g/d/p)') ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?= $form->field($theForm, 'tra_dk')->label('Điều kiện trả') ?></div>
                <div class="col-md-4"><?= $form->field($theForm, 'tra_qua')->label('Trả tiền qua (g/d/p)') ?></div>
            </div>
            <div class="text-right"><?=Html::submitButton('Save changes', ['class' => 'btn btn-primary']); ?></div>
        </div>
    </div>
</div>
<? ActiveForm::end();

$js = <<<'TXT'

TXT;
