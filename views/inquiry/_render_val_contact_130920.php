				<? if (isset($inquiryData['tourName'])) { ?>
				<p><strong>Tour:</strong> <a rel="external" href="<?=$inquiryData['tourUrl']?>"><?=$inquiryData['tourName']?></a></p>
				<? } ?>
				<p><strong>Votre Nom et Prénom:</strong> <?=$inquiryData['prefix']?> / <?=$inquiryData['fname']?> / <?=$inquiryData['lname']?></p>
				<p><strong>Votre adresse mail:</strong> <?=$inquiryData['email']?></p>
				<p><strong>Votre pays de résidence:</strong> <?=$inquiryData['country']?></p>
				<p><strong>Votre message:</strong><br><?=nl2br(htmlspecialchars($inquiryData['message']))?></p>
