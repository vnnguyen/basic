<?
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\HtmlPurifier;
use app\helpers\DateTimeHelper;

include_once('_kase_inc.php');

$class = 'meh';
if ($theCase['stats']['prospect'] < 3) {
    $class = 'frown';
} elseif ($theCase['stats']['prospect'] > 3) {
    $class = 'smile';
}

Yii::$app->params['page_title'] = $theCase['name'];

Yii::$app->params['page_small_title'] = '';
if ($theCase['status'] == 'closed') {
    Yii::$app->params['page_small_title'] .= '<i class="fa fa-lock"></i> ';
} elseif ($theCase['status'] == 'onhold') {
    Yii::$app->params['page_small_title'] .= '<i class="fa fa-clock-o"></i> ';
}
if ($theCase['deal_status'] == 'won') {
    Yii::$app->params['page_small_title'] .= '<i class="fa fa-dollar text-success"></i> ';
} elseif ($theCase['deal_status'] == 'lost') {
    Yii::$app->params['page_small_title'] .= '<i class="fa fa-dollar text-danger"></i> ';
}

if ($theCase['stats']) {
    if ($theCase['stats']['req_countries'] != '') {
        Yii::$app->params['page_small_title'] .= $theCase['stats']['pax_count'] != '' ? $theCase['stats']['pax_count'].'p ' : '';
        Yii::$app->params['page_small_title'] .= $theCase['stats']['day_count'] != '' ? $theCase['stats']['day_count'].'d ' : '';
        if ($theCase['stats']['pa_start_date'] != '') {
            Yii::$app->params['page_small_title'] .= implode('/', array_reverse(explode('-', $theCase['stats']['pa_start_date'])));
        }
        $reqCountries = explode('|', $theCase['stats']['req_countries']);
        if (!empty($reqCountries)) {
            foreach ($reqCountries as $reqCountry) {
                Yii::$app->params['page_small_title'] .= ' <span class="flag-icon flag-icon-'.$reqCountry.'"></span>';
            }
        }
    }
}

if ($theCase['is_b2b'] == 'yes') {
    Yii::$app->params['page_breadcrumbs'] = [
    ['B2B', 'b2b'],
    ['Cases', 'b2b/cases'],
    ['View'],
    ];
} else {
    Yii::$app->params['page_breadcrumbs'][] = ['By '.$theCase['owner']['nickname'], '@web/cases?owner_id='.$theCase['owner']['id']];
    Yii::$app->params['page_breadcrumbs'][] = ['View', '@web/cases/r/'.$theCase['id']];
}


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

// kase/create
$theSysnotes[] = [
    'id'=>1,
    'created_at'=>$theCase['created_at'],
    'user_id'=>$theCase['created_by'],
    'action'=>'kase/create',
    'user'=>[
    'id'=>$theCase['created_by'],
    'nickname'=>$theCase['createdBy']['nickname'],
    ],
];
foreach ($theSysnotes as $note) {
    $time = DateTimeHelper::convert($note['created_at'], 'Y-m-d H:i:s', 'UTC', $myTimeZone);
    $timeTable[$time] = ['object'=>'sysnote', 'id'=>$note['id'], 'title'=>$note['action']];
}

foreach ($inboxMails as $mail) {
    $time = DateTimeHelper::convert($mail['created_at'], 'Y-m-d H:i:s', 'UTC', $myTimeZone);
    $timeTable[$time] = ['object'=>'mail', 'id'=>$mail['id'], 'title'=>$mail['subject']];
}

foreach ($caseInquiries as $inquiry) {
    $time = DateTimeHelper::convert($inquiry['created_at'], 'Y-m-d H:i:s', 'UTC', $myTimeZone);
    $timeTable[$time] = ['object'=>'inquiry', 'id'=>$inquiry['id'], 'title'=>$inquiry['name']];
}
krsort($timeTable);

// File list
$allFileList = [];
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
                    $mail['files'] = @unserialize($mail['files']);
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
// var_dump($note);die;
?>
<style>
    body {background-color:#fff;}
    .editable-click, a.editable-click, a.editable-click:hover {border:none!important;}
    .editable-empty, a.editable-empty {color:#999!important; border-bottom:1px dotted #999!important; font-style:normal!important;}
    i.fa-smile-o {color:#090!important;}
    i.fa-frown-o {color:#c00!important;}

    .note {overflow: hidden;}
    #wrap_note {background: #f3f3f3; border: 1px solid #cdcdcd; padding: 5px; border-radius: 2px; min-height: 100px; overflow: auto;}
</style>
<div class="col col-1">
    <div class="row">
        <div class="col col-1-1">
            <div class="note">
                <div>
                    <p>Note</p>
                </div>
                <div id="wrap_note" data-note_id="<?= ($kNote)? $kNote['id']: 0?>" data-case_id="<?= $theCase['id']?>"><?= ($kNote)? $kNote['value']: ''?></div>
            </div><br>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <span class="label status <?= $theCase['status'] ?>">STATUS: <?= strtoupper($theCase['status']) ?></span>
                        <? if ('yes' == $theCase['is_b2b']) { ?>
                        <span class="label b2b">B2B</span>
                        <? } ?>
                        <?
                        if ($theCase['deal_status'] == 'won') {
                            echo '<span class="label label-success">SALE:WON</span>';
                        } else {
                            if ($theCase['status'] == 'closed') {
                                echo '<span class="label label-danger">SALE:LOST</span>';
                            } else {
                                echo '<span class="label label-default">SALE:PENDING</span>';
                            }
                        }
                        ?>
                        <? if ('yes' == $theCase['is_priority']) { ?><span class="label label-info">PRIORITY</span><? } ?>
                    </div>
                    <div class="heading-elements">
                        <ul class="heading-thumbnails">
                            <li><img title="<?= $theCase['owner']['name'] ?>" class="img-circle" src="/timthumb.php?w=100&amp;h=100&amp;src=<?= $theCase['owner']['image'] ?>"></li>
                        </ul>
                    </div>
                </div>
                <table class="table table-bordered table-xxs">
                    <tbody>
                        <tr>
                            <td>
                                <strong><?= Yii::t('k', 'Prospect') ?></strong>
                            </td>
                            <td>
                                <?= Html::a(str_repeat('<i class="fa fa-'.$class.'-o"></i> ', $theCase['stats']['prospect']), '#', ['class'=>'editable-prospect', 'data-name'=>'prospect', 'data-type'=>'select', 'data-pk'=>$theCase['id'], 'data-url'=>DIR.'cases/stats', 'data-title'=>'Tiềm năng']) ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong><?= Yii::t('k', 'Owner') ?></strong>
                            </td>
                            <td>
                                <?= Html::a($theCase['owner']['nickname'], '@web/users/r/'.$theCase['owner']['id']) ?> <span class="text-muted"><?= Yii::t('k', 'from') ?> <?= date('j/n', strtotime($theCase['ao'])) ?></span><br>
                            </td>
                        </tr>
                        <? if ($theCase['cofr'] == 13) { ?>
                        <tr><td><strong>In France:</strong></td><td><?= Html::a('Hoa Bearez', '@web/users/r/13') ?></td></tr>
                        <? } ?>
                        <? if ($theCase['cofr'] == 767) { ?>
                        <tr><td><strong>In France:</strong></td><td><?= Html::a('Mme Xuân', '@web/users/r/767') ?></td></tr>
                        <? } ?>
                        <? if ($theCase['cofr'] == 5246) { ?>
                        <tr><td><strong>In France:</strong></td><td><?= Html::a('Arnaud Levallet', '@web/users/r/5246') ?></td></tr>
                        <? } ?>
                        <!--tr>
                            <td><strong>Air tickets</strong></td>
                            <td>
                                <? if ($theCase['at_who'] == 767) { ?><?= Html::a('Mme Xuân', '@web/users/r/767') ?> (@Lyon, France) <?= Html::a('Remove', '@web/kases/mme-xuan/'.$theCase['id'], ['style'=>'color:red']) ?><? } ?>
                                <? if ($theCase['at_who'] == 0) { ?><?= Html::a('Add Mme Xuan @Lyon, France', '@web/kases/mme-xuan/'.$theCase['id'], ['style'=>'color:red']) ?><? } ?>
                            </td>
                        </tr-->

                        <tr><td><strong><?= Yii::t('k', 'How contacted') ?></strong></td>
                            <td>
                                <?= $caseHowContactedList[$theCase['how_contacted']] ?? $theCase['how_contacted'] ?>
                                <? if ($theCase['how_contacted'] == 'web') { ?>
                                <span class="text-muted"><?= $theCase['web_referral'] ?></span>
                                <? if (substr($theCase['web_referral'], 0, 6) == 'search' || $theCase['web_referral'] == 'ad/adwords') { ?>
                                <span class="text-warning"><?= $theCase['web_keyword'] ?></span>
                                <? } // if web ref?>
                                <? } // if web ?>
                                <? if ($theCase['company']) { ?>
                                = <?= Html::a($theCase['company']['name'], '@web/companies/r/'.$theCase['company']['id']) ?>
                                <? if ($theCase['company']['image'] != '') { ?>
                                <div><?= Html::img($theCase['company']['image'], ['class'=>'img-responsive', 'style'=>'max-height:100px;']) ?></div>
                                <? } ?>
                                <? } ?>
                            </td>
                        </tr>
                        <tr><td><strong><?= Yii::t('k', 'How found') ?></strong></td>
                            <td>
                                <?= Yii::t('k', $caseHowFoundList[$theCase['how_found']] ?? $theCase['how_found']) ?>
                                <? if ($theCase['referrer']) { ?>
                                <?= Yii::t('k', 'from') ?> <?= Html::a($theCase['referrer']['name'], '@web/users/r/'.$theCase['referrer']['id']) ?>
                                <? if (isset($theCase['referrer']['tours'])) { ?>
                                |
                                <? foreach ($theCase['referrer']['tours'] as $tour) { ?>
                                <?= Html::a($tour['code'], '@web/tours/r/'.$tour['id']) ?>
                                <? break; ?>
                                <? } ?>
                                <? } ?>
                                <? } ?>
                                <?
                                if ($theCase['people']) {
                                    foreach ($theCase['people'] as $person) {
                                        if (isset($person['bookings'])) {
                                            foreach ($person['bookings'] as $booking) {
                                                if (isset($booking['product']) && strtotime($booking['product']['day_from']) < strtotime($theCase['created_at'])) {
                                                    echo ' &middot; ', Html::a($booking['product']['op_code'], '@web/products/r/'.$booking['product']['id']);
                                                }
                                            }
                                        }
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                        <? if ($theCase['info'] != '') { ?>
                        <tr><td><strong>Summary</strong></td><td><?= $theCase['info'] ?></td></tr>
                        <? } ?>
                    </tbody>
                </table>
            </div>

            <div class="mb-1em">
                <div style="width:100px;" class="pull-right text-right">
                <?= Html::a('Add', '@web/persons/c?next=case&case_id='.$theCase['id'], ['class'=>'text-muted']) ?>
                &middot;
                <?= Html::a('View all', '@web/cases/people/'.$theCase['id'], ['class'=>'text-muted']) ?>
                </div>
                <p class="text-bold text-uppercase text-warning"><?= Yii::t('k', 'Bookings') ?></strong></p>
                <? if (!$theCase['bookings']) { ?>
                <p>No bookings found.</p>
                <hr>
                <? } else { ?>
                <table class="table table-bordered table-condensed">
                    <? foreach ($theCase['bookings'] as $booking) { ?>
                    <tr>
                        <td>
                            <?= Html::img(DIR.'timthumb.php?w=100&h=100&src='.$booking['createdBy']['image'], ['style'=>'width:20px; height:20px;']) ?>
                            <i class="fa fa-file-text-o popovers text-muted"
                            data-trigger="hover"
                            data-title="<?= Html::encode($booking['product']['title']) ?>"
                            data-placement="right"
                            data-html="true"
                            data-content="
                            <?
                            if ($booking['product']['days']):
                                $dayIds = explode(',', $booking['product']['day_ids']);
                            if (count($dayIds) > 0) {
                                $cnt = 0;
                                foreach ($dayIds as $id) {
                                    foreach ($booking['product']['days'] as $day) {
                                        if ($day['id'] == $id) {
                                            echo '<strong>', date('d', strtotime("+ $cnt days", strtotime($booking['product']['day_from']))), '</strong> ', Html::encode($day['name']), ' <em>', $day['meals'], '</em><br>';
                                            $cnt ++;
                                        }
                                    }
                                }
                            }
                            endif;
                            ?>
                            "></i>
                            <span class="dropdown">
                                <a data-toggle="dropdown" class="label status <?= $booking['status'] ?>" href="#"><?= strtoupper($booking['status']) ?></a><b class="caret"></b>
                                <ul class="dropdown-menu">
                                    <? if ($booking['status'] != 'won') { ?>
                                    <li><a href="<?= DIR ?>bookings/mw/<?= $booking['id'] ?>">Mark as WON</a></li>
                                    <? } ?>
                                    <? if ($booking['status'] == 'pending') { ?>
                                    <li><a href="<?= DIR ?>bookings/ml/<?= $booking['id'] ?>">Mark as LOST</a></li>
                                    <? } ?>
                                    <? if ($booking['status'] == 'lost') { ?>
                                    <li><a href="<?= DIR ?>bookings/mp/<?= $booking['id'] ?>">Mark as PENDING</a></li>
                                    <? } ?>
                                    <? if ($booking['status'] != 'won') { ?>
                                    <li class="divider"></li>
                                    <? } ?>
                                    <li><a href="<?= DIR ?>bookings/r/<?= $booking['id'] ?>">View booking</a></li>
                                    <li><a href="<?= DIR ?>bookings/u/<?= $booking['id'] ?>">Edit booking</a></li>
                                    <? if ($booking['status'] != 'won') { ?>
                                    <li><a href="<?= DIR ?>bookings/d/<?= $booking['id'] ?>">Delete booking</a></li>
                                    <? } else { ?>
                                    <li><a href="<?= DIR ?>bookings/report/<?= $booking['id'] ?>">Report</a></li>
                                    <!--li><a href="<?= DIR ?>bookings/d/<?= $booking['id'] ?>">Cancel booking</a></li-->
                                    <? } ?>
                                </ul>
                            </span>
                            <? if ($booking['status'] == 'won' && $booking['finish'] == 'canceled') { ?>
                            <span class="label label-warning" title="<?= $booking['finish_dt'] ?>">CXL</span>
                            <? } ?>
                            <?= $booking['status'] == 'won' ? Html::a($booking['product']['tour']['code'], '@web/tours/r/'.$booking['product']['tour']['id'], ['style'=>'background:#ffc; color:#148040; padding:0 5px;']) : '' ?>

                            <?= Html::a($booking['product']['title'], '@web/products/r/'.$booking['product']['id']) ?>
                            <?= $booking['pax'] ?>p
                            <?= $booking['product']['day_count'] ?>d
                            <?= date('j/n/Y', strtotime($booking['product']['day_from'])) ?>
                            <? if ($booking['price'] != 0) { ?>
                            <?= number_format($booking['price'], 0) ?><span class="text-muted"><?= $booking['currency'] ?></span>
                            <? } ?>

                            <?
                            $productAttachments = [];
                            if (file_exists(Yii::getAlias('@webroot').'/upload/devis-pdf/devis-'.$booking['product']['id'].'.pdf')) {
                                $productAttachments[] = [
                                'type'=>'oldpdf',
                                'file'=>'PDF Itinerary (old)',
                                ];
                            }
                            $productUploadPath = Yii::getAlias('@webroot').'/upload/products/'.$booking['product']['id'];
                            if (file_exists($productUploadPath.'/pdf')) {
                                foreach (FileHelper::findFiles($productUploadPath.'/pdf') as $file) {
                                    $productAttachments[] = [
                                    'type'=>'pdf',
                                    'file'=>substr(strrchr($file, '/'), 1),
                                    ];
                                }
                            }
                            if (file_exists($productUploadPath.'/image')) {
                                foreach (FileHelper::findFiles($productUploadPath.'/image') as $file) {
                                    $productAttachments[] = [
                                    'type'=>'image',
                                    'file'=>substr(strrchr($file, '/'), 1),
                                    ];
                                }
                            }
                            if (file_exists($productUploadPath.'/excel')) {
                                foreach (FileHelper::findFiles($productUploadPath.'/excel') as $file) {
                                    $productAttachments[] = [
                                    'type'=>'excel',
                                    'file'=>substr(strrchr($file, '/'), 1),
                                    ];
                                }
                            }

                            foreach($productAttachments as $attachment) {
                                echo Html::a('<i class="fa fa-paperclip"></i>', '/products/download/'.$booking['product']['id'].'?type='.$attachment['type'].'&file='.$attachment['file'], ['title'=>$attachment['file'], 'class'=>'text-muted']);
                            }

                            ?>
                            <?
                            if ($booking['payments']) {
                                ?>
                                <div style="font-size:90%; padding:8px; background-color:#ded">
                                    <table style="width:100%;">
                                        <?
                                        $total = 0;
                                        foreach ($booking['payments'] as $payment) {
                                            ?>
                                            <tr>
                                                <td><?= Html::a(substr($payment['payment_dt'], 0, 10), '@web/payments/r/'.$payment['id'], ['title'=>$payment['method'].' / '.$payment['note']]) ?></td>
                                                <td class="text-right"><? if ($payment['currency'] != 'VND') { ?><?= number_format($payment['amount'], 2) ?> <span class="text-muted"><?= $payment['currency'] ?></span><? } ?></td>
                                                <td class="text-right">
                                                    <? if ($payment['currency'] == 'VND') { $total += $payment['amount']; ?>
                                                    <?= number_format($payment['amount'], 0) ?> <span class="text-muted">VND</span>
                                                    <? } else { $total += $payment['amount'] * $payment['xrate']; ?>
                                                    <?= number_format($payment['amount'] * $payment['xrate'], 0) ?> <span class="text-muted">VND</span>
                                                    <? } ?>
                                                </td>
                                            </tr>
                                            <?
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="2">Total paid</td>
                                            <td class="text-right"><?= number_format($total, 0) ?> <span class="text-muted">VND</span></td>
                                        </tr>
                                    </table>
                                </div>
                                <?
                            }
                            ?>
                            <? if ($booking['status'] == 'won' && in_array(USER_ID, [1, $theCase['owner_id']])) { ?>
                            <div><small><?= Html::a('+Invoice', '/invoices/c?booking_id='.$booking['id']) ?></small></div>
                            <? } ?>
                        </td>
                    </tr>
                    <? } ?>
                </table>
                <? } ?>
            </div>

            <div class="mb-1em">
                <div style="width:100px;" class="pull-right text-right">
                    <?= Html::a('Add', '@web/users/u?next=case&id='.$theCase['id'], ['class'=>'text-muted']) ?>
                    &middot;
                    <?= Html::a('View all', '@web/cases/people/'.$theCase['id'], ['class'=>'text-muted']) ?>
                </div>

                <p class="text-warning text-bold text-uppercase"><?= Yii::t('k', 'Related people') ?></p>
                <? if (!$theCase['people']) { ?>
                <p>No people found. <?= Html::a('Add people', '@web/cases/people/'.$theCase['id']) ?></p>
                <hr>
                <? } else { ?>
                <? foreach ($theCase['people'] as $user) { ?>
                <div style="margin-bottom:5px;" class="clearfix">
                    <?= Html::img('//secure.gravatar.com/avatar/'.md5($user['email']).'?s=40&d=wavatar', ['style'=>'float:left; margin-right:8px;', 'class'=>'img-circle']) ?>
                    <i class="fa fa-<?= $user['gender'] ?>"></i>
                    <? if ($user['country_code'] != '') { ?>
                    <span class="flag-icon flag-icon-<?= $user['country_code'] ?>"></span>
                    <? } ?>
                    <?= Html::a($user['name'], '@web/users/r/'.$user['id']) ?>
                    <br>
                    <span class="text-muted"></span>
                    <?= Html::a('+Email from', '#', ['class'=>'text-muted email-from email-from-'.$user['id'], 'data-email'=>$user['email']]) ?>
                    <?= Html::a('+Email to', '#', ['class'=>'text-muted email-to email-to-'.$user['id'], 'data-email'=>$user['email']]) ?>
                    -
                    <?= Html::a('Edit info', '@web/users/u/'.$user['id'], ['class'=>'text-muted']) ?>
                </div>
                <? } // foreach people ?>
                <? } // if people ?>
            </div>


            <?
            // Get other cases
            $relatedCases = [];
            foreach ($theCase['people'] as $person) {
                foreach ($person['cases'] as $case) {
                    if ($case['id'] != $theCase['id']) {
                        $relatedCases[] = $case;
                    }
                }
            }

            ?>
            <? if (!empty($relatedCases)) { ?>
            <p><strong class="text-warning">RELATED CASES</strong></p>
            <ul class="list-unstyled">
                <? foreach ($relatedCases as $case) { ?>
                <li>
                    <i class="fa fa-briefcase text-muted"></i>
                    <?= Html::a($case['name'], '@web/cases/r/'.$case['id'], ['rel'=>'external']) ?>
                    <? if ($case['status'] == 'closed') { ?><i class="text-muted fa fa-lock"></i><? } ?>
                    <? if ($case['status'] == 'onhold') { ?><i class="text-warning fa fa-clock-o"></i><? } ?>
                    <? if ($case['deal_status'] == 'won') { ?><i class="text-success fa fa-dollar"></i><? } ?>
                    <? if ($case['deal_status'] == 'lost') { ?><i class="text-danger fa fa-dollar"></i><? } ?>
                    <?= $case['owner']['name'] ?>, <?= substr($case['created_at'], 0, 7) ?>
                </li>
                <? } ?>
            </ul>
            <? } ?>


            <hr>
            <div class="mb-1em">
                <?= $this->render('_add_email.php', ['theCase'=>$theCase, 'theEmails'=>$theEmails]) ?>
            </div>
        </div>
        <div class="col col-1-2">
            <div class="mb-1em">
                <div style="width:100px;" class="pull-right text-right">
                    <?= Html::a(Yii::t('op', '+New'), '@web/tasks/c?rtype=case&rid='.$theCase['id'], ['class'=>'task-add text-muted', 'data-rtype'=>'case', 'data-rid'=>$theCase['id'], 'data-rname'=>$theCase['name']]) ?>
                </div>

                <p class="text-uppercase text-bold text-warning"><i class="fa fa-tasks text-muted"></i> <?= Yii::t('k', 'Tasks') ?></p>
                <div id="task-list" style="border-left:4px solid #d6c6b6; padding-left:8px; margin-left:8px;;">
                    <? if (empty($theCase['tasks'])) { ?><p>No tasks found.</p><? } ?>

                    <?
                    $thisYear = date('Y');
                    $today = date('Y-m-d');
                    foreach ($theCase['tasks'] as $t) {
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
                            <span title="<?= $t['createdBy']['name'] ?> <?= DateTimeHelper::convert($t['uo'], 'j/n/Y H:i', 'Asia/Ho_Chi_Minh', $myTimeZone) ?>"><?= USER_ID == 1 || $t['ub'] ? Html::a($t['description'], '@web/tasks/u/'.$t['id'], ['class'=>'task-description', 'data-id'=>$t['id'], 'title'=>$t['cb'] == USER_ID ? 'Edit task' : $t['createdBy']['name'], 'data-toggle'=>'modal', 'data-target'=>'#taskModal']) : $t['description'] ?></span>
                            <span class="task-assignees"><? $cnt = 0; foreach ($t['assignees'] as $tu) { $cnt ++; if ($cnt != 1) echo ', ';?><span id="assignee-<?=$t['id']?>-<?=$tu['id']?>" class="task-assignee text-muted <?/*=$tu['completed_dt'] == '0000-00-00 00:00:00' ? '' : 'done'*/?>"><?= $tu['id'] == USER_ID ? 'Tôi' : $tu['name'] ?></span><? } ?></span>
                        </div>
                        <? } // foreach tasks ?>
                    </div>
                </div>
                <hr>

                <div class="mb-1em">
                    <?//= Html::a('View all', '@web/xfiles', ['class'=>'text-muted pull-right']) ?>
                    <p class="text-uppercase text-bold text-warning"><i class="fa fa-file-o text-muted"></i> <?= Yii::t('k', 'Files') ?></p>
                    <div>
                        <? foreach ($allFileList as $file) { ?>
                        <div>+ <?= Html::a($file['name'], $file['link']) ?> <span class="text-muted"><?= Yii::$app->formatter->asShortSize($file['size'], 0) ?></span></div>
                        <? } ?>
                    </div>
            <!--        
            <ul class="list-unstyled">
                <? foreach ($theCase['files'] as $file) { ?>
                <li>+ <?= Html::a($file['name'], '@web/files/r/'.$file['id']) ?> (<?= number_format($file['size'] / 1024, 2) ?> KB)</li>
                <? } ?>
            </ul>
        -->
    </div>
    <hr>

            <!--
            <div class="mb-1em">
                <?= Html::a('View all', '@web/xfiles', ['class'=>'text-muted pull-right']) ?>
                <p><strong><i class="fa fa-fw fa-file-o"></i> DOCUMENTS & WIKI</strong></p>
                <p>...</p>
            </div>
            <hr>
        -->

            <!--
            <p><strong>ACCESS</strong></p>
            <p>...</p>
        -->

        <p class="text-muted text-light"><i class="fa fa-info-circle"></i> <?= Yii::t('k', 'Last update') ?> <?= DateTimeHelper::convert($theCase['created_at'], 'j/n/Y H:i', 'UTC', $myTimeZone) ?> <?= Yii::t('k', 'by') ?> <?= $theCase['updatedBy']['name'] ?></p>
    </div>
</div>
</div>

<div class="col col-2">
    <div id="xxx">
        <ul class="note-list">
            <li class="first note-list-item clearfix">
                <div class="note-avatar">
                    <?= Html::a(Html::img('/timthumb.php?zc=1&w=100&h=100&src='.Yii::$app->user->identity->image, ['class'=>'img-circle note-author-avatar']), '@web/users/r/'.Yii::$app->user->id) ?>
                </div>
                <div class="note-content">
                    <?= $this->render('_editor.php', ['theCase'=>$theCase]) ?>
                </div>
            </li>
            <?
            $currentTime = strtotime('now');
            foreach ($timeTable as $time=>$item) {
                // A touch of xxx years has passed
                if ($currentTime - strtotime($time) > 31536000) {
                    ?>
                    <li class="note-list-item clearfix">
                        <div class="note-content"><i class="fa fa-clock-o fa-spin text-info"></i> <?= Yii::t('k', 'More than {n,plural, =1{1 year} other{# years}} has passed', ['n'=>floor(($currentTime - strtotime($time)) / 31536000)]) ?> ...</div>
                    </li>
                    <?
                }
                $currentTime = strtotime($time);

                $time = substr($time, 0, 16);
                if ($item['object'] == 'note') {
                    foreach ($theNotes as $note) {
                        if ($note['id'] == $item['id']) {
                            include('kase_r__message.php');
                        }
                    }
                } elseif ($item['object'] == 'inquiry') {
                    foreach ($caseInquiries as $inquiry) {
                        if ($inquiry['id'] == $item['id']) {
                            // BEGIN INQUIRY
                            $inquiryData = [];
                            if ($inquiry['id'] != 9507) {
                                $inquiryData = @unserialize($inquiry['data']);
                            }

                            ?>
                            <li class="note-list-item clearfix">
                                <div class="note-avatar">
                                    <? $userAvatar = '//secure.gravatar.com/avatar/'.md5($inquiry['email']).'?s=100&d=wavatar'; ?>
                                    <?= Html::a(Html::img($userAvatar, ['class'=>'img-circle note-author-avatar']), '#') ?>
                                </div>
                                <div class="note-content">
                                    <h5 class="note-heading">
                                        <i class="fa fa-desktop"></i>
                                        <?= Html::a('Web inquiry from '.$inquiry['site']['name'].' / '.$inquiry['form_name'].' / '.$inquiry['email'], '@web/inquiries/r/'.$inquiry['id'], ['style'=>'font-weight:bold;', 'rel'=>'external']) ?>
                                    </h5>
                                    <div class="mb-1em">
                                        <span class="text-muted timeago" title="<?= date('Y-m-d\TH:i:s', strtotime($inquiry['created_at'])) ?>Z"><?= date('j/n/Y H:i', strtotime($time)) ?></span>
                                    </div>
                                    <div class="note-body">
                                        <div class="inquiry-body">
                                            <?
                                            if ($inquiry['data2'] != '') {
                                                foreach ($allCountries as $country) {
                                                    $find1 = '{{ country : '.$country['code'].' }}';
                                                    $replace1 = '{{ country : '.$country['name'].' }}';
                                                    $find2 = '{{ countryCallingCode : '.$country['code'].' }}';
                                                    $replace2 = '{{ countryCallingCode : '.$country['name'].' +'.$country['dial_code'].' }}';
                                                    if (strpos($inquiry['data2'], $find1) !== false) {
                                                        $inquiry['data2'] = str_replace($find1, $replace1, $inquiry['data2']);
                                                    }
                                                    if (strpos($inquiry['data2'], $find2) !== false) {
                                                        $inquiry['data2'] = str_replace($find2, $replace2, $inquiry['data2']);
                                                    }
                                                }

                                                $ok = '';
                                                $fields = [];
                                                $parts = explode(' }}', $inquiry['data2']);
                                                foreach ($parts as $part) {
                                                    $qa = explode('{{ ', $part);
                                                    if (isset($qa[1])) {
                                                        $a = explode(' : ', $qa[1]);
                                                        if (isset($a[1])) {
                                                            $fields[trim($a[0])] = trim($a[1]);
                                                            $ok .= $qa[0];
                                                            $ok .= '<span style="color:brown">'.substr($qa[1], strlen($a[0]) + 2).'</span>';
                                                        }
                                                        else {
                                                            $ok .= $part.' }}';
                                                        }
                                                    } else {
                                                        $ok .= $part;
                                                    }
                                                }
                                                echo nl2br($ok);
                                            } else {
                                                if (in_array($inquiry['form_name'], [
                                                    'en_contact_130920', 'en_quote_130920',
                                                    'fr_devis_130920', 'fr_devis_140905', 'fr_booking_130920', 'fr_booking_140905',
                                                    'fr_contact_130920', 'fr_contactce_130920', 'fr_rdv_130920',
                                                    'val_contact_130920', 'val_rdv_130920', 'val_devis_130920', 'val_devis_140905', 'val_booking_130920', 'val_booking_140905', 
                                                    'vac_contact_130920', 'vac_rdv_130920', 'vac_devis_130920', 'vac_devis_140905', 'vac_booking_130920', 'vac_booking_140905', 
                                                    ])) {
                                                    echo $this->render('//inquiry/_render_'.$inquiry['form_name'], [
                                                        'theInquiry'=>$inquiry,
                                                        'inquiryData'=>$inquiryData,
                                                        ]);
                                            } else {
                                                if ($inquiry['form_name'] == 'fr_devis_m_140918' && $inquiry['data2'] != '') {
                // BEGIN PARSE INQUIRY
                                $ok = '';
                                $fields = [];
                                $parts = explode(' }}', $inquiry['data2']);
                                foreach ($parts as $part) {
                                    $qa = explode('{{ ', $part);
                                    if (isset($qa[1])) {
                                        $a = explode(' : ', $qa[1]);
                                        if (isset($a[1])) {
                                            $fields[trim($a[0])] = trim($a[1]);
                                            $ok .= $qa[0];
                                            $ok .= '<span style="color:brown">'.Html::encode($a[1]).'</span>';
                                        }
                                        else {
                                            $ok .= Html::encode($part).' }}';
                                        }
                                    } else {
                                        $ok .= Html::encode($part);
                                    }
                                }
                                echo nl2br($ok);
                            // END PARSE INQUIRY
                            } else {
                                ?>
                                <div><strong>CUSTOMER</strong></div>
                                <div>
                                    Name: <span class="text-warning"><?= $inquiry['name'] ?></span><br />
                                    Email: <span  class="text-warning"><?= $inquiry['email'] ?></span><br />
                                </div>
                                <div><strong>INQUIRY DATA</strong></div>
                                <div>
                                    <?
                                    echo '<dl class="dl-horizontal">';
                                    foreach ($inquiryData as $k=>$v) {
                                        if (!empty($v)) {
                                            echo '<dt>', $k, '</dt>';
                                            echo '<dd class="text-warning">', (is_array($v) ? implode(',', $v) : nl2br(Html::encode($v))), '</dd>';
                                        }
                                    }
                                    echo '</dl>';
                                    ?>
                                </div>
                                <?
            } // if form name
        }
    } // if data2
    ?>
</div>
<ul class="list-unstyled" style="margin-top:10px; padding:10px; background-color:#f6f6f0;">
    <li><strong>IP address</strong>
        <a rel="external" href="http://whatismyipaddress.com/ip/<?= $inquiry['ip'] ?>"><?= $inquiry['ip'] ?></a>
    </li>
    <li>
        <strong>HTTP Referrer</strong>
        <i class="fa fa-info-circle" title="<?= Html::encode($inquiry['ref']) ?>"></i>
        <?
        $mRef = parse_url($inquiry['ref']);
        if (false !== $mRef) {
            if (!isset($mRef['query'])) $mRef['query'] = '';
            $mQuery = parse_str(str_replace('&amp;', '&', $mRef['query']), $mq);
            if (!isset($mRef['path'])) $mRef['path'] = '';
            if ($mRef['path'] == '/aclk') {
                echo '<span style="color:Red">Google Adwords</span>';
            } elseif (isset($mRef['host']) && $mRef['host'] == 'www.googleadservices.com') {
                echo '<span style="color:Red">Google Adsense</span>';
            } else {
                if (!isset($mRef['host'])) $mRef['host'] = '(No data)';
                echo $mRef['host'];
            }
            $mqx = '';
            if (is_array($mq)) {
                foreach ($mq as $k=>$v) {
                    if ($k == 'ohost' || $k == 'adurl' || $k == 'url' || $k == 'u' || $k == 'oq' || $k == 'rdata' || $k == 'q' || $k == 'p')
                        $mqx .= '<br /><span class="label label-default" style="background-color:#ccc;">'.strtoupper($k).'</span> '.$v;
                }
            }
            echo $mqx;
        }
        ?>
    </li>
    <li>
        <strong>UserAgent string</strong>
        <?= $inquiry['ua'] ?>
    </li>
</ul>
</div>
</div>
</li>


<?
                        // END INQUIRY
}
}
} elseif ($item['object'] == 'mail') {
    foreach ($inboxMails as $mail) {
        if ($mail['id'] == $item['id']) {
            include('kase_r__mail.php');
        }
    }
} elseif ($item['object'] == 'sysnote') {
    foreach ($theSysnotes as $note) {
        if ($note['id'] == $item['id']) {
                        // BEGIN SYSNOTE
            include('kase_r__sysnote.php');
                        // END SYSNOTE
        }
    }
}
}
?>
</ul>
</div><!-- .xxx -->
</div>

<style type="text/css">
    .inquiry-body strong {color:brown;}
    .task-today {color:#c00;}
    .task-done .task-today {color:#444; display:none;}
    span.done {text-decoration:line-through;}
    .task-overdue .task-date, .task-overdue .task-time {color:#f00;}
</style>
<?
$jsNote = <<<TXT
///////note////////////
var CASE_ID = $('#wrap_note').data('case_id');
$('#wrap_note').on('click', function(){
    CKEDITOR.inline('wrap_note', {
        allowedContent: 'p b i strong; u ; a[!href]',
    });
    $(this).attr('contenteditable','true').focus();
});
$('#wrap_note').on('blur', function(){
    var note_id = $(this).data('note_id');
    var content = $(this).html();
    if (note_id > 0) {
        $.ajax({
            url: "/kase/note_case",
            method: "GET",
            data: {note_id: note_id, body:  content, action: 'update', case_id: CASE_ID},
            dataType: "json"
        }).done(function( msg ) {
            console.log(msg);return;
        }).fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
        });
    } else {
        $.ajax({
            url: "/kase/note_case",
            method: "GET",
            data: {note_id: 0, body:  content, action: 'add', case_id: CASE_ID},
            dataType: "json"
        }).done(function( msg ) {
            console.log(msg);return;
        }).fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
        });
    }
});

TXT;
$js = <<<'TXT'
var names = [
{{jsPeopleList}}
];

var tags = ['important', 'urgent', 'rpsv'];
var tags = $.map(tags, function(value, i) {return {key: value, name:value}});

var at_config = {
  at: "@",
  data: names,
  searchKey: 'nname',
  limit: 10,
  displayTpl: "<li data-value='@${key}'>${name} <small>${email}</small></li>",
  insertTpl: "${atwho-at}[${name}]",
}
var tag_config = {
  at: '#',
  data: tags,
  displayTpl: '<li data-value="#${name}">${name}</li>',
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
                new PNotify({
                    icon:'fa fa-warning text-danger',
                    title: 'Task',
                    text: data.message,
                    animate: {
                        animate: true,
                        in_class: 'zoomInLeft',
                        out_class: 'zoomOutRight'
                    }
                });
            }
        } else {
            alert('Error: data error.');
        }
    }, 'json');
});

    // Vietnamese
/*
jQuery.timeago.settings.strings = {
    prefixAgo: null,
    prefixFromNow: null,
    suffixAgo: 'trước',
    suffixFromNow: 'nữa',
    seconds: "gần một phút",
    minute: "một phút",
    minutes: "%d phút",
    hour: "một tiếng",
    hours: "%d tiếng",
    day: "một ngày",
    days: "%d ngày",
    month: "một tháng",
    months: "%d tháng",
    year: "một năm",
    years: "%d năm",
    wordSeparator: " ",
    numbers: []
};
    */

    // Email from
$('a.email-from').click(function(){
    var email = $(this).data('email');
    $('input#to').val('from:'+email+' ');
    $('.write-toggle:eq(0)').hide();
    $('.write-toggle:eq(1)').show();
    $('#redactor').redactor('core.getObject').focus.setEnd();
    return false;
});
$('a.email-to').click(function(){
    var email = $(this).data('email');
    $('input#to').val('to:'+email+' ');
    $('.write-toggle:eq(0)').hide();
    $('.write-toggle:eq(1)').show();
    $('#redactor').redactor('core.getObject').focus.setEnd();
    return false;
});

$('a.editable-prospect').editable({
    display: function(value, sourceData) {
        if (value == 1) {
            $(this).html('<i class="fa fa-frown-o"></i>');
        }
        if (value == 2) {
            $(this).html('<i class="fa fa-frown-o"></i> <i class="fa fa-frown-o"></i>');
        }
        if (value == 3) {
            $(this).html('<i class="fa fa-meh-o"></i> <i class="fa fa-meh-o"></i> <i class="fa fa-meh-o"></i>');
        }
        if (value == 4) {
            $(this).html('<i class="fa fa-smile-o"></i> <i class="fa fa-smile-o"></i> <i class="fa fa-smile-o"></i> <i class="fa fa-smile-o"></i>');
        }
        if (value == 5) {
            $(this).html('<i class="fa fa-smile-o"></i> <i class="fa fa-smile-o"></i> <i class="fa fa-smile-o"></i> <i class="fa fa-smile-o"></i> <i class="fa fa-smile-o"></i>');
        }
    },
    showbuttons:false,
    source: [
    {value: 1, text: '+'},
    {value: 2, text: '++'},
    {value: 3, text: '+++'},
    {value: 4, text: '++++'},
    {value: 5, text: '+++++'}
    ]
});


TXT;
$js = str_replace(['{{jsPeopleList}}'], [$jsPeopleList], $js);

$this->registerCssFile(DIR.'assets/at.js_0.4.12/css/jquery.atwho.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/Caret.js/0.3.1/jquery.caret.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/at.js/1.5.1/js/jquery.atwho.min.js', ['depends'=>'yii\web\JqueryAsset']);

$this->registerCssFile(DIR.'assets/x-editable_1.5.1/css/bootstrap-editable.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/x-editable_1.5.1/js/bootstrap-editable.min.js', ['depends'=>'app\assets\MainAsset']);

// $this->registerJsFile(DIR.'assets/jquery-timeago_1.4.1/jquery.timeago.js', ['depends'=>'yii\web\JqueryAsset']);

$this->registerJs($jsNote);

$this->registerJs($js);

include('_plupload_inc.php');
include('_ckeditor_inc.php');

include(Yii::getAlias('@app').'/views/task/_task_edit_modal.php');