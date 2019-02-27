<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

Yii::$app->params['page_icon'] = 'car';

if ($month == 'next30days') {
    $monthText = 'trong 30 ngày tới';
} elseif ($month == 'last30days') {
    $monthText = 'trong 30 vừa qua';
} else {
    $monthText = 'trong tháng '.date('n/Y', strtotime($month));
}

if ($orderby == 'startdate') {
    $selectText = 'Tour khởi hành ';
} elseif ($orderby == 'enddate') {
    $selectText = 'Tour kết thúc ';
} else {
    $selectText = 'Tour được mở ';
}

Yii::$app->params['page_title'] = $selectText.$monthText;
Yii::$app->params['page_breadcrumbs'] = [
    ['B2B', 'b2b'],
    ['Tours', 'b2b/tours'],
    [$month, 'b2b/tours?month='.$month],
];

$newMonthList = [''=>'Tháng này'];
$newMonthList['next30days'] = '30 ngày tới';
$newMonthList['last30days'] = '30 ngày qua';
foreach ($monthList as $mo) {
    $newMonthList[$mo['ym']] = $mo['ym'].' ('.$mo['total'].')';
    $newMonthList[$mo['ym']] = $mo['ym'];
}

$statusList = [
    'active'=>'Active',
    'canceled'=>'Canceled',
];

$goto = Yii::$app->request->get('goto');
$gotoList = [
    'vn'=>'Vietnam',
    'la'=>'Laos',
    'kh'=>'Cambodia',
    'mm'=>'Myanmar',
    'th'=>'Thailand',
    'cn'=>'China',
    'id'=>'Indonesia',
    'my'=>'Malaysia',
    'my'=>'Philippines',
    '2x'=>'Any 2 countries',
];

$type = Yii::$app->request->get('type');
$typeList = [
    's'=>'Series tours',
    'n'=>'Non-series tours',
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
    <form class="form-inline mb-2">
        <?= Html::dropdownList('orderby', $orderby, ['startdate'=>'Start in', 'enddate'=>'End in', 'created'=>'Created in'], ['class'=>'form-control']) ?>
        <?= Html::dropdownList('month', $month, $newMonthList, ['class'=>'form-control']) ?>
        <?= Html::dropdownList('type', $type, $typeList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Tour type')]) ?>
        <?= Html::dropdownList('status', $status, $statusList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Status')]) ?>
        <?= Html::dropdownList('client', $client, ArrayHelper::map($clientList, 'id', 'name'), ['class'=>'form-control select2', 'prompt'=>Yii::t('x', 'Client')]) ?>
        <?= Html::dropdownList('goto', $goto, $gotoList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Visiting')]) ?>
        <?= Html::dropdownList('seller', $seller, $sellerList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Seller')]) ?>
        <?= Html::dropdownList('operator', $operator, ArrayHelper::map($operatorList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'Operators']) ?>
        <?= Html::dropdownList('cservice', $cservice, ArrayHelper::map($cserviceList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'Customer care']) ?>
        <?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Search in name']) ?>
        <?= Html::textInput('dayname', $dayname, ['class'=>'form-control', 'placeholder'=>'Search in days']) ?>
        <?= Html::dropdownList('view', $view, [''=>'Hide ratings', 'pts'=>'View ratings'], ['class'=>'form-control']) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '?') ?>
        |
        <?= Html::a(Yii::t('x', 'Text only'), '#', ['class'=>'trigger-text-only']) ?>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table id="tourlist" class="table table-narrow table-striped">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">In</th>
                        <th class="text-center">Out</th>
                        <th style="min-width:280px">Code & Name of tour</th>
                        <th class="text-center">Pax</th>
                        <th class="text-center">Days</th>
                        <th>To</th>
                        <th>Seller</th>
                        <th>Operation</th>
                        <th>CR</th>
                        <th>Guides</th>
                        <th>Drivers</th>
                    </tr>
                </thead>
                <tbody>
<?php
$dayIn = '';
$cnt = 0;

foreach ($theTours as $tour) {
    $seriesOK = true;
    if ($type != '') {
        $seriesOK = false;
        foreach ($tour['bookings'] as $booking) {
            if ($booking['case']['stype'] == 'b2b-series') {
                if ($type == 's') {
                    $seriesOK = true;
                    break;
                }
            } else {
                if ($type == 'n') {
                    $seriesOK = true;
                    break;
                }
            }
        }
    }

    $gotoOK = true;
    if ($goto == '2x' && strlen($tour['tourStats']['countries']) != 5) {
        $gotoOK = false;
    }
    if ($goto != '2x' && array_key_exists($goto, $gotoList) && strpos($tour['tourStats']['countries'], $goto) === false) {
        $gotoOK = false;
    }

    $clientOK = true;
    if ((int)$client != 0) {
        foreach ($tour['bookings'] as $booking) {
            if ($booking['case']['company_id'] == (int)$client) {
                // Chi can co 1 client OK la duoc (truong hop nhieu HSBH)
                break;
            } else {
                $clientOK = false;
            }
        }
    }

    $sellerOK = true;
    if ($seller != 0) {
        $sellerOK = false;
        foreach ($tour['bookings'] as $booking) {
            if ($booking['createdBy']['id'] == $seller) {
                $sellerOK = true;
            }
        }
    }

    $operatorOK = true;
    if ($operator != 0 && $tour['tour']['id'] != 0 && !in_array($operator, $staffList[$tour['tour']['id']]['op'])) {
        $operatorOK = false;
    }

    $cserviceOK = true;
    if ($cservice != 0 && $tour['tour']['id'] != 0 && !in_array($cservice, $staffList[$tour['tour']['id']]['cs'])) {
        $cserviceOK = false;
    }

    $dayOK = true;
    if (strlen(trim($dayname)) > 2) {
        $dayOK = false;
        foreach ($tour['days'] as $day) {
            if (strpos(\fURL::makeFriendly($day['name'], '-'), \fURL::makeFriendly($dayname, '-')) !== false) {
                $dayOK = true;
                break;
            }
        }
    }

    // FG
    $fgOK = true;
    if (in_array($fg, ['f', 'g']) && substr($tour['tour']['code'], 0, 1) != strtoupper($fg)) {
        $fgOK = false;
    }

    // Status
    $statusOK = true;
    if (($status == 'active' && $tour['tour']['status'] == 'deleted') || ($status == 'canceled' && $tour['tour']['status'] != 'deleted')) {
        $statusOK = false;
    }

    $ownerOK = true;
    if ($owner != '' && $tour['tour']['owner'] != $owner) {
        $ownerOK = false;
    }

    if ($seriesOK && $gotoOK && $clientOK && $sellerOK && $operatorOK && $cserviceOK && $dayOK && $fgOK && $statusOK && $ownerOK) {
?>
                <tr class="tour-list-item
                    <? foreach ($tour['bookings'] as $booking) echo 'role-se-',$booking['created_by']; ?>
                    <? foreach ($tourCCStaff as $user) { if ($user['tour_id'] == $tour['tour']['id']) {echo ' role-cr-'.$user['id']; }} ?>
                    <? foreach ($tourOperators as $user) { if ($user['tour_id'] == $tour['tour']['id']) {echo ' role-op-'.$user['id']; }} ?>
                    tour <?= $tour['tour']['status'] == 'deleted' ? 'danger' : '' ?>">
                    <td class="text-center text-muted"><?= ++ $cnt ?></td>
                    <td class="text-center"><strong><?
    if ($dayIn != $tour['day_from']) {
        $dayIn = $tour['day_from'];
        $jOrjn = 'j/n';
        if ($orderby == 'startdate' && $month == date('Y-m')) {
            $jOrjn = 'j';
        }
        echo date($jOrjn, strtotime($dayIn));
    }
?></strong>
                    </td>
                    <td class="text-center">
                        <?
                        $jOrjn = 'j/n';
if ($orderby == 'enddate' && $month == date('Y-m')) {
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
                        <?= Html::a($tour['tour']['code'].' - '.$tour['tour']['name'], '@web/tours/r/'.$tour['tour']['id']) ?>
                        <?php if ($tour['op_finish'] == 'prebooked') { ?><span class="small text-warning alpha-orange">[PRE-BOOKED]</span><?php } ?>
                        <?php
                        $isSeriesTour = false;
                        foreach ($tour['bookings'] as $booking) {
                            if ($booking['case']['stype'] == 'b2b-series') {
                                $isSeriesTour = true;
                        ?><span class="text-pink">(SERIES)</span><?
                                break;
                            }
                        }

                        $returning = false;
                        // foreach ($tour['pax'] as $pax) {
                        //     if ($pax['is_repeating'] == 'yes') {
                        //         $returning = true;
                        //         echo '<i title="Returning customer" class="fa fa-refresh text-info"></i>';
                        //         break;
                        //     }
                        // }
                        ?>
                    </td>
                    <td class="text-nowrap text-center">
                        <?
                        $paxCount = 0;
                        foreach ($tour['bookings'] as $booking) {
                            $paxCount += $booking['pax'];
                        }
                        if (count($tour['pax']) == 0) {
                            echo Html::a($paxCount, '/tours/pax/'.$tour['id'], ['target'=>'_blank']);
                        } else {
                        ?>
                        <a class="popovers"
                            target="_blank"
                            href="/tours/pax/<?= $tour['id'] ?>"
                            data-trigger="hover"
                            data-title="<?= $paxCount ?> pax"
                            data-placement="right"
                            data-html="true"
                            data-content="
<?
                echo '<ol>';
                foreach ($tour['pax'] as $pax) {
                    echo '<li>', $pax['name'], ' (', strtoupper($pax['pp_country_code']), ') ', strtoupper(substr($pax['pp_gender'], 0, 1)), '</li>';
                }
                echo '</ol>';
?>
                        "><?= $paxCount ?></a>
                        <?
                        }
                        ?>
                    </td>
                    <td class="text-center">
                        <a class="popovers"
                            href="/tours/services/<?= $tour['tour']['id'] ?>"
                            data-trigger="hover"
                            data-title="<?= $tour['title'] ?>"
                            data-placement="right"
                            data-html="true"
                            data-content="
<?
            $dayIds = explode(',', $tour['day_ids']);
            if (count($dayIds) > 0) {
                $cnt2 = 0;
                echo '<ol>';
                foreach ($dayIds as $id) {
                    foreach ($tour['days'] as $day) {
                        if ($day['id'] == $id) {
                            $dd = date('j/n', strtotime('+ '.$cnt2.' days', strtotime($tour['day_from'])));
                            $cnt2 ++;
                            echo '<li><strong>', $dd, '</strong> ', Html::encode($day['name']), ' <em>', $day['meals'], '</em></li>';
                        }
                    }
                }
                echo '</ol>';
            }
?>
                        "><?= $tour['day_count'] ?></a>
                    </td>
                    <td class="text-nowrap">
                        <?
                        if ($tour['tourStats']['countries'] != '') {
                            $countries = explode(',', $tour['tourStats']['countries']);
                        ?>
                            <span class="text-only"><?= strtoupper($tour['tourStats']['countries']) ?></span>
                        <?
                            foreach ($countries as $country) {
                        ?>
                            <span title="<?= strtoupper($country) ?>" class="img-only flag-icon flag-icon-<?= $country ?>"></span> <?
                            }
                        }
                        ?>
                    <td class="text-nowrap">
<?
    $imgList = [];
    $nameList = [];
    foreach ($tour['bookings'] as $booking) {
        $imgList[] = '<img class="img-only cursor-pointer img-circle role-se" data-userid="'.$booking['created_by'].'" style="width:24px;" title="'.$booking['createdBy']['name'].'" src="/timthumb.php?w=100&h=100&src='.$booking['createdBy']['image'].'">';
        $nameList[] = $booking['createdBy']['name'];
    }
    echo implode(' ', $imgList);
    echo '<div class="text-only">', implode(', ', $nameList), '</div>';
?>
                    </td>
                    <td class="text-nowrap">
<?
    $imgList = [];
    $nameList = [];
    foreach ($tourOperators as $user) {
        if ($user['tour_id'] == $tour['tour']['id']) {
            $imgList[] = '<img title="'.$user['name'].'" src="/timthumb.php?w=100&h=100&src='.$user['image'].'" data-userid="'.$user['id'].'" class="img-only img-circle cursor-pointer role-op" style="width:24px;">';// $user['name'];
            $nameList[] = $user['name'];
        }
    }
    echo implode(' ', $imgList);
    echo '<div class="text-only">', implode(', ', $nameList), '</div>';
?>
                    </td>
                    <td class="text-nowrap">
<?
    $imgList = [];
    $nameList = [];
    foreach ($tourCCStaff as $user) {
        if ($user['tour_id'] == $tour['tour']['id']) {
            $imgList[] = '<img title="'.$user['name'].'" src="/timthumb.php?w=100&h=100&src='.$user['image'].'" data-userid="'.$user['id'].'" class="img-only img-circle cursor-pointer role-cr" style="width:24px;">';// $user['name'];
            $nameList[] = $user['name'];
        }
    }
    echo implode(' ', $imgList);
    echo '<div class="text-only">', implode(', ', $nameList), '</div>';
?>
                    </td>
                    <td>
<?
    $nameList = [];
    if (!empty($tourGuides)) {
        foreach ($tourGuides as $guide) {
            if ($guide['tour_id'] == $tour['id']) {
                if ($view == 'pts') {
                    $nameList[] = ($guide['points'] == 0 ? '<span title="Chưa có điểm HD" class="label label-warning">?</span> ' : '<span title="Điểm HD" class="label label-info">'.$guide['points'].'</span> ').$guide['namephone'];
                } else {
                    $nameList[] = $guide['namephone'];
                }
            }
        }
    }
    if ($view == 'pts') {
        echo implode('<br>', $nameList);
    } else {
        echo USER_ID == 1 ? '' : implode(', ', $nameList);
    }
?>
                    </td>
                    <td>
<?
    $nameList = [];
    if (!empty($tourDrivers)) {
        foreach ($tourDrivers as $driver) {
            if ($driver['tour_id'] == $tour['id']) {
                if ($view == 'pts') {
                    $nameList[] = ($driver['points'] == 0 ? '<span title="Chưa có điểm LX" class="label label-warning">?</span> ' : '<span title="Điểm LX" class="label label-info">'.$driver['points'].'</span> ').$driver['namephone'];
                } else {
                    $nameList[] = $driver['namephone'];
                }
            }
        }
    }
    if ($view == 'pts') {
        echo implode('<br>', $nameList);
    } else {
        echo USER_ID == 1 ? '' : implode(', ', $nameList);
    }
?>
                    </td>
                </tr>
<?
    } // if hidden
} // foreach
?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php

Yii::$app->params['page_small_title'] = '('.$cnt.' tours)';

$js = <<<'TXT'
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
