<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_venue_inc.php');

Yii::$app->params['page_title'] = 'Edit price/promotion info: '.$theVenue['name'];


?>
<div class="col-md-8">
	<? $form = ActiveForm::begin(['class'=>'form-inline well well-sm']); ?>
	<?= $form->field($theVenue, 'info_pricing')->textArea(['rows'=>30])->label('Information (Markdown-format)') ?>
	<div class="text-right"><?= Html::submitButton('Save changes', ['class'=>'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>
