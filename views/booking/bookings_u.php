<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_bookings_inc.php');

$this->title = 'Edit booking';

?>
<? $form = ActiveForm::begin(); ?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr><td>Product: <?= Html::a($theBooking['product']['title'], '/products/r/'.$theBooking['product']['id']) ?></td></tr>
                <tr><td>Case: <?= Html::a($theBooking['case']['name'], '/cases/r/'.$theBooking['case']['id']) ?></td></tr>
            </table>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3"><?= $form->field($theBooking, 'pax') ?></div>
                <div class="col-md-3"><?= $form->field($theBooking, 'price') ?></div>
                <div class="col-md-3"><?= $form->field($theBooking, 'currency') ?></div>
            </div>
            <?= $form->field($theBooking, 'note')->textArea(['rows'=>5]) ?>
            <div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
        </div>
    </div>
</div>
<? ActiveForm::end();