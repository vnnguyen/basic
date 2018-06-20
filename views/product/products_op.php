<?
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use app\helpers\DateTimeHelper;

include('_products_inc.php');

$this->params['breadcrumb'][] = ['View', '@web/products/r/'.$theProduct['id']];

// Calculate the time of notes and emails
$myTimeZone = Yii::$app->user->identity->timezone;
if (!in_array($myTimeZone, ['UTC', 'Europe/Paris', 'Asia/Ho_Chi_Minh'])) {
	$myTimeZone = 'Asia/Ho_Chi_Minh';
}

$timeTable = [];
foreach ($theNotes as $note) {
	$time = DateTimeHelper::convert($note['co'], 'Y-m-d H:i:s', 'Asia/Ho_Chi_Minh', $myTimeZone);
	$timeTable[$time] = ['object'=>'note', 'id'=>$note['id'], 'title'=>$note['title']];
}
krsort($timeTable);

?>
<div class="col-md-12">
	<ul class="nav nav-tabs mb-1em">
		<li class=""><a href="/products/r/<?= $theProduct['id'] ?>">Product overview</a></li>
		<li><a href="/products/sb/<?= $theProduct['id'] ?>">Sales &amp; Bookings</a></li>
		<li class="active"><a href="/products/op/<?= $theProduct['id'] ?>">Operation</a></li>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">Testing menu <span class="caret"></span></a>
			<ul class="dropdown-menu" role="menu" aria-labelledby="xxx">
				<li role="presentation" class="dropdown-header">PRODUCT</li>
				<li class=""><a role="menuitem" href="">Product Overview</a></li>
				<li class=""><a role="menuitem" href="">Itinerary</a></li>
				<li class=""><a role="menuitem" href="">Prices</a></li>
				<li class=""><a role="menuitem" href="">Files &amp; Notes</a></li>
				<li role="presentation" class="divider"></li>
				<li role="presentation" class="dropdown-header">SALES</li>
				<li><a href="/products/sb/<?= $theProduct['id'] ?>">Sales Overview</a></li>
				<li><a href="/bookings?product_id=<?= $theProduct['id'] ?>">Bookings</a></li>
				<li class=""><a role="menuitem" href="">People</a></li>
				<li class=""><a role="menuitem" href="">Payments</a></li>
				<li role="presentation" class="divider"></li>
				<li role="presentation" class="dropdown-header">OPERATIONS</li>
				<li><a href="/products/op/<?= $theProduct['id'] ?>">Operation Overview</a></li>
				<li class=""><a role="menuitem" href="">Service costs</a></li>
				<li class=""><a role="menuitem" href="">Customers</a></li>
				<li class=""><a role="menuitem" href="">Feedback</a></li>
				<li class=""><a role="menuitem" href="">Files &amp; Notes</a></li>
			</ul>
		</li>
	</ul>
</div>

<div class="col-md-6">
	<div class="alert alert-warning">THIS IS A TEST PAGE</div>
	<ul class="media-list note-list">
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
		<li class="media note-list-item">
			<?= Html::a(Html::img($userAvatar, ['class'=>'note-author-avatar']), '@web/users/r/'.$note['from']['id'], ['class'=>'hidden-sm media-left']) ?>
			<?
			$title = $note['title'];
			$body = $note['body'];
			/*
			// Name mentions
			$toEmailList = [];
			foreach ($thePeople as $person) {
				$mention = '@[user-'.$person['id'].']';
				if (strpos($body, $mention) !== false) {
					$body = str_replace($mention, '@'.Html::a($person['name'], '@web/users/r/'.$person['id'], ['style'=>'font-weight:bold;']), $body);
					$toEmailList[] = $person['email'];
				}
			}
			$toEmailList = array_unique($toEmailList);
			*/
			$body = str_replace(['width:', 'height:', 'font-size:', '<table ', '<p>&nbsp;</p>'], ['x:', 'x:', 'x:', '<table class="table table-condensed table-bordered" ', ''], $body);
			$body = HtmlPurifier::process($body);
			?>
			<div class="media-body note-content">
				<h5 class="media-heading note-heading">
					<? if ($note['via'] == 'email') { ?><i class="fa fa-envelope-o"></i><? } ?>
					<?= Html::a($note['from']['name'], '@web/users/r/'.$note['from_id'], ['class'=>'note-author-name']) ?>
					:
					<?= Html::a($title, '@web/notes/r/'.$note['id'], ['class'=>'note-title']) ?>
					<?
					if ($note['to']) {
						echo ' <span class="text-muted">to</span> ';
						$cnt = 0;
						foreach ($note['to'] as $to) {
							$cnt ++;
							if ($cnt > 1) echo ', ';
							echo Html::a($to['name'], '@web/users/r/'.$note['id'], ['style'=>'color:purple;']);
						}
					}
					?>
				</h5>
				<div class="mb-1em">
					<span class="text-muted timeago" title="<?= date('Y-m-d\TH:i:s', strtotime($note['co'])) ?>+07"><?= $time ?></span>
					- <?= Html::a('Edit', '@web/notes/u/'.$note['id']) ?>
					- <?= Html::a('Delete', '@web/notes/d/'.$note['id']) ?>
				</div>
				<? if ($note['files']) { ?>
				<div class="note-file-list">
					<? foreach ($note['files'] as $file) { ?>
					<div class="note-file-list-item">+ <?= Html::a($file['name'], '@web/files/r/'.$file['id']) ?> <span class="text-muted"><?= number_format($file['size'] / 1024, 2) ?> KB</span></div>
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
			}
		} // foreach timeTable
?>
	</ul>
</div>
<div class="col-md-3">
	<div class="panel panel-warning">
		<div class="panel-heading">
			<?= Html::a('View all', 'tasks', ['class'=>'pull-right']) ?>
			<strong>RELATED TASKS</strong>
		</div>
		<table class="table table-condensed">
			<? if (empty($theTour['tasks'])) { ?><tr><td>No tasks found.</td></tr><? } else { ?>
			<?
			$thisYear = date('Y');
			$today = date('Y-m-d');
			foreach ($theTour['tasks'] as $task) {
			?>
			<tr>
				<td id="div-task-<?=$task['id']?>" class="task <?=$task['status'] == 'on' && strtotime($task['due_dt']) < strtotime(NOW) ? 'task-overdue' : ''?> <?=$task['status'] == 'off' ? 'task-done' : ''?>">
				<i id="icon-<?=$task['id']?>" data-task_id="<?=$task['id']?>" class="fa fa-<?=$task['status'] == 'on' ? '' : 'check-' ?>square-o"></i>
				<?
				if ($task['fuzzy'] == 'date') {
					// Echo nuffin'
				} else {
					if (substr($task['due_dt'], 0, 4) == $thisYear) {
						$dueDTDisplay = date('d-m', strtotime($task['due_dt']));
					} else {
						$dueDTDisplay = date('d-m-Y', strtotime($task['due_dt']));
					}
					if (substr($task['due_dt'], 0, 10) == $today) echo '<span class="task-today">Today</span> ';
					echo '<span class="task-date">', $dueDTDisplay, '</span>';
					if ($task['fuzzy'] == 'time') {
						// Display nuffin
					} else {
						echo ' <span class="task-time">'.substr($task['due_dt'], 11, 5).'</span>';
					}
				}
/*
				?>
				<? if ($task['is_priority'] == 'yes') { ?><i style="color:#c00;" title="Priority" class="icon-asterisk"></i><? } ?>
				<?= Yii::$app->user->id == 1 || $task['ub'] ? Html::a($task['description'], 'tasks/u/'.$task['id']) : $task['description']?>
				<? $cnt = 0; foreach ($task['assignees'] as $tu) { $cnt ++; if ($cnt != 1) echo ', ';?><span id="assignee-<?=$task['id']?>-<?=$tu['id']?>" class="text-muted <?=$tu['completed_dt'] == '0000-00-00 00:00:00' ? '' : 'done'?>" title="AS: <?=$tu['assigned_dt']?>"><?=$tu['user_id'] == Yii::$app->user->id ? 'Tôi' : $tu['user_name']?></span><? } */ ?>
				</td>
			</tr>
			<? } // foreach tasks ?>
			<? } // if empty ?>
		</table>
	</div>
	<div class="panel panel-success">
		<div class="panel-heading">
			<?= Html::a('View all', '#', ['class'=>'pull-right']) ?>
			<strong>PAX LIST</strong>
		</div>
		<table class="table table-condensed">
			<tbody>
				<?
				if (isset($theProduct['bookings']['pax'])) {
					foreach ($theProduct['bookings']['pax'] as $pax) { ?>
				<tr>
					<td><?= $pax['fname'] ?> / <?= $pax['lname'] ?></td>
				</tr>
				<? } } ?>
			</tbody>
		</table>
	</div>
</div>
<div class="col-md-3">
	<div class="panel panel-default">
		<div class="panel-heading"><strong>ITINERARY</strong> <?= count($theDays) ?> days, from <?= $theProduct['day_from'] ?></div>
		<table class="table table-condensed">
			<? $cnt = 0; foreach ($theDays as $day) { $cnt ++; ?>
			<tr>
				<td class="text-right"><?= $cnt ?></td>
				<td><?= $day['name'] ?></td>
				<td><?= $day['meals'] ?></td>
			</tr>
			<? } ?>
		</table>
	</div>
</div>

<?
$js = <<<TXT
$('.ckeditor').ckeditor({
	customConfig: '/assets/js/ckeditor_config_simple_1.js'
});
TXT;
$this->registerJsFile(DIR.'assets/cksource/ckeditor_4.3.4/ckeditor.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/cksource/ckeditor_4.3.4/adapters/jquery.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);