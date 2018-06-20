<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

Yii::$app->params['page_title'] = 'Các tour dùng dịch vụ của: '.$theVenue['name'];
Yii::$app->params['page_small_title'] = '(Page Under Construction)';
Yii::$app->params['page_icon'] = 'car';
Yii::$app->params['page_breadcrumbs'] = [
    ['Dịch vụ', 'venues'],
    [$theVenue['name'], 'venues/r/'.$theVenue['id']],
    ['Danh sách tour']
];

$startDateList = [];
foreach ($theTours as $tour) {
    $startDateList[$tour['id']] = $tour['product']['day_from'];
}
if (!empty($startDateList)) {
    asort($startDateList);
}

$dvTypeList = [
    'a'=>'Accommodations',
    'm'=>'Meals',
    'g'=>'Tour guides',
    'o'=>'Other',
];

$viewList = [];
for ($y = date('Y') + 1; $y >= 2007; $y --) {
    $yearList[$y] = $y;
}
for ($m = 12; $m >= 1; $m --) {
    $monthList[$m] = $m;
}

function rtrim0($s) {
    return rtrim(rtrim($s, '0'), '.');
}

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <form class="form-inline">
                <?= Html::hiddenInput('ks', $theVenue['id']) ?>
                <?= Html::dropdownList('type', $type, $dvTypeList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Service type')]) ?>
                <?= Html::dropdownList('view', $view, $viewList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Service date')]) ?>
                <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Year')]) ?>
                <?= Html::dropdownList('month', $month, $monthList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Month')]) ?>
                <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-default']) ?>
                <?= Html::a(Yii::t('x', 'Reset'), '/tools/tour-ks?ks='.$theVenue['id']) ?>
                |
                Hoặc xem: <?= Html::a(Yii::t('x', 'View pax list'), '/tools/tour-pax-ks?ks='.$theVenue['id']) ?>
                |
                <a href="/tours/nhadan?venue=<?= $theVenue['id'] ?>&view=year">Xem trên lịch</a>
            </form>
        </div>
        <?php if ($theVenue['id'] == 2301) { ?>
        <div class="panel-body">
            <span class="text-danger">NOTE: Mekong Home (Bến Tre) do đơn vị tính giá theo hợp đồng là pax (không phải là phòng) nên số phòng đêm không chính xác</span>
        </div>
        <?php } ?>
        <? if (empty($theTours)) { ?>
        <div class="panel-body">
            <div class="text-danger"><?= Yii::t('x', 'No data found.') ?></div>
        </div>
        <? } else { ?>
    <div class="table-responsive">
        <table class="table table-bordered table-condensed table-striped">
            <thead>
                <tr>
                    <th class="text-center">TT</th>
                    <th>Tour code, tên, trạng thái</th>
                    <th class="text-center">Số pax</th>
                    <?php if ($type == 'a') { ?>
                    <th class="text-center">Số đêm</th>
                    <th class="text-center">Số phòng đêm</th>
                    <?php } ?>
                    <th>Chi tiết các dv/cpt</th>
                </tr>
            </thead>
            <tbody>
                <?
                $tourCount = 0;
                $paxCount = 0;
                $roomCount = 0;
                $nightCount = 0;
                $roomNightCount = 0;
                $totalPrice = 0;
                foreach ($startDateList as $tourId=>$startDate) {
                    $tour = $theTours[$tourId];
                // }
                // foreach ($theTours as $tour) {

                    $noPax = 0;
                    $noRooms = 0;
                    $noNights = 0;
                    $noRoomNights = 0;
                    $price = 0;

                    // Tinh so dem khach san
                    $nights = [];
                    $rooms = [];
                    $names = '';
                    foreach ($tour['cpt'] as $cpt) {
                        if (1 ||
                            (($theVenue['stype'] == 'hotel' || $theVenue['stype'] == 'home') && (
                            strpos($cpt['dvtour_name'], "hách sạn") !== false
                            || strpos($cpt['dvtour_name'], "hà dân") !== false
                            || strpos($cpt['dvtour_name'], "commodation") !== false
                            || strpos($cpt['dvtour_name'], "otel") !== false
                            || strpos($cpt['dvtour_name'], "uest house") !== false
                            || strpos($cpt['dvtour_name'], "uesthouse") !== false
                            || strpos($cpt['dvtour_name'], "stay") !== false
                            ))
                            ||
                            (($theVenue['stype'] == 'cruise') && (
                            strpos($cpt['dvtour_name'], "Boat") !== false
                            || strpos($cpt['dvtour_name'], "ngủ đêm") !== false
                            || strpos($cpt['dvtour_name'], "àu Hạ Long") !== false
                            || strpos($cpt['dvtour_name'], "àu Cát Bà") !== false
                            || strpos($cpt['dvtour_name'], "àu Cái Bè") !== false
                            || strpos($cpt['dvtour_name'], "hách sạn") !== false
                            || strpos($cpt['dvtour_name'], "hà dân") !== false
                            || strpos($cpt['dvtour_name'], "commodation") !== false
                            || strpos($cpt['dvtour_name'], "otel") !== false
                            || strpos($cpt['dvtour_name'], "uest house") !== false
                            || strpos($cpt['dvtour_name'], "uesthouse") !== false
                            || strpos($cpt['dvtour_name'], "stay") !== false
                            ))
                                ) {

                            if (!isset($nights[$cpt['dvtour_day']])) {
                                $nights[$cpt['dvtour_day']] = $cpt['qty'];
                            } else {
                                $nights[$cpt['dvtour_day']] += $cpt['qty'];
                            }

                        }
                            $names .= $cpt['dvtour_day'].' ['.$cpt['tmp_type'].'] '.$cpt['dvtour_name'].': '.rtrim0(number_format($cpt['qty'], 2)).' x '.$cpt['unit'].chr(10);

                    }

                    if (!empty($nights)) {
                        foreach ($tour['product']['bookings'] as $booking) {
                            $noPax += $booking['pax'];
                        }
                        $tourCount ++;
                    }

                    $noNights = count($nights);

                    $calls = [];
                    $lastNight = '';

                    foreach ($nights as $night=>$room) {
                        $noRooms = max($noRooms, $room);
                        $noRoomNights += $room;

                        if ($lastNight == '') {
                            $calls[] = [
                                'nights'=>1,
                                'from'=>date('j/n', strtotime($night)),
                                'until'=>date('j/n', strtotime($night)),
                            ];
                        } else {
                            if (date('Y-m-d', strtotime('-1 day '.$night)) == $lastNight) {
                                $calls[count($calls) - 1]['nights'] ++;
                                $calls[count($calls) - 1]['until'] = date('j/n', strtotime($night));
                            } else {
                                $calls[] = [
                                    'nights'=>1,
                                    'from'=>date('j/n', strtotime($night)),
                                    'until'=>date('j/n', strtotime($night)),
                                ];
                            }
                        }
                        $lastNight = $night;
                    }

                    $paxCount += $noPax;
                    $roomCount += $noRooms;
                    $nightCount += $noNights;
                    $roomNightCount += $noRoomNights;

                    //??
                    $noPax = 0;
                        foreach ($tour['product']['bookings'] as $booking) {
                            $noPax += $booking['pax'];
                        }


                ?>
                <tr>
                    <td class="text-center"><?= $tourCount ?></td>
                    <td class="text-nowrap">
                        <?= $tour['status'] == 'deleted' ? '<span class="text-danger">(CXL)</span>' : '' ?>
                        <?= Html::a($tour['code'], '@web/tours/r/'.$tour['id']) ?> 
                        <?= $tour['name'] ?>
                        <span class="text-muted"><?= $noPax ?>p <?= $tour['product']['day_count'] ?>d <?= date('j/n', strtotime($tour['product']['day_from'])) ?></span>
                    </td>
                    <?php if ($type == 'a') { ?>
                    <td class="text-center"><?= rtrim(rtrim(number_format($noPax, 2), '0'), '.') ?></td>
                    <td class="text-center"><?= rtrim(rtrim(number_format($noNights, 2), '0'), '.') ?></td>
                    <?php } ?>
                    <td class="text-center"><?= rtrim(rtrim(number_format($noRoomNights, 2), '0'), '.') ?></td>
                    <td>
                        <i title="<?= $names ?>" class="fa fa-info-circle text-muted"></i>
                    <?
                    if (!empty($calls)) {
                    ?><i title="<?= $names ?>" class="fa fa-info-circle text-muted"></i>
                    <?
                        foreach ($calls as $call) {
                            echo $call['from'];
                            if ($call['from'] != $call['until']) {
                                echo '-', $call['until'];
                            }
                            echo ' (', $call['nights'], ') &nbsp;';
                        }
                    }
                    ?>
                    </td>
                </tr>
                <?
                }
                ?>
                <tr>
                    <th></th>
                    <th>TOTAL</th>
                    <th class="text-center"><?= number_format($paxCount) ?></th>
                    <?php if ($type == 'a') { ?>
                    <th class="text-center"><?= number_format($nightCount) ?></th>
                    <th class="text-center"><?= number_format($roomNightCount) ?></th>
                    <?php } ?>
                    <th></th>
                </tr>
            </tbody>
        </table>
        </div>
    <? } // if empty ?>
    </div>
<? if (USER_ID == 1) {
    // \fCore::expose($theTours);
}
?>

</div>