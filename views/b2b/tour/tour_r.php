<?
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\HtmlPurifier;
use app\helpers\DateTimeHelper;

$theTourOld = $theTour['tour'];
include_once('_tours_inc.php');

// TODO old files kkc_files

$allFileList = [];

$tourPaxCount = [];
foreach ($theTour['bookings'] as $booking) {
    $tourPaxCount[] = $booking['pax'];
}

if ($theTour['offer_type'] == 'combined2016') {
    Yii::$app->params['page_title'] .= '<span class="text-uppercase text-light" style="background-color:#cff; padding:0 3px; color:#148040;">Combined</span> ';
}

Yii::$app->params['page_title'] .= $theTour['tour']['code'].' - '.$theTour['tour']['name'].' - ';
Yii::$app->params['page_small_title'] = implode('+', $tourPaxCount).'p '.$theTour['day_count'].'d ';
if (substr($theTour['day_from'], 0, 4) == date('Y')) {
    Yii::$app->params['page_small_title'] .= date('j/n', strtotime($theTour['day_from']));
} else {
    Yii::$app->params['page_small_title'] .= date('j/n/Y', strtotime($theTour['day_from']));
}

if ($theTour['day_from'] == date('Y-m-d')) {
    Yii::$app->params['page_small_title'] .= ' ('.Yii::t('app', 'Today').')';
} else {
    Yii::$app->params['page_small_title'] .= ' ('.Yii::$app->formatter->asRelativeTime($theTour['day_from']).')';
}

Yii::$app->params['page_meta_title'] = $theTour['tour']['code'].' - '.$theTour['tour']['name'];


if ($theTour['tourStats']['countries'] != '') {
    $countries = explode(',', $theTour['tourStats']['countries']);
    foreach ($countries as $country) {
        Yii::$app->params['page_small_title'] .= ' <span title="'. strtoupper($country) .'" class="flag-icon flag-icon-'.$country.'"></span>';
    }
}

Yii::$app->params['page_icon'] = 'car';
Yii::$app->params['page_breadcrumbs'] = [
    ['Tours', 'tours'],
    [substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
    [$theTour['tour']['code'], 'tours/r/'.$theTour['tour']['id']],
];

$jsPeopleList = '';
foreach ($thePeople as $person) {
    $jsPeopleList .= "{key:'[".$person['nickname']."]', name:'".$person['nickname']."', nname:'".str_replace('.', '', strstr($person['email'], '@', true)).str_replace(['-', '_', ' '], ['', '', ''], \fURL::makeFriendly($person['fname'].$person['lname']))."', email:'".$person['email']."'},";
}
$jsPeopleList = trim($jsPeopleList, ',');

// Calculate the time of notes and emails
$myTimeZone = Yii::$app->user->identity->timezone;
if (!in_array($myTimeZone, ['UTC', 'Europe/Paris', 'Asia/Ho_Chi_Minh'])) {
    $myTimeZone = 'Asia/Ho_Chi_Minh';
}

$timeTable = [];
foreach ($theNotes as $note) {
    $time = DateTimeHelper::convert($note['co'], 'Y-m-d H:i:s', 'UTC', $myTimeZone);
    $timeTable[$time] = ['object'=>'note', 'id'=>$note['id'], 'title'=>$note['title']];
}
foreach ($theSysnotes as $note) {
    $time = DateTimeHelper::convert($note['created_at'], 'Y-m-d H:i:s', 'UTC', $myTimeZone);
    $timeTable[$time] = ['object'=>'sysnote', 'id'=>$note['id'], 'title'=>$note['action']];
}
foreach ($inboxMails as $mail) {
    $time = DateTimeHelper::convert($mail['created_at'], 'Y-m-d H:i:s', 'UTC', $myTimeZone);
    $timeTable[$time] = ['object'=>'mail', 'id'=>$mail['id'], 'title'=>$mail['subject']];
}
krsort($timeTable);

// File list
foreach ($timeTable as $time=>$item) {
    $time = substr($time, 0, 16);
    if ($item['object'] == 'note') {
        foreach ($theNotes as $note) {
            if ($note['id'] == $item['id']) {
                if ($note['files']) {
                    foreach ($note['files'] as $file) {
                        $allFileList[] = [
                            'name'=>$file['name'],
                            'link'=>'@web/files/r/'.$file['id'],
                            'size'=>$file['size'],
                        ];
                    }
                }
            }
        }
    }
    if ($item['object'] == 'mail') {
        foreach ($inboxMails as $mail) {
            if ($mail['id'] == $item['id']) {
                if ($mail['attachment_count'] > 0 && $mail['files'] != '') {
                    $mail['files'] = unserialize($mail['files']);
                    foreach ($mail['files'] as $file) {
                        $allFileList[] = [
                            'name'=>$file['name'],
                            'link'=>'@web/mails/f/'.$mail['id'].'?name='.urlencode($file['name']),
                            'size'=>$file['size'],
                        ];
                    }
                }
            }
        }
    }
}

?>
<style>
body {background-color:#fff;}
</style>
<div class="col-md-12">
    <? if ($theTour['tour']['status'] == 'draft') { ?>
    <div class="alert alert-warning"><?= Yii::t('op', 'This tour has not been confirmed by Operation Department') ?></div>
    <? } ?>
    <? if ($theTour['op_finish'] == 'canceled') { ?>
    <div class="alert alert-danger"><?= Yii::t('op', 'This tour has been canceled') ?></div>
    <? } ?>
</div>
<div class="col-md-12">
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class=""><a href="/products/r/<?= $theTour['id'] ?>"><?= Yii::t('op', 'Product') ?></a></li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= Yii::t('op', 'Sales') ?> <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                <li role="presentation" class="dropdown-header">CASES</li>
<?
foreach ($theTour['bookings'] as $booking) {
?>
                <li class=""><a role="menuitem" href="<?= DIR ?>cases/r/<?= $booking['case']['id'] ?>"><?= $booking['case']['name'] ?></a></li>
<?
}
?>
                <li role="presentation" class="divider"></li>
                <li role="presentation" class="dropdown-header">BOOKINGS</li>
                <li class=""><a role="menuitem" href="">Proposals &amp; Bookings</a></li>
                <li class=""><a role="menuitem" href="">Invoices</a></li>
                <li class=""><a role="menuitem" href="">Payments</a></li>
            </ul>
        <li>
        <li class="active"><a href="/tours/r/<?= $theTour['tour']['id'] ?>"><?= Yii::t('op', 'Operation') ?></a></li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= Yii::t('op', 'Test menu') ?> <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu" aria-labelledby="xxx">
                <li role="presentation" class="dropdown-header">PRODUCT</li>
                <li class=""><a role="menuitem" href="">Product Overview</a></li>
                <li class=""><a role="menuitem" href="">Itinerary</a></li>
                <li class=""><a role="menuitem" href="">Prices</a></li>
                <li class=""><a role="menuitem" href="">Files &amp; Notes</a></li>
                <li role="presentation" class="divider"></li>
                <li role="presentation" class="dropdown-header">SALES</li>
                <li><a href="/products/sb/32946">Sales Overview</a></li>
                <li><a href="/bookings?product_id=32946">Bookings</a></li>
                <li class=""><a role="menuitem" href="">People</a></li>
                <li class=""><a role="menuitem" href="">Payments</a></li>
                <li role="presentation" class="divider"></li>
                <li role="presentation" class="dropdown-header">OPERATIONS</li>
                <li><a href="/products/op/32946">Operation Overview</a></li>
                <li class=""><a role="menuitem" href="">Service costs</a></li>
                <li class=""><a role="menuitem" href="">Customers</a></li>
                <li class=""><a role="menuitem" href="">Feedback</a></li>
                <li class=""><a role="menuitem" href="">Files &amp; Notes</a></li>
            </ul>
        </li>
    </ul>
</div>
<div class="col col-1">
    <div class="row">
        <div class="col col-1-1">
            <div class="panel panel-primary">
                <table class="table table-condensed">
                    <tbody>
                        <? if ($theTour['tour']['pax_ratings'] != 0) { ?>
                        <tr>
                            <td><strong class="text-success"><?= $theTour['tour']['pax_ratings'] == 100 ? 10 : trim(number_format($theTour['tour']['pax_ratings'] / 10, 1), '.0') ?></strong>/10 (rated by pax)</td>
                        </tr>
                        <? } ?>
                        <tr>
                            <td>
                                <strong>BH</strong>
<?
$nameList = [];
foreach ($theTour['bookings'] as $booking) {
    $nameList[] = Html::a($booking['case']['owner']['nickname'], '@web/users/r/'.$booking['case']['owner']['id']);
}
echo implode(', ', $nameList);
?>
                                <strong>ĐH</strong>
<?
$nameList = [];
foreach ($tourOperators as $user) {
    $nameList[] = Html::a($user['nickname'], '@web/users/r/'.$user['id']);
}
echo implode(', ', $nameList);
?>
                                <strong>QH</strong>
<?
$nameList = [];
foreach ($tourCSStaff as $user) {
    $nameList[] = Html::a($user['nickname'], '@web/users/r/'.$user['id']);
}
echo implode(', ', $nameList);

?>
                            </td>
                        </tr>
<?
if (!empty($tourAgents)) {
    foreach ($tourAgents as $company) {
?>
                        <tr>
                            <td><?= Html::a($company['name'], '@web/companies/r/'.$company['id']) ?>
<?
        if ($company['image'] != '') {
?>
                                <div><?= Html::img($company['image'], ['class'=>'img-responsive', 'style'=>'max-height:100px;']) ?></div>
<?
        }
?>
                            </td>
                        </tr>
<?
    }
}


$birthDays = [];
$theTour['day_until'] = date('Y-m-d', strtotime('+'.($theTour['day_count'] - 1).' days', strtotime($theTour['day_from'])));
foreach ($tourPax as $p) {
    if (!in_array(0, [$p['bday'], $p['bmonth'], $p['byear']])) {
        $bd = date_create_from_format('j/n/Y', $p['bday'].'/'.$p['bmonth'].'/'.substr($theTour['day_until'], 0, 4));
        $bd = strtotime($bd->format('Y-m-d'));
        if (strtotime($theTour['day_from']) <= $bd && $bd <= strtotime($theTour['day_until'])) {
            $birthDays[] = '<span class="label label-danger"><i class="fa fa-birthday-cake"></i> '.$p['bday'].'/'.$p['bmonth'].'</span> '.Html::a($p['name'], '@web/users/r/'.$p['id']);
        }
    }
}/*
if (!empty($otherTours)) {
    foreach ($otherTours as $li) {
        // $tourNotes .= '<a href="/tours/r/'.$li['id'].'" class="label label-info"><i class="icon-white icon-truck"></i> Also '.$li['code'].'</a> ';
    }
}*/
if (!empty($birthDays) || !empty($olderTours)) {
?>
                        <tr>
                            <td>
<?
                                if (!empty($birthDays)) {
                                    echo implode(', ', $birthDays);
                                }
                                if (!empty($olderTours)) {
                                    echo '<div><span class="label label-info">Past tour(s)</span> ';
                                    foreach ($olderTours as $tour) {
                                        echo Html::a($tour['code'], '@web/tours/r/'.$tour['id']);
                                        if ($tour['status'] == 'deleted') {
                                            echo '<span class="text-danger">(CXL)</span>';
                                        }
                                        echo ' ';
                                    }
                                    echo '</div>';
                                }
?>

                            </td>
                        </tr>
<?
} // if tour notes

if (!empty($tourRefs)) {
?>
                        <tr>
                            <td>
<?
    foreach ($tourRefs as $r) {
        /*
                $q = $db->query('SELECT id, code, name FROM at_tours t, at_pax p WHERE t.id=p.tour_id AND p.user_id=%i LIMIT 1', $r['user_id']);
                if ($q->countReturnedRows() > 0) {
                    $refTour = $q->fetchRow();
                } else {
                    $refTour = false;
                }
                */
        echo 'Ref. ', Html::a($r['name'], '@web/users/r/'.$r['user_id']);
        // TODO also tour code
    }
} // if empry ref
?>
                            </td>
                        </tr>
                        <tr>
                            <td>
<?
foreach ($theTour['bookings'] as $booking) {
    foreach ($booking['invoices'] as $invoice) {
        $paymentStatusText = 'UNPAID';
        $paymentStatusColor = '';
        if ($invoice['payment_status'] == 'paid') {
            $paymentStatusText = 'PAID';
            $paymentStatusColor = 'green';
        } else {
            if (strtotime($invoice['due_dt']) < strtotime('today')) {
                $paymentStatusText = 'OVERDUE';
                $paymentStatusColor = 'red';
            }
        }
?>
                                        <div class="row" style="color:<?= $paymentStatusColor ?>">
                                            <div class="col-xs-6" title="<?= $invoice['bill_to_name'] ?>">$ <?= Html::a($invoice['ref'], '@web/invoices/r/'.$invoice['id']) ?></div>
                                            <div class="col-xs-6 text-right text-nowrap" title="<?= $paymentStatusText ?>"><?= $invoice['stype'] == 'credit' ? '-' : '' ?><?= number_format($invoice['amount'], 2) ?> <span><?= $invoice['currency'] ?></span></div>
                                        </div>
<?
    }
}
?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="text-bold text-uppercase"><?= Yii::t('op', 'Itinerary') ?></span> <?= $theTour['day_count'] ?> <?= Yii::t('op', 'days') ?>, <?= Yii::t('op', 'from') ?> <?= date('j/n/Y', strtotime($theTour['day_from'])) ?> <?= Html::a(Yii::t('op', 'Itinerary'), '@web/products/r/'.$theTour['id']) ?> - <?= Html::a(Yii::t('op', 'Notes'), '@web/tours/ctn/'.$theTour['id']) ?>
                </div>
                <table class="table table-xxs">
                    <tbody>
<?
            $dayIds = explode(',', $theTour['day_ids']);
            if (count($dayIds) > 0) {
                $cnt2 = 0;
                foreach ($dayIds as $id) {
                    foreach ($theTour['days'] as $day) {
                        if ($day['id'] == $id) {
                            $date = strtotime('+ '.$cnt2.' days', strtotime($theTour['day_from']));
                            $dd = date('d', $date);
                            $jn = date('j/n', $date);
                            $cnt2 ++;
                            $thisDay = date('Y-m-d', $date);
                            $thisDow = date('D', $date);
?>
                        <tr>
                            <td class="text-right text-muted valign-top" width="10"><small><?= $cnt2 ?></small></td>
                            <td class="no-padding-left">
                                <div class="day-title" data-id="<?= $day['id'] ?>" title="Click to view detail">
                                <strong style="<?= $thisDow == 'Sun' ? 'color:#c00' : '' ?>"><?= $jn ?></strong>
                                <? if (date('Y-m-d') == $thisDay) { ?><span class="label label-success"><?= Yii::t('op', 'Today') ?></span> <? } ?>
                                <?= Html::encode($day['name']) ?>
                                <em><?= $day['meals'] ?></em>
                                </div>
<?
// Old
// foreach ($tourGuides as $guide) {
    //if ($guide['day'] == $thisDay) {
        //echo '<div style="font-size:90%; color:#fb8c00"><i class="fa fa-user"></i> ', $guide['fname'], ' ', $guide['lname'], ' - ', $guide['uphone'], '</div>';
    //}
// }

// Drivers

foreach ($tourGuides as $guide) {
    if (strtotime(substr($guide['use_from_dt'], 0, 10)) <= $date && $date <= strtotime(substr($guide['use_until_dt'], 0, 10))) {
        echo '<div style="font-size:90%; color:#fb8c00"><i class="fa fa-user"></i> ';
        if ($guide['booking_status'] == 'confirmed') {
            echo '[CFM] ';
        } else {
            echo '['.strtoupper($guide['booking_status']).'] ';
        }
        echo $guide['guide_name'];
        echo '</div>';
    }
}


// Drivers

foreach ($tourDrivers as $driver) {
    if (strtotime(substr($driver['use_from_dt'], 0, 10)) <= $date && $date <= strtotime(substr($driver['use_until_dt'], 0, 10))) {
        echo '<div style="font-size:90%; color:#00897b"><i class="fa fa-car"></i> ';
        if ($driver['booking_status'] == 'confirmed') {
            echo '[CFM] ';
        } else {
            echo '['.strtoupper($driver['booking_status']).'] ';
        }
        echo $driver['vehicle_type'].', '.$driver['driver_company'].', '.$driver['driver_name'];
        echo '</div>';
    }
}

?>
<?
    if (!empty($theTour['tournotes'])) {
        foreach ($theTour['tournotes'] as $note) {
            $lines = explode(PHP_EOL, $note['body']);
            foreach ($lines as $line) {
                $parts = explode('>>>', $line);
                if (isset($parts[1])) {
                    $parts[0] = trim($parts[0]);
                    $parts[1] = trim($parts[1]);
                    if ($parts[0] == date('j/n', strtotime($thisDay))) {
                        $color = 'blue';
                        $icon = '';
                        if (strpos($parts[1], '(red)') !== false) {
                            $color = 'red';
                        }
                        if (strpos($parts[1], '(green)') !== false) {
                            $color = 'green';
                        }
                        if (strpos($parts[1], '(purple)') !== false) {
                            $color = 'purple';
                        }
                        if (strpos($parts[1], '(car)') !== false) {
                            $icon = 'car';
                        }
                        if (strpos($parts[1], '(plane)') !== false) {
                            $icon = 'plane';
                        }
                        if (strpos($parts[1], '(air)') !== false) {
                            $icon = 'plane';
                        }
                        if (strpos($parts[1], '(flight)') !== false) {
                            $icon = 'plane';
                        }
                        if (strpos($parts[1], '(phone)') !== false) {
                            $icon = 'phone';
                        }
                        if (strpos($parts[1], '(tel)') !== false) {
                            $icon = 'phone';
                        }
                        if (strpos($parts[1], '(train)') !== false) {
                            $icon = 'train';
                        }
                        if (strpos($parts[1], '(guide)') !== false) {
                            $icon = 'user';
                        }
                        if (strpos($parts[1], '(hdv)') !== false) {
                            $icon = 'user';
                        }
                        if (strpos($parts[1], '(time)') !== false) {
                            $icon = 'clock-o';
                        }
                        $parts[1] = str_replace(['(red)', '(green)', '(blue)', '(purple)'], ['', '', '', ''], $parts[1]);
                        $parts[1] = str_replace(['(car)', '(train)', '(phone)', '(tel)', '(time)', '(plane)', '(flight)', '(air)', '(guide)', '(hdv)'], ['', '', '', '', '', '', '', '', '', ''], $parts[1]);

?>
                        <div title="<?= $note['updatedBy']['name'] ?> <?= DateTimeHelper::convert($note['updated_at'], 'j/n/Y H:i', 'UTC', $myTimeZone); ?>" style="font-size:90%; color:<?= $color ?>;">
                        <? if (in_array(MY_ID, [$note['created_by'], $note['updated_by']])) { ?><a title="<?= Yii::t('op', 'Edit') ?>" class="text-muted" href="/tours/ctn/<?= $note['product_id'] ?>"><i class="fa fa-edit"></i></a><? } ?>
                        <? if ($icon != '') { ?><i class="fa fa-<?= $icon ?>"></i><? } ?>
                        <?= trim($parts[1]) ?>
                        </div>
<?
                    }
                }
            }
        }
    }

?>
                            </td>
                        </tr>
                        <tr class="day-body hidden" id="day-body-<?= $day['id'] ?>">
                            <td></td>
                            <td>
                                <?= Html::a('Dịch tiếng Việt', 'https://translate.google.com/#fr/vi/'.urlencode(str_replace(['_', '*'], [' ', ' '], $day['body'])), ['rel'=>'external', 'style'=>'font-size:90%;']) ?>
                                <?= Markdown::process($day['body']) ?>
                            </td>
                        </tr>
<?
                        }
                    }
                }
            }
?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col col-1-2">
            <div style="width:100px;" class="pull-right text-right">
                <?= Html::a(Yii::t('op', '+New'), '@web/tasks/c?rtype=tour&rid='.$theTour['tour']['id'], ['class'=>'task-add text-muted', 'data-rtype'=>'tour', 'data-rid'=>$theTourOld['id'], 'data-rname'=>$theTourOld['code'].' - '.$theTourOld['name']]) ?>
            </div>
            <p class="text-bold text-uppercase"><?= Yii::t('op', 'Related tasks') ?></p>
            <div id="task-list" style="border-left:4px solid #d6c6b6; padding-left:8px; margin-left:8px;;">
                <? if (empty($theTour['tour']['tasks'])) { ?><p><?= Yii::t('op', 'No tasks found') ?><? } ?>
                <?
                $thisYear = date('Y');
                $today = date('Y-m-d');
                foreach ($theTour['tour']['tasks'] as $t) {
                ?>
                <div id="div-task-<?=$t['id']?>" class="task-list-item task <?=$t['status'] == 'on' && strtotime($t['due_dt']) < strtotime(NOW) ? 'task-overdue' : ''?> <?=$t['status'] == 'off' ? 'task-done' : ''?>">
                    <i id="icon-<?=$t['id']?>" data-task_id="<?=$t['id']?>" title="<?= Yii::t('op', 'Check/Uncheck') ?>" class="cursor-pointer task-check fa fa-<?= $t['status'] == 'on' ? '' : 'check-' ?>square-o"></i>
                    <span class="task-date"><?
                    if ($t['fuzzy'] != 'date') {
                        if (substr($t['due_dt'], 0, 4) == $thisYear) {
                            echo date('j/n', strtotime($t['due_dt']));
                            if (substr($t['due_dt'], 0, 10) == $today) {
                                echo '<span class="task-today">Today</span> ';
                            }
                        } else {
                            echo date('j/n/Y', strtotime($t['due_dt']));
                        }
                    } ?>
                    </span>
                    <span class="task-time"><?
                    // Show time if not fuzzy
                    $t['time'] = substr($t['due_dt'], 11);
                    if ($t['fuzzy'] == 'time') {
                        if ($t['time'] == '11:59:59') {
                            echo 'morning';
                        } elseif ($t['time'] == '17:59:59') {
                            echo 'afternoon';
                        }
                    } elseif ($t['fuzzy'] == 'none') {
                        echo substr($t['time'], 0, 5);
                    } ?>
                    </span>
                    <span class="task-priority"><? if ($t['is_priority'] == 'yes') { ?><i class="fa fa-star text-danger" title="Priority"></i><? } ?></span>
                    <span title="<?= $t['createdBy']['name'] ?> <?= DateTimeHelper::convert($t['uo'], 'j/n/Y H:i', 'Asia/Ho_Chi_Minh', $myTimeZone) ?>"><?= USER_ID == 1 || $t['ub'] ? Html::a($t['description'], '@web/tasks/u/'.$t['id'], ['class'=>'task-description', 'data-id'=>$t['id'], 'title'=>$t['cb'] == USER_ID ? 'Edit task' : $t['createdBy']['name']]) : $t['description'] ?></span>
                    <span class="task-assignees"><? $cnt = 0; foreach ($t['assignees'] as $tu) { $cnt ++; if ($cnt != 1) echo ', ';?><span id="assignee-<?=$t['id']?>-<?=$tu['id']?>" class="task-assignee text-muted <?/*=$tu['completed_dt'] == '0000-00-00 00:00:00' ? '' : 'done'*/?>"><?= $tu['id'] == USER_ID ? 'Tôi' : $tu['name'] ?></span><? } ?></span>
                </div>
                <? } // foreach tasks ?>
            </div>

            <hr>
            <p class="text-bold text-uppercase"><?= Yii::t('op', 'Related cases, bookings, pax') ?></p>
            <div class="panel panel-flat">
                <table class="table table-xxs">
                    <tbody>
<?
$paxCnt = 0;
foreach ($theTour['bookings'] as $booking) {
?>
                        <tr>
                            <td class="info" colspan="2"><strong><i class="fa fa-fw fa-briefcase"></i> <?= $booking['case']['name'] ?></strong>
                                <br>
                                <?= Html::a(Yii::t('op', 'View case'), '@web/cases/r/'.$booking['case']['id']) ?>
                                /
                                <?= Html::a(Yii::t('op', 'View booking'), '@web/bookings/r/'.$booking['id']) ?>
                                /
                                <?= Html::a(Yii::t('op', 'Pax'), '@web/tours/pax/'.$theTour['id'].'?booking='.$booking['id']) ?>
                                <? if ($booking['id'] == 31825) { ?>
                                /
                                <?= Html::a('View reg info', '@web/bookings/reg-info/'.$booking['id']) ?>
                                <? } ?>

                                <? foreach ($tourRegInfo as $reginfo) { ?>
                                <? if ($reginfo['booking_id'] == $booking['id']) { ?>
                                /
                                <?= Html::a('View reg info', '@web/bookings/reg-info/'.$booking['id']) ?>
                                <? } ?>
                                <? } ?>
                            </td>
                        </tr>
<?
    // New pax info available
    if (!empty($theTour['pax'])) {
        foreach ($theTour['pax'] as $pax) {
            if ($pax['booking_id'] == $booking['id']) {
                $pax['data'] = unserialize($pax['data']);
?>
                        <tr>
                            <td width="15" class="text-right text-muted"><?= ++ $paxCnt ?></td>
                            <td class="no-padding-left">
                                <i title="New input method data" class="fa fa-bolt text-muted pull-right"></i>
                                <? if ($pax['data']['previous_tour'] != '') { ?>
                                <i title="Previous tour: <?= $pax['data']['previous_tour'] ?>" class="fa fa-refresh text-success pull-right"></i>
                                <? } ?>
                                <i title="<?= $pax['pp_gender'] ?>" class="fa fa-fw fa-<?= $pax['pp_gender'] ?> color-<?= $pax['data']['pp_gender'] ?>"></i>
                                <span title="" class="flag-icon flag-icon-<?= $pax['data']['pp_country_code'] ?>"></span>
                                <?= $pax['contact_id'] == 0 ? $pax['name'] : Html::a($pax['name'], '#@web/users/r/'.$pax['contact_id'], ['title'=>$pax['data']['pp_name'].' / '.$pax['data']['pp_name2']]) ?>
                                <?
                                if ($pax['data']['pp_bday'] != 0 && $pax['data']['pp_bmonth'] != 0 && $pax['data']['pp_byear'] != 0) {
                                    $datetime1 = new DateTime($pax['data']['pp_byear'].'-'.$pax['data']['pp_bmonth'].'-'.$pax['data']['pp_bday']);
                                    $datetime2 = new DateTime($theTour['day_from']);
                                    $age = $datetime1->diff($datetime2);
                                    $title = $age->format(Yii::t('op', '%yy %mm %dd when tour starts'));
                                }
                                ?>
                                <em title="(<?= $pax['data']['pp_bday'].'/'.$pax['data']['pp_bmonth'].'/'.$pax['data']['pp_byear'] ?>) <?= $title ?? '' ?>"><?= $pax['data']['pp_byear'] == '' ? '' : date('Y') - (int)$pax['data']['pp_byear'] ?></em>
                            </td>
                        </tr>

<?
            }
        }
    } else {
        foreach ($tourPax as $user) {
            if ($user['booking_id'] == $booking['id']) {
?>
                        <tr>
                            <td width="15" class="text-right text-muted"><?= ++ $paxCnt ?></td>
                            <td class="no-padding-left">
                                <i title="<?= $user['gender'] ?>" class="fa fa-fw fa-<?= $user['gender'] ?> color-<?= $user['gender'] ?>"></i>
                                <span class="flag-icon flag-icon-<?= $user['country_code'] ?>"></span>
                                <?= Html::a($user['name'], '@web/users/r/'.$user['id'], ['title'=>$user['fname'].' / '.$user['lname']]) ?>
                                <?
                                if ($user['bday'] != 0 && $user['bmonth'] != 0 && $user['byear'] != 0) {
                                    $datetime1 = new DateTime($user['byear'].'-'.$user['bmonth'].'-'.$user['bday']);
                                    $datetime2 = new DateTime($theTour['day_from']);
                                    $age = $datetime1->diff($datetime2);
                                    $title = $age->format(Yii::t('op', '%yy %mm %dd when tour starts'));
                                }
                                ?>
                                <em title="(<?= $user['bday'].'/'.$user['bmonth'].'/'.$user['byear']?>) <?= $title ?? '' ?>"><?= $user['byear'] == 0 ? '' : date('Y') - $user['byear'] ?></em>
                            </td>
                        </tr>

<?
            }
        }
    }
} // foreach bookings
?>
                    </tbody>
                </table>
            </div>

            <p class="text-bold text-uppercase"><i class="fa fa-file-o"></i> <?= Yii::t('op', 'All files') ?></p>
            <div class="mb-1em">
                <? foreach ($allFileList as $file) { ?>
                <div>+ <?= Html::a($file['name'], $file['link']) ?> <span class="text-muted"><?= Yii::$app->formatter->asShortSize($file['size'], 0) ?></span></div>
                <? } ?>
            </div>

            <? if (!empty($tourFeedbacks)) { ?>
            <div class="mb-1em">
                <p class="text-bold text-uppercase"><i class="fa fa-smiley-o"></i>  <?= Yii::t('op', 'Customer feedback') ?></p>
                <? foreach ($tourFeedbacks as $feedback) { ?>
                <div><i class="fa fa-<?= $feedback['say'] ?>-o"></i> <?= $feedback['who'] ?> : <?= $feedback['what'] ?> : <?= $feedback['feedback'] ?></div>
                <? } // foreach feedback ?>
            </div>
            <? } // if not empty feedback ?>

            <hr>
            <p class="text-muted"><i class="fa fa-info-circle"></i> <?= Yii::t('op', 'This tour was updated on') ?> <?= DateTimeHelper::convert($theTour['tour']['uo'], 'j/n/Y H:i') ?> <?= Yii::t('op', 'by') ?> ?. <?= Yii::t('op', 'The timezone of all messages is') ?> <?= $myTimeZone ?></p>

        </div>
    </div>
</div>
<div class="col col-2">
    <ul class="note-list">
        <li class="first note-list-item clearfix">
            <div class="note-avatar"><?= Html::a(Html::img('/timthumb.php?zc=1&w=100&h=100&src='.Yii::$app->user->identity->image, ['class'=>'note-author-avatar img-circle']), '@web/users/r/'.MY_ID) ?></div>
            <div class="note-content">
            <?= $this->render('_editor.php', ['theTour'=>$theTour]) ?>
            </div>
        </li>
<?
        foreach ($timeTable as $time=>$item) {
            $time = substr($time, 0, 16);
            if ($item['object'] == 'note') {
                foreach ($theNotes as $note) {
                    if ($note['id'] == $item['id']) {
                        // BEGIN NOTE
                        $userAvatar = '//secure.gravatar.com/avatar/'.md5($note['from']['id']).'?s=100&d=wavatar';
                        if ($note['from']['image'] != '') {
                            $userAvatar = '/timthumb.php?zc=1&w=100&h=100&src='.$note['from']['image'];
                        }
                        //$note->from->image != '' ? DIR.'timthumb.php?src='.$note->from->image.'&w=300&h=300&zc=1' : 'http://0.gravatar.com/avatar/'.md5($li->from_id).'.jpg?s=64&d=wavatar';;
                        include('tour_r__message.php');
                    }
                }
                // END NOTE
            } elseif ($item['object'] == 'mail') {
                //break;
                foreach ($inboxMails as $mail) {
                    if ($mail['id'] == $item['id']) {
                        include('tour_r__mail.php');
                    }
                }
            } elseif ($item['object'] == 'sysnote') {
                foreach ($theSysnotes as $note) {
                    if ($note['id'] == $item['id']) {
                        // BEGIN SYSNOTE
?>
        <li class="note-list-item clearfix">
            <? if ($note['action'] == 'kase/close') { ?>
            <div class="note-avatar"><i class="fa fa-lock text-info fa-3x note-author-avatar"></i></div>
            <div class="note-content" style="border-color:#31708F">
                <h5 class="note-heading">
                    <i class="fa fa-lock"></i>
                    <?= Html::a(Html::encode($note['user']['name']), '@web/users/r/'.$note['user']['id'], ['class'=>'note-author-name']) ?>
                    closed this case
                </h5>
                <div class="mb-1em">
                    <span class="text-muted"><?= $time ?></span>
                </div>
                <div class="note-body"><?= $note['info'] ?></div>
            </div>
            <? } ?>
        </li>
<?
                        // END SYSNOTE
                    }
                }
            }
        }
?>
    </ul>
</div>
<style>

i.fa.color-male {color:#3949ab;}
i.fa.color-female {color:#f4511e;}
.day-title {cursor:pointer;}
.task-today {color:#c00;}
.task-done .task-today {color:#444; display:none;}
.task-overdue .task-date, .task-overdue .task-time {color:#f00;}
</style>

<?
$js = <<<'TXT'
    var names = [
        {{jsPeopleList}}
    ];

    var tags = ['important', 'urgent', 'client'];
    var tags = $.map(tags, function(value, i) {return {key: value, name:value}});

    var at_config = {
      at: "@",
      data: names,
      search_key: 'nname',
      limit: 10,
      tpl: "<li data-value='@${key}'>${name} <small>${email}</small></li>",
      show_the_at: true
    }
    var tag_config = {
      at: '#',
      data: tags,
      tpl: '<li data-value="#${name}">${name}</li>',
      show_the_at: true
    }
    $('#to').atwho(at_config);
    $('#title').atwho(tag_config);

    // Task check
    $('#task-list').on('click', 'i.task-check', function(){
        var task_id = $(this).data('task_id');
        $.post('/tasks/ajax', {action:'check', task_id:task_id}, function(data){
            if (data.status) {
                if (data.status == 'OK') {
                    $('span#assignee-' + task_id + '-' + '1').toggleClass('done');
                    $('i#icon-' + task_id).removeClass('fa-square-o').removeClass('fa-check-square-o');
                    if (data.icon == 'icon-check') {
                        $('i#icon-' + task_id).addClass('fa-check-square-o');
                        // $('div#div-task-' + task_id).removeClass('task-overdue');
                    } else {
                        $('i#icon-' + task_id).addClass('fa-square-o');
                    }
                } else {
                    alert(data.message);
                }
            } else {
                alert('Error: data error.');
            }
        }, 'json');
    });
    
    // Day toggle
    $('div.day-title').click(function(){
        var id = $(this).data('id');
        $('tr#day-body-' + id).toggleClass('hidden');
    });


TXT;
$js = str_replace(['{{jsPeopleList}}'], [$jsPeopleList], $js);

$this->registerCssFile(DIR.'assets/at.js_0.4.12/css/jquery.atwho.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile(DIR.'assets/at.js_0.4.12/js/jquery.caret.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile(DIR.'assets/at.js_0.4.12/js/jquery.atwho.js', ['depends'=>'yii\web\JqueryAsset']);
// $this->registerJsFile(DIR.'assets/autosize_1.18.7/jquery.autosize.min.js', ['depends'=>'yii\web\JqueryAsset']);
// $this->registerJsFile(DIR.'assets/jquery.countdown_2.0.4/jquery.countdown.min.js', ['depends'=>'yii\web\JqueryAsset']);

$this->registerJs($js);

include('_plupload_inc.php');
include('_ckeditor_inc.php');

include(Yii::getAlias('@app').'/views/task/_task_edit_modal.php');
