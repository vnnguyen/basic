<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_permission_inc.php');

$this->title = 'New permission';
?>
<div class="col-md-8">
	<? $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-md-6"><?= $form->field($thePermission, 'name') ?></div>
		<div class="col-md-6"><?= $form->field($thePermission, 'alias') ?></div>
	</div>
	<?= $form->field($thePermission, 'info')->textArea(['rows'=>5]) ?>
	<div class="text-right"><?= Html::submitButton('Save changes', ['class'=>'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>
<div class="col-md-4">
</div>