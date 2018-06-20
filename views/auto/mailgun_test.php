<?
use yii\helpers\Html;
use app\helpers\DateTimeHelper;
$theMail = $mail;
$this->title = 'Mail: '.$theMail['subject'];
?>
<div class="col-md-12">
	<table class="table table-condensed table-bordered">
		<tr><td>Sent</td><td><?= DateTimeHelper::convert($theMail['sent_dt'], 'Y-m-d H:i O', 'UTC', Yii::$app->user->identity->timezone) ?> (<?= $theMail['sent_dt_text'] ?>)</td></tr>
		<tr><td>Saved</td><td><?= DateTimeHelper::convert($theMail['created_at'], 'Y-m-d H:i O', 'UTC', Yii::$app->user->identity->timezone) ?> (<?= Yii::t('mn', Yii::$app->formatter->asRelativeTime($theMail['created_at'])) ?>)</td></tr>
		<tr><td>From</td><td><?= Html::encode($theMail['from']) ?></td></tr>
		<tr><td>To</td><td><?= Html::encode($theMail['to']) ?></td></tr>
		<? if ($theMail['cc'] != '') { ?>
		<tr><td>Cc</td><td><?= Html::encode($theMail['cc']) ?></td></tr>
		<? } ?>
		<? if ($theMail['bcc'] != '' && $theMail['bcc'] != 'ims@amicatravel.com') { ?>
		<tr><td>Bcc</td><td><?= Html::encode($theMail['bcc']) ?></td></tr>
		<? } ?>
		<? if ($theMail['attachment_count'] > 0) { $theMailFiles = unserialize($theMail['files']); if (!$theMailFiles) {$theMailFiles = [];} ?>
		<tr><td>Files (<?= $theMail['attachment_count'] ?>)</td>
			<td>
			<? foreach ($theMailFiles as $file) { ?>
			<div>+ <?= Html::a($file['name'], '@web/mails/f/'.$theMail['id'].'?name='.urlencode($file['name'])) ?> <span class="text-muted">(<?= number_format($file['size'] / 1024, 2) ?> KB)</span></div>
			<? } ?>
			</td>
		</tr>
		<? } ?>
		<tr><td>Message Id</td><td><?= Html::encode($theMail['message_id']) ?></td></tr>
		<tr><td>In Reply To</td><td><?= Html::a(Html::encode($theMail['in_reply_to']), '@web/mails/search?key=message_id&value='.urlencode($theMail['in_reply_to'])) ?></td></tr>
		<tr><td>Has Pax</td><td><?= $hasPax == true ? 'YES' : 'NO' ?></td></tr>
		<tr><td>Has Ims</td><td><?= $hasIms == true ? 'YES' : 'NO' ?></td></tr>
		<tr><td>Addresses</td><td><?= implode(', ', $addresses) ?></td></tr>
	</table>
</div>