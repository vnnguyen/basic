<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

Yii::$app->params['page_title'] = Yii::t('x', 'B2C').' - '.Yii::t('x', 'Conversion rate');

include('_report_b2c_inc.php');

function rtrim0($text) {
    return rtrim(rtrim($text, '0'), '.');
}
$kaseDestinationList = [
    'vn' => Yii::t('x', 'VN'),
    'la' => Yii::t('x', 'LAO'),
    'kh' => Yii::t('x', 'CAM'),
    'Myanmar' => Yii::t('x', 'Myanmar'),
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

Yii::$app->params['body_class'] = 'sidebar-xs';

?>
<style>
.index-caret {position:absolute; margin:-6px 0 0 0}
.select2.select2-container {width:100%!important}
</style>
<div class="col-md-12">
    <div class="panel card">
        <div class="panel-body card-header">
            <div id="div-toggle-filters">
                <strong class="text-info"><?= Yii::t('x', 'Viewing {count} B2C cases', ['count'=>'']) ?></strong>
                &middot;
                <strong><?= $kaseViewByList[$view] ?>:</strong> <?= $year ?>; 
                <?php if ($groupby == 'source') { ?><strong><?= Yii::t('x', 'Group by') ?>:</strong> <?= Yii::t('x', 'Source') ?>; <?php } ?>
                <?php if ($groupby == 'seller') { ?><strong><?= Yii::t('x', 'Group by') ?>:</strong> <?= Yii::t('x', 'Seller') ?>; <?php } ?>
                <?php if ($name != '') { ?><strong><?= Yii::t('x', 'Name') ?>:</strong> <?= $name ?>; <?php } ?>
                <?php if ($priority != '') { ?><strong><?= Yii::t('x', 'Priority') ?>:</strong> <?= $kasePriorityList[$priority] ?? $priority ?>; <?php } ?>
                <?php if ($status != '') { ?><strong><?= Yii::t('x', 'Status') ?>:</strong> <?= $kaseStatusList[$status] ?? $status ?>; <?php } ?>
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
                <?php if ($how_found != '') { ?><strong><?= Yii::t('x', 'How customer found us') ?>:</strong> <?= $kaseHowFoundList[$how_found] ?? '' ?>; <?php } ?>

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
                            <p><span class="text-bold text-uppercase"><?= Yii::t('x', 'View') ?></span></p>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'View by') ?> / <?= Yii::t('x', 'Year') ?>:</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-xs-6"><?= Html::dropdownList('view', $view, $kaseViewByList, ['class'=>'form-control']) ?></div>
                                        <div class="col-xs-6"><?= Html::textInput('year', $year, ['class'=>'form-control', 'type'=>'number', 'min'=>2007, 'max'=>10 + date('Y'), 'placeholder'=>Yii::t('x', 'Year, eg. {year}', ['year'=>date('Y')])]) ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Group by') ?>:</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-xs-6"><?= Html::dropdownList('groupby', $groupby, $kaseGroupByList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(None)')]) ?></div>
                                        <div class="col-xs-6"><?//= Html::dropdownList('groupby2', $groupby, $kaseGroupByList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '(None)')]) ?></div>
                                    </div>
                                </div>
                            </div>
                            <p><span class="text-bold text-uppercase"><?= Yii::t('x', 'Case') ?></span></p>

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

                            <!--<div class="row form-group">
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
        </div><!-- #filters -->
        <div class="panel-body">
            <!-- <div id="chart1" style="height:400px;"></div> -->
        </div>
        <div class="panel-body">
            <?php if ($groupby == 'source' || $groupby == 'stype_client' || $groupby == 'devices' || $groupby == 'destination') { ?>
            <p>Click tên nhóm để ẩn/hiện số hồ sơ.</p>
            <?php } ?>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Chỉ số \ Tháng (năm <?= $year ?>)</th>
                            <?php for ($m = 1; $m <= 12; $m ++) { ?>
                            <th class="text-center" colspan="2"><?= $m ?></th>
                            <?php } ?>
                            <th class="text-center" colspan="2">Cả năm</th>
                        </tr>
                        <tr>
                            <th></th>
                            <?php for ($m = 1; $m <= 12; $m ++) { ?>
                            <th class="text-center"><?= Yii::t('app', 'c'); ?></th>
                            <th class="text-center text-danger" >%</th>
                            <?php } ?>
                            <th class="text-center"><?= Yii::t('app', 'c'); ?></th>
                            <th class="text-center">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($groupby == 'source') {?>

                            <?php foreach ($caseHowContactedListFormatted as $hck=>$hcn) {
                                if (strpos($hcn, '--') === false) {
                                    echo '<tr> <td colspan="27"></td></tr>';
                                }
                            ?>
                            <tr class="success">
                                <td class="text-bold" colspan="27" onclick="$('.togglable').toggle(0);"><?= $hcn ?></td>
                            </tr>
                                <?php foreach ($indexList as $index=>$item) { ?>
                            <tr class="togglable">
                                <th>
                                    <?= $item['label'] ?>
                                </th>
                                <?php for ($m = 1; $m <= 12; $m ++) { ?>
                                <td class="text-center <?= $index == 'total' ? 'text-bold' : '' ?>">
                                    <?= $result['grouped-source'][$hck][$year][$m][$index] ?>
                                </td>
                                <td class="text-center">
                                    <?= rtrim0(number_format(($result['grouped-source'][$hck][$year][$m]['total'] > 0)?$result['grouped-source'][$hck][$year][$m][$index] * 100/ $result['grouped-source'][$hck][$year][$m]['total']: 0, 2))?>
                                </td>
                                <?php } ?>
                                <td class="text-center text-bold">
                                    <?= $result['grouped-source'][$hck][$year][0][$index]?>
                                </td>
                                <td class="text-center text-bold">
                                    <?= rtrim0(number_format(($result['grouped-source'][$hck][$year][0]['total'] > 0)?$result['grouped-source'][$hck][$year][0][$index] * 100/ $result['grouped-source'][$hck][$year][0]['total']: 0, 2))?>
                                </td>
                            </tr>
                                <?php } ?>
                            <tr style="background: #ccc">
                                <th><?= Yii::t('x', 'Total') ?> </th>
                                <?php for ($m = 1; $m <= 12; $m ++) {
                                    $data_month = $result['grouped-source'][$hck][$year][$m]['total'];
                                ?>
                                <td class="text-center" colspan="2"> <?= $data_month ?> </td>
                                <?php } ?>
                                <td class="text-center text-bold" colspan="2"> <?= $result['grouped-source'][$hck][$year][0]['total'] ?> </td>
                            </tr>
                            <?php } ?>

                        <?php } elseif ($groupby == 'stype_client') {?>

                            <?php foreach ($kaseHowFoundListFormatted as $hfk=>$hfn) {
                                if (strpos($hfn, '--') === false) {
                                    echo '<tr> <td colspan="27"></td></tr>';
                                }
                            ?>
                            <tr class="success">
                                <td class="text-bold" colspan="27" onclick="$('.togglable').toggle(0);"><?= $hfn ?></td>
                            </tr>
                                <?php foreach ($indexList as $index=>$item) { ?>
                            <tr class="togglable">
                                <th>
                                    <?= $item['label'] ?>
                                </th>
                                <?php for ($m = 1; $m <= 12; $m ++) { ?>
                                <td class="text-center ">
                                    <?= $result['grouped-stype_client'][$hfk][$year][$m][$index] ?>
                                </td>
                                <td class="text-center">
                                    <?= rtrim0(number_format(($result['grouped-stype_client'][$hfk][$year][$m]['total'] > 0)?$result['grouped-stype_client'][$hfk][$year][$m][$index] * 100/ $result['grouped-stype_client'][$hfk][$year][$m]['total']: 0, 2))?>
                                </td>
                                <?php } ?>
                                <td class="text-center text-bold">
                                    <?= $result['grouped-stype_client'][$hfk][$year][0][$index]?>
                                </td>
                                <td class="text-center text-bold">
                                    <?= rtrim0(number_format(($result['grouped-stype_client'][$hfk][$year][0]['total'] > 0)?$result['grouped-stype_client'][$hfk][$year][0][$index] * 100/ $result['grouped-stype_client'][$hfk][$year][0]['total']: 0, 2))?>
                                </td>
                            </tr>
                                <?php } ?>
                            <tr style="background: #ccc">
                                <th><?= Yii::t('x', 'Total') ?> </th>
                                <?php for ($m = 1; $m <= 12; $m ++) {
                                    $data_month = $result['grouped-stype_client'][$hfk][$year][$m]['total'];
                                ?>
                                <td class="text-center" colspan="2"> <?= $data_month ?> </td>
                                <?php } ?>
                                <td class="text-center text-bold" colspan="2"> <?= $result['grouped-stype_client'][$hfk][$year][0]['total'] ?> </td>
                            </tr>
                            <?php } ?>

                        <?php } elseif ($groupby == 'devices') {?>

                            <?php foreach ($kaseDeviceList as $hfk=>$hfn) {
                                if (strpos($hfn, '--') === false) {
                                    echo '<tr> <td colspan="27"></td></tr>';
                                }
                            ?>
                            <tr class="success">
                                <td class="text-bold" colspan="27" onclick="$('.togglable').toggle(0);"><?= $hfn ?></td>
                            </tr>
                                <?php foreach ($indexList as $index=>$item) { ?>
                            <tr class="togglable">
                                <th>
                                    <?= $item['label'] ?>
                                </th>
                                <?php for ($m = 1; $m <= 12; $m ++) { ?>
                                <td class="text-center ">
                                    <?= $result['grouped-devices'][$hfk][$year][$m][$index] ?>
                                </td>
                                <td class="text-center">
                                    <?= rtrim0(number_format(($result['grouped-devices'][$hfk][$year][$m]['total'] > 0)?$result['grouped-devices'][$hfk][$year][$m][$index] * 100/ $result['grouped-devices'][$hfk][$year][$m]['total']: 0, 2))?>
                                </td>
                                <?php } ?>
                                <td class="text-center text-bold">
                                    <?= $result['grouped-devices'][$hfk][$year][0][$index]?>
                                </td>
                                <td class="text-center text-bold">
                                    <?= rtrim0(number_format(($result['grouped-devices'][$hfk][$year][0]['total'] > 0)?$result['grouped-devices'][$hfk][$year][0][$index] * 100/ $result['grouped-devices'][$hfk][$year][0]['total']: 0, 2))?>
                                </td>
                            </tr>
                                <?php } ?>
                            <tr style="background: #ccc">
                                <th><?= Yii::t('x', 'Total') ?> </th>
                                <?php for ($m = 1; $m <= 12; $m ++) {
                                    $data_month = $result['grouped-devices'][$hfk][$year][$m]['total'];
                                ?>
                                <td class="text-center" colspan="2"> <?= $data_month ?> </td>
                                <?php } ?>
                                <td class="text-center text-bold" colspan="2"> <?= $result['grouped-devices'][$hfk][$year][0]['total'] ?> </td>
                            </tr>
                            <?php } ?>

                        <?php } elseif ($groupby == 'destination') {?>

                            <?php foreach ($kaseDestinationList as $desk=>$des) {?>
                            <tr class="success">
                                <td class="text-bold" colspan="27" onclick="$('.togglable').toggle(0);"><?= $des ?></td>
                            </tr>
                                <?php foreach ($indexList as $index=>$item) { ?>
                            <tr class="togglable">
                                <th>
                                    <?= $item['label'] ?>
                                </th>
                                <?php for ($m = 1; $m <= 12; $m ++) { ?>
                                <td class="text-center ">
                                    <?= $result['grouped-destination'][$desk][$year][$m][$index] ?>
                                </td>
                                <td class="text-center">
                                    <?= rtrim0(number_format(($result['grouped-destination'][$desk][$year][$m]['total'] > 0)?$result['grouped-destination'][$desk][$year][$m][$index] * 100/ $result['grouped-destination'][$desk][$year][$m]['total']: 0, 2))?>
                                </td>
                                <?php } ?>
                                <td class="text-center text-bold">
                                    <?= $result['grouped-destination'][$desk][$year][0][$index]?>
                                </td>
                                <td class="text-center text-bold">
                                    <?= rtrim0(number_format(($result['grouped-destination'][$desk][$year][0]['total'] > 0)?$result['grouped-destination'][$desk][$year][0][$index] * 100/ $result['grouped-destination'][$desk][$year][0]['total']: 0, 2))?>
                                </td>
                            </tr>
                                <?php } ?>
                            <tr style="background: #ccc">
                                <th><?= Yii::t('x', 'Total') ?> </th>
                                <?php for ($m = 1; $m <= 12; $m ++) {
                                    $data_month = $result['grouped-destination'][$desk][$year][$m]['total'];
                                ?>
                                <td class="text-center" colspan="2"> <?= $data_month ?> </td>
                                <?php } ?>
                                <td class="text-center text-bold" colspan="2"> <?= $result['grouped-destination'][$desk][$year][0]['total'] ?> </td>
                            </tr>
                            <?php } ?>

                        <?php } else { ?>
                        <?php foreach ($indexList as $index=>$item) {?>
                        <tr>
                            <th>
                                <?= $item['label'] ?> (<?= Yii::t('x', 'Total') ?>)
                            </th>
                            <?php for ($m = 1; $m <= 12; $m ++) {
                                $data_month = $result['filtered'][$year][$m];
                            ?>
                            <td class="text-center">
                                <?= $data_month[$index] ?>
                            </td>
                            <td class="text-center">
                                <?= ($data_month['total'] > 0)? number_format($data_month[$index] * 100 / $data_month['total'], 2): 0?>
                            </td>
                            <?php } ?>
                            <td class="text-center text-bold">
                                <?= $result['filtered'][$year][0][$index] ?>
                            </td>
                            <td class="text-center text-bold">
                                <div class="text-muted"><?= $result['filtered'][$year][0]['total'] == 0 ? '0' : number_format(100 * $result['filtered'][$year][0][$index] / $result['filtered'][$year][0]['total'], 2)?></div>
                            </td>
                        </tr>
                        <?php } // foreach indexList ?>
                        <tr style="background: #ccc">
                            <th><?= Yii::t('x', 'Total') ?> </th>
                            <?php for ($m = 1; $m <= 12; $m ++) {
                                $data_month = $result['filtered'][$year][$m];
                            ?>
                            <td class="text-center" colspan="2"> <?= $data_month['total'] ?> </td>
                            <?php } ?>
                            <td class="text-center text-bold" colspan="2"> <?= $result['filtered'][$year][0]['total'] ?> </td>
                        </tr>
                        <?php } // if grouped by source ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> 
  
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