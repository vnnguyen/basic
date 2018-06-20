<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_dv_inc.php');

if ($theDv->isNewRecord) {
    Yii::$app->params['page_title'] = 'Thêm dịch vụ mới';
} else {
    Yii::$app->params['page_title'] = 'Sửa dịch vụ: '.$theDv['name'];
}

$currencyList = [
    'VND'=>'VND',
    'USD'=>'USD',
    'EUR'=>'EUR',
];

$countryList = [
    'vn'=>'Vietnam',
    'la'=>'Laos',
    'kh'=>'Cambodia',
    'th'=>'Thailand',
    'mm'=>'Myanmar',
];

$xdayList = [
    ''=>'',
    'night'=>'x nights',
    'day'=>'x days',
];

$whoList = [
    'hn'=>'Hanoi office',
    'sg'=>'Saigon office',
    'ah'=>'An Hoa (Hue)',
    'sr'=>'Siem Reap office',
    'hb'=>'Hoa Brerez (France)',
    'lt'=>'Thonglish (Laos)',
    'lm'=>'Medsanh (Laos)',
    'lf'=>'Feuang (Laos)',
];



?>
<? $form = ActiveForm::begin(); ?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Dịch vụ</h6>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2"><?= $form->field($theDv, 'status')->dropdownList(['on'=>'OK', 'draft'=>'Not OK']) ?></div>
                <div class="col-md-3"><?= $form->field($theDv, 'stype')->dropdownList($dvABCTypeList) ?></div>
                <div class="col-md-3"><?= $form->field($theDv, 'is_dependent')->dropdownList(['no'=>'No', 'yes'=>'Yes']) ?></div>
                <div class="col-md-2"><?= $form->field($theDv, 'sorder') ?></div>
                <div class="col-md-2"><?= $form->field($theDv, 'maxpax') ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theDv, 'grouping') ?></div>
                <div class="col-md-6"><?= $form->field($theDv, 'venue_id') ?></div>
            </div>
            <div class="row">
                <div class="col-md-3"><?= $form->field($theDv, 'search_loc') ?></div>
                <div class="col-md-9"><?= $form->field($theDv, 'name') ?></div>
            </div>
            <div class="row">
                <div class="col-md-3"><?= $form->field($theDv, 'search') ?></div>
                <div class="col-md-3"><?= $form->field($theDv, 'xday')->dropdownList($xdayList)->label('Multiplied by') ?></div>
                <div class="col-md-3"><?= $form->field($theDv, 'whobooks')->dropdownList($whoList) ?></div>
                <div class="col-md-3"><?= $form->field($theDv, 'whopays')->dropdownList($whoList) ?></div>
                <div class="col-md-3"><?//= $form->field($theDv, 'price') ?></div>
                <div class="col-md-3"><?//= $form->field($theDv, 'currency')->dropdownList($currencyList) ?></div>
            </div>
            <div class="row">
                <div class="col-md-3"><?= $form->field($theDv, 'unit') ?></div>
                <div class="col-md-3"><?= $form->field($theDv, 'receipt') ?></div>
                <div class="col-md-3"><?= $form->field($theDv, 'default_vendor') ?></div>
                <div class="col-md-3"><?= $form->field($theDv, 'conds') ?></div>
            </div>
            <?= $form->field($theDv, 'note')->textArea(['rows'=>5]) ?>
            <div class="text-right"><?=Html::submitButton('Save changes', ['class' => 'btn btn-primary']); ?></div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Các đối tượng</h6>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-8"><?= $form->field($theDv, 'name1')->label(false) ?></div>
                <div class="col-sm-4"><?= $form->field($theDv, 'type1')->label(false) ?></div>
            </div>
            <div class="row">
                <div class="col-sm-8"><?= $form->field($theDv, 'name2')->label(false) ?></div>
                <div class="col-sm-4"><?= $form->field($theDv, 'type2')->label(false) ?></div>
            </div>
            <div class="row">
                <div class="col-sm-8"><?= $form->field($theDv, 'name3')->label(false) ?></div>
                <div class="col-sm-4"><?= $form->field($theDv, 'type3')->label(false) ?></div>
            </div>
            <div class="row">
                <div class="col-sm-8"><?= $form->field($theDv, 'name4')->label(false) ?></div>
                <div class="col-sm-4"><?= $form->field($theDv, 'type4')->label(false) ?></div>
            </div>
            <div class="row">
                <div class="col-sm-8"><?= $form->field($theDv, 'name5')->label(false) ?></div>
                <div class="col-sm-4"><?= $form->field($theDv, 'type5')->label(false) ?></div>
            </div>
        </div>
    </div>
</div>
<? ActiveForm::end(); ?>
