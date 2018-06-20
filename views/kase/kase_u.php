<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;
use app\helpers\DateTimeHelper;

$userGenderList = [ 
		'male' => 'Male',
		'female' => 'Female' 
];
include('_kase_inc.php');
include ('_person_js.php');

if ($theCase->isNewRecord) {
	$this->title = 'New case';
	$this->params['breadcrumb'][] = ['New', '@web/cases/c'];
	if ($theInquiry) {
		$this->title = 'New case from web inquiry';
	} elseif ($theMail) {
		$this->title = 'New case from email message';
	}
} else {
	$this->title = 'Edit: '.$theCase['name'];
	$this->params['breadcrumb'][] = ['View', '@web/cases/r/'.$theCase['id']];
	$this->params['breadcrumb'][] = ['Edit', '@web/cases/u/'.$theCase['id']];
}

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
<div class="col-md-6">
	<!-- Basic setup -->
	<div class="panel panel-white">
		<form class="form-add-steps" method="POST">
    		<div class="step-wrapper">
				<fieldset class="step" id="add-step1">
					<h6 class="form-wizard-title text-semibold">
						CASE INFO
					</h6>
					<div class="col-md-12">
						<? if ($theCase->isNewRecord) { ?>
						<div class="alert alert-info">You're creating a new B2C case. <a href="/b2b/cases/c">To add new B2B case, click here</a></div>
						<? } ?>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<?= Html::activeLabel($theCase, 'name', ['class' => 'control-label']) ?>
									<?=Html::activeInput('text', $theCase, 'name', ['class' => 'form-control']); ?>
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
									<?= Html::label('How customer contacted us', 'cofr', ['class' => 'control-label']) ?>
									<?= Html::activeDropDownList($theCase, 'cofr', $caseHowContactedListFormatted, ['class' => 'form-control', 'prompt'=> Yii::t('app', '- Select -')]) ?>
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
				<fieldset class="step" id="add-step2">
					<h6 class="form-wizard-title text-semibold">
						<span class="form-wizard-count">2</span>
						CUSTOMER INFO
						<small class="display-block"></small>
					</h6>
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
								<?= Html::activeDropDownList($theUserForm, 'country_code', ArrayHelper::map($countryList, 'code', 'name_en'), ['class' => 'form-control']) ?>
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
									<input type="text" class="form-control" id="email_0" name="email[]" value="<?= isset($theUserForm->email) ? $theUserForm->email : ''?>" placeholder="example@....">
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
					<h6 class="form-wizard-title text-semibold">
						REQUEST
					</h6>
				</fieldset>
			</div>

			<div class="form-wizard-actions">
				<!-- <button type="button" class="btn btn-primary" id="add-step"><i class="icon-plus22"></i> Add step</button> -->
				<button class="btn btn-default" id="step-back" type="reset">Back</button>
				<button class="btn btn-info" id="step-next" type="submit">Next</button>
			</div>
		</form>
	</div>
	<!-- /basic setup -->
	
</div>
<div class="col-md-6">
	    <? if (isset($theInquiry) && $theInquiry) { $inquiryData = unserialize($theInquiry['data']); ?>
		<p><strong>THE WEB INQUIRY</strong></p>
		<ul class="media-list note-list">
			<li class="media note-list-item">
				<? $userAvatar = '//secure.gravatar.com/avatar/'.md5($theInquiry['email']).'?s=100&d=wavatar'; ?>
				<?= Html::a(Html::img($userAvatar, ['class'=>'note-author-avatar media-object hidden-xs']), '#', ['class'=>'pull-left']) ?>
				<div class="media-body note-content">
					<h5 class="media-heading note-heading">
						<i class="fa fa-desktop"></i>
						<?= Html::a('Web inquiry from '.$theInquiry['site']['name'].' / '.$theInquiry['form_name'].' / '.$theInquiry['email'], '@web/inquiries/r/'.$theInquiry['id'], ['style'=>'font-weight:bold;']) ?>
					</h5>
					<div class="mb-1em">
						<span class="text-muted"><?= DateTimeHelper::convert($theInquiry['created_at'], 'd-m-Y H:i O', 'UTC', Yii::$app->user->identity->timezone) ?></span>
					</div>
					<div class="note-body">
						<div class="inquiry-body">
	    					<?if (in_array($theInquiry['form_name'], [
	    							'en_contact_130920', 'en_quote_130920',
	    							'fr_devis_130920', 'fr_devis_140905', 'fr_booking_130920', 'fr_booking_140905',
	    							'fr_contact_130920', 'fr_contactce_130920', 'fr_rdv_130920',
	    							'val_contact_130920', 'val_rdv_130920', 'val_devis_130920', 'val_devis_140905', 'val_booking_130920', 'val_booking_140905', 
	    							'vac_contact_130920', 'vac_rdv_130920', 'vac_devis_130920', 'vac_devis_140905', 'vac_booking_130920', 'vac_booking_140905', 
	    							])) {
	    							echo $this->render('//inquiry/_render_'.$theInquiry['form_name'], [
	    								'theInquiry'=>$theInquiry,
	    								'inquiryData'=>$inquiryData,
	    								]);
	    					} else {
	    						?>
	    						<div><strong>CUSTOMER</strong></div>
	    						<div>
	    							Name: <span class="text-warning"><?= $theInquiry['name'] ?></span><br />
	    							Email: <span  class="text-warning"><?= $theInquiry['email'] ?></span><br />
	    							Country: <span  class="text-warning"><?= $inquiryData['country'] ?></span><br>
	    						</div>
	    						<div><strong>INQUIRY DATA</strong></div>
	    						<div>
	    							<table class="table table-condensed">
	    								<?
	    								foreach ($inquiryData as $k=>$v) {
	    									if ($v != '' || (is_array($v) && empty($v))) {
	    										?>
	    										<tr>
	    											<td><?= $k ?></dt>
	    												<td><?= is_array($v) ? implode(', ', $v) : nl2br(Html::encode($v)) ?></td>
	    											</tr>
	    											<?
	    										}
	    									}
										?>											
									</table>
								</div>
							<?
							}
							?>
							<ul class="list-unstyled" style="border-left:3px solid #ccc; padding-left:10px;">
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
										if (isset($mRef['path']) && $mRef['path'] == '/aclk') {
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
		<? } ?>
		<? if (isset($theMail) && $theMail) { ?>
		<p><strong>THE EMAIL MESSAGE</strong></p>
		<ul class="media-list note-list">
			<li class="media note-list-item">
				<?
				$userAvatar = '//secure.gravatar.com/avatar/'.md5($theMail['from_email']).'?s=100&d=wavatar';
				if ($theMail['from_email'] == $theCase['owner']['email'] && $theCase['owner']['image'] != '') {
					$userAvatar = '/timthumb.php?zc=1&w=100&h=100&src='.$theCase['owner']['image'];
				}
				?> 
				<?= Html::a(Html::img($userAvatar, ['class'=>'note-author-avatar media-object hidden-xs']), '#', ['class'=>'pull-left']) ?>
				<div class="media-body note-content" style="border-left-color:#31708f;">
					<h5 class="media-heading note-heading">
						<i class="fa fa-envelope-o"></i>
						<?= Html::a($theMail['from'], '@web/mails/r/'.$theMail['id'], ['class'=>'note-author-name', 'rel'=>'external']) ?>:
						<?= Html::a($theMail['subject'] == '' ? '' : $theMail['subject'], '@web/mails/r/'.$theMail['id'], ['class'=>'note-title', 'rel'=>'external']) ?>
						<small><a class="text-muted label" style="background-color:#ccc;" onclick="$('#mail-tbl-<?= $theMail['id'] ?>').toggle(); return false;">&hellip;</a></small>
					</h5>
					<div class="mb-1em">
						<span class="text-muted">
							<?= DateTimeHelper::convert($theMail['created_at'], 'd-m-Y H:i O', 'UTC', Yii::$app->user->identity->timezone) ?>
							<? if ($theMail['created_at'] != $theMail['updated_at'] && $theMail['updated_by'] != 0) { ?>
							edited
							<? } ?>
						</span>
						<? if ($theMail['attachment_count'] > 0) { ?>
						- <i class="fa fa-paperclip"></i> <?= $theMail['attachment_count'] ?>
						<? } ?>

						<? if ($theMail['tags'] == 'op') { ?>
						- <?= Html::a('Shared in tour', '@web/mails/u-op/'.$theMail['id'], ['class'=>'label label-success', 'title'=>'Click to stop sharing']) ?>
						<? } else { ?>
						- <?= Html::a('Not shared', '@web/mails/u-op/'.$theMail['id'], ['class'=>'text-muted', 'title'=>'Click to share in tour']) ?>
						<? } ?>

						<? if (in_array(Yii::$app->user->id, [1, $theCase['owner_id']])) { ?>
						- <?= Html::a('Edit', '@web/mails/u/'.$theMail['id'], ['class'=>'text-muted']) ?>
						- <?= Html::a('Delete', '@web/mails/u/'.$theMail['id'], ['class'=>'text-muted']) ?>
						<? } ?>
					</div>
					<div id="mail-tbl-<?= $theMail['id'] ?>">
						<table class="table table-condensed table-bordered bg-info">
							<tbody>
								<tr><td>Date</td><td><?= DateTimeHelper::convert($theMail['sent_dt'], 'd-m-Y H:i O', 'UTC', Yii::$app->user->identity->timezone) ?></td></tr>
								<tr><td>From</td><td><?= Html::encode($theMail['from']) ?></td></tr>
								<tr><td>To</td><td><?= Html::encode($theMail['to']) ?></td></tr>
								<? if ($theMail['cc'] != '') { ?>
								<tr><td>Cc</td><td><?= Html::encode($theMail['cc']) ?></td></tr>
								<? } ?>
							</tbody>
						</table>
					</div>
					<? if ($theMail['attachment_count'] > 0 && $theMail['files'] != '') { $theMail['files'] = unserialize($theMail['files']); ?>
					<div class="note-file-list">
						<? foreach ($theMail['files'] as $file) { ?>
						<div class="note-file-list-item">+ <?= Html::a($file['name'], '@web/mails/f/'.$theMail['id'].'?name='.$file['name']) ?> <span class="text-muted"><?= Yii::$app->formatter->asShortSize($file['size'], 0) ?></span></div>
						<? } ?>
					</div>
					<? } ?>
					<div class="note-body" style="overflow-y:scroll; overflow-x:hidden;">
						<?= str_ireplace(['<br><br><br>', '<br><br>', 'href="', 'src="'], ['<br>', '<br>', 'href="#', 'src="//my.amicatravel.com/assets/img/1x1.png" x="'], $theMail['body']) ?>
					</div>
				</div>
			</li>
		</ul>
		<? } ?>
	</div>

<?php
$js = <<<TXT
var DOC = $(document);
var FORM = $("#abc");

TXT;
$this->registerJsFile('/js/jquery.form.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('/js/core/libraries/jquery_ui/core.min.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('/js/jquery.form.wizard.js', ['depends' => 'yii\web\JqueryAsset']);

$this->registerJsFile('/js/inquiry_form.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJs($js);
?>