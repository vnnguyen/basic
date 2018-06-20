<?
$countryCallingCode = \Yii::$app->db
	->createCommand('SELECT name_fr, dial_code FROM at_countries WHERE code=:code LIMIT 1', [':code'=>$inquiryData['countryCallingCode']])
	->queryOne();
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
				<p><strong>Votre pays, ville de résidence:</strong> <?=$inquiryData['country']?> / <?=$inquiryData['city']?></p>
				<p><strong>Date d'arrivée approximative:</strong> <?=$inquiryData['departureDate']?> <strong>Durée du voyage:</strong> <?=$inquiryData['tourLength']?> jours (transports inclus)</p>
				<p><strong>Destinations:</strong> <?=is_array($inquiryData['countriesToVisit']) ? implode(', ', $inquiryData['countriesToVisit']) : ''?></p>
				<p><strong>Les participants:</strong> <?=$inquiryData['numberOfTravelers12']?> + <?=$inquiryData['numberOfTravelers2']?> + <?=$inquiryData['numberOfTravelers0']?> <strong>détails d'âges:</strong> <?=$inquiryData['agesOfTravelers12']?></p>
				<p><strong>Décrivez votre projet, votre vision du voyage et de quelle façon vous souhaitez découvrir notre pays:</strong><br><?=nl2br(htmlspecialchars($inquiryData['message']))?></p>
				<? if (!empty($inquiryData['tourThemes'])) { ?>
				<p><strong>Thématiques:</strong> <?=implode(', ', $inquiryData['tourThemes'])?></p>
				<? } ?>
				<? if (!empty($inquiryData['hotelTypes'])) { ?>
				<p><strong>Quel(s) type(s) d’hébergement aimeriez-vous pour ce voyage:</strong><br><?=implode(', ', $inquiryData['hotelTypes'])?></p>
				<? } ?>
				<p><strong>Combien de chambres souhaitez-vous:</strong> <?=$inquiryData['hotelRoomDbl']?> DBL, <?=$inquiryData['hotelRoomTwn']?> TWN, <?=$inquiryData['hotelRoomTrp']?> TRP, <?=$inquiryData['hotelRoomSgl']?> SGL</p>
				<p><strong>Le petit déjeuner est généralement déjà inclus dans le prix de l’hébergement. Souhaitez-vous d’autres repas:</strong> <?=$inquiryData['mealsIncluded']?></p>
				<p><strong>A combien estimez-vous votre budget par personne pour ce voyage:</strong> <?=$inquiryData['budget']?></p>
				<p><strong>Pouvons-nous convenir d’un entretien téléphonique:</strong> <?=$inquiryData['callback']?>
					<? if ($inquiryData['callback'] == 'Oui') { ?><br>(<?=$countryCallingCode['name_fr']?>) +<?=$countryCallingCode['dial_code']?> <?=$inquiryData['phone']?> @ <?=$inquiryData['callDate']?> entre <?=$inquiryData['callTime']?> (heure GMT)<? } ?>
				</p>
