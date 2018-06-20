<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\helpers\DateTimeHelper;
$userGenderList = [ 
		'male' => 'Male',
		'female' => 'Female' 
];
include('_inquiries_inc.php');
include ('_person_js.php');
$this->title = 'Inquiry from '.$theInquiry['email'];

$this->params['breadcrumb'] = [
['Sales', '@web/spaces/sales'],
['Inquiries', '@web/inquiries'],
['View', '@web/inquiries/r/'.$theInquiry['id']],
];

$inquiry = $theInquiry;
$this->registerCssFile ( '/css/intlTelInput.css' );


// How they contacted us
$caseHowContactedList = [
'web'=>'Web',
'web/adwords'=>'Adwords',
'web/adwords/google'=>'Google Adwords',
'web/adwords/bing'=>'Bing Ads',
'web/adwords/other'=>'Other',
'web/search'=>'Search',
'web/search/google'=>'Google search',
'web/search/bing'=>'Bing search',
'web/search/yahoo'=>'Yahoo! search',
'web/search/other'=>'Other',
'web/link'=>'Referral',
'web/link/360'=>'Blog 360',
'web/link/facebook'=>'Facebook',
'web/link/other'=>'Other',
'web/ad'=>'Ad online',
'web/ad/facebook'=>'Facebook',
'web/ad/voyageforum'=>'VoyageForum',
'web/ad/routard'=>'Routard',
'web/ad/sitevietnam'=>'Site-Vietnam',
'web/ad/other'=>'Other',
'web/email'=>'Mailing',
'web/direct'=>'Direct access',

'nweb'=>'Non-web',
'nweb/phone'=>'Phone',
'nweb/email'=>'Email',
'nweb/email/tripconn'=>'TripConnexion',
'nweb/email/other'=>'Other',
'nweb/walk-in'=>'Walk-in',
        'nweb/other'=>'Other', // web pages like Fb, fax, snail mail

    'agent'=>'Via a tour company', // OLD?
    ];

    $caseHowContactedListFormatted = [];
    foreach ($caseHowContactedList as $k=>$v) {
    	$cnt = count(explode('/', $k));
    	$v = str_repeat(' --- ', $cnt - 1). $v;
    	$caseHowContactedListFormatted[$k] = $v;
    }

    $caseHowFoundList = [
    'returning'=>'Returning',
    'returning/customer'=>'Returning customer',
    'returning/contact'=>'Returning contact (not a customer)',
    'new'=>'New',
    'new/nref'=>'Not referred',
    'new/nref/web'=>'Web',
    'new/nref/print'=>'Book/Print',
    'new/nref/event'=>'Event/Seminar',
            'new/nref/other'=>'Other', // travel agent, by chance
            'new/ref'=>'Referred',
            'new/ref/customer'=>'Referred by one of Amica\'s customer',
            'new/ref/amica'=>'Referred by one of Amica\'s staff',
            'new/ref/org'=>'Referred by an organization or one of its members', // Ca nhan, to chuc
            'new/ref/other'=>'Referred from other source',
            ];


$caseHowFoundListFormatted = [];
foreach ($caseHowFoundList as $k=>$v) {
	$cnt = count(explode('/', $k));
	$v = str_repeat(' --- ', $cnt - 1). $v;
	$caseHowFoundListFormatted[$k] = $v;
}
?>
<style type="text/css">
	#casefrominquiryform-case_id label, #casefrominquiryform-user_id label {font-weight:normal!important; width: 100%;}
	.select2 { width: 100% !important; }
	.form-wizard-title .error { display:block; color: red; background: #f3f3f3; margin-top: 10px; text-align: center}
	.intl-tel-input .country-list {z-index: 10;}
	.name_user { color: #166DBA; cursor: pointer;}
	.name_user:hover{ color: #000;}
	.edit_user { color: red; cursor: pointer; }
</style>
<div class="col-md-6">
	<ul class="note-list">
		<li class="note-list-item">
			<? $userAvatar = '//secure.gravatar.com/avatar/'.md5($theInquiry['email']).'?s=100&d=wavatar'; ?>
			<div class="note-avatar"><?= Html::a(Html::img($userAvatar, ['class'=>'note-author-avatar media-object hidden-xs']), '#', ['class'=>'pull-left']) ?></div>
			<div class="note-content">
				<h5 class="note-heading">
					<i class="fa fa-desktop"></i>
					<?= Html::a('Web inquiry from '.$theInquiry['site']['name'].' / '.$theInquiry['form_name'].' / '.$theInquiry['email'], '@web/inquiries/r/'.$theInquiry['id'], ['style'=>'font-weight:bold;']) ?>
				</h5>
				<div class="mb-1em">
					<span class="text-muted"><?= DateTimeHelper::convert($theInquiry['created_at'], 'j/n/Y H:i O', 'UTC', Yii::$app->user->identity->timezone) ?></span>
				</div>
				<div class="note-body">
					<div class="inquiry-body">
						<?
						if ($inquiry['data2'] != '') {
							foreach ($allCountries as $country) {
								$find1 = '{{ country : '.$country['code'].' }}';
								$replace1 = '{{ country : '.$country['name'].' }}';
								$find2 = '{{ countryCallingCode : '.$country['code'].' }}';
								$replace2 = '{{ countryCallingCode : '.$country['name'].' +'.$country['dial_code'].' }}';
								if (strpos($inquiry['data2'], $find1) !== false) {
									$inquiry['data2'] = str_replace($find1, $replace1, $inquiry['data2']);
								}
								if (strpos($inquiry['data2'], $find2) !== false) {
									$inquiry['data2'] = str_replace($find2, $replace2, $inquiry['data2']);
								}
							}

							$ok = '';
							$fields = [];
							$parts = explode(' }}', $inquiry['data2']);
							foreach ($parts as $part) {
								$qa = explode('{{ ', $part);
								if (isset($qa[1])) {
									$a = explode(' : ', $qa[1]);
									if (isset($a[1])) {
										$fields[trim($a[0])] = trim($a[1]);
										$ok .= $qa[0];
										$ok .= '<span style="color:brown">'.substr($qa[1], strlen($a[0]) + 2).'</span>';
									}
									else {
										$ok .= $part.' }}';
									}
								} else {
									$ok .= $part;
								}
							}
							echo nl2br($ok);
						} else {
							if (in_array($inquiry['form_name'], [
								'en_contact_130920', 'en_quote_130920',
								'fr_devis_130920', 'fr_devis_140905', 'fr_booking_130920', 'fr_booking_140905',
								'fr_contact_130920', 'fr_contactce_130920', 'fr_rdv_130920',
								'val_contact_130920', 'val_rdv_130920', 'val_devis_130920', 'val_devis_140905', 'val_booking_130920', 'val_booking_140905',
								'vac_contact_130920', 'vac_rdv_130920', 'vac_devis_130920', 'vac_devis_140905', 'vac_booking_130920', 'vac_booking_140905',
								])) {
								echo $this->render('//inquiry/_render_'.$inquiry['form_name'], [
									'theInquiry'=>$inquiry,
									'inquiryData'=>$inquiryData,
									]);
						} else {
							if ($inquiry['form_name'] == 'fr_devis_m_140918' && $inquiry['data2'] != '') {
			// BEGIN PARSE INQUIRY
								$ok = '';
								$fields = [];
								$parts = explode(' }}', $inquiry['data2']);
								foreach ($parts as $part) {
									$qa = explode('{{ ', $part);
									if (isset($qa[1])) {
										$a = explode(' : ', $qa[1]);
										if (isset($a[1])) {
											$fields[trim($a[0])] = trim($a[1]);
											$ok .= $qa[0];
											$ok .= '<span style="color:brown">'.Html::encode($a[1]).'</span>';
										}
										else {
											$ok .= Html::encode($part).' }}';
										}
									} else {
										$ok .= Html::encode($part);
									}
								}
								echo nl2br($ok);
			// END PARSE INQUIRY
							} else {
								?>
								<div><strong>CUSTOMER</strong></div>
								<div>
									Name: <span class="text-warning"><?= $inquiry['name'] ?></span><br />
									Email: <span  class="text-warning"><?= $inquiry['email'] ?></span><br />
								</div>
								<div><strong>INQUIRY DATA</strong></div>
								<div>
									<?
									echo '<dl class="dl-horizontal">';
									foreach ($inquiryData as $k=>$v) {
										if (!empty($v)) {
											echo '<dt>', $k, '</dt>';
											echo '<dd class="text-warning">', (is_array($v) ? implode(',', $v) : nl2br(Html::encode($v))), '</dd>';
										}
									}
									echo '</dl>';
									?>
								</div>
								<?
		} // if form name
	}
} // if data2
?>
</div>
<ul class="list-unstyled" style="padding:10px; margin-top:10px; background-color:#f6f6f0">
	<li><strong>IP address</strong>
		<a rel="external" href="http://whatismyipaddress.com/ip/<?= $theInquiry['ip'] ?>"><?= $theInquiry['ip'] ?></a>
	</li>
	<li>
		<strong>HTTP Referrer</strong>
		<i class="fa fa-info-circle" title="<?= Html::encode($theInquiry['ref']) ?>"></i>
		<?
		$mRef = parse_url($theInquiry['ref']);
		if (false !== $mRef) {
			if (!isset($mRef['query'])) $mRef['query'] = '';
			$mQuery = parse_str(str_replace('&amp;', '&', $mRef['query']), $mq);
			if (!isset($mRef['path'])) $mRef['path'] = '';
			if ($mRef['path'] == '/aclk') {
				echo '<span style="color:Red">Google Adwords</span>';
			} elseif (isset($mRef['host']) && $mRef['host'] == 'www.googleadservices.com') {
				echo '<span style="color:Red">Google Adsense</span>';
			} else {
				if (!isset($mRef['host'])) $mRef['host'] = '(No data)';
				echo $mRef['host'];
			}
			$mqx = '';
			if (is_array($mq)) {
				foreach ($mq as $k=>$v) {
					if ($k == 'ohost' || $k == 'adurl' || $k == 'url' || $k == 'u' || $k == 'oq' || $k == 'rdata' || $k == 'q' || $k == 'p')
						$mqx .= '<br /><span class="label label-default" style="background-color:#ccc;">'.strtoupper($k).'</span> '.$v;
				}
			}
			echo $mqx;
		}
		?>
	</li>
	<li>
		<strong>UserAgent string</strong>
		<?= $theInquiry['ua'] ?>
	</li>
</ul>
</div>
</div>
</li>
</ul>
</div>
<div class="col-md-6">
	<form method="POST">
		<div class="col-md-12">
			<? if (isset($theInquiry['case'])) { ?>
				<p><strong>THIS INQUIRY IS LINKED TO A CASE</strong> <?= Html::a('Change', '@web/inquiries/u/'.$theInquiry['id']) ?></p>
				<? } else { ?>
				<p><strong>THIS INQUIRY IS NOT LINKED TO ANY CASE.<br>YOU CAN LINK IT TO A NEW/EXISTING CASE:</strong></p>
				<?} // if inquiry case?>
				<p><small>THE INQUIRY CONTACT</small></p>
				<ul class="list-unstyled">
					<?
					$iuList = [];
					if (!empty($theInquiry['email'])) {
						$nameUsers = [];
						if (count($inquiryUsers) > 0) {
							foreach ($inquiryUsers as $user) {
								$nameUsers[$user['id']] = $user['fname'].' '.$user['lname'];
							}
							$div_wrap = [];
							foreach ($nameUsers as $user_id => $nameUser) {
								$div_wrap[] = '<span class="name_user" data-id="'.$user_id.'">'.$nameUser.'</span>';
							}
						}
						if (count($nameUsers) > 0) {
							$iuList[implode(',', array_keys($nameUsers))] = $theInquiry['email'].': '.Html::tag('span', implode(', ', $div_wrap), ['class' => 'name_users']) . Html::tag('span', ' ( edit ) ', ['class' => 'edit_user']);
						} else//Html::tag('p', Html::encode($user->name), ['class' => 'username'])
							$iuList[] = $theInquiry['email'].': '.Html::tag('span', '', ['class' => 'name_users']) . Html::tag('span', ' ( edit ) ', ['class' => 'edit_user']);
					}
					echo Html::checkboxList('user_id', [isset($nameUsers) ? implode(',', array_keys($nameUsers)): null], $iuList, ['encode' => false]);
					?>
				</ul>
				<p><small>THE CASE</small></p>
				<?php if (count($theForm->errors) > 0): ?>
					<?php foreach ($theForm->errors as $error): ?>
						<div class="alert alert-danger no-border">
							<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
							<span class="text-semibold">Error!</span> <?=$error[0]?>
					    </div>
					<?php endforeach ?>
				<?php endif ?>

				<ul class="list-unstyled">
					<?
					$iucList = [];
					if (isset($theInquiry['case'])) {
						$iucList['remove'] = '<i class="fa fa-trash"></i> Remove out of this case '.Html::a($theInquiry['case']['name'], '@web/cases/r/'.$theInquiry['case']['id']);
					} else {
						if (!empty($inquiryUserCases)) {
							foreach ($inquiryUserCases as $case) {
								$iucList[$case['id']] = '<i class="fa fa-briefcase"></i> '.Html::a($case['name'], '@web/cases/r/'.$case['id'], ['rel'=>'external']). ' ('.substr($case['created_at'], 0, 7).', '.$case['status'].') '.$case['owner']['name'];
							}
						}
					}
					$iucList['ext'] = '<span id="case_ext">Push to an other case with name below</span>
							<div class="form-group field-casefrominquiryform-case_name hide_select hidden"> <select id="casefrominquiryform-case_name" class="form-control" name="CaseFromInquiryForm[case_name]"> </select> <div class="help-block"></div> </div>
						';
					$iucList[0] = 'Create new CASE';
					echo Html::activeRadioList($theForm, 'case_id', $iucList, ['encode' => false]);
					?>
				</ul>

				<div id="add_case_form">
					<?//= Html::errorSummary($theCase, ['class' => 'errors']) ?>
					<?php if (count($theCase->errors) > 0): ?>
						<?php foreach ($theCase->errors as $error): ?>
							<div class="alert alert-danger no-border">
								<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
								<span class="text-semibold">Error!</span> <?=$error[0]?>
						    </div>
						<?php endforeach ?>
					<?php endif ?>
				</div>
				
		</div>
		<div class="col-md-12">
			<div class="text-right">
				<?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
			</div>
		</div>
	</form>
</div>
<!-- Modal -->
<div class="modal fade" id="userModal" role="dialog">
	<form method="POST" id="form_modal">
		<div class="modal-dialog modal-lg">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">USER INFO</h4>
					<?=Html::hiddenInput( 'current_email', $theUserForm['email'], $options = [] )?>
				</div>
				<div class="modal-body">
					<ul class="list-unstyled">
						<?
						$iuList = [];
						$nameUsers = [];
						if (!empty($inquiryUsers)) {
							foreach ($inquiryUsers as $user) {
								$nameUsers[$user['id']] = $user['fname'].' '.$user['lname'];
							}
							$div_wrap = [];
							foreach ($nameUsers as $user_id => $nameUser) {
								$div_wrap[] = '<span class="name_user" data-id="'.$user_id.'">'.$nameUser.'</span>';
							}
						}
						if (count($nameUsers) > 0) {
							if (count($nameUsers) > 0) {
								$iuList[implode(',', array_keys($nameUsers))] = Html::tag('span', implode(', ', $div_wrap), ['class' => 'name_users']);
							}
						}
						$iuList['ext'] = '<span id="person_ext">Push to an other person with name below</span>
							<div class="form-group hide_select hidden"> <select id="casefrominquiryform-user_name" name="CaseFromInquiryForm[user_name]"> </select></div>
						';
						$iuList[0] = 'Create new person';
						echo Html::activeRadioList($theForm, 'user_id', $iuList, ['encode' => false]);

						?>
					</ul>


					<div id="add_user_form"></div>
				</div>
				<div class="modal-footer">
					<span class="btn btn-primary" id="save_user">Save</span>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</form>
</div>
<!-- end modal -->
<div class="extra-steps hidden">
	<fieldset class="step" id="add-step2">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group <?= isset($theUserForm['errors']['fname']) ? 'has-error' : ''?>">
					<?= Html::activeLabel($theUserForm, 'fname', ['class' => 'control-label']) ?>
					<?= Html::activeInput('text', $theUserForm, 'fname', ['class' => 'form-control']); ?>
					<?= Html::error($theUserForm, 'fname', ['class' => 'help-block']) ?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group <?= isset($theUserForm['errors']['lname']) ? 'has-error' : ''?>">
					<?= Html::activeLabel($theUserForm, 'lname', ['class' => 'control-label']) ?>
					<?=Html::activeInput('text', $theUserForm, 'lname', ['class' => 'form-control']); ?>
					<?= Html::error($theUserForm, 'lname', ['class' => 'help-block']) ?>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group <?= isset($theUserForm['errors']['name']) ? 'has-error' : ''?>">
					<?= Html::activeLabel($theUserForm, 'name', ['class' => 'control-label']) ?>
					<?=Html::activeInput('text', $theUserForm, 'name', ['class' => 'form-control']); ?>
					<?= Html::error($theUserForm, 'name', ['class' => 'help-block']) ?>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<?= Html::activeLabel($theUserForm, 'gender', ['class' => 'control-label']) ?>
					<?= Html::activeDropDownList($theUserForm, 'gender', $userGenderList, ['class' => 'form-control']) ?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-2">
				<div class="form-group">
					<?= Html::activeLabel($theUserForm, 'bday', ['class' => 'control-label']) ?>
					<?=Html::activeInput('text', $theUserForm, 'bday', ['class' => 'form-control']); ?>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<?= Html::activeLabel($theUserForm, 'bmonth', ['class' => 'control-label']) ?>
					<?=Html::activeInput('text', $theUserForm, 'bmonth', ['class' => 'form-control']); ?>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<?= Html::activeLabel($theUserForm, 'byear', ['class' => 'control-label']) ?>
					<?=Html::activeInput('text', $theUserForm, 'byear', ['class' => 'form-control']); ?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<?= Html::activeLabel($theUserForm, 'country_code', ['class' => 'control-label']) ?>
					<?= Html::activeDropDownList($theUserForm, 'country_code', ArrayHelper::map($allCountries, 'code', 'name_en'), ['class' => 'form-control']) ?>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 ">
				<div class="form-group">
					<?= Html::activeLabel($theUserForm, 'marital_status', ['class' => 'control-label']) ?>
					<?= Html::activeDropDownList($theUserForm, 'marital_status', $dt_relation, ['class' => 'form-control', 'prompt'=>'- Select -']) ?>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<?= Html::activeLabel($theUserForm, 'relation', ['class' => 'control-label']) ?>
			</div>
			<div class="col-md-3 col-sm-10 nopadding">
				<select class="form-control" name="relationship_family[]" id="relationship_family" style="width: 100%;">
					<option value="">--Select--</option>

					<?php foreach ($dt_family as $value){?>
					<option value="<?php echo $value;?>">  <?php echo $value;?> </option>
					<?php }?>

				</select>
			</div>

			<div class="col-md-6 col-sm-10 nopadding">
				<div class="form-group">
					<select class="person_family[]" id="person_family_0" style="width: 100%;" name="person_family[]" >
						<option>Search</option>
					</select>
				</div>
			</div>

			<div class="col-md-3 col-sm-10 nopadding">
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-btn">
							<button class="btn btn-success" type="button" onclick="family_fields();">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
							</button>
						</div>
					</div>
				</div>
			</div>
			<div id="family_fields" class="row"></div>
		</div>

		<p><strong>CONTACT INFORMATION</strong></p>

		<div id="a1">
			<div class="row" style="margin-bottom: 10px">
				<div class="col-md-12">
					<?= Html::activeLabel($theUserForm, 'email', ['class' => 'control-label']) ?>
				</div>
				<div class="col-md-3 col-sm-10 nopadding">
					<select name="id_email[]" class="form-control" id="id_email_0" style="width: 100%">
						<option value="">-- Select --</option>
						<?php foreach ($dt_email as $value){?>
						<option value="<?php echo $value;?>" >  <?php echo $value;?> </option>
						<?php }?>
					</select>
				</div>
				<div class="col-sm-6 nopadding">
					<div class="form-group">
						<input type="email" class="form-control" id="email_0" name="email[]" value="<?= isset($theInquiry['email']) ? $theInquiry['email'] : ''?>" placeholder="example@....">
					</div>
				</div>
				<div class="col-sm-3 nopadding">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-btn">
								<button class="btn btn-success" type="button" onclick="email_fields();">
									<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
								</button>
							</div>
						</div>
					</div>
				</div>
				<div id="email_fields" class="row"></div>
			</div>
		</div><!-- end #a1 -->
		<div class="row" style="margin-bottom: 10px">
			<div class="col-md-12">
				<?= Html::activeLabel($theUserForm, 'tel', ['class' => 'control-label']) ?>
			</div>
			<div class="col-md-3 col-sm-10 nopadding">
				<select name="phone_format[]" class="select form-control" id="phone_format_0" style="width: 100%">
					<option value="">-- Select --</option>
					<?php foreach ($dt_phone as $value){?>
					<option value="<?php echo $value;?>">  <?php echo $value;?> </option>
					<?php }?>
				</select>
			</div>

			<div class="col-md-6 col-sm-10 nopadding">
				<div class="form-group">
					<input class="form-control" type="tel" name="phone[]" value="<?= isset($theUserForm->tel) ? $theUserForm->tel : ''?>" id="phone_number_0" style="width: 100%" />
				</div>
			</div>

			<div class="col-md-3 col-sm-10 nopadding">
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-btn">
							<button class="btn btn-success" type="button" onclick="phone_fields();">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
							</button>
						</div>
					</div>
				</div>
			</div>
			<input class="hide" name="dial_code_input[]" id="dial_code_input_<?php echo $count_p;?>">
			<div id="phone_fields" class="row"></div>
		</div> <!-- end row tel -->
		<div class="row" style="margin-bottom: 10px">
			<div class="col-md-12">
				<label class="control-label col-lg-2">Website : </label>
			</div>
			<div class="col-md-3 col-sm-10 nopadding">
				<select name="id_website[]" class="form-control" id="id_website_0" style="width: 100%">
					<option value="">-- Select --</option>
					<?php foreach ($dt_web as $value){?>
					<option value="<?php echo $value;?>">  <?php echo $value;?> </option>
					<?php }?>
				</select>
			</div>

			<div class="col-md-6 col-sm-10 nopadding">
				<div class="form-group">
					<input type="text" class="form-control" name="website[]"
					placeholder="http://... or https://..." id="website" />
				</div>
			</div>

			<div class="col-md-3 col-sm-10 nopadding">
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-btn">
							<button class="btn btn-success" type="button" onclick="website_fields();">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
							</button>
						</div>
					</div>
				</div>
			</div>

			<div id="website_fields" class="row"></div>
		</div> <!-- end row website -->
		<div class="row" style="margin-bottom: 10px">
			<div class="row col-md-12">
				<label class="control-label col-lg-2">Dia Chi : </label>
			</div>
			<div class="col-sm-3 nopadding">
				<div class="form-group">
					<input type="text" class="form-control" id="address"
					name="address[]" value="" placeholder="Address">
				</div>
			</div>
			<div class="col-sm-3 nopadding">
				<div class="form-group">
					<input type="text" class="form-control" id="city" name="city[]"
					value="" placeholder="City">
				</div>
			</div>
			<div class="col-sm-3 nopadding">
				<div class="form-group">
					<input type="text" class="form-control" id="national"
					name="nation[]" value="" placeholder="Nation">
				</div>
			</div>
			<div class="col-sm-3 nopadding">
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-btn">
							<button class="btn btn-success" type="button" onclick="address_fields();">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
							</button>
						</div>
					</div>
				</div>
			</div>
			<div id="address_fields" class="row"></div>
		</div> <!-- end address -->
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<?= Html::activeLabel($theUserForm, 'pob', ['class' => 'control-label']) ?>
					<?= Html::activeInput('text', $theUserForm, 'pob', ['class' => 'form-control']); ?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<?= Html::activeLabel($theUserForm, 'profession', ['class' => 'control-label']) ?>
					<?= Html::activeInput('text', $theUserForm, 'profession', ['class' => 'form-control']); ?>
				</div>
			</div>
		</div>
		<div class="form-group">
			<?= Html::activeLabel($theUserForm, 'tags', ['class' => 'control-label']) ?>
			<?= Html::activeTextarea($theUserForm, 'note', ['class' => 'form-control', 'rows'=>10]); ?>
		</div>
		<div class="form-group">
			<?= Html::activeLabel($theUserForm, 'tags', ['class' => 'control-label']) ?>
			<?= Html::activeInput('text', $theUserForm, 'tags', ['class' => 'form-control']); ?>
		</div>
	</fieldset>
	<fieldset class="step" id="add-step3">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group <?= isset($theCase['errors']['name']) ? 'has-error' : ''?>">
						<?= Html::activeLabel($theCase, 'name', ['class' => 'control-label']) ?>
						<?=Html::activeInput('text', $theCase, 'name', ['class' => 'form-control']); ?>
						<?= Html::error($theCase, 'name', ['class' => 'help-block']) ?>

					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?= Html::activeLabel($theCase, 'language', ['class' => 'control-label']) ?>
						<?= Html::activeDropDownList($theCase, 'language', ['en'=>'English', 'fr'=>'Francais', 'vi'=>'Tiếng Việt'], ['class' => 'form-control', 'prompt'=> Yii::t('app', '- Select -')]) ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?= Html::activeLabel($theCase, 'is_priority', ['class' => 'control-label']) ?>
						<?= Html::activeDropDownList($theCase, 'is_priority', ['yes'=>'Yes', 'no'=>'No'], ['class' => 'form-control', 'prompt'=> Yii::t('app', '- Select -')]) ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<?= Html::activeLabel($theCase, 'campaign_id', ['class' => 'control-label']) ?>
						<?= Html::activeDropDownList($theCase, 'campaign_id', ArrayHelper::map($campaignList, 'id', 'name'), ['class' => 'form-control', 'prompt'=> Yii::t('app', '- Select -')]) ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<?= Html::activeLabel($theCase, 'owner_id', ['class' => 'control-label']) ?>
						<?= Html::activeDropDownList($theCase, 'owner_id', ArrayHelper::map($ownerList, 'id', 'name'), ['class' => 'form-control', 'prompt'=> Yii::t('app', '- Select -')]) ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<?= Html::activeLabel($theCase, 'cofr', ['class' => 'control-label']) ?>
						<?= Html::activeDropDownList($theCase, 'cofr', ArrayHelper::map($cofrList, 'id', 'name'), ['class' => 'form-control', 'prompt'=> Yii::t('app', 'No sellers in France')]) ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<?= Html::label('How customer contacted us', 'how_contacted', ['class' => 'control-label']) ?>
						<?= Html::activeDropDownList($theCase, 'how_contacted', $caseHowContactedListFormatted, ['class' => 'form-control', 'prompt'=> Yii::t('app', '- Select -')]) ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<?= Html::activeLabel($theCase, 'web_keyword', ['class' => 'control-label']) ?>
						<?= Html::activeInput('text', $theCase, 'web_keyword', ['class' => 'form-control', 'placeholder'=>	Yii::t('app', 'Web keyword')]) ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<?= Html::activeLabel($theCase, 'company_id', ['class' => 'control-label']) ?>
						<?= Html::activeDropDownList($theCase, 'company_id', ArrayHelper::map($companyList, 'id', 'name'), ['class' => 'form-control', 'prompt'=> Yii::t('app', '- Select -')]) ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<?= Html::activeLabel($theCase, 'how_found', ['class' => 'control-label']) ?>
						<?= Html::activeDropDownList($theCase, 'how_found', $caseHowFoundListFormatted, ['class' => 'form-control', 'prompt'=> Yii::t('app', '- Select -')]) ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<?= Html::activeLabel($theCase, 'ref', ['class' => 'control-label']) ?>
						<?=Html::activeInput('text', $theCase, 'ref', ['class' => 'form-control']); ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<?= Html::activeLabel($theCase, 'info', ['class' => 'control-label']) ?>
						<?= Html::activeTextarea($theCase, 'info', ['class' => 'form-control', 'rows'=>3]); ?>
					</div>
				</div>
			</div>
		</div>
	</fieldset>
</div>
<?php
$js =<<<TXT
var FORM = $('#form_modal');
var OPTION_SELECT = '';
var F_NAME = '';
$(document).on('click', '#save_user', function() {
	var option_chk = false;
	var option_chk_val = '';
	var text_name = '';
	$('#casefrominquiryform-user_id label').each(function(index, item){
		if ($(item).find('input[name="CaseFromInquiryForm[user_id]"]').is(':checked')) {
			option_chk = true;
			option_chk_val = $(item).find('input[name="CaseFromInquiryForm[user_id]"]').val();
			// text_name

		}
	});
	if (!option_chk) {
		alert('Select option. please!');
		return false;
	} else {
		if (option_chk_val == 'ext') {
			alert('Select name person. please!');
			return false;
		}
		// submit the form
	    FORM.ajaxSubmit({
	    	resetForm: true,
	    	dataType: 'json',
	    	// data: {},
	    	beforeSubmit: validate,
	    	success: function(result){
				if (result.error != undefined) {
					console.log(result.error);
					return false;
				}
				if (result.theUser) {
					var theUser = result.theUser;
					var html = '<span class="name_user" data-id="'+ theUser.id +'">'+theUser.fname+' '+theUser.lname+'</span>';
					if ($('#casefrominquiryform-user_id').find('.name_users').length > 0) {
						var label_current = $('#casefrominquiryform-user_id').find('.name_users').closest('label');
						if ($(label_current).find('input').val().indexOf(theUser.id) === -1) {
							var sepatator = '';
							if ($('#casefrominquiryform-user_id').find('.name_users').text() != '') {
								sepatator = ',';
							}
							$('#casefrominquiryform-user_id').find('.name_users').append(sepatator+' '+html);
							$(label_current).find('input').val($(label_current).find('input').val()+sepatator+theUser.id);
						}
					} else {
						var label_current = '<label><input name="CaseFromInquiryForm[user_id]" value="'+ theUser.id+'" type="radio"> <span class="name_users">'+ html +'</span></label>';
						$('#casefrominquiryform-user_id').prepend(label_current);
					}
					if ($(OPTION_SELECT).val().indexOf(theUser.id) === -1) {
						var sec = '';
						if ($(OPTION_SELECT).val() != '') {
							sec = ',';
						}
						$(OPTION_SELECT).val($(OPTION_SELECT).val()+ sec +theUser.id);
						var content_html = $(OPTION_SELECT).closest('label').find('.name_users').html();
						if (content_html != '' ) {
							content_html += ', ';
						}
						$(OPTION_SELECT).closest('label').find('.name_users').html(content_html+html);
					}
				}
				if (result.theUsers) {
					var theUsers = result.theUsers;
					var user_ids = [];
					var name_users = [];
					jQuery.each(theUsers, function(index, item){
						user_ids.push(item.id);
						name_users.push('<span class="name_user" data-id="'+ item.id +'">'+item.fname+' '+item.lname+'</span>');

					});
					$(OPTION_SELECT).val(user_ids.toString()).attr('checked', true);
					$(OPTION_SELECT).closest('label').find('.name_users').html(name_users.toString());
				}
				$('#userModal').modal('hide');
	    	}
	    });
	}
});
$("#userModal").on('hide.bs.modal', function () {
        exportForm();
});
// $("#userModal").on('show.bs.modal', function () {

//         importForm();
// });
$(document).on('change', '.has-error .form-control', function(){
	if ($(this).val() != '') {
		$(this).closest('.form-group').removeClass('has-error');
	}
});
function validate(formData, jqForm, options) {
	var option_chk_val = 0;
	$('#casefrominquiryform-user_id label').each(function(index, item){
		if ($(item).find('input[name="CaseFromInquiryForm[user_id]"]').is(':checked')) {
			option_chk_val = $(item).find('input[name="CaseFromInquiryForm[user_id]"]').val();
		}
	});
	if (option_chk_val == 0) {
		var form = jqForm[0],
	    	errClass = 'has-error',
	    	requiredMessage = 'This field is required',
	    	err = false,
	    	fname = $(form).find('[name="UsersUuForm[fname]"]'),
	    	lname = $(form).find('[name="UsersUuForm[lname]"]'),
	    	email = $(form).find('[name="email[]"]');
	    if (!$(fname).val()) {
	    	var ELEM = $(fname);
	        err = true;
	        ELEM.closest('.form-group').addClass(errClass);
	        ELEM.closest('.form-group').find('.help-block').text(requiredMessage);
	    }
	    if (!$(lname).val()) {
	        err = true;
	        $(lname).closest('.form-group').addClass(errClass);
	        $(lname).closest('.form-group').find('.help-block').text(requiredMessage);
	    }
	    if (!$(email).val()) {
	        err = true;
	        $(email).closest('.form-group').addClass(errClass);
	    }
	    if (err) {
	    	return false;
	    }
	}
}
$('.edit_user').click(function(){
	OPTION_SELECT = $(this).closest('label').find('input');
	$('#userModal').modal('show');
	return false;
});
$(document).on('click', '#casefrominquiryform-user_id label', function(){
	var input_val = $(this).find('input').val();
	if (input_val == 0) {
		importForm();
		$('#casefrominquiryform-user_id').find('.hide_select').addClass('hidden');
	} else {
		exportForm();
		if (input_val == 'ext' || $(this).find('#person_ext').length > 0) {
			$(this).find('#casefrominquiryform-user_name').focus();
			$('#casefrominquiryform-user_id .hide_select').removeClass('hidden');
		} else {
			$('#casefrominquiryform-user_id .hide_select').addClass('hidden');
		}
	}
});
$('#casefrominquiryform-case_id label').click(function(){
	var input_val = $(this).find('input').val();
	if (input_val == 0) {
		importCaseForm();
		$('#casefrominquiryform-case_id .hide_select').addClass('hidden');
	} else {
		exportCaseForm();
		if (input_val == 'ext') {
			$('#casefrominquiryform-case_id .hide_select').removeClass('hidden');
			$(this).find('#casefrominquiryform-case_id').focus();
		} else {
			$('#casefrominquiryform-case_id .hide_select').addClass('hidden');
		}
	}
});
$('#casefrominquiryform-case_id label').each(function(index, item){
	var input_val = $(this).find('input').val();
	if (input_val == 0 && $(this).find('input').is(':checked')) {
		importCaseForm();
	}
});



$('#casefrominquiryform-user_name').select2({
	placeholder: "Search",
	minimumInputLength: 3,
	ajax: {
		url: "/inquiry/search_user_name",
		dataType: 'json',
		delay: 250,
		data: function (params) {
			return {
				q: params.term,
				page: params.page || 1
			};
		},
		processResults: function (data, params) {
			params.page = params.page || 1;
			return  {
				results: $.map(data.items, function (obj) {
					obj.id = obj.id;
					obj.text = obj.text || obj.name + obj.v;
					return obj;
				}),
				pagination: {
					more: (params.page * 20) < data.total_count
				}
			};
		},
		cache: true
	},
});
$('#casefrominquiryform-case_name').select2({
	placeholder: "Search",
	minimumInputLength: 3,
	ajax: {
		url: "/inquiry/search_case_name",
		dataType: 'json',
		delay: 250,
		data: function (params) {
			return {
				q: params.term,
				page: params.page || 1
			};
		},
		processResults: function (data, params) {
			params.page = params.page || 1;
			return  {
				results: $.map(data.items, function (obj) {
					obj.id = obj.id;
					obj.text = obj.text || obj.name;
					return obj;
				}),
				pagination: {
					more: (params.page * 20) < data.total_count
				}
			};
		},
		cache: true
	},
});
$(document).on('change', '#casefrominquiryform-user_name', function(){
	var user_id = $(this).val();
	var labels = $('#casefrominquiryform-user_id').find('label');
	jQuery.each(labels, function(i, item){
		if ($(item).find('#person_ext').length > 0) {
			$(item).find('input[type="radio"]').val(user_id);
		}
	});
});
$(document).on('change', '#casefrominquiryform-case_name', function(){
	var case_id = $(this).val();
	var labels = $('#casefrominquiryform-case_id').find('label');
	jQuery.each(labels, function(i, item){
		if ($(item).find('#case_ext').length > 0) {
			$(item).find('input[type="radio"]').val(case_id);
		}
	});
});
function importForm(){
	$('#add_user_form').append($('#add-step2'));

}
function exportForm(){
	$('.extra-steps').append($('#add-step2'));
}
function importCaseForm(){
	$('#add_case_form').append($('#add-step3'));
}
function exportCaseForm(){
	$('.extra-steps').append($('#add-step3'));
}
TXT;

$this->registerJsFile('/js/jquery.form.js', ['depends' => 'yii\web\JqueryAsset']);
// $this->registerJsFile('/js/core/libraries/jquery_ui/core.min.js', ['depends' => 'yii\web\JqueryAsset']);
// $this->registerJsFile('/js/jquery.form.wizard.js', ['depends' => 'yii\web\JqueryAsset']);

// $this->registerJsFile('/js/inquiry_form.js', ['depends' => 'yii\web\JqueryAsset']);

$this->registerJs($js);
?>