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
	body {font:13px/16px Arial; color:#222;}
	em {font-style:italic;}
	strong {font-weight:bold;}
	h2 {background:#ccc; color:#000; padding:5px; margin-bottom:16px; font:bold 16px/20px Arial; border:1px solid #999;}
	.has-bg {width:100%; background:url(<?= DIR ?>images/dotted-line-bg.png) left -20px repeat;}
	div.has-bg {margin-bottom:32px;}
	.h-100 {height:100px;}
	.h-500 {height:500px;}
	table {width:100%; xborder-collapse:collapse;}
	table, td, th {border:1px solid #000;}
	td, th {border:1px solid #666; padding:5px;}
	th {font-weight:bold;}
	.ta-c {text-align:center;}
	p, table {margin-bottom:16px;}
	h1 {font:bold 20px/22px Arial;}
	</style>
</head>
<body>
	<div style="max-width:800px;">
		<div style="width:260px; float:left; margin:0 32px 32px 0;">
			<? if ($theForm['logoName'] != 'none') { ?>
			<img src="<?= $printLogo ?>" style="margin:0; display:block; padding:0; max-width:250px; max-height:168px;">
			<? } // if not none ?>
			<h1>
				<span style="font-size:16px"><?=fUTF8::upper($printName)?></span>
				<br />QUESTIONNAIRE DE SATISFACTION (<?= $theForm['regionName'] ?>)
			</h1>
		</div>
		<p><strong>Madame, Monsieur,</strong></p>
		<p>L’équipe <strong><?= $printName ?></strong> vous souhaite la bienvenue !</p>
		<p>Nous tenons tout d’abord à vous remercier de votre confiance et de nous avoir choisis pour l’organisation de votre voyage.</p>
		<p>Afin de connaître vos impressions, vos satisfactions et vos regrets, nous vous proposons de répondre au questionnaire qui suit. Ce dernier réservé pour nos prestations dans le <?= $theForm['regionName'] ?> nous permet de mieux comprendre vos souhaits et d’améliorer notre service.</p>
		<p>Merci de prendre 2 minutes chaque jour pour nous écrire vos remarques. Toutes vos observations sont précieuses.</p>
		<p>Nous vous remercions pour votre participation et à très bientôt !</p>
		<table style="clear:both; margin:0;">
			<tr>
				<td width="275">Tour code: <strong><?= $theTour['op_code'] ?></strong> / ID <strong><?= $theTour['id'] ?></strong></td>
				<td width="">Votre nom et prénom: <strong><?= $theForm['paxName'] ?></strong></td>
			</tr>
		</table>
		
		<div style="clear:both; margin-bottom:32px;"></div>

		<h2>Partie <?= ++ $partCnt ?> : REMARQUES SUR VOTRE PROGRAMME</h2>
		<p>Vous trouverez ici votre itinéraire organisé avec <?= $printName ?><?= $theForm['logoName'] == 'both' ? ' - <strong>Agence réceptive de '.$theCompany['name'].'</strong>' : ''?>. Nous vous remercions de prendre quelques minutes à la fin de chaque journée pour évaluer les services et activités réalisées (y compris: les hôtels (ou bateaux), les repas, les activités, le rythme de la journée)</p>
		<table id="tourdays">
			<thead>
				<tr>
					<th width="20%">Itineraire / Hotel</th>
					<th width="80%">VOS REMARQUES<br />(itinéraire proposé, les hôtels, les repas, les activités, le rythme de la journée etc.)</th>
				</tr>
			</thead>
			<tbody>
<?
$dayIdList = explode(',', $theTour['day_ids']);
$cnt = 0;
foreach ($dayIdList as $di) {
	foreach ($theTour['days'] as $day) { 
		if ($day['id'] == $di) {
			$cnt ++;
			if ($cnt >= $printDays[0] && $cnt <= $printDays[1]) {
				$ngay = date('d/m/Y', strtotime($theTour['day_from'].' + '.($cnt - 1).'days'));
?>
				<tr class="h-100">
					<td class="ta-c">
						<?= $ngay ?>
						<br /><strong><?= $day['name']?></strong>
						<br />&nbsp;
						<br />
				<?
				/*
				foreach ($theDV as $dv) {
					if (date('d/m/Y', strtotime($dv['dvtour_day'])) == $ngay) {
						echo $dv['oppr'];
						break;
					}
				}*/
				?>
					</td>
					<td class="has-bg h-100">&nbsp;</td>
				</tr>
<?
			} // if in print range
		} // if day id
	} // foreach days
} // foreach day id
?>
			</tbody>
		</table>

<?
$guideNames = explode(',', $theForm['guideNames']);
if ($theForm['guideNames'] != '' && !empty($guideNames)) {
?>
		<h2>Partie <?= ++ $partCnt ?> : REMARQUES SUR LE GUIDE</h2>
		<p>Globalement, quel est votre niveau de satisfaction concernant le service de votre guide ?</p>
		<div class="has-bg h-100"></div>
<?
	foreach ($guideNames as $guideName) {
		$guideName = trim($guideName);
		if ($guideName != '') {
?>
		<div><strong>GUIDE : <?= strtolower($guideName) == 'yes' ? '' : Html::encode($guideName) ?></strong></div>
		<table class="">
			<tr>
				<th class="ta-c">Vos remarques</th>
				<td class="ta-c" width="15%">Insatisfaisant</td>
				<td class="ta-c" width="15%">Moyen</td>
				<td class="ta-c" width="15%">Acceptable</td>
				<td class="ta-c" width="15%">Satisfaisant</td>
				<td class="ta-c" width="15%">Excellent</td>
			</td>
<?
			foreach (['Niveau de français', 'Serviabilité + Adaptabilité', 'Connaissances', 'Ponctualité', 'Relationnel + écoute'] as $item) {
?>
			<tr style="height:32px;">
				<td class="ta-c"><?= $item ?></td>
				<?= str_repeat('<td></td>', 5) ?>
			</tr>
<?
			}
?>
		</table>
<?
		}
	}
}
?>

<?
$driverNames = explode(',', $theForm['driverNames']);
if ($theForm['driverNames'] != '' && !empty($driverNames)) {
?>
		<h2>Partie <?= ++ $partCnt ?> : EVALUATION DU TRANSPORT ROUTIER</h2>
		<p>Globalement, quel est votre niveau de satisfaction concernant de la qualité du véhicule et du chauffeur ?</p>
		<div class="has-bg h-100"></div>
<?
	foreach ($driverNames as $driverName) {
		$driverName = trim($driverName);
		if ($driverName != '') {
?>
		<div><strong>CHAUFFEUR : <?= strtolower($driverName) == 'yes' ? '' : Html::encode($driverName) ?></strong></div>
		<table class="">
			<tr>
				<th class="ta-c">Vos remarques</th>
				<td class="ta-c" width="15%">Insatisfaisant</td>
				<td class="ta-c" width="15%">Moyen</td>
				<td class="ta-c" width="15%">Acceptable</td>
				<td class="ta-c" width="15%">Satisfaisant</td>
				<td class="ta-c" width="15%">Excellent</td>
			</td>
<?
			foreach (['Véhicule', 'Serviabilité du Chauffeur', 'Professionnalisme du Chauffeur'] as $item) {
?>
			<tr style="height:32px;">
				<td class="ta-c"><?= $item ?></td>
				<?= str_repeat('<td></td>', 5) ?>
			</tr>
<?
			}
?>
		</table>
<?
		}
	}
}
?>

		<h2>Partie <?= ++ $partCnt ?> : COMMENTAIRES ET RECOMMANDATIONS</h2>
		<p>Comment évaluez-vous les prestations et les services d’<?= $printName ?> dans le <?= $theForm['regionName'] ?>? Avez-vous des commentaires ou recommandations détaillés ?</p>
		<div class="has-bg h-500"></div>

		<p>Date: ______________________________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Signature:__________________________________</p>
		<br />&nbsp;
		<br />&nbsp;
		<br />&nbsp;
		<br />&nbsp;
		<br />&nbsp;
		<div class="ta-c">
			<em><strong>La réussite de votre voyage pour nous est essentielle !</strong></em>
			<br />&nbsp;
			<hr />
			<br /><strong><?= \fUTF8::upper($printName)?></strong>
			<? if (in_array($theForm['logoName'], ['us', 'both'])) { ?>
			<br />27 Nguyen Truong To, Nikko Building - 3è étage, Ba Dinh, Hanoi, Vietnam
			<br />Tel: (+84 4) 62734455 • Fax: (+84 4) 62733504
			<br />Email: <strong>info@amica-travel.com</strong> • Web: <strong>www.amica-travel.com</strong>
			<? } ?>
			<br />&nbsp;<br /><span style="font-style:italic; color:#999; font-size:10px;">--- This feedback form was prepared on <?=date('Y-m-d H:i:s T')?> by <?= Yii::$app->user->identity->name ?> ---</span>
		</div>
		<!-- Feedback form end -->
	</div>
</body>
</html>
