<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Markdown;

include('_events_inc.php');

$this->title = $theEvent['name'];

?>
<div class="col-md-8">
	<table class="table table-condensed table-bordered">
		<tbody>
			<tr>
				<td>Name</td>
				<td><?= $theEvent['name'] ?></td>
			</tr>
			<tr>
				<td>Description</td>
				<td><?= nl2br($theEvent['info']) ?></td>
			</tr>
			<tr>
				<td>Time</td>
				<td><?= $theEvent['from_dt'] ?> to <?= $theEvent['until_dt'] ?></td>
			</tr>
			<tr>
				<td>Venue</td>
				<td><?= $theEvent['venue'] ?></td>
			</tr>
			<tr>
				<td>People</td>
				<td>
					<? $cnt = 0; foreach ($theEvent['people'] as $user) { ?>
					<div><?= ++$cnt.'. '.$user['name'] ?></div>
					<? } ?>
				</td>
			</tr>
		</tbody>
	</table>
	<? if (!empty($theComments)) { ?>
	<p><strong>COMMENTS</strong></p>
	<div id="comments" class="mb-1em">
		<? foreach ($theComments as $comment) { ?>
		<div class="media" id="comment-id-<?= $comment['id'] ?>">
			<a class="pull-left" href="<?= DIR ?>users/r/<?= $comment['createdBy']['id'] ?>"><img class="media-object" style="width:64px; height:64px;" src="<?= DIR ?>timthumb.php?w=100&h=100&src=<?= $comment['createdBy']['image'] ?>" alt="Avatar"></a>
			<div class="media-body" style="border:1px solid #eee; background:transparent url(https://my.amicatravel.com/assets/img/layout/grids.png) left top repeat; padding:10px;">
				<h5 class="media-heading"><a href="<?= DIR ?>users/r/<?= $comment['createdBy']['id'] ?>" style="color:#960; font-weight:bold;"><?= $comment['createdBy']['name'] ?></a></h5>
				<div class="mb-1em"><span class="text-muted"><?=date_format(date_timezone_set(date_create($comment['created_at']), timezone_open('Asia/Saigon')), 'd-m-Y H:i')?></span></div>
				<?= nl2br($comment['body']) ?>
			</div>
		</div>
		<? } ?>
	</div>
	<? } // if empty comments ?>
	<? $form = ActiveForm::begin() ?>
	<div id="comment" class="mb-1em">
		<p><strong>ADD YOUR COMMENT</strong></p>
		<div class="media">
			<a class="pull-left" href="#"><img class="media-object" style="width:60px; height:60px;" src="<?= Yii::$app->user->identity->image ?>" alt="Avatar"></a>
			<div class="media-body">
				<?= $form->field($theComment, 'body', ['template'=>'{input}{hint}{error}'])->textArea(['rows'=>5, 'class'=>'form-control']) ?>
				<div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Post comment'), ['class' => 'btn btn-primary']); ?></div>
			</div>
		</div>
	</div>
	<? ActiveForm::end() ?>

</div>
<?
/*

$rType = 'event';
$rId = seg3;

// Event
$q = $db->query('SELECT * FROM at_events WHERE id=%i LIMIT 1', $rId);
$theEvent = $q->countReturnedRows() > 0 ? $q->fetchRow() : show_error(404, 'Event not found: event-'.$rId);

$q = $db->query('SELECT u.id, u.name FROM persons u, at_event_user eu WHERE u.id=eu.user_id AND eu.event_id=%i ORDER BY u.lname LIMIT 1000', $rId);
$theUsers = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

$pageT = $theEvent['name'];
$pageM = 'events';
$pageB = array(
	anchor('events', 'Events'),
	anchor('events/r/'.$rId, $theEvent['name']),
);

include('_hd_13.php');?>
<div class="col-lg-2"><? include('events__sb.php'); ?></div>
<div class="col-lg-10">
	<table class="table table-condensed table-bordered">
		<thead>
			<tr><th width="30%">Name</th><th>Value</th></tr>
		</thead>
		<tbody>
			<tr><th>Event ID</th><td><?=$theEvent['id']?></td></tr>
			<tr><th>Trạng thái</th><td><?=$eventStatusList[$theEvent['status']]?></td></tr>
			<tr><th>Tên</th><td><?=$theEvent['name']?></td></tr>
			<tr><th>Miêu tả</th><td><?=fHTML::convertNewLines($theEvent['info'])?></td></tr>
			<tr><th>Phân loại</th><td><?=$eventTypeList[$theEvent['stype']]?></td></tr>
			<tr><th>Địa điểm</th><td><?=$theEvent['venue']?></td></tr>
			<tr><th>Thời gian nghỉ</th><td><?=date('d/m/Y H:i', strtotime($theEvent['from_dt']))?> - <?=date('d/m/Y H:i', strtotime($theEvent['until_dt']))?></td></tr>
			<tr><th>Tổng thời gian</th><td>
			<?
				$totalDays = floor($theEvent['mins'] / (60 * 8));
				$totalHours = floor(($theEvent['mins'] - $totalDays * 60 * 8) / 60);
				$totalMins = $theEvent['mins'] - ($totalDays * 60 * 8) - ($totalHours * 60);
				if ($totalDays != 0) echo $totalDays, ' ngày ';
				if ($totalHours != 0) echo $totalHours, ' giờ ';
				if ($totalMins != 0) echo $totalMins, ' phút ';
			?>
			</td></tr>
			<tr><th>Người liên quan</th><td><?
					$cnt = 0;
					foreach ($theUsers as $tu) {					
						$cnt ++;
						if ($cnt > 1) echo '<br />';
						echo $cnt, ' - ', anchor('events?user='.$tu['id'], $tu['name']);
					}
				?></td></tr>
		</tbody>
	</table>
</div>
*/ ?>