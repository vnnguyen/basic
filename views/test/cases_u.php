<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

//include('_cp_inc.php');

if ($theCase->isNewRecord) {
	$this->title = 'Hồ sơ mới';
} else {
	$this->title = 'Sửa: '.$theCase['name'];
	$this->params['breadcrumb'][] = ['Xem', 'cp/r/'.$theCase['id']];
}

?>
<div class="col-lg-8">
	<? $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-lg-3">
			<?= $form->field($theCase, 'is_priority')->dropdownList(['yes'=>'Priority', 'no'=>'Non-priority'], ['prompt'=>'- Priority -']) ?>
		</div>
		<div class="col-lg-3">
			<?= $form->field($theCase, 'language')->dropdownList(['en'=>'English', 'fr'=>'Francais', 'vi'=>'Tiếng Việt'], ['prompt'=>'- Select language -']) ?>
		</div>
		<div class="col-lg-3">
			<?= $form->field($theCase, 'is_b2b')->dropdownList(['yes'=>'B2B', 'no'=>'B2C']) ?>
		</div>
		<div class="col-lg-3">
			<?= $form->field($theCase, 'market_id') ?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6">
			<?=$form->field($theCase, 'name'); ?>
		</div>
		<div class="col-lg-6">
			<?=$form->field($theCase, 'owner_id'); ?>
		</div>
	</div>
	<?=$form->field($theCase, 'info')->textArea(['rows'=>4]); ?>

	<div class="text-right"><?= Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>
<div class="col-lg-4">
</div>
