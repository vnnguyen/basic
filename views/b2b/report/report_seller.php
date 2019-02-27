<?php

use yii\helpers\Html;

Yii::$app->params['page_title'] = 'B2B Seller Report';
Yii::$app->params['page_breadcrumbs'] = [
    ['B2B', 'b2b'],
    ['Reports', 'b2b/reports'],
    ['Seller'],
];

$result = [];

$sql1 = 'SELECT COUNT(*) AS total, MONTH(created_at) AS mo FROM at_cases WHERE is_b2b="yes" AND stype="b2b" AND YEAR(created_at)=:yr GROUP BY mo ORDER BY mo';
$noRequests = Yii::$app->db->createCommand($sql1, [':yr'=>$year])->queryAll();

$sql2 = 'SELECT COUNT(*) AS total, MONTH(day_from) AS mo FROM at_ct WHERE SUBSTRING(op_code,1,1)="G" AND op_status="op" AND op_finish!="canceled" AND YEAR(day_from)=:yr GROUP BY mo ORDER BY mo';
$noTours = Yii::$app->db->createCommand($sql2, [':yr'=>$year])->queryAll();

$sql3 = 'SELECT b.pax, br.price, br.cost, MONTH(p.day_from) AS mo FROM at_bookings b, at_booking_reports br, at_ct p WHERE p.id=b.product_id AND br.booking_id=b.id AND SUBSTRING(p.op_code,1,1)="G" AND p.op_status="op" AND p.op_finish!="canceled" AND YEAR(p.day_from)=:yr';
$noBookings = Yii::$app->db->createCommand($sql3, [':yr'=>$year])->queryAll();

foreach ($noRequests as $n) {
    $result['noRequests'][$n['mo']] = $n['total'];
}
foreach ($noTours as $n) {
    $result['noTours'][$n['mo']] = $n['total'];
}
foreach ($noBookings as $n) {
    if (!isset($result['noPax'][$n['mo']])) {
        $result['noPax'][$n['mo']] = $n['pax'];
    } else {
        $result['noPax'][$n['mo']] += $n['pax'];
    }
    if (!isset($result['totalPrice'][$n['mo']])) {
        $result['totalPrice'][$n['mo']] = $n['price'];
    } else {
        $result['totalPrice'][$n['mo']] += $n['price'];
    }
    if (!isset($result['totalCost'][$n['mo']])) {
        $result['totalCost'][$n['mo']] = $n['cost'];
    } else {
        $result['totalCost'][$n['mo']] += $n['cost'];
    }
}

$sellerList = [1087=>'Hà Đoàn', 35030=>'Ly Dương', 11724=>'Nhung Hoa'];
$yearList = [2015=>2015, 2016=>2016, 2017=>2017, 2018=>2018];
$monthList = [1=>1, 2=>2, 3=>3, 4=>4, 5=>5, 6=>6, 7=>7, 8=>8, 9=>9, 10=>10, 11=>11, 12=>12];

?>
<div class="col-md-12">
    <form class="form-inline form-search">
        Bán hàng: <?= Html::dropdownList('seller', $seller, $sellerList, ['class'=>'form-control', 'prompt'=>'- Select -']) ?>
        Năm: <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control']) ?>
        Tháng: <?= Html::dropdownList('month', $month, $monthList, ['class'=>'form-control']) ?>

        <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
    </form>
    <br>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Hồ sơ Request</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-narrow table-bordered table-striped">
                <thead>
                    <th>Chỉ số \ Năm <?= $year ?> - Tháng</th>
                    <?php for ($mo = 1; $mo <= 12; $mo ++) { ?>
                    <th class="text-center" width="6%"><?= $mo ?></th>
                    <?php } ?>
                </thead>
                <tbody>
                    <tr>
                        <th>Số request</th>
                        <?php for ($mo = 1; $mo <= 12; $mo ++) { ?>
                        <td class="text-center"><?= $result['noRequests'][$mo] ?? '' ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <th>Số tour</th>
                        <?php for ($mo = 1; $mo <= 12; $mo ++) { ?>
                        <td class="text-center"><?= $result['noTours'][$mo] ?? '' ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <th>Số khách</th>
                        <?php for ($mo = 1; $mo <= 12; $mo ++) { ?>
                        <td class="text-center"><?= $result['noPax'][$mo] ?? '' ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <th>Doanh thu</th>
                        <?php for ($mo = 1; $mo <= 12; $mo ++) { ?>
                        <td class="text-center"><?= number_format($result['totalPrice'][$mo] ?? 0) ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <th>Lãi gộp</th>
                        <?php for ($mo = 1; $mo <= 12; $mo ++) { ?>
                        <td class="text-center"><?= number_format($result['totalCost'][$mo] ?? 0) ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <th>% lãi gộp</th>
                        <?php for ($mo = 1; $mo <= 12; $mo ++) { ?>
                        <td class="text-center"><?
                        if (isset($result['totalPrice'][$mo], $result['totalCost'][$mo]) && $result['totalPrice'][$mo] != 0) {
                            echo number_format(100 * ($result['totalPrice'][$mo] - $result['totalCost'][$mo]) / $result['totalPrice'][$mo], 2).'%';
                        }
                        ?></td>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>