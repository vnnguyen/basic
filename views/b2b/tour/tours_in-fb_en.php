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
				<br />CUSTOMER SATISFACTION SURVEY (<?= $theForm['regionName'] ?>)
			</h1>
		</div>
		<p><strong>Dear Sir, Madam</strong></p>
		<p>First of all, we would like to thank you for your trust in <strong><?= $printName ?></strong> as an organiser for your trip.</p>
		<p>In order to better understand your impressions, your satisfactions and perhaps dissatisfactions when you travel with us, we would like to invite you to take several minutes to answer the following questionnaire.</p>
		<p>Your feedback is very important to us to improve the service to our valued customers and we really appreciate your time.</p>
		<p>We wish you a very enjoyable trip. And thank you again for your time and cooperation.</p>

		<table style="clear:both; margin:0;">
			<tr>
				<td width="275">Tour code: <strong><?= $theTour['op_code'] ?></strong> / ID <strong><?= $theTour['id'] ?></strong></td>
				<td width="">Your name: <strong><?= $theForm['paxName'] ?></strong></td>
			</tr>
		</table>
		
		<div style="clear:both; margin-bottom:32px;"></div>

		<h2>Part <?= ++ $partCnt ?> : YOUR COMMENTS ON THE ITINERARY</h2>
		<p>Hereinafter you will find your itinerary organized by <?= $printName ?><?= $theForm['logoName'] == 'both' ? ' - <strong>a receptive agency of '.$theCompany['name'].'</strong>' : ''?>. We would like to thank you for spending your time everyday commenting on our services and activities during your trip (for example: accommodation, food & beverage, guides and drivers).</p>
		<table id="tourdays">
			<thead>
				<tr>
					<th width="20%">Itinerary / Hotel</th>
					<th width="80%">YOUR REMARKS<br />(itinerary, hotels, meals, activities, pace of visits etc.)</th>
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
				$ngay = date('j M., Y', strtotime($theTour['day_from'].' + '.($cnt - 1).'days'));
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
		<h2>Part <?= ++ $partCnt ?> : ABOUT YOUR TOUR GUIDE(S)</h2>
		<p>Overall, how are you satisfied with the guides?</p>
		<div class="has-bg h-100"></div>
<?
	foreach ($guideNames as $guideName) {
		$guideName = trim($guideName);
		if ($guideName != '') {
?>
		<div><strong>GUIDE : <?= strtolower($guideName) == 'yes' ? '' : Html::encode($guideName) ?></strong></div>
		<table class="">
			<tr>
				<th class="ta-c">Your remarks</th>
				<td class="ta-c" width="15%">Poor</td>
				<td class="ta-c" width="15%">Acceptable</td>
				<td class="ta-c" width="15%">Satisfactory</td>
				<td class="ta-c" width="15%">Good</td>
				<td class="ta-c" width="15%">Excellent</td>
			</td>
<?
			foreach (['Spoken English', 'Service & Flexibility', 'Knowledge', 'Punctuality', 'Manner'] as $item) {
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
		<h2>Part <?= ++ $partCnt ?> : ABOUT YOUR TRANSPORTATION AND DRIVER(S)</h2>
		<p>Overall, how are you satisfied withe the quality of your driver(s) and vehicle(s)?</p>
		<div class="has-bg h-100"></div>
<?
	foreach ($driverNames as $driverName) {
		$driverName = trim($driverName);
		if ($driverName != '') {
?>
		<div><strong>DRIVER : <?= strtolower($driverName) == 'yes' ? '' : Html::encode($driverName) ?></strong></div>
		<table class="">
			<tr>
				<th class="ta-c">Your remarks</th>
				<td class="ta-c" width="15%">Poor</td>
				<td class="ta-c" width="15%">Acceptable</td>
				<td class="ta-c" width="15%">Satisfactory</td>
				<td class="ta-c" width="15%">Good</td>
				<td class="ta-c" width="15%">Excellent</td>
			</td>
<?
			foreach (['Vehicle(s)', 'Driver\'s service & manner', 'Driver\'s professionalism'] as $item) {
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

		<h2>Part <?= ++ $partCnt ?> : OTHER COMMENTS AND RECOMMENDATIONS</h2>
		<p>How do you evaluate the services by <?= $printName ?> in the region of <?= $theForm['regionName'] ?>? Do you have any detailed comments or recommendations?</p>
		<div class="has-bg h-500"></div>

		<p>Date: ______________________________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Signature:__________________________________</p>
		<br />&nbsp;
		<br />&nbsp;
		<br />&nbsp;
		<br />&nbsp;
		<br />&nbsp;
		<div class="ta-c">
			<em><strong>The success of your trip is the success of our company!</strong></em>
			<br />&nbsp;
			<hr />
			<br /><strong><?= \fUTF8::upper($printName)?></strong>
			<? if (in_array($theForm['logoName'], ['us', 'both'])) { ?>
			<br />3rd fl., Nikko Building,  27 Nguyen Truong To street, Ba Dinh district, Hanoi, Vietnam
			<br />Tel: (+84 4) 62734455 • Fax: (+84 4) 62733504
			<br />Email: <strong>info@amica-travel.com</strong> • Web: <strong>www.amica-travel.com</strong>
			<? } ?>
			<br />&nbsp;<br /><span style="font-style:italic; color:#999; font-size:10px;">--- This feedback form was prepared on <?=date('Y-m-d H:i:s T')?> by <?= Yii::$app->user->identity->name ?> ---</span>
		</div>
		<!-- Feedback form end -->
	</div>
</body>
</html>
