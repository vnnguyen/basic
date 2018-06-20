<?
use yii\helpers\Html;

include('_tasks_inc.php');

$this->title = 'Tasks';

?>
<div class="col-md-12">
	<div class="table-responsive">
		<table class="table table-bordered table-striped table-condensed">
			<thead>
				<tr>
					<td width="30"></td>
					<th>Due date</th>
					<th>Description</th>
					<th>Assigned to</th>
					<th>Related to</th>
					<th>Assigned by</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<? $cnt = 0; foreach ($theTasks as $task) { $cnt ++; ?>
				<tr>
					<td class="text-muted text-center"><?= $cnt ?></td>
					<td><?= $task['due_dt'] ?></td>
					<td><?= Html::a($task['description'], '@web/tasks/r/'.$task['id']) ?></td>
					<td><?
foreach ($task['assignees'] as $user) {
	echo Html::a($user['name'], '@web/users/r/'.$user['id']);
}
					?></td>
					<td><?= $task['rtype'] ?></td>
					<td><?= $task['createdBy']['name'] ?></td>
					<td><?= $task['id'] ?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
</div>
