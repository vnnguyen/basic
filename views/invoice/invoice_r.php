<?php

use yii\helpers\Html;

$total = 0;
$option = 0;
include('_invoice_inc.php');

if (!function_exists('trim00')) {
    function trim00($str)
    {
        return rtrim(rtrim($str, '0'), '.');
    }
}


Yii::$app->params['page_layout'] = '-t';
Yii::$app->params['body_class'] = 'bg-white sidebar-xs';
Yii::$app->params['page_title'] = 'Invoice #'.str_pad($theInvoice['id'], '6', '0', STR_PAD_LEFT).' ('.$theInvoice['ref'].')';

$lang = 'fr';

?>
<div class="col-md-8">
    <div class="row">
        <div class="col-sm-8">
            <img height="100" src="<?= Yii::$app->params['print_logo'] ?>">
        </div>
        <div class="col-sm-4">
            <h1 style="color:#BD3920; margin:0"><strong>
                <?php if ($theInvoice['stype'] == 'invoice') { ?>
                <?= Yii::t('invoice', 'INVOICE', null, $lang) ?>
                <?php } elseif ($theInvoice['stype'] == 'credit') { ?>
                <?= Yii::t('invoice', 'CREDIT/REFUND') ?>
                <?php } ?>
            </strong></h1>
            <div class="fs-x90pc">
                <br><strong><?= Yii::t('invoice', 'Ref') ?>:</strong> <?= $theInvoice['ref'] ?>
                <br><strong><?= Yii::t('invoice', 'Date') ?>:</strong> <?= date('d-m-Y') ?>
            </div>
        </div>
    </div>
    <hr>
    <!--
    <table class="table table-condensed nb">
        <tbody>
            <tr>
                <?php if ($theInvoice['brand'] == 'at') { ?>
                <td width="400">
                </td>
                <?php } elseif ($theInvoice['brand'] == 'si') { ?>
                <td width="170">
                    <img id="logo" height="100" src="/assets/img/logo_si_v_261212.jpg" alt="Secret Indochina logo">
                    <!-- <img id="logo" height="110" width="152" src="data:image/jpeg;base64,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" alt="Secret Indochina logo"> -->
                </td>
                <?php } ?>
                <td class="text-nowrap">
                    <?php if ($theInvoice['brand'] == 'atx') { ?>
                    <strong>AMICA TRAVEL</strong>
                    <div class="fs-90pc">
                    <?= Yii::t('invoice', '3rd Floor, Nikko Building') ?>
                    <br><?= Yii::t('invoice', '27 Nguyen Truong To str, Hanoi, Vietnam') ?>
                    <br>[t] +84 24 6273 4455
                    <br>[w] www.amica-travel.com
                    <br>[e] info@amica-travel.com
                    </div>
                    <?php } ?>
                    <?php if ($theInvoice['brand'] == 'si') { ?>
                    <strong>AMICA JSC.</strong>
                    <div class="fs-90pc">
                    <?= Yii::t('invoice', '6th Floor, Nikko Building') ?>
                    <br><?= Yii::t('invoice', '27 Nguyen Truong To str, Hanoi, Vietnam') ?>
                    <br>[t] +84 24 3266 9052
                    <br>[w] www.secretindochina.com
                    <br>[e] contact@secretindochina.com
                    </div>
                    <?php } ?>
                </td>

            </tr>

        </tbody>
    </table>
    -->

    <table class="table table-condensed nb">
        <tbody>
            <tr>
                <td width="15%" class="text-right" style="vertical-align:top"><strong><?= Yii::t('invoice', 'Bill to') ?>:</strong></td>
                <td width="40%" style="vertical-align:top"><?= $theInvoice['bill_to_name'] ?><br><?= nl2br(Html::encode($theInvoice['bill_to_address'])) ?></td>
                <td width="25%" class="text-right" style="vertical-align:top">
                    <strong><?= Yii::t('invoice', 'Due date') ?>:</strong>
                </td>
                <td width="20%" style="vertical-align:top">
                    <?= date('d-m-Y', strtotime($theInvoice['due_dt'])) ?>
<?php if ($theInvoice['status'] != 'active') { ?>
                    <div style="color:<?= $theInvoice['status'] == 'canceled' ? '#c00' : '#999' ?>; font-size:18px;"><?= strtoupper($theInvoice['status']) ?></div>
<?php } else { ?>
    <?php if ($theInvoice['payment_status'] == 'paid') { ?>
                    <div style="color:green; font-size:18px;"><?= Yii::t('invoice', 'PAID') ?></div>
    <?php } else { ?>
        <?php if (strtotime($theInvoice['due_dt']) < date('Y-m-d 23:59:59')) { ?>
                    <div style="color:red; font-size:18px;"><?= Yii::t('invoice', 'OVERDUE') ?></div>
        <?php } ?>
    <?php } ?>
<?php } // status ?>
                </td>
            </tr>
        </tbody>
    </table>
    
    <table class="table table-bordered" id="pricetable">
        <thead>
            <tr>
                <th><?= Yii::t('invoice', 'Service & Description') ?></th>
                <th width="80"><?= Yii::t('invoice', 'Price') ?></th>
                <th width="20"><?= Yii::t('invoice', 'Qty') ?></th>
                <th width="160"><?= Yii::t('invoice', 'Total') ?></th>
            </tr>
        </thead>
        <tbody>
<?php
            $total = 0;
            $lines = explode(PHP_EOL, $theInvoice['body']);
            foreach ($lines as $line) {
                $line = trim($line);
                $parts = explode('|', $line);
                if (isset($parts[2])) {
                    $value = (float)$parts[1] * (float)$parts[2];
                    $total += $value;
?>
            <tr>
                <td><?= trim($parts[0]) ?></td>
                <td class="text-right text-nowrap"><?= trim00(number_format((float)$parts[1], 2)) ?> <?= $theInvoice['currency'] ?></td>
                <td class="text-right text-nowrap">&times;<?= $parts[2] ?></td>
                <td class="text-right text-nowrap"><?= trim00(number_format($value, 2)) ?> <?= $theInvoice['currency'] ?></td>
            </tr>
<?php
                }
            }

            if ($theInvoice['body2'] != '') {
?>
            <tr>
                <td colspan="3" class="text-right br-0 bb-0 bl-0"><?= Yii::t('invoice', 'SUB TOTAL') ?></td>
                <td class="text-right text-nowrap"><?= trim00(number_format($total, 2)) ?> <?= $theInvoice['currency'] ?></td>
            </tr>
<?php
            } // if body2

            if ($theInvoice['body2'] != '') {
                $lines = explode(PHP_EOL, $theInvoice['body2']);
                foreach ($lines as $line) {
                    $line = trim($line);
                    $parts = explode('|', $line);
                    if (isset($parts[0]) && count($parts) == 2 && trim($parts[0]) != '') {
                        if (strpos($parts[1], '%') !== false) {
                            $value = 0.01 * $total * (float)$parts[1];
                            $parts[0] = trim($parts[0]).' '.trim($parts[1]);
                        } else {
                            $value = (float)$parts[1];
                        }
                        $total += $value;
?>
            <tr>
                <td colspan="3" class="nb text-right"><?= trim($parts[0]) ?></td>
                <td class="text-right text-nowrap"><?= trim00(number_format($value, 2)) ?> <?= $theInvoice['currency'] ?></td>
            </tr>
<?php
                    }
                }
            }
?>

            <?php

            if ($theInvoice['gw_currency'] != $theInvoice['currency'] || $theInvoice['body3'] != '') {
?>
            <tr>
                <td colspan="3" class="bt-0 bl-0 bb-0 text-right"><?= Yii::t('invoice', 'TOTAL') ?> (<?= $theInvoice['currency'] ?>)</td>
                <td class="text-right text-nowrap bg-success"><strong><?= trim00(number_format($total, 2)) ?></strong> <?= $theInvoice['currency'] ?></td>
            </tr>
<?php
            }?>


            <tr><td colspan="4" class="nb text-center" style="padding:4px;">Choisissez votre option de paiement</td></tr>
            
            <?php if ($theInvoice['booking']['case']['is_b2b'] == 'no' && $theInvoice['method'] != 'cash') {

                $op2_total = ($theInvoice['gw_currency'] != $theInvoice['currency'])?$theInvoice['amount'] * (float)$theInvoice['gw_xrate']: $theInvoice['amount'];
                $deduction = $op2_total * 2/100;
                $value = $op2_total - $deduction;
                echo '<tr style="border-bottom: 1px solid #000"><td colspan="4" class="nb text-center" style="padding:4px;"><strong>'.Yii::t('invoice', 'Option ').++$option. ': '.Yii::t('invoice', 'Method of payment').': '.$methodList['cash'].'</strong></td></tr>';
                //$value = $value * (float)$theInvoice['gw_xrate'];
            ?>
            <tr>
                <td colspan="3" class="bt-0 bb-0 bl-0 text-right text-nowrap"><?= Yii::t('invoice', 'Déduction'). ' 2%' ?></td>
                <td class="text-right text-nowrap"><strong><span class="deduction_num"><?= trim00(number_format($deduction, 2)) ?> <?= $theInvoice['gw_currency'] ?></span></strong></td>
            </tr>
            <tr>
                <td class="nb text-right"><?= Yii::t('invoice', 'Paiement en')?></td>
                <td class="nb text-right">
                    <select class="stype_curency">
                        <option value="vnd">VND</option>
                        <option value="usd">USD</option>
                        <option value="eur">EUR</option>
                    </select>
                </td>
                <td  class="bt-0 bb-0 bl-0 text-right text-nowrap"><?= Yii::t('invoice', 'Taux de change')?></td>
                <td class="text-right text-nowrap"><?= Html::textInput('xrate', 0, ['class'=>'form-control', 'type'=>'number', 'data-amount'=>$theInvoice['amount']])?></td>
            </tr>
            <tr><td colspan="4" class="nb" style="padding:4px;"></td></tr>
            <tr>
                <td colspan="3" class="nb text-right"><strong><?= Yii::t('invoice', 'TOTAL DUE') ?></strong></td>
                <td class="text-right text-nowrap bg-success currency_number"><strong><span class="total_num"><?= trim00(number_format($value, 2)) ?> <?= $theInvoice['gw_currency'] ?></span></strong></td>
            </tr>
            <?   } ?>

            <tr><td colspan="4" class="nb" style="padding:4px;"></td></tr>

            <?php
            if ($theInvoice['body3'] != '') {
                 echo '<tr style="border-bottom: 1px solid #000"><td colspan="4" class="nb text-center" style="padding:4px;"><strong>'.Yii::t('invoice', 'Option ').++$option. ': '.Yii::t('invoice', 'Method of payment').': '.$methodList[$theInvoice['method']].'</strong></td></tr>';
            ?>

            <?php
                $cntt = 0;
                $lines = explode(PHP_EOL, $theInvoice['body3']);
                foreach ($lines as $line) {
                    $line = trim($line);
                    $parts = explode('|', $line);
                    if (isset($parts[0]) && count($parts) == 2 && trim($parts[0]) != '') {
                        if (strpos($parts[1], '%') !== false) {
                            $value = 0.01 * $total * (float)$parts[1];
                            $parts[0] = trim($parts[0]).' '.trim($parts[1]);
                        } else {
                            $value = (float)$parts[1];
                        }
                        $cntt ++;
                        $total += $value;
?>

            <tr>
                <td class="nb text-right"><?= Yii::t('invoice', 'Paiement en')?></td>
                <td class="nb text-right">
                    <?= Html::dropDownList('currency_select', strtolower($theInvoice['currency']), ['vnd' => 'VND', 'usd' => 'USD', 'eur' => 'EUR'], ['class' => 'stype_curency_credit']) ?>
                </td>
                <td  class="bt-0 bb-0 bl-0 text-right text-nowrap"><?= Yii::t('invoice', 'Taux de change')?></td>
                <td class="text-right text-nowrap"><?= Html::textInput('xrate_credit', 1, ['class'=>'form-control', 'type'=>'number', 'data-amount'=>$theInvoice['amount'], 'data-xrate' => trim($parts[0])])?></td>
            </tr>


            <tr>
                <td class="nb text-right">
<?php
                        if ($cntt == 1) {
                            if ($theInvoice['gw_name'] != '') {
                                echo ' (via ', $theInvoice['gw_name'], ')';
                            }
                        }
?>
                </td>
                <td colspan="2" class="bt-0 bb-0 bl-0 text-right text-nowrap"><?= trim($parts[0]) ?></td>
                <td class="text-right text-nowrap"><strong><span class="bancaires_num"><?= trim00(number_format($value, 2)) ?> <?= $theInvoice['currency'] ?></span></strong> </td>
            </tr>
<?php
                    }
                }
            }
?>
            <tr><td colspan="4" class="nb" style="padding:4px;"></td></tr>
            <tr>
                <td colspan="3" class="nb text-right"><strong><?= Yii::t('invoice', 'TOTAL DUE') ?></strong></td>
                <td class="text-right text-nowrap bg-success"><strong><span class="total_num_credit"><?= trim00(number_format($total, 2)) ?> <?= $theInvoice['currency'] ?></span></strong> </td>
            </tr>
            
        </tbody>
    </table>

    <?php if ($theInvoice['link'] != '') { ?>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>
                    <strong><?= Yii::t('invoice', 'Link to payment page') ?></strong>
                    <br><?= Html::a($theInvoice['link'], $theInvoice['link'], ['style'=>'border-bottom:1px solid #ccc', 'rel'=>'external']) ?>
                    <i class="fa fa-external-link"></i>
                </td>
            </tr>
        </tbody>
    </table>
    <?php } ?>

    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>
                    <p><strong><?= Yii::t('invoice', 'Note') ?></strong>: <?= Yii::t('invoice', 'You are responsible for all bank fees') ?></p>
                    <?php if ($theInvoice['note_invoice'] != '') { ?>
                    <p><?= nl2br(Html::encode($theInvoice['note_invoice'])) ?></p>
                    <?php } ?>
                </td>
            </tr>
        </tbody>
    </table>

    <br>

    <?php if (isset($_GET['signature'])) { ?>
    <table class="table nb">
        <tbody>
            <tr>
                <td width="30%" class="text-center">
                    <?php if ($theInvoice['sig_client'] != 'none') { ?>
                    <p><strong><?= Yii::t('invoice', 'Customer') ?></strong></p>
                    <br><br><br>
                    <hr>
                    <?php if ($theInvoice['sig_client'] == 'name') { ?>
                    <?= $theInvoice['bill_to_name'] ?>
                    <?php } // if sig name ?>
                    <?php } // if sig != none ?>
                </td>
                <td width="40%">&nbsp;</td>
                <td width="30%" class="text-center">
                    <?php if ($theInvoice['brand'] == 'si') { ?>
                    <?php if ($theInvoice['sig_seller'] != 'none') { ?>
                    <p><strong><?= Yii::t('invoice', 'For Secret Indochina') ?></strong></p>
                    <?php if ($theInvoice['sig_seller'] == 'seal' || $theInvoice['sig_seller'] == 'sealname') { ?>
                    <p><img src="https://my.amicatravel.com/assets/img/amica-thu-huong-invoice-sig-141209.png"></p>
                    <?php } else { ?>
                    <br><br><br>
                    <hr>
                    <?php } // if seal || sealname ?>
                    <?php if ($theInvoice['sig_seller'] == 'name' || $theInvoice['sig_seller'] == 'sealname') { ?>
                    <?= ucwords($theInvoice['booking']['createdBy']['fname'].' '.$theInvoice['booking']['createdBy']['lname']) ?>
                    <?php } // if name || sealname ?>
                    <?php } // if sig != none?>
                    <?php } // if SI ?>

                    <?php if ($theInvoice['brand'] == 'at') { ?>
                    <?php if ($theInvoice['sig_seller'] != 'none') { ?>
                    <p><strong><?= Yii::t('invoice', 'For Amica Travel') ?></strong></p>
                    <?php if ($theInvoice['sig_seller'] == 'seal' || $theInvoice['sig_seller'] == 'sealname') { ?>
                    <p><img src="https://my.amicatravel.com/assets/img/amica-thu-huong-invoice-sig-141209.png"></p>
                    <?php } else { ?>
                    <br><br><br>
                    <hr>
                    <?php } // if seal || sealname ?>
                    <?php if ($theInvoice['sig_seller'] == 'name' || $theInvoice['sig_seller'] == 'sealname') { ?>
                    <?= ucwords($theInvoice['booking']['createdBy']['fname'].' '.$theInvoice['booking']['createdBy']['lname']) ?>
                    <?php } // if name || sealname ?>
                    <?php } // if sig != none?>
                    <?php } // if AT ?>
                </td>
            </tr>
        </tbody>
    </table>
    <br><br>
    <div class="text-center" style="clear:both;">*** <?= Yii::t('invoice', 'THANK YOU FOR YOUR BUSINESS') ?> ***</div>
    <?php } ?>
</div>

<div class="col-md-4">
    <p class="text-uppercase text-bold">Booking summary</p>
    <p>
        <?php if ($theInvoice->isNewRecord) { ?>
        Booking: (<?= Html::a('ID '.$theBooking['id'], '@web/bookings/r/'.$theBooking['id']) ?>) <?= Html::a($theBooking['product']['title'], '@web/products/r/'.$theBooking['product']['id']) ?> @<i class="fa fa-briefcase text-muted"></i> <?= Html::a($theBooking['case']['name'], '@web/cases/r/'.$theBooking['case']['id']) ?> by  <?= $theBooking['createdBy']['name'] ?>
        <?php } else { ?>
        Booking <?= Html::a($theInvoice['booking']['id'], '@web/bookings/r/'.$theInvoice['booking']['id']) ?> | Tour <?= Html::a($theInvoice['booking']['product']['tour']['code'], '@web/tours/r/'.$theInvoice['booking']['product']['tour']['id']) ?> | Product <?= Html::a($theInvoice['booking']['product']['title'], '@web/products/r/'.$theInvoice['booking']['product']['id']) ?> by <?= $theInvoice['booking']['createdBy']['name'] ?>
        <?php } ?>
    </p>

    <?php if ($theInvoice['nho_thu'] != '') { ?>
    <div class="alert alert-warning"><strong>NOTE:</strong> (Nhờ thu / trả) Thực hiện thanh toán qua <?= $theInvoice['nho_thu'] ?></div>
    <?php } ?>
    <!--
    <p><i class="fa fa-fw fa-print text-warning"></i> <strong>PRINT INVOICE</strong></p>
    <p>
        <?= Html::a('Print invoice', '@web/invoices/p/'.$theInvoice['id'], ['class'=>'btn btn-primary']) ?>
        <?= Html::a('Print with signature', '@web/invoices/p/'.$theInvoice['id'].'?signature=yes', ['class'=>'btn btn-primary']) ?>
    </p>
    -->
    <p><i class="fa fa-fw fa-file-pdf-o text-danger"></i> <strong>DOWNLOAD PDF</strong></p>
    <p>
        <?= Html::a('Invoice', '@web/invoices/pdf/'.$theInvoice['id'], ['class'=>'btn btn-info btn_link_dowload']) ?>
        <?= Html::a('Invoice with signature', '@web/invoices/pdf/'.$theInvoice['id'].'?signature=yes', ['class'=>'btn btn-info btn_link_dowload_sign']) ?>
    </p>

    <?php if (!empty($theInvoice['payments'])) { ?>
    <p class="text-uppercase text-bold">Payments</p>
    <table class="table table-framed table-narrow mb-20">
        <thead>
            <tr>
                <th>Date</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Rate to VND</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($theInvoice['payments'] as $payment) { ?>
            <tr>
                <td><?= date('j/n', strtotime($payment['payment_dt'])) ?></td>
                <td class="text-right"><?= number_format($payment['amount']) ?> <?= $payment['currency'] ?></td>
                <td class="text-right"><?= number_format($payment['xrate']) ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>

    <?php if (in_array(MY_ID, [1, 11, 17])) { ?>
    <p class="text-uppercase text-bold">Mark as paid/unpaid</p>
        <?php if ($theInvoice['status'] == 'active') { ?>
            <?php if ($theInvoice['payment_status'] == 'paid') { ?>
        <?= Html::a('Mark as UNPAID', '@web/invoices/mu/'.$theInvoice['id'], ['class'=>'btn btn-danger']) ?>
            <?php } else { ?>
        <?= Html::a('Mark as PAID', '@web/invoices/mp/'.$theInvoice['id'], ['class'=>'btn btn-success']) ?>
            <?php } ?>
        <?php } ?>
    </p>
    <?php } ?>
    <?php if ($theInvoice['note'] != '') { ?>
    <p class="text-uppercase text-bold">NOTE:</p>
    <p><?= nl2br($theInvoice['note']) ?></p>
    <?php } ?>
    <p>Invoice created <?= \app\helpers\DateTimeHelper::convert($theInvoice['created_at'], 'j/n/Y H:i') ?></p>
    <p>Last update <?= Yii::$app->formatter->asRelativetime($theInvoice['updated_at']) ?> by <?= $theInvoice['updatedBy']['name'] ?></p>
</div>
<?php
$js = <<<TXT
var HREF_link = $('.btn_link_dowload').attr('href') + '?';
var OP_stype_cur1 = '';
var OP_stype_cur2 = '';
var OP_xrate_cur1 = 0;
var OP_xrate_cur2 = 0;
Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
    var n = this,
        decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
        decSeparator = decSeparator == undefined ? "." : decSeparator,
        thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
        sign = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return myTrim(sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : ""));
};

function myTrim(x) {
    return x.replace(/0+$/gm, '').replace(/\.+$/gm, '');
}
console.log(myTrim('4.00'));
$('.stype_curency').on('change', function(){
    $('[name="xrate"]').val(0);
});
$('[name="xrate"]').on('blur', function(){
    var amount = $(this).data("amount");
    var xrate = $(this).val();
    var currency = $('.stype_curency').val();
    if(xrate <= 0) {
        return false;
    }
    // $('.deduction_num').text('0');
    // $('.total_num').text('0');
    var deduction = 2*(amount * xrate)/100;
    var total_pay = (amount * xrate) - deduction;
    $('.deduction_num').text(deduction.formatMoney(2, ',', '.')+' '+ currency.toUpperCase());
    $('.total_num').text(total_pay.formatMoney(2, ',', '.')+' '+ currency.toUpperCase());

    OP_stype_cur1 = currency;
    OP_xrate_cur1 = xrate;
	var link = HREF_link + 'op_cur_stype1=' + OP_stype_cur1 + '&op_cur_xrate1=' + OP_xrate_cur1 + '&op_cur_stype2=' + OP_stype_cur2 + '&op_cur_xrate2=' + OP_xrate_cur2;
	$('.btn_link_dowload').prop('href', link);
	$('.btn_link_dowload_sign').prop('href', link + '&signature=yes');
});

$('.stype_curency_credit').on('change', function(){
    $('[name="xrate_credit"]').val(0);
});
$('[name="xrate_credit"]').on('blur', function(){
    var amount = $(this).data("amount");
    var xrate = $(this).val();
    var currency = $('.stype_curency_credit').val();
    var banc = $(this).data("xrate").substr(-2,1);
    if(xrate <= 0) {
        return false;
    }
    $('.bancaires_num').text('0');
    $('.total_num_credit').text('0');
    var bancaires = banc*(amount * xrate)/100;
    var total_pay = (amount * xrate) + bancaires;
    $('.bancaires_num').text(bancaires.formatMoney(2, ',', '.')+' '+ currency.toUpperCase());
    $('.total_num_credit').text(total_pay.formatMoney(2, ',', '.')+' '+ currency.toUpperCase());

    OP_stype_cur2 = currency;
    OP_xrate_cur2 = xrate;
    var link = HREF_link + 'op_cur_stype1=' + OP_stype_cur1 + '&op_cur_xrate1=' + OP_xrate_cur1 + '&op_cur_stype2=' + OP_stype_cur2 + '&op_cur_xrate2=' + OP_xrate_cur2;
	$('.btn_link_dowload').prop('href', link);
	$('.btn_link_dowload_sign').prop('href', link + '&signature=yes');
});

TXT;
$this->registerJs($js);
?>
