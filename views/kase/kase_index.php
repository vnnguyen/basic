<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_kase_inc.php');
Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_layout'] = '-t';
$this->params['icon'] = 'briefcase';
Yii::$app->params['page_breadcrumbs'] = [
    ['Sales', '@web'],
    ['B2C cases (total found: '.number_format($pagination->totalCount).')'],
];

$this->params['actions'] = [
    [
        ['icon'=>'plus', 'label'=>'New case', 'link'=>'cases/c', 'active'=>SEG2 == 'c'],
    ],
];

Yii::$app->params['page_title'] = 'B2C cases ('.number_format($pagination->totalCount).')';

$kaseViewList = [
    'created'=>Yii::t('x', 'Created'),
    'assigned'=>Yii::t('x', 'Assigned'),
    'closed'=>Yii::t('x', 'Closed'),
];

$countryList = \common\models\Country::find()
    ->select(['code', 'name'=>'name_'.Yii::$app->language])
    ->where(['status'=>'on'])
    ->orderBy('name')
    ->asArray()
    ->all();
$countryList = ArrayHelper::map($countryList, 'code', 'name');

$kaseDestList = \common\models\Country::find()
    ->select(['code', 'name_en'])
    ->where(['code'=>['vn', 'la', 'kh', 'mm', 'th', 'my', 'id', 'cn']])
    ->orderBy('name_en')
    ->asArray()->all();

$paxAgeGroupList = [
    '0_1'=>'<2',
    '2_11'=>'2-11',
    '12_17'=>'12-17',
    '18_25'=>'18-25',
    '26_34'=>'26-34',
    '35_50'=>'35-50',
    '51_60'=>'51-60',
    '61_70'=>'61-70',
    '71_up'=>'>70',
];

$kaseLanguageList = [
    'fr'=>'Français',
    'en'=>'English',
    'vi'=>'Tiếng Việt',
];
$kasePriorityList = [
    'yes'=>'Priority',
    'no'=>'Non-priority',
];
$kaseStatusList = ['open'=>'Open', 'onhold'=>'On hold', 'closed'=>'Closed'];
$kaseDealStatusList = ['pending'=>'Pending', 'won'=>'Won', 'lost'=>'Lost'];
$kaseOwnerList = [];
$kaseOwnerList[] = [
    'id'=>'all',
    'name'=>Yii::t('x', 'Any seller'),
    'group'=>Yii::t('x', 'Other'),
];
$kaseOwnerList[] = [
    'id'=>'none',
    'name'=>Yii::t('x', 'No seller'),
    'group'=>Yii::t('x', 'Other'),
];
foreach ($ownerList as $seller) {
    $kaseOwnerList[] = [
        'id'=>$seller['id'],
        'name'=>$seller['lname'].' '.$seller['email'],
        'group'=>$seller['status'] == 'on' ? Yii::t('x', 'Active') : Yii::t('x', 'Inactive'),
    ];
}

$consultantInFranceList = [];
$consultantInFranceList[] = [
    'id'=>'13',
    'name'=>'Hoa Bearez',
];
$consultantInFranceList[] = [
    'id'=>'5246',
    'name'=>'Arnaud Levallet',
];
$consultantInFranceList[] = [
    'id'=>'1769',
    'name'=>'Trân (Cao Lê Trân)',
];
$consultantInFranceList[] = [
    'id'=>'767',
    'name'=>'Cô Xuân (Vương Thị Xuân)',
];
$consultantInFranceList[] = [
    'id'=>'688',
    'name'=>'Frédéric Hoeckel',
];

$kaseProspectList = [
    '1'=>'1 *',
    '2'=>'2 **',
    '3'=>'3 ***',
    '4'=>'4 ****',
    '5'=>'5 *****',
];

$kaseDeviceList = [
    'desktop'=>'Desktop',
    'tablet'=>'Tablet',
    'mobile'=>'Mobile',
    'none'=>'None/Unknown',
];

$kaseSiteList = [
    'fr'=>'FR',
    'vac'=>'VAC',
    'val'=>'VAL',
    'vpc'=>'VPC',
    'ami'=>'AMI',
    'en'=>'EN',
];

$kaseDestList = \common\models\Country::find()
    ->select(['code', 'name'=>'name_'.Yii::$app->language])
    ->where(['code'=>['vn', 'la', 'kh', 'mm', 'my', 'id', 'ph', 'cn']])
    ->orderBy('name')
    ->asArray()
    ->all();

$paxAgeGroupList = [
    '0_1'=>'<2',
    '2_11'=>'2-11',
    '12_17'=>'12-17',
    '18_25'=>'18-25',
    '26_34'=>'26-34',
    '35_50'=>'35-50',
    '51_60'=>'51-60',
    '61_70'=>'61-70',
    '71_up'=>'>70',
];
$dkdiemdenList = [
    'any'=>Yii::t('x', 'Any of selected countries'),
    'all'=>Yii::t('x', 'All selected countries'),
    'only'=>Yii::t('x', 'Only selected countries'),
];

?>
<style type="text/css">
.select2.select2-container {width:100%!important;}
.form-group {margin-bottom:4px;}
.bg-prospect-5 {background-color:#930;}
.bg-prospect-4 {background-color:#e60;}
.bg-prospect-3 {background-color:#f80;}
.bg-prospect-2 {background-color:#fb8;}
.bg-prospect-1 {background-color:#fdb;}
.bg-prospect-0 {background-color:#fff;}
.color-prospect-5 {color:#930;}
.color-prospect-4 {color:#e60;}
.color-prospect-3 {color:#f80;}
.color-prospect-2 {color:#fb8;}
.color-prospect-1 {color:#fdb;}
.color-prospect-0 {color:#fff;}
</style>
<div class="col-md-12">
    <?/*
    <select class="form-control" name="contacted">

        <option value="all">How customer contacted us</option>
        <option value="link" <?= $getHowContacted == 'link' ? 'selected="selected"' : ''?>>Link</option>
        <option value="web" <?= $getHowContacted == 'web' ? 'selected="selected"' : ''?>>Web inquiry</option>
        <option value="web-direct" <?= $getHowContacted == 'web-direct' ? 'selected="selected"' : ''?>>- Direct web access</option>
        <option value="web-bingad" <?= $getHowContacted == 'web-bingad' ? 'selected="selected"' : ''?>>- Bing Ad</option>
        <option value="web-adsense" <?= $getHowContacted == 'web-adsense' ? 'selected="selected"' : ''?>>- Adsense</option>
        <option value="web-adwords" <?= $getHowContacted == 'web-adwords' ? 'selected="selected"' : ''?>>- Adwords</option>
        <option value="web-adwords-amica" <?= $getHowContacted == 'web-adwords-amica' ? 'selected="selected"' : ''?>>- - Adwords Amica</option>
        <option value="web-otherad" <?= $getHowContacted == 'web-otherad' ? 'selected="selected"' : ''?>>- Other Ad</option>
        <option value="web-search" <?= $getHowContacted == 'web-search' ? 'selected="selected"' : ''?>>- Search</option>
        <option value="web-search-amica" <?= $getHowContacted == 'web-search-amica' ? 'selected="selected"' : ''?>>- - Search Amica</option>
        <option value="web-trip-connexion" <?= $getHowContacted == 'web-trip-connexion' ? 'selected="selected"' : ''?>>- Via TripConnexion</option>
        <option value="email" <?= $getHowContacted == 'email' ? 'selected="selected"' : ''?>>Email</option>
        <option value="phone" <?= $getHowContacted == 'phone' ? 'selected="selected"' : ''?>>Phone</option>
        <option value="direct" <?= $getHowContacted == 'direct' ? 'selected="selected"' : ''?>>In person</option>
        <option value="agent" <?= $getHowContacted == 'agent' ? 'selected="selected"' : ''?>>Via a travel agency</option>
        <option value="social" <?= $getHowContacted == 'social' ? 'selected="selected"' : ''?>>Social media</option>
        <option value="other" <?= $getHowContacted == 'other' ? 'selected="selected"' : ''?>>Other</option>
        <option value="unknown" <?= $getHowContacted == 'unknown' ? 'selected="selected"' : ''?>>Not known / Not recorded</option>
    </select>
    <select class="form-control" name="found">
        <option value="all">How customer knew about us</option>
        <option value="web" <?= $getHowFound == 'web' ? 'selected="selected"' : ''?>>Web search/ad</option>
        <option value="print" <?= $getHowFound == 'print' ? 'selected="selected"' : ''?>>Press / print</option>
        <option value="event" <?= $getHowFound == 'event' ? 'selected="selected"' : ''?>>Event / Seminar</option>
        <option value="word" <?= $getHowFound == 'word' ? 'selected="selected"' : ''?>>Word of mouth</option>
        <option value="returning" <?= $getHowFound == 'returning' ? 'selected="selected"' : ''?>>Returning customer</option>
        <option value="other" <?= $getHowFound == 'other' ? 'selected="selected"' : ''?>>Other</option>
    </select>
    <select class="form-control" name="campaign_id" style="width:200px;">
        <option value="all">Campaigns</option>
        <option value="0"  <?= $campaign_id == '0' ? 'selected="selected"' : '' ?>>No campaign</option>
        <option value="yes"  <?= $campaign_id == 'yes' ? 'selected="selected"' : '' ?>>Any campaign</option>
        <?php foreach ($campaignList as $case) { ?>
        <option value="<?= $case['id'] ?>" <?= $case['id'] == $campaign_id ? 'selected="selected"' : '' ?>><?= date('d/m/Y', strtotime($case['start_dt'])) ?>: <?= $case['name'] ?></option>
        <?php } ?>
    </select>*/?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div id="div-toggle-filters">
                <strong class="text-info"><?= Yii::t('x', 'Viewing {count} B2C cases', ['count'=>number_format($pagination->totalCount)]) ?></strong>
                &middot;
                <?php if ($year != '') { ?><strong><?= $kaseViewList[$view] ?>:</strong> <?= $month ?>/<?= $year ?>; <?php } ?>
                <?php if ($name != '') { ?><strong><?= Yii::t('x', 'Name') ?>:</strong> <?= $name ?>; <?php } ?>
                <?php if ($priority != '') { ?><strong><?= Yii::t('x', 'Priority') ?>:</strong> <?= $kasePriorityList[$priority] ?? $priority ?>; <?php } ?>
                <?php if ($status != '') { ?><strong><?= Yii::t('x', 'Status') ?>:</strong> <?= $kaseStatusList[$status] ?? $status ?>; <?php } ?>
                <?php if ($deal_status != '') { ?><strong><?= Yii::t('x', 'Sale status') ?>:</strong> <?= $kaseDealStatusList[$deal_status] ?? $deal_status ?>; <?php } ?>
                <?php if ($owner_id != '') { ?><strong><?= Yii::t('x', 'Seller') ?>:</strong>
                    <?php
                    foreach ($kaseOwnerList as $item) {
                        if ($item['id'] == $owner_id) {
                            echo $item['name'];
                            break;
                        }
                    }
                    ?>; <?php } ?>
                <?php if ($cofr != '') { ?><strong><?= Yii::t('x', 'Consultant in France') ?>:</strong>
                    <?php
                    foreach ($consultantInFranceList as $item) {
                        if ($item['id'] == $cofr) {
                            echo $item['name'];
                            break;
                        }
                    }
                    ?>; <?php } ?>

                <?php if ($how_contacted != '') { ?><strong><?= Yii::t('x', 'How customer contacted us') ?>:</strong> <?= $caseHowContactedList[$how_contacted] ?? '' ?>; <?php } ?>
                <?php if ($kx != '') { ?><strong><?= Yii::t('x', 'Channel') ?>:</strong> <?= strtoupper($kx) ?>; <?php } ?>
                <?php if ($how_found != '') { ?><strong><?= Yii::t('x', 'Source') ?>:</strong> <?= $kaseHowFoundList[$how_found] ?? '' ?>; <?php } ?>

                <?php if ($prospect != '') { ?><strong><?= Yii::t('x', 'Prospect') ?>:</strong> <?= $prospectList[$prospect] ?? $prospect ?>; <?php } ?>
                <?php if ($device != '') { ?><strong><?= Yii::t('x', 'Browser device used') ?>:</strong> <?= $kaseDeviceList[$device] ?? $device ?>; <?php } ?>
                <?php if ($site != '') { ?><strong><?= Yii::t('x', 'Request form used') ?>:</strong> <?= $kaseSiteList[$site] ?? $site ?>; <?php } ?>

                <?php if ($nationality != '') { ?><strong><?= Yii::t('x', 'Nationality of travelers') ?>:</strong> <span class="flag-icon flag-icon-<?= $nationality ?>"></span> <?= strtoupper($nationality) ?>; <?php } ?>
                <?php if ($age != '') { ?><strong><?= Yii::t('x', 'Age of travelers') ?>:</strong> <?= $paxAgeGroupList[$age] ?? $age ?>; <?php } ?>
                <?php if ($paxcount != '') { ?><strong><?= Yii::t('x', 'Number of travelers') ?>:</strong> <?= $paxcount ?>; <?php } ?>
                <?php if (!empty($req_countries)) { ?><strong><?= Yii::t('x', 'Countries wishing to visit') ?>:</strong> 
                    <?= $dkdiemdenList[$req_countries_select] ?? $req_countries_select ?>
                    <?php
                    $reqCountries = [];
                    foreach ($req_countries as $req_country) {
                        $reqCountries[] = '<span class="flag-icon flag-icon-'.$req_country.'"></span> '.strtoupper($req_country);
                    }
                    echo implode(' ', $reqCountries);
                    ?>
                ; <?php } ?>
                <?php if ($req_year != '') { ?><strong><?= $req_date == 'start' ? Yii::t('x', 'Travel start date') : Yii::t('x', 'Travel end date') ?>:</strong> <?= $req_month ?>/<?= $req_year ?>; <?php } ?>
                <?php if ($daycount != '') { ?><strong><?= Yii::t('x', 'Number of days available') ?>:</strong> <?= $daycount ?>; <?php } ?>
                <?php if ($budget != '') { ?><strong><?= Yii::t('x', 'Budget') ?>:</strong> <?= $budget ?> <?= $budget_currency ?>; <?php } ?>
                <?php if ($req_travel_type != '') { ?><strong><?= Yii::t('x', 'Type of travel group') ?>:</strong> <?= $kaseTravelTypeList[$req_travel_type] ?? $req_travel_type ?>; <?php } ?>
                <?php if ($req_theme != '') { ?><strong><?= Yii::t('x', 'Travel theme') ?>:</strong> <?= $kaseReqTourThemeList[$req_theme] ?? $req_travel_theme ?>; <?php } ?>
                <?php if ($req_tour != '') { ?><strong><?= Yii::t('x', 'Requested tour') ?>:</strong> <?= $kaseRequestedTourList[$req_tour] ?? $req_tour ?>; <?php } ?>
                <?php if ($req_extension != '') { ?><strong><?= Yii::t('x', 'Requested extension') ?>:</strong> <?= $kaseFormuleList[$req_extension] ?? $req_extension ?>; <?php } ?>

                <a href="#" class="action-show-filters"><?= Yii::t('x', 'Alter conditions') ?></a>
                <a href="#" class="action-cancel-filters" style="display:none;"><?= Yii::t('x', 'Cancel') ?></a>
                &middot;
                <a href="?" class="action-reset-filters"><?= Yii::t('x', 'Reset') ?></a>
            </div>
            <div id="div-filters" style="display:none">
                <hr>
                <form class="form-horizontal">
                    <div class="row">
                        <div class="col-sm-6">
                            <p><span class="text-bold text-uppercase"><?= Yii::t('x', 'Case') ?></span></p>
                            <div class="row form-group">
                                <div class="col-sm-3">
                                    <?= Html::dropdownList('view', $view, $kaseViewList, ['class'=>'form-control']) ?>
                                </div>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-xs-6"><?= Html::dropdownList('year', $year, ArrayHelper::map($yearList, 'y', 'y'), ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any year)')]) ?></div>
                                        <div class="col-xs-6"><?= Html::dropdownList('month', $month, $monthList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any month)')]) ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Case name') ?>:</label>
                                <div class="col-sm-9"><?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Name')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Language') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('language', $language, $kaseLanguageList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Priority') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('is_priority', $priority, $kasePriorityList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Status') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('status', $status, $kaseStatusList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Sale status') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('deal_status', $deal_status, $kaseDealStatusList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Seller') ?>:</label>
                                <div class="col-sm-9 has-select2"><?= Html::dropdownList('owner_id', $owner_id, ArrayHelper::map($kaseOwnerList, 'id', 'name', 'group'), ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Consultant in France') ?>:</label>
                                <div class="col-sm-9 has-select2"><?= Html::dropdownList('cofr', $cofr, ArrayHelper::map($consultantInFranceList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Campaign') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('campaign_id', $campaign_id, [], ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Prospect') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('prospect', $prospect, $kaseProspectList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
                            </div>
                            <p><span class="text-bold text-uppercase"><?= Yii::t('x', 'Source') ?></span></p>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Browser device used') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('device', $device, $kaseDeviceList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Request form used') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('site', $site, $kaseSiteList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Channel') ?>:</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <?= Html::dropdownList('kx', $kx, $kaseChannelList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <p><span class="text-bold text-uppercase"><?= Yii::t('x', 'Customer') ?></span></p>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'How customer contacted us') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('how_contacted', $how_contacted, $caseHowContactedListFormatted, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'How customer found us') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('how_found', $how_found, $kaseHowFoundListFormatted, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
                            </div>

<!--                             <div class="row form-group">
                                <label class="col-sm-3 control-label">(X) <?= Yii::t('x', 'Country of residence') ?>:</label>
                                <div class="col-sm-9 has-select2"><?= Html::textInput('x', '', ['class'=>'form-control', 'placeholder'=>Yii::t('x', '...')]) ?></div>
                            </div> -->
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Nationality') ?>:</label>
                                <div class="col-sm-9 has-select2"><?= Html::dropdownList('nationality', $nationality, $countryList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Age of travelers') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('age', $age, $paxAgeGroupList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
                            </div>

                            <p><span class="text-bold text-uppercase"><?= Yii::t('x', 'Request') ?></span></p>
                            <div class="row form-group">
                                <div class="col-sm-3">
                                    <?= Html::dropdownList('req_date', $req_date, ['start'=>Yii::t('x', 'Travel start date'), 'end'=>Yii::t('x', 'Travel end date')], ['class'=>'form-control']) ?>
                                </div>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-xs-6"><?= Html::textInput('req_year', $req_year, ['class'=>'form-control', 'type'=>'number', 'min'=>date('Y'), 'max'=>10 + date('Y'), 'placeholder'=>Yii::t('x', 'Year, eg. {year}', ['year'=>date('Y')])]) ?></div>
                                        <div class="col-xs-6 "><?= Html::dropdownList('req_month', $req_month, $monthList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any month)')]) ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Number of days available') ?>:</label>
                                <div class="col-sm-9 has-select2"><?= Html::textInput('daycount', $daycount, ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Min[-Max], eg. 10 or 10-20')]) ?></div>
                            </div>

                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Number of travelers') ?>:</label>
                                <div class="col-sm-9 has-select2"><?= Html::textInput('paxcount', $paxcount, ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Min[-Max], eg. 10 or 10-20')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Countries wishing to visit') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('req_countries_select', $req_countries_select, $dkdiemdenList, ['class'=>'form-control']) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label">&nbsp;</label>
                                <div class="col-sm-9 has-select2"><?= Html::dropdownList('req_countries', $req_countries, ArrayHelper::map($kaseDestList, 'code', 'name'), ['class'=>'form-control', 'multiple'=>'multiple']) ?></div>
                            </div>

                            <!--
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Budget') ?>:</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-xs-6"><?= Html::textInput('budget', $budget, ['class'=>'form-control', 'placeholder'=>Yii::t('x', '...')]) ?></div>
                                        <div class="col-xs-6"><?= Html::dropdownList('budget_currency', $budget_currency, ['EUR'=>'EUR', 'USD'=>'USD'], ['class'=>'form-control']) ?></div>
                                    </div>
                                </div>
                            </div>
                            -->
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Type of travel group') ?>:</label>
                                <div class="col-sm-9 has-select2"><?= Html::dropdownList('req_travel_type', $req_travel_type, $kaseTravelTypeList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Travel theme') ?>:</label>
                                <div class="col-sm-9 has-select2"><?= Html::dropdownList('req_theme', $req_theme, $kaseReqTourThemeList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Requested tours') ?>:</label>
                                <div class="col-sm-9 has-select2"><?= Html::dropdownList('req_tour', $req_tour, $kaseRequestedTourList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Requested extensions') ?>:</label>
                                <div class="col-sm-9 has-select2"><?= Html::dropdownList('req_extension', $req_extension, $kaseFormuleList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="-text-right">
                        <button class="btn btn-primary" type="submit"><?= Yii::t('app', 'Go') ?></button>
                        <a class="action-cancel-filters"><?= Yii::t('app', 'Cancel') ?></a>
                        &middot;
                        <a class="action-reset-filters" href="?"><?= Yii::t('app', 'Reset') ?></a>
                    </div>
                </form>
            </div>
        </div>
        <?php if (empty($theCases)) { ?>
        <div class="panel-body text-danger"><?= Yii::t('x', 'No data found.') ?></div>
        <?php } else { ?>

        <div class="table-responsive">
            <table class="table table-narrow table-striped">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th><?= $kaseViewList[$view] ?></th>
                        <th><?= Yii::t('x', 'Case name') ?></th>
                        <th>Owner & assign date</th>
                        <th><?= Yii::t('x', 'Source') ?></th>
                        <th>Destinations</th>
                        <th>Avail. time</th>
                        <th>Days</th>
                        <th>Pax</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($theCases)) { ?><tr><td colspan="7">No cases found. New entries will appear here as soon as someone submits a web form on our site.</td></tr><?php } ?>
                <?php foreach ($theCases as $case) { ?>
                <?php
            $_channel = '';
            // K1
            if ($case['how_contacted'] == 'web/adwords/google') {
                $_channel = 'k1';
            }

            // K2
            if ($case['how_contacted'] == 'web/adwords/bing') {
                $_channel = 'k2';
            }

            // K3
            if (strpos($case['how_contacted'], 'web/search') !== false) {
                $_channel = 'k3';
            }

            // K4
            if (strpos($case['how_contacted'], 'web/link') !== false || strpos($case['how_contacted'], 'web/adonline') !== false || $case['how_contacted'] == 'web') {
                $_channel = 'k4';
            }
            // K5
            if ($case['how_contacted'] == 'web/direct') {
                $_channel = 'k5';
            }
            // K6
            if ($case['how_contacted'] == 'web/email') {
                $_channel = 'k6';
            }
            // K7
            if (strpos($case['how_contacted'], 'nweb') !== false) {
                $_channel = 'k7';
            }
            // K8
            if ($case['stats']['kx'] == 'k8') {
                $_channel = 'k8';
            }
            if ($case['stats']['kx'] == '') {
                $case['stats']['kx'] = $_channel;
            }

                    ?>
                    <tr>
                        <td>
                            <a title="<?=Yii::t('mn', 'Edit')?>" rel="external" class="text-muted" href="<?=DIR?>cases/u/<?=$case['id']?>"><i class="fa fa-edit"></i></a>
                        </td>
                        <td class="text-nowrap"><?= str_replace('/'.date('Y'), '', date_format(date_timezone_set(date_create($case['created_at']), timezone_open('Asia/Saigon')), 'j/n/Y \<\s\p\a\n\ \c\l\a\s\s\=\"\t\e\x\t\-\m\u\t\e\d\"\>H:i\<\/\s\p\a\n\>')) ?></td>
                        <td class="text-nowrap">
                            <?php if ($case['stats']['prospect'] != 0 && $case['stats']['prospect'] != '') { ?>
                            <sup><a href="?prospect=<?= $case['stats']['prospect'] ?>" class="text-bold color-prospect-<?= $case['stats']['prospect'] ?>"><?= $case['stats']['prospect'] ?></a></sup>
                            <?php } ?>
                            <?= Html::a($case['name'], '@web/cases/r/'.$case['id'], ['style'=>$case['is_priority'] == 'yes' ? 'font-weight:bold' : '']) ?>
                            <?php if ($case['status'] == 'onhold') { ?><i class="text-warning fa fa-clock-o"></i><?php } ?>
                            <?php if ($case['status'] == 'closed') { ?><i class="text-muted fa fa-lock"></i><?php } ?>
                            <?php if ($case['deal_status'] == 'won') { ?><i class="text-success fa fa-dollar"></i><?php } ?>
                            <?php if ($case['deal_status'] == 'lost' || ($case['status'] == 'closed' && $case['deal_status'] != 'won')) { ?><i class="text-danger fa fa-dollar"></i><?php } ?>
                        </td>
                        <td class="text-nowrap">
                            <?php if ($case['owner_id'] !== null) { ?>
                            <img class="img-circle" src="<?= DIR ?>timthumb.php?src=<?= $case['owner']['image'] ?>&w=100&h=100" style="width:20px; height:20px">
                            <?=Html::a($case['owner']['nickname'], '?owner_id='.$case['owner']['id'])?>
                            <span class="text-muted"><?= str_replace('/'.date('Y'), '', date('j/n/Y', strtotime($case['ao']))) ?></span>
                            <?php } else { ?>
                            <?= Yii::t('x', 'No seller') ?>
                            <?php } ?>
                        </td>
                        <td class="text-nowrap">
                            <?= $case['campaign_id'] != 0 ? '<span class="label label-info">C</span> ' : '' ?>
                            <span class="text-muted" title="<?= Yii::t('x', 'Source') ?>: <?= $kaseHowFoundList[$case['how_found']] ?? $case['how_found'] ?>"><?= strtoupper(substr($case['how_found'], 0, 1)) ?></span>
                            <?php if (substr($case['how_found'], 0, 8) == 'referred') { ?>
                            <?= Html::a($case['referrer']['name'], '@web/persons/r/'.$case['ref'], ['rel'=>'external']) ?>
                            <?php } ?>
                            &middot;
                            <span class="text-muted " title="Contacted: <?= $caseHowContactedList[$case['how_contacted']] ?? $case['how_contacted'] ?>"><?= $case['stats']['kx'] == '' ? 'KU' : strtoupper($case['stats']['kx']) ?></span>
                            <?php if ($case['web_keyword'] != '') { ?>
                            <span class="text-pink"><?= $case['web_keyword'] ?></span>
                            <?php } ?>
                            
                            <?php
                            /*
                            if ($case['how_contacted'] == 'agent') {
                                echo 'via ', Html::a($case['company']['name'], '@web/companies/r/'.$case['company_id'], ['rel'=>'external']);
                            } else {
                                if ($case['how_contacted'] != '') {
                                    echo $case['how_contacted'];
                                }
                            }
*/
                            ?>
                        </td>
                        <?php if ($case['stats']['is_data_loaded'] == 'yes') { ?>
                        <td><?
                        if ($case['stats']['req_countries'] != '') {
                            $reqCountries = explode('|', $case['stats']['req_countries']);
                            if (!empty($reqCountries)) {
                                foreach ($reqCountries as $reqCountry) {
                                    echo '<span class="flag-icon flag-icon-', $reqCountry, '"></span>';
                                }
                            }
                        }
                        ?></td>
                        <td class="-text-center"><?= $case['stats']['start_date'] ?></td>
                        <td class="text-center"><?= $case['stats']['day_count'] ?></td>
                        <td class="text-center"><?= $case['stats']['pax_count'] ?></td>
                        <?php } else { ?>
                        <td colspan="4"  class="text-center"><?= Html::a('Edit request', '@web/cases/request/'.$case['id']) ?></td>
                        <?php } ?>

                        <td>
                            <?php if ($case['info'] != '') { ?>
                            <i title="<?= Html::encode($case['info']) ?>" class="fa fa-info-circle"></i>
                            <?php } ?>
                            <?php if ($case['status'] == 'closed' && $case['deal_status'] != 'won') { ?>
                            <i title="<?= Html::encode($case['closed_note']) ?>" class="fa fa-exclamation-circle text-danger"></i>
                            <?php } ?>
                        </td>
                    </tr>

                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php } ?>

    <?php if ($pagination->pageSize < $pagination->totalCount) { ?>
    <div class="text-center">
    <?= LinkPager::widget([
        'pagination' => $pagination,
        'firstPageLabel' => '<<',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '>>',
    ]); ?>
    </div>
    <?php } ?>

</div>
<?php

$js = <<<'JS'
    $('.selectpicker').selectpicker();
    $('[data-toggle="popover"]').popover()
    $('.action-show-filters').on('click', function(e){
        e.preventDefault()
        $('#div-filters').show()
        $('.action-show-filters').hide()
        $('.action-cancel-filters').show()
    })
    $('.action-cancel-filters').on('click', function(e){
        e.preventDefault()
        $('#div-filters').hide()
        $('.action-show-filters').show()
        $('.action-cancel-filters').hide()
    })
    $('.has-select2 select').select2()
JS;

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);