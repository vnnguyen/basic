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
	h2 {color:#000; padding:0 0 5px; margin-bottom:16px; font:bold 16px/20px Arial; border-bottom:1px solid #999;}
	.has-bg {width:100%; background:url(<?= DIR ?>images/dotted-line-bg.png) left -20px repeat;}
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
		<p>Nous tenons, tout d’abord, à vous remercier de votre confiance et de nous avoir choisis pour l’organisation de votre voyage.</p>
		<p>Afin d’améliorer constamment la qualité de nos prestations, nous vous serions reconnaissant de bien vouloir nous faire part de vos appréciations en répondant au questionnaire suivant.</p>
		<p>Vous remerciant pour votre participation, nous vous disons à très bientôt !</p>
		<table style="clear:both; margin:0;">
			<tr>
				<td width="275">Tour code: <strong><?= $theTour['op_code'] ?></strong> / ID <strong><?= $theTour['id'] ?></strong></td>
				<td width="">Votre nom et prénom: <strong><?= $theForm['paxName'] ?></strong></td>
			</tr>
		</table>
		
		<div style="clear:both; margin-bottom:32px;"></div>

		<h2>Partie <?= ++ $partCnt ?> : REMARQUES SUR LES PRESTATIONS</h2>
		<p>Globalement, comment évaluez-vous les prestations suivantes ?</p>
		<table class="table table-condensed table-bordered">
			<tr>
				<td class="ta-c" width="28%"></td>
				<td class="ta-c" width="18%">Insatisfaisant</td>
				<td class="ta-c" width="18%">Moyen</td>
				<td class="ta-c" width="18%">Bien</td>
				<td class="ta-c" width="18%">Très bien</td>
			</tr>
			<? foreach (['Hôtels', 'Chez l’habitant', 'Repas', 'Bateaux', 'Train', 'Véhicule'] as $item) { ?>
			<tr>
				<td class="ta-c"><?= $item ?></td>
				<?= str_repeat('<td></td>', 4) ?>
			</tr>
			<? } ?>
		</table>
		<h2>Partie <?= ++ $partCnt ?> : REMARQUES SUR LES GUIDES ET CHAUFFEURS *</h2><?
$guideNames = explode(',', $theForm['guideNames']);
if ($theForm['guideNames'] != '' && !empty($guideNames)) { ?>
		<p>Quel est votre niveau de satisfaction en rapport avec les compétences du guide ?</p><?
	foreach ($guideNames as $guideName) {
		$guideName = trim($guideName);
		if ($guideName != '') { ?>
		<table class="">
			<tr>
				<td class="ta-c"><? if (strtolower($guideName) != 'yes') { ?><strong>Guide : <?= Html::encode($guideName) ?></strong><? } ?></td>
				<td class="ta-c" width="18%">Insatisfaisant</td>
				<td class="ta-c" width="18%">Acceptable</td>
				<td class="ta-c" width="18%">Satisfaisant</td>
				<td class="ta-c" width="18%">Très satisfaisant</td>
			</td>
<?
			foreach (['Niveau de français', 'Connaissances', 'Capacité d’organisation', 'Serviabilité (à l’écoute, disponible, flexible)', 'Capacité d’assurer le contact du voyageur avec les habitants et la vie locale'] as $item) {
?>
			<tr style="height:32px;">
				<td class="ta-c"><?= $item ?></td>
				<?= str_repeat('<td></td>', 4) ?>
			</tr>
<?
			}
?>
		</table>

		<p>Est-ce que vous avez des commentaires particuliers concernant les prestations du guide ?</p>
		<div class="has-bg h-200"></div>
<?
		}
	}
}

$driverNames = explode(',', $theForm['driverNames']);
if ($theForm['driverNames'] != '' && !empty($driverNames)) { ?>
		<p>Quel est votre niveau de satisfaction en rapport avec les prestations du chauffeur ?</p><?
	foreach ($driverNames as $driverName) {
		$driverName = trim($driverName);
		if ($driverName != '') { ?>
		<table>
			<tr>
				<td class="ta-c"><? if (strtolower($driverName)!= 'yes') { ?><strong>Chauffeur : <?= Html::encode($driverName) ?></strong><? } ?></td>
				<td class="ta-c" width="18%">Insatisfaisant</td>
				<td class="ta-c" width="18%">Acceptable</td>
				<td class="ta-c" width="18%">Satisfaisant</td>
				<td class="ta-c" width="18%">Très satisfaisant</td>
			</td><?
			foreach (['Professionnalisme dans la conduite', 'Serviabilité', 'Concentration', 'Relationnel (avec le guide et les voyageurs)', 'Propreté du véhicule'] as $item) { ?>
			<tr style="height:32px;">
				<td class="ta-c"><?= $item ?></td>
				<?= str_repeat('<td></td>', 4) ?>
			</tr><?
			} ?>
		</table>
		<p>Est-ce que vous avez des commentaires particuliers concernant les prestations du chauffeur ?</p>
		<div class="has-bg h-200"></div><?
		}
	}
} ?>
		<h2>Partie <?= ++ $partCnt ?> : D’AUTRES COMMENTAIRES, IMPRESSIONS SUR LE VOYAGE ET SUR <?= strtoupper($printName) ?></h2>
		<p>Comment évaluez-vous les prestations et les services d’<?= $printName ?> dans le <?= $theForm['regionName'] ?>? Avez-vous des commentaires ou recommandations détaillés ?</p>
		<div class="has-bg h-400"></div>
		<p>Pourriez-vous nous attribuer une note sur 10 pour votre voyage ? _________</p>
		<br />&nbsp;
		<p>Date : ______________________________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Signature :__________________________________</p>
		<br />&nbsp;

		<? if (in_array($theForm['logoName'], ['us', 'both'])) { ?>
		<p><em>* En fonction du niveau de satisfaction des voyageurs, Amica Travel accorde une prime au guide et au chauffeur. Cette prime ne remplace pas leur rémunération qui est fixe. Nous vous prions donc de rester le plus objectif possible dans votre appréciation.</em></p>
		<br />&nbsp;
		<? } ?>

		<div class="ta-c">
			<em><strong>La réussite de votre voyage pour nous est essentielle !</strong></em>
			<br />&nbsp;
			<hr />
			<br /><strong><?= \fUTF8::upper($printName)?></strong>
			<? if (in_array($theForm['logoName'], ['us', 'both'])) { ?>
			<br />Addresse : 27 Rue Nguyen Truong To, Nikko Building - 3è étage, Ba Dinh, Hanoi, Vietnam
			<br />Tel : (+84 4) 6273 4455 • Fax : (+84 4) 6273 3504
			<br />Email : <strong>info@amica-travel.com</strong> • Site web : <strong>www.amica-travel.com</strong>
			<? } ?>
			<br />&nbsp;<br /><span style="font-style:italic; color:#999; font-size:10px;">--- Version 151010 | Printed on <?= date('j/n/Y \a\t H:i T')?> by <?= Yii::$app->user->identity->name ?> ---</span>
		</div>
		<!-- Feedback form end -->
	</div>
</body>
</html>
