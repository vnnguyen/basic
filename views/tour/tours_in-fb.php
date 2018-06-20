<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

require_once('_tours_inc.php');

$this->title = 'Print tour feedback form - '.$theTour['op_code'];

if ($theCompany) {
    $logoOptionList = [
        'them'=>'Logo and name of '. $theCompany['name'],
        'both'=>'Logo and name of Amica Travel as a receptive agency for '. $theCompany['name'],
        'us'=>'Logo and name of Amica Travel',
        'si'=>'Logo and name of Secret Indochina',
        'none'=>'No logo, only name of '. $theCompany['name'],
        'voyages-villegia'=>'SPECIAL: Name and logo of Voyages Villegia (for Plani-Corpo)',
    ];
}

$languageList = [
    'en'=>'English',
    'fr'=>'Francais',
    'vi'=>'Tiếng Việt',
];

$dayIdList = explode(',', $theTour['day_ids']);
$dayCnt = [];
$cnt = 0;
foreach ($dayIdList as $id) {
    $cnt ++;
    $dayCnt[$cnt] = $cnt;
}

?>
<div class="col-md-8">
    <div class="alert alert-info">
        <strong>CHÚ Ý:</strong><br>
        - Nếu có nhiều tên guide / lái xe thì điền các tên cách nhau bằng dấu phẩy<br>
        - Nếu để trống phần tên guide / lái xe thì form sẽ không in ra câu hỏi cho phần đó<br>
        - Nếu không biết tên guide / lái xe mà vẫn muốn in câu hỏi phần đó ra form thì phải điền tên là <kbd>yes</kbd><br>
    </div>
    <? $form = ActiveForm::begin(); ?>

    <? if ($theCompany) { ?>
    <?= $form->field($theForm, 'logoName')->dropdownList($logoOptionList)->label('This tour is from '.$theCompany['name'].'. Select a logo option') ?>
    <? } ?>

    <div class="row">
        <div class="col-md-6"><?= $form->field($theForm, 'language')->dropDownList($languageList) ?></div>
        <div class="col-md-6"><?= $form->field($theForm, 'paxName') ?></div>
    </div>
    <div class="row">
        <div class="col-md-6"><?= $form->field($theForm, 'guideNames')->input('number', ['min' => 0, 'max' => 10, 'step' => 1]) ?></div>
        <div class="col-md-6"><?= $form->field($theForm, 'driverNames')->input('number', ['min' => 0, 'max' => 10, 'step' => 1]) ?></div>
    </div>
    <div class="text-right"><?= Html::submitButton('Print form', ['class'=>'btn btn-primary']) ?></div>
    <? ActiveForm::end(); ?>
</div>
<div class="col-md-4">
    <p><strong>TOUR ITINERARY</strong></p>
    <ul class="list-unstyled">
<?
$cnt = 0;
foreach ($dayIdList as $id) {
    foreach ($theTour['days'] as $day) {
        if ($id == $day['id']) {
            $cnt ++;
?>
        <li><strong><?= $cnt ?></strong> - <?= $day['name'] ?></li>
<?
        }
    }
}
?>
    </ul>
</div>