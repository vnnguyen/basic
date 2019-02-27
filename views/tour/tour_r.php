<?php
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\HtmlPurifier;
use app\helpers\DateTimeHelper;

$testGroup = [111];

if (isset($theTour['id'])) {
    $this->beginBlock('page_tabs'); ?>
<ul class="nav nav-tabs nav-tabs-bottom mb-0 px-3">
    <li class="nav-item"><a class="nav-link<?= SEG2 == 'r' ? ' active' : '' ?>" href="/tours/r/<?= $theTour['tour']['id'] ?>/feed"><?= Yii::t('x', 'Overview') ?></a></li>
    <li class="nav-item"><a class="nav-link<?= in_array(SEG3, ['', 'u']) ? ' active' : '' ?>" href="/products/r/<?= $theTour['id'] ?>"><?= Yii::t('x', 'Program') ?></a></li>
    <li class="nav-item"><a class="nav-link<?= SEG3 == 'services' ? ' active' : '' ?>" href="/tours/services/<?= $theTour['tour']['id'] ?>"><?= Yii::t('x', 'Services') ?></a></li>
    <li class="nav-item"><a class="nav-link<?= SEG3 == 'sales' ? ' active' : '' ?>" href="#/tours/<?= $theTour['id'] ?>/sales"><?= Yii::t('x', 'Sales') ?></a></li>
    <li class="nav-item"><a class="nav-link<?= SEG3 == 'operation' ? ' active' : '' ?>" href="#/tours/<?= $theTour['id'] ?>/operation"><?= Yii::t('x', 'Operation') ?></a></li>
    <li class="nav-item"><a class="nav-link<?= SEG3 == 'pax' ? ' active' : '' ?>" href="/tours/<?= $theTour['id'] ?>/pax"><?= Yii::t('nav', 'Customers') ?></a></li>
</ul><?php
    $this->endBlock();
}

$theTourOld = $theTour['tour'];
include_once('_tours_inc.php');

if ($theTour['client_series'] != '') {
    Yii::$app->params['page_sub_title'] = $theTour['client_series'];
}

// TODO old files kkc_files

$allFileList = [];

$tourPaxCount = [];
foreach ($theTour['bookings'] as $booking) {
    $tourPaxCount[] = $booking['pax'];
    // B2B Priority
    if (!empty($booking['case']) && in_array($booking['case']['is_priority'], [1,2,3,4])) {
         $priorityText = '<span class="text-warning-'.(4 + $booking['case']['is_priority']).'00" title="'.Yii::t('x', 'Priority').'">'.$booking['case']['is_priority'].'<i class="fa fa-star"></i></span> ';
         Yii::$app->params['page_title'] .= $priorityText;
    }
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
if ($theTour['owner'] == 'si') {
    Yii::$app->params['page_breadcrumbs'] = [
        ['B2B', 'b2b'],
        ['Tours', 'b2b/tours'],
        [substr($theTour['day_from'], 0, 7), 'b2b/tours?month='.substr($theTour['day_from'], 0, 7)],
        [$theTour['tour']['code'], 'tours/r/'.$theTour['tour']['id']],
    ];
} else {
    Yii::$app->params['page_breadcrumbs'] = [
        ['Tours', 'tours'],
        [substr($theTour['day_from'], 0, 7), 'tours?orderby=startdate&time='.substr($theTour['day_from'], 0, 7)],
        [$theTour['tour']['code'], 'tours/r/'.$theTour['tour']['id']],
    ];
}


// Calculate the time of notes and emails
$myTimeZone = Yii::$app->user->identity->timezone;
if (!in_array($myTimeZone, ['UTC', 'Europe/Paris', 'Asia/Ho_Chi_Minh'])) {
    $myTimeZone = 'Asia/Ho_Chi_Minh';
}

foreach ($theTour['bookings'] as $booking) {
    $bookingIdList[] = $booking['id'];
}
$tourOnlineFeedbacks = \common\models\CpLink::find()
    ->where(['booking_id'=>$bookingIdList])
    ->with([
        'createdBy'=>function($q){
            return $q->select(['id', 'name']);
        },
    ])
    ->asArray()
    ->all();

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
// Incidents
foreach ($theTour['incidents'] as $incident) {
    $time = DateTimeHelper::convert($incident['created_dt'], 'Y-m-d H:i:s', 'UTC', $myTimeZone);
    $timeTable[$time] = ['object'=>'incident', 'id'=>$incident['id'], 'title'=>$incident['name']];
}
// Online feedbacks
foreach ($tourOnlineFeedbacks as $onlfb) {
    $time = DateTimeHelper::convert($onlfb['created_dt'], 'Y-m-d H:i:s', 'UTC', $myTimeZone);
    $timeTable[$time] = ['object'=>'feedback-sent', 'id'=>$onlfb['id'], 'title'=>Yii::t('x', 'Feedback link sent')];
    if (!empty($onlfb['fb_submitted_dt'])) {
        $time = DateTimeHelper::convert($onlfb['fb_submitted_dt'], 'Y-m-d H:i:s', 'UTC', $myTimeZone);
        $timeTable[$time] = ['object'=>'feedback', 'id'=>$onlfb['id'], 'title'=>Yii::t('x', 'Feedback submitted')];
    }
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
                            'link'=>'@web/attachments/'.$file['id'],
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
                            'link'=>'@web/mails/'.$mail['id'].'/f?name='.urlencode($file['name']),
                            'size'=>$file['size'],
                        ];
                    }
                }
            }
        }
    }
    if ($item['object'] == 'feedback') {
        // TODO read files from feedback
    }
}

?>
<link href="https://fonts.googleapis.com/css?family=Mali" rel="stylesheet">
<style>

.con { position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px;}

@media (max-width: 1199px) {
    .con-1 {width:100%;}
    .con-2 {width:100%;}
}
@media (max-width: 799px) {
    .con-1-1 {width:100%;}
    .con-1-2 {width:100%;}
}
@media (min-width: 800px) and (max-width: 1199px) {
    .con-1-1 {width:50%; float:left;}
    .con-1-2 {width:50%; float:left;}
}
@media (min-width: 1200px) and (max-width: 1399px) {
    .con-1 {width: 41.66666667%; left:58.33333333%; float:left;}
    .con-2 {width: 58.33333333%; right:41.66666667%; float:left;}
    .con-1-1 {width:100%;}
    .con-1-2 {width:100%;}
}
@media (min-width: 1400px) and (max-width: 1599px) {
    .con-1 {width: 33.33333333%; left:66.66666667%; float:left;}
    .con-2 {width: 66.66666667%; right:33.33333333%; float:left;}
    .con-1-1 {width:100%;}
    .con-1-2 {width:100%;}
}
@media (min-width: 1600px) {
    .con-1 {width:50%; left:50%; float:left;}
    .con-2 {width:50%; right:50%; float:left;}
    .con-1-1 {width:50%; float:left;}
    .con-1-2 {width:50%; float:left;}
}

.note-list {list-style:none; padding:0; margin:0;}
    .note-list-item {list-style:none; border-top:1px solid #eee; padding:24px 0;}
    .note-list-item.first {border-top:none; padding-top:0;}
        .note-avatar {width:64px; height:64px; float:left;}
            .note-author-avatar {width:64px; height:64px;}
        .note-content {margin-left:80px;}
            a.note-author-name, .note-author-name {color:#6d4c41;}
            a.note-recipient-name, .note-recipient-name {color:#9C27B0;}
            .note-heading {margin-top:0;}
                .note-title {}
            .note-meta {}
            .note-file-list {margin-left:2em; margin-bottom: 1em;}
                .note-file-list-item {}
            .note-body {}
            .note-actions {}

@media (max-width: 479px) {
    .note-avatar {display:none;}
    .note-content {margin-left:0;}
}



.fb-title {font-family: 'Mali', cursive; color:#4caf50;}
.fb-content {font-family: 'Mali', cursive;}
body {background-color:#fff}
.uploader_file_preview {float:left; width:60px; height:60px; display:inline-block; margin-right:12px;}
.mb-12 {margin-bottom:12px;}
.-cke_chrome {border-radius:3px; border-color:#ddd!important;}
.-cke_top {margin:0!important; padding:0 8px!important; border-bottom-color:#eee!important; -background-color:#eee!important;}
.-cke_toolgroup {margin:0!important;}
.-cke_button, .cke_button:hover {border:0!important; padding:4px!important;}
.-cke_button:hover {cursor:pointer!important;background-color:#f5f5f5!important;}
.post-attachments {padding-left:24px;}
#div-edit-post .post-attachments {padding:7px 12px 7px 24px; background-color:#f6f6f6; -border:1px solid #ddd; border-top:0;}
.post-attachment {margin-top:4px;}
.text-tournote.text-blue, .text-tournote.text-blue a {color:blue!important;}
</style>
<div class="col-md-12 d-none">
    <?php if ($theTour['tour']['status'] == 'draft') { ?>
    <div class="alert alert-warning"><?= Yii::t('op', 'This tour has not been confirmed by Operation Department') ?></div>
    <?php } ?>
    <?php if ($theTour['op_finish'] == 'canceled') { ?>
    <div class="alert alert-danger"><?= Yii::t('op', 'This tour has been canceled') ?></div>
    <?php } ?>
</div>
<div class="col-md-12" style="display:none">
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class=""><a href="/products/r/<?= $theTour['id'] ?>"><?= Yii::t('op', 'Product') ?></a></li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= Yii::t('op', 'Sales') ?> <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                <li role="presentation" class="dropdown-header">CASES</li>
<?php
foreach ($theTour['bookings'] as $booking) {
?>
                <li class=""><a role="menuitem" href="<?= DIR ?>cases/r/<?= $booking['case']['id'] ?>"><?= $booking['case']['name'] ?></a></li>
<?php
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
<div class="con con-1">
    <div class="row">
        <div class="con con-1-1">
            <?php if (!empty($theTour['servicesPlus'])) { ?>
            <div class="alert alert-info">
            <?php foreach ($theTour['servicesPlus'] as $service) { ?>
                <div><i class="fa fa-fw fa-heart text-pink"></i> <?= Html::a($service['sv'], '/qhkh/service-plus?view=tour&tour='.$theTour['op_code'], ['class'=>'alert-link']) ?></div>
            <?php } ?>
            </div><!-- services plus -->
            <?php } ?>

            <?php if (!empty($theTour['presents'])) { ?>
            <div class="alert alert-info">
            <?php foreach ($theTour['presents'] as $present) { ?>
                <div><i class="fa fa-fw fa-gift text-slate" title="<?= Yii::t('x', 'Gift') ?>"></i> <?= date('j/n', strtotime($present['transaction_dt'])) ?> <?= Html::a($present['item']['name'], '/tours/gifts/'.$theTour['id'], ['class'=>'alert-link']) ?> &times; <?= number_format($present['qty']) ?></div>
            <?php } ?>
            </div><!-- presents -->
            <?php } ?>

            <?php if (!empty($theTour['incidents'])) { ?>
            <div class="alert alert-danger">
            <?php foreach ($theTour['incidents'] as $incident) { ?>
                <div><i class="fa fa-fw fa-bomb"></i> <?= date('j/n', strtotime($incident['incident_date'])) ?>: <?= Html::a($incident['name'], '/incidents/r/'.$incident['id'], ['class'=>'alert-link']) ?></div>
            <?php } ?>
            </div><!-- incidents -->
            <?php } ?>
            <?php if (!empty($theTour['complaints'])) { ?>
            <div class="alert alert-warning">
            <?php foreach ($theTour['complaints'] as $complaint) { ?>
                <div><i class="fa fa-fw fa-thumbs-down"></i> <?= date('j/n', strtotime($complaint['complaint_date'])) ?>: <?= Html::a($complaint['name'], '/complaints/r/'.$complaint['id'], ['class'=>'alert-link']) ?></div>
            <?php } ?>
            </div><!-- complaints -->
            <?php } ?>
            <div class="card">
                <table class="table table-narrow">
                    <tbody>
                        <?php if ($theTour['tour']['parentTour']) { ?>
                        <!-- PARENT TOUR -->
                        <tr>
                            <td class="warning">
                                <strong><?= Yii::t('x', 'Parent tour') ?>:</strong>
                                <?= Html::a($theTour['tour']['parentTour']['code'], '/tours/r/'.$theTour['tour']['parentTour']['id']) ?>
                            </td>
                        </tr>
                        <?php } ?>

                        <?php if ($theTour['tour']['childTours']) { ?>
                        <!-- PARENT TOUR -->
                        <tr>
                            <td class="warning">
                                <strong><?= Yii::t('x', 'Extensions') ?>:</strong>
                                <?php foreach ($theTour['tour']['childTours'] as $cntChildTour=>$childTour) { ?>
                                <?= $cntChildTour == 0 ? '' : ', ' ?><?= Html::a($childTour['code'], '/tours/r/'.$childTour['id']) ?>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>

                        <?php if ($theTour['tour']['pax_ratings'] != 0) { ?>
                        <tr>
                            <td><strong class="text-success"><?= $theTour['tour']['pax_ratings'] == 100 ? 10 : trim(number_format($theTour['tour']['pax_ratings'] / 10, 1), '.0') ?></strong>/10 (rated by pax)</td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td>
                                <strong>BH</strong>
<?php
$nameList = [];
foreach ($theTour['bookings'] as $booking) {
    $nameList[] = Html::a($booking['case']['owner']['nickname'], '@web/contacts/'.$booking['case']['owner']['id']);
}
echo implode(', ', $nameList);
?>
                                <strong>ĐH</strong>
<?php
$nameList = [];
foreach ($tourOperators as $user) {
    $nameList[] = Html::a($user['nickname'], '@web/contacts/'.$user['id'], ['title'=>$user['days'] == '' ? '' : 'Days '.$user['days']]);
}
echo implode(', ', $nameList);
?>
                                <strong>QH</strong>
<?php
$nameList = [];
foreach ($tourCSStaff as $user) {
    $nameList[] = Html::a($user['nickname'], '@web/contacts/'.$user['id']);
}
echo implode(', ', $nameList);


foreach ($theTour['bookings'] as $booking) {
    $theCase = $booking['case'];
    if ($theCase['is_priority'] != 'no') {
?>
                                <div>
                                </div>
<?php
    }
}
?>

                            </td>
                        </tr>
<?php
if (!empty($tourAgents)) {
    foreach ($tourAgents as $company) {
?>
                        <tr>
                            <td>
                                <div>
                                    <strong><?= Yii::t('x', 'TO/TA') ?></strong> <?= Html::a($company['name'], '@web/b2b/clients/r/'.$company['id']) ?>
                                    <?php if ($theTour['client_series'] != '') { ?><strong><?= Yii::t('x', 'Series') ?></strong> <?= Html::a($theTour['client_series'], '/tours/series?client='.$theTour['client_id']) ?><?php } ?>
                                </div>
                                <?php if ($theTourOld['client_name'] != '') { ?><?= Yii::t('x', 'for') ?> <strong><?= $theTourOld['client_name'] ?></strong><?php } ?>
<?php
        if ($theTourOld['client_logo'] != '' || $company['image'] != '') {
?>
                                <div><?= Html::img($theTourOld['client_logo'] != '' ? $theTourOld['client_logo'] : $company['image'], ['class'=>'img-fluid', 'style'=>'max-height:100px;']) ?></div>
<?php
        }
?>
                            </td>
                        </tr>
<?php
    }
} else {
    if ($theTour['owner'] == 'si') { ?>
                        <tr>
                            <td>
                                <div>
                                    <strong><?= Yii::t('x', 'TO/TA') ?></strong> <?= Html::a('Secret Indochina', 'https://www.secretindochina.com', ['target'=>'_blank']) ?>
                                    <?php if ($theTour['client_series'] != '') { ?><strong><?= Yii::t('x', 'Series') ?></strong> <?= Html::a($theTour['client_series'], '/tours/series?client='.$theTour['client_id']) ?><?php } ?>
                                </div>
                                <?php if ($theTourOld['client_name'] != '') { ?><?= Yii::t('x', 'for') ?> <strong><?= $theTourOld['client_name'] ?></strong><?php } ?>
                                <div><?= Html::img('/assets/img/logo_si_160922_1248x664.jpg', ['class'=>'img-fluid', 'style'=>'max-height:100px;']) ?></div>
                            </td>
                        </tr><?php
    }
}

$birthDays = [];
$theTour['day_until'] = date('Y-m-d', strtotime('+'.($theTour['day_count'] - 1).' days', strtotime($theTour['day_from'])));
// if (!empty($theTour['pax'])) {
//     foreach ($theTour['pax'] as $p) {
//         $bd = strtotime(substr($theTour['day_until'], 0, 4).substr($p['pp_birthdate'], 4));
//         if (strtotime($theTour['day_from']) <= $bd && $bd <= strtotime($theTour['day_until'])) {
//             $birthDays[] = '<span class="label label-danger"><i class="fa fa-birthday-cake"></i> '.date('j/n', strtotime($p['pp_birthdate'])).'</span> '.Html::a($p['name'], '@web/tours/pax/'.$theTour['id'].'?action=view&pax='.$p['id']);
//         }
//     }
// } else {
    foreach ($tourPax as $p) {
        if (!in_array(0, [$p['bday'], $p['bmonth'], $p['byear']])) {
            $bd = date_create_from_format('j/n/Y', $p['bday'].'/'.$p['bmonth'].'/'.substr($theTour['day_from'], 0, 4));
            $bd = strtotime($bd->format('Y-m-d'));
            $bd2 = date_create_from_format('j/n/Y', $p['bday'].'/'.$p['bmonth'].'/'.substr($theTour['day_until'], 0, 4));
            $bd2 = strtotime($bd2->format('Y-m-d'));
            if ((strtotime($theTour['day_from']) <= $bd && $bd <= strtotime($theTour['day_until'])) || (strtotime($theTour['day_from']) <= $bd2 && $bd2 <= strtotime($theTour['day_until']))) {
                $birthDays[] = '<span class="text-danger"><i class="fa fa-birthday-cake"></i> '.$p['bday'].'/'.$p['bmonth'].'</span> '.Html::a($p['name'], '@web/persons/r/'.$p['id']);
            }
        }
    }
// }
if (!empty($birthDays) || !empty($olderTours)) {
?>
                        <tr>
                            <td>
<?php
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
<?php
} // if tour notes

if (!empty($tourRefs)) {
?>
                        <tr>
                            <td>
<?php
    foreach ($tourRefs as $r) {
        /*
                $q = $db->query('SELECT id, code, name FROM at_tours t, at_pax p WHERE t.id=p.tour_id AND p.user_id=%i LIMIT 1', $r['user_id']);
                if ($q->countReturnedRows() > 0) {
                    $refTour = $q->fetchRow();
                } else {
                    $refTour = false;
                }
                */
        echo 'Ref. ', Html::a($r['name'], '@web/contacts/'.$r['user_id']);
        // TODO also tour code
    }
} // if empry ref
?>
                            </td>
                        </tr>
                        <tr>
                            <td>
<?php
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
                                            <div class="col" title="<?= $invoice['bill_to_name'] ?>">$ <?= Html::a($invoice['ref'], '@web/invoices/r/'.$invoice['id']) ?></div>
                                            <div class="col text-right text-nowrap" title="<?= $paymentStatusText ?>"><?= $invoice['stype'] == 'credit' ? '-' : '' ?><?= number_format($invoice['amount'], 2) ?> <span><?= $invoice['currency'] ?></span></div>
                                        </div>
<?php
    }
}
?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <div class="card-header bg-white">
                    <span class="font-weight-bold text-uppercase"><?= Yii::t('op', 'Itinerary') ?></span> <?= $theTour['day_count'] ?> <?= Yii::t('op', 'days') ?>, <?= Yii::t('op', 'from') ?> <?= date('j/n/Y', strtotime($theTour['day_from'])) ?> <?= Html::a(Yii::t('op', 'Itinerary'), '@web/products/r/'.$theTour['id']) ?> - <?= Html::a(Yii::t('x', 'Translate'), '@web/products/translate/'.$theTour['id'].'?language=vi') ?> - <?= Html::a(Yii::t('x', 'Notes'), '@web/tours/ctn/'.$theTour['id']) ?>
                </div>
                <table class="table table-narrow">
                    <tbody>
<?php
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
                            <td class="text-right text-muted" width="10" style="padding-left:8px!important; padding-right:0!important; vertical-align:top"><?= $cnt2 ?>.</td>
                            <td class="no-padding-left">
                                <div class="day-title" data-id="<?= $day['id'] ?>" title="Click to view detail">
                                    <strong style="<?= $thisDow == 'Sun' ? 'color:#c00' : '' ?>"><?= $jn ?></strong>
                                    <?php if (date('Y-m-d') == $thisDay) { ?><span class="badge badge-success"><?= Yii::t('op', 'Today') ?></span> <?php } ?>
                                    <?= Html::encode($day['name']) ?>
                                    <em><?= $day['meals'] ?></em>
                                </div>
<?php
            if (isset($theTour['tour']['cpt'])) {
                foreach ($theTour['tour']['cpt'] as $cpt) {
                    if ($cpt['dvtour_day'] == $thisDay) {
?>
                        <div style="font-size:90%;" class="text-blue">
                            <i class="fa fa-fw fa-bed"></i>
                            <?= Html::a($cpt['venue']['name'], '/cpt?tour='.$theTour['op_code'].'&search='.$cpt['venue']['name'], ['class'=>'text-blue']) ?>
                            <?= trim($cpt['qty'], '.00)') ?> <?= $cpt['unit'] ?>
                        </div>
<?php
                        break;
                    }
                }
            }


// Old
// foreach ($tourGuides as $guide) {
    //if ($guide['day'] == $thisDay) {
        //echo '<div style="font-size:90%; color:#fb8c00"><i class="fa fa-user"></i> ', $guide['fname'], ' ', $guide['lname'], ' - ', $guide['uphone'], '</div>';
    //}
// }

// Tour guides
foreach ($tourGuides as $guide) {
    if (strtotime(substr($guide['use_from_dt'], 0, 10)) <= $date && $date <= strtotime(substr($guide['use_until_dt'], 0, 10))) {
        echo '<div style="font-size:90%;" class="text-orange"><i class="fa fa-fw fa-user"></i> ';
        if ($guide['booking_status'] == 'confirmed') {
            // echo '[CFM] ';
        } else {
            echo '['.strtoupper($guide['booking_status']).'] ';
        }
        if ($guide['guide_user_id'] == 0) {
            echo $guide['guide_name'];
        } else {
            echo Html::a($guide['guide_name'], '/contacts/'.$guide['guide_user_id'].'?listtours=guide', ['class'=>'text-orange', 'title'=>Yii::t('x', 'View contact')]);
        }

        echo '</div>';
    }
}


// Drivers

foreach ($tourDrivers as $driver) {
    if (strtotime(substr($driver['use_from_dt'], 0, 10)) <= $date && $date <= strtotime(substr($driver['use_until_dt'], 0, 10))) {
        echo '<div style="font-size:90%;" class="text-teal"><i class="fa fa-car"></i> ';
        if ($driver['booking_status'] == 'confirmed') {
            // echo '[CFM] ';
        } else {
            echo '['.strtoupper($driver['booking_status']).'] ';
        }
        if ($driver['driver_user_id'] == 0) {
            $dname = $driver['driver_name'];
        } else {
            $dname = Html::a($driver['driver_name'], '/contacts/'.$driver['driver_user_id'].'?listtours=driver', ['class'=>'text-teal', 'title'=>Yii::t('x', 'View contact')]);
        }

        echo implode(' / ', [$driver['vehicle_type'], $driver['driver_company'], $dname]);
        echo '</div>';
    }
}

// Tour notes
if (!empty($theTour['tournotes'])) {
    foreach ($theTour['tournotes'] as $tourNote) {
        if ($tourNote['days'] == $cnt2) {
?>
                        <div title="<?= $tourNote['updatedBy']['name'] ?> <?= DateTimeHelper::convert($tourNote['updated_dt'], 'j/n/Y H:i', 'UTC', $myTimeZone); ?>" style="font-size:90%;" class="text-tournote text-<?= $tourNote['color'] ?>">
                            <?php if ($tourNote['icon'] != '') { ?><i class="fa fa-fw fa-<?= $tourNote['icon'] ?>"></i><?php } ?>
                            <?= trim($tourNote['note']) ?>
                            <?php if (in_array(USER_ID, [$tourNote['updated_by']])) { ?><a title="<?= Yii::t('x', 'Edit') ?>" class="text-muted" href="/tours/ctn/<?= $theTour['id'] ?>"><i class="fa fa-edit"></i></a><?php } ?>
                        </div>
<?php
        }
    }
}
?>
                            </td>
                        </tr>
                        <tr class="day-body d-none" id="day-body-<?= $day['id'] ?>">
                            <td></td>
                            <td>
                                <?= Html::a('Dịch tiếng Việt', 'https://translate.google.com/#fr/vi/'.urlencode(str_replace(['_', '*'], [' ', ' '], $day['body'])), ['rel'=>'external', 'style'=>'font-size:90%;']) ?>
                                <?= Markdown::process($day['body']) ?>
                            </td>
                        </tr>
<?php
                        }
                    }
                }
            }
?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="con con-1-2">
            <div style="width:100px;" class="pull-right text-right">
                <?= Html::a(Yii::t('op', '+New'), '@web/tasks/c?rtype=tour&rid='.$theTour['tour']['id'], ['class'=>'task-add text-muted', 'data-rtype'=>'tour', 'data-rid'=>$theTourOld['id'], 'data-rname'=>$theTourOld['code'].' - '.$theTourOld['name']]) ?>
            </div>
            <p class="font-weight-bold text-uppercase"><?= Yii::t('op', 'Related tasks') ?></p>
            <div id="task-list" style="border-left:4px solid #d6c6b6; padding-left:8px; margin-left:8px;;">
                <?php if (empty($theTour['tour']['tasks'])) { ?><p><?= Yii::t('op', 'No tasks found') ?><?php } ?>
                <?php
                $thisYear = date('Y');
                $today = date('Y-m-d');
                foreach ($theTour['tour']['tasks'] as $t) {
                ?>
                <div id="div-task-<?=$t['id']?>" class="task-list-item task <?=$t['status'] == 'on' && strtotime($t['due_dt']) < strtotime(NOW) ? 'task-overdue' : ''?> <?=$t['status'] == 'off' ? 'task-done' : ''?>">
                    <i id="icon-<?=$t['id']?>" data-task_id="<?=$t['id']?>" title="<?= Yii::t('op', 'Check/Uncheck') ?>" class="cursor-pointer task-check fa fa-<?= $t['status'] == 'on' ? '' : 'check-' ?>square-o"></i>
                    <span class="task-date"><?php
                    if ($t['fuzzy'] != 'date') {
                        if (substr($t['due_dt'], 0, 4) == $thisYear) {
                            echo date('j/n', strtotime($t['due_dt']));
                            if (substr($t['due_dt'], 0, 10) == $today) {
                                echo '<span class="task-today">', Yii::t('x', 'Today'), '</span> ';
                            }
                        } else {
                            echo date('j/n/Y', strtotime($t['due_dt']));
                        }
                    } ?>
                    </span>
                    <span class="task-time"><?php
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
                    <span class="task-priority"><?php if ($t['is_priority'] == 'yes') { ?><i class="fa fa-star text-danger" title="Priority"></i><?php } ?></span>
                    <span title="<?= $t['createdBy']['name'] ?> <?= DateTimeHelper::convert($t['uo'], 'j/n/Y H:i', 'Asia/Ho_Chi_Minh', $myTimeZone) ?>"><?= USER_ID == 1 || $t['ub'] ? Html::a($t['description'], '@web/tasks/'.$t['id'].'/u', ['class'=>'task-description', 'data-id'=>$t['id'], 'title'=>$t['cb'] == USER_ID ? 'Edit task' : $t['createdBy']['name']]) : $t['description'] ?></span>
                    <span class="task-assignees">
                        <?php foreach ($t['taskAssign'] as $cnt=>$taskAssign) { ?>
                        <?= $cnt > 0 ? ', ': '' ?>
                        <span id="assignee-<?=$t['id']?>-<?=$taskAssign['user_id']?>" class="task-assignee text-muted <?= $taskAssign['completed_dt'] === null ? '' : 'task-assignee-done' ?>"><?= $taskAssign['user_id'] == USER_ID ? 'Tôi' : $taskAssign['assignee']['name'] ?></span>
                        <?php } ?>
                    </span>
                </div>
                <?php } // foreach tasks ?>
            </div>

            <hr>
            <p class="font-weight-bold text-uppercase"><?= Yii::t('op', 'Related cases, bookings, pax') ?></p>
            <div class="card">
                <table class="table table-narrow">
                    <tbody>
<?php
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
                                <?= Html::a(Yii::t('op', 'Pax'), '@web/tours/'.$theTour['id'].'/pax?booking='.$booking['id']) ?>
                                <?php
                                if (USER_ID == 1) {
                                    echo ' / ', Html::a(Yii::t('x', 'Stats'), '@web/tours/stats/'.$theTour['id']);
                                } ?>
                                <?php /* if ($booking['id'] == 31825) { ?>
                                /
                                <?= Html::a('View reg info', '@web/bookings/reg-info/'.$booking['id']) ?>
                                <?php } */ ?>

                                <?php /* foreach ($tourRegInfo as $reginfo) { ?>
                                <?php if ($reginfo['booking_id'] == $booking['id']) { ?>
                                /
                                <?= Html::a('View reg info', '@web/bookings/reg-info/'.$booking['id']) ?>
                                <?php } ?>
                                <?php } */ ?>
                            </td>
                        </tr>
<?php
    foreach ($tourPax as $person) {
        if ($person['booking_id'] == $booking['id']) {
?>
                        <tr>
                            <td width="15" class="text-right text-muted"><?= ++ $paxCnt ?></td>
                            <td class="pl-0">
                                <a title="View passport info" class="pull-right text-muted" href="/tours/pax/<?= $theTour['id'] ?>?action=list&pax_id=<?= $person['id'] ?>"><i class="fa fa-file-text-o"></i></a>
                                <i title="<?= $person['gender'] ?>" class="fa fa-fw fa-<?= $person['gender'] ?> color-<?= $person['gender'] ?>"></i>
                                <span class="flag-icon flag-icon-<?= $person['country_code'] ?>"></span>
                                <?= Html::a($person['name'], '@web/contacts/'.$person['id'], ['title'=>$person['fname'].' * '.$person['lname']]) ?>
                                <?php
                                if ($person['bday'] != 0 && $person['bmonth'] != 0 && $person['byear'] != 0) {
                                    $datetime1 = new DateTime($person['byear'].'-'.$person['bmonth'].'-'.$person['bday']);
                                    $datetime2 = new DateTime($theTour['day_until']);
                                    $age = $datetime1->diff($datetime2);
                                    $title = $age->format(Yii::t('op', '%yy %mm %dd when tour ends'));
                                }
                                ?>
                                <em title="(<?= $person['bday'].'/'.$person['bmonth'].'/'.$person['byear']?>) <?= $title ?? '' ?>"><?= $person['byear'] == 0 ? '' : isset($age) ? $age->y : '' ?></em>
                            </td>
                        </tr>

<?php
        }
    }

    if (!empty($tourPaxCanceled)) { ?>
                        <tr class="alpha-danger"><td colspan="2"><?= Yii::t('x', 'Canceled') ?>: <?php
        $canceledPaxCount = 0;
        foreach ($tourPaxCanceled as $person) {
            if ($person['booking_id'] == $booking['id']) {
                if ($canceledPaxCount > 0) {
                    echo ', ';
                }
                echo Html::a($person['name'], '@web/contacts/'.$person['id'], ['title'=>$person['fname'].' * '.$person['lname']]);
                $canceledPaxCount ++;
            }
        } ?>
                        </td></tr><?php
    }
} // foreach bookings
?>
                    </tbody>
                </table>
            </div>

            <?php
            $tourLichxe = \common\models\Lichxe::find()
                ->where(['tour_id'=>$theTour['id']])
                ->with(['updatedBy'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                    },
                ])
                ->asArray()
                ->all();
            if (!empty($tourLichxe)) { ?>
            <span class="pull-right"><?= Html::a(Yii::t('x', 'View all'), '/tours/'.$theTour['id'].'/lichxe', ['class'=>'text-muted']) ?></span>
            <p class="font-weight-bold text-uppercase">
                <i class="fa fa-car"></i> <?= Yii::t('x', 'Vehicle bookings') ?></p>
            <div class="mb-1em">
                <?php foreach ($tourLichxe as $lx) { ?>
                <div> &nbsp; <?= Html::a('#'.$lx['id'], '/tours/'.$theTour['id'].'/lichxe?office='.$lx['vp'].'&action=print&not-fit&lien&paxlist=yes&lichxe='.$lx['id']) ?> (<?= Html::a('web', '/tours/'.$theTour['id'].'/lichxe?output=html&office='.$lx['vp'].'&action=print&not-fit&lien&paxlist=yes&lichxe='.$lx['id']) ?>) <?= $lx['chuxe'] ?> <?= $lx['loaixe'] ?> <span class="text-muted"><?= $lx['updatedBy']['name'] ?> <?= date('j/n/Y', strtotime($lx['updated_dt'])) ?></span></div>
                <?php } ?>
            </div>
            <?php } // empty tourLichxe ?>

            <p class="font-weight-bold text-uppercase"><i class="fa fa-file-o"></i> <?= Yii::t('op', 'All files') ?></p>
            <div class="mb-1em">
                <?php foreach ($allFileList as $file) { ?>
                <div>+ <?= Html::a($file['name'], $file['link']) ?> <span class="text-muted"><?= Yii::$app->formatter->asShortSize($file['size'], 0) ?></span></div>
                <?php } ?>
            </div>

            <?php if (!empty($tourFeedbacks)) { ?>
            <div class="mb-2">
                <p class="font-weight-bold text-uppercase"><i class="fa fa-smiley-o"></i>  <?= Yii::t('op', 'Customer feedback') ?></p>
                <?php foreach ($tourFeedbacks as $feedback) { ?>
                <div><i class="fa fa-<?= $feedback['say'] ?>-o"></i> <?= $feedback['who'] ?> : <?= $feedback['what'] ?> : <?= $feedback['feedback'] ?></div>
                <?php } // foreach feedback ?>
            </div>
            <?php } // if not empty feedback ?>

            <hr>
            <p class="text-muted"><i class="fa fa-info-circle"></i> <?= Yii::t('op', 'This tour was updated on') ?> <?= DateTimeHelper::convert($theTour['tour']['updated_dt'], 'j/n/Y H:i') ?> <?= Yii::t('op', 'by') ?> ?. <?= Yii::t('op', 'The timezone of all messages is') ?> <?= $myTimeZone ?></p>

        </div>
    </div>
</div>
<div class="con con-2">
    <ul class="note-list">
        <li class="first note-list-item clearfix" id="div-my-editor">
            <div class="note-avatar"><?= Html::a(Html::img('/timthumb.php?zc=1&w=100&h=100&src='.Yii::$app->user->identity->image, ['class'=>'note-author-avatar rounded-circle']), '@web/contacts/'.USER_ID) ?></div>
            <div class="note-content">
                <?= $this->render('_editor_new.php', ['theTour'=>$theTour]) ?>
            </div>
        </li>
<?php
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
            } elseif ($item['object'] == 'feedback' || $item['object'] == 'feedback-sent') {
                //break;
                foreach ($tourOnlineFeedbacks as $onlfb) {
                    if ($onlfb['id'] == $item['id']) {
                        include('tour_r__feedback.php');
                    }
                }
            } elseif ($item['object'] == 'sysnote') {
                foreach ($theSysnotes as $note) {
                    if ($note['id'] == $item['id']) {
                        // BEGIN SYSNOTE
?>
        <li class="note-list-item clearfix">
            <?php if ($note['action'] == 'kase/close') { ?>
            <div class="note-avatar"><i class="fa fa-lock text-info fa-3x note-author-avatar"></i></div>
            <div class="note-content" style="border-color:#31708F">
                <h5 class="note-heading">
                    <i class="fa fa-lock"></i>
                    <?= Html::a(Html::encode($note['user']['name']), '@web/contacts/'.$note['user']['id'], ['class'=>'note-author-name']) ?>
                    closed this case
                </h5>
                <div class="mb-1em">
                    <span class="text-muted"><?= $time ?></span>
                </div>
                <div class="note-body"><?= $note['info'] ?></div>
            </div>
            <?php } ?>
        </li>
<?php
                        // END SYSNOTE
                    }
                }
            } elseif ($item['object'] == 'incident') {
                //break;
                foreach ($theTour['incidents'] as $incident) {
                    if ($incident['id'] == $item['id']) {
                        include('tour_r__incident.php');
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
.task-assignee-done {text-decoration:line-through;}
        #div-post-here .hide-element { display: none }
        .action-u-message:hover, .action-delete-post:hover {cursor: pointer;}
    </style>
<?php

$js = <<<'JS'
    // Task check
    $('#task-list').on('click', 'i.task-check', function(){
        var task_id = $(this).data('task_id');
        $.post('/tasks/ajax', {action:'check', task_id:task_id}, function(data){
            if (data.status) {
                if (data.status == 'OK') {
                    $('span#assignee-' + task_id + '-' + '1').toggleClass('task-assignee-done');
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
        $('tr#day-body-' + id).toggleClass('d-none');
    });

    // Hash tags
    var tags = ['important', 'urgent', 'client', 'reservation-status'];
    var tags = $.map(tags, function(value, i) {return {key: value, name:value}});

    var tag_config = {
      at: '#',
      data: tags,
      tpl: '<li data-value="#${name}">${name}</li>',
      show_the_at: true
    }

    // $('.nicescroll').each(function(){
    //     new PerfectScrollbar($(this),{
    //         wheelSpeed: 2,
    //         wheelPropagation: true
    //     });
    // })
    // var ps = new PerfectScrollbar('.nicescroll',{
    //     wheelSpeed: 2,
    //     wheelPropagation: true
    // });
// Nice scroll
// $('.nicescroll').niceScroll({
//     mousescrollstep: 100,
//     cursorcolor: '#ccc',
//     cursorborder: '',
//     cursorwidth: 3,
//     hidecursordelay: 100,
//     autohidemode: 'scroll',
//     horizrailenabled: false,
//     preservenativescrolling: false,
//     railpadding: {
//         right: 0.5,
//         top: 1.5,
//         bottom: 1.5
//     }
// });


JS;

$js .= <<<'TXT'
var CurentMessage = null;
var actionPost = '';
    $('#title').atwho(tag_config);


// Delete posts
$('.action-delete-post').on('click', function(e){
    e.preventDefault();

    var url = $(this).attr('href')
    post_id = $(this).data('id')
    var div = $('.note-list-item#note-list-item-' + post_id)

    if (!confirm('Delete message now?' + "\n" + 'All related attachments will also be deleted.' + "\n" + 'This action is cannot be undone.')) {
        return false;
    }

    div.block({
        message: '<div><i class="fa fa-refresh fa-spin"></i> Deleting post...</div>',
        css: { border: '3px solid #a00', padding:20 }
    });

    $.post(url + '?xh', {
        })
    .done(function(){
        $('.note-list-item#note-list-item-' + post_id).remove()
        })
    .fail(function(){
        alert('Error deleting post')
        })
    .always(function(){
        $('.note-list-item#note-list-item-' + post_id).unblock();
        })
})
var defaultTags = $("#replyto").val();
// REPLY POST INLINE
$(document).on('click', '.action-reply-post', function(e){
    e.preventDefault()
    if ($(this).hasClass('replying')) {
        return false;
    }
    cancelAllPosts()
    $(this).addClass('replying')
    CurentMessage = $(this).closest('.note-list-item');

    var div = $(this).closest('.note-content').find('.you-are-replying:eq(0)')
    div.removeClass('d-none')

    post_thread_id = $(this).data('thread_id')

    $('#title').closest('.form-group').hide()

    $('#post-form').removeClass('d-none').appendTo(div);

    var editor1 = CKEDITOR.replace('editor1', CKEconfig)
    editor1.once( 'instanceReady', function() {
        this.focus()
    });

    $("#replyto").val('').trigger('change');
    var ar_reply = [];
    if ($(this).data("reply_ids") != 0) {
        ar_reply = $(this).data("reply_ids").split(',');
    }
    $("#replyto").val(ar_reply).trigger('change');

    // $('html, body').animate({
    //     scrollTop: $("#post-form").offset().top
    // }, 500);
});

$(document).on('click', '.action-u-message', function(){
    if ($(this).hasClass('updating')) {
        return false;
    }
    $('.action-u-message.updating').removeClass('updating');
    $(this).addClass('updating');

    var clicked = $(this);
    CurentMessage = clicked.closest('.note-list-item');
    post_id = CurentMessage.data('id');
    actionPost = 'edit';
    //call form
    $('#post-form').append($('#post-form')).insertAfter(CurentMessage).removeClass('d-none');
    $('#title').val('').closest('.form-group').show();
    if (CKEDITOR.instances.editor1) {
        CKEDITOR.instances.editor1.destroy()
    }
    CKEDITOR.replace('editor1', CKEconfig);
    CKEDITOR.instances.editor1.setData('');
    $("#replyto").val('').trigger('change');
    CurentMessage.hide();
    //get info note
    $.ajax({
        url: '/posts/u/' + post_id,
        method: 'GET',
    }).done(function(response) {




        var thePost = JSON.parse(response).thePost;
        if (thePost.n_id != 0) {
            $('#title').closest('.form-group').hide();
        } else {
            $('#title').val(thePost.title).closest('.form-group').hide();
        }
        CKEDITOR.instances.editor1.setData(thePost.body);


        var ar_reply = [];
        $.each(JSON.parse(response).replyToIdList, function(index, id_tag){
            ar_reply.push(id_tag);
        });
        $("#replyto").val(ar_reply).trigger('change');

        if(thePost.files.length > 0) {
            var html_file_uploaded = '<div class="table table-striped" id="uploaded-previews">';
            $.each(thePost.files, function(index, file){
                var file_id = file.id,
                    href = "@web/attachments/" + file_id,
                    file_name = file.name,
                    file_size = file.size,
                    file_time_uploaded = file.uo;
                html_file_uploaded += '<div class="file-row">' +
                    '<div class="preview"><a href="'+href+'"><img data-dz-thumbnail="" src="/assets/img/placeholder.jpg" style="width:64px; height:64px;"></a></div>' +
                    '<div>' +
                       ' <div>' +
                            '<span class="name" data-dz-name=""><a href="' + href + '">' + file_name + '</a></span>' +
                            '— <small class="size text-muted" data-dz-size=""><strong>' + file_size + '</strong> KB</small>' +
                            '<small class="pull-right text-muted">' + file_time_uploaded + '</small>' +
                        '</div>' +
                        '<div>' +
                            '<a class="text-danger action-remove-file" href="#" data-file_id="' + file_id + '">' +
                                '<i class="fa fa-trash-o"></i> <span>Delete</span>' +
                            '</a>' +
                            '<div class="div-unremove-file" style="display:none;"> File will be removed when form is submitted. —' +
                                '<a href="#" class="text-info action-unremove-file" data-file_id="' + file_id + '">' +
                                    '<i class="fa fa-trash-o"></i> <span>Restore</span>' +
                                '</a>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';
            });
            html_file_uploaded += '</div>';
            $(html_file_uploaded).insertBefore($("#previews"));
        }
    }).fail(function(){
        alert('Fail to load data!');
    });
    return false;
});
$(document).on('click', '.action-cancel-post', function(e){
    e.preventDefault();
    if (CurentMessage != null) {
        CurentMessage.show();
    }
    CurentMessage = null;
    cancelAllPosts();
})

$('.note-content').on('mouseenter', function(){
    // $(this).find('.div-action-reply-post span').toggleClass('d-none')
})

TXT;


$this->registerCssFile(DIR.'assets/at.js_0.4.12/css/jquery.atwho.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile(DIR.'assets/at.js_0.4.12/js/jquery.caret.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile(DIR.'assets/at.js_0.4.12/js/jquery.atwho.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js', ['depends'=>'yii\web\JqueryAsset']);

// $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js', ['depends'=>'yii\web\JqueryAsset']);
// $this->registerJsFile(DIR.'assets/autosize_1.18.7/jquery.autosize.min.js', ['depends'=>'yii\web\JqueryAsset']);
// $this->registerJsFile(DIR.'assets/jquery.countdown_2.0.4/jquery.countdown.min.js', ['depends'=>'yii\web\JqueryAsset']);

$this->registerJs($js);

// include('_plupload_inc.php');
// include('_ckeditor_inc.php');
// if (in_array(USER_ID, $testGroup)) {
    // include('_tour_r__js.php');
// }

include(Yii::getAlias('@app').'/views/tasks/_tasks_edit_modal.php');
