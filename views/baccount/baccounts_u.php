<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

//include('_baccounts_inc.php');

?>
<div class="col-md-8">
	<? $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-md-3">
			<?= $form->field($theBaccount, 'stype') ?>
		</div>
		<div class="col-md-7">
			<?= $form->field($theBaccount, 'name') ?>
		</div>
		<div class="col-md-2">
			<?= $form->field($theBaccount, 'currency') ?>
		</div>
	</div>
	<?= $form->field($theBaccount, 'info')->textArea(['rows'=>4]) ?>
	<?= $form->field($theBaccount, 'note')->textArea(['rows'=>4]) ?>
	<div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?></div>
	<? ActiveForm::end(); ?>
</div>

