<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

Yii::$app->params['page_title'] = Yii::t('x', 'B2C').' - '.Yii::t('x', 'Conversion rate');

include('_report_b2c_inc.php');

$dkdiemdenList = [
    'all'=>'Gồm tất cả các điểm được chọn, bất kể thứ tự',
    'any'=>'Gồm ít nhất một trong các điểm được chọn',
    'not'=>'Không có điểm nào trong các điểm được chọn',
    'only'=>'Tất cả và chỉ gồm các điểm được chọn',
    'exact'=>'Tất cả và theo đúng thứ tự được chọn',
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

$viewList = [
    'startdate'=>'Khởi hành',
    'enddate'=>'Kết thúc',
];

$currency = 'USD';

Yii::$app->params['body_class'] = 'sidebar-xs';

?>
<style>
th {background-color:#f3f3f3;}
.table-narrow tr>td {padding:8px 4px!important;}
.table-narrow tr>td:first-child {padding:8px 4px!important;}
.text-underline {text-decoration:underline;}
.index-caret {position:absolute; margin:-6px 0 0 0}
.select2.select2-container {width:100%!important}
</style>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <div id="div-toggle-filters">
                <strong class="text-info"><?= Yii::t('x', 'Viewing {count} B2C cases', ['count'=>'']) ?></strong>
                &middot;
                <?php if ($year != '') { ?><strong><?= $kaseViewList[$view] ?>:</strong> <?= $month ?>/<?= $year ?>; <?php } ?>
                <?php if ($name != '') { ?><strong><?= Yii::t('x', 'Name') ?>:</strong> <?= $name ?>; <?php } ?>
                <?php if ($priority != '') { ?><strong><?= Yii::t('x', 'Priority') ?>:</strong> <?= $kasePriorityList[$priority] ?? $priority ?>; <?php } ?>
                <?php if ($status != '') { ?><strong><?= Yii::t('x', 'Status') ?>:</strong> <?= $kaseStatusList[$status] ?? $status ?>; <?php } ?>
                <?php if ($owner_id != 'all' && $owner_id != '') { ?><strong><?= Yii::t('x', 'Seller') ?>:</strong>
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
        </div><!-- #filters -->
        <div class="panel-body">
            <div id="chart1" style="height:400px;"></div>
        </div>
        <div class="table-responsive">
            <table class="table table-narrow table-bordered">
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
                    <tr>
                        <th>
                            <i class="fa fa-star position-left" style="color:gold"></i>
                            <?= Yii::t('x', 'Conversion rate') ?>
                        </th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <td class="text-center success text-success">
                            <div style="font-size:150%"><?= $result['filtered'][$year][$m]['total'] == 0 ? '0' : number_format(100 * $result['filtered'][$year][$m]['won'] / $result['filtered'][$year][$m]['total'], 2)?>%</div>
                        </td>
                        <?php } ?>
                        <td class="text-center text-bold text-success"></td>
                    </tr>
                    <?php foreach ($indexList as $index=>$item) { ?>
                    <tr>
                        <th>
                            <i class="fa fa-square position-left" style="color:<?= $item['color'] ?>"></i>
                            <?= $item['label'] ?>
                        </th>
                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
                        <td class="text-center <?= $index == 'total' ? 'text-bold' : '' ?>">
                            <?= Html::a($result['filtered'][$year][$m][$index], '/cases?is_b2b=no&ca=created&month='.$year.'-'.str_pad($m, 2, '0', STR_PAD_LEFT).'&sale_status='.$index) ?>
                            /
                            <?= $result['total'][$year][$m][$index] ?>
                            <div><?= $result['total'][$year][$m]['total'] == 0 ? '0' : number_format(100 * $result['filtered'][$year][$m][$index] / $result['total'][$year][$m]['total'], 2)?>%</div>
                        </td>
                        <?php } ?>
                        <td class="text-center text-bold">
                            <?= Html::a($result['filtered'][$year][0][$index], '/cases?is_b2b=no&ca=created&month='.$year.'&sale_status='.$index) ?>
                            <div><?= $result['total'][$year][0]['total'] == 0 ? '0' : number_format(100 * $result['filtered'][$year][0][$index] / $result['total'][$year][0]['total'], 2)?>%</div>
                        </td>
                    </tr>
                    <?php } ?>
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
            title : 'Conversion rate',
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