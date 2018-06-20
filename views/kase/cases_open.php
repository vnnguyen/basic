<?
use yii\helpers\Html;
$this->title = 'Open cases';

$this->params['breadcrumb'][] = ['My open cases', 'cases/open'];

?>
<div class="col-md-12">
	<div class="table-responsive">
		<table id="tblList" class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
					<th width="40">ID</th>
					<th>Name</th>
					<th>Assigned</th>
					<th>Proposals / Bookings</th>
					<th>Tasks</th>
					<th></th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theCases as $li) { ?>
				<tr>
					<td class="text-muted text-center"><?= $li['id'] ?></td>
					<td><?= Html::a($li['name'], '@web/cases/r/'.$li['id'], ['rel'=>'external']) ?></td>
					<td><?= $li['ao'] ?></td>
					<td><?= strtoupper($li['deal_status']) ?></td>
					<td>
						<?
						if ($li['tasks']) {
							foreach ($li['tasks'] as $task) {
						?>
						<div>
							<i class="fa fa-square-o"></i>
							<?= str_replace('-'.date('Y'), '', date('d-m-Y', strtotime($task['due_dt']))) ?>
							<?= Html::a($task['description'], '@web/tasks/u/'.$task['id']) ?>
						</div>
						<?
							}
						}
						?>
					</td>
					<td>
						<?
						if ($li['tasks']) {
							foreach ($li['tasks'] as $task) {
								echo $task['due_dt'];
								break;
							}
						}
						?>
					</td>
					<td>
						<?= Html::a('Close', '@web/cases/close/'.$li['id']) ?>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
</div>
<style type="text/css">
	.dt_header {margin-bottom:1em;}
	div#tblList_filter label, div#tblHotels_length label {width:100%;}
	#tblList_filter input {padding:5px 12px; height:34px; line-height:20px; width:100%;}
	#tblList_length select {padding:6px 12px; height:34px; line-height:20px; width:100%;}
	#tblList_info {height:34px; line-height:34px; }
</style>
<?
$js = <<<TXT
	$('#tblList').dataTable({
		"iDisplayLength": 100,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
		"sDom": "<'dt_header row'<'col-lg-2'f><'col-lg-2'l><'col-lg-4'i><'text-right col-lg-4'p>r>t>",
		"sPaginationType": "bootstrap",
		"bStateSave": true,
		"aoColumns": [
			null,
			null,
			null,
			null,
			{"iDataSort": 5},
			{"bVisible": false},
			{"bSortable": false}
		],
		"oLanguage": {
			"sLengthMenu": "_MENU_",
			"sSearch": "_INPUT_",
			"oPaginate": {
				"sPrevious": "",
				"sNext": ""
			},
			"sInfo": "Showing _START_ to _END_ of _TOTAL_",
			"sInfoFiltered": " - filtering from _MAX_"
		}
	});
TXT;
$this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/jquery.dataTables.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/js/datatables/paging-b3.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);


// All deals, filtered
/*
$q = $db->query('SELECT ao, id, name, opened, closed, closed_note, cofr, owner_id,
	(SELECT name FROM persons u WHERE u.id=owner_id LIMIT 1) AS owner_name
  FROM at_cases WHERE (owner_id=%i OR cofr=%i) AND status="open" 
  ORDER BY ao DESC, updated_at DESC LIMIT 1000', myID, myID, myID);
$theCases = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

// Case deals
$caseDeals = array();
if (!empty($theCases)) {
	$caseIdList = array();
	foreach ($theCases as $tc) $caseIdList[] = $tc['id'];
	$q = $db->query('SELECT case_id, stype, status, owner, (SELECT name FROM persons u WHERE u.id=owner LIMIT 1) AS owner_name FROM at_deals WHERE case_id IN ('.implode(',', $caseIdList).') ORDER BY stype DESC');
	$caseDeals = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
}
	
$caseTasks = array();
if (!empty($theCases)) {
	$caseIdList = array();
	foreach ($theCases as $c) $caseIdList[] = $c['id'];
	$q = $db->query('SELECT t.* FROM at_tasks t, at_task_user tu WHERE tu.task_id=t.id AND t.status="on" AND tu.user_id=%i AND rtype="case" ORDER BY due_dt LIMIT 1000', myID);
	if ($q->countReturnedRows() > 0) $caseTasks = $q->fetchAllRows();
}

$pageT = count($theCases).' hồ sơ bán hàng tôi đang xử lý';
$pageM = 'cases';
$pageB = array(anchor('cases', 'Hồ sơ bán hàng'));

include('__hd.php');?>
<div class="row-fluid">
	<div class="span12 widget">
		<div class="widget-header">
			<span class="title">Đây là các hồ sơ bán hàng đang còn mở (kể cả hồ sơ đã bán được tour). Để ngừng theo dõi, bạn hãy đóng hồ sơ lại.</span>
		</div>
		<div class="widget-content table-container">
			<table id="tbl-cases" class="table table-condensed">
				<thead>
					<tr>
						<th width="5%">ID</th>
						<th width="10%">Ngày nhận</th>
						<th width="20%">Tên hồ sơ</th>
						<th width="30%">Sản phẩm đang bán</th>
						<th width="35%">Các công việc liên quan</th>
					</tr>
				</thead>
				<tbody>
				<? foreach ($theCases as $c) { ?>
				<tr>
					<td class="ta-r"><?=$c['id']?></td>
					<td><?=$c['ao']?></td>
					<td>
						<?=anchor('cases/r/'.$c['id'], $c['name'])?>
						<? if (myID != $c['owner_id']) echo ' w. ', anchor('users/r/'.$c['owner'], $c['owner_name'], 'style="color:#c60"'); ?>
						<? if (myID != $c['cofr'] && $c['cofr'] == 13) echo ' w. ', anchor('users/r/13', 'Hoa Bearez', 'style="color:#c60"'); ?>
					</td>
					<td><?
					$cnt = 0;
					foreach ($caseDeals as $cd) {			
						if ($cd['case_id'] == $c['id']) {
							$cnt++;
							if ($cnt > 1) echo ', ';
							echo anchor('cases/proposals/'.$c['id'], $dealTypeList[$cd['stype']]);
							if ($cd['owner'] != myID) echo ' - ', anchor('users/r/'.$cd['owner'], $cd['owner_name']);
							if ($cd['status'] != 'pending') echo ' (<span class="deal-status-', $cd['status'], '">', $cd['status'], '</span>)';
						}
					}
					if ($cnt == 0) echo 'None';
					?></td>
					<td><?
					foreach ($caseTasks as $t) {
						if ($t['rid'] == $c['id']) {
							echo '<div>'.substr($t['due_dt'], 0, 10).' '.anchor('tasks/u/'.$t['id'], $t['description']).'</div>';
						}
					}
					?></td>
				</tr>
				<? } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script src="//ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.js" ></script>
<script src="<?=DIR?>js/datatables/paging.js" ></script>
<script>
$(function(){
	$('#tbl-cases').dataTable({
		"iDisplayLength": 100,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
		"sDom": "<'dt_header row-fluid'<'span6 form-inline'l><'span6 form-inline ta-r'f>r>t<'dt_footer row-fluid'<'span6'i><'span6'p>>",
		"sPaginationType": "bootstrap",
		"bStateSave": true
	});
});
</script>
*/