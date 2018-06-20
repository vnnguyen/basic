<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_payments_inc.php');

$this->title = 'Payment detail';

$this->params['breadcrumb'][] = ['View', '@web/payments/r/'.$thePayment['id']];

?>

<div class="col-md-8">
    <div class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-condensed">
                <tbody>
                    <tr><td><strong>Payment ID</strong></td><td><?= $thePayment['id'] ?> / <strong>Ref No</strong> <?= $thePayment['ref'] ?> <strong>Invoice</strong> <?= $thePayment['invoice']['ref'] ?></td></tr>
                    <tr><td><strong>Booking ID</strong></td><td><?= Html::a($thePayment['booking']['id'], '@web/bookings/r/'.$thePayment['booking']['id']) ?></td></tr>
                    <tr><td><strong>Tour code</strong></td><td>
                        <?= Html::a($thePayment['booking']['product']['tour']['code'].' - '.$thePayment['booking']['product']['tour']['name'], '@web/tours/r/'.$thePayment['booking']['product']['tour']['id']) ?>
                        (<?= Html::a('View all payments for this tour', '@web/payments?product_id='.$thePayment['booking']['product']['id'], ['class'=>'text-muted']) ?>)
                        </td></tr>
                    <tr><td><strong>Tour itinerary</strong></td><td><?= Html::a($thePayment['booking']['product']['title'], '@web/products/r/'.$thePayment['booking']['product']['id']) ?></td></tr>
                    <tr><td><strong>Time</strong></td><td><?= $thePayment['payment_dt'] ?> (Hanoi time)</td></tr>
                    <tr><td><strong>Payment by</strong></td><td><?= $thePayment['payer'] ?></td></tr>
                    <tr><td><strong>Payment to</strong></td><td><?= $thePayment['payee'] ?></td></tr>
                    <tr><td><strong>Method/Account</strong></td><td><?= $thePayment['method'] ?></td></tr>
                    <tr><td><strong>Amount</strong></td>
                        <td>
                            <strong class="text-success"><?= number_format($thePayment['amount'], 2) ?></strong> <?= $thePayment['currency'] ?>
                            <? if ($thePayment['currency'] != 'VND') { ?>
                            eq. to <br>
                            <strong class="text-danger"><?= number_format($thePayment['xrate'] * $thePayment['amount'], 2) ?></strong> VND
                            <? } ?>
                        </td>
                    </tr>
                    <tr><td><strong>Exchange rate</strong></td><td><?= $thePayment['xrate'] ?></td></tr>
                    <tr><td><strong>Note</strong></td><td><?= nl2br($thePayment['note']) ?></td></tr>
                    <tr><td><strong>Updated by</strong></td><td><?= $thePayment['updatedBy']['name'] ?> <?= Yii::$app->formatter->asRelativetime($thePayment['updated_at']) ?> (UTC)</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
