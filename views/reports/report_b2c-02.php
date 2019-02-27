<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$reportList = [
    '1'=>'Tour bán trong năm 2017, chạy kết thúc trong năm 2018',
    '2'=>'Tour bán từ 1/1 dến 12/7/2017, chạy kết thúc trong năm 2018',
    '3'=>'Tour bán từ 1/1 dến 12/7/2018, chạy kết thúc trong năm 2019',
];

Yii::$app->params['page_layout'] = '.s';
Yii::$app->params['page_title'] = $reportList[$report];

?>
<div class="col-md-12">
    <form class="form-inline mb-2">
        <?= Html::dropdownList('report', $report, $reportList, ['class'=>'form-control']) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
    </form>
    <div class="card table-responsive">
        <table class="table table-narrow table-striped">
            <thead>
                <tr>
                    <th width="10"></th>
                    <th class="text-center">Ngày bán</th>
                    <th>Tour code</th>
                    <th class="text-right">Số khách</th>
                    <th class="text-right">Số ngày</th>
                    <th class="text-center">Khởi hành</th>
                    <th class="text-center">Kết thúc</th>
                    <th>Doanh thu</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cnt = 0;
                $totalPax = 0;
                $totalDays = 0;
                $grandTotal = [];
                foreach ($bookings as $booking) {
                    if ($booking['product']) {
                        $cnt ++;
                        $totalPax += $booking['pax'];
                        $totalDays += $booking['product']['day_count'];
                        $total = [];
                        foreach ($booking['invoices'] as $invoice) {
                            if ($invoice['stype'] != 'invoice') {
                                $invoice['amount'] = -$invoice['amount'];
                            }
                            if (!isset($total[$invoice['currency']])) {
                                $total[$invoice['currency']] = $invoice['amount'];
                            } else {
                                $total[$invoice['currency']] += $invoice['amount'];
                            }

                            if (!isset($grandTotal[$invoice['currency']])) {
                                $grandTotal[$invoice['currency']] = $invoice['amount'];
                            } else {
                                $grandTotal[$invoice['currency']] += $invoice['amount'];
                            }
                        }
                        ?>
                <tr>
                    <td class="text-center text-muted"><?= $cnt ?></td>
                    <td class="text-center"><?= date('j/n/Y', strtotime($booking['status_dt'])) ?></td>
                    <td><?= Html::a($booking['product']['op_code'], '/products/op/'.$booking['product']['id']) ?></td>
                    <td class="text-right"><?= $booking['pax'] ?></td>
                    <td class="text-right"><?= $booking['product']['day_count'] ?></td>
                    <td class="text-center"><?= date('j/n/Y', strtotime($booking['product']['day_from'])) ?></td>
                    <td class="text-center"><?= date('j/n/Y', strtotime($booking['product']['tour_end'])) ?></td>
                    <td class="text-right"><?php
                    foreach ($total as $curr=>$subt) {
                        echo '<div>', Html::a(number_format($subt), '/bookings/r/'.$booking['id']), ' ', $curr, '</div>';
                    }
                    ?></td>
                </tr><?php
                    }
                } ?>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="text-right"><?= number_format($totalPax) ?></th>
                    <th class="text-right"><?= number_format($totalDays) ?></th>
                    <th></th>
                    <th></th>
                    <th class="text-right"><?php
                    foreach ($grandTotal as $curr=>$subt) {
                        echo '<div>', number_format($subt), ' ', $curr, '</div>';
                    }
                    ?></th>
                </tr>
            </tbody>
        </table>
    </div>
</div>