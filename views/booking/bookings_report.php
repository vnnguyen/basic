<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_bookings_inc.php');

$this->title = 'Edit booking';

$currencyList = [
    'USD'=>'USD',
    'EUR'=>'EUR',
    'VND'=>'VND',
];

$form = ActiveForm::begin(); ?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Edit booking data</h6>
        </div>
        <div class="panel-body">
            Case: <?= Html::a($theBooking['case']['name'], '@web/cases/r/'.$theBooking['case']['id']) ?>
            |
            Product: <?= Html::a($theBooking['product']['title'], '@web/products/sb/'.$theBooking['product']['id']) ?>
            |
            Tour: <?= Html::a($theBooking['product']['op_code'], '@web/products/sb/'.$theBooking['product']['id']) ?>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3"><?= $form->field($theReport, 'pax_count')->label('Số pax') ?></div>
                <div class="col-md-3"><?= $form->field($theReport, 'day_count')->label('Số ngày') ?></div>
            </div>
            <div class="row">
                <div class="col-md-3"><?= $form->field($theReport, 'price')->label('Doanh thu') ?></div>
                <div class="col-md-3"><?= $form->field($theReport, 'price_unit')->dropdownList($currencyList, ['promtp'=>'-'])->label('-') ?></div>
                <div class="col-md-3"><?= $form->field($theReport, 'cost')->label('Chi phí') ?></div>
                <div class="col-md-3"><?= $form->field($theReport, 'cost_unit')->dropdownList($currencyList, ['promtp'=>'-'])->label('-') ?></div>
            </div>
            <?= $form->field($theReport, 'note')->textArea(['rows'=>5]) ?>
            <div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
            
        </div>
    </div>
</div>
<? ActiveForm::end();