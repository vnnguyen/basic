<?php

use yii\helpers\Html;
use yii\web\HttpException;

$action = \fRequest::getValid('action', array('', 'do', 'undo', 'delete'));
$taskId = \fRequest::get('taskId', 'integer', 0);
$redir = \fRequest::getValid('redir', array('', 'ref'));

if ($taskId != 0 && $action == 'delete') {
    $theTask = Yii::$app->db->createCommand('SELECT description FROM at_tasks WHERE id=:id AND ub=:ub LIMIT 1', [':id'=>$taskId, ':ub'=>USER_ID])->queryOne();
    if (!$theTask) {
        throw new HttpException(403, 'Access denied');
    }
    Yii::$app->db->createCommand()->delete('at_tasks', ['id'=>$taskId])->execute();
    Yii::$app->db->createCommand()->delete('at_task_user', ['task_id'=>$taskId])->execute();
}

if ($taskId != 0 && $action != '' && $redir == 'ref' && isset($_SERVER['HTTP_REFERER'])) {
    return $this->redirect('/tasks');
    exit;
}

$getCat = SEG2;
if ($getCat == '') {
  $metaT = 'Nhiệm vụ tôi phải làm';

  $theTasks = Yii::$app->db->createCommand('SELECT t.*, (SELECT nickname FROM users u WHERE u.id=t.ub LIMIT 1) AS ub_name,
        IF(rtype="case", (SELECT name FROM at_cases c WHERE c.id=rid LIMIT 1), (SELECT CONCAT(code, " - ", name) AS name FROM at_tours t WHERE t.id=rid LIMIT 1)) AS rname,
        IF(n_id=0, "", (SELECT title FROM at_messages WHERE at_messages.id=t.n_id LIMIT 1)) AS title
        FROM at_tasks t, at_task_user tu WHERE tu.task_id=t.id AND t.status="on" AND tu.user_id=:me ORDER BY due_dt, is_priority LIMIT 1000', [':me'=>USER_ID])->queryAll();

} elseif ($getCat == 'assigned') {
  $metaT = 'Nhiệm vụ tôi giao cho người khác';
  $q = $db->query('SELECT *, "" AS ub_name,
        IF(rtype="case", (SELECT name FROM at_cases c WHERE c.id=rid LIMIT 1), (SELECT CONCAT(code, " - ", name) AS name FROM at_tours t WHERE t.id=rid LIMIT 1)) AS rname,
        IF(n_id=0, "", (SELECT title FROM at_messages WHERE at_messages.id=t.n_id LIMIT 1)) AS title
        FROM at_tasks t, at_task_user tu WHERE tu.task_id=t.id AND ub=%i AND tu.user_id!=%i ORDER BY status, due_dt, is_priority LIMIT 1000', USER_ID, USER_ID);
} elseif ($getCat == 'done') {
  $metaT = 'Nhiệm vụ tôi đã làm xong';
  $q = $db->query('SELECT t.*, (SELECT name FROM persons u WHERE u.id=t.ub LIMIT 1) AS ub_name,
        IF(rtype="case", (SELECT name FROM at_cases c WHERE c.id=rid LIMIT 1), (SELECT CONCAT(code, " - ", name) AS name FROM at_tours t WHERE t.id=rid LIMIT 1)) AS rname,
        IF(n_id=0, "", (SELECT title FROM at_messages WHERE at_messages.id=t.n_id LIMIT 1)) AS title
        FROM at_tasks t, at_task_user tu WHERE tu.task_id=t.id AND t.status="off" AND tu.user_id=%i ORDER BY due_dt DESC, is_priority LIMIT 1000', USER_ID);
} else {
    show_error(404);
}

// Task task id list
foreach ($theTasks as $t) $theTaskIdList[] = $t['id'];

// The task users
if (empty($theTaskIdList)) {
    $theTaskUsers = array();
} else {
    $theTaskUsers = Yii::$app->db->createCommand('SELECT u.nickname AS user_name, tu.* FROM users u, at_task_user tu WHERE tu.user_id=u.id AND tu.task_id IN ('.implode(',', $theTaskIdList).') ORDER BY lname')->queryAll();
}

$thisYear = date('Y');
$today = date('Y-m-d');

Yii::$app->params['page_title'] = 'My tasks';
Yii::$app->params['page_breadcrumbs'] = [
    ['Tasks', 'tasks'],
];
Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'plus', 'title'=>'Add new task', 'link'=>'tasks/c', 'active'=>SEG2 == 'c'],
    ],
];
Yii::$app->params['page_actions'][] = [
    ['label'=>'Active', 'link'=>'tasks', 'active'=>SEG2==''],
    ['label'=>'Completed', 'title'=>'Tasks I have completed', 'link'=>'tasks/done', 'active'=>SEG2=='done'],
    ['label'=>'Assigned', 'title'=>'Tasks I assigned other people', 'link'=>'tasks/assigned', 'active'=>SEG2=='assigned'],
];

?>
<style>
.task-overdue .task-date {color:#F44336; font-weight:bold; background-color:yellow;}
.task-assignee {color:#999!important;}
.task-assignee.done {text-decoration:line-through; color:#ccc!important;}
</style>
<div id="tasks" class="col-md-12">
    <div class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-xxs table-striped">
                <thead>
                    <tr>
                        <th width="55%">Due date & description</th>
                        <th width="25%">Related to</th>
                        <th width="5%">Mins</th>
                        <th width="15%">Assigned by</th>
                    </tr>
                </thead>
                <tbody>
                    <? if (empty($theTasks) > 0) { ?>
                    <tr>
                        <td colspan="4"><?= Yii::t('task', 'No tasks found.') ?></td>
                    </tr>
                    <? } else { ?>
                        <? foreach ($theTasks as $t) { ?>
                    <tr>
                        <td>
                            <div id="div-task-<?= $t['id'] ?>" class="task <?= $t['status'] == 'on' && strtotime($t['due_dt']) < strtotime(NOW) ? 'task-overdue' : '' ?> <?= $t['status'] == 'off' ? 'task-done' : '' ?>">
                                <i id="icon-<?= $t['id'] ?>" data-task_id="<?= $t['id'] ?>" class="fa fa-fw cursor-pointer task-check <?= $t['status'] == 'off' ? 'fa-check-square-o' : 'fa-square-o' ?>"></i>
                                <?
                                if ($t['fuzzy'] == 'date') {
                                    // Echo nuffin'
                                } else {
                                    if (substr($t['due_dt'], 0, 4) == $thisYear) {
                                        $dueDTDisplay = date('j/n', strtotime($t['due_dt']));
                                    } else {
                                        $dueDTDisplay = date('j/n/Y', strtotime($t['due_dt']));
                                    }
                                    if (substr($t['due_dt'], 0, 10) == $today) echo '<span class="today text-danger">Hôm nay</span> ';
                                    echo '<span class="task-date">', $dueDTDisplay, '</span>';
                                    if ($t['fuzzy'] == 'time') {
                                        // Display nuffin
                                    } else {
                                        echo ' <span class="task-time">'.substr($t['due_dt'], 11, 5).'</span>';
                                    }
                                }

                                ?>
                                <? if ($t['is_priority'] == 'yes') { ?><i style="color:#c00;" title="Priority" class="fa fa-asterisk"></i><? } ?>
                                <?= USER_ID == $t['ub'] || USER_ID == 1 ? Html::a($t['description'], '/tasks/u/'.$t['id'], ['class'=>'td-n']) : $t['description']?>
                                <? $cnt = 0; foreach ($theTaskUsers as $tu) { if ($tu['task_id'] == $t['id']) { $cnt ++; if ($cnt != 1) echo ', ';?><span id="assignee-<?=$t['id']?>-<?=$tu['user_id']?>" class="text-light task-assignee <?=$tu['completed_dt'] == ZERODT ? '' : 'done'?>" title="AS: <?=$tu['assigned_dt']?>"><?= $tu['user_id'] == USER_ID ? 'Tôi' : $tu['user_name']?></span><? } } ?>
                            </div>
                        </td>
                        <td>
                            <? if ($t['n_id'] != 0) { ?>&rarr; <?= Html::a($t['title'], 'n/r/'.$t['n_id'], ['class'=>'quiet'])?><? } ?>
                            <? if ($t['rid'] != 0 && $t['rtype'] != 'none') { ?> # <?= Html::a($t['rname'], $t['rtype'].'s/r/'.$t['rid'], ['class'=>'td-n quiet']) ?><? } ?>
                        </td>
                        <td class="ta-r"><?=$t['mins']?></td>
                        <td><?
                            if ($t['ub'] == USER_ID || 1 == USER_ID) {
                                echo 'Tôi ['.Html::a('Edit', '/tasks/u/'.$t['id']).'-'.Html::a('Delete', '/tasks?redir=ref&action=delete&taskId='.$t['id']).']';
                            } else {
                                echo $t['ub_name'];
                            }
                  ?>
                        </td>
                    </tr>
                        <? } // foreach ?>
                    <? } // if not empty ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php

$js = <<<'TXT'
    $('i.task-check').on('click', function(){
        var task_id = $(this).data('task_id');
        $.post('/tasks/ajax', {action:'check', task_id:task_id}, function(data){
            if (data.status) {
                if (data.status == 'OK') {
                    $('span#assignee-' + task_id + '-' + 'USER_ID').toggleClass('done');
                    $('i#icon-' + task_id).removeClass('fa-check-square-o').removeClass('fa-square-o');
                    if (data.icon == 'icon-check') {
                        $('i#icon-' + task_id).addClass('fa-check-square-o');
                    } else {
                        $('i#icon-' + task_id).addClass('fa-square-o');
                    }
                } else {
                    new PNotify({
                        title: 'Notice',
                        text: data.message,
                        styling: 'bootstrap3',
                        delay: 4000,
                        animate: {
                            animate: true,
                            in_class: 'slideInDown',
                            out_class: 'slideOutUp'
                        },
                        hide: false,
                        confirm: {
                            confirm: true,
                            buttons: [{
                                text: 'Ok',
                                addClass: 'btn-primary',
                                click: function(notice) {
                                    notice.remove();
                                }
                            },
                            null]
                        },
                        buttons: {
                            closer: false,
                            sticker: false
                        },
                        history: {
                            history: false
                        }
                    });
                }
            } else {
                alert('Error: data error.');
            }
        }, 'json');
    });
TXT;
$js = str_replace('USER_ID', USER_ID, $js);
$this->registerJs($js);

