<?php

use yii\helpers\Html;
use yii\helpers\Inflector;

?>
<htmlpageheader name="myheader"><img src="/upload/huan/si_header.jpg"/></htmlpageheader>

<div id="content">
    <h3 style="text-align:center;"><?= strtoupper($theTour['bookings'][0]['case']['company']['name']) ?></h3>
    <h1 style="color:#222"><?= $theTour['op_name'] ?></h1>
    <h3><?= Yii::t('si_tour_summary', 'PROGRAM IN BRIEF') ?></h3>
    <table class="table table-bordered _table-condensed">
        <thead>
            <tr>
                <th><?= Yii::t('si_tour_summary', 'Day') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Date') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Itinerary') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Guide & Driver') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Meals') ?></th>
            </tr>
        </thead>
        <tbody>
<?
$dayIdList = explode(',', $theTour['day_ids']);

$cnt = 0;
foreach ($dayIdList as $di) {
    foreach ($theTour['days'] as $ng){
        if ($di == $ng['id']) {
            $ngay = strtotime($theTour['day_from'].' + '.$cnt.'days');
?>
            <tr class="<?= $cnt%2 == 0 ? 'bg0': 'bg1' ?>">
                <td width="30" class="text-center text-muted"><?= ++$cnt ?></td>
                <td><?= Yii::$app->formatter->asDate($ngay, 'php:j/n/Y D') ?></td>
                <td><?= $ng['name'] ?></td>
                <td><?= $ng['guides'] ?></td>
                <td><?= $ng['meals'] ?></td>
            </tr>
<?
        }
    }
}
?>
        </tbody>
    </table>
    <h3><?= Yii::t('si_tour_summary', 'LIST OF TRAVELLERS') ?></h3>
    <table class="table table-bordered _table-condensed">
        <thead>
            <tr>
                <th><?= Yii::t('si_tour_summary', 'No.') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Title') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Full name') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Age') ?></th>
                <!--
                <th><?= Yii::t('si_tour_summary', 'Rooming') ?></th>
                -->
            </tr>
        </thead>
        <tbody>
            <?
            $cnt = 0;
            foreach ($theTour['bookings'] as $booking) {
                foreach ($booking['pax'] as $pax) {
            ?>
            <tr class="<?= $cnt%2 == 0 ? 'bg0': 'bg1' ?>">
                <td width="30" class="text-muted"><?= ++ $cnt ?></td>
                <? if ($theTour['language'] == 'fr') { ?>
                <td><?= $pax['gender'] == 'male' ? 'M.' : 'Mme.' ?></td>
                <? } elseif ($theTour['language'] == 'en') { ?>
                <td><?= $pax['gender'] == 'male' ? 'Mr.' : 'Ms.' ?></td>
                <? } else { ?>
                <td><?= $pax['gender'] == 'male' ? 'Ông' : 'Bà' ?></td>
                <? } ?>
                <td><?= $pax['lname'] ?> <?= $pax['fname'] ?></td>
                <td><?= $pax['byear'] == 0 ? '' : date('Y') - $pax['byear'] ?></td>
                <!--td></td -->
            </tr>
            <?
                }
            }
            ?>
        </tbody>
    </table>

    <h3><?= Yii::t('si_tour_summary', 'ACCOMMODATIONS') ?></h3>
    <table class="table table-bordered _table-condensed">
<? // Gia va cac options
$cnt = 0;
$ctpx = $theTour['prices'];
$ctpx = explode(chr(10), $ctpx);
$unitp = '';
$minp = 99999;
$maxp = 0;
$optcnt = 0;
foreach ($ctpx as $ctp) {
    if (substr($ctp, 0, 7) == 'OPTION:') {
        $optcnt ++;
/*
?>
        <tr class="b-ffc">
            <th colspan="4">Option <?= $optcnt.' : '.trim(substr($ctp, 7)) ?></th>
        </tr>
<?
*/
?>
        <thead>
            <tr>
                <th><?= Yii::t('si_tour_summary', 'Destination') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Hotel/Resort & Website') ?></th>
                <!-- th><?= Yii::t('si_tour_summary', 'No. of nights') ?></th -->
                <th><?= Yii::t('si_tour_summary', 'Room type') ?></th>
            </tr>
        </thead>
        <tbody>
<?
    }
    if (substr($ctp, 0, 2) == '+ ') {
        $line = trim(substr($ctp, 2));
        $line = explode(':', $line);
        for ($i = 0; $i < 4; $i ++) if (!isset($line[$i])) $line[$i] = '';
        $cnt ++;
?>
            <tr class="<?= $cnt%2 == 0 ? 'bg0': 'bg1' ?>">
                <td><?= $line[0] ?></td>
                <td><?= Html::a($line[1], 'http://'.str_replace('http://', '', trim($line[3])), ['target'=>'_blank']) ?></td>
                <td><?= $line[2] ?></td>
            </tr>
<?
    }
}

?> 
    </table>
    <h3><?= Yii::t('si_tour_summary', 'FLIGHTS') ?></h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?= Yii::t('si_tour_summary', 'Route') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Number') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Departure time') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Arrival time') ?></th>
            </tr>
        </thead>
    </table>

    <h3><?= Yii::t('si_tour_summary', 'TRAINS') ?></h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?= Yii::t('si_tour_summary', 'Train route') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Number') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Departure time') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Arrival time') ?></th>
            </tr>
        </thead>
    </table>

    <h3><?= Yii::t('si_tour_summary', 'TOUR GUIDE DETAILS') ?></h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?= Yii::t('si_tour_summary', 'Tour guide') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Contact number') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Service time') ?></th>
            </tr>
        </thead>
        <tbody>
            <?
            $cnt = 0;
            foreach ($theTour['guides'] as $guide) {
                $cnt ++;
                if ($guide['guide_user_id'] == 0) {
                    $guideNameParts = explode('-', $guide['guide_name']);
                    $guideName = $guideNameParts[0] ?? $guide['guide_name'];
                    $guideTel = $guideNameParts[1] ?? '';
                } else {
                    $guideName = $guide['guide']['fname'].' '.$guide['guide']['lname'];
                    $guideTel = $guide['guide']['phone'];
                }
                $guideName = Inflector::transliterate($guideName);
                $dialCode = '(0084) ';
                $guideTel = $dialCode.substr(trim($guideTel), 1);
            ?>
            <tr class="<?= $cnt%2 == 0 ? 'bg0': 'bg1' ?>">
                <td><?= $guideName ?></td>
                <td><?= $guideTel ?></td>
                <td><?= date('j/n/Y', strtotime($guide['use_from_dt'])) ?> - <?= date('j/n/Y', strtotime($guide['use_until_dt'])) ?></td>
            </tr>
            <?
            }
            ?>
        </tbody>
    </table>


<?
$contacts = [
    ['name'=>'Ms Hoa Hong NHUNG', 'phone'=>'(0084) 9 1476 0390'],
    ['name'=>'Mr Nguyen DUC ANH', 'phone'=>'(0084) 9 0454 2880'],
    ['name'=>'Ms Ta Thi Thu HA', 'phone'=>'(0084) 9 1620 4869'],
    ['name'=>'Mr Jonathan MORANA', 'phone'=>'(0084) 12 2294 0707'],
    ['name'=>'Mr Tran Le HIEU', 'phone'=>'(0084) 9 1555 2294'],
    ['name'=>'Secret Indochina office', 'phone'=>'(0084) 4 3266 9052'],
];
?>
    <h3><?= Yii::t('si_tour_summary', 'EMERGENCY CONTACTS') ?></h3>
    <table class="table table-bordered _table-condensed">
        <thead>
            <tr>
                <th><?= Yii::t('si_tour_summary', 'Person in charge') ?></th>
                <th><?= Yii::t('si_tour_summary', 'Contact number') ?></th>
            </tr>
        </thead>
        <tbody>
            <?
            $cnt = 0;
            foreach ($contacts as $contact) {
                $cnt ++;
            ?>
            <tr class="<?= $cnt%2 == 0 ? 'bg0': 'bg1' ?>">
                <td><?= $contact['name'] ?></td>
                <td><?= $contact['phone'] ?></td>
            </tr>
            <?
            }
            ?>
        </tbody>
    </table>
</div>

<htmlpagefooter name="myfooter"><img src="/upload/huan/si_footer.png"/></htmlpagefooter>
