<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

Yii::$app->params['page_title'] = 'Confirm sample tour day deletion';

Yii::$app->params['page_breadcrumbs'] = [
	['B2B', 'b2b'],
	['Sample tour days', 'b2b/sample-tour-days'],
	['Delete'],
];

?>
<div class="col-md-8">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="alert alert-danger">
				<strong>WARNING:</strong> You're about to delete a sample tour day:
				<br><?= Html::encode($theDay['title']) ?>
				<br>This day will be removed permanently and cannot be recovered.
				<br>Are you sure you want to delete this sample day?
			</div>
			<form method="post" action="">
				<?= Html::hiddenInput('confirm', 'delete') ?>
				<div><?= Html::submitButton(Yii::t('app', 'Yes, delete this'), ['class' => 'btn btn-primary']) ?> or <?= Html::a('Cancel', '/b2b/sample-tour-days-u/'.$theDay['id']) ?></div>
			</form>
		</div>
	</div>
</div>
