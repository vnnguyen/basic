<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;
use app\helpers\DateTimeHelper;

include('_kase_inc.php');



// How they contacted us
$caseHowContactedList = [
    'web'=>'Web',
        'web/adwords'=>'Adwords',
            'web/adwords/google'=>'Google Adwords',
            'web/adwords/bing'=>'Bing Ads',
            'web/adwords/other'=>'Other',
        'web/search'=>'Search',
            'web/search/google'=>'Google search',
            'web/search/bing'=>'Bing search',
            'web/search/yahoo'=>'Yahoo! search',
            'web/search/other'=>'Other',
        'web/link'=>'Referral',
            'web/link/360'=>'Blog 360',
            'web/link/facebook'=>'Facebook',
            'web/link/other'=>'Other',
        'web/ad'=>'Ad online',
            'web/ad/facebook'=>'Facebook',
            'web/ad/voyageforum'=>'VoyageForum',
            'web/ad/routard'=>'Routard',
            'web/ad/sitevietnam'=>'Site-Vietnam',
            'web/ad/other'=>'Other',
        'web/email'=>'Mailing',
        'web/direct'=>'Direct access',

    'nweb'=>'Non-web',
        'nweb/phone'=>'Phone',
        'nweb/email'=>'Email',
            'nweb/email/tripconn'=>'TripConnexion',
            'nweb/email/other'=>'Other',
        'nweb/walk-in'=>'Walk-in',
        'nweb/other'=>'Other', // web pages like Fb, fax, snail mail

    'agent'=>'Via a tour company', // OLD?
];

$caseHowContactedListFormatted = [];
foreach ($caseHowContactedList as $k=>$v) {
    $cnt = count(explode('/', $k));
    $v = str_repeat(' --- ', $cnt - 1). $v;
    $caseHowContactedListFormatted[$k] = $v;
}

$kaseHowFoundList = [
    'returning'=>'Returning',
        'returning/customer'=>'Returning customer',
    'new'=>'New',
        'new/returning/contact'=>'Returning contact (not a customer)',
        'new/nref'=>'Not referred',
            'new/nref/web'=>'Web',
            'new/nref/print'=>'Book/Print',
            'new/nref/event'=>'Event/Seminar',
            'new/nref/other'=>'Other', // travel agent, by chance
        'new/ref'=>'Referred',
            'new/ref/customer'=>'Referred by one of Amica\'s customer',
            'new/ref/amica'=>'Referred by one of Amica\'s staff',
            'new/ref/org'=>'Referred by an organization or one of its members', // Ca nhan, to chuc
            'new/ref/other'=>'Referred from other source',
];


$kaseHowFoundListFormatted = [];
foreach ($kaseHowFoundList as $k=>$v) {
    $cnt = count(explode('/', $k));
    $v = str_repeat(' --- ', $cnt - 1). $v;
    $kaseHowFoundListFormatted[$k] = $v;
}

$countryList = [
    'vn'=>'Vietnam',
    'la'=>'Laos',
    'kh'=>'Cambodia',
    'mm'=>'Myanmar',
    'id'=>'Indonesia',
    'my'=>'Malaysia',
    'th'=>'Thailand',
    'cn'=>'China',
];

$priorityList = [
    'no'=>Yii::t('x', 'No'),
    'yes'=>Yii::t('x', 'Yes'),
    1=>1,
    2=>2,
    3=>3,
    4=>4,
];

?>
<div class="col-md-8">
    <div class="card">
        <?php $form = ActiveForm::begin(); ?>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6"><?= $form->field($theCase, 'company_id')->dropdownList(ArrayHelper::map($companyList, 'id', 'name'), ['prompt'=>'- Select -']) ?></div>
                <? if ($theCase->isNewRecord) { ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Total No. of cases</label>
                        <p class="form-control-static" id="total-cases">Select a company to view</p>
                    </div>
                </div>
                <? } ?>
            </div>
            <div class="row">
                <div class="col-md-9"><?= $form->field($theCase, 'name')->label(Yii::t('k', 'Case name')) ?></div>
                <div class="col-md-3"><?= $form->field($theCase, 'stype')->dropdownList($kaseTypeList)->label(Yii::t('k', 'Category')) ?></div>
            </div>
            <div class="row">
                <div class="col-md-3"><?= $form->field($theCase, 'language')->dropdownList(['en'=>'English', 'fr'=>'Francais', 'vi'=>'Tiếng Việt'], ['prompt'=>'- Select -']) ?></div>
                <div class="col-md-3"><?= $form->field($theCase, 'is_priority')->dropdownList($priorityList) ?></div>
                <div class="col-md-6"><?= $form->field($theCase, 'campaign_id')->dropdownList(ArrayHelper::map($campaignList, 'id', 'name'), ['prompt'=>'( No campaigns )']) ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theCase, 'owner_id')->dropdownList(ArrayHelper::map($ownerList, 'id', 'name'), ['prompt'=>'- Select -'])->label(Yii::t('k', 'Assign to')) ?></div>
                <div class="col-md-6"><?= $form->field($theCase, 'cofr')->dropdownList(ArrayHelper::map($cofrList, 'id', 'name'), ['prompt'=>'- Select -'])->label(Yii::t('k', 'Amica contact in France')) ?></div>
            </div>

            <fieldset>
                <legend>Client's request</legend>
                <p>Leave blank if not applicable.</p>
                <div class="row">
                    <div class="col-md-3"><?= $form->field($theStats, 'start_date')->label('Start date')->hint('2017 or 2017-10 etc.') ?></div>
                    <div class="col-md-3"><?= $form->field($theStats, 'pax_count')->label('No. of pax')->hint('4 or 4-5 etc.') ?></div>
                    <div class="col-md-3"><?= $form->field($theStats, 'day_count')->label('No. of days')->hint('10 or 10-15 etc.') ?></div>
                </div>
                <div class="row">
                    <div class="col-md-12"><?= $form->field($theStats, 'req_countries')->checkboxList($countryList)->label('Destinations') ?></div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Source</legend>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($theCase, 'how_contacted')->dropdownList($caseHowContactedListFormatted, ['prompt'=>'- Select -'])->label('How customer contacted us') ?></div>
                    <div class="col-md-6"><?= $form->field($theCase, 'web_keyword') ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($theCase, 'how_found')->dropdownList($kaseHowFoundListFormatted) ?></div>
                    <div class="col-md-6"><?= $form->field($theCase, 'ref')->hint('If referred by word of mouth') ?></div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Note</legend>
                <?= $form->field($theCase, 'info')->textArea(['rows'=>3])->label(false) ?>
            </fieldset>
            
            <div class="form-actions">
                <?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php

$js = <<<'TXT'
$('#kase-company_id').on('change', function(){
    var id = $(this).val();
    if (id != 0) {
        $('#total-cases').html('Counting...');
        $.ajax({
            method: "POST",
            url: "/b2b/cases/c",
            data: { id: id }
        })
        .done(function(msg){
            $('#total-cases').html(msg);
        })
        .fail(function(msg){
            $('#total-cases').html('Error!');
        })
    }
})   
TXT;

if ($theCase->isNewRecord) {
    $this->registerJs($js);
}