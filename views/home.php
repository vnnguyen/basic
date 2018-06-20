<? 
use yii\helpers\Html;
include('/var/www/__apps/my.amicatravel.com/views/fdb.php');
/*
Lanh dao : xem moi thu
Huan, PhAnh, Doan Ha, Ngoc : xem moi thu
Nguoi khac : xem rieng minh
*/
// My inbox
//$q = $db->query('SELECT id, `from`, sender, dt, attachments, subject, recipient FROM at_inbox WHERE recipient_id=%i ORDER BY dt LIMIT 10', myID);
//$theMsgs = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
// $q = $db->query('SELECT COUNT(*) FROM at_inbox WHERE recipient_id=%i', myID);
// $inboxCount = $q->fetchScalar();


// Ms Ngoc can see a list of new tours
if (myID == 1 || myID == 118) {
	$q = $db->query('select *, (select name from persons where id=se limit 1) as se_name from at_tours where status=%s AND op!=1 order by at_tours.name', 'draft');
	$thisNewTours = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : null;
}

if (myID <= 4 || myID == 118 || myID == 695 || myID == 4432) {
	//$q = $db->query('SELECT COUNT(*) FROM at_messages');
	//$pg = new hxPagination($q->fetchScalar(), '?page=', fRequest::get('page', 'integer', 1), 10, 1);
	$q = $db->query('SELECT id, uo, co, ub, from_id, m_to, rid, rtype, title, via, priority, n_id, file_count,
		(SELECT name FROM persons u WHERE u.id=from_id) as from_name,
		(SELECT image FROM persons u WHERE u.id=from_id) as from_image,
		(SELECT name FROM persons u WHERE u.id=m_to) as m_to_name
		FROM at_messages
		ORDER BY co DESC LIMIT 0, 20');
	$theNotes = $q->fetchAllRows();
} else {
	$q = $db->query('SELECT COUNT(*) FROM at_messages WHERE from_id=%i OR m_to=%i OR ub=%i', myID, myID, myID);
	$pg = new hxPagination($q->fetchScalar(), '?page=', fRequest::get('page', 'integer', 1), 10, 1);
	$q = $db->query('SELECT id, uo, co, ub, from_id, m_to, rid, rtype, title, via, priority, n.n_id, file_count,
		(SELECT name FROM persons u WHERE u.id=from_id) as from_name,
		(SELECT image FROM persons u WHERE u.id=from_id) as from_image,
		(SELECT name FROM persons u WHERE u.id=m_to) as m_to_name
		FROM at_messages n, at_message_to nt WHERE n.id=nt.message_id AND nt.user_id=%i
		ORDER BY co DESC LIMIT 0, 20', myID);
	$theNotes = $q->fetchAllRows();
}


for ($i = 0; $i < count($theNotes); $i ++) {
	$relType = $theNotes[$i]['rtype'];
	$relId = $theNotes[$i]['rid'];

	// Khoong hop le
	if ($relType == 'case') {$relURL = 'cases/r/'.$relId; $relTypeName = 'Hồ sơ'; $q = $db->query('SELECT name FROM at_cases WHERE id=%i LIMIT 1', $relId);}
	if ($relType == 'tour') {$relURL = 'tours/r/'.$relId; $relTypeName = 'Tour'; $q = $db->query('SELECT CONCAT(code, %s, name) FROM at_tours WHERE id=%i LIMIT 1', ' - ', $relId);}
	if ($relType == 'user') {$relURL = 'users/r/'.$relId; $relTypeName = 'Danh bạ cá nhân'; $q = $db->query('SELECT name FROM persons WHERE id=%i LIMIT 1', $relId);}
	if ($relType == 'venue') {$relURL = 'venues/r/'.$relId; $relTypeName = 'Điểm cung cấp dịch vụ'; $q = $db->query('select name from venues where id=%i LIMIT 1', $relId);}
	if ($relType == 'company') {$relURL = 'companies/r/'.$relId; $relTypeName = 'Công ty'; $q = $db->query('select name from at_companies where id=%i LIMIT 1', $relId);}
	if ($q->countReturnedRows() > 0) {
		$theNotes[$i]['rname'] = $q->fetchScalar();
		$theNotes[$i]['rurl'] = $relURL;
	}
}

$this->title  = 'Dashboard';
$this->params['small'] = myName;

?>
<div class="col-md-8">
  <? if ((myID == 1  || myID == 118) && !empty($thisNewTours)) { ?>
  <table class="table table-condensed table-bordered">
	<tr><th colspan="4" style="background-color:#ffc"><span class="label label-important">Tour mới</span> Click tên tour để xác nhận</th></tr>
  <? foreach ($thisNewTours as $nt) { ?>
  <tr>
		<td><?=anchor('users/r/'.$nt['se'], $nt['se_name'], 'class="m_from"')?></td>
		<td> <a href="<?=DIR?>tours/accept/<?=$nt['id']?>"><?=$nt['name']?></a> Click để xác nhận</td>
		<td><?=anchor('tours/r/'.$nt['id'], 'Xem tour')?></td>
		<td class="muted"><?=date('d-m-Y H:i', strtotime($nt['uo']))?></td>
  </tr>
  <? } // end foreach ?>
	</table>
  <? } // end if ?>
	
	<? if (!empty($theMsgs)) { ?>
	<p><strong><a href="/my/inbox">My inbox</a></strong> - Xoá hoặc nhập vào hồ sơ để rút ngắn danh sách này!</p>
	<table class="table table-condensed table-bordered">
		<thead>
		<tr>
			<th width="30%">From</th>
			<th width="15%">Date</th>
			<th>Subject</th>
		</tr>
		</thead>
		<tbody>
		<? foreach ($theMsgs as $m) { ?>
		<tr>
			<td title="<?=htmlspecialchars($m['recipient'])?> / <?=htmlspecialchars($m['sender'])?>"><?=htmlspecialchars($m['from'])?></td>
			<td><?=date('Y-m-d H:i', strtotime($m['dt']))?></td>
			<td><?=$m['attachments'] > 0 ? '<i class="icon-file"></i> ' : ''?><?=anchor('my/inbox/r/'.$m['id'], htmlspecialchars($m['subject']))?></td>
		</tr>
		<? } ?>
		</tbody>
	</table>
	<? } ?>
	<h4><i class="fa fa-file"></i> Latest news feed</h4>
	<div class="clearfix">
		<? foreach ($theNotes as $tn) { ?>
		<div style="border-bottom:1px solid #ececec; padding:4px 0;">
			<div class="show-on-hover">
				<span class="shown-on-hover pull-right" style="line-height:1; width:45px; background-color:#fff;">
					<a title="Reply" class="muted td-n" href="<?=DIR?>n/r/<?=$tn['id']?>"><i class="icon-comment"></i></a>
					<? if (myID == $tn['ub'] || myID == 1) { ?>
					<a title="Edit" class="muted td-n" href="<?=DIR?>n/u/<?=$tn['id']?>"><i class="icon-edit"></i></a>
					<a title="Delete" class="muted td-n" href="<?=DIR?>n/d/<?=$tn['id']?>"><i class="icon-remove"></i></a>
					<? } ?>
				</span>
				<?= Html::a(Html::img('/timthumb.php?w=100&h=100&zc=1&src='.$tn['from_image'], ['title'=>$tn['from_name'], 'style'=>'width:20px; height:20px;']), 'users/r/'.$tn['from_id'])?>
				<?
			if ($tn['via'] == 'email') echo '<i class="icon-envelope text-info"></i>';
			if ($tn['via'] == 'form') echo '<i class="icon-signin text-info"></i>';
			if ($tn['via'] == 'ev') echo '<i class="icon-user text-info"></i>';
				?>
				<?=anchor('users/r/'.$tn['from_id'], $tn['from_name'] == '' ? '(Unknown name)' : $tn['from_name'], 'class="n-from-name"')?>:
				<? if ($tn['priority'] == 'B2') { ?><span class="label label-warning">Important</span><? } ?>
				<? if ($tn['priority'] == 'C3') { ?><span class="label label-important">Urgent</span><? } ?>
				<?=anchor('n/r/'.$tn['id'], $tn['title'] == '' ? '(Message)' : $tn['title'])?>
				<? if (isset($tn['rname'])) { ?># <?=anchor($tn['rurl'], $tn['rname'], 'style="color:#396" class="rel"')?><? } ?>
				<span class="muted"><?=$tn['uo'] != $tn['co'] ? ' sửa ' : ''?> <?=date('d/m/Y H:i', strtotime($tn['uo']))?></span>
			</div>
		</div>
		<? } ?>
	</div>
	<h4><i class="fa fa-group"></i> Currently online</h4>
	<div class="clearfix">
		<?
		// Online users
		$q = $db->query('SELECT s.user_id, s.created_at, s.ip_address, u.name FROM at_logins s, persons u WHERE u.id=s.user_id AND s.created_at+90000>NOW() GROUP BY user_id ORDER BY created_at DESC LIMIT 100');
		$onlineUsers = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
		foreach ($onlineUsers as $ou) { ?>
			<?=anchor('users/r/'.$ou['user_id'], Html::img($ou['user_id'], ['title'=>$ou['name']]), 'class="pull-left" style="margin:0 5px 5px 0;" title="'.$ou['name'].'"')?>
				<? } ?>
	</div>
</div>
<div class="col-md-4">
<?php
// The tasks
$q = $db->query('SELECT t.*, u.name AS ub_name FROM at_tasks t, persons u, at_task_user tu WHERE t.status=%s AND tu.completed_dt=0 AND u.id=t.ub AND tu.task_id=t.id AND tu.user_id=%i ORDER BY t.due_dt,1,16, t.is_priority LIMIT 15', 'on', myID);
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
?>
	<table class="table table-condensed">
		<thead>
			<tr><th>
				<?=Html::a('View all', 'tasks', ['class'=>'pull-right small']) ?>
				<i class="fa fa-tasks"></i> My tasks
				</th></tr>
		</thead>
		<tbody>
			<tr>
				<td><? if (empty($theTasks)) { echo 'No tasks found'; } else { ?>
				<div class="task-list">
					<?
					$thisYear = date('Y');
					$today = date('Y-m-d');
					foreach ($theTasks as $t) {
					?>
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
					<? } ?>
					</div>
				<? }?>
				</td>
			</tr>
		</tbody>
	</table>
	
	<table class="table table-condensed">
		<thead><tr><th>
			<? //=anchor('me/starred', __('common', 'View all'), 'class="pull-right fw-n td-n"')?>
			<i class="icon-star"></i> Starred items
			</th></tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<ul class="unstyled">
		<?
		$q = $db->query('SELECT rtype, rid, name FROM at_stars WHERE stype=%s AND ub=%i ORDER BY uo DESC LIMIT 6', 's', myID);
		$theStarredItems = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
		foreach ($theStarredItems as $it) {
			echo '<li>';
			if ($it['rtype'] == 'case') echo '<i class="muted icon-briefcase"></i> ';
			if ($it['rtype'] == 'tour') echo '<i class="muted icon-truck"></i> ';
			echo anchor($it['rtype'].'s/r/'.$it['rid'], $it['name']);
			echo '</li>';
		}
		?>
					</ul>
				</td>
			</tr>		
		</tbody>
	</table>

	<table class="table table-condensed">
		<thead><tr><th>
			<? //=anchor('me/viewed', __('common', 'View all'), 'class="pull-right fw-n td-n"')?>
			<i class="icon-eye-open"></i> Recently viewed items
			</th></tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<ul class="unstyled">
		<?
		$q = $db->query('SELECT rtype, rid, name FROM at_stars WHERE stype=%s AND ub=%i ORDER BY uo DESC LIMIT 6', 'v', myID);
		$theStarredItems = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
		foreach ($theStarredItems as $it) {
			echo '<li>';
			if ($it['rtype'] == 'case') echo '<i class="muted icon-briefcase"></i> ';
			if ($it['rtype'] == 'tour') echo '<i class="muted icon-truck"></i> ';
			echo anchor($it['rtype'].'s/r/'.$it['rid'], $it['name']);
			echo '</li>';
		}
		?>
					</ul>
				</td>
			</tr>		
		</tbody>
	</table>
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