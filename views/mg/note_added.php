<div style="width:600px;">
	<p><?= $body ?></p>
	<p style="color:#999">&mdash;<br /><?//= Yii::t('mg', 'Reply to this email directly') ?><!-- <?= Yii::t('mg', 'or') ?> --><a href="https://my.amicatravel.com/messages/r/<?= $theNote['id']?>"><?= Yii::t('mg', 'view message') ?></a> <?= Yii::t('mg', 'or') ?> <a href="<?= $relUrl ?>"><?= Yii::t('mg', 'view related content') ?></a> <?= Yii::t('mg', 'on IMS') ?></p>
</div>
