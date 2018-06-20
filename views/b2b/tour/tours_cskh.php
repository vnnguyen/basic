<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_tours_inc.php');

$this->title = 'Phân công CSKH tour '.$theTour['op_code'];
$this->params['breadcrumb'] = [
	['Tour operation', '#'],
	['Tours', 'tours'],
	[substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
	[$theTour['op_code'], 'tours/r/'.$theTourOld['id']],
	['Customer care staff'],
];
?>
<style type="text/css">
label {display:block;}
</style>
<div class="col-md-8">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h6 class="panel-title">Select one or more persons</h6>
		</div>
		<div class="panel-body">
		<? $form = ActiveForm::begin(); ?>
			<?= $form->field($theForm, 'css')->checkboxList(ArrayHelper::map($cssList, 'id', 'name')) ?>
			<div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
		<? ActiveForm::end(); ?>		
		</div>
	</div>	
</div>