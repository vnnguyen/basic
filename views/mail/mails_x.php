<?
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use app\helpers\DateTimeHelper;

include('_mails_inc.php');
$this->title = 'DATA:' .$theMail['subject'];

$this->params['breadcrumb'] = [
	['Mails', '@web/mails'],
	['View', '@web/mails/x/'.$theMail['id']],
];

$data = @unserialize($theMail['data']);
if (!$data) {
	$data = [];
}
ksort($data);

$date = \DateTime::createFromFormat('D, d M Y H:i:s O', trim(substr($data['Date'], 0, 31)));

?>
<div class="col-md-12">
	<p>SAVED: <?= $theMail['created_at'] ?> | DATE: <?= $date->format('Y-m-d H:i:s') ?> </p>
	<table class="table table-condensed table-bordered">
		<? foreach ($data as $k=>$v) { ?>
		<tr>
			<td class="text-nowrap"><?= $k ?></td>
			<td>
				<?= Html::encode($v) ?>
			</td>
		</tr>
		<? } ?>
	</table>
</div>