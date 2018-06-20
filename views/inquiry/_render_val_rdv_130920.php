<?
$countryCallingCode = \Yii::$app->db
	->createCommand('SELECT name_fr, dial_code FROM at_countries WHERE code=:code LIMIT 1', [':code'=>$inquiryData['countryCallingCode']])
	->queryOne();

if (!isset($countryCallingCode)) {
	$countryCallingCode = [
		'name_fr'=>'?',
		'dial_code'=>'+xxx',
	];
}
?>
<? if (isset($inquiryData['tourName'])) { ?>
<p><strong>Tour:</strong> <a rel="external" href="<?=$inquiryData['tourUrl']?>"><?=$inquiryData['tourName']?></a></p>
<? } ?>
<p><strong>Votre Nom et Prénom:</strong> <?=$inquiryData['prefix']?> / <?=$inquiryData['fname']?> / <?=$inquiryData['lname']?></p>
<p><strong>Votre adresse mail:</strong> <?=$inquiryData['email']?></p>
<p><strong>Votre pays de résidence:</strong> <?=$inquiryData['country']?></p>
<p><strong>Date / heure pour le RDV:</strong> (<?=$countryCallingCode['name_fr']?>) +<?=$countryCallingCode['dial_code']?> <?=$inquiryData['phone']?> @ <?=$inquiryData['callDate']?> entre <?=$inquiryData['callTime']?> (heure GMT)</p>
<p><strong>Votre message:</strong><br><?=nl2br(htmlspecialchars($inquiryData['message']))?></p>
