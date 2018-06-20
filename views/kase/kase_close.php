<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_kase_inc.php');

$this->title = 'Close case: '.$theCase['name'];

Yii::$app->params['page_breadcrumbs'][] = ['View', 'cases/r/'.$theCase['id']];
Yii::$app->params['page_breadcrumbs'][] = ['Close', 'cases/close/'.$theCase['id']];

?>
<div class="col-md-8">
	<div class="alert alert-info">
		<strong>You are about to close this case</strong>
		<br>A closed case will not appear on your List of Active Cases. You can re-open it any time.
		<br>Select a reason and give any additional information below
	</div>
	<? $form = ActiveForm::begin(); ?>
		<?= $form->field($theCase, 'why_closed')->dropdownList($caseWhyClosedList, ['prompt'=>'- Select -']) ?>
		<?= $form->field($theCase, 'closed_note')->textArea(['rows'=>5]) ?>
		<div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>