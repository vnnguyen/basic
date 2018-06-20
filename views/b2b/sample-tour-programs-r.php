<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\CkeditorAsset;

Yii::$app->params['page_title'] = $theProgram['title'];

Yii::$app->params['page_breadcrumbs'] = [
	['B2B', 'b2b'],
	['Sample tour programs', 'b2b/sample-tour-programs'],
	['View'],
];

$form = ActiveForm::begin();

?>
<div class="col-md-8">
	<div class="panel panel-default">
		<div class="panel-body">
			<h5>Summary</h5>
			<p><?= $theProgram['body'] ?></p>
			<h5>Itinerary</h5>
			<? foreach ($theProgram['days'] as $day) { ?>
			<p><strong><?= $day['title'] ?></strong></p>
			<?= $day['body'] ?>
			<hr>
			<? } ?>
		</div>
	</div>
</div>
