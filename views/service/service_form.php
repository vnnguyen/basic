<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
?>
<? $form = ActiveForm::begin(); ?>
<div class="col-md-6">
	<?= $form->field($theService, 'code') ?>
	<?= $form->field($theService, 'context')->textArea(['rows'=>5]) ?>
	<?= $form->field($theService, 'sv')->textArea(['rows'=>5]) ?>
	<?= $form->field($theService, 'cp') ?>
	<?= $form->field($theService, 'result')->textArea(['rows'=>5]) ?>
	<div class="text-right">
		<?= Html::submitButton('Save changes', ['class'=>'btn btn-primary']) ?>
	</div>
</div>

<? ActiveForm::end();
