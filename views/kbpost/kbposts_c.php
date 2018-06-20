<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_kbposts_inc.php');
$this->title  = 'New post';

?>
<? $form = ActiveForm::begin(); ?>
<div class="col-lg-8">
	<?= $form->field($model, 'title') ?>
	<div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Save changes'), ['class' => 'btn btn-primary']); ?></div>
</div>
<? ActiveForm::end();
