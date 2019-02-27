<?php
use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\web\HttpException;

include('_tasks_inc.php');

$myTimeZone = Yii::$app->user->identity->timezone;

// Task task id list
$tourTaskIdList = [];
$caseTaskIdList = [];
foreach ($theTasks as $task) {
    $theTaskIdList[] = $task['id'];
    if ($task['rtype'] == 'tour') {
        $tourTaskIdList[] = $task['rid'];
    }
    if ($task['rtype'] == 'case') {
        $caseTaskIdList[] = $task['rid'];
    }
}

$taskTours = [];
if (!empty($tourTaskIdList)) {
   $taskTours = \common\models\Tour::find()
        ->select(['id', 'code', 'name'])
        ->where(['id'=>$tourTaskIdList])
        ->indexBy('id')
        ->asArray()
        ->all();
}

$taskCases = [];
if (!empty($caseTaskIdList)) {
    $taskCases = \common\models\Kase::find()
        ->select(['id', 'name'])
        ->where(['id'=>$caseTaskIdList])
        ->indexBy('id')
        ->asArray()
        ->all();
}

// The task users
if (empty($theTaskIdList)) {
    $theTaskUsers = array();
} else {
    $theTaskUsers = Yii::$app->db->createCommand('SELECT u.nickname AS user_name, tu.* FROM users u, at_task_user tu WHERE tu.user_id=u.id AND tu.task_id IN ('.implode(',', $theTaskIdList).') ORDER BY lname')->queryAll();
}

$thisYear = date('Y');
$today = date('Y-m-d');

Yii::$app->params['page_title'] = Yii::t('x', 'My tasks');

?>
<style>
.task-list-item {padding:6px 0;}
.task-assignee-done {text-decoration:line-through;}
.task-overdue .task-date {color:#F44336; font-weight:bold; background-color:yellow;}
.task-assignee {color:#999!important;}
.task-assignee.done {text-decoration:line-through; color:#ccc!important;}
.task-today {color:#c00; margin:0 2px;}
.task-done .task-today {color:#444; display:none;}
.task-overdue .task-date, .task-overdue .task-time {color:#f00;}
.task-assignee-done {text-decoration:line-through;}
.task-group-label {margin-top:1.5rem;}
.task-group-label:first-child {margin-top:0;}
</style>
<div id="tasks" class="col-md-12">
    <div class="card">
        <div class="card-header bg-light pb-0 pt-sm-0">
            <ul class="nav nav-tabs nav-tabs-highlight card-header-tabs">
                <li class="nav-item">
                    <a href="/tasks" class="nav-link<?= $status == 'open' ? ' active' : '' ?>">
                        <?= Yii::t('x', 'Open tasks') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/tasks?status=done" class="nav-link<?= $status == 'done' ? ' active' : '' ?>">
                        <?= Yii::t('x', 'Completed tasks') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/tasks?status=assigned" class="nav-link<?= $status == 'assigned' ? ' active' : '' ?>">
                        <?= Yii::t('x', 'Tasks I assigned other people') ?>
                    </a>
                </li>
            </ul>
        </div>
        <div id="task-list" class="card-body">
            <?php if (empty($theTasks)) { ?>
                <p><?= Yii::t('x', 'No data found.') ?>
            <?php } ?>
            <?php
            $thisYear = date('Y');
            $today = date('Y-m-d');

            $labelOverdue = false;
            $labelTasks = false;
            $labelNextWeek = false;
            $labelNext2Week = false;
            $labelNextYear = false;
            $labelLater = false;

            $firstHr = true;
            foreach ($theTasks as $task) {
                if ($status == 'open' && !$labelOverdue && strtotime($task['due_dt']) <= strtotime('now')) {
                    $labelOverdue = true;
                    // if ($firstHr) {
                    //     $firstHr = false;
                    // } else {
                    //     echo '<hr>';
                    // }
                    ?>
            <div class="task-group-label font-weight-bold text-danger"><?= Yii::t('x', 'Overdue') ?></div>
                    <?php
                }
                if ($status == 'open' && !$labelTasks && $labelOverdue && strtotime($task['due_dt']) > strtotime('now')) {
                    $labelTasks = true;
                    ?>
            <div class="task-group-label font-weight-bold"><?= Yii::t('x', 'Upcoming tasks') ?></div>
                    <?php
                }
                if ($status == 'open' && !$labelNextWeek && strtotime(substr($task['due_dt'], 0, 10)) >= strtotime('next Monday')) {
                    $labelNextWeek = true;
                    ?>
            <div class="task-group-label font-weight-bold"><?= Yii::t('x', 'Next week') ?></div>
                    <?php
                }
                if ($status == 'open' && !$labelNext2Week && strtotime(substr($task['due_dt'], 0, 10)) >= strtotime('+7 days', strtotime('next Monday'))) {
                    $labelNext2Week = true;
                    ?>
            <div class="task-group-label font-weight-bold"><?= Yii::t('x', '2 weeks from now') ?></div>
                    <?php
                }
                if ($status == 'open' && !$labelNextYear && substr($task['due_dt'], 0, 4) == $thisYear + 1) {
                    $labelNextYear = true;
                    ?>
            <div class="task-group-label font-weight-bold"><?= Yii::t('x', 'Next year') ?></div>
                    <?php
                }
                if ($status == 'open' && !$labelLater && substr($task['due_dt'], 0, 4) > $thisYear + 1) {
                    $labelLater = true;
                    ?>
            <div class="task-group-label font-weight-bold"><?= Yii::t('x', 'Later') ?></div>
                    <?php
                }
            ?>
            <div id="div-task-<?= $task['id'] ?>" class="task-list-item task <?=$task['status'] == 'on' && strtotime($task['due_dt']) < strtotime(NOW) ? 'task-overdue' : ''?> <?=$task['status'] == 'off' ? 'task-done' : ''?>">
                <i id="icon-<?= $task['id'] ?>" data-task_id="<?=$task['id']?>" title="<?= Yii::t('op', 'Check/Uncheck') ?>" class="cursor-pointer task-check fa fa-<?= $task['status'] == 'on' ? '' : 'check-' ?>square-o"></i>
                <span class="task-date"><?php
                if ($task['fuzzy'] != 'date') {
                    if (substr($task['due_dt'], 0, 4) == $thisYear) {
                        echo date('j/n', strtotime($task['due_dt']));
                        if (substr($task['due_dt'], 0, 10) == $today) {
                            echo '<span class="task-today">', Yii::t('x', 'Today'), '</span> ';
                        }
                    } else {
                        echo date('j/n/Y', strtotime($task['due_dt']));
                    }
                } ?>
                </span>
                <span class="task-time"><?php
                // Show time if not fuzzy
                $task['time'] = substr($task['due_dt'], 11);
                if ($task['fuzzy'] == 'time') {
                    if ($task['time'] == '11:59:59') {
                        echo 'morning';
                    } elseif ($task['time'] == '17:59:59') {
                        echo 'afternoon';
                    }
                } elseif ($task['fuzzy'] == 'none') {
                    echo substr($task['time'], 0, 5);
                } ?>
                </span>
                <span class="task-priority"><?php if ($task['is_priority'] == 'yes') { ?><i class="fa fa-star text-danger" title="Priority"></i><?php } ?></span>
                <span title="<?= $task['createdBy']['name'] ?> <?= DateTimeHelper::convert($task['uo'], 'j/n/Y H:i', 'Asia/Ho_Chi_Minh', $myTimeZone) ?>"><?= USER_ID == 1 || $task['ub'] ? Html::a($task['description'], '@web/tasks/'.$task['id'].'/u', ['class'=>'task-description', 'data-id'=>$task['id'], 'title'=>$task['cb'] == USER_ID ? 'Edit task' : $task['createdBy']['name']]) : $task['description'] ?></span>
                <?php if ($task['cb'] != USER_ID) { ?>
                <span class="task-owner text-muted ml-2"><?= $task['createdBy']['name'] ?></span>
                <?php } ?>
                <i class="fa fa-caret-right text-muted small"></i>
                <span class="task-assignees">                    
                    <?php foreach ($task['taskAssign'] as $cnt=>$taskAssign) { ?>
                    <?= $cnt > 0 ? ', ': '' ?>
                    <span id="assignee-<?=$task['id']?>-<?=$taskAssign['user_id']?>" class="task-assignee text-muted <?=$taskAssign['completed_dt'] === null ? '' : 'task-assignee-done' ?>"><?= $taskAssign['user_id'] == USER_ID ? Yii::t('x', 'Me') : $taskAssign['assignee']['name'] ?></span>
                    <?php } ?>
                </span>
                <?php
                if (isset($taskCases[$task['rid']])) {
                    $taskRelName = $taskCases[$task['rid']]['name'];
                    $taskRelClass = 'info';
                } elseif (isset($taskTours[$task['rid']])) {
                    $taskRelName = $taskTours[$task['rid']]['code'].' - '.$taskTours[$task['rid']]['name'];
                    $taskRelClass = 'success';
                } else {
                    $taskRelName = $task['rtype'].'/'.$task['rid'];
                    $taskRelClass = 'muted';
                }
                if (!empty($taskRelClass)) { ?>
                <span class="task-relation badge badge-outline-<?= $taskRelClass ?> ml-2"><?= Html::a($taskRelName, '/'.$task['rtype'].'s/r/'.$task['rid'], ['target'=>'_blank', 'class'=>'text-'.$taskRelClass, 'style'=>'font-size:.9rem; text-shadow:none']) ?></span>
                <?php
                }
                ?>
                <?php if ($task['ub'] == USER_ID || 1 == USER_ID) { ?>
                <span class="small task-actions ml-2">
                    <?= Html::a(Yii::t('x', 'Edit'), '/tasks/u/'.$task['id'], ['class'=>'text-muted']).' &middot; '.Html::a(Yii::t('x', 'Delete'), '/tasks?redir=ref&action=delete&taskId='.$task['id'], ['class'=>'action-delete-task text-danger']) ?>
                </span>
                <?php } ?>
            </div>
            <?php } // foreach tasks ?>
        </div>
        <?php /*div class="table-responsive">
            <table class="table table-narrow table-striped mb-0">
                <thead>
                    <tr>
                        <th width="55%">Due date & description</th>
                        <th width="25%">Related to</th>
                        <th width="5%">Mins</th>
                        <th width="15%">Assigned by</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($theTasks) > 0) { ?>
                    <tr>
                        <td colspan="4"><?= Yii::t('task', 'No tasks found.') ?></td>
                    </tr>
                    <?php } else { ?>
                        <?php foreach ($theTasks as $t) {
                            $completedByMe = false;
                            foreach ($theTaskUsers as $tu) {
                                if ($tu['task_id'] == $task['id'] && USER_ID == $tu['user_id'] && $tu['completed_dt'] != ZERODT) {
                                    $completedByMe = true;
                                    break;
                                }
                            }
                            if (!$completedByMe) {
                            ?>
                    <tr>
                        <td>
                            <div id="div-task-<?= $task['id'] ?>" class="task <?= $task['status'] == 'on' && strtotime($task['due_dt']) < strtotime(NOW) ? 'task-overdue' : '' ?> <?= $task['status'] == 'off' ? 'task-done' : '' ?>">
                                <i id="icon-<?= $task['id'] ?>" data-task_id="<?= $task['id'] ?>" class="fa fa-fw cursor-pointer task-check <?= $task['status'] == 'off' ? 'fa-check-square-o' : 'fa-square-o' ?>"></i>
                                <?
                                if ($task['fuzzy'] == 'date') {
                                    // Echo nuffin'
                                } else {
                                    if (substr($task['due_dt'], 0, 4) == $thisYear) {
                                        $dueDTDisplay = date('j/n', strtotime($task['due_dt']));
                                    } else {
                                        $dueDTDisplay = date('j/n/Y', strtotime($task['due_dt']));
                                    }
                                    if (substr($task['due_dt'], 0, 10) == $today) echo '<span class="today text-danger">Hôm nay</span> ';
                                    echo '<span class="task-date">', $dueDTDisplay, '</span>';
                                    if ($task['fuzzy'] == 'time') {
                                        // Display nuffin
                                    } else {
                                        echo ' <span class="task-time">'.substr($task['due_dt'], 11, 5).'</span>';
                                    }
                                }

                                ?>
                                <?php if ($task['is_priority'] == 'yes') { ?><i style="color:#c00;" title="Priority" class="fa fa-asterisk"></i><?php } ?>
                                <?= USER_ID == $task['ub'] || USER_ID == 1 ? Html::a($task['description'], '/tasks/'.$task['id'].'/u', ['class'=>'td-n']) : $task['description']?>
                                <?php $cnt = 0; foreach ($theTaskUsers as $tu) { if ($tu['task_id'] == $task['id']) { $cnt ++; if ($cnt != 1) echo ', ';?><span id="assignee-<?=$task['id']?>-<?=$tu['user_id']?>" class="text-light task-assignee <?=$tu['completed_dt'] == ZERODT ? '' : 'done'?>" title="AS: <?=$tu['assigned_dt']?>"><?= $tu['user_id'] == USER_ID ? 'Tôi' : $tu['user_name']?></span><?php } } ?>
                            </div>
                        </td>
                        <td>
                            <?php if ($task['n_id'] != 0) { ?>&rarr; <?= Html::a($task['title'], 'n/r/'.$task['n_id'], ['class'=>'quiet'])?><?php } ?>
                            <?php if ($task['rid'] != 0 && $task['rtype'] != 'none') { ?> # <?= Html::a($task['rname'], $task['rtype'].'s/r/'.$task['rid'], ['class'=>'td-n quiet']) ?><?php } ?>
                        </td>
                        <td class="ta-r"><?=$task['mins']?></td>
                        <td><?
                            if ($task['ub'] == USER_ID || 1 == USER_ID) {
                                echo 'Tôi ['.Html::a('Edit', '/tasks/u/'.$task['id']).'-'.Html::a('Delete', '/tasks?redir=ref&action=delete&taskId='.$task['id']).']';
                            } else {
                                echo $task['ub_name'];
                            }
                  ?>
                        </td>
                    </tr>
                            <?php } // if not completed by me ?>
                        <?php } // foreach ?>
                    <?php } // if not empty ?>
                </tbody>
            </table>
        </div */?>
    </div>
</div>

<?php

$js = <<<'TXT'
    // Task check
    $('#task-list').on('click', 'i.task-check', function(){
        var task_id = $(this).data('task_id');
        $.post('/tasks/ajax?xh', {action:'check', task_id:task_id}, function(data){
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

    $('.action-delete-task').on('click', function(e){

        if (!confirm('Delete task?')) {
            return false;
        }
    })

    // $('i.task-check').on('click', function(){
    //     var task_id = $(this).data('task_id');
    //     $.post('/tasks/ajax', {action:'check', task_id:task_id}, function(data){
    //         if (data.status) {
    //             if (data.status == 'OK') {
    //                 $('span#assignee-' + task_id + '-' + 'USER_ID').toggleClass('done');
    //                 $('i#icon-' + task_id).removeClass('fa-check-square-o').removeClass('fa-square-o');
    //                 if (data.icon == 'icon-check') {
    //                     $('i#icon-' + task_id).addClass('fa-check-square-o');
    //                 } else {
    //                     $('i#icon-' + task_id).addClass('fa-square-o');
    //                 }
    //             } else {
    //                 new PNotify({
    //                     title: 'Notice',
    //                     text: data.message,
    //                     styling: 'bootstrap3',
    //                     delay: 4000,
    //                     animate: {
    //                         animate: true,
    //                         in_class: 'slideInDown',
    //                         out_class: 'slideOutUp'
    //                     },
    //                     hide: false,
    //                     confirm: {
    //                         confirm: true,
    //                         buttons: [{
    //                             text: 'Ok',
    //                             addClass: 'btn-primary',
    //                             click: function(notice) {
    //                                 notice.remove();
    //                             }
    //                         },
    //                         null]
    //                     },
    //                     buttons: {
    //                         closer: false,
    //                         sticker: false
    //                     },
    //                     history: {
    //                         history: false
    //                     }
    //                 });
    //             }
    //         } else {
    //             alert('Error: data error.');
    //         }
    //     }, 'json');
    // });
TXT;
$js = str_replace('USER_ID', USER_ID, $js);
$this->registerJs($js);

include(Yii::getAlias('@app').'/views/tasks/_tasks_edit_modal.php');
