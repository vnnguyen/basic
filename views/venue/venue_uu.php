<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_venue_inc.php');

Yii::$app->params['page_title'] = 'Edit general info: '.$theVenue['name'];
Yii::$app->params['page_breadcrumbs'] = [
	['Venues', 'venues'],
	['View', 'venues/r/'.$theVenue['id']],
	['Edit', URI],
];
?>
<div class="col-md-8">
	<? $form = ActiveForm::begin(); ?>
	<?= $form->field($theForm, 'image'); ?>
	<?= $form->field($theForm, 'image2'); ?>
	<?= $form->field($theForm, 'location')->textArea(['rows' => 3]); ?>
	<?= $form->field($theForm, 'style')->textArea(['rows' => 3]); ?>
	<?= $form->field($theForm, 'service')->textArea(['rows' => 3]); ?>
	<?= $form->field($theForm, 'facilities')->textArea(['rows' => 3]); ?>
	<?= $form->field($theForm, 'publicRatings')->textArea(['rows' => 3]); ?>
	<?= $form->field($theForm, 'amicaRatings')->textArea(['rows' => 3]); ?>
	<?= $form->field($theForm, 'notes')->textArea(['rows' => 3]); ?>
	<div class="text-right"><?= Html::submitButton('Submit', ['class' => 'btn btn-primary']); ?></div>
	<? ActiveForm::end(); ?>
</div>
