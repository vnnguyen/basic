<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_terms_inc.php');

if ($theTerm->isNewRecord) {
	$this->title = 'New term in: '.$theTerm['name'];
} else {
	$this->title = 'Edit: '.$theTerm['name'];
}

?>
<? $form = ActiveForm::begin(); ?>
<div class="col-md-8">
	<div class="row">
		<div class="col-md-6"><?= $form->field($theTerm, 'name') ?></div>
		<div class="col-md-6"><?= $form->field($theTerm, 'alias') ?></div>
	</div>
	<?= $form->field($theTerm, 'info')->textArea(['rows'=>5]) ?>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theTerm, 'status')->dropdownList(['off'=>'Off', 'on'=>'On'], ['prompt'=>'- Select -']) ?></div>
		<div class="col-md-3"><?= $form->field($theTerm, 'sorder') ?></div>
		<div class="col-md-3"><?= $form->field($theTerm, 'pid') ?></div>
	</div>
	<div class="text-right"><?= Html::submitButton('Save changes', ['class'=>'btn btn-primary']) ?></div>
</div>
<? ActiveForm::end(); ?>