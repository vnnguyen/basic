<?
$countryCallingCode = \Yii::$app->db
	->createCommand('SELECT name_en, dial_code FROM at_countries WHERE code=:code LIMIT 1', [':code'=>$inquiryData['countryCallingCode']])
	->queryOne();
if (!isset($countryCallingCode)) {
	$countryCallingCode = [
		'name_en'=>'?',
		'dial_code'=>'+xxx',
	];
}
?>
<? if (isset($inquiryData['tourName'])) { ?>
<p><strong>Tour:</strong> <a rel="external" href="<?=$inquiryData['tourUrl']?>"><?=$inquiryData['tourName']?></a></p>
<? } ?>
<p><strong>Your first and last name:</strong> <?=$inquiryData['prefix']?> / <?=$inquiryData['fname']?> / <?=$inquiryData['lname']?></p>
<p><strong>Your email address:</strong> <?=$inquiryData['email']?></p>
<p><strong>Your country and city of residence:</strong> <?=$inquiryData['country']?> / <?=$inquiryData['city']?></p>
<p><strong>Your approximate date of departure:</strong> <?=$inquiryData['departureDate']?> <strong>Approximate tour length:</strong> <?=$inquiryData['tourLength']?> days (including transportation)</p>
<p><strong>What countries do you want to visit:</strong> <?=is_array($inquiryData['countriesToVisit']) ? implode(', ', $inquiryData['countriesToVisit']) : ''?></p>
<p><strong>Number and ages of travelers:</strong> <?=$inquiryData['numberOfTravelers12']?> + <?=$inquiryData['numberOfTravelers2']?> + <?=$inquiryData['numberOfTravelers0']?> <strong>age details:</strong> <?=$inquiryData['agesOfTravelers12']?></p>
<p><strong>Please describe your travel preferences:</strong><br><?=nl2br(htmlspecialchars($inquiryData['message']))?></p>
<? if (!empty($inquiryData['tourThemes'])) { ?>
<p><strong>What types of travel do you prefer:</strong> <?=implode(', ', $inquiryData['tourThemes'])?></p>
<? } ?>
<? if (!empty($inquiryData['hotelTypes'])) { ?>
<p><strong>What types of accommodation do you prefer:</strong><br><?=implode(', ', $inquiryData['hotelTypes'])?></p>
<? } ?>
<p><strong>How many rooms do you prefer to have:</strong> <?=$inquiryData['hotelRoomDbl']?> DBL, <?=$inquiryData['hotelRoomTwn']?> TWN, <?=$inquiryData['hotelRoomTrp']?> TRP, <?=$inquiryData['hotelRoomSgl']?> SGL</p>
<p><strong>Meals. Breakfast is generally included with our tours. Would you like us to include other meals as well:</strong> <?=$inquiryData['mealsIncluded']?></p>
<p><strong>How much do you estimate your budget per person for this trip:</strong> <?=$inquiryData['budget']?></p>
<p><strong>Complimentary phone call:</strong> <?=$inquiryData['callback']?>
	<? if ($inquiryData['callback'] == 'Yes') { ?><br>(<?=$countryCallingCode['name_en']?>) +<?=$countryCallingCode['dial_code']?> <?=$inquiryData['phone']?> @ <?=$inquiryData['callDate']?> entre <?=$inquiryData['callTime']?> (UTC)<? } ?>
</p>
