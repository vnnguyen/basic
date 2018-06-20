<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_kase_inc.php');

$this->title = 'New case';
if ($theInquiry) {
	$this->title = 'New case from web inquiry';
} elseif ($theMail) {
	$this->title = 'New case from email message';
}


?>
<div class="col-md-8">
	<? $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theCase, 'name') ?></div>
		<div class="col-md-2"><?= $form->field($theCase, 'language')->dropdownList(['en'=>'English', 'fr'=>'Francais', 'vi'=>'Tiếng Việt'], ['prompt'=>'- Select -']) ?></div>
		<div class="col-md-2"><?= $form->field($theCase, 'is_b2b')->dropdownList(['yes'=>'Yes', 'no'=>'No'], ['prompt'=>'- Select -']) ?></div>
		<div class="col-md-2"><?= $form->field($theCase, 'is_priority')->dropdownList(['yes'=>'Yes', 'no'=>'No'], ['prompt'=>'- Select -']) ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theCase, 'owner_id')->dropdownList(ArrayHelper::map($ownerList, 'id', 'name'), ['prompt'=>'- Select -']) ?></div>
		<div class="col-md-6"><?= $form->field($theCase, 'cofr')->dropdownList(ArrayHelper::map($cofrList, 'id', 'name'), ['prompt'=>'No sellers in France']) ?></div>
	</div>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theCase, 'how_found') ?></div>
		<div class="col-md-6"><?= $form->field($theCase, 'company_id')->dropdownList(ArrayHelper::map($companyList, 'id', 'name')) ?></div>
	</div>
	
	<?= $form->field($theCase, 'info')->textArea(['rows'=>3]) ?>
	<div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>

<?
/*

// Markets
$q = $db->query('SELECT id, name FROM at_markets WHERE status="on" ORDER BY name');
$caseMarketList = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

// List of sellers
$q = $db->query('select u.id, u.name, u.fname, u.lname, u.email FROM persons u, at_user_role ug WHERE u.is_member="yes" AND ug.user_id=u.id AND ug.role_id=%i ORDER BY lname LIMIT 100', $hxRoles['banhang']['id']);
$sellerList = $q->fetchAllRows();

$fv = new hxFormValidation();
$fv->setRules('name', 'Name of case', 'trim|required|htmlspecialchars|max_length[128]');
$fv->setRules('market_id', 'Market ID', 'trim|required|htmlspecialchars|is_natural_no_zero');
$fv->setRules('owner', 'Owner', 'trim|required|is_natural_no_zero');
$fv->setRules('cofr', '@France', 'trim|required|is_natural');
$fv->setRules('how_found', 'How found us', 'trim|htmlspecialchars');
$fv->setRules('ref', 'Referral user Id', 'trim|is_natural');
$fv->setRules('how_contacted', 'How contacted us', 'trim|htmlspecialchars');
$fv->setRules('web_referral', 'Web referral', 'trim|htmlspecialchars');
$fv->setRules('web_keyword', 'Web keyword', 'trim|htmlspecialchars');
$fv->setRules('info', 'Description', 'trim|htmlspecialchars|max_length[1024]');

if (fRequest::isPost()) {
	$isUnique = true;
	if ($_POST['name'] != '') {
		$q = $db->query('SELECT id, name FROM at_cases WHERE name=%s LIMIT 1', $_POST['name']);
		if ($q->countReturnedRows() > 0) {
			$isUnique = false;
		}
	}
  if ($isUnique && $fv->run()) {
    $q = $db->query('INSERT INTO at_cases (created_at, created_by, updated_at, updated_by, owner_id, opened, status, is_priority, owner, cofr, ao, name, market_id, company_id, how_found, how_contacted, web_referral, web_keyword, info, ref) 
			VALUES (%s,%i,%s,%i,%i,%s,%s,%s,%i,%i,%s,%s,%i,%i,%s,%s,%s,%s,%s,%i)',
      NOW,
      myID,
      NOW,
      myID,
      $_POST['owner'],
			substr(NOW, 0, 10),
			$_POST['status'],
			$_POST['is_priority'],
			$_POST['owner'],
			$_POST['cofr'],
			substr(NOW, 0, 10),
      $_POST['name'],
			$_POST['market_id'],
			$_POST['agent_id'],
			$_POST['how_found'],
			$_POST['how_contacted'],
			$_POST['web_referral'],
			$_POST['web_keyword'],
      $_POST['info'],
			$_POST['how_found'] == 'word' && $_POST['ref'] != 0 ? $_POST['ref'] : 0
		);
		$rId = $q->getAutoIncrementedValue();
		
		// Case search
		$db->query('INSERT INTO at_search (rtype, rid, search, found) VALUES ("case", %i, %s, %s)', $rId, $_POST['name'].' '.fURL::makeFriendly($_POST['name'], ''), $_POST['name']);
		
		// Case ref
		if ($_POST['how_found'] == 'word' && $_POST['ref'] != 0) {
			$db->query('INSERT INTO at_referrals (created_at, created_by, updated_at, updated_by, status, user_id, case_id) VALUES (%s,%i,%s,%i,%s,%i,%i)',
				NOW, myID, NOW, myID, 'draft', $_POST['ref'], $rId
			);
		}
		
    // Email people
    if ($_POST['owner'] != 0) {
      $seEmail = myEmail;
      foreach ($sellerList as $se) {
        if ($se['id'] == $_POST['owner']) {
          $seEmail = $se['email'];
					$seName = $se['name'];
          break;
        }
      }
			$theCaseLink = 'http://my.amicatravel.com/cases/r/'.$rId;
			
			/* Mailgun *//*
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, 'api:key-41qs3pbnff7i2k42jmsh9v6ch059jf76');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v2/amicatravel.com/messages');
			curl_setopt($ch, CURLOPT_POSTFIELDS, array(
				'h:Reply-To' => 'noreply@amicatravel.com',
				'from' => myName.' <notifications@amicatravel.com>',
				'to' => $seName.' <'.$seEmail.'>',
				'subject' => '[ims] Giao hồ sơ bán hàng (# '.$_POST['name'].')',
				'text' => 'To view case: '.$theCaseLink,
				'html' => '<p>Hãy xem chi tiết trên IMS</p><p>&mdash;<br /><a href="'.$theCaseLink.'">View case</a> on IMS</p>',
				)
			);
			$result = curl_exec($ch);
			curl_close($ch);
    }
    // Email people
    if ($_POST['cofr'] == 13) {
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, 'api:key-41qs3pbnff7i2k42jmsh9v6ch059jf76');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v2/amicatravel.com/messages');
			curl_setopt($ch, CURLOPT_POSTFIELDS, array(
				'h:Reply-To' => 'noreply@amicatravel.com',
				'from' => myName.' <notifications@amicatravel.com>',
				'to' => 'Hoa Bearez <bearez.hoa@amicatravel.com>',
				'subject' => '[ims] Giao hồ sơ bán hàng (# '.$_POST['name'].')',
				'text' => 'To view case: '.$theCaseLink,
				'html' => '<p>Hãy xem chi tiết trên IMS</p><p>&mdash;<br /><a href="'.$theCaseLink.'">View case</a> on IMS</p>',
				)
			);
			$result = curl_exec($ch);
			curl_close($ch);		
    }

    redirect('cases/r/'.$rId);
    exit;
  }
} else {
	$_POST['status'] = 'on';
	$_POST['is_priority'] = 'no';
	$_POST['owner'] = myID;
	$_POST['cofr'] = 0;
  $_POST['name'] = '';
	$_POST['market_id'] = 2;
	$_POST['daily_id'] = 0;
	$_POST['agent_id'] = 0;
	$_POST['how_found'] = '';
	$_POST['ref'] = 0;
	$_POST['how_contacted'] = '';
	$_POST['web_referral'] = '';
	$_POST['web_keyword'] = '';
	$_POST['info'] = '';
}

$pageM = 'cases';
$pageB = array(
	anchor('cases', 'Cases'),
	anchor('cases/c', 'Create'),
	);
$pageT = 'New case';

include('__hd.php'); ?>
<div class="span8">
	<? if (fRequest::isPost() && !$isUnique) { ?><div class="alert alert-error">A case with the same name <strong><?=$_POST['name']?></strong> already exists. Please select a different name.</div><? } ?>
	<?=$fv->getErrorMessage('<div class="alert alert-error">', '</div>')?>
	<form method="post" action="">
	<div class="row-fluid">
		<div class="span6">Market<br />
			<select class="span12" name="market_id">
				<option value="">- Please select -</option>
				<? foreach ($caseMarketList as $item) { ?>
				<option value="<?=$item['id']?>" <?=$_POST['market_id'] == $item['id'] ? 'selected="selected"' : ''?>><?=$item['name']?></option>
				<? } ?>
			</select>
		</div>
		<div class="span6">Status<br />
			<select class="span12" name="status">
				<? foreach ($caseStatusList as $k=>$v) { ?>
				<option value="<?=$k?>" <?=$_POST['status'] == $k ? 'selected="selected"' : ''?>><?=$v?></option>
				<? } ?>
			</select>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span8">Case name<br /><input type="text" placeholder="Max 64 characters" class="span12" name="name" value="<?=$_POST['name']?>" /></div>
		<div class="span4">
			Is priority?<br />
			<select class="span12" name="is_priority">
				<option value="no"></option>
				<option value="yes" <?=$_POST['is_priority'] == 'yes' ? 'selected="selected"' : ''?>>&#x2605; Priotiy Case</option>
			</select>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			Amica<br />
			<select class="span12" name="owner">
				<option value="0" <?=$_POST['owner'] == 0 ? 'selected="selected"' : ''?>>Không có người phụ trách</option>
				<? foreach ($sellerList as $u) { ?>
				<option value="<?=$u['id']?>" <?=$_POST['owner'] == $u['id'] ? 'selected="selected"' : ''?>><?=$u['name']?> (<?=$u['email']?>)</option>
				<? } ?>
			</select>
		</div>
		<div class="span6">
			&nbsp;<br />
			<select class="span12" name="cofr">
				<option value="9999">Keep current value</option>
				<option value="0" <?=$_POST['cofr'] == 0 ? 'selected="selected"' : ''?>>Không có đại diện tại Pháp</option>
				<option value="13" <?=$_POST['cofr'] == 13 ? 'selected="selected"' : ''?>>Hoa Bearez (bearez.hoa@amicatravel.com)</option>
			</select>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			Trung gian<br />
			<select class="span12" name="daily_id">
				<option value="0">- Chọn đại lý -</option>
				<option value="x" <?=$_POST['id'] == 'x' ? 'selected="selected"' : ''?>>iTravel Laos</option>
			</select>
		</div>
		<div class="span6">
			&nbsp;<br />
			<select class="span12" name="agent_id">
				<option value="0">- Chọn hãng du lịch -</option>
				<? foreach ($allCompanies as $c) { ?>
				<option value="<?=$c['id']?>" <?=$_POST['company_id'] == $c['id'] ? 'selected="selected"' : ''?>><?=$c['name']?></option>
				<? } ?>
			</select>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span4">
			Liên hệ qua<br />
			<select class="span12" name="how_contacted">
				<? foreach ($caseHowContactedUs as $k=>$v) { ?>
				<option value="<?=$k?>" <?=$_POST['how_contacted'] == $k ? 'selected="selected"' : ''?>><?=$v == '' ? '- Liên hệ với Amica qua -' : $v?></option>
				<? } ?>
			</select>
		</div>
		<div class="span4">
			&nbsp;<br />
			<select id="how_contacted_web" class="span12" name="web_referral">
				<? foreach ($caseWebReferralList as $k=>$v) { ?>
				<option value="<?=$k?>" <?=$_POST['web_referral'] == $k ? 'selected="selected"' : ''?>><?=strpos($k, '/') !== false ? '&nbsp; ' : ''?><?=$v?></option>
				<? } ?>
			</select>
		</div>
		<div class="span4">
			&nbsp;<br />
			<input id="how_contacted_web_keyword" type="text" class="span12" name="web_keyword" value="<?=$_POST['web_keyword']?>" placeholder="Search keyword" />
		</div>
	</div>
	<div class="row-fluid">
		<div class="span4">
			Biết Amica qua<br />
			<select class="span12" name="how_found">
				<? foreach ($caseHowFoundUs as $k=>$v) { ?>
				<option value="<?=$k?>" <?=$_POST['how_found'] == $k ? 'selected="selected"' : ''?>><?=$v == '' ? '- Biết Amica qua -' : $v?></option>
				<? } ?>
			</select>
		</div>
		<div class="span4">
			<span id="how_found_word">
				ID người giới thiệu<br />
				<input type="text" class="span12" name="ref" value="<?=$_POST['ref']?>" />
			</span>
		</div>
	</div>
	<p>Description<br /><textarea id="message" class="span12 h-50" name="info"><?=$_POST['info']?></textarea></p>
	<p><button class="btn btn-primary" type="submit">Submit form</button></p>
	</form>
</div>
<script>
$(function(){
	$('#how_found_word').hide();
	$('select[name="how_found"]').change(function(){
		var val = $(this).val();
		if (val == 'word') {
			$('#how_found_word').show(0);
		} else {
			$('#how_found_word').hide(0);
		}
	});
	$('#how_contacted_web, #how_contacted_web_keyword').hide();
	$('select[name="how_contacted"]').change(function(){
		var val = $(this).val();
		if (val == 'web') {
			$('#how_contacted_web, #how_contacted_web_keyword').show(0);
		} else {
			$('#how_contacted_web, #how_contacted_web_keyword').hide(0);
		}
	});
});
</script>
<? include('__ft.php');
*/