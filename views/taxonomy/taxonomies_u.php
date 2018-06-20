<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_taxonomies_inc.php');

if ($theTaxonomy->isNewRecord) {
	$this->title = 'New taxonomy';
} else {
	$this->title = 'Edit: '.$theTaxonomy['name'];
}

?>
<? $form = ActiveForm::begin(); ?>
<div class="col-md-8">
	<div class="row">
		<div class="col-md-6"><?= $form->field($theTaxonomy, 'name') ?></div>
		<div class="col-md-6"><?= $form->field($theTaxonomy, 'alias') ?></div>
	</div>
	<?= $form->field($theTaxonomy, 'info')->textArea(['rows'=>5]) ?>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theTaxonomy, 'status')->dropdownList(['off'=>'Off', 'on'=>'On'], ['prompt'=>'- Select -']) ?></div>
		<div class="col-md-3"><?= $form->field($theTaxonomy, 'is_hierachical')->dropdownList(['no'=>'No', 'yes'=>'Yes'], ['prompt'=>'- Select -']) ?></div>
		<div class="col-md-3"><?= $form->field($theTaxonomy, 'is_multiple')->dropdownList(['no'=>'No', 'yes'=>'Yes'], ['prompt'=>'- Select -']) ?></div>
	</div>
	<div class="text-right"><?= Html::submitButton('Save changes', ['class'=>'btn btn-primary']) ?></div>
</div>
<? ActiveForm::end(); ?>