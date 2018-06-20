<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Add a new exchange rate';
?>
<div class="col-lg-2">xxx
	<? //include('users__sb.php'); ?>
</div>
<div class="col-lg-7">
	<? $form = ActiveForm::begin();?>
	<?=$form->field($model, 'rate_dt'); ?>
	<div class="row">
		<div class="col-lg-4">
			<?=$form->field($model, 'currency1'); ?>
		</div>
		<div class="col-lg-4">
			<?=$form->field($model, 'currency2'); ?>
		</div>
		<div class="col-lg-4">
			<?=$form->field($model, 'rate'); ?>
		</div>
	</div>
	<?=$form->field($model, 'note'); ?>
	<?=Html::submitButton(Yii::t('mn', 'Submit'), array('class' => 'btn btn-primary')); ?>
	<? ActiveForm::end(); ?>
</div>
<div class="col-lg-3">
ddd	
</div>