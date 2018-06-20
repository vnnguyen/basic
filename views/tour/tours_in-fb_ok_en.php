<?
use yii\helpers\Html;
$partCnt = 0;

?>
<!doctype html>
<html lang="fr">
	<head>
	<meta charset="utf-8">
	<title><?= $theTour['op_code'] ?> - SATISFACTION SURVEY - <?= $printName ?></title>
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
				<br />CUSTOMER SATISFACTION SURVEY (<?= $theForm['regionName'] ?>)
			</h1>
		</div>
		<p><strong>Dear Sir/Madam,</strong></p>
		<p>First of all, we would like to thank you for your trust in <strong><?= $printName ?></strong> as your travel organiser.</p>
		<p>In order to better understand your impressions, your satisfactions and perhaps dissatisfactions when you travel with us, we would like to invite you to take a few minutes to answer the following survey.</p>
		<p>Your feedback is very important to us to improve the service to our valued customers and we really appreciate your time.</p>
		<p>We wish you a very enjoyable trip. And thank you again for your cooperation.</p>
		<table style="clear:both; margin:0;">
			<tr>
				<td width="275">Tour code: <strong><?= $theTour['op_code'] ?></strong> / ID <strong><?= $theTour['id'] ?></strong></td>
				<td width="">Your name: <strong><?= $theForm['paxName'] ?></strong></td>
			</tr>
		</table>
		
		<div style="clear:both; margin-bottom:32px;"></div>

		<h2>Part <?= ++ $partCnt ?> : OVERALL SERVICES</h2>
		<p>How are you satisfied with the following services?</p>
		<table class="table table-condensed table-bordered">
			<tr>
				<td class="ta-c" width="28%"></td>
				<td class="ta-c" width="18%">Unsatisfied</td>
				<td class="ta-c" width="18%">Acceptable</td>
				<td class="ta-c" width="18%">Satisfied</td>
				<td class="ta-c" width="18%">Very satisfied</td>
			</tr>
			<? foreach (['Hotels', 'Homestays', 'Meals', 'Cruises', 'Train', 'Vehicle(s)'] as $item) { ?>
			<tr>
				<td class="ta-c"><?= $item ?></td>
				<?= str_repeat('<td></td>', 4) ?>
			</tr>
			<? } ?>
		</table>
		<h2>Part <?= ++ $partCnt ?> : TOUR GUIDE(S) AND DRIVER(S) *</h2><?
$guideNames = explode(',', $theForm['guideNames']);
if ($theForm['guideNames'] != '' && !empty($guideNames)) { ?>
		<p>How are you satisfied with your tour guide?</p><?
	foreach ($guideNames as $guideName) {
		$guideName = trim($guideName);
		if ($guideName != '') { ?>
		<table class="">
			<tr>
				<td class="ta-c"><? if (strtolower($guideName) != 'yes') { ?><strong>Tour guide : <?= Html::encode($guideName) ?></strong><? } ?></td>
				<td class="ta-c" width="18%">Unsatisfied</td>
				<td class="ta-c" width="18%">Acceptable</td>
				<td class="ta-c" width="18%">Satisfied</td>
				<td class="ta-c" width="18%">Very satisfied</td>
			</td>
<?
			foreach (['Language skills', 'Knowledge', 'Organization skills', 'Helpfulness (being attentive, available, flexible etc.)', 'Ability to connect travellers with locals'] as $item) {
?>
			<tr style="height:32px;">
				<td class="ta-c"><?= $item ?></td>
				<?= str_repeat('<td></td>', 4) ?>
			</tr>
<?
			}
?>
		</table>

		<p>Do you have any specific comments about your tour guide?</p>
		<div class="has-bg h-200"></div>
<?
		}
	}
}

$driverNames = explode(',', $theForm['driverNames']);
if ($theForm['driverNames'] != '' && !empty($driverNames)) { ?>
		<p>How are you satisfied with your driver?</p><?
	foreach ($driverNames as $driverName) {
		$driverName = trim($driverName);
		if ($driverName != '') { ?>
		<table>
			<tr>
				<td class="ta-c"><? if (strtolower($driverName)!= 'yes') { ?><strong>Driver : <?= Html::encode($driverName) ?></strong><? } ?></td>
				<td class="ta-c" width="18%">Unsatisfied</td>
				<td class="ta-c" width="18%">Acceptable</td>
				<td class="ta-c" width="18%">Satisfied</td>
				<td class="ta-c" width="18%">Very satisfied</td>
			</td><?
			foreach (['Professionalism', 'Helpfulness', 'Concentration', 'Relation with tour guide and travellers', 'Condition of vehicle'] as $item) { ?>
			<tr style="height:32px;">
				<td class="ta-c"><?= $item ?></td>
				<?= str_repeat('<td></td>', 4) ?>
			</tr><?
			} ?>
		</table>
		<p>Do you have any specific comments about your driver?</p>
		<div class="has-bg h-200"></div><?
		}
	}
} ?>
		<h2>Part <?= ++ $partCnt ?> : OTHER COMMENTS ABOUT YOUR TRIP AND ABOUT <?= strtoupper($printName) ?></h2>
		<p>How do you evaluate the services by <?= $printName ?> in the region of <?= $theForm['regionName'] ?>? Do you have any detailed comments or recommendations?</p>
		<div class="has-bg h-400"></div>
		<p>On a scale of 1 to 10, how would you rate your trip (1=worst, 10=best) ? _________</p>
		<br />&nbsp;
		<p>Date: ______________________________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Signature:__________________________________</p>
		<br />&nbsp;

		<? if (in_array($theForm['logoName'], ['us', 'both'])) { ?>
		<p><em>* Depending on how satisfied you say you were, Amica Travel grants a bonus to the guide and driver. This bonus does not replace the remuneration which is fixed. We'd therefore ask you to be as objective as possible in your survey. Thank you.</em></p>
		<br />&nbsp;
		<? } ?>

		<div class="ta-c">
			<em><strong>The success of your trip is the success of our company!</strong></em>
			<br />&nbsp;
			<hr />
			<br /><strong><?= \fUTF8::upper($printName)?></strong>
			<? if (in_array($theForm['logoName'], ['us', 'both'])) { ?>
			<br />Address : 27 Nguyen Truong To street, Nikko Building 3rd Floor, Ba Dinh District, Hanoi, Vietnam
			<br />Tel: (+84 4) 6273 4455 • Fax: (+84 4) 6273 3504
			<br />Email: <strong>info@amica-travel.com</strong> • Website: <strong>www.amica-travel.com</strong>
			<? } ?>
			<br />&nbsp;<br /><span style="font-style:italic; color:#999; font-size:10px;">--- Version 151010 | Printed on <?= date('j/n/Y \a\t H:i T')?> by <?= Yii::$app->user->identity->name ?> ---</span>
		</div>
		<!-- Feedback form end -->
	</div>
</body>
</html>
