<?
use yii\helpers\Html;

app\assets\DatetimePickerAsset::register($this);

$theTotal = 0;
foreach ($total as $payable=>$amount) {
    $theTotal += $amount;
}

$chitiet = \Yii::$app->request->get('chitiet');
Yii::$app->params['body_class'] = 'bg-white';
Yii::$app->params['page_icon'] = 'money';
Yii::$app->params['page_title'] = 'Lịch thanh toán chi phí tour '.date('j/n', strtotime($day1)).' - '.date('j/n', strtotime($day2)).' | '.number_format($theTotal).' VND';
Yii::$app->params['page_breadcrumbs'] = [
    ['Chi phí tour', 'cpt'],
    ['Lịch thanh toán', 'cpt/lich-thanh-toan'],
];
?>

<div class="col-md-12">
    <form class="form-inline mb-20">
        Rate 1 USD = <?= number_format($xRates['USD'], 0) ?> VND | Chọn khoảng ngày
        <?= Html::textInput('days', $days, ['class'=>'form-control days']) ?>
        <?= Html::dropdownList('chitiet', $chitiet, ['yes'=>'Diễn giải từng mục', 'no'=>'Chỉ hiện tổng tiền'], ['class'=>'form-control']) ?>
        <?= Html::dropdownList('c3', $c3, ['off'=>'Chưa TT', 'on'=>'Đã TT'], ['class'=>'form-control']) ?>
        <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Reset'), '?') ?>
    </form>
    <p><strong>XEM THEO NGÀY</strong></p>
    <div class="table-responsive">
        <table class="table table-xxs table-striped table-bordered">
            <thead>
                <tr>
                <th width="10%">Thời hạn</th>
                <th width="10%">Tour code</th>
                <th width="35%">Nội dung</th>
                <th width="10%">Ngày dịch vụ</th>
                <th width="15%">Ai trả</th>
                <th width="10%">Phải TT</th>
                <th width="10%">Đã TT</th>
                </tr>
            </thead>
            <tbody>
            <?
            $dow = '';
            $due = '';
            $name = '';
            $venue = '';
            foreach ($theCptx as $cpt) {
                if ($due != $cpt['due'] || $name != $cpt['dvtour_name'] || $venue != $cpt['venue_id']) {
                    $checkoutDate = '';
                    if ($cpt['venue_id']) {
                        $sql = 'SELECT dvtour_day FROM cpt WHERE tour_id=:tour AND dvtour_name=:name AND venue_id=:venue AND dvtour_day!=:day ORDER BY dvtour_day DESC LIMIT 1';
                        $checkoutDate = Yii::$app->db->createCommand($sql, [
                            ':tour'=>$cpt['tour_id'],
                            ':name'=>$cpt['dvtour_name'],
                            ':venue'=>$cpt['venue_id'],
                            ':day'=>$cpt['dvtour_day'],
                            ])->queryScalar();
                    }
                }
            ?>
                <tr>
                    <td class="text-nowrap"><?= $dow != $cpt['due'] ? Yii::$app->formatter->asDate($cpt['due'], 'php:l, j/n') : '' ?></td>
                    <td class="text-nowrap">
                        <?=$cpt['tour_status'] == 'deleted' || $cpt['tour_status'] == 'canceled' ? '<span style="background:red; color:#ffc;">CXL</span> ' : ''?><?= Html::a($cpt['code'], '@web/tours/r/'.$cpt['tour_id'] )?>
                        <?
                        $invoiceStatus = false;
                        foreach ($theTours as $tour) {
                            if ($tour['id'] == $cpt['tour_id']) {
                                foreach ($tour['product']['bookings'] as $booking) {
                                    foreach ($booking['invoices'] as $invoice) {
                                        if ($invoice['status'] == 'active' && $invoice['payment_status'] == 'paid') {
                                            $invoiceStatus = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        if ($invoiceStatus) {
                            echo '<i title="Khách đã thanh toán" class="text-success fa fa-dollar"></i>';
                        }
                        ?>                  
                    </td>
                    <td><?= Html::a($cpt['dvtour_name'], '@web/cpt/r/'.$cpt['dvtour_id']) ?> (<?= $cpt['venue_id'] == 0 ? $cpt['oppr'] : Html::a($cpt['venue_name'], '/venues/r/'.$cpt['venue_id'], ['target'=>'_blank']) ?>)</td>
                    <td class="text-center">
                        <?= date('j/n/Y', strtotime($cpt['dvtour_day'])) ?>
                        <?= $checkoutDate == '' ? '' : ' - '.date('j/n/Y', strtotime($checkoutDate)) ?>
                    </td>
                    <td><?= $cpt['payer']?></td>
                    <td class="text-right text-nowrap"><?= number_format($cpt['qty'] * $cpt['price']) ?> <span class="text-muted"><?= $cpt['unitc'] ?></span></td>
<?
                    $sum = 0;
                    $ltt = 0;
                    $cmt = [];
                    foreach ($theMttx as $mtt) {
                        if ($mtt['cpt_id'] == $cpt['dvtour_id']) {
                            $ltt ++;
                            $sum += $mtt['amount'] * $mtt['xrate'];
                            $cmt[] = number_format($mtt['amount'] * $mtt['xrate']).' '.$mtt['currency'].' @'.\app\helpers\DateTimeHelper::convert($mtt['payment_dt'], 'j/n/Y H:i');
                        }
                    }
?>
                    <td class="text-right text-nowrap" title="<?= implode(chr(10), $cmt) ?>">
                    <?
                    if ($ltt > 1) {
                        echo '<span class="text-muted">('.$ltt.')</span> ';
                    }
                    if ($sum != 0) {
                        echo number_format($sum) .' <span class="text-muted">VND</span>';
                    }
                    // if ($cpt['paid_full'] == 'yes') {
                    //     echo '<span class="text-success">PAID</span>';
                    // }
                    // echo $cpt['c3'];
                    // $text = '';
                    // if (substr($cpt['c5'], 0, 2) == 'on') {
                    //     $text = 'một phần';
                    // }
                    // if (substr($cpt['c7'], 0, 2) == 'on') {
                    //     $text = 'toàn bộ';
                    // }
                    // echo $text;
                    ?></td>
                </tr>
            <?
                if ($dow != $cpt['due']) {
                    $dow = $cpt['due'];
                }
                $due = $cpt['due'];
                $name = $cpt['dvtour_name'];
                $venue = $cpt['venue_id'];
                // HUAN }
            }
            ?>
            </tbody>
        </table>
    </div>
    <hr>
    <p><strong>XEM THEO NHÀ CUNG CẤP</strong></p>
    <div class="table-responsive">
        <table class="table table-condensed table-striped table-bordered">
            <thead>
                <tr>
                    <th width="100">Thanh toán cho</th>
                    <th width="100">Tổng tiền</th>
                    <? if ($chitiet != 'no') { ?>
                    <th width="50">Tour</th>
                    <th width="100">Dịch vụ</th>
                    <th width="100">Đơn giá</th>
                    <th width="50">SL</th>
                    <th width="150">Tổng</th>
                    <? } ?>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <? foreach ($result as $payableto=>$items) { ?>
                <? $cnt = 0 ?>
                <? foreach ($items as $item) { ?>
                    <? $cnt ++ ?>
                    <? if ($chitiet != 'no' || $cnt == 1) { ?>
                <tr>
                    <td class="text-nowrap"><?= $cnt == 1 ? $payableto : '' ?></td>
                    <td class="text-right text-nowrap"><?= $cnt == 1 ? number_format($total[$payableto], 0).' VND' : '' ?></td>
                    <? if ($chitiet != 'no') { ?>
                    <td><?= Html::a($item['tour_code'], '@web/cpt?view=tour-code&tour='.$item['tour_code']) ?></td>
                    <td class="text-nowrap"><?= Html::a($item['name'], '@web/cpt/r/'.$item['id']) ?></td>
                    <td class="text-right text-nowrap"><?= number_format($item['quantity'] * $item['price'], 2) ?> <span class="text-muted"><?= $item['currency'] ?></span></td>
                    <td class="text-nowrap">&times; <?= $item['quantity'] ?> <?= $item['unit'] ?></td>
                    <td class="text-right text-nowrap"><?= number_format($item['total'], 0) ?> <span class="text-muted">VND</span></td>
                    <? } ?>
                    <td></td>
                </tr>
                    <? } ?>
                <? } ?>
            <? } ?>
            </tbody>
        </table>
    </div>
</div>
<style type="text/css">
.datepicker>div {display:block;}
</style>
<?

$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/css/datepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.en.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.fr.min.js', ['depends'=>'yii\web\JqueryAsset']);
// $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.vi.min.js', ['depends'=>'yii\web\JqueryAsset']);

$js = <<<'TXT'
$.fn.datepicker.language['vi'] = {
    days: ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'],
    daysShort: ['Nhật', 'Hai', 'Ba', 'Tư', 'Năm', 'Sáu', 'Bảy'],
    daysMin: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
    months: ['Tháng một','Tháng hai','Tháng ba','Tháng tư','Tháng năm','Tháng sáu', 'Tháng bảy','Tháng tám','Tháng chín','Tháng mười','Tháng mười một','Tháng mười hai'],
    monthsShort: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
    today: 'Hôm nay',
    clear: 'Xoá',
    dateFormat: 'dd/mm/yyyy',
    timeFormat: 'hh:ii',
    firstDay: 0
};

$('.days').datepicker({
    dateFormat: 'yyyy-mm-dd',
    language: '{$lang}',
    todayButton: true,
    range: true,
    multipleDatesSeparator: ' - '
});
TXT;
$this->registerJs(str_replace(['{$lang}'], [\Yii::$app->language], $js));