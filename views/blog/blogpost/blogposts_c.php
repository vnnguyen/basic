<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_blogposts_inc.php');

$this->title  = 'New post';

$form = ActiveForm::begin(); ?>
<div class="col-md-8">
	<div class="panel panel-default">
		<div class="panel-heading"><h6 class="panel-title">New post</h6></div>
		<div class="panel-body">
			<?= $form->field($theEntry, 'title'); ?>
			<?= $form->field($theEntry, 'summary')->textArea(['rows'=>5]); ?>
			<div class="row">
				<div class="datetimepicker col-sm-4"><?= $form->field($theEntry, 'online_from'); ?></div>
			</div>
			<div class="text-right"><?= Html::submitButton(Yii::t('mn', 'Save changes'), ['class' => 'btn btn-primary']) ?></div>
		</div>
	</div>
</div>
<? ActiveForm::end();

