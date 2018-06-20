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

$this->title = $theTour['tour']['code'].' - '.$theTour['tour']['name'].' - ';
Yii::$app->params['page_small_title'] = implode('+', $tourPaxCount).'p '.$theTour['day_count'].'d ';
if (substr($theTour['day_from'], 0, 4) == date('Y')) {
    Yii::$app->params['page_small_title'] .= date('j/n', strtotime($theTour['day_from']));
} else {
    Yii::$app->params['page_small_title'] .= date('j/n/Y', strtotime($theTour['day_from']));
}
Yii::$app->params['page_small_title'] .= ' ('.Yii::$app->formatter->asRelativeTime($theTour['day_from']).')';
Yii::$app->params['page_icon'] = 'car';
Yii::$app->params['page_breadcrumbs'] = [
    ['Tours', 'tours'],
    [substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
    [$theTour['tour']['code'], 'tours/r/'.$theTour['tour']['id']],
];

$jsPeopleList = '';
foreach ($thePeople as $person) {
    $jsPeopleList .= "{key:'[".$person['name']."]', name:'".$person['fname']." ".$person['lname']."', nname:'".str_replace('.', '', strstr($person['email'], '@', true)).str_replace(['-', '_', ' '], ['', '', ''], \fURL::makeFriendly($person['fname'].$person['lname']))."', email:'".$person['email']."'},";
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
                            'link'=>'@web/mails/f/'.$mail['id'].'?name='.$file['name'],
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
    <? if ($theTour['op_finish'] == 'canceled') { ?>
    <div class="alert alert-danger">This tour has been canceled</div>
    <? } ?>
</div>
<div class="col-md-12">
    <ul class="nav nav-tabs nav-tabs-line">
        <li class=""><a href="/products/r/<?= $theTour['id'] ?>">Product</a></li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Sales <span class="caret"></span></a>
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
        <li class="active"><a href="/tours/r/<?= $theTour['tour']['id'] ?>">Operation</a></li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Test menu <span class="caret"></span></a>
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
                        <tr>
                            <th width="50" class="text-nowrap">Tour</th><td><?= $theTour['tour']['code'] ?> - <?= $theTour['tour']['name'] ?></td>
                        </tr>
                        <? if ($theTour['tour']['pax_ratings'] != 0) { ?>
                        <tr>
                            <th>Pts</th>
                            <td><strong class="text-success"><?= trim(number_format($theTour['tour']['pax_ratings'] / 10, 1), '.0') ?></strong>/10 (rated by pax)</td>
                        </tr>
                        <? } ?>
                        <tr>
                            <th class="text-nowrap">Staff</th><td>
                                <strong>BH</strong>
<?
$nameList = [];
foreach ($theTour['bookings'] as $booking) {
    $nameList[] = Html::a($booking['case']['owner']['name'], '@web/users/r/'.$booking['case']['owner']['id']);
}
echo implode(', ', $nameList);
?>
                                <strong>ĐH</strong>
<?
$nameList = [];
foreach ($tourOperators as $user) {
    $nameList[] = Html::a($user['name'], '@web/users/r/'.$user['id']);
}
echo implode(', ', $nameList);
?>
                                <strong>CSKH</strong>
<?
$nameList = [];
foreach ($tourCSStaff as $user) {
    $nameList[] = Html::a($user['name'], '@web/users/r/'.$user['id']);
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
                            <th>Agent</th>
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
$y1 = date('Y', strtotime($theTour['day_from']));
$y2 = date('Y', strtotime($theTour['day_until']));
foreach ($tourPax as $p) {
    $paxBD1 = strtotime($y1.'-'.$p['bmonth'].'-'.$p['bday']);
    $paxBD2 = strtotime($y2.'-'.$p['bmonth'].'-'.$p['bday']);
    if (($y1 == $y2) && (strtotime($theTour['day_from']) <= $paxBD1) && ($paxBD2 <= strtotime($theTour['day_until']))) {
        $birthDays[] = '<span class="label label-danger"><i class="fa fa-gift"></i> Birthday '.$p['bday'].'/'.$p['bmonth'].'</span> '.Html::a($p['name'], '@web/users/r/'.$p['id']);
    }
    if (($y1 != $y2) && ((strtotime($theTour['day_from']) <= $paxBD1) || ($paxBD2 <= strtotime($theTour['day_until'])))) {
        $birthDays[] = '<span class="label label-danger"><i class="fa fa-gift"></i> Birthday '.$p['bday'].'/'.$p['bmonth'].'</span> '.Html::a($p['name'], '@web/users/r/'.$p['id']);
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
                            <th class="text-nowrap">Notes</th>
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
                            <th class="text-nowrap">Ref.</th><td>
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
        echo Html::a($r['name'], '@web/users/r/'.$r['user_id']);
        // TODO also tour code
    }
} // if empry ref
?>
                            </td>
                        </tr>
                        <tr>
                            <th>$$$$</th>
                            <td>
<?
foreach ($theTour['bookings'] as $booking) {
    foreach ($booking['invoices'] as $invoice) {
        $paymentStatusText = '(UNPAID)';
        $paymentStatusColor = '';
        if ($invoice['payment_status'] == 'paid') {
            $paymentStatusText = '(PAID)';
            $paymentStatusColor = 'green';
        } else {
            if (strtotime($invoice['due_dt']) < strtotime('today')) {
                $paymentStatusText = '(OVERDUE)';
                $paymentStatusColor = 'red';
            }
        }
?>
                                        <div class="row" style="color:<?= $paymentStatusColor ?>">
                                            <div class="col-xs-5" title="<?= $invoice['bill_to_name'] ?>"><?= Html::a($invoice['ref'], '@web/invoices/r/'.$invoice['id']) ?></div>
                                            <div class="col-xs-4 text-right text-nowrap"><?= $invoice['stype'] == 'credit' ? '-' : '' ?><?= number_format($invoice['amount'], 2) ?> <span><?= $invoice['currency'] ?></span></div>
                                            <div class="col-xs-2"><?= $paymentStatusText ?></div>
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
                <div class="panel-heading"><strong>ITINERARY</strong> <?= $theTour['day_count'] ?> days, from <?= date('j/n/Y', strtotime($theTour['day_from'])) ?> <?= Html::a('Itinerary', '@web/products/r/'.$theTour['id']) ?> - <?= Html::a('Notes', '@web/tours/ctn/'.$theTour['id']) ?></div>
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
                                <? if (date('Y-m-d') == $thisDay) { ?><span class="label label-success">TODAY</span> <? } ?>
                                <?= Html::encode($day['name']) ?>
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
                        <? if (in_array(MY_ID, [$note['created_by'], $note['updated_by']])) { ?><a title="Edit" class="text-muted" href="/tours/ctn/<?= $note['product_id'] ?>"><i class="fa fa-edit"></i></a><? } ?>
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
                            <td class="text-nowrap" width="20"><em><?= $day['meals'] ?></em></td>
                        </tr>
                        <tr class="day-body hidden" id="day-body-<?= $day['id'] ?>">
                            <td></td>
                            <td colspan="2">
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
                <?= Html::a('+New', '@web/tasks/c?rtype=tour&rid='.$theTour['tour']['id'], ['class'=>'text-muted']) ?>
            </div>
            <p><strong>RELATED TASKS</strong></p>
            <? if (empty($theTour['tour']['tasks'])) { ?><p>No tasks found. <?= Html::a('Add tasks', '@web/tasks/c?rtype=tour&rid='.$theTour['tour']['id']) ?></p><? } else { ?>
            <div style="border-left:4px solid #d6c6b6; padding-left:8px; margin-left:8px;;">
                <?
                $thisYear = date('Y');
                $today = date('Y-m-d');
                foreach ($theTour['tour']['tasks'] as $t) {
                ?>
                <div style="padding:2px;">
                    <div id="div-task-<?=$t['id']?>" class="task <?=$t['status'] == 'on' && strtotime($t['due_dt']) < strtotime(NOW) ? 'task-overdue' : ''?> <?=$t['status'] == 'off' ? 'task-done' : ''?>">
                    <i id="icon-<?=$t['id']?>" data-task_id="<?=$t['id']?>" class="task-check fa fa-<?= $t['status'] == 'on' ? '' : 'check-' ?>square-o"></i>
                    <?
                    if ($t['fuzzy'] == 'date') {
                        // Echo nuffin'
                    } else {
                        if (substr($t['due_dt'], 0, 4) == $thisYear) {
                            $dueDTDisplay = date('d-m', strtotime($t['due_dt']));
                        } else {
                            $dueDTDisplay = date('d-m-Y', strtotime($t['due_dt']));
                        }
                        if (substr($t['due_dt'], 0, 10) == $today) echo '<span class="task-today">Today</span> ';
                        echo '<span class="task-date">', $dueDTDisplay, '</span>';
                        if ($t['fuzzy'] == 'time') {
                            // Display nuffin
                        } else {
                            echo ' <span class="task-time">'.substr($t['due_dt'], 11, 5).'</span>';
                        }
                    }

                    ?>
                    <? if ($t['is_priority'] == 'yes') { ?><i class="fa fa-star text-danger" title="Priority"></i><? } ?>
                    <span title="<?= $t['createdBy']['name'] ?> <?= DateTimeHelper::convert($t['uo'], 'j/n/Y H:i', 'Asia/Ho_Chi_Minh', $myTimeZone) ?>"><?= Yii::$app->user->id == 1 || $t['ub'] ? Html::a($t['description'], '@web/tasks/u/'.$t['id']) : $t['description'] ?></span>
                    <? $cnt = 0; foreach ($t['assignees'] as $tu) { $cnt ++; if ($cnt != 1) echo ', ';?><span id="assignee-<?=$t['id']?>-<?=$tu['id']?>" class="text-muted <?/*=$tu['completed_dt'] == '0000-00-00 00:00:00' ? '' : 'done'*/?>" title="AS: <?//=$tu['assigned_dt']?>"><?= $tu['id'] == Yii::$app->user->id ? 'Tôi' : $tu['name']?></span><? } ?>
                    </div>
                </div>
                <? } // foreach tasks ?>
            </div>
            <? } // if empty ?>

            <hr>
            <p class="text-warning"><strong>RELATED CASES, BOOKINGS, PAX</strong></p>
            <div class="panel panel-flat">
                <? if (USER_ID == 1111) { ?>
                <ul class="media-list">
<?
$paxCnt = 0;
foreach ($theTour['bookings'] as $booking) {
?>
                    <li class="media-header"><i class="fa fa-fw fa-briefcase"></i> <?= $booking['case']['name'] ?>
                        <?= Html::a('View case', '@web/cases/r/'.$booking['case']['id'], ['class'=>'text-muted']) ?>
                        <?= Html::a('View booking', '@web/bookings/r/'.$booking['id'], ['class'=>'text-muted']) ?>
                    </li>
                    
<?
    foreach ($tourPax as $user) {
        if ($user['booking_id'] == $booking['id']) {
?>
                    <li class="media">
                        <div class="media-link collapsed" aria-expanded="false">
                            <div class="media-left"><img src="assets/images/demo/users/face1.jpg" class="img-circle" alt=""></div>
                            <div class="media-body">
                                <div class="media-heading text-semibold"><?= $user['name'] ?></div>
                                <span class="text-muted">Last.fm</span>
                            </div>
                            <div class="media-right media-middle text-nowrap">
                                <i class="cursor-pointer icon-menu7 display-block" data-toggle="collapse" data-target="#pax-<?= $user['id'] ?>" ></i>
                            </div>
                        </div>
                        <div class="collapse" id="pax-<?= $user['id'] ?>" aria-expanded="false" style="height: 0px;">
                            <div class="contact-details">
                                <ul class="list-extended list-unstyled list-icons">
                                    <li><i class="icon-pin position-left"></i> Amsterdam</li>
                                    <li><i class="icon-user-tie position-left"></i> Senior Designer</li>
                                    <li><i class="icon-phone position-left"></i> +1(800)431 8996</li>
                                    <li><i class="icon-mail5 position-left"></i> <a href="#">james@alexander.com</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
<?
        } // if booking_id = booking id
    } // foreach tour pax
?>
                    
<? 
} // foreach tour bookings
?>
                </ul>
                <? } ?>

                <table class="table table-xxs">
                    <tbody>
<?
$paxCnt = 0;
foreach ($theTour['bookings'] as $booking) {
?>
                        <tr>
                            <td class="info" colspan="2"><strong><i class="fa fa-fw fa-briefcase"></i> <?= $booking['case']['name'] ?></strong>
                                <br>
                                <?= Html::a('View case', '@web/cases/r/'.$booking['case']['id']) ?>
                                /
                                <?= Html::a('View booking', '@web/bookings/r/'.$booking['id']) ?>
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
    foreach ($tourPax as $user) {
        if ($user['booking_id'] == $booking['id']) {
?>
                        <tr>
                            <td width="15" class="text-right text-muted"><?= ++ $paxCnt ?></td>
                            <td class="no-padding-left">
                                <i title="<?= $user['gender'] ?>" class="fa fa-fw fa-<?= $user['gender'] ?> color-<?= $user['gender'] ?>"></i>
                                <span class="flag-icon flag-icon-<?= $user['country_code'] ?>"></span>
                                <?= Html::a($user['name'], '@web/users/r/'.$user['id'], ['title'=>$user['fname'].' / '.$user['lname']]) ?>
                                <em><?= $user['byear'] == 0 ? '' : date('Y') - $user['byear'] ?></em>
                            </td>
                        </tr>

<?
        }
    }
} // foreach bookings
?>
                    </tbody>
                </table>
            </div>

            <p><strong><i class="fa fa-file-o"></i> ALL FILES</strong></p>
            <div class="mb-1em">
                <? foreach ($allFileList as $file) { ?>
                <div>+ <?= Html::a($file['name'], $file['link']) ?> <span class="text-muted"><?= Yii::$app->formatter->asShortSize($file['size'], 0) ?></span></div>
                <? } ?>
            </div>

            <? if (!empty($tourFeedbacks)) { ?>
            <div class="mb-1em">
                <p><strong>CUSTOMER FEEDBACK</strong></p>
                <? foreach ($tourFeedbacks as $feedback) { ?>
                <div><i class="fa fa-<?= $feedback['say'] ?>-o"></i> <?= $feedback['who'] ?> : <?= $feedback['what'] ?> : <?= $feedback['feedback'] ?></div>
                <? } // foreach feedback ?>
            </div>
            <? } // if not empty feedback ?>

            <hr>
            <p><i class="fa fa-info-circle"></i> This tour was updated on <?= DateTimeHelper::convert($theTour['tour']['uo'], 'j/n/Y H:i') ?></p>
            <p><i class="fa fa-clock-o"></i> The timezone of all messages is <?= $myTimeZone ?></p>

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

?>
        <li class="note-list-item clearfix">
            <div class="note-avatar">
            <?= Html::a(Html::img($userAvatar, ['class'=>'img-circle note-author-avatar']), '@web/users/r/'.$note['from']['id']) ?>
            </div>
            <?
            if ($note['n_id'] != 0) {
                $title = 'replied';
            } else {
                $title = $note['title'] == '' ? '( no title )' : $note['title'];
            }
            // #client hashtag
            $clientHash = false;
            if (strpos($title, '#client') !== false) {
                $title = str_replace('#client', '', $title);
                $clientHash = true;
            }
            $clientReservStatusHash = false;
            if (strpos($title, '#reservation-status') !== false) {
                $title = str_replace('#reservation-status', '', $title);
                $clientReservStatusHash = true;
            }
            $body = $note['body'];

            $body = str_replace(['width:', 'height:', 'font-size:', '<table', '</table>', '<p>&nbsp;</p>'], ['x:', 'x:', 'x:', '<div class="table-responsive"><table class="table table-condensed table-bordered" ', '</table></div>', ''], $body);
            $body = HtmlPurifier::process($body);
            ?>
            <div class="note-content">
                <h5 class="note-heading">
                    <? if ($note['via'] == 'email') { ?><i class="fa fa-envelope-o"></i><? } ?>
                    <?= Html::a($note['from']['name'], '@web/users/r/'.$note['from_id'], ['class'=>'note-author-name']) ?>
                    :

                    <? if ($clientHash) { ?><strong style="background-color:#BD499B; padding:0 4px; color:#fff;">#client</strong><? } ?>
                    <? if ($clientReservStatusHash) { ?><strong style="background-color:#BD499B; padding:0 4px; color:#fff;">#reservation-status</strong><? } ?>

                    <? if (substr($note['priority'], 0, 1) == 'C') { ?><strong style="background-color:#f60; padding:0 4px; color:#fff;">#important</strong><? } ?>
                    <? if (substr($note['priority'], -1) == '3') { ?><strong style="background-color:#c00; padding:0 4px; color:#fff;">#urgent</strong><? } ?>

                    <?= Html::a($title, '@web/notes/r/'.$note['id'], ['class'=>'note-title']) ?>
                    <?
                    if ($note['to']) {
                        echo ' <span class="text-muted">to</span> ';
                        $cnt = 0;
                        foreach ($note['to'] as $to) {
                            $cnt ++;
                            if ($cnt > 1) echo ', ';
                            echo Html::a($to['name'], '@web/users/r/'.$to['id'], ['style'=>'color:purple;']);
                        }
                    }
                    ?>
                </h5>
                <div class="mb-1em">
                    <span class="text-muted"><?= date('j/n/Y H:i', strtotime($time)) ?></span>
                    - <?= Html::a('Edit', '@web/notes/u/'.$note['id']) ?>
                    - <?= Html::a('Delete', '@web/notes/d/'.$note['id']) ?>
                </div>
                <? if ($note['files']) { ?>
                <div class="note-file-list">
                    <? foreach ($note['files'] as $file) { ?>
                    <div class="note-file-list-item">+ <?= Html::a($file['name'], '@web/files/r/'.$file['id']) ?> <span class="text-muted"><?= Yii::$app->formatter->asShortSize($file['size'], 0) ?></span></div>
                    <? } ?>
                </div>
                <? } ?>
                <div class="note-body">
                    <?= $body ?>
                </div>
            </div>
        </li>
<?
                    }
                }
                // END NOTE
            } elseif ($item['object'] == 'mail') {
                //break;
                foreach ($inboxMails as $mail) {
                    if ($mail['id'] == $item['id']) {
                        // BEGIN MAIL
?>
        <li class="note-list-item clearfix">
            <div class="note-avatar"> 
            <?
            $userAvatar = '//secure.gravatar.com/avatar/'.md5($mail['from_email']).'?s=100&d=wavatar';
            if ($mail['from_email'] == 'n.thiminh@amicatravel.com') {
                $userAvatar = '/upload/amica-user-avatars/nthiminh.jpg';
                if (!in_array(USER_ID, [2,3,4])) $mail['from'] = 'Minh Nguyễn';
            }
            if ($mail['from_email'] == 'pham.ha@amicatravel.com') {
                $userAvatar = '/upload/amica-user-avatars/pthiha.jpg';
                if (!in_array(USER_ID, [2,3,4])) $mail['from'] = 'Hà Phạm';
            }
            if ($mail['from_email'] == 'tran.duong@amicatravel.com') {
                $userAvatar = '/upload/amica-user-avatars/tthuyduong.jpg';
                if (!in_array(USER_ID, [2,3,4])) $mail['from'] = 'Dương ngâu';
            }
            if ($mail['from_email'] == 'bearez.hoa@amicatravel.com') {
                $userAvatar = '/upload/amica-user-avatars/hoab.jpg';
                if (!in_array(USER_ID, [2,3,4])) $mail['from'] = 'Hoa Bearez';
            }
            //if ($mail['from_email'] == $theTour['owner']['email'] && $theTour['owner']['image'] != '') {
                //$userAvatar = '/timthumb.php?zc=1&w=100&h=100&src='.$theTour['owner']['image'];
            //}
            ?> 
            <?= Html::a(Html::img($userAvatar, ['class'=>'img-circle note-author-avatar']), '#') ?>
            </div>
            <div class="note-content">
                <h5 class="note-heading">
                    <i class="fa fa-envelope-o"></i>
                    <?= Html::a($mail['from'], '@web/mails/r/'.$mail['id'], ['class'=>'note-author-name', 'rel'=>'external']) ?>:
                    <?= Html::a($mail['subject'] == '' ? '( no subject )' : $mail['subject'], '@web/mails/r/'.$mail['id'], ['class'=>'note-title', 'rel'=>'external']) ?>
                    <small><a class="text-muted label" style="background-color:#ccc;" onclick="$('#mail-tbl-<?= $mail['id'] ?>').toggle(); return false;">&hellip;</a></small>
                </h5>
                <div class="mb-1em">
                    <span class="text-muted">
                    <?= date('j/n/Y H:i', strtotime($time)) ?>
                    <? if ($mail['created_at'] != $mail['updated_at'] && $mail['updated_by'] != 0) { ?>
                    edited
                    <? } ?>
                    </span>
                    <? if ($mail['attachment_count'] > 0) { ?>
                    - <i class="fa fa-paperclip"></i> <?= $mail['attachment_count'] ?>
                    <? } ?>

                    <? if ($mail['tags'] == 'op') { ?>
                    - <?= Html::a('Shared in tour', '@web/mails/u-op/'.$mail['id'], ['class'=>'label label-success', 'title'=>'Click to stop sharing']) ?>
                    <? } else { ?>
                    - <?= Html::a('Not shared', '@web/mails/u-op/'.$mail['id'], ['class'=>'text-muted', 'title'=>'Click to share in tour']) ?>
                    <? } ?>

                    <? if (in_array(MY_ID, [1])) { ?>
                    - <?= Html::a('Unlink', '@web/mails/unlink/'.$mail['id'], ['class'=>'text-muted', 'title'=>'Unlink this email from this case']) ?>
                    - <?= Html::a('Edit', '@web/mails/u/'.$mail['id'], ['class'=>'text-muted']) ?>
                    - <?= Html::a('Delete', '@web/mails/u/'.$mail['id'], ['class'=>'text-muted']) ?>
                    <? } ?>
                </div>
                <div id="mail-tbl-<?= $mail['id'] ?>" style="display:none;">
                    <table class="table table-condensed table-bordered bg-info">
                        <tbody>
                            <tr><td>Date</td><td><?= DateTimeHelper::convert($mail['sent_dt'], 'd-m-Y H:i O', 'UTC', Yii::$app->user->identity->timezone) ?></td></tr>
                            <tr><td>From</td><td><?= Html::encode($mail['from']) ?></td></tr>
                            <tr><td>To</td><td><?= Html::encode($mail['to']) ?></td></tr>
                            <? if ($mail['cc'] != '') { ?>
                            <tr><td>Cc</td><td><?= Html::encode($mail['cc']) ?></td></tr>
                            <? } ?>
                        </tbody>
                    </table>
                </div>
                <? if ($mail['attachment_count'] > 0 && $mail['files'] != '') { $mail['files'] = unserialize($mail['files']); ?>
                <div class="note-file-list">
                    <? foreach ($mail['files'] as $file) { ?>
                    <div class="note-file-list-item">+ <?= Html::a($file['name'], '@web/mails/f/'.$mail['id'].'?name='.$file['name']) ?> <span class="text-muted"><?= Yii::$app->formatter->asShortSize($file['size'], 0) ?></span></div>
                    <? } ?>
                </div>
                <? } ?>
                <div class="note-body pre-scrollable">
                    <?
                        $sep = [
                            'bearez.hoa@amicatravel.com'=>'<b>Correspondante - Amica Travel en France</b>',
                            'mai.phuong@amicatravel.com'=>'Amica Travel - Voyage sur mesure au Vietnam, au Laos, au Cambodge',
                            'phung.lien@amicatravel.com'=>'PHUNG Lien (Mme)',
                            'nguyen.ha@amicatravel.com'=>'<span>Hà NGUYEN</span>',
                            'ngoc.linh@amicatravel.com'=>'LY Ngoc Linh',
                            'ngo.hang@amicatravel.com'=>'Hang NGO',
                            'ngoc.anh@amicatravel.com'=>'Ngoc Anh NGUYEN',
                            'dinh.huyen@amicatravel.com'=>'DINH Thi Thuong Huyen',
                            'bui.ngoc@amicatravel.com'=>'Conseillère de voyage',
                            'nguyen.thao@amicatravel.com'=>'Conseillère en voyage',
                            'hoa.nhung@amicatravel.com'=>'Nhung HOA (Mlle)',
                            'doan.ha@amicatravel.com'=>'Assistante du Chef de vente',
                        ];
                        if (isset($sep[$mail['from_email']])) {
                            $pos = strpos($mail['body'], $sep[$mail['from_email']]);
                            if (false !== $pos) {
                                $mail['body'] = substr($mail['body'], 0, $pos - 1);
                                $mail['body'] = HtmlPurifier::process($mail['body']);
                            }
                        }
                        // Not [cs]
                        if (substr($mail['subject'], 0, 4) != '[cs]')
                        echo str_ireplace(['<br><br><br>', '<br><br>', 'href="', 'src="'], ['<br>', '<br>', 'href="#', 'src="//my.amicatravel.com/assets/img/1x1.png" x="'], $mail['body']);
                    ?>
                </div>
            </div>
        </li>
<?
                        // END MAIL
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
    $('i.task-check').on('click', function(){
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
//$this->registerJsFile(DIR.'assets/autosize_1.18.7/jquery.autosize.min.js', ['depends'=>'yii\web\JqueryAsset']);
//$this->registerJsFile(DIR.'assets/jquery.countdown_2.0.4/jquery.countdown.min.js', ['depends'=>'yii\web\JqueryAsset']);

$this->registerJsFile(DIR.'assets/moment_2.8.1/moment.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);

include('_plupload_inc.php');
include('_redactor_inc.php');
include('_ckeditor_inc.php');

