<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_events_inc.php');

$this->title = 'New event';

?>
<div class="col-md-8">
	<? $form = ActiveForm::begin(); ?>
	<?= $form->field($theEvent, 'name') ?>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theEvent, 'stype')->dropdownList($eventTypeList) ?></div>
		<div class="col-md-6"><?= $form->field($theEvent, 'status')->dropdownList($eventStatusList) ?></div>
	</div>
	<?= $form->field($theEvent, 'info')->textArea(['rows'=>5]) ?>
	<?= $form->field($theEvent, 'users')->dropdownList(ArrayHelper::map($allPeople, 'id', 'name'), ['multiple'=>'multiple']) ?>
	<div class="row">
		<div class="col-md-5"><?= $form->field($theEvent, 'from_dt') ?></div>
		<div class="col-md-5"><?= $form->field($theEvent, 'until_dt') ?></div>
		<div class="col-md-2"><?= $form->field($theEvent, 'mins') ?></div>
	</div>
	<?= $form->field($theEvent, 'venue') ?>
	<div class="text-right"><?= Html::submitButton('Save changes', ['class'=>'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>
<?
$js = <<<TXT
$('#event-users').select2();
TXT;
$this->registerJs($js);
/*
$q = $db->query('SELECT u.id, u.fname, u.lname FROM persons u WHERE u.is_member="yes" ORDER BY u.lname LIMIT 1000');
$theUsers = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

$fv = new hxFormValidation();
$fv->setRules('name', 'Tên sự kiện', 'trim|required|max_length[64]');
$fv->setRules('info', 'Miêu tả thêm', 'trim|max_length[5000]|htmlspecialchars');
$fv->setRules('stype', 'Phân loại', 'trim|required');
$fv->setRules('venue', 'Địa điểm', 'trim|htmlspecialchars');
$fv->setRules('user[]', 'Người liên quan', 'trim|required|is_natural_no_zero');
$fv->setRules('from_date', 'Từ ngày nào', 'trim|required|exact_length[10]');
$fv->setRules('from_time', 'Từ mấy giờ', 'trim|required|exact_length[5]');
$fv->setRules('from_date', 'Đến ngày nào', 'trim|required|exact_length[10]');
$fv->setRules('until_time', 'Đến mấy giờ', 'trim|required|exact_length[5]');
$fv->setRules('days', 'Số ngày', 'trim|required|is_natural');
$fv->setRules('hours', 'Số giờ', 'trim|required|is_natural');
$fv->setRules('mins', 'Số phút', 'trim|required|is_natural');
$fv->setRules('status', 'Trạng thái', 'trim|required');

if (fRequest::isPost()) {
	if ($fv->run()) {
		// Save DB
		$_POST['from_time'] = substr($_POST['from_time'], 0, 2).':'.substr($_POST['from_time'], -2);
		$_POST['until_time'] = substr($_POST['until_time'], 0, 2).':'.substr($_POST['until_time'], -2);
		$fromDT = $_POST['from_date'].' '.$_POST['from_time'].':00';
		$untilDT = $_POST['until_date'].' '.$_POST['until_time'].':00';
		$_POST['mins'] += $_POST['days'] * 8 * 60 + $_POST['hours'] * 60;
		$q = $db->query('INSERT INTO at_events (co, cb, uo, ub, name, info, stype, from_dt, until_dt, mins, status, venue) VALUES (%s, %i, %s, %i, %s, %s, %s, %s, %s, %i, %s, %s)',
			NOW, myID, NOW, myID, $_POST['name'], $_POST['info'], $_POST['stype'], $fromDT, $untilDT, $_POST['mins'], $_POST['status'], $_POST['venue']
		);
		$newEventId = $q->getAutoIncrementedValue();
		// Event User
		foreach ($_POST['user'] as $tu) {
			$db->query('INSERT INTO at_event_user (uo, ub, event_id, user_id) VALUES (%s,%i, %i,%i)',
				NOW, myID, $newEventId, $tu
			);
		}
		// Email users
		$email = new fEmail();
		$email->clearRecipients();
		$email->setFromEmail('notifications@amicatravel.com', myName);
		//$email->addRecipient('khanh.linh@amica-travel.com', 'Linh T.');
		$email->addRecipient('hn.huan@gmail.com');
		$email->setSubject('[ims] Sự kiện: '.$_POST['name']);
		$email->setBody('Gửi từ IP : '.$appUser->ipAddress.'

'.$_POST['name'].'
========================
Thời gian: từ '.$fromDT.' đến '.$untilDT.' ('.$_POST['mins'].' phút)

Cụ thể: '.fHTML::prepare($_POST['info']).'

------------------------
Xem chi tiết: '.SITE_URL.'events/r/'.$newEventId.'
');
    $email->send();
		// Redir
		redirect('events');
		exit;
	}
	if (!isset($_POST['user'])) $_POST['user'] = array();
} else {
	$_POST['name'] = '';
	$_POST['info'] = '';
	$_POST['stype'] = 'nghiphep';
	$_POST['user'] = array(myID);
	$_POST['from_date'] = date('Y-m-d', strtotime('+1 days'));
	$_POST['from_time'] = '08h00';
	$_POST['until_date'] = date('Y-m-d', strtotime('+2 days'));
	$_POST['until_time'] = '17h30';
	$_POST['days'] = 1;
	$_POST['hours'] = 0;
	$_POST['mins'] = 0;
	$_POST['status'] = 'draft';
	$_POST['venue'] = '';
}



include('__hd.php');?>
<div class="span8">
	<?=$fv->getErrorMessage('<div class="alert alert-error">', '</div>', '<strong>Lỗi khi gửi form. Xin hãy kiểm tra lại:</strong>')?>
  <form class="form-horizontal" method="post" action="">
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="name">Tên sự kiện:</label>
				<div class="controls">
					<input type="text" class="span12" name="name" value="<?=$_POST['name']?>" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="about">Miêu tả:</label>
				<div class="controls">
					<textarea class="span12 h-100" name="info"><?=$_POST['info']?></textarea>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="stype">Phân loại</label>
				<div class="controls">
					<select name="stype" class="span4">
						<? foreach ($eventTypeList as $k=>$v) { ?>
						<option value="<?=$k?>" <?=$k == $_POST['stype'] ? 'selected="selected"' : ''?>><?=$v?></option>
						<? } ?>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="stype">Địa điểm</label>
				<div class="controls">
					<input type="text" name="venue" class="span12" value="<?=$_POST['venue']?>" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="stype">Người liên quan</label>
				<div class="controls">
					<select name="user[]" class="span12 select2" multiple="multiple">
						<? foreach ($theUsers as $tu) { ?>
						<option value="<?=$tu['id']?>" <?=in_array($tu['id'], $_POST['user']) ? 'selected="selected"' : ''?>><?=$tu['lname']?>, <?=$tu['fname']?></option>
						<? } ?>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="from_dt">Khoảng thời gian:</label>
				<div class="controls">
					<input type="text" class="input-small datepicker" name="from_date" value="<?=$_POST['from_date']?>" />
					<input type="text" class="input-small" name="from_time" value="<?=$_POST['from_time']?>" />
					đến
					<input type="text" class="input-small datepicker" name="until_date" value="<?=$_POST['until_date']?>" />
					<input type="text" class="input-small" name="until_time" value="<?=$_POST['until_time']?>" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="stype">Tổng thời gian</label>
				<div class="controls">
					<select name="days" class="span3">
						<? for ($cnt = 0; $cnt <= 365; $cnt ++ ) { ?>
						<option value="<?=$cnt?>" <?=$_POST['days'] == $cnt ? 'selected="selected"' : ''?>><?=$cnt?> ngày</option>
						<? } ?>
					</select>
					<select name="hours" class="span3">
						<? for ($cnt = 0; $cnt <= 7; $cnt ++ ) { ?>
						<option value="<?=$cnt?>" <?=$_POST['hours'] == $cnt ? 'selected="selected"' : ''?>><?=$cnt?> giờ</option>
						<? } ?>
					</select>
					<select name="mins" class="span3">
						<? for ($cnt = 0; $cnt <= 59; $cnt ++ ) { ?>
						<option value="<?=$cnt?>" <?=$_POST['mins'] == $cnt ? 'selected="selected"' : ''?>><?=$cnt?> phút</option>
						<? } ?>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="stype">Trạng thái</label>
				<div class="controls">
					<select name="status" class="span4">
						<? foreach ($eventStatusList as $k=>$v) { ?>
						<option value="<?=$k?>" <?=$k == $_POST['status'] ? 'selected="selected"' : ''?>><?=$v?></option>
						<? } ?>
					</select>
				</div>
			</div>
		</fieldset>
		<div class="form-actions">
			<button class="btn btn-primary" type="submit">Thêm sự kiện</button>
		</div>
	</form>
</div>
<link rel="stylesheet" href="<?=DIR?>js/select2/select2.css" />
<script src="<?=DIR?>js/select2/select2.js" type="text/javascript"></script>
<script type="text/javascript"> $(".select2").select2(); </script>

<? include('__ft.php');*/