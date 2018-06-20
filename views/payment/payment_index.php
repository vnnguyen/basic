<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

include('_payments_inc.php');

Yii::$app->params['page_title'] = 'Payments by customers ('.$pagination->totalCount.')';

if (!empty($theBookings)) {
    foreach ($theBookings as $booking) {
        $this->params['actions'][] = [
            ['label'=>'+ Payment', 'link'=>'bookings/r/'.$booking['id']],
        ];
    }
}

?>
<!--div class="alert alert-info">CHÚ Ý: Mới tách thêm 2 loại ct mới là CT tour Hãng và ct tour TCG, mọi người chú ý khi tìm kiếm</div-->
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
            <?= Html::dropdownList('month', $getMonth, ArrayHelper::map($monthList, 'ym', 'ym'), ['class'=>'form-control', 'prompt'=>'All months']) ?>
            <?= Html::dropdownList('method', $getMethod, ArrayHelper::map($methodList, 'method', 'method'), ['class'=>'form-control', 'prompt'=>'All accounts']) ?>
            <?= Html::textInput('tour', $getTour, ['class'=>'form-control', 'placeholder'=>'Tour code or ID']) ?>
            <?= Html::textInput('note', $getNote, ['class'=>'form-control', 'placeholder'=>'Search note']) ?>
            <?= Html::dropdownList('limit', $getLimit, ['25'=>'25 per page', '50'=>'50 per page', '100'=>'100 per page', '500'=>'500 per page'], ['class'=>'form-control']) ?>
            <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
            <?= Html::a('Reset', '@web/payments') ?>
            </form>
        </div>
        <? if (empty($thePayments)) { ?>
        <div class="panel-footer text-danger">No payments found.</div>
        <? } else { ?>
        <div class="table-responsive">
            <table class="table table-striped table-xxs">
                <thead>
                    <tr>
                        <th class="text-center"></th>
                        <th>Date</th>
                        <th>Tour / Booking</th>
                        <th>Invoice</th>
                        <th>Amount</th>
                        <th>VND eq.</th>
                        <th>Method</th>
                        <th>Note</th>
                        <th>Updated</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
    <?
                    $cnt = 0;
                    $total = 0;
                    foreach ($thePayments as $payment) {
                        $vndAmount = $payment['xrate'] * $payment['amount'];
                        $total += $vndAmount;
    ?>
                    <tr>
                        <td class="text-muted text-center"><?= ++$cnt ?></td>
                        <td class="text-nowrap"><?= substr($payment['payment_dt'], 0, 10) ?></td>
                        <td class="text-nowrap">
                            <?= Html::a($payment['booking']['product']['tour']['code'], 'payments?product_id='.$payment['booking']['product']['id']) ?>
                            /
                            <?= Html::a($payment['booking']['id'], '/bookings/r/'.$payment['booking']['id']) ?>
                            <?= $payment['booking']['createdBy']['name'] ?>
                        </td>
                        <td>
                            <?= Html::a($payment['invoice']['ref'], '/invoices/r/'.$payment['invoice']['id']) ?>
                        </td>
                        <td class="text-right text-nowrap">
                            <?= Html::a(number_format($payment['amount'], intval($payment['amount']) == $payment['amount'] ? 0 : 2), 'payments/r/'.$payment['id']) ?>
                            <small class="text-muted"><?= $payment['currency'] ?></small>
                        </td>
                        <td class="text-right text-nowrap">
                            <?= number_format($vndAmount, intval($vndAmount) == $vndAmount ? 0 : 2) ?>
                            <small class="text-muted">VND</small>
                        </td>
                        <td class="text-nowrap"><?= $payment['method'] ?></td>
                        <td><?= $payment['note'] ?></td>
                        <td class="text-nowrap"><?= $payment['createdBy']['name'] ?></td>
                        <td class="text-nowrap">
                            <?= Html::a('<i class="fa fa-edit"></i>', 'payments/u/'.$payment['id'], ['class'=>'text-muted', 'title'=>'Edit']) ?>
                            <?= Html::a('<i class="fa fa-trash-o"></i>', 'payments/d/'.$payment['id'], ['class'=>'text-muted', 'title'=>'Delete']) ?>
                        </td>
                    </tr>
                    <? } ?>
                    <tr>
                        <td colspan="3"></td>
                        <td></td>
                        <td class="text-right text-nowrap">
                            <strong class="text-success"><?= number_format($total) ?></strong>
                            <small class="text-muted">VND</small>
                        </td>
                        <td colspan="4"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <? } // if payments ?>
        <? if ($pagination->totalCount > $pagination->limit) { ?>
        <div class="panel-body text-center">
        <?= LinkPager::widget([
            'pagination' => $pagination,
            'firstPageLabel' => '<<',
            'prevPageLabel' => '<',
            'nextPageLabel' => '>',
            'lastPageLabel' => '>>',
        ]) ?>
        </div>
        <? } // if pagination ?>
    </div>
</div>
