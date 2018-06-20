<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_bookings_inc.php');

Yii::$app->params['page_title'] = 'Confirm booking';
Yii::$app->params['page_breadcrumbs'][] = ['Confirm booking'];

?>
<div class="col-md-8">
    <div class="alert alert-info">
        <strong>Congrats! You're about to confirm this booking.</strong>
        <br />Bạn có thể sửa những thông tin cần thiết dưới đây. Sau đó nhấn Submit để:
        <br />- Booking sẽ chuyển sang trạng thái <b>WON (CONFIRMED)</b>
        <br />- Một tour mới sẽ được mở dựa trên chương trình này
        <br />- Bộ phận điều hành sẽ được thông báo tự động về yêu cầu xác nhận tour mói của bạn
        <br />- Bạn sẽ chuyển đến trang Tour mới để note các thông tin cần thiết
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Confirm booking</h6>
        </div>
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th>Case: <?= Html::a($theBooking['case']['name'], 'cases/r/'.$theBooking['case']['id']) ?></td>
                    <th>Tour program: <?= Html::a($theBooking['product']['title'], 'products/sb/'.$theBooking['product']['id']) ?></td>
                </tr>
            </table>
        </div>
        <div class="panel-body">
            <? $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-3"><?= $form->field($theBooking, 'pax') ?></div>
                <div class="col-md-3"><?= $form->field($theBooking, 'price') ?></div>
                <div class="col-md-3"><?= $form->field($theBooking, 'currency') ?></div>
            </div>
            <div class="alert alert-info">
                NOTE: As of Sept 8, 2016 seller will determine the new tour's code and name at time of booking confirmation.
                <br>CHÚ Ý: Kể từ 8/9/2016 người bán hàng sẽ là người quyết định code và tên tour tại thời điểm xác nhận booking.
            </div>
            <div class="row">
                <div class="col-md-3"><?= $form->field($theBooking, 'tourCode')->label('Suggested code') ?></div>
                <div class="col-md-6"><?= $form->field($theBooking, 'tourName')->label('Suggested name of new tour') ?></div>
                <? if ($theBooking['case']['is_b2b'] == 'yes') { ?>
                <div class="col-md-3"><?= $form->field($theBooking, 'clientRef')->label('Client code') ?></div>
                <? } ?>
            </div>
            <?= $form->field($theBooking, 'note')->textArea(['rows'=>5]) ?>
            <div>
                <?= Html::submitButton(Yii::t('app', 'Submit'), ['class'=>'btn btn-primary']) ?>
                <?= Yii::t('app', 'or') ?> <?= Html::a(Yii::t('b', 'Cancel'), '/cases/r/'.$theBooking['case']['id']) ?>
            </div>

            <? ActiveForm::end(); ?>  
        </div>
    </div>
</div>
