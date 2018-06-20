<?
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use app\helpers\DateTimeHelper;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
$userGenderList = [ 
		'male' => 'Male',
		'female' => 'Female' 
];
include('_mails_inc.php');
include ('_person_js.php');
Yii::$app->params['body_class'] = 'bg-white';
Yii::$app->params['page_small_title'] = Yii::$app->formatter->asRelativeTime($theMail['created_at']);

$postData = @unserialize($theMail['data']);
if (!$postData) {
	$postData = [];
}

$date = \DateTime::createFromFormat('D, d M Y H:i:s O', substr($theMail['sent_dt_text'], 0, 31));

$theMail['from'] = str_replace(['"', '\''], ['', ''], $theMail['from']);
$theMail['to'] = str_replace(['"', '\''], ['', ''], $theMail['to']);
$theMail['cc'] = str_replace(['"', '\''], ['', ''], $theMail['cc']);
$theMail['bcc'] = str_replace(['"', '\''], ['', ''], $theMail['bcc']);
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
	.name_user { color: #166DBA; cursor: pointer;}
	.name_user:hover{ color: #000;}
	.edit_user { color: red; cursor: pointer; }
</style>
<div class="col-md-6">
	<table class="table table-condensed table-bordered mb-15">
		<tr><td>From</td><td><?= Html::encode($theMail['from']) ?></td></tr>
		<tr><td>To</td><td><?= Html::encode($theMail['to']) ?></td></tr>
		<? if ($theMail['cc'] != '') { ?>
		<tr><td>Cc</td><td><?= Html::encode($theMail['cc']) ?></td></tr>
		<? } ?>
		<? if ($theMail['bcc'] != '' && $theMail['bcc'] != 'ims@amicatravel.com') { ?>
		<tr><td>Bcc</td><td><?= Html::encode($theMail['bcc']) ?></td></tr>
		<? } ?>
		<? if ($theMail['attachment_count'] > 0) { $theMailFiles = unserialize($theMail['files']); if (!$theMailFiles) {$theMailFiles = [];} ?>
		<tr><td>Files (<?= $theMail['attachment_count'] ?>)</td>
			<td>
			<? foreach ($theMailFiles as $file) { ?>
			<div>+ <?= Html::a($file['name'], '@web/mails/f/'.$theMail['id'].'?name='.urlencode($file['name'])) ?> <span class="text-muted"><?= Yii::$app->formatter->asShortSize($file['size'], 0) ?></div>
			<? } ?>
			</td>
		</tr>
		<? } ?>
	</table>

	<!-- Nav tabs -->
	<ul class="nav nav-tabs mb-15">
		<li class="active"><a href="#stripped" data-toggle="tab">Body</a></li>
		<li><a href="#full" data-toggle="tab">Body (full)</a></li>
		<? if (Yii::$app->user->id == 1) { ?>
		<li><a href="#raw" data-toggle="tab">Raw</a></li>
		<li><a href="#edit" data-toggle="tab">Edit</a></li>
		<? } ?>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<div class="tab-pane active" id="stripped"><?= $theMail['body'] ?></div>
		<div class="tab-pane" id="full">
			<iframe src="<?= DIR ?>mails/bh/<?= $theMail['id'] ?>" seamless width="100%" height="600" frameborder="0"></iframe>
		</div>
		<? if (MY_ID == 1) { ?>
		<div class="tab-pane" id="raw">
			<div style="width:100%; max-height:600px; overflow-x:scroll; overflow-y:scroll;">
			<table class="table table-condensed table-bordered">
				<? foreach ($postData as $k=>$v) { ?>
				<tr>
					<td class="text-nowrap"><?= $k ?></td>
					<td>
						<?= Html::encode($v) ?>
					</td>
				</tr>
				<? } ?>
			</table>
			</div>
		</div>
		<div class="tab-pane" id="edit">
		LATER
		</div>
		<? } ?>
	</div>
</div>
<div class="col-md-6">
	<? if (isset($theMail['case'])) { ?>
		<p><strong>THIS MESSAGE IS LINKED TO A CASE</strong></p>
	<? } else { ?>
	<p><strong>THIS MESSAGE IS NOT LINKED TO ANY CASE</strong>
	</p>
	<? } ?>
	<div>
		<?
		$form = ActiveForm::begin();
		?>
			<ul class="list-unstyled">
			
			<?php
			$eList = [];
			if (count($emails) > 0) {
				foreach ($emails as $k => $email) {
					$nameUsers = [];
					if (!empty($theUsers)) {
						foreach ($theUsers as $user) {
							if ($user['email'] == $email) {
								$nameUsers[$user['id']] = $user['fname'].' '.$user['lname'];
							}
						}
						$div_wrap = [];
						foreach ($nameUsers as $user_id => $nameUser) {
							$div_wrap[] = '<span class="name_user" data-id="'.$user_id.'">'.$nameUser.'</span>';
						}
					}
					if (count($nameUsers) > 0) {
						$eList[implode(',', array_keys($nameUsers))] = Html::tag('span', $email, ['class' => 'name_email']).': '.Html::tag('span', implode(', ', $div_wrap), ['class' => 'name_users']) . Html::tag('span', ' ( edit ) ', ['class' => 'edit_user']);
					} else//Html::tag('p', Html::encode($user->name), ['class' => 'username'])
						$eList['unknown-'.$k] = Html::tag('span', $email, ['class' => 'name_email']).': '.Html::tag('span', '', ['class' => 'name_users']) .' '.Html::tag('span', '( edit )', ['class' => 'edit_user']);
				}
				echo Html::checkboxList('user_id', [], $eList, ['encode' => false]);
			}
			?>
			</ul>
			<p><small>THE CASE</small></p>
			<ul class="list-unstyled">
		<?
			$cList = [];
			if (isset($theMail['case'])) {
				$cList['remove'] = '<i class="fa fa-trash"></i> Remove out of this case '.Html::a($theMail['case']['name'], '@web/cases/r/'.$theMail['case']['id']);
			} else {
				if (!empty($theCases)) {
					foreach ($theCases as $case) {
						$cList[$case['id']] = '<i class="fa fa-briefcase"></i> '.Html::a($case['name'], '@web/cases/r/'.$case['id'], ['rel'=>'external']). ' ('.substr($case['created_at'], 0, 7).', '.$case['status'].') '.$case['owner']['name'];
					}
				}
			}
			$cList['ext'] = '<span id="case_ext">Push to an other case with name below</span>
					<div class="form-group field-casefrominquiryform-case_name hide_select hidden"> <select id="casefrominquiryform-case_name" class="form-control" name="CaseFromInquiryForm[case_name]"> </select> <div class="help-block"></div> </div>
				';
			$cList[0] = 'Create new CASE';
			echo Html::activeRadioList($theForm, 'case_id', $cList, ['encode' => false]);
		?>
			</ul>
			<div id="add_case_form">
					<?//= Html::errorSummary($theCase, ['class' => 'errors']) ?>
					<?php if (count($theCase->errors) > 0): var_dump($theCase->errors);die;?>
						<?php foreach ($theCase->errors as $error): ?>
							<div class="alert alert-danger no-border">
								<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
								<span class="text-semibold">Error!</span> <?=$error[0]?>
						    </div>
						<?php endforeach ?>
					<?php endif ?>
				</div>
			<div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?></div>
		<?
			ActiveForm::end();
		?>
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
						<?=Html::hiddenInput( 'current_email', '', $options = [] )?>
					</div>
					<div class="modal-body">
						<ul class="list-unstyled">
							<?
							$uList = [];
						    $uList['ext'] = '<span id="person_ext">Push to an other person with name below</span>
								<div class="form-group field-casefrominquiryform-user_name hide_select hidden"> <select id="casefrominquiryform-user_name" class="form-control" name="CaseFromInquiryForm[user_name]"> </select> <div class="help-block"></div> </div>
							';
							$uList[0] = 'Create new person';
							echo Html::activeRadioList($theForm, 'user_id', $uList, ['encode' => false]);
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
	<hr>

	<p><strong>RELATED PEOPLE</strong></p>
	<?
	// var_dump($theUsers);
	// var_dump($emails);
	// die();
	foreach ($theUsers as $user) { ?>
		<? foreach ($emails as $i=>$email) { ?>
			<? if (strtolower($user['email']) == strtolower($email)) { unset($emails[$i]); ?>
	<div class="clearfix" style="margin-bottom:5px;">
		<img src="//secure.gravatar.com/avatar/<?= md5($user['email']) ?>.jpg?s=40&d=wavatar" alt="Avatar" class="img-circle" style="float:left; margin-right:10px;">
		<i class="text-muted fa fa-<?= $user['gender'] ?>"></i>
		<?= Html::a($user['name'], '@web/users/r/'.$user['id']) ?><br>
		<span class="text-muted"><?= $user['email'] ?></span>
	</div>
			<? } ?>
		<? } ?>
	<? } ?>
	<? foreach ($emails as $email) { ?>
	<div class="clearfix" style="margin-bottom:5px;">
		<img src="//secure.gravatar.com/avatar/<?= md5($email) ?>.jpg?s=40&d=wavatar" alt="Avatar" style="float:left; margin-right:10px;">
		<span style="color:red">Unknown email</span> (<?= Html::a('+ New user', '@web/users/c?email='.$email, ['rel'=>'external']) ?>)<br>
		<span class="text-muted"><?= $email ?></span>
	</div>
	<? } ?>
	<hr>

	<p><strong>RELATED PEOPLE'S CASES</strong></p>
	<ul class="list-unstyled">
		<? if (empty($theCases)) { ?>
		<p>No cases found.</p>
		<? } else { ?>
		<? foreach ($theCases as $case) { ?>
		<li>
			<i class="fa fa-briefcase"></i>
			<?= Html::a($case['name'], '@web/cases/r/'.$case['id']) ?>
			<? if ($case['status'] == 'closed') { ?><i class="text-muted fa fa-lock"></i><? } ?>
			<? if ($case['status'] == 'onhold') { ?><i class="text-warning fa fa-clock-o"></i><? } ?>
			<? if ($case['deal_status'] == 'won') { ?><i class="text-success fa fa-dollar"></i><? } ?>
			<? if ($case['deal_status'] == 'lost') { ?><i class="text-danger fa fa-dollar"></i><? } ?>
			<?= $case['owner']['name'] ?>, <?= substr($case['created_at'], 0, 7) ?>
			<?= $theMail['case_id'] != $case['id'] ? Html::a('+', '@web/mails/r/'.$theMail['id'].'?action=add-to-case&case-id='.$case['id']) : '<span class="label label-default">LINKED</span>' ?>
		</li>
		<? } // foreach ?>
		<? } // empty ?>
	</ul>

	<hr>
	<p><strong>MORE DETAILS</strong></p>
	<p><strong>Sent:</strong> <?= DateTimeHelper::convert($theMail['sent_dt'], 'Y-m-d H:i O', 'UTC', Yii::$app->user->identity->timezone) ?> (<?= $theMail['sent_dt_text'] ?>)</p>
	<p><strong>Received:</strong> <?= DateTimeHelper::convert($theMail['created_at'], 'Y-m-d H:i O', 'UTC', Yii::$app->user->identity->timezone) ?></p>
	<p><strong>Message Id:</strong> <?= Html::encode($theMail['message_id']) ?></p>
	<p><strong>In Reply To:</strong> <?= Html::a(Html::encode($theMail['in_reply_to']), '@web/mails/search?key=message_id&value='.urlencode($theMail['in_reply_to'])) ?></p>

</div>
<?php
$js =<<<TXT
	var FORM = $('#form_modal');
	var OPTION_SELECT = '';
	var C_EMAIL = '';
	var C_id = '';
	$(document).on('click', '#save_user', function() {
		var option_chk = false;
		var option_chk_val = '';
		$('#casefrominquiryform-user_id label').each(function(index, item){
			if ($(item).find('input[name="CaseFromInquiryForm[user_id]"]').is(':checked')) {
				option_chk = true;
				option_chk_val = $(item).find('input[name="CaseFromInquiryForm[user_id]"]').val();

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
		    	beforeSubmit:  validate,
		    	success: function(result){
					if (result.error != undefined) {
						console.log(result.error);
						return false;
					}
					if (result.theUser) {
						var theUser = result.theUser;
						var html = '<span class="name_user" data-id="'+ theUser.id +'">'+theUser.fname+' '+theUser.lname+'</span>';
						var sec = '';
						if ($(OPTION_SELECT).val().indexOf(theUser.id) === -1) {
							if ($(OPTION_SELECT).val().charAt(0) == 'u') {
								$(OPTION_SELECT).val(theUser.id);
							} else {
								$(OPTION_SELECT).val($(OPTION_SELECT).val()+ ',' +theUser.id);
							}
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
	$("#userModal").on('show.bs.modal', function () {
		$(this).find('input[name="current_email"]').val(C_EMAIL);
	});
	$("#userModal").on('hide.bs.modal', function () {
		$('#casefrominquiryform-user_id label').each(function(index, item){
			if ($(this).find('.name_users').length > 0) {
				$(this).remove();
			}
		});
	        exportForm();
	});
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
		var current_label = $(this).closest('label'),
			cur_id = $(current_label).find('input').val();
			C_EMAIL = $(this).closest('label').find('.name_email').text();
			OPTION_SELECT = $(this).closest('label').find('input');
		if (cur_id.charAt(0) == 'u') {
			$('#userModal').modal('show');
		} else {
			FORM.ajaxSubmit({
		    	// resetForm: true,
		    	dataType: 'json',
		    	data: {user_id: cur_id},
		    	// beforeSubmit:  validate,
		    	success: function(result){
					if (result.error != undefined) {
						console.log(result.error);
						return false;
					}
					if (result.theUser) {
						var theUser = result.theUser;
						var html = '<span class="name_user" data-id="'+ theUser.id +'">'+theUser.fname+' '+theUser.lname+'</span>';
						var label_current = '<label><input name="CaseFromInquiryForm[user_id]" value="'+ theUser.id+'" type="radio"> <span class="name_users">'+ html +'</span></label>';
						$('#casefrominquiryform-user_id').prepend(label_current);
					}
					if (result.theUsers) {
						var theUsers = result.theUsers;
						var user_ids = [];
						var name_users = [];
						jQuery.each(theUsers, function(index, item){
							user_ids.push(item.id);
							name_users.push('<span class="name_user" data-id="'+ item.id +'">'+item.fname+' '+item.lname+'</span>');

						});
						var label_current = '<label><input name="CaseFromInquiryForm[user_id]" value="'+ user_ids.toString() +'" type="radio"> <span class="name_users">'+ name_users.toString() +'</span></label>';
						$('#casefrominquiryform-user_id').prepend(label_current);
					}
					$('#userModal').modal('show');
		    	}
		    });
		}
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
				$('#casefrominquiryform-case_id').find('.hide_select').removeClass('hidden');
				$(this).find('#casefrominquiryform-case_name').focus();
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
		console.log(case_id);
		var labels = $('#casefrominquiryform-case_id').find('label');
		jQuery.each(labels, function(i, item){
			if ($(item).find('#case_ext').length > 0) {
				$(item).find('input[type="radio"]').val(case_id);
			}
		});
	});
	function importForm(){
		$('#add_user_form').append($('#add-step2'));
		$('#add-step2').find('[name="email[]"]').val(C_EMAIL);

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
$this->registerJs($js);
?>