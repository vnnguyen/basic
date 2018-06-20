<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'New tour program';
$this->params['icon'] = 'plus';

?>
<div class="col-lg-8">
	<? $form = ActiveForm::begin();?>
	<div class="row">
		<div class="col-md-4">
			<?=$form->field($theCt, 'id'); ?>
		</div>
		<div class="col-md-4">
			<?=$form->field($theCt, 'offer_type'); ?>
		</div>
		<div class="col-md-4">
			<?=$form->field($theCt, 'language'); ?>
		</div>
	</div>
	<?= $form->field($theCt, 'title') ?>
	<?= $form->field($theCt, 'about') ?>
	<?= $form->field($theCt, 'intro')->textArea(['rows'=>4]); ?>
	<div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?></div>
	<? ActiveForm::end(); ?>
</div>
