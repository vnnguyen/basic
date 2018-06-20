<?
use yii\helpers\Html;

include('_kase_inc.php');

$this->title = 'Reopen case: '.$theCase['name'];

$this->params['breadcrumb'][] = ['View', 'cases/r/'.$theCase['id']];
$this->params['breadcrumb'][] = ['Reopen', 'cases/reopen/'.$theCase['id']];

?>
<div class="col-md-8">
	<div class="alert alert-info">
		<strong>Case reopening confirmation</strong><br>
		This case was closed on <?= date('d-m-Y', strtotime($theCase['closed'])) ?><br>
		Note: <?= $theCase['why_closed'] ?> / <?= $theCase['closed_note'] ?><br>
		Owner: <?= $theCase['owner']['name'] ?>
	</div>
	<form method="post" action="" class="form-inline well well-sm">
		Are you sure you want to reopen this case:
		<?= Html::dropdownList('confirm', null, ['yes'=>'Yes, do it now', 'no'=>'No'], ['prompt'=>'Select answer', 'class'=>'form-control']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
	</form>
</div>
<?/*
// Case
$q = $db->query('SELECT * FROM at_cases WHERE id=%i LIMIT 1', seg3);
$theCase = $q->countReturnedRows() > 0 ? $q->fetchRow() : show_error(404);

if (myID > 4 && myID != 4432 && myID != $theCase['owner_id']) show_error(403, 'You cannot change someone else\'s case');
if ($theCase['status'] != 'closed') show_error(403, 'You are not allowed to change this case. It is not [CLOSED]');

if (isset($_POST['confirm']) && $_POST['confirm'] == 'yes') {
  $db->query('UPDATE at_cases SET updated_at=%s, updated_by=%i, status="open", status_date=%s, closed="0000-00-00" WHERE id=%i LIMIT 1',
    NOW, myID, NOW, seg3
	);
	if ($theCase['deal_status'] == 'lost') $db->query('UPDATE at_cases SET deal_status="active", deal_status_date=%s WHERE id=%i LIMIT 1', NOW, seg3);
  redirect('cases/r/'.seg3);
  exit;
}

$pageT = 'Re-open a closed case';
$pageM = 'cases';
$pageB = array(
	anchor('cases', 'Cases'),
	anchor('cases/r/'.$theCase['id'], $theCase['name']),
	anchor(uris, 'Re-open'),
	);
include('__hd.php'); ?>
<div class="span8">
  <form method="post" action="">
  <div class="alert alert-info">This case was closed on <?=$theCase['closed']?> because of the following reason:<br /><?=$theCase['closed_note']?></div>
  <p>Hãy xác nhận mở lại hồ sơ / Are you sure you want to re-open it?<br /><select name="confirm" class="form-control">
    <option value="no" selected="selected">Tôi không muốn thực hiện tiếp / NO, I DO NOT WANT TO CONTINUE</option>
    <option value="yes">Tôi hiểu và muốn thực hiện tiếp / I UNDERSTAND AND I WANT TO REOPEN THIS LEAD</option>
  </select></p>
  <p><button type="submit" class="btn">Re-open this case now</button> or <a href="#back">Cancel</a></div>
  </form>
</div>
<? include('__ft.php');*/