<?
$welcome['en'] = 'Welcome';
$welcome['fr'] = 'Bienvenue';
$welcome['vi'] = 'Chào mừng';

?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="utf-8" />
	<title>Biển đón khách | Welcome banner | Pancarte d'accueil</title> 
	<style>
	* {padding:0; border:0; margin:0;}
	table {width:100%;}
	input[type=text], textarea {border:1px solid #333; padding:2px 10px;}
	</style>
</head>
<body>
	<? if ($theForm['template'] == 'old') { ?>
	<table style="border-bottom:5px solid #ddd; margin-bottom:32px;">
		<tr>
			<td style="text-align:left; height:200px; max-height:200px;">
				<? if ($theForm['logo'] == 'amica') { ?>
				<img style="max-height:200px;" src="http://my.amicatravel.com/assets/img/logo_amica_purple_433x260.png">
				<? } else { ?>
				<img style="max-height:200px;" src="http://my.amicatravel.com/upload/company-logos/<?= $theBooking['case']['company']['image'] ?>">
				<? } ?>
			</td>
			<td style="text-align:right">
				<div style="font:bold 80px/80px Arial;">
				<? if ($theForm['logo'] == 'amica') { ?>
				AMICA TRAVEL
				<? } else { ?>
				<?= $theBooking['case']['company']['name'] ?>
				<? } ?>
				</div>
				<? if ($theForm['extra'] != '') { ?>
				<div style="font:bold 50px/50px Arial; text-align:right;"><?= str_replace('|', '<br />', $theForm['extra']) ?></div>
				</tr>
			</td>
		</tr>
		<? } // if extra ?>
	</table>
	<? } // if template ?>

	<div style="text-align:center; font-family:Arial, sans-serif; font-size:64px; line-height:64px; font-weight:bold; padding-bottom:16px;">
		<?= $welcome[$theForm['language']] ?>
	</div>
	<div style="text-align:center; font-family:Arial, sans-serif; font-size:16px; line-height:16px; font-weight:bold; padding-bottom:32px;">
		(<?= $theForm['pax']?> &middot; <?= $theForm['location'] ?> &middot; <?= $theForm['time'] ?>)
	</div>
	<div style="text-align:center; font-family:Trebuchet MS, sans-serif; font-size:90px; line-height:105px; font-weight:bold; ">
		<?= nl2br($theForm['names']) ?>
	</div>
	<? /*
	<? if (isset($theCompany['image']) && $theCompany['image'] != '' && isset($_POST['logo']) && $_POST['logo'] == 'agency') { ?>
	<table>
    <tr>
		<td style="width:20%; padding-left:25px;">
			<? if (isset($theCompany['image']) && $theCompany['image'] != '' && isset($_POST['logo']) && $_POST['logo'] == 'agency') { ?>
			<p><img src="http://my.amicatravel.com/upload/company-logos/<?=$theCompany['image']?>" width="100%"></p>
			<? } else { ?>
			<p><img src="http://my.amicatravel.com/assets/img/logo_amica_purple_433x260.png" width="100%"></p>
			<? } ?>
			<p style="font:bold 60px/60px Arial; padding-bottom:20px;"><?= $getLang == 'fr' ? 'Bienvenue' : 'Welcome' ?></p>
		</td>
      <td colspan="2" style="width:80%; text-align:right; padding-right:25px; padding-top:40px; vertical-align:top; letter-spacing:-1px;">
				<? if ($getExtraText == '') { ?>
				<div style="font:bold 80px/80px Arial;">
					<? if (isset($theCompany['image']) && $theCompany['image'] != '' && isset($_POST['logo']) && $_POST['logo'] == 'agency') { ?>
					<?=$theCompany['name']?>
					<? } else { ?>
					AMICA TRAVEL
					<? } ?>
				</div>
				<? } else { ?>
				<div style="font:bold 80px/80px Arial;">AMICA TRAVEL</div>
				<div style="font:bold 50px/50px Arial; text-align:right; padding-left:20px; padding-bottom:20px;"><?=str_replace('|', '<br />', $getExtraText)?></div>
				<? } ?>
			</td>
    </tr>
    <tr>
      <td colspan="3" style="text-align:center; height:320px; vertical-align:middle; padding:0 20px; font:bold 90px/105px Trebuchet MS;"><?=str_replace(chr(10), '<br />', $_POST['names'])?></td>
    </tr>
		<tr>
			<td style="width:20%; font:40px Arial; text-align:left;"><?=$_POST['pax']?> <?= $getLang == 'en' ? 'person' : 'personne' ?><?=$_POST['pax'] == 1 ? '' : 's'?></td>
			<td style="width:55%; text-align:center; font:40px Arial;"><?=$_POST['vol']?></td>
			<td style="width:20%; text-align:right; font:40px Arial;"><?=$_POST['arr']?></td>
		</tr>
	</table>
	<? } else { ?>
	<div style="text-align:center; font-family:Arial, sans-serif; font-size:64px; line-height:64px; font-weight:bold; padding-bottom:16px;"><?= $getLang == 'fr' ? 'Bienvenue' : 'Welcome' ?></div>
	<div style="text-align:center; font-family:Arial, sans-serif; font-size:16px; line-height:16px; font-weight:bold; padding-bottom:32px;">(<?=$_POST['pax']?> pax &middot; <?=$_POST['vol']?> &middot; <?=$_POST['arr']?>)</div>
	<div style="text-align:center; font-family:Trebuchet MS, sans-serif; font-size:90px; line-height:105px; font-weight:bold; "><?=str_replace(chr(10), '<br />', $_POST['names'])?></div>
	<? } // if company */ ?>
</body>
</html>