<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$templateList = [
	'new'=>'New template (no logo)',
	'old'=>'Old template (logo on top)',
];

if (isset($theBooking['case']['company'])) {
	$logoList['other'] = 'Logo and name of '.$theBooking['case']['company']['name'];
}
$logoList['amica'] = 'Logo and name of Amica Travel';

$languageList = [
	'en'=>'English',
	'fr'=>'Français',
	'vi'=>'Tiếng Việt',
];

$this->title = 'Print welcome banner';
$this->params['icon'] = 'print';
$this->params['breadcrumb'] = [
	['Bookings', 'bookings'],
	['View', 'bookings/r/'.$theBooking['id']],
	['Print welcome banner', URI],
];

?>
<div class="col-md-8">
	<? $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-md-4"><?= $form->field($theForm, 'template')->dropdownList($templateList, ['prompt'=>'- Select -']) ?></div>
		<div class="col-md-4"><?= $form->field($theForm, 'language')->dropdownList($languageList, ['prompt'=>'- Select -']) ?></div>
		<div class="col-md-4"><?= $form->field($theForm, 'logo')->dropdownList($logoList, ['prompt'=>'- Select -']) ?></div>
	</div>
	<?= $form->field($theForm, 'extra')->hint('Eg. Agence réceptive de XANADU Travel au Vietnam') ?>
	<?= $form->field($theForm, 'names')->textArea(['rows'=>3]) ?>
	<div class="row">
		<div class="col-md-4"><?= $form->field($theForm, 'pax')->hint('Eg. 10 pax') ?></div>
		<div class="col-md-4"><?= $form->field($theForm, 'location')->hint('Eg. AF145') ?></div>
		<div class="col-md-4"><?= $form->field($theForm, 'time')->hint('Eg. 13:30') ?></div>
	</div>
	<div class="text-right"><?= Html::submitButton('Print', ['class'=>'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>