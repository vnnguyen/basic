<?
use yii\helpers\Html;


$this->title = 'Tasks I assigned other people';
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
						<div id="div-task-<?= $task['id'] ?>" class="task <?=$task['status'] == 'on' && strtotime($task['due_dt']) < strtotime(NOW) ? 'task-overdue' : ''?> <?=$task['status'] == 'off' ? 'task-done' : ''?>">
						<i id="icon-<?= $task['id'] ?>" data-task_id="<?= $task['id'] ?>" class="fa fa-fw task-check <?= $task['status'] == 'off' ? 'fa-check-square-o' : 'fa-fw fa-square-o' ?>"></i>
<?
$thisYear = date('Y');
$today = date('Y-m-d');
	if ($task['fuzzy'] == 'date') {
		// Echo nuffin'
	} else {
		if (substr($task['due_dt'], 0, 4) == $thisYear) {
			$dueDTDisplay = date('d-m', strtotime($task['due_dt']));
		} else {
			$dueDTDisplay = date('d-m-Y', strtotime($task['due_dt']));
		}
		if (substr($task['due_dt'], 0, 10) == $today) echo '<span class="today">Hôm nay</span> ';
		echo '<span class="task-date">', $dueDTDisplay, '</span>';
		if ($task['fuzzy'] == 'time') {
			// Display nuffin
		} else {
			echo ' <span class="task-time">'.substr($task['due_dt'], 11, 5).'</span>';
	}
}

?>
						<? if ($task['is_priority'] == 'yes') { ?><i title="Prioriry" class="fa fa-star text-danger"></i><? } ?>
						<?= Html::a($task['description'], '@web/tasks/u/'.$task['id']) ?>
<?
$cnt = 0;
foreach ($taskUserList as $user) {
	if ($user['task_id'] == $task['id']) {
		$cnt ++;
		if ($cnt != 1) {
			echo ', ';
		}
?>
						<span id="assignee-<?= $task['id'] ?>-<?= $user['id'] ?>" class="text-muted task-assignee <?= $user['completed_dt'] == '0000-00-00 00:00:00' ? '' : 'done' ?>" title="Assigned: <?= $user['assigned_dt'] ?>"><?= $user['id'] == MY_ID ? 'Tôi' : $user['name'] ?></span>
<?
	}
}
?>
						</div>
					</td>
					<td>
						<? if ($task['n_id'] != 0) { ?>&rarr; <?= Html::a($task['title'], '@web/n/r/'.$task['n_id'], ['class'=>'text-muted'])?><? } ?>
						<? if ($task['rid'] != 0 && $task['rtype'] != 'none') { ?> # <?= Html::a($task['rname'], DIR.$task['rtype'].'s/r/'.$task['rid'],  ['class'=>'text-muted'])?><? } ?>
					</td>
					<td><?= $task['mins'] ?></td>
					<td>
<?
	if ($task['ub'] == MY_ID) {
		echo 'Tôi ['. Html::a('Sửa', '@web/tasks/u/'.$task['id']).'-'. Html::a('Xoá', '@web/tasks?redir=ref&action=delete&taskId='.$task['id']).']';
	} else {
		echo $task['ub_name'];
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

<?
$js = <<<'TXT'
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
TXT;

$this->registerJs($js);