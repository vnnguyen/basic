<?
if (isset($inquiryData['countryCallingCode'])) {
	$countryCallingCode = \Yii::$app->db
	->createCommand('SELECT name_fr, dial_code FROM at_countries WHERE code=:code LIMIT 1', [':code'=>$inquiryData['countryCallingCode']])
	->queryOne();
}
if (isset($inquiryData['country'])) {
	$country = \Yii::$app->db
	->createCommand('SELECT name_fr FROM at_countries WHERE code=:code LIMIT 1', [':code'=>$inquiryData['country']])
	->queryScalar();
}
if (!isset($countryCallingCode)) {
	$countryCallingCode = [
		'name_fr'=>'?',
		'dial_code'=>'XXX',
	];
}
?>
				<? if (isset($inquiryData['tourName'])) { ?>
				<p><strong>Tour:</strong> <a rel="external" href="<?=$inquiryData['tourUrl']?>"><?=$inquiryData['tourName']?></a></p>
				<? } ?>
				<p><strong>Votre Nom et Prénom:</strong> <?=$inquiryData['prefix']?> / <?=$inquiryData['fname']?> / <?=$inquiryData['lname']?></p>
				<p><strong>Votre adresse mail:</strong> <?=$inquiryData['email']?></p>
				<p><strong>Votre pays de résidence:</strong> <?= $country ? $country : $inquiryData['country'] ?></p>
				<p><strong>Votre message:</strong><br><?=nl2br(htmlspecialchars($inquiryData['message']))?></p>
