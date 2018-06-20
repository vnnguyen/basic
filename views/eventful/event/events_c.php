<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_events_inc.php');

$this->title  = 'New event';

$form = ActiveForm::begin(); ?>
<div class="col-md-8">
	<?= $form->field($theEvent, 'name') ?>
	<?= $form->field($theEvent, 'summary')->textArea(['rows'=>5]); ?>
	<div class="row">
		<div class="datetimepicker col-sm-4"><?= $form->field($theEvent, 'from_dt'); ?></div>
		<div class="datetimepicker col-sm-4"><?= $form->field($theEvent, 'until_dt'); ?></div>
		<div class="datetimepicker col-sm-4"><?= $form->field($theEvent, 'timezone'); ?></div>
	</div>
	<div class="text-right"><?= Html::submitButton(Yii::t('mn', 'Save changes'), ['class' => 'btn btn-primary']) ?></div>
</div>
<? ActiveForm::end();

