<?php
use app\widgets\LinkPager;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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
    'won'=>Yii::t('x', 'Won'),
];

$countryList = \common\models\Country::find()
    ->select(['code', 'name'=>'name_'.Yii::$app->language])
    ->where(['status'=>'on'])
    ->orderBy('name')
    ->asArray()
    ->all();
$countryList = ArrayHelper::map($countryList, 'code', 'name');
// $cookies = Yii::$app->response->cookies; //Yii::$app->request->cookies;//Yii::$app->response->cookies->get('fileDownloadToken')
// if (($cookie = $cookies->get('fileDownloadToken')) !== null) {
//     // var_dump($cookie);die;
//     Yii::$app->response->cookies->remove('fileDownloadToken');
// }
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
<style type="text/css">
.form-control:focus {background-color:#ddf}
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
    <div class="card">
        <div class="card-body">
            <div id="div-toggle-filters">
                <strong class="text-info"><?= Yii::t('x', 'Viewing {count} B2C cases', ['count'=>number_format($pagination->totalCount)]) ?></strong>
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
                <?php if ($is_priority != '') { ?><strong><?= Yii::t('x', 'Priority') ?>:</strong> <?= $priorityList[$is_priority] ?? $is_priority ?>; <?php } ?>
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

                <?php if ($kx != '') { ?><strong><?= Yii::t('x', 'Channel') ?>:</strong> <?= $kx == 'k0' ? '(No data)' : strtoupper($kx) ?>; <?php } ?>
                <?php if ($kxcost == 'yes' || $kxcost == 'no') { ?><strong><?= Yii::t('x', 'K cost applied') ?>:</strong> <?= ucwords($kxcost) ?>; <?php } ?>

                <?php if ($how_found != '') { ?><strong><?= Yii::t('x', 'Source') ?>:</strong> <?= $how_found == 't0' ? '(No data)' : $kaseHowFoundList[$how_found] ?? '' ?>; <?php } ?>

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
            <div id="div-filters" style="<?= isset($_GET['filter']) ? '' : 'display:none' ?>">
                <hr>
                <form class="form-horizontal" id="search_form">
                    <div class="row">
                        <div class="col-sm-6">
                            <p><span class="text-bold text-uppercase"><?= Yii::t('x', 'Case') ?></span></p>
                            <?php if (isset($_GET['filter'])) {
                                echo Html::hiddenInput('filter', 'yes');
                            } ?>
                            <!--
                            <div class="row form-group">
                                <div class="col-sm-3">
                                    <?= Html::dropdownList('view', $view, $kaseViewList, ['class'=>'form-control']) ?>
                                </div>
                            </div>
                            -->
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
                                <div class="col-sm-9"><?= Html::dropdownList('is_priority', $is_priority, $priorityList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
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
                                        <div class="col-sm-6">
                                            <?= Html::dropdownList('kxcost', $kxcost, ['yes'=>Yii::t('x', 'K cost applied'), 'no'=>Yii::t('x', 'K cost not applied')], ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?>
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
                                <div class="col-sm-9"><?= Html::dropdownList('how_found', $how_found, array_merge($kaseHowFoundListFormatted, ['t0'=>'(No data)']), ['class'=>'form-control', 'prompt'=>Yii::t('x', '(Any)')]) ?></div>
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
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Export columns') ?>:</label>
                                <div class="col-sm-9 has-select2"><?= Html::dropdownList('export_column', $export_column, $export_columns, ['class'=>'form-control', 'multiple'=>'multiple', 'id' => 'export_fields']) ?>
                                    <?= Html::input('hidden', 'downloadToken', '', ['id' => 'download_token_value']) ?>
                                </div>
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
        <?php if (empty($theCases)) { ?>
        <div class="card-body text-danger"><?= Yii::t('x', 'No data found.') ?></div>
        <?php } else { ?>

        <div class="table-responsive">
            <table class="table table-narrow table-striped">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th><?= Yii::t('x', 'Created') ?></th>
                        <?php if ($view != 'created') { ?><th><?= Yii::t('x', $kaseViewList[$view]) ?></th><?php } ?>
                        <th><?= Yii::t('x', 'Case name') ?></th>
                        <th><?= Yii::t('x', 'Owner & assign date') ?></th>
                        <?php if (USER_ID == 1) { ?><th><?= Yii::t('x', 'reK') ?></th><?php } ?>
                        <?php if (in_array(USER_ID, [1, 695]) && $kxcost == 'yes') { ?>
                        <th class="text-right"><?= Yii::t('x', 'K cost') ?></th>
                        <?php } ?>
                        <th><?= Yii::t('x', 'Source') ?></th>
                        <th><?= Yii::t('x', 'Destinations') ?></th>
                        <th><?= Yii::t('x', 'Avail. time') ?></th>
                        <th class="text-center"><?= Yii::t('x', 'Days') ?></th>
                        <th class="text-center"><?= Yii::t('x', 'Pax') ?></th>
                        <th><?= Yii::t('x', 'Note') ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($theCases as $case) { ?>
                    <?php
                    $reK = '-';
                    if ($case['how_contacted'] == 'web/adwords/google') {
                        $reK = 'k1';
                    } elseif ($case['how_contacted'] == 'web/adwords/bing') {
                        $reK = 'k2';
                    } elseif (substr($case['how_contacted'], 0, 10) == 'web/search') {
                        $reK = 'k3';
                    } elseif (substr($case['how_contacted'], 0, 8) == 'web/link' || substr($case['how_contacted'], 0, 12) == 'web/adonline' || $case['how_contacted'] == 'web/unknown') {
                        // REF, AD ONLINE, WEB UNKNOWN
                        $reK = 'k4';
                    } elseif ($case['how_contacted'] == 'web/direct') {
                        $reK = 'k5';
                    } elseif ($case['how_contacted'] == 'web/email') {
                        $reK = 'k6';
                    } elseif (substr($case['how_contacted'], 0, 4) == 'nweb') {
                        $reK = 'k7';
                    }
                    if ($case['stats']['kx'] == 'k8') {
                        $reK = 'k8';
                    }
                        if ($reK != 'k8' && $reK != '-' && $reK != $case['stats']['kx'] && USER_ID == 1 && isset($_GET['update-kx'])) {
                            \Yii::$app->db->createCommand()->update('at_case_stats', ['kx'=>$reK], ['case_id'=>$case['id']])->execute();
                        }


                    ?>
                    <tr>
                        <td>
                            <a title="<?=Yii::t('mn', 'Edit')?>" rel="external" class="text-muted" href="<?=DIR?>cases/u/<?=$case['id']?>"><i class="fa fa-edit"></i></a>
                        </td>
                        <td class="text-nowrap"><?= str_replace('/'.date('Y'), '', date_format(date_timezone_set(date_create($case['created_at']), timezone_open('Asia/Saigon')), 'j/n/Y \<\s\p\a\n\ \c\l\a\s\s\=\"\t\e\x\t\-\m\u\t\e\d\"\>H:i\<\/\s\p\a\n\>')) ?></td>
                        <?php if ($view == 'assigned') { ?><td class="text-nowrap"><?= date('j/n', strtotime($case['ao'])) ?></td><?php } ?>
                        <?php if ($view == 'closed') { ?><td class="text-nowrap"><?= date('j/n', strtotime($case['closed'])) ?></td><?php } ?>
                        <?php if ($view == 'won') { ?><td class="text-nowrap"><?= date('j/n', strtotime($case['status_dt'])) ?></td><?php } ?>
                        <td class="text-nowrap">
                            <?php if ($case['stats']['prospect'] != 0 && $case['stats']['prospect'] != '') { ?>
                            <span class="badge badge-flat badge-pill border-warning"><a href="?prospect=<?= $case['stats']['prospect'] ?>" class="text-bold color-prospect-<?= $case['stats']['prospect'] ?>"><?= $case['stats']['prospect'] ?></a></span>
                            <?php } ?>
                            <?php if (in_array($case['is_priority'], [1,2,3,4])) { ?><?= str_repeat('<i class="text-orange fa fa-caret-right"></i>', $case['is_priority']) ?><?php } ?>
                            <?= Html::a($case['name'], '@web/cases/r/'.$case['id'], ['style'=>$case['is_priority'] == 'yes' ? 'font-weight:bold' : '']) ?>
                            <?php if ($case['status'] == 'onhold') { ?><i class="text-warning fa fa-clock-o"></i><?php } ?>
                            <?php if ($case['status'] == 'closed') { ?><i class="text-muted fa fa-lock"></i><?php } ?>
                            <?php if ($case['deal_status'] == 'won') { ?><i class="text-success fa fa-dollar"></i><?php } ?>
                            <?php if ($case['deal_status'] == 'lost' || ($case['status'] == 'closed' && $case['deal_status'] != 'won')) { ?><i class="text-danger fa fa-dollar"></i><?php } ?>
                        </td>
                        <td class="text-nowrap">
                            <?php if ($case['owner_id'] !== null) { ?>
                            <img class="rounded-circle" src="<?= DIR ?>timthumb.php?src=<?= $case['owner']['image'] ?>&w=100&h=100" style="width:20px; height:20px">
                            <?=Html::a($case['owner']['nickname'], '?owner_id='.$case['owner']['id'])?>
                            <span class="text-muted"><?= str_replace('/'.date('Y'), '', date('j/n/Y', strtotime($case['ao']))) ?></span>
                            <?php } else { ?>
                            <?= Yii::t('x', 'No seller') ?>
                            <?php } ?>
                        </td>
                        <?php if (USER_ID == 1) { ?>
                        <td title="<?= $case['how_found'] ?> - <?= $case['how_contacted'] ?>" class="<?= $reK != $case['stats']['kx'] ? 'text-danger' : '' ?>"><?= strtoupper($reK) ?></td>
                        <?php } ?>
                        <?php if (in_array(USER_ID, [1, 695]) && $kxcost == 'yes') { ?>
                        <td title="" class="text-slate text-right text-nowrap"><?= number_format($case['kx_cost'], 1) ?></td>
                        <?php } ?>
                        <td class="text-nowrap">
                            <?= $case['campaign_id'] != 0 ? '<span class="label label-info">C</span> ' : '' ?>
                            <span class="text-muted" title="<?= Yii::t('x', 'Source') ?>: <?= $kaseHowFoundList[$case['how_found']] ?? $case['how_found'] ?>"><?= strtoupper(substr($case['how_found'], 0, 1)) ?></span>
                            <?php if (substr($case['how_found'], 0, 8) == 'referred') { ?>
                            <?= Html::a($case['referrer']['name'], '@web/contacts/'.$case['ref'], ['rel'=>'external']) ?>
                            <?php } ?>
                            &middot;
                            <span class="text-muted " title="Contacted: <?= $caseHowContactedList[$case['how_contacted']] ?? $case['how_contacted'] ?>"><?= $case['stats']['kx'] == '' ? '-' : strtoupper($case['stats']['kx']) ?></span>
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
                        <td colspan="4"  class="text-center"><?= Html::a(Yii::t('x', 'Edit request'), '/cases/request/'.$case['id']) ?></td>
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

        <?php } ?>
    </div>

    <?= LinkPager::widget([
        'pagination' => $pagination,
        'firstPageLabel' => '<<',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '>>',
    ]) ?>
</div><!-- haha -->
<div id="domMessage" style="display:none;">
    <h2 class="text-center"><img style="width: 20px; height: 20px" src="/img/busy1.gif" /> We are processing your request.  Please be patient.</h1>
</div>

<?php

$js = <<<'JS'
    var downloadToken = new Date().getTime();
    $('#search_form').submit(function(){
        if ($('#export_fields').val().length > 0) {
            blockUIForDownload();
        }
        return true;
    });

    // function timer() {
    //     var attempts = 1000;
    //     var downloadTimer = window.setInterval(function () {
    //         var token = getCookie("downloadToken");
    //         attempts--;

    //         if (token == downloadToken || attempts == 0) {
    //             $(".log").prepend("Browser received file from server<br/>");
    //             window.clearInterval(downloadTimer);
    //         }
    //         else {
    //             $(".log").prepend("Browser not received file from server yet<br/>");
    //         }
    //     }, 1000);
    // }

    // function parse(str) {
    //     var obj = {};
    //     var pairs = str.split(/ *; */);
    //     var pair;
    //     if ('' == pairs[0]) return obj;
    //     for (var i = 0; i < pairs.length; ++i) {
    //         pair = pairs[i].split('=');
    //         obj[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
    //     }
    //     return obj;
    // }

    // function getCookie(name) {
    //     var parts = parse(document.cookie);
    //     console.log(parts);
    //     return parts[name] === undefined ? null : parts[name];
    // }

    var fileDownloadCheckTimer;
    function blockUIForDownload() {
        var attempts = 10000;
        var token = new Date().getTime(); //use the current timestamp as the token value
        $('#download_token_value').val(token);
        $.blockUI({
            message: $('#domMessage'),
            css: {
                borderRadius: '5px'
            }
            });
        fileDownloadCheckTimer = window.setInterval(function () {
            var cookieValue = $.cookie('downloadToken');
            attempts--;
            if (cookieValue == token || attempts == 0)
                finishDownload();
        }, 1000);
    }
    function finishDownload() {
        window.clearInterval(fileDownloadCheckTimer);
        $.cookie('downloadToken', null); //clears this cookie value
        $.unblockUI();
    }
    $('.has-drp input').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'YYYY-MM-DD',
            cancelLabel: 'Any date'
        },
        ranges: {
           'Last year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
           'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
           'Last week': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
           'Last 30 days': [moment().subtract(29, 'days'), moment()],
           'Last 7 days': [moment().subtract(6, 'days'), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Today': [moment(), moment()],
           'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
           'Next 7 Days': [moment(), moment().add(6, 'days')],
           'Next 30 Days': [moment(), moment().add(29, 'days')],
           'This month': [moment().startOf('month'), moment().endOf('month')],
           'This year': [moment().startOf('year'), moment().endOf('year')],
           'Next month': [moment().add(1, 'months').startOf('month'), moment().add(1, 'months').endOf('month')],
           'Next year': [moment().add(1, 'years').startOf('year'), moment().add(1, 'years').endOf('year')],
        },
    })
    $('.has-drp input').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' -- ' + picker.endDate.format('YYYY-MM-DD'));
    });

    $('.has-drp input').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $(this).parent().addClass('d-none')
    });

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

    $('[name="date_created"], [name="date_assigned"], [name="date_closed"], [name="date_won"], [name="date_start"], [name="date_end"]').on('change', function(){
        var val = $(this).val()
        if (val == 'custom') {
            $(this).parent().parent().find('.col-sm-5.has-drp').removeClass('d-none').find(':input:eq(0)').focus();
        } else {
            $(this).parent().parent().find('.col-sm-5.has-drp').addClass('d-none').find(':input:eq(0)').val('');
        }
    })
JS;
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js', ['depends'=>'app\assets\MainAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.3/daterangepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.3/daterangepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);