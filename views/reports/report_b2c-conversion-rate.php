<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

Yii::$app->params['page_title'] = Yii::t('x', 'B2C').' - '.Yii::t('x', 'Conversion rate');

include('_report_b2c_inc.php');

function rtrim0($text) {
    return rtrim(rtrim($text, '0'), '.');
}

$kaseChannelList = [
    'k1'=>'K1',
    'k2'=>'K2',
    'k3'=>'K3',
    'k4'=>'K4',
    'k5'=>'K5',
    'k6'=>'K6',
    'k7'=>'K7',
    'k8'=>'K8',
];


$currencyList = [
    'EUR'=>'EUR',
    'USD'=>'USD',
    'VND'=>'VND',
];

$destList = \common\models\Country::find()
    ->select(['code', 'name_en'])
    ->where(['code'=>['vn', 'la', 'kh', 'mm', 'th', 'my', 'id', 'cn']])
    ->orderBy('name_en')
    ->asArray()
    ->all();

$currency = 'USD';

$kaseViewList = [
    'created'=>Yii::t('x', 'Created'),
    'assigned'=>Yii::t('x', 'Assigned'),
    'closed'=>Yii::t('x', 'Closed'),
    'won'=>Yii::t('x', 'Won'),
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

// Date list
$humanDateList = [
    'custom'=>Yii::t('x', 'Custom date'),
];

$yList = [];
$yMin = 2007;
$yMax = date('Y') + 1;
foreach ($humanDateList as $lkey=>$lvalue) {
    $yList[] = [
        'name'=>$lkey,
        'value'=>$lvalue,
        'group'=>Yii::t('x', 'Quick select'),
    ];
}
for ($y = $yMax; $y >= $yMin; $y --) {
    $yList[] = [
        'name'=>$y,
        'value'=>$y.' - '.Yii::t('x', 'All year'),
        'group'=>$y,
    ];
    for ($m = 12; $m >= 1; $m --) {
        $yList[] = [
            'name'=>$y.'-'.str_pad($m, 2, '0', STR_PAD_LEFT),
            'value'=>$y.'-'.str_pad($m, 2, '0', STR_PAD_LEFT),
            'group'=>$y,
        ];
    }
}
$createdDateList = ArrayHelper::map($yList, 'name', 'value', 'group');

?>
<style>
.index-caret {position:absolute; margin:-6px 0 0 0}
.select2.select2-container {width:100%!important}
</style>
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <div id="div-toggle-filters">
                <strong class="text-info"><?= Yii::t('x', 'Viewing B2C cases') ?></strong>
                &middot;
                <?php if ($date_created != '') { ?>
                    <strong><?= Yii::t('x', 'Case created') ?>:</strong>
                    <?php if ($date_created == 'custom' && $date_created_custom != '') { ?>
                    <?= $date_created_custom ?>;
                    <?php } else { ?>
                    <?= $date_created ?>;
                    <?php } ?>
                <?php } ?>
                <?php if ($date_assigned != '') { ?>
                    <strong><?= Yii::t('x', 'Case assigned') ?>:</strong>
                    <?php if ($date_assigned == 'custom' && $date_assigned_custom != '') { ?>
                    <?= $date_assigned_custom ?>;
                    <?php } else { ?>
                    <?= $date_assigned ?>;
                    <?php } ?>
                <?php } ?>
                <?php if ($date_won != '') { ?><strong><?= Yii::t('x', 'Deal won') ?>:</strong> <?= $date_won ?>; <?php } ?>
                <?php if ($date_closed != '') { ?>
                    <strong><?= Yii::t('x', 'Case closed') ?>:</strong>
                    <?php if ($date_closed == 'custom' && $date_closed_custom != '') { ?>
                    <?= $date_closed_custom ?>;
                    <?php } else { ?>
                    <?= $date_closed ?>;
                    <?php } ?>
                <?php } ?>

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

                <?php if ($pv != '') { ?><strong><?= Yii::t('x', 'Pacific Voyages') ?>:</strong> <?= $yesNoList[$pv] ?? $pv ?>; <?php } ?>

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

                <?php if ($date_start != '') { ?>
                    <strong><?= Yii::t('x', 'Tour start date') ?>:</strong>
                    <?php if ($date_start == 'custom' && $date_start_custom != '') { ?>
                    <?= $date_start_custom ?>;
                    <?php } else { ?>
                    <?= $date_start ?>;
                    <?php } ?>
                <?php } ?>
                <?php if ($date_end != '') { ?>
                    <strong><?= Yii::t('x', 'Tour end date') ?>:</strong>
                    <?php if ($date_end == 'custom' && $date_end_custom != '') { ?>
                    <?= $date_end_custom ?>;
                    <?php } else { ?>
                    <?= $date_end ?>;
                    <?php } ?>
                <?php } ?>

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
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Case created') ?>:</label>
                                <div class="col-sm-4"><?= Html::dropdownList('date_created', $date_created, $createdDateList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Any time')]) ?></div>
                                <div class="col-sm-5 <?= $date_created != 'custom' ? 'd-none' : '' ?> has-drp"><?= Html::textInput('date_created_custom', $date_created_custom, ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Select date')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3"><?= Yii::t('x', 'Case assigned') ?>:</label>
                                <div class="col-sm-4"><?= Html::dropdownList('date_assigned', $date_assigned, $createdDateList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Any time')]) ?></div>
                                <div class="col-sm-5 <?= $date_assigned != 'custom' ? 'd-none' : '' ?> has-drp"><?= Html::textInput('date_assigned_custom', $date_assigned_custom, ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Select date')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Deal won') ?>:</label>
                                <div class="col-sm-4"><?= Html::dropdownList('date_won', $date_won, $createdDateList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Any time')]) ?></div>
                                <div class="col-sm-5 <?= $date_won != 'custom' ? 'd-none' : '' ?> has-drp"><?= Html::textInput('date_won_custom', $date_won_custom, ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Select date')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Case closed') ?>:</label>
                                <div class="col-sm-4"><?= Html::dropdownList('date_closed', $date_closed, $createdDateList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Any time')]) ?></div>
                                <div class="col-sm-5 <?= $date_closed != 'custom' ? 'd-none' : '' ?> has-drp"><?= Html::textInput('date_closed_custom', $date_closed_custom, ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Select date')]) ?></div>
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
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Channel - K') ?>:</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <?= Html::dropdownList('kx', $kx, array_merge(['k0'=>'Chưa điền K', 'k17'=>'K1-K7'], $kaseChannelList), ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?>
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
                                <label class="col-sm-3"><?= Yii::t('x', 'Tour start') ?>:</label>
                                <div class="col-sm-4"><?= Html::dropdownList('date_start', $date_start, $createdDateList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Any time')]) ?></div>
                                <div class="col-sm-5 <?= $date_start != 'custom' ? 'd-none' : '' ?> has-drp"><?= Html::textInput('date_start_custom', $date_start_custom, ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Select date')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3"><?= Yii::t('x', 'Tour end') ?>:</label>
                                <div class="col-sm-4"><?= Html::dropdownList('date_end', $date_end, $createdDateList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Any time')]) ?></div>
                                <div class="col-sm-5 <?= $date_end != 'custom' ? 'd-none' : '' ?> has-drp"><?= Html::textInput('date_end_custom', $date_end_custom, ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Select date')]) ?></div>
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
                    <div>
                        <button class="btn btn-primary" type="submit"><?= Yii::t('x', 'Go') ?></button>
                        <a class="action-cancel-filters"><?= Yii::t('x', 'Cancel') ?></a>
                        &middot;
                        <a class="action-reset-filters" href="?"><?= Yii::t('x', 'Reset') ?></a>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel-body">
            <div id="chart1" style="height:400px;"></div>
        </div>
        <div class="panel-body">
            <?php if ($groupby == 'source' || $groupby == 'seller') { ?>
            <p>Click tên nhóm để ẩn/hiện số hồ sơ.</p>
            <?php } ?>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Chỉ số \ Tháng (năm <?= $year ?>)</th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <th class="text-center" width="6%"><?= $m ?></th>
                        <?php } ?>
                        <th class="text-center" width="7%">Cả năm</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($groupby == 'source') { ?>

                    <?php foreach ($caseHowContactedListFormatted as $hck=>$hcn) { ?>
                    <tr class="success">
                        <td class="text-bold" onclick="$('.togglable').toggle(0);"><?= $hcn ?></td>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <td class="text-success text-center">
                            <?php if ($result['grouped-source'][$hck][$year][$m]['total'] == 0) { ?>
                            <?php } else { ?>
                            <?= rtrim0(number_format(100 * $result['grouped-source'][$hck][$year][$m]['won'] / $result['grouped-source'][$hck][$year][$m]['total'], 2)) ?>%
                            <?php } ?>
                        </td>
                        <?php } ?>
                        <td class="text-success text-center text-bold">
                            <?php if ($result['grouped-source'][$hck][$year][0]['total'] == 0) { ?>
                            <?php } else { ?>
                            <?= rtrim0(number_format(100 * $result['grouped-source'][$hck][$year][0]['won'] / $result['grouped-source'][$hck][$year][0]['total'], 2)) ?>%
                            <?php } ?>
                        </td>
                    </tr>
                        <?php foreach ($indexList as $index=>$item) { ?>
                    <tr class="togglable">
                        <th>
                            <i class="fa fa-square position-left" style="color:<?= $item['color'] ?>"></i>
                            <?= $item['label'] ?>
                        </th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <td class="text-center <?= $index == 'total' ? 'text-bold' : '' ?>">
                            <?= $result['grouped-source'][$hck][$year][$m][$index] ?>
                        </td>
                        <?php } ?>
                        <td class="text-center text-bold">
                            <?= $result['grouped-source'][$hck][$year][0][$index] ?>
                        </td>
                    </tr>
                        <?php } ?>
                    <?php } ?>

                    <?php } elseif ($groupby == 'seller') { ?>

                    <?php foreach ($result['grouped-seller'] as $sid=>$sre) {
                        $ownerName = $sid;
                        foreach ($ownerList as $owner) {
                            if ($owner['id'] == $sid) {
                                $ownerName = $owner['lname'].' - '.$owner['email'];
                                break;
                            }
                        }
                        ?>
                    <tr class="success">
                        <td class="text-bold" onclick="$('.togglable').toggle(0);"><?= $ownerName ?></td>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <td class="text-success text-center">
                            <?php if ($result['grouped-seller'][$sid][$year][$m]['total'] == 0) { ?>
                            <?php } else { ?>
                            <?= rtrim0(number_format(100 * $result['grouped-seller'][$sid][$year][$m]['won'] / $result['grouped-seller'][$sid][$year][$m]['total'], 2)) ?>%
                            <?php } ?>
                        </td>
                        <?php } ?>
                        <td class="text-success text-center text-bold">
                            <?php if ($result['grouped-seller'][$sid][$year][0]['total'] == 0) { ?>
                            <?php } else { ?>
                            <?= rtrim0(number_format(100 * $result['grouped-seller'][$sid][$year][0]['won'] / $result['grouped-seller'][$sid][$year][0]['total'], 2)) ?>%
                            <?php } ?>
                        </td>
                    </tr>
                        <?php foreach ($indexList as $index=>$item) { ?>
                    <tr class="togglable">
                        <th>
                            <i class="fa fa-square position-left" style="color:<?= $item['color'] ?>"></i>
                            <?= $item['label'] ?>
                        </th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <td class="text-center <?= $index == 'total' ? 'text-bold' : '' ?>">
                            <?= $result['grouped-seller'][$sid][$year][$m][$index] ?>
                        </td>
                        <?php } ?>
                        <td class="text-center text-bold">
                            <?= $result['grouped-seller'][$sid][$year][0][$index] ?>
                        </td>
                    </tr>
                        <?php } ?>
                    <?php } ?>

                    <?php } else { ?>
                    <tr>
                        <th>
                            <div><i class="fa fa-star position-left" style="color:gold"></i> <?= Yii::t('x', 'Conversion rate') ?> (<?= Yii::t('x', 'Won') ?>/<?= Yii::t('x', 'Total') ?>)</div>
                            <div><i class="fa fa-star position-left" style="color:purple"></i> <?= Yii::t('x', 'Highest possible rate') ?> (<?= Yii::t('x', 'Won') ?>+<?= Yii::t('x', 'Pending') ?>/<?= Yii::t('x', 'Total') ?>)</div>
                        </th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <td class="text-center success text-success" width="6%">
                            <div style="font-size:150%"><?= $result['filtered'][$year][$m]['total'] == 0 ? '0' : rtrim0(number_format(100 * $result['filtered'][$year][$m]['won'] / $result['filtered'][$year][$m]['total'], 2))?>%</div>
                            <div><?= $result['filtered'][$year][$m]['total'] == 0 ? '0' : rtrim0(number_format(100 * ($result['filtered'][$year][$m]['won'] + $result['filtered'][$year][$m]['pending']) / $result['filtered'][$year][$m]['total'], 2))?>%</div>
                        </td>
                        <?php } ?>
                        <td class="text-center success text-bold text-success" width="8%">
                            <div style="font-size:150%"><?= $result['filtered'][$year][0]['total'] == 0 ? '0' : rtrim0(number_format(100 * $result['filtered'][$year][0]['won'] / $result['filtered'][$year][0]['total'], 2))?>%</div>
                            <div><?= $result['filtered'][$year][0]['total'] == 0 ? '0' : rtrim0(number_format(100 * ($result['filtered'][$year][0]['won'] + $result['filtered'][$year][0]['pending']) / $result['filtered'][$year][0]['total'], 2))?>%</div>
                        </td>
                    </tr>
                    <?php foreach ($indexList as $index=>$item) { ?>
                    <tr>
                        <th>
                            <i class="fa fa-square position-left" style="color:<?= $item['color'] ?>"></i>
                            <?= $item['label'] ?> (<?= Yii::t('x', 'Filtered') ?>/<?= Yii::t('x', 'Total') ?>)
                        </th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <td class="text-center <?= $index == 'total' ? 'text-bold' : '' ?>">
                            <?= Html::a($result['filtered'][$year][$m][$index], '/cases?is_b2b=no&date_created='.$year.'-'.str_pad($m, 2, '0', STR_PAD_LEFT).'&deal_status='.$index) ?>
                            /
                            <?= $result['total'][$year][$m][$index] ?>
                            <!-- <div class="text-muted"><?= $result['total'][$year][$m][$index] == 0 ? '0' : number_format(100 * $result['filtered'][$year][$m][$index] / $result['total'][$year][$m][$index], 2)?>%</div> -->
                        </td>
                        <?php } ?>
                        <td class="text-center text-bold">
                            <?= Html::a($result['filtered'][$year][0][$index], '/cases?is_b2b=no&date_created='.$year.'&deal_status='.$index) ?>
                            /
                            <?= $result['total'][$year][0][$index] ?>
                            <!-- <div class="text-muted"><?= $result['total'][$year][0]['total'] == 0 ? '0' : number_format(100 * $result['filtered'][$year][0][$index] / $result['total'][$year][0]['total'], 2)?>%</div> -->
                        </td>
                    </tr>
                    <?php } // foreach indexList ?>
                    <?php } // if grouped by source ?>
                </tbody>
            </table>
        </div>
    </div>
</div> 
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawVisualization);

    <?php $chartIndexList = ['total', 'lost', 'won', 'pending']; ?>
    function drawVisualization() {
        var data = google.visualization.arrayToDataTable([
            ['<?= Yii::t('x', 'Month') ?>'<?php foreach ($chartIndexList as $index) { ?>, '<?= $indexList[$index]['label'] ?>'<?php }?>],
            <?php
            foreach ($result['filtered'] as $y=>$r) {
                for ($m = 1; $m <= 12; $m ++) { ?>
            ['<?= $m ?>'<?php foreach ($chartIndexList as $index) { ?>, <?= $result['filtered'][$year][$m][$index]?><?php }?>],
            <?php } }?>
      
        ]);
    
        var options = {
            title : 'Number of Cases',
            vAxis: {title: 'Số hồ sơ'},
            hAxis: {title: 'Tháng (<?= $year ?>)'},
            seriesType: 'bars',
            series: {0: {type: 'line'}},
            colors: [<?php foreach ($chartIndexList as $index) { echo '\'', $indexList[$index]['color'], '\'', ($index == 'pending' ? '' : ', '); } ?>],
            isStacked: true
        };
        
        var chart = new google.visualization.ComboChart(document.getElementById('chart1'));
        chart.draw(data, options);
    }
</script>
  
<?php

$js = <<<'JS'
    // $('.selectpicker').selectpicker();
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
Yii::$app->params['js'] = $js;