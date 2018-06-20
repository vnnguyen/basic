<?
use yii\helpers\Html;

include('_task_inc.php');

$this->title = 'Tasks completed by me';
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
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th>Due date & description</th>
                        <th>Related to</th>
                        <th>Mins</th>
                        <th>Assigned by</th>
                    </tr>       
                </thead>
                <tbody>
                    <? foreach ($theTasks as $task) { ?>
                    <tr>
                        <td>
                            <i class="fa fa-fw fa-check-square-o"></i>
                            <?= $task['description'] ?>
                        </td>
                        <td><?= $task['rtype'] ?>-<?= $task['rid'] ?></td>
                        <td><?= $task['mins'] ?></td>
                        <td><?= $task['cb'] ?></td>
                    </tr>
                    <? } ?>         
                </tbody>
            </table>
        </div>
    </div>
</div>


<?

/*
$getAction = fRequest::getValid('action', array('', 'do', 'undo', 'delete'));
$getTaskId = fRequest::get('taskId', 'integer', 0);
$getRedir = fRequest::getValid('redir', array('', 'ref'));

if ($getTaskId != 0 && $getAction == 'delete') {
    $q = $db->query('SELECT description FROM at_tasks WHERE id=%i AND ub=%i LIMIT 1', $getTaskId, myID);
    if ($q->countReturnedRows() > 0) {
        $db->query('DELETE FROM at_tasks WHERE id=%i LIMIT 1', $getTaskId);
        $db->query('DELETE FROM at_task_user WHERE task_id=%i', $getTaskId);
    } else {
        show_error(403);
    }
}

if ($getTaskId != 0 && $getAction != '' && $getRedir == 'ref' && isset($_SERVER['HTTP_REFERER'])) {
    redirect($_SERVER['HTTP_REFERER']);
    exit;
}

$getCat = seg2;
if ($getCat == '') {
  $metaT = 'Nhiệm vụ tôi phải làm';
  $q = $db->query('SELECT t.*, (SELECT name FROM persons u WHERE u.id=t.ub LIMIT 1) AS ub_name,
        IF(rtype="case", (SELECT name FROM at_cases c WHERE c.id=rid LIMIT 1), (SELECT CONCAT(code, " - ", name) AS name FROM at_tours t WHERE t.id=rid LIMIT 1)) AS rname,
        IF(n_id=0, "", (SELECT title FROM at_messages WHERE at_messages.id=t.n_id LIMIT 1)) AS title
        FROM at_tasks t, at_task_user tu WHERE tu.task_id=t.id AND t.status="on" AND tu.user_id=%i ORDER BY due_dt, is_priority LIMIT 1000', myID);
} elseif ($getCat == 'assigned') {
  $metaT = 'Nhiệm vụ tôi giao cho người khác';
  $q = $db->query('SELECT *, "" AS ub_name,
        IF(rtype="case", (SELECT name FROM at_cases c WHERE c.id=rid LIMIT 1), (SELECT CONCAT(code, " - ", name) AS name FROM at_tours t WHERE t.id=rid LIMIT 1)) AS rname,
        IF(n_id=0, "", (SELECT title FROM at_messages WHERE at_messages.id=t.n_id LIMIT 1)) AS title
        FROM at_tasks t, at_task_user tu WHERE tu.task_id=t.id AND ub=%i AND tu.user_id!=%i ORDER BY status, due_dt, is_priority LIMIT 1000', myID, myID);
} elseif ($getCat == 'done') {
  $metaT = 'Nhiệm vụ tôi đã làm xong';
  $q = $db->query('SELECT t.*, (SELECT name FROM persons u WHERE u.id=t.ub LIMIT 1) AS ub_name,
        IF(rtype="case", (SELECT name FROM at_cases c WHERE c.id=rid LIMIT 1), (SELECT CONCAT(code, " - ", name) AS name FROM at_tours t WHERE t.id=rid LIMIT 1)) AS rname,
        IF(n_id=0, "", (SELECT title FROM at_messages WHERE at_messages.id=t.n_id LIMIT 1)) AS title
        FROM at_tasks t, at_task_user tu WHERE tu.task_id=t.id AND t.status="off" AND tu.user_id=%i ORDER BY due_dt DESC, is_priority LIMIT 1000', myID);
} else {
    show_error(404);
}
$theTasks = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

// Task task id list
foreach ($theTasks as $t) $theTaskIdList[] = $t['id'];

// The task users
if (empty($theTaskIdList)) {
    $theTaskUsers = array();
} else {
    $q = $db->query('SELECT u.name AS user_name, tu.* FROM persons u, at_task_user tu WHERE tu.user_id=u.id AND tu.task_id IN ('.implode(',', $theTaskIdList).') ORDER BY lname');
    
    $theTaskUsers = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
}


$pageM = 'tasks';
$pageB = array(
    anchor('tasks', __('mn', 'Tasks')),
    );

$thisYear = date('Y');
$today = date('Y-m-d');

include('__hd.php');?>
<div id="tasks" class="analytics-tab paper-stack">
    <ul class="nav nav-tabs">
        <li class="<?=seg2 == '' ? 'active' : ''?>"><a href="<?=DIR?>tasks"><i class="icon-check-empty"></i> Nhiệm vụ chưa làm</a></li>
        <li class="<?=seg2 == 'done' ? 'active' : ''?>"><a href="<?=DIR?>tasks/done"><i class="icon-check"></i> Nhiệm vụ đã làm xong</a></li>
        <li class="<?=seg2 == 'assigned' ? 'active' : ''?>"><a href="<?=DIR?>tasks/assigned"><i class="icon-tasks"></i> Nhiệm vụ đã giao người khác</a></li>
        <li class="<?=seg2 == 'c' ? 'active' : ''?>"><a href="<?=DIR?>tasks/c"><i class="icon-plus"></i> Thêm nhiệm vụ</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active">
            <div class="analytics-tab-content">
    <table class="table table-striped table-condensed">
    <thead>
    <tr>
      <th width="55%">Due date & description</th>
      <th width="25%">Related to</th>
            <th width="5%">Mins</th>
      <th width="15%">Assigned by</th>
    </tr>
    </thead>
    <tbody>
            <? if (empty($theTasks) > 0) { ?><tr><td colspan="4">Không có nhiệm vụ nào</td></tr><? } ?>
      <? foreach ($theTasks as $t) { ?>
      <tr>
        <td>
                    <div id="div-task-<?=$t['id']?>" class="task <?=$t['status'] == 'on' && strtotime($t['due_dt']) < strtotime(NOW) ? 'task-overdue' : ''?> <?=$t['status'] == 'off' ? 'task-done' : ''?>">
                        <i id="icon-<?=$t['id']?>" data-task_id="<?=$t['id']?>" class="task-check <?=$t['status'] == 'off' ? 'icon-check' : 'icon-check-empty'?>"></i>
                        <?
                        if ($t['fuzzy'] == 'date') {
                            // Echo nuffin'
                        } else {
                            if (substr($t['due_dt'], 0, 4) == $thisYear) {
                                $dueDTDisplay = date('d-m', strtotime($t['due_dt']));
                            } else {
                                $dueDTDisplay = date('d-m-Y', strtotime($t['due_dt']));
                            }
                            if (substr($t['due_dt'], 0, 10) == $today) echo '<span class="today">Hôm nay</span> ';
                            echo '<span class="task-date">', $dueDTDisplay, '</span>';
                            if ($t['fuzzy'] == 'time') {
                                // Display nuffin
                            } else {
                                echo ' <span class="task-time">'.substr($t['due_dt'], 11, 5).'</span>';
                            }
                        }

                        ?>
                        <? if ($t['is_priority'] == 'yes') { ?><i style="color:#c00;" title="Priority" class="icon-asterisk"></i><? } ?>
                        <?=myID == $t['ub'] ? anchor('tasks/u/'.$t['id'], $t['description'], 'class="td-n"') : $t['description']?>
                        <? $cnt = 0; foreach ($theTaskUsers as $tu) { if ($tu['task_id'] == $t['id']) { $cnt ++; if ($cnt != 1) echo ', ';?><span id="assignee-<?=$t['id']?>-<?=$tu['user_id']?>" class="small quieter task-assignee <?=$tu['completed_dt'] == ZERODT ? '' : 'done'?>" title="AS: <?=$tu['assigned_dt']?>"><?=$tu['user_id'] == myID ? 'Tôi' : $tu['user_name']?></span><? } } ?>
                    </div>
        </td>
                <td>
                    <? if ($t['n_id'] != 0) { ?>&rarr; <?=anchor('n/r/'.$t['n_id'], $t['title'], 'class="quiet"')?><? } ?>
                    <? if ($t['rid'] != 0 && $t['rtype'] != 'none') { ?> # <?=anchor($t['rtype'].'s/r/'.$t['rid'], $t['rname'], 'class="td-n quiet"')?><? } ?>
                </td>
                <td class="ta-r"><?=$t['mins']?></td>
        <td><?
                    if ($t['ub'] == myID) {
                        echo 'Tôi ['.anchor('tasks/u/'.$t['id'], 'Sửa').'-'.anchor('tasks?redir=ref&action=delete&taskId='.$t['id'], 'Xoá').']';
                    } else {
                        echo $t['ub_name'];
                    }
          ?>
        </td>
      </tr>
      <? } ?>
    </tbody>
  </table>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    $('i.task-check').on('click', function(){
        var task_id = $(this).data('task_id');
        $.post('<?=DIR?>tasks/ajax', {action:'check', task_id:task_id}, function(data){
            if (data.status) {
                if (data.status == 'OK') {
                    $('span#assignee-' + task_id + '-' + '<?=myID?>').toggleClass('done');
                    $('i#icon-' + task_id).removeClass('icon-check').removeClass('icon-check-empty').addClass(data.icon);
                    if (data.icon == 'icon-check') {
                        $('div#div-task-' + task_id).removeClass('task-overdue');
                    }
                } else {
                    alert(data.message);
                }
            } else {
                alert('Error: data error.');
            }
        }, 'json');
    });
});
</script>

*/