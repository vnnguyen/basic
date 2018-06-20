<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

include('_events_inc.php');

$this->title = 'Events ('.$pages->totalCount.')';
/*
$getMonth = fRequest::get('month', 'string', 'from', true);
$getUser = fRequest::get('user', 'string', 'all', true);
$getType = fRequest::get('type', 'string', 'all', true);
$getStatus = fRequest::get('status', 'string', 'all', true);

$andMonth = 'AND SUBSTRING(from_dt, 1, 7)="'.$getMonth.'"';
	if ($getMonth == 'all') $andMonth = '';
	if ($getMonth == 'from') $andMonth = ' AND from_dt>="'.date('Y-m-d').' 00:00:00"';
$andUser = 'AND user="'.$getUser.'"'; if ($getUser == 'all') $andUser = '';
$andType = 'AND stype="'.$getType.'"'; if ($getType == 'all') $andType = '';
$andStatus = 'AND status="'.$getStatus.'"'; if ($getStatus == 'all') $andStatus = '';

$q = $db->query('SELECT COUNT(*) AS total, SUBSTRING(from_dt, 1, 7) AS month FROM at_events GROUP BY month ORDER BY month DESC');
$eventMonthList = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

$q = $db->query('SELECT COUNT(*) AS total, u.fname, u.lname, u.email, u.id FROM at_event_user eu, persons u WHERE u.id=eu.user_id GROUP BY eu.user_id ORDER BY u.lname');
$eventUserList = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

// Su kien
if ($getUser == 'all') {
	$q = $db->query('SELECT * FROM at_events WHERE 1=1 '.$andMonth.' '.$andType.' '.$andStatus.' ORDER BY from_dt LIMIT 1000');
	$theEvents = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
} else { 
	$q = $db->query('SELECT e.* FROM at_events e, at_event_user eu WHERE eu.event_id=e.id AND eu.user_id=%i '.$andMonth.' '.$andType.' '.$andStatus.' ORDER BY from_dt LIMIT 1000', (int)$getUser);
	$theEvents = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
}

// Nguoi nghi
if (!empty($theEvents)) {
	$eventIdList = array();
	foreach ($theEvents as $event) $eventIdList[] = $event['id'];
	$q = $db->query('SELECT u.id, u.name, eu.event_id FROM persons u, at_event_user eu WHERE u.id=eu.user_id AND eu.event_id IN ('.implode(',', $eventIdList).') ORDER BY u.lname LIMIT 1000');
	$eventUsers = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
}

$pageT = 'Các sự kiện';
if ($getMonth == 'all') $pageT .= ' sắp tới';
$pageM = 'events';
$pageB = array(
	a('events', 'Events'),
	);
include('_hd_13.php');
*/
$eventUserList = [];

?>
<div class="col-md-12">
	<form method="get" action="" class="well well-sm form-inline">
		<div class="form-group">
			<select class="form-control" name="month">
				<option value="all">Thời gian</option>
				<option value="fromnow" <?= $getMonth == 'from' ? 'selected="selected"' : ''?>>Hôm nay trở đi</option>
				<? foreach ($monthList as $month) { ?>
				<option value="<?=$month['ym']?>" <?= $getMonth == $month['ym'] ? 'selected="selected"' : ''?>><?=$month['ym']?></option>
				<? } ?>
			</select>
		</div>
		<div class="form-group">
			<select class="form-control" name="user">
				<option value="0">Related people</option>
				<? foreach ($eventUserList as $eu) { ?>
				<option value="<?=$eu['id']?>" <?=$getUser == $eu['id'] ? 'selected="selected"' : ''?>><?=$eu['lname']?>, <?=$eu['fname']?> (<?=$eu['total']?>)</option>
				<? } ?>
			</select>
		</div>
		<div class="form-group">
			<select class="form-control" name="type">
				<option value="all">All types</option>
				<? foreach ($eventTypeList as $k=>$v) { ?>
				<option value="<?=$k?>" <?= $getType == $k ? 'selected="selected"' : ''?>><?=$v?></option>
				<? } ?>
			</select>
		</div>
		<div class="form-group">
			<select class="form-control" name="status">
				<option value="all">All status</option>
				<? foreach ($eventStatusList as $k=>$v) { ?>
				<option value="<?=$k?>" <?= $getStatus == $k ? 'selected="selected"' : ''?>><?=$v?></option>
				<? } ?>
			</select>
		</div>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', 'events') ?>
	</form>
	<? if (empty($theEvents)) { ?><p>No events found</p><? } else { ?>
	<table class="table table-condensed table-striped table-bordered">
		<thead>
			<tr>
				<th width="30"></th>
				<th>Sự kiện / Sự việc</th>
				<th>Liên quan đến</th>
				<th>Thời gian từ - đến</th>
				<th width="50" class="text-center">Ngày</th>
				<th width="50" class="text-center">Giờ</th>
				<th width="50" class="text-center">Phút</th>
				<th>Trạng thái</th>
				<th width="40"></th>
			</tr>
		</thead>
		<tbody>
			<?
			$cnt = 0;
			$totalMins = 0;
			foreach ($theEvents as $event) {
				$cnt ++;
				$totalMins += $event['mins'];
			?>
			<tr>
				<td class="muted text-center" style="border-left:3px solid <?=$eventTypeColors[$event['stype']]?>"><?=$cnt?></td>
				<td><?= Html::a($event['name'], 'events/r/'.$event['id'])?></td>
				<td><?
				foreach ($event['people'] as $user) {
					echo '<div>', Html::a($user['name'], 'events?month='.substr($event['from_dt'], 0, 7).'&user='.$user['id']), '</div>';
				}
				?></td>
				<td>
				<?
				$todayDay = date('Y-m-d');
				$fromDate = substr($event['from_dt'], 0, 10);
				$fromTime = substr($event['from_dt'], 11, 5);
				$untilDate = substr($event['until_dt'], 0, 10);
				$untilTime = substr($event['until_dt'], 11, 5);
				
				echo $fromDate == $todayDay ? 'Hôm nay ' : date('d/m/Y', strtotime($event['from_dt']));
				if ($untilDate == $fromDate) {
					if ($fromTime == '08:00' && $untilTime == '17:30') {
						echo ' cả ngày';
					} elseif ($fromTime == '08:00' && $untilTime == '12:00') {
						echo ' cả sáng';
					} elseif ($fromTime == '13:30' && $untilTime == '17:30') {
						echo ' cả chiều';
					} else {
						echo ' ', $fromTime, ' - ', $untilTime;
					}
				} else {
					echo ' ', $fromTime, ' - ', date('d/m/Y', strtotime($event['until_dt'])), ' ', $untilTime;
				}
				?>
				</td>
				<?
	$event['days'] = floor($event['mins'] / (60 * 8));
	$event['hours'] = floor(($event['mins'] - $event['days'] * 60 * 8) / 60);
	$event['mins'] = $event['mins'] - ($event['days'] * 60 * 8) - ($event['hours'] * 60);
				?>
				<td class="text-center"><?=$event['days'] == 0 ? '' : number_format($event['days'], 0)?></td>
				<td class="text-center"><?=$event['hours'] == 0 ? '' : number_format($event['hours'], 0)?></td>
				<td class="text-center"><?=$event['mins'] == 0 ? '' : number_format($event['mins'], 0)?></td>
				<td><?=$eventStatusList[$event['status']]?></td>
				<td>
					<a title="Edit" class="text-muted" href="<?=DIR?>events/u/<?=$event['id']?>"><i class="fa fa-edit"></i></a>
					<a title="Delete" class="text-muted" href="<?=DIR?>events/d/<?=$event['id']?>"><i class="fa fa-trash-o"></i></a>
				</td>
			</tr>
			<? } ?>
			<tr>
				<td class="ta-r" colspan="4">Tổng số:</td>
				<?
	$totalRealMins = $totalMins;
	$totalDays = floor($totalMins / (60 * 8));
	$totalHours = floor(($totalMins - $totalDays * 60 * 8) / 60);
	$totalMins = $totalMins - ($totalDays * 60 * 8) - ($totalHours * 60);
				?>
				<td class="text-center fw-b"><?=number_format($totalDays, 0)?></td>
				<td class="text-center fw-b"><?=number_format($totalHours, 0)?></td>
				<td class="text-center fw-b"><?=number_format($totalMins, 0)?></td>
				<td class="ta-r fw-b">= <?=number_format($totalRealMins, 0)?></td>
				<td>phút</td>
			</tr>
		</tbody>
	</table>
	<? if ($pages->totalCount > $pages->pageSize) { ?>
	<div class="text-center">
	<?=LinkPager::widget(array(
		'pagination' => $pages,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	));?>
	</div>
	<? } // if page ?>
	<? } // if empty events ?>
</div>