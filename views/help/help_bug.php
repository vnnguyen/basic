<?
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

Yii::$app->params['page_title'] = 'Report a bug or suggestion';
Yii::$app->params['page_small_title'] = 'Báo lỗi hoặc góp ý cho IMS';
Yii::$app->params['page_icon'] = 'bug';
Yii::$app->params['page_breadcrumbs'] = [
	['Help', 'help'],
	['Report a bug / suggestion'],
];

?>
<div class="col-lg-4 col-lg-push-8">
	<div class="panel panel-white">
		<div class="panel-heading">
			<h6 class="panel-title">Contact developer</h6>
		</div>
		<div class="panel-body">
			<p>Bạn dùng form bên cạnh để điền và gửi thông tin cho Mr Huân (admin).</p>
			<p>Hoặc có thể email hay gọi điện:</p>
			<p><strong>Email:</strong> huan@amicatravel.com</p>
			<p><strong>Mobile:</strong> 0 9797 0 6936</p>
		</div>
	</div>
</div>
<div class="col-lg-8 col-lg-pull-4">
	<div class="panel panel-white">
		<div class="panel-heading">
			<h6 class="panel-title">Bug / error report</h6>
		</div>
		<div class="panel-body">
			<? $form = ActiveForm::begin(['layout' => 'horizontal']); ?>
				<?= $form->field($model, 'uri', ['inputOptions'=>['placeholder'=>'https://']]) ?>
				<?= $form->field($model, 'happened')->textArea(['rows'=>5]) ?>
				<?= $form->field($model, 'expected')->textArea(['rows'=>5]) ?>
				<?= $form->field($model, 'comment')->textArea(['rows'=>5]) ?>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
					<?= Html::submitButton('Submit form', ['class' => 'btn btn-primary']) ?>
					<?= Html::resetButton('Reset form', ['class' => 'btn btn-default']) ?>
					</div>
				</div>
			<? ActiveForm::end(); ?>
		</div>
	</div>
</div>