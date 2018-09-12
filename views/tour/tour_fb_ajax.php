<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.9/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
	<?php
		use yii\helpers\Html;

		$version = $versions['20072018'];
		
		$action = Yii::$app->request->get('action', 'add');
	
	?>
	<!-- font face -->
	<style>
		/* latin */
		@font-face {
		  font-family: "LatoLatin-Regular";
		  font-style: normal;
		  src: url('/font/LATOLATIN/LatoLatin-Regular.ttf') format('truetype');
		}
		@font-face {
		  font-family: "LatoLatin-Bold";
		  font-style: normal;
		  src: url('/font/LATOLATIN/LatoLatin-Bold.ttf') format('truetype');
		}
		@font-face {
		  font-family: "LatoLatin-Semibold";
		  font-style: normal;
		  src: url('/font/LATOLATIN/LatoLatin-Semibold.ttf') format('truetype');
		}
	</style>

	
	<style>
		body {color:#222; font: 15.5px/1.5'LatoLatin-Regular', "sans-serif"}
		body .header {
			background: rgba(212,214,217, 0.9) none repeat scroll 0 0;
			display: inline-block;
			height: 95px;
			padding: 18px 0;
			position: relative;
			z-index: 999;
			font: LatoLatin-Regular,sans-serif;
		}
		.header .info-header p {
			color: #232323;
			font: 14.5px LatoLatin-Regular,sans-serif;
			margin: 2px 0;
		}
		.header .info-header {
			text-align: right;
		}
		.contain{
			float: left;
			clear: left;
			width: 100%;
			height: auto;
			font-size: 15.5px;
			font-family: "LatoLatin-Regular", sans-serif;
		}
		.contain .amc-column{
			width: 960px;
			margin: 0 auto;
			padding: 0 15px;
		}
		.container-1{margin-top: -150px; position: relative; z-index: 0; }
		.container-1 .row-2{position: absolute; bottom: 15px; left: 0; right: 0; }
		.container-1 .amc-column .title{
			color: white;
			text-transform: uppercase;
			font-size: 32px;
			text-align: center;
			margin: 0 0 35px 0;
		}
		.container-2{z-index: 2;}
		
		.form{width: 870px; margin: 0 auto; }
		.form .amc-col{padding: 0 10px; }
		.text .tt{font-size: 18px; font-family: "LatoLatin-Bold", sans-serif; margin: 40px 0 20px; text-transform: uppercase; }
		
		.input_full{width: 100%; }
		.code-anti{vertical-align: top; }
		.fix-text-code{margin: 20px 0 0; }
		
		.error-summary ul li{color: #e26640; }
		span.field-contactform-calldate  {
			background: rgba(0, 0, 0, 0) url("https://www.amica-travel.com/assets/img/page2016/icon_input_datepicker.png") no-repeat scroll right 5px;
			display: inline-block;
			height: 100%;
			padding-right: 40px;
		}
		.fix-tt{margin: 0 0px 0 20px; }
		
		.text{
			width: 870px;
			margin: 60px auto 0;
			padding: 0 10px;
			text-align: center;
		}

		.text .fix{padding: 0 10px; }
		.input-fullname {width: 100%; }
		.amc-col-fix-align {text-align: right; }

		.fix-middle-text {display: inline-block; margin-top: 12px; }
		.has-error .text-label {/*  color: #e26640; */ }
		.input-region {width: 100%; }
		.input-country {width: 100%; }
		.input-ville {width: 100%; }
		.fix-col-left {display: table; height: 100%; }
		.fix-col-left .middle-text {display: table-cell; padding: 0 10px; vertical-align: middle; }
		.fix-img-bottom-left {bottom: -10px; display: inline-block; left: -55px; position: absolute; z-index: 3; }
		.fix-img-right {top: 15%; display: inline-block; right: 0; position: absolute; z-index: -11; }
		.footer {position: relative; }
		h1 {font:bold 20px/22px "LatoLatin-Regular";}
	</style>
	<!-- input form -->
	<style>
		.area-btn-list-menu{background: rgba(255,255,255, 0.8); z-index: 1; }
		input, select{border: 1px solid #d9d9d9; height: 42px; line-height: 44px; padding: 0 10px; }
		textarea{border: 1px solid #d9d9d9; width: 100%; padding: 10px; }
		select.fix-arrow{
			background: url(https://www.amica-travel.com/assets/img/page2016/arrow_up_down_cam.png) no-repeat scroll right 15px center;
			-moz-appearance: none !important;
			-webkit-appearance: none !important;
			padding-right: 27px;
		}
		input[type="radio"], input[type="checkbox"] {display:none; }

		input[type="radio"] + span, input[type="checkbox"] + span {
			display:inline-block;
			width:15px;
			height:15px;
			margin:-4px 0px 0 0;
			vertical-align:middle;
			cursor:pointer;
			-moz-border-radius:  50%;
			border-radius:  50%;
		}

		input[type="checkbox"] + span{
			border-radius: 0px;
		}

		input[type="radio"] + span {
			background: url("https://www.amica-travel.com/assets/img/page2016/bg_radio.png") no-repeat scroll left center;
		}
		input[type="checkbox"] + span{
			background: url("https://www.amica-travel.com/assets/img/page2016/bg_checkbox.png") no-repeat scroll left center;
		}

		input[type="radio"]:checked + span{
			background: url("https://www.amica-travel.com/assets/img/page2016/bg_list_active.png") no-repeat scroll left center;
		}
		input[type="checkbox"]:checked + span{
			background: url("https://www.amica-travel.com/assets/img/page2016/bg_checkbox_active.png") no-repeat scroll left center;
		}

		input[type="radio"] + label span,
		input[type="radio"]:checked + label span,
		input[type="checkbox"] + label span,
		input[type="checkbox"]:checked + label span{
			-webkit-transition:background-color 0.4s linear;
			-o-transition:background-color 0.4s linear;
			-moz-transition:background-color 0.4s linear;
			transition:background-color 0.4s linear;
		}
		label {
			font-family: "LatoLatin-Regular",sans-serif;
			display: block;
			margin: 0 15px 0 0;
			font-size: 15.5px;
			font-weight: normal;
			line-height: 15.5px
		}
		.help-block {
			color: #e25825;
			font-size: 13px;
			font-style: italic;
			margin: 3px 0 0;

		}
		.has-error input, .has-error select, .has-error textarea{border: 1px solid #e25825; }
		select { border-radius: 5px; }
		/*table*/

		table td{padding: 8px; padding-bottom: 0!important;}
		table td:first-child {padding-left: 0!important;}
		.ta-c {text-align:center;}
		table th.not_fix_width { min-width: 15%;}
		.table-bordered, .table-bordered td, .table-bordered th {
		    border: 0 none !important;
		}
		.bg-finish{background-color: #e6e3d9 !important; border-radius: 10px 10px 0 0; -moz-border-radius-topleft: 2px;}
		td { vertical-align: middle !important;}
		/*button submit*/
		#btn-valider-big{
			text-transform: uppercase;
			color: white;
			font-size: 13.5px;
			background: #e75925 url(https://www.amica-travel.com/assets/img/page2016/arrow_white.png) 328px center no-repeat;
			display: inline-block;
		    text-align: center;
		    font-family: "LatoLatin-Bold", sans-serif;
		    margin: 30px 0 35px;
		    border-radius: 9px;
		    width: 293px;
		    padding: 13px 10px;
		    border: none;
		}
		#btn-valider-big:hover{opacity: 0.8; }
	</style>
	<style>
		.wrap-name {padding: 10px 14px 0 14px; }
		#header-fixed {position: fixed; top: 0px; display:none; background-color:white; }
		/*.wrap-table h4 {font: "LatoLatin-Bold", sans-serif;}*/
		.wrap-table {padding-bottom: 40px;}
		.wrap-table h4 span {   display: inline-block; }
		.wrap-table h4 span:first-letter {text-transform:uppercase;}
		.text-left span {display: inline-block;}
		.wrap-table h4 span.require:after {content: " *"; color: #e75a26; }

		/*h5 span {text-transform:lowercase;}*/
		h5 span:first-letter {text-transform:uppercase;}
		.text-left span {display: block}
		.span-fix {padding-left: 20%;}

		.question .option {margin-bottom: 13px;}
		.question .option:last-child {margin-bottom: 0;}
		.question .option label { margin-right: 0.6rem; font-color: #b1b1b1; line-height: 15.5px}
		.wrap-row label:after {content: " *"; color: #e75a26; }

		.table td label {margin-right: 0;}

		.table td {padding-top: 6px; font: 15.5px/1.5 "LatoLatin-Regular",sans-serif; font-color: #b1b1b1;}
		.wrap-table h4{font: 15.5px "LatoLatin-Semibold",sans-serif; /*font-color: #201f1f;*/ margin-bottom: 13px; line-height: 15.5px}

		.form-control:focus {border-color: #7d7d7d; box-shadow: none}
		.container-2 .amc-column {
		    background: transparent;
		    padding-top: 60px;
		    position: relative;
		}
		.contain.container-1::before {
		    /*background-color: rgba(0, 0, 0, 0.4);*/
		    content: "";
		    display: inline-block;
		    height: 567px;
		    width: 100%;
		}
		.bg-fix {background-color: rgb(208,201,175)}
		.bg-fix th {font-weight: 600}
		.text-intro {font: 15.5px/1.5 "LatoLatin-Regular",sans-serif; margin-bottom: 36px;}
		.nav-item {min-width: 25%}
		.nav-item .nav-link { color: rgb(132,135,152); padding: 2px 2px 0 2px; border-radius: 10px 10px 0 0;}
		/*.nav-tabs .nav-link {padding: 3px 3px 0 3px;}*/
		.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {color: rgb(227,84,41);}
		.nav {padding-left: 2%;}
		.fix-col {text-align: left; padding-left: 3px;}
		h2 {color:#000; font:bold 22px "LatoLatin-Regular"; font-family: "LatoLatin-Regular",sans-serif; line-height: 22px; padding-top: 40px; padding-bottom: 0px; margin-bottom: 40px}

		h2 span {
			display: inline-block;
			width: 40px;
			height: 40px;
			background: #e46d00;
			border-radius: 50%;
			padding: 5px;
			vertical-align: middle;
			margin-right: 10px;
		}
		.container-3:nth-of-type(1) h2 span {
			/*background:#000;*/
			background: #e46d00 url(/img/img-fb/icon1.png) center no-repeat;
		}
		.container-3:nth-of-type(2) h2 span {
			background: #e46d00 url(/img/img-fb/icon2.png) center no-repeat;
		}
		.container-3:nth-of-type(3) h2 span {
			background: #e46d00 url(/img/img-fb/icon3.png) center no-repeat;
		}
		.container-3:nth-of-type(4) h2 span {
			background: #e46d00 url(/img/img-fb/icon4.png) center no-repeat;
		}
		.nav-link {padding: 0.5rem 1rem 0 1rem;}
		/*th.fix-width { padding-top: 10px!important ;}*/
		th.fix-width ~ th { width: 12%; padding-left: 0; padding-right: 0;}
		.mb-fix { margin-bottom: 13px; }
		table th {padding-top: 12px; font-weight: normal; line-height: 15.5px}
		th.q6_fix {font: 15.5px 'LatoLatin-Semibold',sans-serif; padding-left: 0!important; padding-top: 0; width: 61%; padding-bottom: 0!important; }
		.q6_fix + th {  padding-top: 0 !important; padding-bottom: 0!important;}
		.q6_fix + th + th {  padding-top: 0 !important; padding-bottom: 0!important;}
		#header_fix th {padding-top: 0;}
		.h4-title {font: 15.5px "LatoLatin-Semibold",sans-serif; text-transform: capitalize; height: 53px; line-height: 53px; padding-right: 30%; width: 37%;}
		.container-3:last-child .wrap-table:nth-of-type(5) h4{ line-height: 17.5px; }
		.help-block {
			font-size: 13px;
			font-family: 'LatoLatin-Regular';
			font-style: italic;
			line-height: 13px;
			margin-top: 5px;
			color: #e25825;
		}
	</style>
	<!-- reponsive -->
	<style>
		@media (max-width: 1555px) { 
			.fix-img-bottom-left {display: none }
		}
		@media (max-width: 1199.98px) { 
			.fix-img-bottom-left { z-index: -11; display: none }
		}
		@media (max-width: 1024px) {
			.container-1 {margin-top: -200px; }
			.contain.container-1::before { max-height: 445px;}
		}
		@media (max-width: 991.98px) {

		}
		@media (max-width: 960px) {
			.contain .amc-column{
				width: 100%;
			}
			.contain.container-1::before { max-height: 350px;}
			.container-2 .text {max-width: 100%;}
			.container-1 {margin-top: -147px; }
			.modal-fix {max-width: 100%;}
		}
		@media (max-width: 767.98px) {
			.container-1 {margin-top: -120px; }
			.contain.container-1::before { max-height: 287px;}
			.w-cs-100 { width: 100% !important; }
			.h4-title {padding-right: 20%;}
			.file-preview-thumbnails {
			    display: flex;
			    justify-content: left;
			    align-items: center;
			}
		}
		@media (max-width: 575.98px) {
			.container-1 {margin-top: -100px; }
			.header .info-header {display: none;}
			.contain.container-1::before { max-height: 247px;}
			.wrap-row .question { margin-bottom: 15px; }
		}
		@media (max-width: 480px) {
			.container-1 {margin-top: -80px; background-size: 100% !important; background-repeat: no-repeat;}
			.header .info-header {display: none;}
			.contain.container-1::before { max-height: 100px;}
			.container-1 .amc-column .title {display: none}
		}
	</style>
	
	<!-- upload -->
	<style>
		.btn-file input[type="file"] {
			top: 0;
			left: 0;
			min-width: 100%;
			min-height: 100%;
			text-align: right;
			opacity: 0;
			background: none;
			cursor: inherit;
			display: block;
		}
		.file-preview-image{ height: 100%!important; }

		.file-preview {border: none; display: inline-block; width: auto; padding: 0; margin: 0;}
		.krajee-default.file-preview-frame .kv-file-content {
			width: 106px;
			height: 106px;
			position: relative;
		}
		.krajee-default.file-preview-frame {padding: 0; margin: 0; margin-right: 5px; margin-bottom:5px; border-radius: 10px; overflow: hidden; border: none;}
		.browse_btn {background:url(/img/img-fb/btn_upload.jpg) no-repeat center; color: #b6b6b6; border-radius: 11px; height: 106px; width: 106px; margin-top: 10px;
		}
		.my-custom-frame-css:hover {
			cursor: pointer;
		}
		.file-preview-image-btn {background:url(/img/img-fb/btn_upload.jpg) no-repeat center;}
		.close_file{
			width: 5px;
			height: 5px;
			right: 15px;
			top: 5px;
			position: absolute;
			font-size: 15.5px;
			color: #000;
			cursor: pointer;
		}
		/*.close_file:hover{color: #f7f7f7;}*/
		.krajee-default.file-preview-frame .kv-file-content:before:hover{ background-color: #fff }
		.browse_btn, .file-thumbnail-footer, .file-upload-indicator, .file-input .clearfix, .krajee-default .file-footer-caption, .kv-fileinput-error{display: none!important;}
		.file-drop-zone {border: none ; }
		.op_fix {margin-bottom: 38px; line-height: 15.5px; font-size: 15.5px;}
	</style>
	<!-- modal thanks -->
	<style>
		.modal-body {font-size: 15.5px; font-family: "LatoLatin-Bold", sans-serif;}
		.head_fix { height: 378px; }
		.modal-fix {max-width: 960px; margin-top: 50px;}
		.say_thanks {  }
		.close_modal {
			width: 36px; height: 36px; background: #000; border-radius: 50%; color:#fff; font-size: 25px;
			display: block;
			text-align: center;
			vertical-align: middle;
			padding: 5px;
		}
		.close {
			position: absolute;
			top: -37px;
			right: 0px; }

	</style>
</head>
<body>
	<div class="container-fluid contain header">
		<div class="amc-column">
			<div class="logo float-left">
				<a href="/" alt="Amica Travel">
					<img src="https://www.amica-travel.com/assets/img/form/xlogo.png.pagespeed.ic.NIHNykYGDX.png" alt="Amica Travel" data-pagespeed-url-hash="1969326292" data-pagespeed-onload="pagespeed.CriticalImages.checkImageForCriticality(this);" onload="var elem=this;if (this==window) elem=document.body;elem.setAttribute('data-pagespeed-loaded', 1)" data-pagespeed-loaded="1">
				</a>
			</div>
			<div class="info-header float-right">
				<p>info@amica-travel.com</p>
				<p>FR : (+33) 6 19 08 15 72 ou (+33) 6 28 22 72 86</p>
				<p>VN : (+84) 984 56 66 76</p>
			</div>
		</div>
	</div>
	<div class="contain container-1" style="background-image: url('/img/img-fb/bg-head.JPG'); background-size: cover;">

		<div class="amc-column row-2">
			<h1 class="title">QUESTIONNAIRE DE SATISFACTION</h1>
		</div>
	</div>
	<div class="contain container-2">
		<div class="amc-column">
			<div class="text-center">
				<p class="text-center text-intro">Madame, Monsieur, cher voyageur Amica,<br><br>
					L’équipe tenait à vous remercier de votre confiance et de nous avoir choisi, pour l’organisation de votre voyage.<br>
					Afin d’améliorer constamment la qualité de nos prestations, nous vous serions<br>
					reconnaissant de bien vouloir nous faire part de vos appréciations, en répondant au<br>
					questionnaire suivant. Cela ne vous prendra pas plus de 5 minutes pour donner vos précieux avis. <br><br>
					Vous remerciant par avance !
				</p>
			</div>
		</div>
	</div>
	<form id="fbForm" method="POST" accept-charset="utf-8" enctype="multipart/form-data" data-tour_id="<?= $theTour['id']?>">
	<!-- section 1 -->
	<div class="contain container-3" style="background-color: #f7f7f7;">
		<div class="amc-column">
			<h2> <span></span> Informations générales</h2>
			<div class="wrap-table">
				<!-- info -->
				<div class="row wrap-row ">
					<div class="col-sm-2 question ">
						<label class="mb-fix"> Civilité</label>
						<select id="contactform-prefix" class="fix-arrow input-fullname" name="info[prefix]">
							<option value="M." <?= (isset($info['prefix']) && $info['prefix'] == 'M.')? 'selected': '' ?> >M.</option>
							<option value="Mme." <?= (isset($info['prefix']) && $info['prefix'] == 'Mme.')? 'selected': '' ?> >Mme.
							</option>
						</select>
					</div>
					<div class="col-sm-5 question">
						<label class="mb-fix">Nom</label>
						<div class="form-group mb-0 require">
							<input class="form-control" name="info[nom]" value="<?= (isset($info['nom']))? $info['nom']: '' ?>" type="text">
						</div>
					</div>
					<div class="col-sm-5 question">
						<label class="mb-fix">Prénom</label>
						<div class="form-group mb-0 require">
							<input class="form-control" name="info[pre]" value="<?= (isset($info['pre']))? $info['pre']: '' ?>" type="text">
						</div>
					</div>
				</div>
			</div>


			<?php
			$q1 = $version['questions']['q1'];
			unset($version['questions']['q1']);
			?>
			<div class="table-responsive wrap-table">
				<h4><span><?= $q1['title']?></span></h4>
				<div class="question d-flex flex-column">
					<?php foreach ($q1['options'] as $op_title) {?>
					<div class="option">
						<?php if(strtolower($op_title) != "autre" ){?>
						<label class="float-left">
							<input class="control-info" type="checkbox" name="q1<?= '['.$op_title.']'?>" <?= isset($content_q['q1'][$op_title])? 'checked': '' ?>>
							<?= $op_title?>
						</label>
						<!-- <label class="float-left"><?= $op_title?></label> -->
						<?php } else {?>
						<label class="float-left" style="text-transform: capitalize; margin-bottom: 12px"><?= $op_title?></label>
						<label class="float-left w-100">
							<div class="form-group mb-0">
								<input name="q1<?='['.$op_title.']'?>" class="form-control" value="<?=isset($content_q['q1'][$op_title])? $content_q['q1'][$op_title]: ''?>" >
							</div>
						</label>
						<?php }?>
						
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<!-- section 2 -->
	<div class="contain container-3" style="position: relative; ">
		<div class="amc-column" style="background-color: #fff">
			<?php foreach ($version['questions'] as $num_q => $q) {
				if ($num_q == 'q8') {
					break;
				}
				?>
				<?php if($num_q == 'q2') {?>
					<h2><span></span> Pendant le voyage</h2>
				<?php } ?>
				<div class="table-responsive wrap-table">
					<?php if ($num_q != 'q6' && $num_q != 'q2' && $num_q != 'q3' && $num_q != 'q4' ) {?>
					<h4><span><?= ($num_q == 'q2')? "" : $q['title']?></span></h4>
					<?php } ?>

					<!-- guide and driver -->
					<?php if($q['title'] == 'guide' || $q['title'] == 'chauffeur') { ?>
					<div class="float-left h4-title"><?= $q['title']?></div>
					<ul class="nav nav-tabs">
					<?php
						if ($q['title'] == 'guide') { $title = 'guides'; }
						if ( $q['title'] == 'chauffeur') { $title = 'drivers'; }

						foreach ($theTour[$title] as $index => $user) {
							$gender = 'Ms';
							$mark = $index + 1;
							$name = '';
							$dateUse = '';
							$monthTrue = true;
							if (date('m', strtotime($user['use_until_dt'])) != date('m', strtotime($user['use_from_dt']))) {
								$monthTrue = false;
							}
							if (!$monthTrue) {
								$dateUse = date('j/n', strtotime($user['use_from_dt'])) . ' - ' . date('j/n', strtotime($user['use_until_dt']));
							} else {
								$dateUse = date('j', strtotime($user['use_from_dt'])) . ' - ' . date('j/n', strtotime($user['use_until_dt']));
							}

							if ($title == 'guides') {
								if (isset($user['guide']['gender']) && $user['guide']['gender'] == 'male') {
									$gender = 'Mr';
								}
								$user['guide_name'] = preg_replace('/(-)? (\d)*/', '', $user['guide_name']);
								$name = isset($user['guide']['lname'])? $gender. '. ' .$user['guide']['lname']: $user['guide_name'];
							}
							if ($title == 'drivers') {
								if (isset($user['driver']['gender']) && $user['driver']['gender'] == 'male') {
									$gender = 'Mr';
								}
								$user['driver_name'] = preg_replace('/(-)? (\d)*/', '', $user['driver_name']);
								$name = isset($user['driver']['lname']) ? $gender. '. ' .$user['driver']['lname']: $user['driver_name'];
							}
						?>
						<li class="nav-item"><a href="#<?= $title.$mark?>" style="text-transform: capitalize; line-height: 20px" class="nav-link text-center <?= ($index == 0)? 'active show': ''?>" data-toggle="tab"><div class="wrap-name">
							<?= $name?><br><span class="text-center" style="display: block; color: #919191; font-size: 15.5px"><?= $dateUse?></span>
						</div></a> </li>
						<?php } ?>
					</ul>

					<div class="tab-content">
					<?php foreach ($theTour[$title] as $i_user => $user) {
						$mark = $i_user + 1;
						?>
						<div class="tab-pane fade <?= ($i_user == 0)? 'active show': ''?>" id="<?= $title.$mark?>">
							<table class="table table-condensed table-bordered  mb-0 ">
								<thead>
									<tr class="">
										<th class="ta-c fix-width" ></th>
										<?php foreach ($q['options_value'] as $op_v) { ?>
											<th class="ta-c" ><?= $op_v?></th>
										<?php } ?>
									</tr>
								</thead>
							<?php $current_v = ''; ?>
							<?php foreach ($q['options'] as $index => $op) {
								$current_v = isset($content_q[$num_q][$op][$mark])?$content_q[$num_q][$op][$mark] : '';
								?>
								<tr>
									<td class="ta-c text-left">
										<?php
											echo $op;
										?>
									</td>
									<?php for($i = 1; $i <= count($q['options_value']); $i ++) {
										$checked = '';
										if (isset($arr_v[$index]) && $arr_v[$index] == $i){
											$checked = 'checked';
										}
									?>
									<td class="text-center">
										<div class="checkbox">
										<?php if ($action != 'view'){ ?>
										<label class="text-center">
											<input class="control-info" type="radio" <?= (isset($content_q[$num_q][$op][$mark]) && $content_q[$num_q][$op][$mark] == $i)? 'checked': '' ?> ></span>
										</label>
										<?php } ?>
										</div>
									</td>
								<?}?>
								<?= Html::input('hidden', $num_q.'['.$op.']['.$mark.']', $current_v, [])?>
								</tr>
							<?php }?>
						</table>
						</div>
					<?php } ?>
					</div>
					<!-- other table -->
					<?php } elseif($num_q == 'q7' || $num_q == 'q5') { ?>
						<div class="question">
							<div class="text-left form-group  mb-0">
								<textarea name="<?=$num_q.'['.$q['options'][0].']'?>" class="form-control" ><?=isset($content_q[$num_q]['autre'])? $content_q[$num_q]['autre']: ''?></textarea>
							</div>
						</div>
						<?php } else { ?>
						<table class="table table-condensed table-bordered mb-0 " id="<?=($num_q == 'q2')? 'header_fix': ''?>">
							<?php if (count($q['options'] > 1) && strtolower($q['options'][0]) != 'autre') { ?>
								<thead>
									<tr class="">
										<th class="ta-c text-left <?= ($num_q == 'q2')? 'fix-width': ''?>  <?= ($num_q == 'q6')? 'q6_fix': ''?>"> <?= ($num_q == 'q6')? $q['title']: ''?></th>
										<?php foreach ($q['options_value'] as $op_v) { ?>
											<th class="ta-c <?= ($num_q == 'q6' && strtolower($op_v) == 'non' )? 'text-left': '';?>" ><?= $op_v?></th>
										<?php } ?>
									</tr>
								</thead>
							<?php } ?>
							<?php
								$arr_category = [];
							?>
							<? foreach ($q['options'] as $index => $op) {
									$current_v = '';
									if (isset($content_q[$num_q][$op])) {
										$current_v = $content_q[$num_q][$op];
									}
									$new_cate = false;
									if (strpos($op, '::') !== false) {
										$arr_c = explode('::', $op);
										$col_span = count($q['options_value']) + 1;
										if (!in_array($arr_c[0], $arr_category)) {
											$arr_category[] = $arr_c[0];
											$new_cate = true;
										}
									}
									if ($new_cate) {
										echo "<tr><td colspan=".$col_span.">".$arr_c[0]." : </td></tr>";
									}
									?>
									<tr>
										<td class="ta-c text-left">
											<?php 
												if (strpos($op, '::') !== false){
													echo "<span class='span-fix'>".$arr_c[1]."</span>";
												} else {
													echo ($op != $q['title'])? $op : '';
												}
											?>
										</td>
										<?php for($i = 1; $i <= count($q['options_value']); $i ++) {
											$checked = '';
											if (isset($content_q[$num_q][$op])) {
												$checked = ($content_q[$num_q][$op] == $i)? 'checked': '';
											}
											?>
											<td class="text-center">
											<?php if ($action != 'view'){ ?>
												<label class="<?= ($num_q == 'q6' && $i == 2 )? 'fix-col': 'text-center';?>">
													<input class="control-info" type="radio"  <?= $checked ?>  ></input>
												</label>
											<?php } ?>
											</td>

										<?}?>
										<?= Html::input('hidden', $num_q.'['.$op.']', $current_v, [])?>
									</tr>
							<?php }?>
						</table>
					<?php } ?>
				</div>
			<?php } ?>
			<table id="header-fixed" class="table table-condensed table-bordered"></table>

		</div>
		<img alt="" class="img-fluid img-lazy fix-img-right" src="/img/bg-right.png" >
	</div>
	<!-- section 3 -->
	<div class="contain container-3" style="background-color: #f7f7f7;">
		<div class="amc-column">
		<?php foreach ($version['questions'] as $num_q => $q) {
			if (!in_array($num_q, ['q8','q9', 'q10', 'q11'])) {
				continue;
			}
			?>
			<?php if($num_q == 'q8') {?>
				<h2><span></span> Vos futurs voyages</h2>
			<?php } ?>
			<div class="table-responsive wrap-table">
				<h4><span class="<?= ($num_q == 'q8' || $num_q == 'q10') ? 'require' : ''?>"><?= $q['title']?></span></h4>
				<?php if ($num_q == 'q11' || $num_q == 'q9') { ?>
				<div class="question d-flex flex-column">
					<?php foreach ($q['options'] as $op_title) {?>
					<div class="option">
						<?php if(strtolower($op_title) != "autre" ){?>
						<label class="float-left">
							<input class="control-info" type="checkbox" name="<?=$num_q.'['.$op_title.']'?>" <?= isset($content_q[$num_q][$op_title])? 'checked': '' ?>>
							<?= $op_title?>
						</label>
						<!-- <label class="float-left"><?= $op_title?></label> -->
						<?php } else {?>
						<label class="float-left" style="text-transform: capitalize; margin-bottom: 12px"><?= $op_title?></label>
						<label class="float-left w-100">
							<div class="form-group mb-0">
								<input name="<?=$num_q.'['.$op_title.']'?>" class="form-control" value="<?=isset($content_q[$num_q][$op_title])? $content_q[$num_q][$op_title]: ''?>" >
							</div>
						</label>
						<?php }?>
					</div>
					<?php } ?>
				</div>
				<?php } elseif ($num_q == 'q8' || $num_q == 'q10') {?>
				<div class="question d-flex flex-column">
					<?php foreach ($q['options_value'] as $op_title) {?>
					<div class="option">
						<label class="float-left">
							<input class="control-info" type="radio" val="" name="<?=$num_q.'['.$op_title.']'?>" <?= isset($content_q[$num_q][$op_title])? 'checked': '' ?>>
							<?= $op_title?>
						</label>
						<!-- <label class="float-left"><?= $op_title?></label> -->
					</div>
					<?php } ?>
					
				</div>
				<?php }?>
			</div>
		<?php } ?>
		</div>
	</div>
	<!-- section 4 -->
	<div class="contain container-3">
		<div class="amc-column">
			<h2><span></span> Votre témoignage</h2>
			<?php if ($action != 'view') { ?>
			<div class="wrap-table question">
				<h4>N'hésitez pas à partager votre retour d'expériences et vos conseils personnalisés via ce formulaire
				</h4>
				<div class="wrap_form">
					
					<div class="comment">
						<br>
						<div class="form-group op_fix">
							<label class="mb-fix">Titre de votre témoignage</label><input type="text" class="form-control" maxlength="60" name="contact_1" onKeyDown="limitText(this.form.contact_1,this.form.countdown,60);" onKeyUp="limitText(this.form.contact_1,this.form.countdown,60);" value="">
							<span class="text-muted" style="color: #6c757d">
								<input readonly type="text" style="color: #6c757d;border: none; background: none;padding: 0; height: 15.5px; font-size: 15px; margin-top: 5px" name="countdown" size="1" value="60"> caractères restants</span>
						</div>
						<div class="form-group op_fix">
							<label class="mb-fix">Votre témoignage</label><textarea name="contact_2"  onKeyDown="limitText(this.form.contact_2,this.form.countdown_2,1000);" onKeyUp="limitText(this.form.contact_2,this.form.countdown_2,1000);"  class="form-control" maxlength="1000"></textarea>
							<span class="text-muted" style="color: #6c757d">
								<input readonly type="text" style="color: #6c757d;border: none; background: none;padding: 0; height: 15.5px; font-size: 15px; margin-top: 5px" name="countdown_2" size="3" value="1000"> caractères restants</span>
						</div>
						<div class="form-group op_fix">
							<label class="mb-fix">Avez-vous des photos à nous faire partager ? (10 photos maximum, 10 MB maximum par photo)</label>
							<!-- upload -->
							<input id="input-pr-rev" name="input_upload[]" type="file" multiple class="file">
							<!-- end upload -->
						</div>
						<div class="clearfix"></div>
						<div class="form-group mb-0">
							<label class="mb-fix">Une vidéo ou un lien (blog, site internet, etc.) à nous relayer ?</label>
							<input type="text" class="form-control" name="contact_3" value="">
						</div>
					</div>
				</div>
			</div>
			<p style="margin-bottom: 0.7rem; line-height: 15.5px"><span style="color: #E46D00">*</span> Champs obligatoire</p>
			<div class="text-center">
				<button id="btn-valider-big" class="text-center" type="submit" >Envoyez votre avis</button>
			</div>

			<?php } ?>
		</div>
	</div>
	</form>

	<!-- Modal thanks-->
	<div class="modal fade" id="thankModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-fix" role="document">
	    <div class="modal-content">
	      <div class="modal-header head_fix" style="background-image: url(/img/img-fb/DSCF0285.JPG); background-size: cover;">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span class="close_modal" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 12" width="14" height="12"><g fill="none" fill-rule="evenodd" stroke="#fff"><path d="M1 0l12 12M13 0L1 12"></path></g></svg></span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <div class="text-center say_thanks">Un grand merci de la part de l'équipe d'Amica Travel !</div>
	      </div>
	    </div>
	  </div>
	</div>
	<div class="footer contain amica-travel-notification">
		<img alt="" class="img-fluid img-lazy fix-img-bottom-left" src="/img/img-fb/bg-left.png" data-pagespeed-url-hash="3000603361" data-pagespeed-onload="pagespeed.CriticalImages.checkImageForCriticality(this);" onload="var elem=this;if (this==window) elem=document.body;elem.setAttribute('data-pagespeed-loaded', 1)" data-pagespeed-loaded="1">
	</div>






	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.9/js/plugins/piexif.min.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.9/js/plugins/piexif.min.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.9/js/plugins/purify.min.js" type="text/javascript"></script>	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.9/js/fileinput.min.js"></script>
	<script>

		var tableOffset = $("#header_fix").offset().top;
		var tableheight = $("#header_fix").height();
		var $header = $("#header_fix thead").clone();
		var $fixedHeader = $("#header-fixed").append($header);
		var tourID = $('form').data('tour_id');
		$fixedHeader.width($("#header_fix").width());

		var FILES_UPLOAD = '';
		$(window).on("scroll", function() {
		    var offset = $(this).scrollTop();
		    if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
		        $fixedHeader.show();
		    }
		    if (offset < tableOffset || offset > (tableOffset + tableheight) ) {
		        $fixedHeader.hide();
		    }
		});
		var FORM = $('#fbForm');
		$("input[type=radio], input[type=checkbox]").after('<span></span>');
		$(document).on('click', 'input[type=radio]', function(e){
			var td = $(this).closest('td');
			if (td.length > 0) {
				var tr = $(this).closest('tr');
				$(tr).find('input[type=radio]').prop('checked', false);

				$(this).prop('checked', true);
				$(tr).find('input').val($(this).closest('td').index());
				var id_attr = $(this).closest('.tab-pane').prop('id');
				$('.nav-tabs li a[href="#'+id_attr+'"] div').addClass('bg-finish');
			}
			var div_q = $(this).closest('.question');
			if (div_q.length > 0) {
				$(div_q).find('input[type=radio]').prop('checked', false);
				$(this).prop('checked', true);
			}
			var wrap_table = $(this).closest('.wrap-table');
			if ($(wrap_table).find('h4 span').hasClass('require')) {
				$(wrap_table).find('h4 span').removeClass('has-error');
			}
			$(wrap_table).find('.help-block').remove();


		});
		$(document).on('click', 'input[type=checkbox]', function(e){
			var status_check = $(this).prop('checked');
			var tr = $(this).closest('tr');
			if (status_check) {
				$(tr).find('input').val($(this).closest('td').index());
			} else {
				$(tr).find('input').val('');
				var id_attr = $(this).closest('.tab-pane').prop('id');
			}
		});
		$('form').on('submit', function(event)
		{
		  	event.stopPropagation();
		    event.preventDefault();
	  		if (!validate()) {
				if(typeof $('.has-error').first().offset() !== 'undefined') {
					$(window).scrollTop($('.has-error').first().offset().top-200);
					$('.has-error').each(function(index, error){
						if (
							// $(error).closest('.wrap-table').find('.option').length > 0 
							// &&
							$(error).closest('.wrap-table').find('.help-block').length == 0
						){

							$(error).closest('.wrap-table').find('.question').append('<div class="help-block">Requise !</div>');
						}
					});
				}
				return false;
			}
			var data = new FormData();
			$.each(FILES_UPLOAD, function(key, value)
			{
				data.append(key, value);
			});
		    $.ajax({
		        url: '/tours/uploadfile',
		        type: 'POST',
		        data: data,
		        cache: false,
		        dataType: 'json',
		        processData: false,
		        contentType: false,
		        success: function(data, textStatus, jqXHR)
		        {
		        	if(typeof data.error === 'undefined')
		        	{
		        		submitForm(event, data);
		        	}
		        },
		        error: function(jqXHR, textStatus, errorThrown)
		        {
		        	console.log('ERRORS_VALIDATE_FOMR: ' + textStatus);
		        }
		    });
		});
		$("#thankModal").on('show.bs.modal', function () {
	            $('.modal-fix').css('marginTop', ($(window).height() / 3) - 90 );
	    });
		$('.require input[type="text"]').on('blur', function(){
			var question = $(this).closest('.question');
			if ($(question).find('.help-block').length == 0) {
				$(question).append('<div class="d-none help-block">Requise !</div>');
			}
			if ($(this).val().length == 0) {
				$(this).closest('.require').addClass('has-error');
				$(question).find('.help-block').removeClass('d-none');
			} else {
				$(this).closest('.require').removeClass('has-error');
				$(question).find('.help-block').addClass('d-none');;
			}
		});
		$('textarea').each(function () {
	  		this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
		}).on('input', function () {
			this.style.height = 'auto';
			this.style.height = (this.scrollHeight) + 'px';
		});

		
		$(document).on({
			mouseenter: function() {
				if (!$(this).closest('.file-preview-frame').hasClass('my-custom-frame-css')) {
					if ($(this).find('.close_file').length == 0)
					$(this).append('<span class="close_file"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 12" width="14" height="12"><g fill="none" fill-rule="evenodd" stroke="#fff"><path d="M1 0l12 12M13 0L1 12"></path></g></svg></span>');//<i class="fas fa-trash"></i>
					$(this).find('.close_file').show();
				}
			},
			mouseleave: function() {
				$(this).find('.close_file').hide();
			},
		}, '.kv-file-content');
		$(document).on('click', '.close_file', function(){
			var removed = $(this).closest('.file-preview-frame');
			if (removed.length > 0) {
				$(removed).find('.kv-file-remove').trigger('click');
			}
		});
	    $(document).on('click', '.my-custom-frame-css img', function(){
	    	$('#input-pr-rev').trigger('click');
	    });

	</script>
	    
	<script>
		$("#input-pr-rev").fileinput({
		    showRemove: false,
		    uploadAsync: true,
		    reversePreviewOrder: true,
		    initialPreviewAsData: true,
		    overwriteInitial: false,
		    allowedFileExtensions: ["jpg", "png", "gif"],
		    dropZoneEnabled: false,
		    showCaption: false,
		    showUpload:false,
		    showClose: false,
		    browseClass: 'browse_btn',
		    browseLabel: '',
		    maxFileCount: 10,
		    validateInitialCount: false,
		    // msgFilesTooMany: '',
		    msgUploadEmpty: "No valid data available for upload",
		    elCaptionContainer: 'caption_class',
		    maxFileSize: 10000,
		    msgSizeTooLarge: 'File "{name}" ({size} KB) exceeds maximum allowed upload size of {maxSize} KB. Please retry your upload!',
		    initialPreview: [
			    "/img/img-fb/btn_upload.jpg",
			],
			initialPreviewConfig: [
			    {
			        caption: '', 
			        width: '120px', 
			        url: '/img/img-fb/btn_upload.jpg', 
			        key: 101, 
			        frameClass: 'my-custom-frame-css',
			        frameAttr: {
			            // style: 'height:80px',
			            title: 'My Custom Title',
			        },
			        extra: function() { 
			            return {id: $("#id").val()};
			        },
			    }
			],
		    fileActionSettings: {
		    	// showRemove:false,
		    	showUpload: false,
		    	showZoom: false,
		    	uploadIcon: '',
		    	removeIcon: '<i class="fas fa-trash"></i>',
		    },
		    layoutTemplates: {
		    	footer: '<div class="file-thumbnail-footer">\n' +
				'    {actions}\n' +
				'</div>',
		    	preview: '<div class="file-preview">\n' +
		        '    <div class="file-preview-thumbnails d-flex flex-row flex-wrap">\n' +
		        '    </div>\n' +
		        // '    <div class="clearfix"></div>' +
		        // '    <div class="file-preview-status text-center text-success"></div>\n' +
		        '    <div class="kv-fileinput-error"></div>\n' +
		        '    </div>\n' +
		        '</div>',
		    }
		})
		.on('filebatchselected', function(event, files) {
			// console.log(files);
		    if (files.length == 10) {
		    	$('.my-custom-frame-css').hide();
		    }
		    if ($('.kv-fileinput-error').html() != '') {
		    	var list_id_error = [];
				$('.kv-fileinput-error').find('ul li').each(function(index, li){
					list_id_error.push($(li).data("file-id"));
				});
		    	if (list_id_error.length > 0) {
		    		$(list_id_error).each(function(id, item){
		    			$('#'+item).remove();
		    		});
		    	}
		    	$('.kv-fileinput-error').empty()
		    }
		    var files_select = [];
		    for(var i = 0; i < files.length; i++) {
		    	files_select.push(files[i]);
		    }
		    FILES_UPLOAD = files_select;
		}).on('fileremoved', function(event) {
		    $('.my-custom-frame-css').show();
		});

		function validate() {

				var option_chk_val = true;
				$('.require').each(function(index, item){
					if ($(item).find('input[type="text"]').length > 0) {
						if($(item).find('input[type="text"]').val() == '') {
							$(this).addClass('has-error');
							option_chk_val = false;
						}
					}
					else {
						var wrap_table = $(this).closest('.wrap-table');
						var checked = false;
						$(wrap_table).find('input[type=radio], input[type=checkbox]').each(function(id,chk){
							if ($(chk).prop('checked') == true) {
								checked = true;
							}
						});
						if (!checked) {
							$(this).addClass('has-error');
							option_chk_val = false;
						}
					}
				});
				if (!option_chk_val) {
					return false;
				}

				return true;
		}


		function limitText(limitField, limitCount, limitNum) {
			if (limitField.value.length > limitNum) {
				limitField.value = limitField.value.substring(0, limitNum);
			} else {
				limitCount.value = limitNum - limitField.value.length;
			}
		}
		function submitForm(event, data)
		{
			$form = $(event.target);
			
			// Serialize the form data
			var formData = $form.serialize();
			
			// You should sterilise the file names
			$.each(data.files, function(key, value)
			{
				formData = formData + '&uploads[]=' + value;
			});

			$.ajax({
				url: '/tours/feedback?id=' + tourID,
		        type: 'POST',
		        data: formData,
		        cache: false,
		        dataType: 'json',
		        success: function(data, textStatus, jqXHR)
		        {
		        	if(typeof data.error === 'undefined')
		        	{
		        		// Success so call function to process the form
		        		$('#thankModal').modal({backdrop: 'static', keyboard: false});
		        		console.log('SUCCESS: ' + data['success']);
		        	}
		        	else
		        	{
		        		// Handle errors here
		        		console.log('ERRORS_SUBMIT: ' + data.error);
		        	}
		        },
		        error: function(jqXHR, textStatus, errorThrown)
		        {
		        	// Handle errors here
		        	console.log('ERRORS_SUBMIT_FORM: ' + textStatus);
		        },
		        complete: function()
		        {
		        	// STOP LOADING SPINNER
		        }
			});
		}
	</script>
	</body>
</html>
