<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_kase_inc.php');

$this->title = 'Edit source: '.$theCase['name'];
$this->params['breadcrumb'][] = ['View', 'cases/r/'.$theCase['id']];
$this->params['breadcrumb'][] = ['Edit', 'cases/u/'.$theCase['id']];


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

$caseHowFoundList = [
    'returning'=>'Returning',
        'returning/customer'=>'Returning customer',
        'returning/contact'=>'Returning contact (not a customer)',
    'new'=>'New',
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


$caseHowFoundListFormatted = [];
foreach ($caseHowFoundList as $k=>$v) {
    $cnt = count(explode('/', $k));
    $v = str_repeat(' --- ', $cnt - 1). $v;
    $caseHowFoundListFormatted[$k] = $v;
}

?>
<div class="col-md-8">
    <? $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6"><?= $form->field($theCase, 'how_contacted')->dropdownList($caseHowContactedListFormatted, ['prompt'=>'- Select -'])->label('How customer contacted us & web referral') ?></div>
        <div class="col-md-6"><?= $form->field($theCase, 'web_keyword')->label('If search or ad, what keywords') ?></div>
    </div>
    <div class="row">
        <div class="col-md-6"><?= $form->field($theCase, 'company_id')->dropdownList(ArrayHelper::map($companyList, 'id', 'name'), ['prompt'=>'- Select -'])->label('If via a company, what company') ?></div>
        <div class="col-md-6"><?= $form->field($theCase, 'campaign_id')->dropdownList(ArrayHelper::map($campaignList, 'id', 'name'), ['prompt'=>'( No campaigns )'])->label('Campaign name if related to a campaign') ?></div>
    </div>
    <div class="row">
        <div class="col-md-6"><?= $form->field($theCase, 'how_found')->dropdownList($caseHowFoundListFormatted, ['prompt'=>'- Select -'])->label('How customer knew about us') ?></div>
        <div class="col-md-6"><?= $form->field($theCase, 'ref')->label('ID of referrer user if Word of mouth') ?></div>
    </div>
    <?= $form->field($theCase, 'info')->textArea(['rows'=>3]) ?>
    <div class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
    <? ActiveForm::end(); ?>
</div>
<div class="col-md-4">
    <p><strong>Chỉ dẫn</strong></p>
    <ul>
        <li>Chỉ điền tên công ty tour khi How contacted = Via tour company</li>
        <li>Chỉ điền Referrer ID khi How found = Word of mouth</li>
        <li>Chỉ điền Web referral / Web keyword khi How contacted = Web</li>
        <li>Để trống nếu không biết thông tin</li>
    </ul>
</div>