<?
use yii\helpers\Html;

\Yii::$app->language = $theForm->language;

Yii::$app->params['page_title'] = 'In chi phí cho tour guide - '.$theTour['op_code'];
Yii::$app->params['page_layout'] = '-n -s -h';
Yii::$app->params['body_class'] = 'bg-white';

$total = 0;
$dayIdList = explode(',', $theTour['day_ids']);
$showDays = [];
$ranges = explode(',', $theForm->days);
foreach ($ranges as $range) {
    $rr = explode('-', $range);
    if (isset($rr[1])) {
        //1-3
        for ($i = (int)trim($rr[0]); $i <= (int)trim($rr[1]); $i ++) {
            $showDays[] = $i;
        }
    } else {
        //4
        $showDays[] = trim($range);
    }
}

$dayMap = \yii\helpers\ArrayHelper::map($theTour['days'], 'id', 'name');

foreach ($dayIdList as $cnt=>$dayId) {
    if (!in_array($cnt + 1, $showDays)) {
        unset($dayIdList[$cnt]);
    }
}

foreach ($dayMap as $dayId=>$dayName) {
    if (!in_array($dayId, $dayIdList)) {
        unset($dayMap[$dayId]);
    }
}

// \fCore::expose($dayIdList);
// \fCore::expose($dayMap);

require_once('_tours_inc.php');

$sql = 'SELECT u.name, u.phone, tu.role FROM persons u, at_tour_user tu WHERE tu.user_id=u.id AND tu.tour_id=:tour_id AND tu.role IN ("cservice", "operator")';
$tourStaff = Yii::$app->db->createCommand($sql, [
    ':tour_id'=>$theTourOld['id'],
    ])->queryAll();

// Costs

$totalVNDCost = [0];
$totalCost = [
    'USD'=>[0],
    'EUR'=>[0],
    'LAK'=>[0],
    'KHR'=>[0],
    'VND'=>[0],
]; 

foreach ($dayIdList as $cnt=>$dayId) {
    $Ymd = date('Y-m-d', strtotime($theTour['day_from'].' +'.($cnt).'days'));
    foreach ($theCptx as $cpt) {
        if (($theForm->payer == '' || $cpt['payer'] == $theForm->payer) && $cpt['dvtour_day'] == $Ymd) {
            if (!isset($totalVNDCost[$cpt['dvtour_id']])) {
                $totalVNDCost[$cpt['dvtour_id']] = 0;
            }
            if (!isset($totalCost[$cpt['unitc']][$cpt['dvtour_id']])) {
                $totalCost[$cpt['unitc']][$cpt['dvtour_id']] = 0;
            }
            if (!isset($xRates[$cpt['unitc']])) {
                $xRates[$cpt['unitc']] = 1;
            }
            if ($cpt['plusminus'] == 'plus') {
                $totalVNDCost[$cpt['dvtour_id']] = $xRates[$cpt['unitc']] * $cpt['price'] * $cpt['qty'];
                $totalVNDCost[0] += $xRates[$cpt['unitc']] * $cpt['price'] * $cpt['qty'];
                $totalCost[$cpt['unitc']][$cpt['dvtour_id']] += $cpt['price'] * $cpt['qty'];
                $totalCost[$cpt['unitc']][0] += $cpt['price'] * $cpt['qty'];
            } else {
                $totalVNDCost[$cpt['dvtour_id']] = -$xRates[$cpt['unitc']] * $cpt['price'] * $cpt['qty'];
                $totalVNDCost[0] -= $xRates[$cpt['unitc']] * $cpt['price'] * $cpt['qty'];
                $totalCost[$cpt['unitc']][$cpt['dvtour_id']] -= $cpt['price'] * $cpt['qty'];
                $totalCost[$cpt['unitc']][0] -= $cpt['price'] * $cpt['qty'];
            }
        }
    }
}
?>
<style type="text/css">
@media print {
    @page {size: A4 portrait;}
}

table th, table td {vertical-align:top!important}
.font-roboto-15 .content {font-size:11px!important;}
.table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {padding:4px;}
</style>
<div class="col-md-12">
    <div class="alert alert-info hidden-print">
        <a href="/tours/r/<?= $theTourOld['id'] ?>">Return to tour</a>
        or
        <a href="/tours/in-hf/<?= $theTour['id'] ?>">Return to print settings</a>
    </div>
    <table class="table table-condensed table-borderless">
        <tr>
            <td width="200">
                <img id="logo" xheight="110" width="200" src="<?= Yii::$app->params['print_logo'] ?>" alt="Logo">
            </td>
            <td class="text-nowrap">
            </td>
            <td class="text-right">
                <h2 style="margin:0">
                    <span class="text-bold"><?= Yii::t('in-hd', 'Detail of tour costs for tour guide') ?></span>
                    <br><span><?= $theTour['op_code'] ?> &middot; <?= $theTour['op_name'] ?> &middot; <?= $theTour['pax']?>p <?= $theTour['day_count']?>d <?= date('j/n/Y', strtotime($theTour['day_from'])) ?></span>
                </h2>
            </td>
        </tr>
    </table>

    <table class="table table-condensed table-borderless mb-20">
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
            <td width="45%">
                <table class="table table-borderless table-condensed">
                    <? if (!in_array('vnd', $theForm->options)) { ?>
                    <tr>
                        <th width="30%"><?= Yii::t('in-hd', 'Total') ?></th>
                        <td width="70%">
                            <div style="margin:0 0 10px; font-size:18px; line-height:20px;"><span class="text-bold" id="totalhere"><?= number_format($totalVNDCost[0], intval($totalVNDCost[0]) == $totalVNDCost[0] ? 0 : 2) ?></span> VND</div>
                            <div><em id="spellout"><?= Yii::$app->formatter->asSpellout($totalVNDCost[0]) ?></em></div>
                        </td>
                    </tr>
                    <tr>
                        <th><?= Yii::t('in-hd', 'Rate') ?></th>
                        <td><?= number_format($xRates['USD']) ?> VND/USD</td>
                    </tr>
                    <? } ?>
                    <tr>
                        <th><?= Yii::t('in-hd', 'Tour guide') ?></th>
                        <td>
            <?
            foreach ($tourguideList as $tourguide) {
                if ($tourguide['guide_name'] == $theForm->tourguide) {
                    echo $tourguide['guide_name'];
                    break;
                }
            }
            ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?= Yii::t('in-hd', 'Driver') ?></th>
                        <td>
            <?
            foreach ($driverList as $driver) {
                if ($driver['driver_user_id'] == $theForm->driver) {
                    echo $driver['driver_name'];
                    break;
                }
            }
            ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?= Yii::t('in-hd', 'Sales') ?></th>
                        <td>
            <?
            foreach ($theTour['bookings'] as $booking) {
                echo $booking['createdBy']['name'], ' - ', $booking['createdBy']['phone'];
            }
            ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?= Yii::t('in-hd', 'Operation') ?></th>
                        <td><?
                        foreach ($tourStaff as $user) {
                            if ($user['role'] == 'operator') {
                                echo '<div>', $user['name'], ' - ', $user['phone'], ' <i title="Xoá" class="hidden-print fa fa-trash-o text-danger cursor-pointer" onclick="$(this).parent().remove();"></i></div>';
                            }
                        }
                        ?></td>
                    </tr>
                    <tr>
                        <th><?= Yii::t('in-hd', 'Customer svc') ?></th>
                        <td><?
                        foreach ($tourStaff as $user) {
                            if ($user['role'] == 'cservice') {
                                echo '<div>', $user['name'], ' - ', $user['phone'], ' <i title="Xoá" class="hidden-print fa fa-trash-o text-danger cursor-pointer" onclick="$(this).parent().remove();"></i></div>';
                            }
                        }
                        ?></td>
                    </tr>
                </table>
            </td>
            <td>
                <? if (in_array('vnd', $theForm->options)) { ?>
                <p class="text-right"><strong><?= Yii::t('in-hd', 'Total') ?></strong></p>
                <? foreach ($totalCost as $cur=>$cost) { if ($cost[0] != 0) { ?>
                <div class="text-right" style="font-size:24px;"><strong><?= number_format($cost[0], intval($cost[0]) == $cost[0] ? 0 : 2) ?></strong> <?=$cur ?></div>
                <div class="text-right"><em><?= Yii::$app->formatter->asSpellout($cost[0]) ?> <?= $cur ?></em></div>
                <? } } ?>
                <? } ?>
                <? if (!in_array('not', $theForm->options)) { ?>
                <p><strong>GHI CHÚ CHO TOUR GUIDE:</strong></p>
                <p><strong>Nhớ lấy hoá đơn, chứng từ, vé tham quan đầy đủ về để thanh toán.</strong> Thông tin ghi hoá đơn như sau:</p>
                <p><strong>CÔNG TY CP ĐẦU TƯ THƯƠNG MẠI VÀ DU LỊCH THÂN THIỆN VIỆT NAM</strong>
                   <br>Địa chỉ: Tầng 3, tòa nhà Nikko, 27 Nguyễn Trường Tộ, Phường Nguyễn Trung Trực, Quận Ba Đình, TP Hà Nội, Việt Nam
                    <br>MST: 0 1 0 2 1 9 5 3 1 9</p>
                <p><strong>Nhớ lấy feedback của khách</strong> với những đoàn có đưa feedback.</p>
                <p>Nếu cần trợ giúp về tuyến điểm, đường đi, xin liên hệ với Bộ phận Sản phẩm và Dịch vụ: Mr Hà (0943-388-718) hoặc Mr Nguyên (0988-933-188)</p>
                <? } ?>
            </td>
        </tr>
        <? if ($theForm->note != '') { ?>
        <tr>
            <td colspan="3">
                <p><strong><?= Yii::t('in-hd', 'Other note for tour guide') ?>:</strong></p>
                <div style="padding-left:60px;">
                    <?= nl2br($theForm->note) ?>
                </div>
            </td>
        </tr>
        <? } ?>

    </table>

    <h5><?= Yii::t('in-hd', 'Detail of tour costs') ?></h5>
    <table class="table table-condensed mb-20">
        <thead>
            <tr>
                <th colspan="2"><?= Yii::t('in-hd', 'Cost') ?></th>
                <th><?= Yii::t('in-hd', 'Supplier') ?></th>
                <th><?= Yii::t('in-hd', 'No.') ?></th>
                <th><?= Yii::t('in-hd', 'Unit') ?></th>
                <th><?= Yii::t('in-hd', 'Price') ?></th>
                <th><?= Yii::t('in-hd', 'Cur') ?></th>
                <th class="text-right"><?= Yii::t('in-hd', 'Total') ?></th>
            </tr>
        </thead>
        <tbody>
<?
$venues = [];
$companies = [];

foreach ($dayIdList as $cnt=>$dayId) {
    // foreach ($theTour['days'] as $ng) {
    //     if ($ng['id'] == $di) {
    //         if (in_array($cnt, $showDays)) {
                $Ymd = date('Y-m-d', strtotime($theTour['day_from'].' +'.($cnt).'days'));
?>
            <tr id="day<?= $Ymd ?>">
                <th width="30">#<?= 1 + $cnt ?></th>
                <th colspan="8">
                    <span style="font-weight:normal">(<?= Yii::$app->formatter->asDate($Ymd, 'php:j/n/Y l') ?>)</span>
                    <?= $dayMap[$dayId] ?>
                </th>
            </tr>
<?
                foreach ($theCptx as $cpt) {
                    if ($cpt['dvtour_day'] == $Ymd) {
                        if ($theForm->payer == '' || $cpt['payer'] == $theForm->payer || !in_array('cpt', $theForm->options)) {
?>
            <tr>
                <td>
                    <? if ($cpt['payer'] != $theForm->payer) { ?>
                    <i title="Xoá cpt này" class="hidden-print fa fa-trash-o text-danger cursor-pointer" onclick="$(this).parent().parent().remove()"></i>
                    <? } ?>
                </td>
                <td>
<?
                        // if ($cpt['start'] != '00:00:00') {
                        //     echo '('.substr($cpt['start'], 0, 5).') ';
                        // }
                        // if ($cpt['number'] != '') {
                        //     echo '('.$cpt['number'].') ';
                        // }
                        echo $cpt['dvtour_name'];
?>
                </td>
                <td>
<?
                        if ($cpt['venue_id'] != 0) {
                            $venues[] = $cpt['venue_id'];
                            echo $cpt['venue_name'];
                        } elseif ($cpt['via_company_id'] != 0) {
                            $companies[] = $cpt['via_company_id'];
                            echo $cpt['via_company_name'];
                        } elseif ($cpt['by_company_id'] != 0) {
                            $companies[] = $cpt['by_company_id'];
                            echo $cpt['by_company_name'];
                        } else {
                            echo $cpt['oppr'];
                        }
?>
                </td>
                <td class="text-center">
                    <?= number_format($cpt['qty'], intval($cpt['qty']) == $cpt['qty'] ? 0 :  2) ?>
                </td>
                <td><?= $cpt['unit'] ?></td>
<?
                        if ($cpt['payer'] == $theForm->payer) {
?>
                <td class="text-right"><?= $cpt['plusminus'] == 'minus' ? '-' : '' ?><?= number_format($cpt['price'], intval($cpt['price']) == $cpt['price'] ? 0 : 2) ?></td>
                <td><?= $cpt['unitc'] ?></td>
                <td class="text-right" title="<?= $total ?>">
                    <strong><?= $cpt['plusminus'] == 'minus' ? '-' : '' ?>
                        <?
                        if (in_array('vnd', $theForm->options)) {
                            echo number_format($totalCost[$cpt['unitc']][$cpt['dvtour_id']], intval($totalCost[$cpt['unitc']][$cpt['dvtour_id']]) == $totalCost[$cpt['unitc']][$cpt['dvtour_id']] ? 0 : 2);
                        } else {
                            echo number_format($totalVNDCost[$cpt['dvtour_id']], intval($totalVNDCost[$cpt['dvtour_id']]) == $totalVNDCost[$cpt['dvtour_id']] ? 0 : 2);
                        }
                        ?>
                    </strong>
                    <?= in_array('vnd', $theForm->options) ? $cpt['unitc'] : '' ?>
                </td>
                <!--
                <td><?//= $cpt['booker'] ?></td>
                <td><?//= $cpt['payer'] == $theForm->payer ? $theForm->payer : '' ?></td>
                -->
<?
                        } else {
?>
                <td colspan="3"></td>
<?
                        }
?>
            </tr>
<?
                    }
                }
            }
            $cnt ++;
    //         }
    //     }
    // }
}
?>
        </tbody>
    </table>

    <? if (!in_array('ncc', $theForm->options)) { ?>
    <h5 class="vinfo"><?= Yii::t('in-hd', 'Contact information of suppliers') ?></h5>
    <table class="vinfo table table-condensed mb-20">
        <thead>
            <tr>
                <th><a href="#" class="hidden-print" onclick="$('.vinfo').remove(); return false;">[ẩn hết]</a></th>
                <th><?= Yii::t('in-hd', 'Name') ?></th>
                <th><?= Yii::t('in-hd', 'Address') ?></th>
                <th><?= Yii::t('in-hd', 'Telephone') ?></th>
            </tr>
        </thead>
        <tbody>
<?
$allVenueNames = \common\models\Venue::find()
    ->select(['id', 'name'])
    ->where(['id'=>$venues])
    ->with(['metas'])
    ->asArray()
    ->all();

$allCompanyNames = \common\models\Company::find()
    ->select(['id', 'name'])
    ->where(['id'=>$companies])
    ->with(['metas'])
    ->asArray()
    ->all();

foreach ($allVenueNames as $venue) {
    $vTel = '';
    $vAddr = '';
    foreach ($venue['metas'] as $vd) {
        if ($vTel == '' && ($vd['k'] == 'tel' || $vd['k'] == 'mobile')) $vTel = $vd['v'];
        if ($vAddr == '' && $vd['k'] == 'address') $vAddr = $vd['v'];
    }
?>
            <tr>
                <td><a class="hidden-print" onclick="$(this).parent().parent().hide(0); return false;" href="#">[ẩn đi]</a></td>
                <td><?= $venue['name'] ?></td>
                <td><?= $vAddr ?></td>
                <td><?= $vTel ?></td>
            </tr>
<?
}

foreach ($allCompanyNames as $company) {
    $cTel = '';
    $cAddr = '';
    foreach ($company['metas'] as $cd) {
        if ($cTel == '' && ($cd['k'] == 'tel' || $cd['k'] == 'mobile')) $cTel = $cd['v'];
        if ($cTel == '' && $cd['k'] == 'address') $cAddr = $cd['v'];
    }
?>
            <tr>
                <td><a class="hidden-print" onclick="$(this).parent().parent().hide(0); return false;" href="#">[ẩn đi]</a></td>
                <td><?= $company['name'] ?></td>
                <td><?= $cAddr ?></td>
                <td><?= $cTel ?></td>
            </tr>
<? } ?>
        </tbody>
    </table>
    <? } ?>

    <table class="table text-center">
        <tr>
            <td width="33%">&nbsp;<br><?= Yii::t('in-hd', 'Tour guide') ?></td>
            <td width="33%">&nbsp;<br><?= Yii::t('in-hd', 'Tour operator') ?></td>
            <td width="33%"><em><?= Yii::t('in-hd', 'Printed on') ?> <?= date('j/n/Y') ?></em><br><?= Yii::t('in-hd', 'Accountant') ?></td>
        </tr>
    </table>
</div>

<?

$js = "
$('#totalhere').text('".number_format($total, intval($total) == $total ? 0 : 2)."')
$('#spellout').text('".\Yii::$app->formatter->asSpellout($total)." VND')
";
// $this->registerJs($js);