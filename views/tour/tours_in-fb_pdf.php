<?
use yii\helpers\Html;
$partCnt = 0;

?>
<!doctype html>
<html lang="fr">
	<head>
	<meta charset="utf-8">
	<title><?= $theTour['op_code'] ?> - QUESTIONNAIRE DE SATISFACTION - <?= $printName ?></title>
	<style>
	body {font-size:13px; color:#222; font-family: Arial, "sans-serif"}
	em {font-style:italic;}
	strong {font-weight:bold;}
	h2 {color:#000; padding:0 0 5px; margin-bottom:16px; font:bold 14px/18px Arial; border-bottom:1px solid #999;font-family: Arial, "sans-serif"}
	.has-bg {width:100%;}
	div.has-bg {margin-bottom:32px;}
	.h-100 {height:100px;}
	.h-200 {height:200px;}
	.h-400 {height:400px;}
	table {width:100%; border-collapse:collapse;}
	table, td, th {border:1px solid #000;}
	td, th {border:1px solid #666; padding:5px;}
	th {font-weight:bold;}
	.ta-c {text-align:center;}
	p, table {margin-bottom:16px;}
	h1 {font:bold 20px/22px Arial;}
	div.wrap-table table { border: 0;}
	div.wrap-table table td {border: 0;}
	</style>
</head>
<body style="font-size:13px; color:#222; font-family: Arial, 'sans-serif'">
	<div style="max-width:800px;">
		<div style="width:260px; float:left; margin:0 32px 32px 0;">
			<? if ($theForm['logoName'] != 'none') { ?>
			<img src="<?= $printLogo ?>" style="margin:0; display:block; padding:0; max-width:250px; max-height:168px;">
			<? } // if not none ?>
			<h1 style="font:bold 20px/22px Arial;">
				<span style="font-size:16px"><?=fUTF8::upper($printName)?></span>
				<br />QUESTIONNAIRE DE SATISFACTION
			</h1>
		</div>
		<p><strong>Madame, Monsieur,</strong></p>
		<p>L’équipe <strong><?= $printName ?></strong> vous souhaite la bienvenue !</p>
		<p>Nous tenons, tout d’abord, à vous remercier de votre confiance et de nous avoir choisis pour l’organisation de votre voyage.</p>
		<p>Afin d’améliorer constamment la qualité de nos prestations, nous vous serions reconnaissant de bien vouloir nous faire part de vos appréciations en répondant au questionnaire suivant.</p>
		<p>Vous remerciant pour votre participation, nous vous disons à très bientôt !</p>
		<table style="clear:both; margin:0;">
			<tr>
				<td width="275">Tour code: <strong><?= $theTour['op_code'] ?></strong> / ID <strong><?= $theTour['id'] ?></strong></td>
				<td width="">Votre nom et prénom: <strong><?= $theForm['paxName'] ?></strong></td>
			</tr>
		</table>

		<div style="clear:both; margin-bottom:32px;"></div>
	<?php if ($theTour['bookings'][0]['case']['is_b2b'] == 'no') {?>
		<?php foreach ($questions as $q => $q_content): ?>
			<?php if ($q != 'q2' && $q != 'q3'): ?>
				<?php if ($q == 'q1'): ?> <h2>Partie <?= ++ $partCnt ?> : REMARQUES SUR LES PRESTATIONS</h2> <?php endif ?>
				<?php if ($q == 'q4'): ?> <h2>Partie <?= ++ $partCnt ?> : D’AUTRES COMMENTAIRES, IMPRESSIONS SUR LE VOYAGE ET SUR <?= strtoupper($printName) ?></h2> <?php endif ?>
				<p><?= $q_content['title']?> ?</p>
				<table class="table table-condensed table-bordered">
					<tr>
						<th class="ta-c" width=""></th>
						<?php foreach ($q_content['options_value'] as $op_v): ?>
							<th class="ta-c"><?=$op_v?></th>
						<?php endforeach ?>
					</tr>
					<? foreach ($q_content['options'] as $op) { ?>
					<tr>
						<td class="ta-c"><?= $op ?></td>
						<?= str_repeat('<td></td>', count($q_content['options_value'])) ?>
					</tr>
					<? } ?>
				</table>
				<?php if ($q_content['note_q'] != ''): ?>
					<p><?= $q_content['note_q']?> ?</p>
					<div class="wrap-table">
						<table class="">
						<? for ($tr = 1; $tr <= 6 ; $tr++) { ?>
							<tr >
								<td style="padding-bottom: 20px; border-bottom: 1px dotted #000">
								</td>
							</tr>
						<? } ?>
						</table>
					</div>
				<?php endif ?>
			<?php endif ?>
			<?php if ($q == 'q2' || $q == 'q3'): ?>
				<?php if ($q == 'q2'): ?> <h2>Partie <?= ++ $partCnt ?> : REMARQUES SUR LE GUIDE</h2> <?php endif ?>
				<?php if ($q == 'q3'): ?> <h2>Partie <?= ++ $partCnt ?> :  EVALUATION DU TRANSPORT ROUTIER</h2><?php endif ?>
				<p><?= $q_content['title']?> ?</p>
				<?php
					$num_tables = ($q == 'q2')? $theForm['guideNames'] : $theForm['driverNames'];
				?>
				<? for ($i=1; $i <= $num_tables ; $i++) {?>
				<table>
					<tr>
						<th class="ta-c" width="" style="text-align: left;"><strong><?= ($q == 'q2')? 'Guide': 'Chauffeur'?>: </strong></th>
						<?php foreach ($q_content['options_value'] as $op_v): ?>
							<th class="ta-c" align="center"><?=$op_v?></th>
						<?php endforeach ?>
					</tr>
					<? foreach ($q_content['options'] as $op) { ?>
					<tr>
						<td class="ta-c"><?= $op ?></td>
						<?= str_repeat('<td></td>', count($q_content['options_value'])) ?>
					</tr>
					<? } ?>
				</table>
				<?}?>
				<p><?= $q_content['note_q']?> ?</p>
				<div class="wrap-table">
					<table class="">
					<? for ($tr = 1; $tr <= 6 ; $tr++) { ?>
						<tr >
							<td style="padding-bottom: 20px; border-bottom: 1px dotted #000">
							</td>
						</tr>
					<? } ?>
					</table><br>
				</div>
			<?php endif ?>
		<?php endforeach ?>
	<?php }?>
	<?php if ($theTour['bookings'][0]['case']['is_b2b'] == 'no') {?>
	<p>Pourriez-vous nous attribuer une note sur 10 pour votre voyage ? _________</p>
	<br />&nbsp;
	<? } ?>
	<p>Date : ______________________________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Signature :__________________________________</p>
	<br />&nbsp;

	<? if (in_array($theForm['logoName'], ['us', 'both'])) { ?>
	<p><em>* En fonction du niveau de satisfaction des voyageurs, Amica Travel accorde une prime au guide et au chauffeur. Cette prime ne remplace pas leur rémunération qui est fixe. Nous vous prions donc de rester le plus objectif possible dans votre appréciation.</em></p>
	<br />&nbsp;
	<? } ?>

	<div class="ta-c">
		<em><strong>La réussite de votre voyage pour nous est essentielle !</strong></em>
		<br />&nbsp;
		<hr />
		<br /><strong><?= \fUTF8::upper($printName)?></strong>
		<? if (in_array($theForm['logoName'], ['us', 'both'])) { ?>
		<br />Addresse : 27 Rue Nguyen Truong To, Nikko Building - 3è étage, Ba Dinh, Hanoi, Vietnam
		<br />Tel : (+84 24) 6273 4455 • Fax : (+84 24) 6273 3504
		<br />Email : <strong>info@amica-travel.com</strong> • Site web : <strong>www.amica-travel.com</strong>
		<? } ?>
	</div>
	<!-- Feedback form end -->
</div>
</body>
</html>