<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = 'Lịch thanh toán tour năm '.$year;

$this->params['icon'] = 'area-chart';

$this->params['breadcrumb'] = [
    ['Manager', '@web/manager'],
    ['Reports', '@web/reports'],
];

$rate['EUR'] = (float)$eur;
$rate['USD'] = (float)$usd;
$rate['VND'] = 1;

$nhothuOKList = [
    ''=>'Nhờ thu và không',
    'no'=>'Không nhờ thu',
    'yes'=>'Có nhờ thu',
];
foreach ($nhothuList as $item) {
    if ($item['nho_thu'] != '') {
        $nhothuOKList[$item['nho_thu']] = '- '.$item['nho_thu'];
    }
}

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="alert alert-info">
                <strong>NOTE:</strong> Click các dòng tổng (màu xanh / đỏ) để xem chi tiết. Click thêm lần nữa để quay lại.
                Tỉ giá 1 EUR = <?= $rate['EUR'] ?> VND, 1 USD = <?= $rate['USD'] ?> VND (thay đổi được trong 2 ô dưới đây)
            </div>
            <form class="form-inline">
                <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>'Năm']) ?>
                <?= Html::dropdownList('method', $method, ArrayHelper::map($methodList, 'method', 'method'), ['class'=>'form-control', 'prompt'=>'Phương thức tt']) ?>
                <?= Html::dropdownList('nhothu', $nhothu, $nhothuOKList, ['class'=>'form-control']) ?>
                <?= Html::textInput('eur', $eur, ['class'=>'form-control', 'placeholder'=>'Tỉ giá EUR']) ?>
                <?= Html::textInput('usd', $usd, ['class'=>'form-control', 'placeholder'=>'Tỉ giá USD']) ?>
                <?//= Html::dropdownList('seller', $seller, ArrayHelper::map($sellerList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'Seller']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
                <?= Html::a('Reset', '@web/reports/lichtttour') ?>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-condensed table-bordered">
                <tbody>
<?
// \fCore::expose($result); exit;
$cnt = 0;
$currentDate = '';
foreach ($result as $date=>$ids) {
    $cnt ++;
    if ($date != $currentDate) {
        $currentDate = $date;
        $text = [];
        $toVnd = 0;
        if ($currentDate == 'overdue') {
?>
                <tr class="week-heading" data-id="<?= $currentDate ?>">
                    <td class="text-danger">
                        <strong>OVERDUE
<?
        } else {
            if (isset($result['overdue']) || $cnt != 1) {
?>
                            </tbody>
                        </table>
                    </td>
                </tr>
<?
            }
?>
                <tr class="week-heading" data-id="<?= $currentDate ?>">
                    <td class="text-success">
                        <strong>Tuần từ <?= date('j/n/Y', strtotime($currentDate)) ?> đến <?= date('j/n/Y', strtotime('+6 days', strtotime($currentDate))) ?></strong>
                    </td>
                    <td>
<?
        }
        if (isset($total[$currentDate])) {
            foreach ($total[$currentDate] as $currency=>$number) {
                $text[] = number_format($number, 2).' <span class="text-muted">'.$currency.'</span>';
                $toVnd += $rate[$currency] * $number;
            }
            if ($toVnd != 0) {
                echo implode(' + ', $text);
            }
        }
?>
                    </td>
                    <td class="text-right text-nowrap text-<?= $currentDate == 'overdue' ? 'danger' : 'success' ?>">
                        <?= number_format($toVnd, 0) ?>
                        <span class="text-muted">VND</span>
                    </td>
                </tr>
                <tr class="week-under-heading-<?= $currentDate ?>" style="display:none;">
                    <td colspan="3">
                        <table class="table table-xxs table-bordered">
                            <thead>
                                <tr>
                                    <th width="10" class="text-nowrap">Hạn trả</th>
                                    <th width="10" class="text-nowrap">Tour</th>
                                    <th width="10" class="text-nowrap">Tổng hoá đơn</th>
                                    <th xwidth="10" class="text-nowrap">Tên người trả</th>
                                    <th width="10" class="text-nowrap">Phương thức</th>
                                    <th width="10" class="text-nowrap">Cổng tt</th>
                                    <th width="10" class="text-nowrap">Thu / tỉ giá</th>
                                    <th width="10" class="text-nowrap">Nhờ thu</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
<?

    }
    foreach ($ids as $id) {
?>
                <tr>
                    <td class="text-nowrap"><?= substr($theInvoices[$id]['due_dt'], 0, 10) ?></td>
                    <td class="text-nowrap">
<?
    if($theInvoices[$id]['booking']) {
        if ($theInvoices[$id]['booking']['finish'] == 'canceled') {
            echo '<span class="text-danger">(CXL)</span> ';
        }
        echo Html::a($theInvoices[$id]['booking']['product']['op_code'], '@web/bookings/r/'.$theInvoices[$id]['booking']['id']);
        echo ' - ';
        echo Html::a($theInvoices[$id]['booking']['product']['op_name'], '@web/bookings/r/'.$theInvoices[$id]['booking']['id']);
    }
?>
                    </td>
                    <td class="text-right text-nowrap">
                        <? if ($theInvoices[$id]['stype'] == 'credit') { ?>
                        <?= Html::a('-'.number_format($theInvoices[$id]['amount'], 2), '@web/invoices/r/'.$id, ['class'=>'text-danger']) ?>
                        <? } else { ?>
                        <?= Html::a(number_format($theInvoices[$id]['amount'], 2), '@web/invoices/r/'.$id) ?>
                        <? } ?>
                        <span class="text-muted"><?= $theInvoices[$id]['currency'] ?></span>
                    </td>
                    <td class="-text-nowrap"><?= $theInvoices[$id]['bill_to_name'] ?></td>
                    <td class="text-nowrap"><?= $theInvoices[$id]['method'] ?></td>
                    <td class="text-nowrap"><?= $theInvoices[$id]['gw_name'] ?></td>
                    <td class="text-nowrap text-right">
                        <? if ($theInvoices[$id]['gw_currency'] != $theInvoices[$id]['currency']) { ?>
                        &times; <?= $theInvoices[$id]['gw_xrate'] ?> = <?= $theInvoices[$id]['gw_currency'] ?>
                        <? } ?>
                    </td>
                    <td class="text-nowrap"><?= $theInvoices[$id]['nho_thu'] ?></td>
                    <td><?= $theInvoices[$id]['note'] ?></td>
                </tr>
<?
    } // foreach ids
} // foreach result
?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
tr.week-heading {cursor:pointer;}
</style>
<?
$js = <<<'TXT'
$('tr.week-heading').click(function(){
    $('tr.week-heading').toggle();
    $(this).show();
    var id = $(this).data('id');
    $('tr.week-under-heading-' + id).toggle();
});
TXT;

$this->registerJs($js);