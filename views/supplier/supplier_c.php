<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'New company';
$this->params['breadcrumb'] = [
	['Companies', 'companies'],
];
?>
<div class="col-lg-8">
	<? $form = ActiveForm::begin() ?>
	<?= $form->field($theCompany, 'name'); ?>
	<?= $form->field($theCompany, 'name_full'); ?>
	<div class="text-right"><?= Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?></div>
	<? ActiveForm::end(); ?>
</div>
