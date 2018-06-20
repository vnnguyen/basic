<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_bookings_inc.php');

$this->title = 'Edit pax info';

$currencyList = [
    'USD'=>'USD',
    'EUR'=>'EUR',
    'VND'=>'VND',
];

$genderList = [
    'male'=>'Male',
    'female'=>'Female',
];

for ($i = 1; $i <= 31; $i ++) {
    $dayList[$i] = $i;
}

for ($i = 1; $i <= 12; $i ++) {
    $monthList[$i] = $i.' - '.Yii::t('reg', date('F', strtotime('2015-'.$i)));
}

$thisYear = date('Y');
for ($i = $thisYear; $i >= 1900; $i --) {
    $yearList[$i] = $i;
}
for ($i = 30 + $thisYear; $i >= $thisYear; $i --) {
    $yearListExtended[$i] = $i;
}

$form = ActiveForm::begin(); ?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            Case: <?= Html::a($theBooking['case']['name'], '@web/cases/r/'.$theBooking['case']['id']) ?>
            |
            Booking: <?= Html::a('#'.$theBooking['id'], '@web/bookings/r/'.$theBooking['id']) ?>
            |
            Tour: <?= Html::a($theBooking['product']['op_code'], '@web/products/sb/'.$theBooking['product']['id']) ?>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th><?= Yii::t('b', 'Family name(s)') ?></th>
                        <th><?= Yii::t('b', 'Given name(s)') ?></th>
                        <th><?= Yii::t('b', 'Gender') ?></th>
                        <th><?= Yii::t('b', 'Date of birth') ?></th>
                        <th><?= Yii::t('b', 'Nationality') ?></th>
                        <th><?= Yii::t('b', 'Passport No.') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theBooking['people'] as $pax) { ?>
                    <tr>
                        <td><?= $pax['fname'] ?></td>
                        <td><?= $pax['lname'] ?></td>
                        <td><?= $pax['gender'] ?></td>
                        <td><?= implode('/', [$pax['bday'], $pax['bmonth'], $pax['byear']]) ?></td>
                        <td><span title="<?= strtoupper($pax['country_code']) ?>" class="flag-icon flag-icon-<?= $pax['country_code'] ?>"></span> <?= $pax['country']['name_en'] ?></td>
                        <td><?= $pax['id'] ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Edit pax info</h6>
        </div>
        <div class="panel-body">
<div class="row">
    <div class="col-md-6"><?=$form->field($theForm, 'name')->label(Yii::t('x', 'Display name of this customer')) ?></div>
</div>

<h4><?= Yii::t('reg', 'Passport information') ?></h4>
<p><strong><?= Yii::t('reg', 'Please type each field exactly as appears in the passport') ?></strong>.</p>
<div class="row">
    <div class="col-md-4"><?= $form->field($theForm, 'pp_number') ?></div>
    <div class="col-md-8"><?= $form->field($theForm, 'pp_country_code')->dropdownList(ArrayHelper::map($countryList, 'code', 'name_en'), ['prompt'=>Yii::t('reg', '- Select -')]); ?></div>
</div>
<div class="row">
    <div class="col-md-6"><?= $form->field($theForm, 'pp_name_1') ?></div>
    <div class="col-md-6"><?= $form->field($theForm, 'pp_name_2') ?></div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label"><?= Yii::t('reg', 'Date of birth') ?></label>
            <div class="row">
                <div class="col-md-3 col-xs-4"><?=$form->field($theForm, 'pp_bday')->label(false)->dropdownList($dayList, ['prompt'=>yii::t('reg', 'Day')]) ?></div>
                <div class="col-md-5 col-xs-4"><?=$form->field($theForm, 'pp_bmonth')->label(false)->dropdownList($monthList, ['prompt'=>yii::t('reg', 'Month')]) ?></div>
                <div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'pp_byear')->label(false)->dropdownList($yearList, ['prompt'=>yii::t('reg', 'Year')]) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3"><?= $form->field($theForm, 'pp_gender')->dropdownList($genderList, ['prompt'=>Yii::t('reg', '- Select -')]) ?></div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label"><?= Yii::t('reg', 'Passport issue date') ?></label>
            <div class="row">
                <div class="col-md-3 col-xs-4"><?=$form->field($theForm, 'pp_iday')->label(false)->dropdownList($dayList, ['prompt'=>yii::t('reg', 'Day')]) ?></div>
                <div class="col-md-5 col-xs-4"><?=$form->field($theForm, 'pp_imonth')->label(false)->dropdownList($monthList, ['prompt'=>yii::t('reg', 'Month')]) ?></div>
                <div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'pp_iyear')->label(false)->dropdownList($yearList, ['prompt'=>yii::t('reg', 'Year')]) ?></div>
            </div>            
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label"><?= Yii::t('reg', 'Passport expiry date') ?></label>
            <div class="row">
                <div class="col-md-3 col-xs-4"><?=$form->field($theForm, 'pp_eday')->label(false)->dropdownList($dayList, ['prompt'=>yii::t('reg', 'Day')]) ?></div>
                <div class="col-md-5 col-xs-4"><?=$form->field($theForm, 'pp_emonth')->label(false)->dropdownList($monthList, ['prompt'=>yii::t('reg', 'Month')]) ?></div>
                <div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'pp_eyear')->label(false)->dropdownList($yearListExtended, ['prompt'=>yii::t('reg', 'Year')]) ?></div>
            </div>
        </div>
    </div>
</div>
<h4><?= Yii::t('reg', 'Contact information') ?></h4>
<div class="row">
    <div class="col-md-6"><?=$form->field($theForm, 'tel_1') ?></div>
    <div class="col-md-6"><?=$form->field($theForm, 'tel_2') ?></div>
</div>
<div class="row">
    <div class="col-md-6"><?=$form->field($theForm, 'email') ?></div>
    <div class="col-md-6"><?=$form->field($theForm, 'website') ?></div>
</div>
<div class="row">
    <div class="col-md-6"><?=$form->field($theForm, 'profession') ?></div>
    <div class="col-md-6"><?=$form->field($theForm, 'place_of_birth') ?></div>
</div>
<?=$form->field($theForm, 'address') ?>

<h4><?= Yii::t('reg', 'Other notes about this person' ) ?></h4>
<?= $form->field($theForm, 'note')->textArea(['rows'=>5])->label(Yii::t('reg', 'Health conditions, meals and other special requests')) ?>

            <div><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
        </div>
    </div>
</div>
<? ActiveForm::end();