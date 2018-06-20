<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$templateList = [
    'new'=>'New template (no logo)',
    'old'=>'Old template (logo on top)',
];

if (isset($theTour['bookings'][0]['case']['company'])) {
    $logoList['other'] = 'Logo and name of '.$theTour['bookings'][0]['case']['company']['name'];
    $logoList['si'] = 'Logo and name of Secret Indochina';
}
$logoList['amica'] = 'Logo and name of Amica Travel';


$languageList = [
    'en'=>'English',
    'fr'=>'Français',
    'vi'=>'Tiếng Việt',
];

$outputList = [
    'html'=>'Web view',
    'pdf-download'=>'PDF file - download',
    'pdf-view'=>'PDF file - view',
];

include('_tours_inc.php');

$this->title = 'Print welcome banner: '.$theTour['op_code'];
$this->params['icon'] = 'print';
$this->params['breadcrumb'] = [
    ['Tour operation', '#'],
    ['Tours', 'tours'],
    [substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
    [$theTour['op_code'], 'tours/r/'.$theTourOld['id']],
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
    <?= $form->field($theForm, 'names')->textArea(['rows'=>5]) ?>
    <div class="row">
        <div class="col-md-4"><?= $form->field($theForm, 'pax')->hint('Eg. 10 pax') ?></div>
        <div class="col-md-4"><?= $form->field($theForm, 'location')->hint('Eg. AF145') ?></div>
        <div class="col-md-4"><?= $form->field($theForm, 'time')->hint('Eg. 13:30') ?></div>
    </div>
    <div class="row">
        <div class="col-md-4"><?= $form->field($theForm, 'output')->dropdownList($outputList) ?></div>
    </div>
    <div class="text-right"><?= Html::submitButton('Print', ['class'=>'btn btn-primary']) ?></div>
    <? ActiveForm::end(); ?>
</div>