<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;


Yii::$app->params['page_title'] = 'Thống kê hồ sơ / tour theo tháng kết thúc';
Yii::$app->params['page_breadcrumbs'] = [
    ['Reports', 'reports'],
];

$typeList = [
    'all'=>'All types',
    'hotel'=>'Hotels',
    'home'=>'Local homes',
    // 'cruise'=>'Cruise vessels',
    // 'restaurant'=>'Restaurants',
    // 'sightseeing'=>'Sightseeing spots',
    // 'train'=>'Night trains',
    // 'other'=>'Other',
];

$statusList = [
    ''=>'All hotels',
    'str'=>'Strategic',
    're'=>'Recommended',
    'restr'=>'Strategic/Recommended',
];

$destList = \Yii::$app->db->createCommand('SELECT id, name_en, country_code FROM at_destinations ORDER BY country_code, id')->queryAll();

$yearList = [2015=>2015, 2016=>2016, 2017=>2017, 2018=>2018];
$sellerList = [3066=>'Thảo', 1677=>'Phương'];
$yesNoList = ['yes'=>'Yes', 'no'=>'No'];


$year = 2017;
$seller = 3066;
$returning = '';
$source = '';
$contact = '';
$visits = '';
?>
<style type="text/css">
tr.ok td {background-color:#ffffcc;}
</style>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <form class="form-inline">
                <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control']) ?>
                <?= Html::dropdownList('seller', $seller, $sellerList, ['class'=>'form-control', 'prompt'=>'All sellers']) ?>
                <?= Html::dropdownList('returning', $returning, $yesNoList, ['class'=>'form-control', 'prompt'=>'Returning']) ?>
                <?= Html::dropdownList('source', $source, $yearList, ['class'=>'form-control', 'prompt'=>'Source']) ?>
                <?= Html::dropdownList('contact', $contact, $yearList, ['class'=>'form-control', 'prompt'=>'Contact']) ?>
                <?= Html::dropdownList('visits', $visits, $yearList, ['class'=>'form-control', 'prompt'=>'Visits']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
                <?= Html::a('Reset', '?') ?>
            </form>
        </div>
        <div class="panel-body">
            <span class="label bg-brown cursor-pointer sohoso">SỐ HS</span>
            <span class="label bg-success cursor-pointer sohoso_won">SỐ HS WON</span>
            <span class="label bg-danger cursor-pointer sohoso_lost">SỐ HS LOST</span>
            <span class="label bg-violet cursor-pointer sohoso_pending">SỐ HS PENDING</span>
            <span class="label bg-slate cursor-pointer sotour">SỐ TOUR</span>
        </div>
        <div class="table-responsive">
            <table id="tbl-startme" class="table table-bordered table-xxs table-striped">
                <thead>
                    <tr>
                        <th width="">Năm<br><?= $year ?></th>
                        <? for ($mo = 1; $mo <= 12; $mo ++) { ?>
                        <th width="" class="text-center">Tháng<br><?= $mo ?></th>
                        <? } ?>
                    </tr>
                </thead>
                <tbody>
                    <tr class="nok startme">
                        <td></td>
                        <? for ($mo = 1; $mo <= 12; $mo ++) { ?>
                        <td class="text-center">
                            <div class="so sohoso"><?= Html::a(random_int(0, 100), '/cases?orderby=enddate&month=2017-'.substr('0'.$mo, -2), ['class'=>'text-brown', 'target'=>'_blank']) ?></div>
                            <div class="so sohoso_won">
                                <?= Html::a(random_int(0, 100), '/cases?orderby=enddate&month=2017-'.substr('0'.$mo, -2), ['class'=>'text-success', 'target'=>'_blank']) ?>
                                <span class="small text-muted"><?= random_int(0, 100) ?>%</span>
                            </div>
                            <div class="so sohoso_lost">
                                <?= Html::a(random_int(0, 100), '/cases?orderby=enddate&month=2017-'.substr('0'.$mo, -2), ['class'=>'text-danger', 'target'=>'_blank']) ?>
                                <span class="small text-muted"><?= random_int(0, 100) ?>%</span>
                            </div>
                            <div class="so sohoso_pending">
                                <?= Html::a(random_int(0, 100), '/cases?orderby=enddate&month=2017-'.substr('0'.$mo, -2), ['class'=>'text-violet', 'target'=>'_blank']) ?>
                                <span class="small text-muted"><?= random_int(0, 100) ?>%</span>
                            </div>
                            <div class="so sotour"><?= Html::a($results[2017][$mo], '/tours?orderby=enddate&month=2017-'.substr('0'.$mo, -2), ['class'=>'text-slate', 'target'=>'_blank']) ?></div>
                            <!-- <div><span class="pie"><?= random_int(0, 100) ?>, <?= random_int(0, 100) ?>, <?= random_int(0, 100) ?></span></div> -->
                        </td>
                        <? } ?>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-body">
            <div id="chart1" style="width:100%; height:400px;"></div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', {'packages':['bar', 'corechart']});
google.charts.setOnLoadCallback(drawChart1);

function drawChart1() {
    var data = google.visualization.arrayToDataTable([
    ['Month', 'Tours']
    <? foreach ($results[2017] as $mo=>$num) { ?>
    , ['Tháng <?= $mo ?>', <?= $num ?>]
    <? } ?>
    ]);

    var options = {
        title: 'Confirmed tours in 2017',
        pieHole: 0.4,
    };

    var chart1 = new google.visualization.PieChart(document.getElementById('chart1'));
    chart1.draw(data, options);
}
</script>
<?
$js = <<<'TXT'
$('span.label.sotour').on('click', function(){
    $(this).toggleClass('bg-slate label-default')
    $('div.so.sotour').toggle();
});
$('span.label.sohoso').on('click', function(){
    $(this).toggleClass('bg-brown label-default')
    $('div.so.sohoso').toggle();
});
$('span.label.sohoso_won').on('click', function(){
    $(this).toggleClass('bg-success label-default')
    $('div.so.sohoso_won').toggle();
});
$('span.label.sohoso_lost').on('click', function(){
    $(this).toggleClass('bg-danger label-default')
    $('div.so.sohoso_lost').toggle();
});
$('span.label.sohoso_pending').on('click', function(){
    $(this).toggleClass('bg-violet label-default')
    $('div.so.sohoso_pending').toggle();
});
$("span.pie").peity("donut");
TXT;

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/peity/3.2.1/jquery.peity.min.js', ['depends'=>'yii\web\JqueryAsset']);

$this->registerJs($js);