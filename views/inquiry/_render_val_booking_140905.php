<?
if (defined('SEG1')) {
	$countryCallingCode = \Yii::$app->db
	->createCommand('SELECT name_fr, dial_code FROM at_countries WHERE code=:code LIMIT 1', [':code'=>$inquiryData['countryCallingCode']])
	->queryOne();
}
if (!isset($countryCallingCode)) {
	$countryCallingCode = [
		'name_fr'=>'?',
		'dial_code'=>'XXX',
	];
}
?>
<table class="inquiry_fr_devis">
	<thead>
		<tr>
			<th width="30%"></th>
			<th width="70%"></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>Submitted at</th><td><?= date_format(date_timezone_set(date_create($theInquiry['created_at']), timezone_open('Asia/Saigon')), 'l, j F Y h:ia') ?> (Hanoi time)</td>
		</tr>
		<tr>
			<th>Site / Form</th><td><?= $theInquiry['site']['name'] ?> / <?= $theInquiry['form_name'] ?></td>
		</tr>
		<tr>
			<td colspan="2">
				<? if (isset($inquiryData['tourName'])) { ?>
				<p><strong>Tour:</strong> <a rel="external" href="<?=$inquiryData['tourUrl']?>"><?=$inquiryData['tourName']?></a></p>
				<? } ?>
				<p><strong>Votre Nom et Prénom:</strong> <?=$inquiryData['prefix']?> / <?=$inquiryData['fname']?> / <?=$inquiryData['lname']?></p>
				<p><strong>Votre adresse mail:</strong> <?=$inquiryData['email']?></p>
				<p><strong>Votre pays, ville de résidence:</strong> <?=$inquiryData['country']?> / <?=$inquiryData['city']?></p>
				<p><strong>Date de départ approximative:</strong> <?=$inquiryData['departureDate']?> <strong>Date de retour:</strong> <?=$inquiryData['deretourDate']?> <strong>Durée du voyage:</strong> <?=$inquiryData['tourLength']?> jours (transports inclus)</p>
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
			</td>
		</tr>
		<tr>
			<th>IP address</th>
			<td><a rel="external" href="http://whatismyipaddress.com/ip/<?=$theInquiry['ip']?>"><?=$theInquiry['ip']?></a></td></tr>
		<tr>
			<th>HTTP Referrer</th>
			<td><i class="fa fa-info-circle" title="<?=$theInquiry['ref']?>"></i>
				<?
				$mRef = parse_url($theInquiry['ref']);
				if (false !== $mRef) {
					if (!isset($mRef['query'])) $mRef['query'] = '';
					$mQuery = parse_str(str_replace('&amp;', '&', $mRef['query']), $mq);
					if ($mRef['path'] == '/aclk') {
						echo '<span style="color:Red">Google Adwords</span>';
					} elseif (isset($mRef['host']) && $mRef['host'] == 'www.googleadservices.com') {
						echo '<span style="color:Red">Google Adsense</span>';
					} else {
						if (!isset($mRef['host'])) $mRef['host'] = '(No data)';
						echo $mRef['host'];
					}
					$mqx = '';
					if (is_array($mq)) {
						foreach ($mq as $k=>$v) {
							if ($k == 'ohost' || $k == 'adurl' || $k == 'url' || $k == 'u' || $k == 'oq' || $k == 'rdata' || $k == 'q' || $k == 'p')
							$mqx .= '<br /><span class="label label-default">'.$k.'</span> '.$v;
						}
					}
					echo $mqx;
				}
			?>
			</td>
		</tr>
		<tr>
			<th>UserAgent string</th>
			<td><?=$theInquiry['ua']?></td>
		</tr>
	</tbody>
</table>
<style type="text/css">
table.inquiry_fr_devis {border-collapse:collapse; border:1px solid #ccc; width:100%;}
table.inquiry_fr_devis strong {color:brown;}
table.inquiry_fr_devis td, table.inquiry_fr_devis th {border:1px solid #ccc; padding:5px; vertical-align:top;} 
table.inquiry_fr_devis thead td, table.inquiry_fr_devis thead th {padding:0; height:0;}
table.inquiry_fr_devis tbody th {text-align:right;}
table.inquiry_fr_devis tbody td p {margin-bottom:0.5em!important;}
</style>