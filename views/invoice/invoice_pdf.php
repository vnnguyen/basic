<?php

use yii\helpers\Html;

$total = 0;
$option = 0;

$arr_currency = ['vnd' => 'VND', 'usd' => 'USD', 'eur' => 'EUR'];

include('_invoice_inc.php');

if (!function_exists('trim00')) {
    function trim00($str)
    {
        return rtrim(rtrim($str, '0'), '.');
    }
}

Yii::$app->params['page_title'] = 'Invoice '.$theInvoice['ref'];
Yii::$app->language = $theInvoice['lang'];

?><html lang="<?= $theInvoice['lang'] ?>">
<head>
    <meta charset="utf-8">
    <title>Invoice <?= $theInvoice['ref'] ?></title>
    <style type="text/css">
    html, body {font-family:Calibri,sans-serif; font-size:10pt;}
    h1 {font-size:30px!important; line-height:32px!important; margin:0 0 2px!important;}
    .pr-30 {padding-right:30px!important;}
    .fs-125pc {font-size:125%!important;}
    .fs-90pc {font-size:90%!important;}

    .table {margin-bottom:12px; border-collapse: collapse; width:100%;}
    .table td, .table th {text-align:left; vertical-align:top; padding:4px;}
    .table-bordered td, .table-bordered th {border:1px solid #ccc;}
    .table-borderless td, .table-borderless th {border:0;}
    #wrap {margin:20px 0; background-color:#fff; padding:48px;}
    .text-center, .table th.text-center, .table td.text-center {text-align:center;}
    .text-right, .table th.text-right, .table td.text-right {text-align:right;}
    .text-nowrap {white-space: nowrap!important;}

    table.nb, table.nb tbody, table.nb tr, table.nb td, table.nb th {border:0!important;}
    table#pricetable {border:0!important; border-top:1px solid #BF4C9D!important;}
    table.table-bordered td.nb {border:0!important;}
    table.table-bordered td.br-0 {border-right:0!important;}
    table.table-bordered td.bl-0 {border-left:0!important;}
    table.table-bordered td.bb-0 {border-bottom:0!important;}
    table.table-bordered td.bt-0 {border-top:0!important;}
    @page {
        margin:0;
        margin-header:0;
        margin-footer:0;
        margin-top:4cm;
        margin-bottom:3cm;
        header:pageHeader;
        footer:pageFooter;
    }
    </style>
</head>
<body>
    <?php if ($theInvoice['brand'] == 'at') { ?>
    <htmlpageheader name="pageHeader" style="display:none">
        <div style="margin-left:12.8cm; padding-top:2cm;">
            <h1 style="color:#BD3920"><strong>
                <?php if ($theInvoice['stype'] == 'invoice') { ?>
                <?= Yii::t('invoice', 'INVOICE') ?>
                <?php } elseif ($theInvoice['stype'] == 'credit') { ?>
                <?= Yii::t('invoice', 'CREDIT/REFUND') ?>
                <?php } ?>
            </strong></h1>
            <br>
            <div class="fs-x90pc">
                <strong><?= Yii::t('invoice', 'Ref') ?>:</strong> <?= $theInvoice['ref'] ?>
                <br><strong><?= Yii::t('invoice', 'Date') ?>:</strong> <?= date('d-m-Y') ?>
            </div>
        </div>
        <img style="margin-top:-2cm; margin-left:2.2cm" height="76" src="<?= Yii::$app->params['print_logo'] ?>">
    </htmlpageheader>
    <htmlpagefooter name="pageFooter" style="display:none">
        <img src="https://my.amicatravel.com/assets/tools/docx/b2c/pdf-images/last-footer-fr.jpg">
    </htmlpagefooter>
    <?php } else { ?>
    <!-- SI -->
    <htmlpageheader name="pageHeader" style="display:none">
        <div style="margin-left:13cm; padding-top:2cm;">
            <h1 style="color:#e53791"><strong>
                <?php if ($theInvoice['stype'] == 'invoice') { ?>
                <?= Yii::t('invoice', 'INVOICE') ?>
                <?php } elseif ($theInvoice['stype'] == 'credit') { ?>
                <?= Yii::t('invoice', 'CREDIT/REFUND') ?>
                <?php } ?>
            </strong></h1>
            <br>
            <div class="fs-x90pc">
                <strong><?= Yii::t('invoice', 'Ref') ?>:</strong> <?= $theInvoice['ref'] ?>
                <br><strong><?= Yii::t('invoice', 'Date') ?>:</strong> <?= date('d-m-Y') ?>
            </div>
        </div>
        <img style="margin-top:-3cm; margin-left:2.5cm" height="120" width="150" src="/assets/img/logo_si_v_261212.jpg">
    </htmlpageheader>
    <htmlpagefooter name="pageFooter" style="display:none">
        <div style="padding:0 1cm 1cm; text-align:center;">
            <strong>AMICA JSC.</strong> &middot; <?= Yii::t('invoice', '6th Floor, Nikko Building') ?>, <?= Yii::t('invoice', '27 Nguyen Truong To str, Hanoi, Vietnam') ?>
            <br>[t] +84 24 3266 9052 &middot; [w] https://www.secretindochina.com &middot; [e] contact@secretindochina.com
        </div>
    </htmlpagefooter>
    <?php } ?>

    <div style="padding:1cm 2cm 0">
        <hr>
        <br><br>

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
                <th width="100"><?= Yii::t('invoice', 'Total') ?></th>
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
            <?php if ($theInvoice['gw_currency'] != $theInvoice['currency'] || $theInvoice['body3'] != '') {?>
            <tr>
                <td colspan="3" class="bt-0 bl-0 bb-0 text-right"><?= Yii::t('invoice', 'TOTAL') ?> (<?= $theInvoice['currency'] ?>)</td>
                <td class="text-right text-nowrap bg-success"><strong><?= trim00(number_format($total, 2)) ?></strong> <?= $theInvoice['currency'] ?></td>
            </tr>
            <?php }?>


            <tr><td colspan="4" class="nb text-center" style="padding:4px;">Choisissez votre option de paiement</td></tr>
            
            <?php if ($theInvoice['booking']['case']['is_b2b'] == 'no' && $theInvoice['method'] != 'cash') {
                if ($op_cur_stype1 != '' && $op_cur_xrate1 > 0 && $op_cur_stype1 != $theInvoice['currency']) {
                    $op2_total = $theInvoice['amount'] * (float)$op_cur_xrate1;
                    $theInvoice['gw_currency'] = strtoupper($op_cur_stype1);
                } else {
                    $op2_total = ($theInvoice['gw_currency'] != $theInvoice['currency'])?$theInvoice['amount'] * (float)$theInvoice['gw_xrate']: $theInvoice['amount'];
                }
                $deduction = $op2_total * 2/100;
                $value = $op2_total - $deduction;
                echo '<tr style="border-bottom: 1px solid #000"><td colspan="4" class="nb text-center" style="padding:4px;"><strong>'.Yii::t('invoice', 'Option ').++$option. ': '.Yii::t('invoice', 'Method of payment').': '.$methodList['cash'].'</strong></td></tr>';
                //$value = $value * (float)$theInvoice['gw_xrate'];
            ?>
            <tr><td colspan="4" class="nb" style="padding:4px;"></td></tr>
            <tr>
                <td colspan="3" class="bt-0 bb-0 bl-0 text-right text-nowrap"><?= Yii::t('invoice', 'Déduction'). ' 2%' ?></td>
                <td class="text-right text-nowrap"><strong><span class="deduction_num"><?= trim00(number_format($deduction, 2)) ?> </span></strong><?= $theInvoice['gw_currency'] ?></td>
            </tr>
            <tr>
                <td class="nb text-right"><?= Yii::t('invoice', 'Paiement en')?></td>
                <td class="nb text-right">
                    <select class="stype_curency">
                        <?php foreach ($arr_currency as  $cur) {?>
                            <option value="<?= strtolower($cur)?>" <?= (strtolower($cur) == strtolower($op_cur_stype1))? 'selected="selected"': ''?>><?= $cur?></option>
                        <?php } ?>
                    </select>
                </td>
                <td  class="bt-0 bb-0 bl-0 text-right text-nowrap"><?= Yii::t('invoice', 'Taux de change')?></td>
                <td class="text-right text-nowrap"><?= $op_cur_xrate1 ?></td>
            </tr>
            <tr><td colspan="4" class="nb" style="padding:4px;"></td></tr>
            <tr>
                <td colspan="3" class="nb text-right"><strong><?= Yii::t('invoice', 'TOTAL DUE') ?></strong></td>
                <td class="text-right text-nowrap bg-success currency_number"><strong><span class="total_num"><?= trim00(number_format($value, 2)) ?> </span></strong><?= $theInvoice['gw_currency'] ?></td>
            </tr>
            <?   } ?>

            <tr><td colspan="4" class="nb" style="padding:4px;"></td></tr>

            <?php
            if ($theInvoice['body3'] != '') {
                 echo '<tr style="border-bottom: 1px solid #000"><td colspan="4" class="nb text-center" style="padding:4px;"><strong>'.Yii::t('invoice', 'Option ').++$option. ': '.Yii::t('invoice', 'Method of payment').': '.$methodList[$theInvoice['method']].'</strong></td></tr>';
            ?>

                <?php
                $old_total = 0;
                $val = 0;
                if ($op_cur_stype2 != '' && $op_cur_xrate2 > 0) {
                    $old_total = $total * $op_cur_xrate2;
                }
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
                        if ($op_cur_stype2 != '' && $op_cur_xrate2 > 0) {
                            $theInvoice['currency'] = strtoupper($op_cur_stype2);
                            $val = $value = 0.01 * $old_total * (float)$parts[1];
                        }
                ?>

            <tr>
                <td class="nb text-right"><?= Yii::t('invoice', 'Paiement en')?></td>
                <td class="nb text-right">
                    <select class="stype_curency">
                        <?php
                        if ($op_cur_stype2 == '') {
                            $op_cur_stype2 = strtolower($theInvoice['currency']);
                        }
                        foreach ($arr_currency as  $cur) {?>
                            <option value="<?= strtolower($cur)?>" <?= (strtolower($cur) == strtolower($op_cur_stype2))? 'selected="selected"': ''?>><?= $cur?></option>
                        <?php } ?>
                    </select>
                </td>
                <td  class="bt-0 bb-0 bl-0 text-right text-nowrap"><?= Yii::t('invoice', 'Taux de change')?></td>
                <td class="text-right text-nowrap"><?= ($op_cur_xrate2 > 0) ? $op_cur_xrate2: 1 ?></td>
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
                <td class="text-right text-nowrap"><strong><span class="bancaires_num"><?= trim00(number_format($value, 2)) ?> </span></strong><?= $theInvoice['currency'] ?> </td>
            </tr>
<?php
                    }
                }
            }
?>
            <tr><td colspan="4" class="nb" style="padding:4px;"></td></tr>
            <tr>
                <td colspan="3" class="nb text-right"><strong><?= Yii::t('invoice', 'TOTAL DUE') ?></strong></td>
                <td class="text-right text-nowrap bg-success"><strong><span class="total_num_credit"><?= trim00(number_format(($old_total == 0)?$total : $old_total + $val, 2)) ?> </span></strong> <?= $theInvoice['currency'] ?></td>
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

    <?php if (isset($_GET['signature']) && SEG2 == 'pdf') { ?>
    <div style="float:left; width:7cm;" class="text-center">
        <?php if ($theInvoice['sig_client'] != 'none') { ?>
        <p><strong><?= Yii::t('invoice', 'Customer') ?></strong></p>
        <br><br><br>
        <hr>
        <?php if ($theInvoice['sig_client'] == 'name') { ?>
        <?= $theInvoice['bill_to_name'] ?>
        <?php } // if sig name ?>
        <?php } // if sig != none ?>
    </div>
    <div style="float:right; width:7cm;" class="text-center">
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
    </div>

    <?php if ($theInvoice['brand'] == 'at') { ?>
    <div class="text-center" style="clear:both;"><br><br>*** <?= Yii::t('invoice', 'THANK YOU FOR YOUR BUSINESS') ?> ***</div>
    <?php } ?>

    <?php } ?>

        </div>
    </body>
</html>
                    
