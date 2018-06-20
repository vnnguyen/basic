<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_tours_inc.php');

Yii::$app->params['page_title'] = 'Tour settings: '.$theTour['op_code'];
Yii::$app->params['page_breadcrumb'] = [
	['Tour operation', '#'],
	['Tours', 'tours'],
	[substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
	['View', 'tours/r/'.$theTourOld['id']],
	['Settings'],
];

?>
<div class="col-md-8">
	<? $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-md-6"><?= $form->field($theForm, 'show_client')->dropdownList(['No'=>'No', 'Yes'=>'Yes']) ?></div>
	</div>
	<div><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>
