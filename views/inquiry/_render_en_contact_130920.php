<?
$inquiryCountry = \Yii::$app->db
	->createCommand('SELECT name_en FROM at_countries WHERE code=:code LIMIT 1', [':code'=>$inquiryData['country']])
	->queryOne();

if (isset($inquiryData['countryCallingCode'])) {
	$countryCallingCode = \Yii::$app->db
		->createCommand('SELECT name_en, dial_code FROM at_countries WHERE code=:code LIMIT 1', [':code'=>$inquiryData['countryCallingCode']])
		->queryOne();
	if (!$countryCallingCode) {
		$countryCallingCode = [
			'name_en'=>'?',
			'dial_code'=>'?',
		];
	}
}
?>
<? if (isset($inquiryData['tourName'])) { ?>
<p><strong>Tour:</strong> <a rel="external" href="<?= $inquiryData['tourUrl'] ?>"><?= $inquiryData['tourName'] ?></a></p>
<? } ?>
<p><strong>Your title, first and last name:</strong> <?= $inquiryData['prefix'] ?> / <?= $inquiryData['fname'] ?> / <?= $inquiryData['lname'] ?></p>
<p><strong>Your email address:</strong> <?= $inquiryData['email'] ?></p>
<p><strong>Your country of residence:</strong> <?= $inquiryCountry['name_en'] ?></p>
<p><strong>Your message:</strong><br><?=nl2br(htmlspecialchars($inquiryData['message']))?></p>
