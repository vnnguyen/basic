<?php
use yii\helpers\Html;
?>
<?php
$this->registerCssFile('https://use.fontawesome.com/releases/v5.2.0/css/all.css');
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.9/css/fileinput.min.css');
?>
<?php
$this->registerCss('
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
    .browse_btn {background:url(/img/btn_upload.jpg) no-repeat center; color: #b6b6b6; border-radius: 11px; height: 106px; width: 106px; margin-top: 10px;
    }
    .my-custom-frame-css:hover {
        cursor: pointer;
    }
    .file-preview-image-btn {background:url(/img/btn_upload.jpg) no-repeat center;}
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
');
?>
<?php
 $css = <<<TXT
    /* font face */
    @font-face {
      font-family: "LatoLatin-Regular";
      font-style: normal;
      src: url("/font/LATOLATIN/LatoLatin-Regular.ttf") format("truetype");
    }
    @font-face {
      font-family: "LatoLatin-Bold";
      font-style: normal;
      src: url("/font/LATOLATIN/LatoLatin-Bold.ttf") format("truetype");
    }
    @font-face {
      font-family: "LatoLatin-Semibold";
      font-style: normal;
      src: url("/font/LATOLATIN/LatoLatin-Semibold.ttf") format("truetype");
    }


    body {color:#222; font: 15.5px/1.5}
    .card { width: 100%}
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
    select { border-radius: 5px; }
    /*table*/

    table td{padding:8px; padding-bottom: 0!important;}
    table td:first-child {padding-left: 0!important;}
    .ta-c {text-align:center;}
    table th.not_fix_width { min-width: 15%;}
    .table-bordered, .table-bordered td, .table-bordered th {
        border: 0 none;
        border-bottom: 1px solid #e6e3d9!important;
    }
    .bg-finish{background-color: #e6e3d9 !important; border-radius: 10px 10px 0 0; -moz-border-radius-topleft: 2px;}
    td { vertical-align: middle !important;}
    .wrap-name {padding: 8px 4px; }
    #header-fixed {position: fixed; top: 0px; display:none; background-color:white; }
    .wrap-table {padding-bottom: 40px;}
    .wrap-table h4 span {   display: inline-block; }
    .wrap-table h4 span:first-letter {text-transform:uppercase;}
    .text-left span {display: inline-block;}
    .wrap-table h4 span.require:after {content: " *"; color: #e75a26; }
    h5 span:first-letter {text-transform:uppercase;}
    .text-left span {display: block}
    .span-fix {padding-left: 20%;}

    .question .option {margin-bottom: 13px;}
    .question .option:last-child {margin-bottom: 0;}
    .question .option label { margin-right: 0.6rem; font-color: #b1b1b1; line-height: 15.5px}
    .wrap-row label:after {content: " *"; color: #e75a26; }

    .table td label {margin-right: 0;}

    .table td {padding-top: 6px; font: 15.5px/1.5 "LatoLatin-Regular",sans-serif;}
    .table.table-borderless td {padding-bottom: 6px!important;}
    .table thead th {padding-bottom:8px!important; font-weight:bold!important;}
    .wrap-table h4{font: 15.5px "LatoLatin-Semibold",sans-serif; /*font-color: #201f1f;*/ margin-bottom: 13px; line-height: 15.5px}
    th.fix-width ~ th { width: 12%; padding-left: 0; padding-right: 0;}
    .mb-fix { margin-bottom: 13px; }
    table th {padding-top: 12px; font-weight: normal; line-height: 15.5px}
    th.q6_fix {font: 15.5px 'LatoLatin-Semibold',sans-serif; padding-left: 0!important; padding-top: 0; width: 61%; padding-bottom: 0!important; }

    #header_fix th {padding-top: 0;}
    .h4-title {font: 15.5px "LatoLatin-Semibold",sans-serif; text-transform: capitalize; height: 53px; line-height: 53px; padding-right: 30%; width: 42%;}
    .fix-col {text-align: left; padding-left: 3px;}
    .amc-column h2 {color:#000; font:bold 22px "LatoLatin-Regular"; font-family: "LatoLatin-Regular",sans-serif; line-height: 22px; padding-top: 40px; padding-bottom: 0px; margin-bottom: 40px}

        .amc-column h2 span {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: #e46d00;
            border-radius: 50%;
            padding: 5px;
            vertical-align: middle;
            margin-right: 10px;
        }
        .amc-column:nth-of-type(1) h2 span {
            /*background:#000;*/
            background: #e46d00 url(/img/icon1.png) center no-repeat;
        }
        .amc-column:nth-of-type(2) h2 span {
            background: #e46d00 url(/img/icon2.png) center no-repeat;
        }
        .amc-column:nth-of-type(3) h2 span {
            background: #e46d00 url(/img/icon3.png) center no-repeat;
        }
        .amc-column:nth-of-type(4) h2 span {
            background: #e46d00 url(/img/icon4.png) center no-repeat;
        }
TXT;
$this->registerCss($css);
?>
<?php
$version = $versions['20072018'];
?>
<?php
$info = $content_q['info'];
?>
<div class="card">
    <div class="card-body">
    	<form id="fbForm" method="POST" accept-charset="utf-8" enctype="multipart/form-data" data-tour_id="<?= $theTour['id']?>">
    	<!-- section 1 -->
		<div class="amc-column">
            <div class="feedback_scores">
                <div> <strong>Tổng điểm feedback:</strong> <span class='scores'> <?= $scores['totals']?></span> </div>
                <div> <strong>Điểm hướng dẫn viên</strong></div>
                <?php if(isset($scores['guides'])) {
                    foreach ($scores['guides'] as $uid => $score){
                    ?>
                    <div> <strong>- <?= $uid?>:</strong> <span class='scores'> <?= $score?></span> </div>
                    <?php }
                } else {
                ?>
                    <div> <strong>- Không có thông tin Hướng dẫn</strong></div>
                <?php } ?>
                <div> <strong>Điểm Lái xe</strong></div>
                <?php if(isset($scores['drives'])) {
                    foreach ($scores['drives'] as $uid => $score){
                    ?>
                    <div> <strong>- <?= $uid?>:</strong> <span class='scores'> <?= $score?></span> </div>
                <?php }
                } else {
                ?>
                    <div> <strong>- Không có thông tin Lái xe</strong></div>
                <?php } ?>
            </div>
			<h2> <span></span> Informations générales</h2>
			<div class="wrap-table">
				<!-- info -->
				<div class="row wrap-row ">
					<div class="col-sm-2 ">
						<label class="mb-fix"> Civilité</label>
						<select id="contactform-prefix" class="form-control fix-arrow input-fullname" name="info[prefix]">
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
    	<!-- section 2 -->
		<div class="amc-column" style="background-color: #ffffff">
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
					<div class="float-left h4-title"><?= $q['title'] . 's'?></div>
					<ul class="nav nav-tabs">
					<?php
						if ($q['title'] == 'guide') { $title = 'guide'; }
						if ( $q['title'] == 'chauffeur') { $title = 'driver'; }
						$arr_dt = [];
						$t = $title . 's';
						foreach ($theTour[$t] as $index => $user) {
							if ($user[$title . '_user_id'] == 0) {
								continue;
							}
							$jn1 = date('j/n', strtotime($user['use_from_dt']));
                            $jn2 = date('j/n', strtotime($user['use_until_dt']));

                            if ($jn1 == $jn2) {
                                $dtUse = $jn1;
                            } else {
                                if (strrchr($jn1, '/') == strrchr($jn2, '/')) {
                                    $dtUse = date('j', strtotime($user['use_from_dt'])) . ' - ' . $jn2;
                                } else {
                                    $dtUse = $jn1 . ' - ' . $jn2;
                                }
                            }
							$arr_dt[$user[$title . '_user_id']][] = $dtUse;

						}
						$arr_uid = [];
						foreach ($theTour[$t] as $index => $user) {
							if (in_array($user[$title . '_user_id'], $arr_uid)) {
								continue;
							}

							if ($user[$title . '_user_id'] == 0) {
								$jn1 = date('j/n', strtotime($user['use_from_dt']));
	                            $jn2 = date('j/n', strtotime($user['use_until_dt']));

	                            if ($jn1 == $jn2) {
	                                $dateUse = $jn1;
	                            } else {
	                                if (strrchr($jn1, '/') == strrchr($jn2, '/')) {
	                                    $dateUse = date('j', strtotime($user['use_from_dt'])) . ' - ' . $jn2;
	                                } else {
	                                    $dateUse = $jn1 . ' - ' . $jn2;
	                                }
	                            }
							} else {
								$arr_uid[] = $user[$title . '_user_id'];
								$dateUse = implode(', ', $arr_dt[$user[$title . '_user_id']]);
							}
							$gender = 'Ms';
							$mark = $index + 1;
							$name = '';

							if ($t == 'guides') {
                                if (isset($user['guide']['gender']) && $user['guide']['gender'] == 'male') {
                                    $gender = 'Mr';
                                }
                                // $user['guide_name'] = preg_replace('/(-)? (\d)*/', '', $user['guide_name']);
                                $user['guide_name'] = preg_replace('/(-)?(\d)*/', '', $user['guide_name']);
                                $name = isset($user['guide']['lname']) ? $gender. '. ' .$user['guide']['lname']: $user['guide_name'];
                                $image = isset($user['guide']['image']) && $user['guide']['image'] != '' ? $user['guide']['image'] : 'https://my.amicatravel.com/assets/img/user_256x256.png';
                            }
                            if ($t == 'drivers') {
                                if (isset($user['driver']['gender']) && $user['driver']['gender'] == 'male') {
                                    $gender = 'Mr';
                                }
                                // $user['driver_name'] = preg_replace('/(-)? (\d)*/', '', $user['driver_name']);
                                $user['driver_name'] = str_replace(['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '.', '-'], ['', '', '', '', '', '', '', '', '', '', '', '', ], $user['driver_name']);
                                $user['driver_name'] = 'Mr. '.strrchr(trim($user['driver_name']), ' ');
                                $name = isset($user['driver']['lname']) ? $gender. '. ' .$user['driver']['lname']: $user['driver_name'];
                                $image = isset($user['driver']['image']) && $user['driver']['image'] != '' ? $user['driver']['image'] : 'https://my.amicatravel.com/assets/img/user_256x256.png';
                            }
                            if (substr($image, 0, 1) == '/') {
                                $image = 'https://my.amicatravel.com'.$image;
                            }
						?>
						<li class="nav-item"><a href="#<?= $t.$mark?>" style="text-transform: capitalize; line-height: 20px" class="nav-link text-center <?= ($index == 0)? 'active show': ''?>" data-toggle="tab"><div class="wrap-name">
                            <div><img src="<?= $image ?>" class="rounded-circle" style="width:64px; height:64px;"></div>
                            <div><?= $name?></div>
                            <div><span class="text-center" style="display: block; color: #919191; font-size:90%"><?= $dateUse?></span></div>
                        </div></a> </li>
                        <?php } ?>
                    </ul>

					<div class="tab-content">
					<?php foreach ($theTour[$t] as $i_user => $user) {
						$mark = $i_user + 1;
						?>
						<div class="tab-pane fade <?= ($i_user == 0)? 'active show': ''?>" id="<?= $t.$mark?>">
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
								$current_v = isset($content_q[$num_q][$op][$user['id']])?$content_q[$num_q][$op][$user['id']] : '';
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
										<label class="text-center">
											<input class="control-info" type="radio" <?= (isset($content_q[$num_q][$op][$user['id']]) && $content_q[$num_q][$op][$user['id']] == $i)? 'checked': '' ?> ></span>
										</label>
										</div>
									</td>
								<?}?>
								<?= Html::input('hidden', $num_q.'['.$op.']['.$user['id'].']', $current_v, [])?>
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
						<table class="table table-condensed  mb-0 " id="<?=($num_q == 'q2')? 'header_fix': ''?>">
							<?php if (count($q['options']) > 1 && strtolower($q['options'][0]) != 'autre') { ?>
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
												<label class="<?= ($num_q == 'q6' && $i == 2 )? 'fix-col': 'text-center';?>">
													<input class="control-info" type="radio"  <?= $checked ?>  ></input>
												</label>
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
    	<!-- section 3 -->
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
    	<!-- section 4 -->
		<div class="amc-column">
			<h2><span></span> Votre témoignage</h2>
			<div class="wrap-table question">
				<h4>N'hésitez pas à partager votre retour d'expériences et vos conseils personnalisés via ce formulaire
				</h4>
				<div class="wrap_form">

					<div class="comment">
						<br>
						<div class="form-group op_fix">
							<label class="mb-fix">Titre de votre témoignage</label><input type="text" class="form-control" maxlength="60" name="contact_1" value="<?= isset($content_q['contact_1'])? $content_q['contact_1']: ''?>">
						</div>
						<div class="form-group op_fix">
							<label class="mb-fix">Votre témoignage</label><textarea name="contact_2"  class="form-control" maxlength="1000"><?= isset($content_q['contact_2'])? $content_q['contact_2']: ''?></textarea>
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
							<input type="text" class="form-control" name="contact_3" value="<?= isset($content_q['contact_3'])? $content_q['contact_3']: ''?>">
						</div>
					</div>
				</div>
			</div>
		</div>
    	</form>
    </div>
</div>

<?php

$js = <<<'TXT'
$("input, select, option, textarea").prop('disabled',true);
$("input[type=radio], input[type=checkbox]").after('<span></span>');
$(document).ready(function(){
    $(document).on('click', '.my-custom-frame-css img', function(){
        $('#input-pr-rev').trigger('click');
    });
    //     var el4 = $('#input-pr-rev'), initPlugin = function() {
    //         el4.fileinput({previewClass:''});
    //     };

    // // initialize plugin
    // initPlugin();

    $("#input-pr-rev").fileinput('destroy');
    $("#input-pr-rev").fileinput({
        showRemove: false,
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
            $data_img
        ]
    });

});
TXT;
$data_img = '';
$link_files = explode(', ', $content_q['link_files']);

foreach ($link_files as $link) {
    if ($link == '') {
        continue;
    }
    $data_img .= "'/". $link . "',";
}
if ($data_img == '') {
    $data_img = "'" . 'https://my.amicatravel.com/assets/img/user_256x256.png' . "'";
}

$js = str_replace('$data_img', $data_img, $js);
$this->registerJs($js);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.9/js/plugins/piexif.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.9/js/plugins/purify.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.9/js/fileinput.min.js', ['depends'=>'app\assets\MainAsset']);
?>
