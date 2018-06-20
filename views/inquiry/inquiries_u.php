<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_inquiries_inc.php');

$this->title = 'Edit inquiry';
$this->params['icon'] = 'plus';
$this->params['breadcrumb'] = [
	['Sales', '@web/spaces/sales'],
	['Inquiries', '@web/inquiries'],
	['View', '@web/inquiries/r/'.$theInquiry['id']],
];
?>
<div class="col-md-12">
	<? $form = ActiveForm::begin(); ?>
	<?= $form->field($theInquiry, 'case_id') ?>
	<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
	<? ActiveForm::end(); ?>
</div>
