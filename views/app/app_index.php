<?
use yii\helpers\Html;
$this->title = 'Welcome back';
$this->params['icon'] = 'dashboard';
$this->params['breadcrumb'] = [
	['Workspace', ''],
];
?>
<style>
.task-overdue .task-date {background-color:#fcc; padding:0 4px;}
.task-today {background-color:#ffc; padding:0 4px;}
</style>
<div class="col-lg-6 col-md-8">
	<div class="panel panel-info">
		<div class="panel-heading">
			<?= Html::a('View all', '@web/notes', ['class'=>'pull-right']) ?>
			<strong>LATEST NOTES & ACTIVITIES</strong>
		</div>
		<table class="table table-condensed">
			<? if (empty($theNotes)) { ?><tr><td>No data found.</td></tr><? } else { ?>
			<? foreach ($theNotes as $li) { ?>
			<tr><td>
		<?
		if ($li['from']['image'] != '') {
			$avatar = DIR.'timthumb.php?w=100&h=100&zc=1&src='.$li['from']['image'];
		} else {
			$avatar = 'https://secure.gravatar.com/avatar/'.md5($li['from']['id']).'.jpg?s=100&d=wavatar';
		}
		?>
		<?= Html::img($avatar, ['style'=>'width:20px; height:20px;']) ?>
		<? if ($li['via'] == 'email') { ?><i class="fa fa-envelope-o"></i><? } ?>
		<?= Html::a($li['from']['name'], '@web/users/r/'.$li['from']['id'], ['style'=>'color:#963']) ?>:
		<? if ($li['priority'] == 'B2') { ?><span class="label label-warning">Important</span><? } ?>
		<? if ($li['priority'] == 'C3') { ?><span class="label label-danger">Urgent</span><? } ?>
		<?= Html::a($li['title'] == '' ? '( No title )' : $li['title'], '@web/notes/r/'.$li['id']) ?>
		<?= $li['relatedCase'] && $li['rtype'] == 'case' ? ' # '.Html::a($li['relatedCase']['name'], '@web/cases/r/'.$li['relatedCase']['id'], ['style'=>'color:#060']) : ''?>
		<?= $li['relatedTour'] && $li['rtype'] == 'tour' ? ' # '.Html::a($li['relatedTour']['code'].' - '.$li['relatedTour']['name'], '@web/tours/r/'.$li['relatedTour']['id'], ['style'=>'color:#060']) : '' ?>
		<span class="text-muted"><?= $li['uo'] == $li['co'] ? '' : 'edited ' ?><?= $li['uo'] ?></span>
			</td></tr>
			<? } // foreach notes ?>
			<? } // if not empty ?>
		</table>
	</div>

	<p>
		<?= Html::a('View all', '@web/kb/lists/members', ['class'=>'pull-right btn btn-xs btn-default']) ?>
		<strong>CURRENTLY ONLINE</strong>
	</p>
	<div class="mb-1em clearfix">
		<? foreach ($onlineUsers as $li) { ?>
		<?= Html::a(Html::img(DIR.'timthumb.php?zc=1&w=100&h=100&src='.$li['image'], ['style'=>'width:48px; height:48px; float:left; display:block; margin:0 0 4px 4px;']), '@web/users/r/'.$li['id'], ['title'=>$li['name']]) ?>
		<? } ?>
	</div>
</div>
<div class="col-lg-6 col-md-4">
	<div class="row">
		<div class="col-lg-6">
			<? if (in_array(Yii::$app->user->id, [1, 118]) && !empty($theTours)) { ?>
			<div class="panel panel-success">
				<div class="panel-heading">
					<?= Html::a('View all', '@web/tours', ['class'=>'pull-right']) ?>
					<strong>NEW TOURS</strong>
				</div>
				<table class="table table-condensed">
					<? foreach ($theTours as $nt) { ?>
					<tr>
						<td>
							<strong><?= Html::a('Please accept: '.$nt['code'].' ('.$nt['day_count'].' days, from '.$nt['day_from'].')', '@web/tours/accept/'.$nt['id']) ?></strong>
							-
							<?= Html::a('View', '@web/tours/r/'.$nt['id']) ?>
							<br>
							<?= Html::a($nt['se_name'], '@web/users/r/'.$nt['se'])?>
							<span class="text-muted"><?= date('d-m-Y H:i', strtotime($nt['uo'])) ?></span>
					</tr>
					<? } ?>
				</table>
			</div>
			<? } // end if ?>

			<div class="panel panel-warning">
				<div class="panel-heading">
					<?= Html::a('View all', '@web/tasks', ['class'=>'pull-right']) ?>
					<strong>MY TASKS</strong>
				</div>
				<table class="table table-condensed">
					<? if (empty($theTasks)) { ?><tr><td>No tasks found.</td></tr><? } else { ?>
					<?
					$thisYear = date('Y');
					$today = date('Y-m-d');
					foreach ($theTasks as $t) {
					?>
					<tr>
						<td id="div-task-<?=$t['id']?>" class="task <?=$t['status'] == 'on' && strtotime($t['due_dt']) < strtotime(NOW) ? 'task-overdue' : ''?> <?=$t['status'] == 'off' ? 'task-done' : ''?>">
						<i id="icon-<?=$t['id']?>" data-task_id="<?=$t['id']?>" class="fa fa-<?=$t['status'] == 'on' ? '' : 'check-' ?>square-o"></i>
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
						<? if ($t['is_priority'] == 'yes') { ?><i style="color:#c00;" title="Priority" class="icon-asterisk"></i><? } ?>
						<?=Yii::$app->user->id == 1 || $t['ub'] ? Html::a($t['description'], '@web/tasks/u/'.$t['id']) : $t['description']?>
						<?= Html::a('<i class="fa fa-fw fa-link"></i>', DIR.$t['rtype'].'s/r/'.$t['rid'], ['class'=>'text-muted', 'title'=>'Link to '.$t['rtype']]) ?>
						<? $cnt = 0; foreach ($theTaskUsers as $tu) { if ($tu['task_id'] == $t['id']) { $cnt ++; if ($cnt != 1) echo ', ';?><span id="assignee-<?=$t['id']?>-<?=$tu['user_id']?>" class="text-muted <?=$tu['completed_dt'] == '0000-00-00 00:00:00' ? '' : 'done'?>" title="AS: <?=$tu['assigned_dt']?>"><?=$tu['user_id'] == Yii::$app->user->id ? 'Tôi' : $tu['user_name']?></span><? } } ?>
						</td>
					</tr>
					<? } // foreach tasks ?>
					<? } // if empty ?>
				</table>
			</div>
			<? if (!empty($newPayments)) { ?>
			<div class="panel panel-success">
				<div class="panel-heading">
					<strong>RECENT TOUR PAYMENTS</strong>
					<?= Html::a('View all', '@web/payments', ['class'=>'pull-right']) ?>
				</div>
				<table class="table table-condensed">
					<? foreach ($newPayments as $payment) { ?>
					<tr>
						<td style="white-space:nowrap;">
							<i class="fa fa-info-circle popovers pull-left text-muted"
								data-trigger="hover"
								data-title="<?= $payment['method'] ?>"
								data-html="true"
								data-content="<?= nl2br($payment['note']) ?><br>(By <?= $payment['updated'] ?>)"></i>
							<strong><?= substr($payment['payment_dt'], 8, 2) ?></strong>
							<small class="text-muted"><?= substr($payment['payment_dt'], 11, 5) ?></small>
						</td>
						<td width="75">
							<?= Html::a($payment['tour_code'], '@web/tours/r/'.$payment['tour_id'], ['style'=>'background:#ffc; color:#148040; padding:0 3px; ']) ?>
						</td>
						<td title="Payer">
							<?= $payment['payer'] ?>
						</td>
						<td class="text-right">
							<?= number_format($payment['amount'], 0) ?>
							<span class="text-muted"><?= $payment['currency'] ?></span>
						</td>
					</tr>
					<? } ?>
				</table>
			</div>
			<? } //payments ?>

			<? if (!empty($absentPeople)) { ?>
			<div class="panel panel-danger">
				<div class="panel-heading">
					<strong>ON LEAVE TODAY</strong>
					<?= Html::a('Calendar', '@web/calendar', ['class'=>'pull-right']) ?>
				</div>
				<table class="table table-condensed">
					<? foreach ($absentPeople as $li) { ?>
					<tr>
						<td>
							<?= Html::img(DIR.'timthumb.php?w=100&h=100&src='.$li['image'], ['style'=>'height:20px; width:20px;']) ?>
							<?= Html::a ($li['name'], '@web/users/r/'.$li['id']) ?>
							<?= $li['e_name'] ?>
						</td>
						<td>
							<strong><?= date('d', strtotime($li['from_dt'])) ?></strong>
							<small class="text-muted"><?= date('H:i', strtotime($li['from_dt'])) ?></small>
							-
							<? if (substr($li['from_dt'], 0, 10) != substr($li['until_dt'], 0, 10)) { ?><strong><?= date('d', strtotime($li['until_dt'])) ?></strong><? } ?>
							<small class="text-muted"><?= date('H:i', strtotime($li['until_dt'])) ?></small>
						</td>
					</tr>
					<? } ?>
				</table>
			</div>
			<? } //absent ?>
		</div>
		<div class="col-lg-6">
			<? if (app\helpers\User::inGroups('any:it,lanhdao,banhang')) { ?>
			<div class="panel panel-danger">
				<div class="panel-heading">
					<i class="fa fa-bar-chart-o"></i> <strong>SELLER'S REPORTS</strong>
				</div>
				<div class="panel-body bg-danger">
					<p>
					<?= Html::a('Thống kê HS của tôi', '@web/me/reports', ['rel'=>'external']) ?>
					&middot;
					<?= Html::a('Tỉ lệ HS thành công chung', '@web/manager/sales-results', ['rel'=>'external']) ?>
					&middot;
					<?= Html::a('Tỉ lệ HS thành công theo nguồn khách', '@web/manager/sales-results-sources', ['rel'=>'external']) ?>
					&middot;
					<?= Html::a('Số lượng HS thành công theo tháng', '@web/manager/sales-results-changes', ['rel'=>'external']) ?>
					&middot;
					<?= Html::a('Số lượng HS giao người bán hàng các tháng', '@web/manager/sales-results-assignments?seller='.Yii::$app->user->id, ['rel'=>'external']) ?>
					</p>
					<select class="form-control" onchange="if ($(this).val() != 0) location.assign('https://my.amicatravel.com/manager/sales-results-seller?source=all&year=2014&seller=' + $(this).val());">
						<option value="0">- Select to view a seller's report -</option>
						<? foreach ($sellerList as $seller) { ?>
						<option value="<?= $seller['id'] ?>"><?= $seller['lname'] ?>, <?= $seller['fname'] ?> (<?= $seller['email'] ?>)</option>
						<? } ?>
					</select>
				</div>
			</div>
			<? } ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>RECENTLY VIEWED ITEMS</strong>
					<?= Html::a('View all', '@web/me/viewed', ['class'=>'pull-right']) ?>
				</div>
				<table class="table table-condensed">
					<? foreach ($theViewedItems as $it) { ?>
					<tr>
						<td><?
						if ($it['rtype'] == 'case') echo '<i class="text-muted fa fa-briefcase"></i> ';
						if ($it['rtype'] == 'tour') echo '<i class="text-muted fa fa-truck"></i> ';
						echo Html::a($it['name'], DIR.$it['rtype'].'s/r/'.$it['rid'], ['rel'=>'external']);
						?></td>
					</tr>
					<? } ?>
				</table>
			</div>

			<!--
			<div class="panel panel-default">
				<div class="panel-heading">
					<?= Html::a('View all', '@web/me/starred', ['class'=>'pull-right']) ?>
					<strong>MY STARRED ITEMS</strong>
				</div>
				<table class="table table-condensed">
					<? if (empty($theStarredItems)) { ?><tr><td>No items found</td></tr><? } ?>
					<? foreach ($theStarredItems as $it) { ?>
					<tr>
						<td>
							<?
						if ($it['rtype'] == 'case') echo '<i class="text-muted fa fa-briefcase"></i> ';
						if ($it['rtype'] == 'tour') echo '<i class="text-muted fa fa-truck"></i> ';
						echo '<i class="fa fa-star text-warning"></i> ';
						echo Html::a($it['name'], DIR.$it['rtype'].'s/r/'.$it['rid'], ['rel'=>'external']);
							?>
						</td>
					</tr>
					<? } ?>
				</table>
			</div>
			-->
		</div>
	</div>
</div>
<?
$js = <<<TXT
$('i.task-check').on('click', function(){
	var task_id = $(this).data('task_id');
	$.post('/tasks/ajax', {action:'check', task_id:task_id}, function(data){
		if (data.status) {
			if (data.status == 'OK') {
				$('span#assignee-' + task_id + '-' + '{myID}).toggleClass('done');
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
//$this->registerJs(str_replace('{myID}', Yii::$app->user->id, $js));