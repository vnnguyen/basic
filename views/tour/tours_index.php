<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\helpers\DateTimeHelper;

Yii::$app->params['page_icon'] = 'car';

Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'clock-o', 'label'=>'Chạy hôm nay', 'link'=>'tours?orderby=operated&time=today'],
        ['icon'=>'clock-o', 'label'=>'Chạy ngày mai', 'link'=>'tours?orderby=operated&time=tomorrow'],
        ['icon'=>'calendar', 'label'=>'Chạy tháng này', 'link'=>'tours?orderby=operated&time='],
        ['icon'=>'calendar', 'label'=>'Khởi hành tháng này', 'link'=>'tours?orderby=startdate&time='],
        ['icon'=>'calendar', 'label'=>'Mới mở', 'link'=>'tours?orderby=created&time=last30days', 'class'=>'text-pink'],
        ['icon'=>'calendar', 'label'=>'Lịch tour tuần', 'link'=>'tours/calendar', 'class'=>'text-success'],
    ]
];

if ($time == 'today') {
    $timeText = 'hôm nay';
} elseif ($time == 'tomorrow') {
    $timeText = 'ngày mai';
} elseif ($time == 'next7days') {
    $timeText = 'trong 7 ngày tới';
} elseif ($time == 'thisweek') {
    $timeText = 'tuần này';
} elseif ($time == 'last30days') {
    $timeText = 'trong 30 ngày vừa qua';
} else {
    $timeText = 'trong tháng '.date('n/Y', strtotime($time));
    if (strlen($time) == 4) {
        $timeText = 'trong '.$time;
    }
}

if ($orderby == 'startdate') {
    $selectText = 'Tour khởi hành ';
} elseif ($orderby == 'enddate') {
    $selectText = 'Tour kết thúc ';
} elseif ($orderby == 'operated') {
    $selectText = 'Tour chạy ';
} else {
    $selectText = 'Tour được mở ';
}

Yii::$app->params['page_layout'] = '-t';
Yii::$app->params['page_title'] = $selectText.$timeText;
Yii::$app->params['page_breadcrumbs'] = [
    ['Tours', '@web/tours'],
    [$selectText.' '.$timeText],
];

$newMonthList = [''=>Yii::t('x', 'This month')];
$newMonthList['today'] = Yii::t('x', 'Today');
$newMonthList['tomorrow'] = Yii::t('x', 'Tomorrow');
// $newMonthList['thisweek'] = Yii::t('x', 'This week');
$newMonthList['next7days'] = Yii::t('x', 'Next 7 days');
$newMonthList['next30days'] = Yii::t('x', 'Next 30 days');
$newMonthList['last30days'] = Yii::t('x', 'Last 30 days');
$newMonthList = $newMonthList + $monthList;

$statusList = [
    'active'=>Yii::t('x', 'Active tours'),
    'canceled'=>Yii::t('x', 'Canceled tours'),
];

$gotoList = [
    'vn'=>Yii::t('x', 'Vietnam'),
    'vn-n'=>'- '.Yii::t('x', 'Vietnam - North'),
    'vn-c'=>'- '.Yii::t('x', 'Vietnam - Central'),
    'vn-s'=>'- '.Yii::t('x', 'Vietnam - South'),
    'la'=>Yii::t('x', 'Laos'),
    'kh'=>Yii::t('x', 'Cambodia'),
    'mm'=>Yii::t('x', 'Myanmar'),
    'th'=>Yii::t('x', 'Thailand'),
    'cn'=>Yii::t('x', 'China'),
    'id'=>Yii::t('x', 'Indonesia'),
    'my'=>Yii::t('x', 'Malaysia'),
];

$viewList = [
    'startdate'=>Yii::t('x', 'Start date in'),
    'enddate'=>Yii::t('x', 'End date in'),
    'operated'=>Yii::t('x', 'Operated in'),
    'created'=>Yii::t('x', 'Created in')
];

$brandList = [
    'f'=>Yii::t('x', 'F tours'),
    'g'=>Yii::t('x', 'G tours'),
];

?>
<style>
.popover {min-width:500px;}
.fa-male {color:blue;}
.fa-female {color:purple;}
.form-control.w-auto {width:auto; display:inline;}
.text-only {display:none;}
</style>
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <strong class="text-info"><?= Yii::t('x', 'Viewing {count} tours', ['count'=>count($theTours)]) ?></strong>
            &middot;
            <?php if ($orderby != '') { ?><strong><?= $viewList[$orderby] ?? $orderby ?>:</strong> <?= $newMonthList[$time] ?? $time ?>; <?php } ?>
            <?php if ($fg != '') { ?><strong><?= Yii::t('x', 'Brand') ?>:</strong> <?= $brandList[$fg] ?? $fg ?>; <?php } ?>
            <?php if ($status != '') { ?><strong><?= Yii::t('x', 'Status') ?>:</strong> <?= $statusList[$status] ?? $status ?>; <?php } ?>
            <?php if ($departure != '') { ?><strong><?= Yii::t('x', 'Region of departure') ?>:</strong> <?= trim($gotoList[$departure] ?? $departure, '- ') ?>; <?php } ?>
            <?php if ($goto != '') { ?><strong><?= Yii::t('x', 'Destination') ?>:</strong> <?= trim($gotoList[$goto] ?? $goto, '- ') ?>; <?php } ?>
            <?php if ($daycount != '') { ?><strong><?= Yii::t('x', 'Number of days') ?>:</strong> <?= $daycount ?>; <?php } ?>
            <?php if ($paxcount != '') { ?><strong><?= Yii::t('x', 'Number of pax') ?>:</strong> <?= $paxcount ?>; <?php } ?>

            <?php if ($name != '') { ?><strong><?= Yii::t('x', 'Name') ?>:</strong> <?= $name ?>; <?php } ?>
            <?php if ($dayname != '') { ?><strong><?= Yii::t('x', 'Day titles') ?>:</strong> <?= $dayname ?>; <?php } ?>
            <?php if ($seller != '') { ?><strong><?= Yii::t('x', 'Seller') ?>:</strong> <?php
            foreach ($allSellers as $sePerson) {
                if ($seller == $sePerson['id']) {
                    echo $sePerson['name'], '; ';
                    break;
                }
            }

            ?>; <?php } ?>
            <?php if ($operator != '') { ?><strong><?= Yii::t('x', 'Operator') ?>:</strong> <?php
            foreach ($allOperators as $opPerson) {
                if ($operator == $opPerson['id']) {
                    echo $opPerson['name'], '; ';
                    break;
                }
            }
            ?>
            <?php } ?>
            <?php if ($booker != '') { ?><strong><?= Yii::t('x', 'Service booker') ?>:</strong> <?php
            foreach ($allBookers as $opBooker) {
                if ($booker == $opBooker['id']) {
                    echo $opBooker['name'], '; ';
                    break;
                }
            }
            ?>
            <?php } ?>
            <?php if ($cservice != '') { ?><strong><?= Yii::t('x', 'Customer Relations') ?>:</strong> <?php
            foreach ($allCRStaff as $crPerson) {
                if ($cservice == $crPerson['id']) {
                    echo $crPerson['name'], '; ';
                    break;
                }
            }
            ?>
            <?php } ?>

            <?php /* if (in_array(USER_ID, [1, 118, 8162])) { ?>
            <?php if ($owner != '') { ?><strong><?= Yii::t('x', 'Owner') ?>:</strong> <?= $owner ?>; <?php } ?>
            <?php } */ ?>

            <?php if ($guide != '') { ?><strong><?= Yii::t('x', 'Tour guide') ?>:</strong> <?= $guide ?>; <?php } ?>
            <?php if ($driver != '') { ?><strong><?= Yii::t('x', 'Tour driver') ?>:</strong> <?= $driver ?>; <?php } ?>

            <a href="#" class="toggle-filters"><?= Yii::t('x', 'Alter conditions') ?></a>
            <a href="#" class="toggle-filters d-none"><?= Yii::t('x', 'Cancel filters') ?></a>
            &middot;
            <?= Html::a(Yii::t('x', 'Reset all'), '?' ) ?>
            &middot;
            <?= Html::a(Yii::t('x', 'Text only'), '#', ['class'=>'trigger-text-only']) ?>
            <div class="d-none" id="filters">
                <hr>
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <div class="col-sm-3"><?= Html::dropdownList('orderby', $orderby, $viewList, ['class'=>'form-control']) ?></div>
                                <div class="col-sm-9"><?= Html::dropdownList('time', $time, $newMonthList, ['class'=>'form-control']) ?></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3"><?= Yii::t('x', 'Brand') ?></label>
                                <div class="col-sm-9"><?= Html::dropdownList('fg', $fg, $brandList, ['class'=>'form-control', 'prompt'=>'F/G tours']) ?></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3"><?= Yii::t('x', 'Status') ?></label>
                                <div class="col-sm-9"><?= Html::dropdownList('status', $status, $statusList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '- Select -')]) ?></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3"><?= Yii::t('x', 'Region of departure') ?></label>
                                <div class="col-sm-9"><?= Html::dropdownList('departure', $departure, $gotoList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '- Select -')]) ?></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3"><?= Yii::t('x', 'Destination') ?></label>
                                <div class="col-sm-9"><?= Html::dropdownList('goto', $goto, $gotoList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '- Select -')]) ?></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3"><?= Yii::t('x', 'Number of days') ?></label>
                                <div class="col-sm-9"><?= Html::textInput('daycount', $daycount, ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'eg. 10 or 10-15')]) ?></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3"><?= Yii::t('x', 'Number of pax') ?></label>
                                <div class="col-sm-9"><?= Html::textInput('paxcount', $paxcount, ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'eg. 10 or 10-15')]) ?></div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3"><?= Yii::t('x', 'Search in tour name') ?></label>
                                <div class="col-sm-9"><?= Html::textInput('name', $name, ['class'=>'form-control']) ?></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3"><?= Yii::t('x', 'Search in day titles') ?></label>
                                <div class="col-sm-9"><?= Html::textInput('dayname', $dayname, ['class'=>'form-control']) ?></div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-sm-3"><?= Yii::t('x', 'Seller') ?></label>
                                <div class="col-sm-9"><?= Html::dropdownList('seller', $seller, ArrayHelper::map($allSellers, 'id', 'name', 'status'), ['class'=>'form-control', 'prompt'=>Yii::t('x', '- Select -')]) ?></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3"><?= Yii::t('x', 'Operator') ?></label>
                                <div class="col-sm-9"><?= Html::dropdownList('operator', $operator, ArrayHelper::map($allOperators, 'id', 'name', 'status'), ['class'=>'form-control', 'prompt'=>Yii::t('x', '- Select -')]) ?></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3"><?= Yii::t('x', 'Service booker') ?></label>
                                <div class="col-sm-9"><?= Html::dropdownList('booker', $booker, ArrayHelper::map($allOperators, 'id', 'name', 'status'), ['class'=>'form-control', 'prompt'=>Yii::t('x', '- Select -')]) ?></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3"><?= Yii::t('x', 'Customer Relations') ?></label>
                                <div class="col-sm-9"><?= Html::dropdownList('cservice', $cservice, ArrayHelper::map($allCRStaff, 'id', 'name', 'status'), ['class'=>'form-control', 'prompt'=>Yii::t('x', '- Select -')]) ?></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3"><?= Yii::t('x', 'Tour guide') ?></label>
                                <div class="col-sm-9"><?= Html::textInput('guide', $guide, ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Name or phone')]) ?></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3"><?= Yii::t('x', 'Tour driver') ?></label>
                                <div class="col-sm-9"><?= Html::textInput('driver', $driver, ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Name or phone')]) ?></div>
                            </div>
                        </div>
                    </div>
                    <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
                    <?= Html::a(Yii::t('x', 'Cancel filters'), '#', ['class'=>'toggle-filters d-none']) ?>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table id="tourlist" class="table table-narrow">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <?php if ($orderby == 'created') { ?>
                        <th class="text-center" width="50"><?= Yii::t('x', 'Created') ?></th>
                        <?php } ?>
                        <th class="text-center" width="50"><?= Yii::t('x', 'In') ?></th>
                        <th class="text-center" width="50"><?= Yii::t('x', 'Out') ?></th>
                        <th style="min-width:280px"><?= Yii::t('x', 'Tour code & name') ?></th>
                        <th class="text-center">P</th>
                        <th class="text-center">D</th>
                        <th><?= Yii::t('x', 'Dests') ?></th>
                        <th><?= Yii::t('x', 'Sales') ?></th>
                        <th><?= Yii::t('x', 'Operators') ?></th>
                        <th><?= Yii::t('x', 'Bookers') ?></th>
                        <th><?= Yii::t('x', 'C.R.') ?></th>
                        <th><?= Yii::t('x', 'Guides') ?></th>
                        <th><?= Yii::t('x', 'Drivers') ?></th>
                        <th><?= Yii::t('x', 'Note') ?></th>
                    </tr>
                </thead>
                <tbody>
<?php
$dayIn = '';
$cnt = 0;

foreach ($theTours as $tour) { ?>
                <tr class="tour-list-item
                    <? foreach ($tour['bookings'] as $booking) echo 'role-se-',$booking['created_by']; ?>
                    <? foreach ($allCRStaff as $user) { if ($user['tour_id'] == $tour['tour']['id']) {echo ' role-cr-'.$user['id']; }} ?>
                    <? foreach ($allOperators as $user) { if ($user['tour_id'] == $tour['tour']['id']) {echo ' role-op-'.$user['id']; }} ?>
                    <? foreach ($allBookers as $user) { if ($user['tour_id'] == $tour['tour']['id']) {echo ' role-bk-'.$user['id']; }} ?>
                    tour <?= $tour['tour']['status'] == 'deleted' ? 'alpha-danger' : '' ?>">
                    <td class="text-center text-muted"><?= ++ $cnt ?></td>
                    <?php if ($orderby == 'created') { ?>
                    <td class="text-center"><?= DateTimeHelper::convert($tour['tour']['created_dt'], 'j/n') ?></td>
                    <?php } ?>
                    <td class="text-center"><strong>
                    <?
                        if ($dayIn != $tour['day_from']) {
                            $dayIn = $tour['day_from'];
                            $jOrjn = 'j/n';
                            // if ($orderby == 'startdate' && $time == date('Y-m')) {
                            //     $jOrjn = 'j';
                            // }
                            echo date($jOrjn, strtotime($dayIn));
                        }
                    ?>
                    </strong>
                    </td>
                    <td class="text-center">
                        <?php
                        $jOrjn = 'j/n';
                            if ($orderby == 'enddate' && $time == date('Y-m')) {
                                $jOrjn = 'j';
                            }
                        ?>
                        <?= date($jOrjn, strtotime($tour['day_from'].' + '.($tour['day_count'] - 1).'days')) ?>
                    </td>
                    <td>
<?
                        $flag = $tour['language'];
                        if ($tour['language'] == 'en') $flag = 'us';
                        if ($tour['language'] == 'vi') $flag = 'vn';
                        echo '<span class="flag-icon flag-icon-', $flag,'"></span>';
?>
                        <?= $tour['offer_type'] == 'combined2016' ? '<span class="text-uppercase text-light" style="background-color:#cff; color:#148040; padding:0 3px" title="Combined">C</span> ' : ''?>
                        <?= $tour['tour']['status'] == 'deleted' ? '<strong style="color:#c00;">(CXL)</strong> ' : ''?>
                        <?= Html::a($tour['tour']['code'].' - '.$tour['tour']['name'], '@web/tours/'.$tour['id']) ?>
                        <?php if ($tour['op_finish'] == 'prebooked') { ?><span class="small text-warning alpha-orange">[PRE-BOOKED]</span><?php } ?>
                        <?php if ($tour['tour']['status'] == 'draft') { ?><span class="text-warning">(NEW)</span><?php } ?>
<?php
                        if (!empty($tour['client_id']) && $tour['client_series'] != '') {
                        ?><span class="text-pink small">(SERIES)</span><?
                        }

            $dayIds = explode(',', $tour['day_ids']);
            if (count($dayIds) > 0) {
                $cnt2 = 0;
                foreach ($dayIds as $id) {
                    foreach ($tour['days'] as $day) {
                        if ($day['id'] == $id) {
                            $dd = date('j/n', strtotime('+ '.$cnt2.' days', strtotime($tour['day_from'])));
                            $cnt2 ++;
                            if (($time != 'tomorrow' && $dd == date('j/n')) || ($time == 'tomorrow' && $dd == date('j/n', strtotime('tomorrow')))) {
                                echo '<br><span class="text-muted">', $dd, ' ', Html::encode($day['name']), '</span>';
                            }
                        }
                    }
                }
            }
?>
                        <?php
                        // Recommended
                        foreach ($tour['bookings'] as $booking) {
                            if (substr($booking['case']['how_found'], 0, 8) == 'referred') {
                        ?><i title="Referred customer" class="pull-right fa fa-share text-violet"></i><?
                            }
                        }

                        if (!empty($tour['servicesPlus'])) {
                        ?><i title="Service Plus:<? foreach ($tour['servicesPlus'] as $service) {echo "\n".$service['sv'];} ?>" class="pull-right fa fa-heart text-pink"></i><?
                        }

                        if (!empty($tour['presents'])) {
                        ?><i title="Presents:<? foreach ($tour['presents'] as $present) {echo "\n".$present['item']['name'].' x '.number_format($present['qty']);} ?>" class="pull-right fa fa-gift text-slate"></i><?
                        }

                        if (!empty($tour['incidents'])) {
                        ?><i title="Incidents:<? foreach ($tour['incidents'] as $incident) {echo "\n".date('j/n', strtotime($incident['incident_date'])).': '.$incident['name'];} ?>" class="pull-right fa fa-bomb text-danger"></i><?
                        }

                        if (!empty($tour['complaint'])) {
                        ?><i title="Complaints:<? foreach ($tour['complaints'] as $complaint) {echo "\n".date('j/n', strtotime($incident['complaint_date'])).': '.$complaint['name'];} ?>" class="pull-right fa fa-fire text-warning"></i><?
                        }

                        $isReturning = false;
                        $paxCount = 0;
                        $paxList = [];
                        foreach ($tour['bookings'] as $booking) {
                            $paxCount += $booking['pax'];
                            foreach ($booking['people'] as $person) {
                                $paxList[] = $person;
                                foreach ($person['bookings'] as $pbooking) {
                                    if ($pbooking['id'] != $booking['id']) {
                                        if (isset($pbooking['product']) && strtotime($tour['day_from']) > strtotime($pbooking['product']['day_from'])) {
                                            $isReturning = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        if ($isReturning) {
                        ?><i title="Returning customer" class="pull-right fa fa-refresh text-info"></i><?
                        }

                        // Birthdate
                        $hasBirthday = false;
                        foreach ($tour['bookings'] as $booking) {
                            foreach ($booking['people'] as $person) {
                                if ($person['bday'] != 0 && $person['bmonth'] != 0) {
                                    $testDate1 = strtotime(substr($tour['day_from'], 0, 4).'-'.$person['bmonth'].'-'.$person['bday']);
                                    $testDate2 = strtotime(date('Y', strtotime('+'.($tour['day_count'] - 1).' days '.$tour['day_from'])).'-'.$person['bmonth'].'-'.$person['bday']);
                                    $tourStart = strtotime($tour['day_from']);
                                    $tourEnd = strtotime('+'.($tour['day_count'] - 1).' days '.$tour['day_from']);
                                    if ($testDate1 >= $tourStart && $testDate1 <= $tourEnd || $testDate2 >= $tourStart && $testDate2 <= $tourEnd) {
                                        $hasBirthday = true;
                                        break;
                                    }
                                }
                            }
                        }
                        if ($hasBirthday) {
                        ?><i title="Birthday in tour" class="pull-right fa fa-birthday-cake text-orange"></i><?
                        }
                        ?>
                    </td>
                    <td class="text-nowrap text-center">
                        <?php
                        $extraClass = '';
                        if (count($paxList) != $paxCount) {
                            $extraClass = 'text-danger';
                        }
                        if (count($paxList) == 0) {
                            echo Html::a($paxCount, '/tours/'.$tour['id'].'/pax', ['target'=>'_blank', 'class'=>$extraClass]);
                        } else {
                        ?>
                        <a class="popovers <?= $extraClass ?>"
                            target="_blank"
                            href="/tours/<?= $tour['id'] ?>/pax"
                            data-trigger="hover"
                            data-placement="auto"
                            data-title="<?= $paxCount ?> pax"
                            data-html="true"
                            data-content="<ol><?php
                foreach ($paxList as $pax) {
                    echo '<li><span class=\'flag-icon flag-icon-', $pax['country_code'], '\'></span>', Html::encode($pax['name']), ' ', strtoupper(substr($pax['gender'], 0, 1)), '</li>';
                } ?></ol>"><?= $paxCount ?></a><?php
                        } // if paxList = 0 ?>
                    </td>
                    <td class="text-center">
                        <a class="popovers"
                            href="/tours/services/<?= $tour['tour']['id'] ?>"
                            data-trigger="hover"
                            data-placement="auto"
                            data-title="<?= $tour['title'] ?>"
                            data-html="true"
                            data-content="<?php
            $dayIds = explode(',', $tour['day_ids']);
            if (count($dayIds) > 0) {
                $cnt2 = 0;
                echo '<ol>';
                foreach ($dayIds as $id) {
                    foreach ($tour['days'] as $day) {
                        if ($day['id'] == $id) {
                            $dd = date('j/n', strtotime('+ '.$cnt2.' days', strtotime($tour['day_from'])));
                            $cnt2 ++;
                            echo '<li><strong>', $dd, '</strong> ', $dd == date('j/n') ? ' <span class=\'badge badge-info\'>'.Yii::t('x', 'Today').'</span> ' : '', Html::encode($day['name']), ' <em>', $day['meals'], '</em></li>';
                        }
                    }
                }
                echo '</ol>';
            } ?>"><?= $tour['day_count'] ?></a>
                    </td>
                    <td class="text-nowrap">
                        <?php
                        if ($tour['tourStats']['countries'] != '') {
                            $countries = explode(',', $tour['tourStats']['countries']);
                        ?>
                            <span class="text-only"><?= strtoupper($tour['tourStats']['countries']) ?></span>
                        <?php
                            foreach ($countries as $country) {
                        ?>
                            <span title="<?= strtoupper($country) ?>" class="img-only flag-icon flag-icon-<?= $country ?>"></span> <?
                            }
                        }
                        ?>
                    <td class="text-nowrap">
<?php
    $imgList = [];
    $nameList = [];
    foreach ($tour['bookings'] as $booking) {
        $imgList[] = '<img class="img-only cursor-pointer rounded-circle role-se" data-userid="'.$booking['created_by'].'" style="width:24px;" title="'.$booking['createdBy']['name'].'" src="/timthumb.php?w=100&h=100&src='.$booking['createdBy']['image'].'">';
        $nameList[] = $booking['createdBy']['name'];
    }
    echo implode(' ', $imgList);
    echo '<div class="text-only">', implode(', ', $nameList), '</div>';
?>
                    </td>
                    <td class="text-nowrap">
<?php
    $imgList = [];
    $nameList = [];
    foreach ($allOperators as $user) {
        if ($user['tour_id'] == $tour['tour']['id']) {
            $imgList[] = '<img title="'.$user['name'].'" src="/timthumb.php?w=100&h=100&src='.$user['image'].'" data-userid="'.$user['id'].'" class="img-only rounded-circle cursor-pointer role-op" style="width:24px;">';// $user['name'];
            $nameList[] = $user['name'];
        }
    }
    echo implode(' ', $imgList);
    echo '<div class="text-only">', implode(', ', $nameList), '</div>';
?>
                    </td>
                    <td class="text-nowrap">
<?php
    $imgList = [];
    $nameList = [];
    foreach ($allBookers as $user) {
        if ($user['tour_id'] == $tour['tour']['id']) {
            $imgList[] = '<img title="'.$user['name'].'" src="/timthumb.php?w=100&h=100&src='.$user['image'].'" data-userid="'.$user['id'].'" class="img-only rounded-circle cursor-pointer role-bk" style="width:24px;">';// $user['name'];
            $nameList[] = $user['name'];
        }
    }
    echo implode(' ', $imgList);
    echo '<div class="text-only">', implode(', ', $nameList), '</div>';
?>
                    </td>
                    <td class="text-nowrap">
<?php
    $imgList = [];
    $nameList = [];
    foreach ($allCRStaff as $user) {
        if ($user['tour_id'] == $tour['tour']['id']) {
            $imgList[] = '<img title="'.$user['name'].'" src="/timthumb.php?w=100&h=100&src='.$user['image'].'" data-userid="'.$user['id'].'" class="img-only rounded-circle cursor-pointer role-cr" style="width:24px;">';// $user['name'];
            $nameList[] = $user['name'];
        }
    }
    echo implode(' ', $imgList);
    echo '<div class="text-only">', implode(', ', $nameList), '</div>';
?>
                        </td>
                        <td>
<?php
    $nameList = [];
    if (!empty($tourGuides)) {
        foreach ($tourGuides as $guide) {
            if ($guide['tour_id'] == $tour['id']) {
                $nameList[] = ($guide['points'] == 0 ? '<span class=\'badge badge-flat border-warning text-warning\'>?</span> ' : '<span class=\'badge badge-flat border-info text-info\'>'.$guide['points'].'</span> ').$guide['namephone'].' ('.date('j/n', strtotime($guide['use_from_dt'])).'-'.date('j/n', strtotime($guide['use_until_dt'])).')';
            }
        }
    }
?>
                            <a class="popovers badge badge-<?= empty($nameList) ? 'warning' : 'info' ?> badge-pill"
                            href="/tours/guides/<?= $tour['id'] ?>"
                            data-trigger="hover"
                            data-title="<?= Yii::t('x', 'Tour guides') ?>"
                            data-html="true"
                            data-content="<?= empty($nameList) ? Yii::t('x', 'No guides') : implode('<br>', $nameList); ?>"><?= count($nameList) ?></a>
                        </td>
                        <td>
<?php
    $nameList = [];
    if (!empty($tourDrivers)) {
        foreach ($tourDrivers as $driver) {
            if ($driver['tour_id'] == $tour['id']) {
                $nameList[] = ($driver['points'] == 0 ? '<span class=\'badge badge-flat border-warning text-warning\'>?</span> ' : '<span class=\'badge badge-flat border-info text-info\'>'.$driver['points'].'</span> ').$driver['namephone'].' ('.date('j/n', strtotime($driver['use_from_dt'])).'-'.date('j/n', strtotime($driver['use_until_dt'])).')';
            }
        }
    }
?>
                            <a class="popovers badge badge-<?= empty($nameList) ? 'warning' : 'info' ?> badge-pill"
                            href="/tours/drivers/<?= $tour['id'] ?>"
                            data-trigger="hover"
                            data-title="<?= Yii::t('x', 'Tour drivers') ?>"
                            data-html="true"
                            data-content="<?= empty($nameList) ? Yii::t('x', 'No drivers') : implode('<br>', $nameList); ?>"><?= count($nameList) ?></a>
                        </td>
                        <td></td>
                    </tr>
    <?php
    } // foreach tour ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$js = <<<'TXT'
$('.toggle-filters').on('click', function(){
    $('#filters').toggleClass('d-none')
    $('.toggle-filters').toggleClass('d-none')
})


$('img.role-cr').on('click', function(){
    var userid1 = $(this).data('userid')
    var cservice = $('select[name="cservice"]').val();
    resel();
    if (cservice == userid1) {
        $('tr.tour-list-item').show();
    } else {
        $('select[name="cservice"]').val(userid1);
        $('tr.tour-list-item').hide();
        $('tr.tour-list-item.role-cr-' + userid1).show();
    }
    renum();
})

$('img.role-op').on('click', function(){
    var userid2 = $(this).data('userid')
    var operator = $('select[name="operator"]').val();
    resel();
    if (operator == userid2) {
        $('tr.tour-list-item').show();
    } else {
        $('select[name="operator"]').val(userid2);
        $('tr.tour-list-item').hide();
        $('tr.tour-list-item.role-op-' + userid2).show();
    }
    renum();
})

$('img.role-bk').on('click', function(){
    var userid2 = $(this).data('userid')
    var operator = $('select[name="operator"]').val();
    resel();
    if (operator == userid2) {
        $('tr.tour-list-item').show();
    } else {
        $('select[name="operator"]').val(userid2);
        $('tr.tour-list-item').hide();
        $('tr.tour-list-item.role-bk-' + userid2).show();
    }
    renum();
})

$('img.role-se').on('click', function(){
    var userid3 = $(this).data('userid')
    var seller = $('select[name="seller"]').val();
    resel();
    if (seller == userid3) {
        $('tr.tour-list-item').show();
    } else {
        $('select[name="seller"]').val(userid3);
        $('tr.tour-list-item').hide();
        $('tr.tour-list-item.role-se-' + userid3).show();
    }
    renum();
})

$('a.trigger-text-only').on('click', function(){
    $('.text-only').toggle();
    $('.img-only').toggle();
    $('.tour-list-item').toggleClass('text-nowrap');
    return false;
});

function renum() {
    $('tr.tour-list-item:visible').each(function(i){
        $(this).find('td:eq(0)').html(i + 1);
    })
}

function resel() {
    $('select[name="seller"], select[name="operator"], select[name="cservice"]').val('');
}

TXT;
// $this->registerCssFile(DIR.'assets/x-editable_1.5.1/css/bootstrap-editable.css', ['depends'=>'app\assets\MainAsset']);
// $this->registerJsFile(DIR.'assets/x-editable_1.5.1/js/bootstrap-editable.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);

Yii::$app->params['page_small_title'] = '('.number_format($cnt).' tour)';
