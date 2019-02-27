<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_title'] = 'B2B Reports';
Yii::$app->params['page_small_title'] = count($theBookings).' bookings';
Yii::$app->params['page_breadcrumbs'] = [
    ['B2B', 'b2b'],
    ['Reports'],
];

$xrateUSD = 1.25;

$kq = [];

for ($yr = 2015; $yr <= date('Y') + 1; $yr ++) {
    $yearList[$yr] = $yr;
}
for ($mo = 1; $mo <= 12; $mo ++) {
    $monthList[$mo] = $mo;
}

$viewToursByList = [
    'sale_date'=>'Sale date',
    'start_date'=>'Start date',
    'end_date'=>'End date',
];

?>

<div class="col-md-12">
    <form class="form-inline mb-2">
        <?= Html::dropdownList('seller', $seller, ArrayHelper::map($sellerList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Seller')]) ?>
        <?= Html::dropdownList('client', $client, ArrayHelper::map($clientList, 'id', 'name'), ['class'=>'form-control select2', 'prompt'=>Yii::t('x', 'Client')]) ?>
        <?= Html::dropdownList('viewtour', $viewtour, $viewToursByList, ['class'=>'form-control']) ?>
        <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Year')]) ?>
        <?= Html::dropdownList('month', $month, $monthList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Month')]) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '@web/b2b/reports') ?>
    </form>
    <br>

    <div class="alert alert-info">
        STT <span class="text-danger">màu đỏ</span> có nghĩa là booking chưa được cập nhật dữ liệu doanh thu.
    </div>

    <div class="card">
        <div class="card-header">Bảng thống kê doanh thu (Request)</div>
        <div class="table-responsive">
            <table class="table table-striped table-narrow table-bordered">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th width="40">OK</th>
                        <th>Tour</th>
                        <th>Hãng</th>
                        <th width="40">Ngày cfm</th>
                        <th width="40">Ngày KH</th>
                        <th width="40">Ngày KT</th>
                        <th width="40" class="text-center">Ngày</th>
                        <th width="40" class="text-center">Pax</th>
                        <th class="text-center">NxP</th>
                        <th>Tiền</th>
                        <th>D/thu</th>
                        <th>C/phí</th>
                        <th>Dt/np</th>
                        <th>Cp/np</th>
                        <th>L/gộp</th>
                        <!-- <th>DTQĐ</th> -->
                        <th>P%LG</th>
                        <th>Note</th>
                        <th>HSBH</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sumNgay = 0;
                    $sumPax = 0;
                    $sumNgayPax = 0;
                    $sumDoanhThu = 0;
                    $sumChiPhi = 0;
                    $avgDoanhThuNgayPax = 0;
                    $avgChiPhiNgayPax = 0;
                    $sumLaiGop = 0;
                    $sumDoanhThuQuyDoi = 0;
                    $avgPhanTramLaiGop = 0;

                    $cnt = 0;
                    
                    foreach ($theBookings as $booking) {
                        if ((int)$client == 0 || $client == $booking['case']['company_id']) {
                            if ($booking['case']['is_b2b'] == 'yes' && $booking['case']['stype'] == 'b2b') {
                                $doanhThuUSD = 0;
                                $chiPhiUSD = 0;
                                if ($booking['report']) {
                                    $ngay = $booking['report']['day_count'];
                                    $pax = $booking['report']['pax_count'];
                                    $ngayPax = $ngay * $pax;
                                    if ($booking['report']['price_unit'] != 'USD') {
                                        $doanhThu = $booking['report']['price'] / $xrateUSD;
                                        $chiPhi = $booking['report']['cost'] / $xrateUSD;
                                        $doanhThuUSD = $booking['report']['price'];
                                        $chiPhiUSD = $booking['report']['cost'];
                                    } else {
                                        $doanhThu = $booking['report']['price'];
                                        $chiPhi = $booking['report']['cost'];
                                    }
                                    $loaiTien = $booking['report']['price_unit'];
                                } else {
                                    $ngay = $booking['product']['day_count'];
                                    $pax = $booking['pax'];
                                    $ngayPax = $ngay * $pax;
                                    $doanhThu = 10;//$booking['price'];
                                    $chiPhi = 10;//rand(890, 1280);
                                    $loaiTien = $booking['currency'];
                                }

                                $doanhThuNgayPax = $ngayPax == 0 ? 0 : $doanhThu / $ngayPax;
                                $chiPhiNgayPax = $ngayPax == 0 ? 0 : $chiPhi / $ngayPax;
                                $laiGop = $doanhThu - $chiPhi;
                                $doanhThuQuyDoi = $laiGop * 5;
                                $phanTramLaiGop = $doanhThu == 0 ? 0 : 100 * $laiGop / $doanhThu;

                                if ($booking['report']) {
                                    $sumNgay += $ngay;
                                    $sumPax += $pax;
                                    $sumNgayPax += $ngayPax;
                                    $sumDoanhThu += $doanhThu;
                                    $sumChiPhi += $chiPhi;
                                }
                                $cnt ++;
                    ?>
                    <tr class="">
                        <td class="text-center"><?= Html::a('<i class="fa fa-edit"></i>', '@web/bookings/report/'.$booking['id'], ['class'=>'text-muted']) ?></td>
                        <td class="text-center" style="white-space:nowrap">
                            <?php if (!$booking['report']) { ?>
                            <span class="text-danger"><?= $cnt ?></span>
                            <?php } else { ?>
                            <span class="text-success"><?= $cnt ?></span>
                            <?php } ?>
                        </td>
                        <td class="text-nowrap">
                            <?= Html::a($booking['product']['tour']['code'], '@web/tours/r/'.$booking['product']['tour']['id'], ['style'=>'background-color:#ffc; color:#060; padding:0 5px;', 'title'=>$booking['product']['title']]) ?>
                            <?php if ($booking['note'] != '') { ?>
                                <i class="fa fa-file-text-o pull-left text-muted popovers"
                                    data-toggle="popover"
                                    data-trigger="hover"
                                    data-title="<?= $booking['product']['title'] ?>"
                                    data-html="true"
                                    data-content="<?= Html::encode($booking['note']) ?>"></i>
                            <?php } ?>
                            <?php if ($booking['finish'] == 'canceled') { ?>
                            <span class="label label-warning">CXL</span>
                            <?php } ?>
                        </td>
                        <td><?= $booking['case']['company']['name'] ?></td>
                        <td class="text-nowrap text-center"><?= date('j/n/Y', strtotime($booking['status_dt'])) ?></td>
                        <td class="text-nowrap text-center"><?= date('j/n/Y', strtotime($booking['product']['day_from'])) ?></td>
                        <td class="text-nowrap text-center"><?= date('j/n/Y', strtotime('+'.($booking['product']['day_count'] - 1).' days', strtotime($booking['product']['day_from']))) ?></td>
                        <td class="text-center"><?= $ngay ?></td>
                        <td class="text-center"><?= $pax ?></td>
                        <td class="text-center"><?= $ngayPax ?></td>
                        <?php if (!$booking['report']) { ?>
                        <td colspan="8" class="text-center text-danger">No data</td>
                        <?php } else { ?>
                        <td class="text-center"><?= $loaiTien ?></td>
                        <td class="text-right"><?= number_format($doanhThu, 0) ?></td>
                        <td class="text-right"><?= number_format($chiPhi, 0) ?></td>
                        <td class="text-right"><?= number_format($doanhThuNgayPax, 2) ?></td>
                        <td class="text-right"><?= number_format($chiPhiNgayPax, 2) ?></td>
                        <td class="text-right" style="<?= $laiGop < 0 ? 'color:red' : '' ?>"><?= number_format($laiGop, 0) ?></td>
                        <!-- <td class="text-right" style="<?= $laiGop < 0 ? 'color:red' : '' ?>"><?= number_format($doanhThuQuyDoi, 0) ?></td> -->
                        <td class="text-right" style="<?= $laiGop < 0 ? 'color:red' : '' ?>"><?= number_format($phanTramLaiGop, 2) ?>%</td>
                        <td class="text-center">
                            <?php if ($booking['report']['note'] != '') { ?>
                                <i class="cursor-pointer fa fa-info-circle" title="<?= $booking['report']['note'] ?>"></i>
                            <?php } ?>
                        </td>
                        <?php } ?>
                        <td>
                            <?= Html::a($booking['case']['name'], '@web/cases/r/'.$booking['case']['id']) ?>
                            <?= $booking['case']['owner']['name'] ?>
                        </td>
                    </tr>
                    <?php
                                if (isset($kq[$booking['case']['owner']['name']])) {
                                    $kq[$booking['case']['owner']['name']]['count'] ++;
                                    $kq[$booking['case']['owner']['name']]['pct'] += $phanTramLaiGop;
                                    $kq[$booking['case']['owner']['name']]['avg'] = $kq[$booking['case']['owner']['name']]['pct'] / $kq[$booking['case']['owner']['name']]['count'];
                                } else {
                                    $kq[$booking['case']['owner']['name']] = [
                                        'avg'=>0,
                                        'count'=>1,
                                        'pct'=>$phanTramLaiGop,
                                    ];
                                }
                            } // if client
                        } // is b2b
                    } // for each bookings
                    ?>
                    <tr>
                        <th colspan="7">Total</th>
                        <th class="text-center"><?= $sumNgay ?></th>
                        <th class="text-center"><?= $sumPax ?></th>
                        <th class="text-center"><?= $sumNgayPax ?></th>
                        <th class="text-center"></th>
                        <th class="text-right"><?= number_format($sumDoanhThu, 0) ?></th>
                        <th class="text-right"><?= number_format($sumChiPhi, 0) ?></th>
                        <th class="text-right"><?= $sumNgayPax == 0 ? 0 : number_format($sumDoanhThu / $sumNgayPax, 2) ?></th>
                        <th class="text-right"><?= $sumNgayPax == 0 ? 0 : number_format($sumChiPhi / $sumNgayPax, 2) ?></th>
                        <!-- <th class="text-right"><?= number_format(5 * ($sumDoanhThu - $sumChiPhi), 0) ?></th> -->
                        <th class="text-right"><?= number_format($sumDoanhThu - $sumChiPhi, 0) ?></th>
                        <th class="text-right"><?= $sumDoanhThu == 0 ? 0 : number_format(100 * ($sumDoanhThu - $sumChiPhi) / $sumDoanhThu, 2) ?>%</th>
                        <th></th>
                        <th></th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <br>

    <div class="card">
        <div class="card-header">Bảng thống kê doanh thu (Series)</div>
        <div class="table-responsive">
            <table class="table table-striped table-narrow table-bordered">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th width="40">OK</th>
                        <th>Tour</th>
                        <th>Hãng</th>
                        <th width="40">Ngày cfm</th>
                        <th width="40">Ngày KH</th>
                        <th width="40">Ngày KT</th>
                        <th width="40" class="text-center">Ngày</th>
                        <th width="40" class="text-center">Pax</th>
                        <th>N*pax</th>
                        <th>Tiền</th>
                        <th>D/thu</th>
                        <th>C/phí</th>
                        <th>Dt/np</th>
                        <th>Cp/np</th>
                        <th>L/gộp</th>
                        <!-- <th>DTQĐ</th> -->
                        <th>P%LG</th>
                        <th>Note</th>
                        <th>HSBH</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    $sumNgay = 0;
                    $sumPax = 0;
                    $sumNgayPax = 0;
                    $sumDoanhThu = 0;
                    $sumChiPhi = 0;
                    $avgDoanhThuNgayPax = 0;
                    $avgChiPhiNgayPax = 0;
                    $sumLaiGop = 0;
                    $sumDoanhThuQuyDoi = 0;
                    $avgPhanTramLaiGop = 0;

                    $cnt = 0;
                    
                    foreach ($theBookings as $booking) {
                        if ((int)$client == 0 || $client == $booking['case']['company_id']) {
                            if ($booking['case']['is_b2b'] == 'yes' && $booking['case']['stype'] == 'b2b-series') {
                                $doanhThuUSD = 0;
                                $chiPhiUSD = 0;
                                if ($booking['report']) {
                                    $ngay = $booking['report']['day_count'];
                                    $pax = $booking['report']['pax_count'];
                                    $ngayPax = $ngay * $pax;
                                    if ($booking['report']['price_unit'] != 'USD') {
                                        $doanhThu = $booking['report']['price'] / $xrateUSD;
                                        $chiPhi = $booking['report']['cost'] / $xrateUSD;
                                        $doanhThuUSD = $booking['report']['price'];
                                        $chiPhiUSD = $booking['report']['cost'];
                                    } else {
                                        $doanhThu = $booking['report']['price'];
                                        $chiPhi = $booking['report']['cost'];
                                    }
                                    $loaiTien = $booking['report']['price_unit'];
                                } else {
                                    $ngay = $booking['product']['day_count'];
                                    $pax = $booking['pax'];
                                    $ngayPax = $ngay * $pax;
                                    $doanhThu = 10;//$booking['price'];
                                    $chiPhi = 10;//rand(890, 1280);
                                    $loaiTien = $booking['currency'];
                                }

                                $doanhThuNgayPax = $ngayPax == 0 ? 0 : $doanhThu / $ngayPax;
                                $chiPhiNgayPax = $ngayPax == 0 ? 0 : $chiPhi / $ngayPax;
                                $laiGop = $doanhThu - $chiPhi;
                                $doanhThuQuyDoi = $laiGop * 5;
                                $phanTramLaiGop = $doanhThu == 0 ? 0 : 100 * $laiGop / $doanhThu;

                                if ($booking['report']) {
                                    $sumNgay += $ngay;
                                    $sumPax += $pax;
                                    $sumNgayPax += $ngayPax;
                                    $sumDoanhThu += $doanhThu;
                                    $sumChiPhi += $chiPhi;
                                }
                                $cnt ++;
                    ?>
                    <tr class="">
                        <td class="text-center"><?= Html::a('<i class="fa fa-edit"></i>', '@web/bookings/report/'.$booking['id'], ['class'=>'text-muted']) ?></td>
                        <td class="text-center" style="white-space:nowrap">
                            <?php if (!$booking['report']) { ?>
                            <span class="text-danger"><?= $cnt ?></span>
                            <?php } else { ?>
                            <span class="text-success"><?= $cnt ?></span>
                            <?php } ?>
                        </td>
                        <td class="text-nowrap">
                            <?= Html::a($booking['product']['tour']['code'], '@web/tours/r/'.$booking['product']['tour']['id'], ['style'=>'background-color:#ffc; color:#060; padding:0 5px;', 'title'=>$booking['product']['title']]) ?>
                            <?php if ($booking['note'] != '') { ?>
                                <i class="fa fa-file-text-o pull-left text-muted popovers"
                                    data-toggle="popover"
                                    data-trigger="hover"
                                    data-title="<?= $booking['product']['title'] ?>"
                                    data-html="true"
                                    data-content="<?= Html::encode($booking['note']) ?>"></i>
                            <?php } ?>
                            <?php if ($booking['finish'] == 'canceled') { ?>
                            <span class="label label-warning">CXL</span>
                            <?php } ?>
                        </td>
                        <td><?= $booking['case']['company']['name'] ?></td>
                        <td class="text-nowrap text-center"><?= date('j/n/Y', strtotime($booking['status_dt'])) ?></td>
                        <td class="text-nowrap text-center"><?= date('j/n/Y', strtotime($booking['product']['day_from'])) ?></td>
                        <td class="text-nowrap text-center"><?= date('j/n/Y', strtotime('+'.($booking['product']['day_count'] - 1).' days', strtotime($booking['product']['day_from']))) ?></td>
                        <td class="text-center"><?= $ngay ?></td>
                        <td class="text-center"><?= $pax ?></td>
                        <td class="text-center"><?= $ngayPax ?></td>
                        <?php if (!$booking['report']) { ?>
                        <td colspan="8" class="text-center text-danger">No data</td>
                        <?php } else { ?>
                        <td class="text-center"><?= $loaiTien ?></td>
                        <td class="text-right"><?= number_format($doanhThu, 0) ?></td>
                        <td class="text-right"><?= number_format($chiPhi, 0) ?></td>
                        <td class="text-right"><?= number_format($doanhThuNgayPax, 2) ?></td>
                        <td class="text-right"><?= number_format($chiPhiNgayPax, 2) ?></td>
                        <td class="text-right" style="<?= $laiGop < 0 ? 'color:red' : '' ?>"><?= number_format($laiGop, 0) ?></td>
<!--                         <td class="text-right" style="<?= $laiGop < 0 ? 'color:red' : '' ?>"><?= number_format($doanhThuQuyDoi, 0) ?></td> -->
                        <td class="text-right" style="<?= $laiGop < 0 ? 'color:red' : '' ?>"><?= number_format($phanTramLaiGop, 2) ?>%</td>
                        <td class="text-center">
                            <?php if ($booking['report']['note'] != '') { ?>
                                <i class="cursor-pointer fa fa-info-circle" title="<?= $booking['report']['note'] ?>"></i>
                            <?php } ?>
                        </td>
                        <?php } ?>
                        <td>
                            <?= Html::a($booking['case']['name'], '@web/cases/r/'.$booking['case']['id']) ?>
                            <?= $booking['case']['owner']['name'] ?>
                        </td>
                    </tr>
                    <?php
                                if (isset($kq[$booking['case']['owner']['name']])) {
                                    $kq[$booking['case']['owner']['name']]['count'] ++;
                                    $kq[$booking['case']['owner']['name']]['pct'] += $phanTramLaiGop;
                                    $kq[$booking['case']['owner']['name']]['avg'] = $kq[$booking['case']['owner']['name']]['pct'] / $kq[$booking['case']['owner']['name']]['count'];
                                } else {
                                    $kq[$booking['case']['owner']['name']] = [
                                        'avg'=>0,
                                        'count'=>1,
                                        'pct'=>$phanTramLaiGop,
                                    ];
                                }
                            } // is b2b
                        } // if client
                    } // for each bookings
                    ?>
                    <tr>
                        <th colspan="7">Total</th>
                        <th class="text-center"><?= $sumNgay ?></th>
                        <th class="text-center"><?= $sumPax ?></th>
                        <th class="text-center"><?= $sumNgayPax ?></th>
                        <th class="text-center"></th>
                        <th class="text-right"><?= number_format($sumDoanhThu, 0) ?></th>
                        <th class="text-right"><?= number_format($sumChiPhi, 0) ?></th>
                        <th class="text-right"><?= $sumNgayPax == 0 ? 0 : number_format($sumDoanhThu / $sumNgayPax, 2) ?></th>
                        <th class="text-right"><?= $sumNgayPax == 0 ? 0 : number_format($sumChiPhi / $sumNgayPax, 2) ?></th>
                        <!-- <th class="text-right"><?= number_format(5 * ($sumDoanhThu - $sumChiPhi), 0) ?></th> -->
                        <th class="text-right"><?= number_format($sumDoanhThu - $sumChiPhi, 0) ?></th>
                        <th class="text-right"><?= $sumDoanhThu == 0 ? 0 : number_format(100 * ($sumDoanhThu - $sumChiPhi) / $sumDoanhThu, 2) ?>%</th>
                        <th></th>
                        <th></th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Hồ sơ mở trong tháng (Request)</div>
        <div class="table-responsive">
            <table class="table table-bordered table-narrow table-striped">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th>Hang</th>
                        <th width="50">Ngay</th>
                        <th>Ten ho so</th>
                        <th>Yeu cau</th>
                        <th>Ban hang</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $cnt = 0;
                    $comp = '';
                    foreach ($monthCases as $case) {
                        if ((int)$client == 0 || $client == $case['company_id']) {
                        $cnt ++;
                    ?>
                    <tr>
                        <td class="text-center text-muted"><?= $cnt ?></td>
                        <td><?
                        if ($case['company']['name'] == '') {
                            echo '( Unknown )';
                        } else {
                            if ($comp != $case['company']['name']) {
                                // Dem so hs cua cong ty nay
                                $compCnt = 0;
                                foreach ($monthCases as $mk) {
                                    if ($mk['company_id'] == $case['company_id']) {
                                        $compCnt ++;
                                    }
                                }
                                $comp = $case['company']['name'];
                                echo $comp;
                                echo '<span class="pull-right badge badge-info">', $compCnt, '</span>';
                            }
                        }
                        ?></td>
                        <td class="text-center"><?= date('j/n', strtotime($case['created_at'])) ?></td>
                        <td>
                            <?= Html::a($case['name'], '/b2b/cases/r/'.$case['id'], ['target'=>'_blank']) ?>
                            <?php if ($case['status'] == 'onhold') { ?><i class="text-warning fa fa-clock-o"></i><?php } ?>
                            <?php if ($case['status'] == 'closed') { ?><i class="text-muted fa fa-lock"></i><?php } ?>
                            <?php if ($case['deal_status'] == 'won') { ?><i class="text-success fa fa-dollar"></i><?php } ?>
                            <?php if ($case['deal_status'] == 'lost' || ($case['status'] == 'closed' && $case['deal_status'] != 'won')) { ?><i class="text-danger fa fa-dollar"></i><?php } ?>
                        </td>
                        <td>
                            <?php if ($case['stats']) { ?>
                            <span class="text-muted">
                                <?= $case['stats']['pax_count'] == '' ? '' : $case['stats']['pax_count'].'p' ?>
                                <?= $case['stats']['day_count'] == '' ? '' : $case['stats']['day_count'].'d' ?>
                                <?= $case['stats']['start_date'] == '' ? '' : $case['stats']['start_date'] ?>
                                <?
                                $cx = explode('|', $case['stats']['req_countries']);
                                foreach ($cx as $c) {
                                    echo '<span class="flag-icon flag-icon-', $c, '"></span> ', strtoupper($c);
                                }
                                ?></span>
                            <?php } ?>
                        </td>
                        <td><?= $case['owner']['name'] ?></td>
                        <td><?= $case['info'] ?></td>
                    </tr>
                    <?php
                    } // if client
                } // foreach
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load("visualization", "1", {packages:["corechart"]});
        google.setOnLoadCallback(drawChart1);
        function drawChart1() {
            var data = google.visualization.arrayToDataTable([
                ['Bán hàng', 'Tỉ lệ lãi gộp'],
                <?
                arsort($kq);
                $cnt = 0;
                foreach ($kq as $k=>$v) {
                    $cnt ++;
                    if ($cnt != 1) {
                        echo ', ';
                    }
                ?>
                ['<?= $k ?>', <?= number_format($v['pct'] / $v['count'], 2) ?>]
                <?
                }
                ?>
            ]);

            var options = {
                hAxis: {title: 'Bán hàng'},
                chartArea:{left:0,top:0,width:"100%",height:"80%"},
                //legend:{position: 'none'},
                //hAxis:{textPosition: 'none'},
                colors:['green'],
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('chart1'));
            chart.draw(data, options);
        }
    </script>
</div>
<style type="text/css">
.popover {max-width:500px;}
.form-control .w-auto {width:auto;}
</style>
<?
$js = <<<TXT
$('.popovers').popover();
$('i.fa.fa-info-circle').on('click', function(){
    alert ($(this).attr('title'));
});
TXT;
$this->registerJs($js);
