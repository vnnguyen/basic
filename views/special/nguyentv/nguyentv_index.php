<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;


Yii::$app->params['page_title'] = 'Venues';
Yii::$app->params['page_breadcrumbs'] = [
    ['Venues', 'venues'],
];

$typeList = [
    'all'=>'All types',
    'hotel'=>'Hotels',
    'home'=>'Local homes',
    'cruise'=>'Cruise vessels',
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


?>
<style type="text/css">
tr.ok td {background-color:#ffffcc;}
</style>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <form class="form-inline">
                <?= Html::dropdownList('type', $type, $typeList, ['class'=>'form-control']) ?>
                <?= Html::dropdownList('dest', $dest, ArrayHelper::map($destList, 'id', 'name_en', 'country_code'), ['class'=>'form-control']) ?>
                <?= Html::dropdownList('status', $status, $statusList, ['class'=>'form-control']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
            </form>
        </div>
        <? if (empty($theVenues)) { ?>
        <div class="panel-body">No data found</div>
        <? } else { ?>
        <div class="table-responsive">
            <table id="tbl-startme" class="table table-bordered table-condensed table-striped">
                <thead>
                    <tr>
                        <th width="" rowspan="2">Name</th>
                        <th width="100" colspan="2" class="text-center">Tour</th>
                        <th width="100" colspan="2" class="text-center">Pax</th>
                        <th width="100" colspan="2" class="text-center">Room nights</th>
                    </tr>
                    <tr>
                        <th width="50">2015</th>
                        <th width="50">2016</th>
                        <th width="50">2015</th>
                        <th width="50">2016</th>
                        <th width="50">2015</th>
                        <th width="50">2016</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theVenues as $venue) { ?>
                    <tr class="nok startme">
                        <td>
                            <?= Html::a($venue['name'], '/tools/tour-ks?ks='.$venue['id'], ['class'=>'startme', 'data-id'=>$venue['id'], 'target'=>'_blank'])?>
                            <!-- <span class="text-muted"><?= $venue['search'] ?></span> -->
                        </td>
                        <td class="text-center"><?= $venue['stats']['t2015'] ?></td>
                        <td class="text-center"><?= $venue['stats']['t2016'] ?></td>
                        <td class="text-center"><?= $venue['stats']['p2015'] ?></td>
                        <td class="text-center"><?= $venue['stats']['p2016'] ?></td>
                        <td class="text-center"><?= $venue['stats']['rn2015'] ?></td>
                        <td class="text-center"><?= $venue['stats']['rn2016'] ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
        <div class="panel-body">
            <div id="chart1" style="width:100%; height:400px;"></div>
            <!-- <p id="imglink1">Save as image</p> -->
            <div id="chart2" style="width:100%; height:400px;"></div>
            <div id="chart3" style="width:100%; height:400px;"></div>
        </div>
        <? } ?>
    </div>
</div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', {'packages':['bar', 'corechart']});
google.charts.setOnLoadCallback(drawChart1);
google.charts.setOnLoadCallback(drawChart2);
google.charts.setOnLoadCallback(drawChart3);

function drawChart1() {
    var data = new google.visualization.arrayToDataTable([
        ['Khách sạn', '2015', '2016']
        <? foreach ($theVenues as $venue) { ?>
        , ["<?= $venue['name'] ?>", <?= $venue['stats']['rn2015'] ?>, <?= $venue['stats']['rn2016'] ?>]
        <? } ?>
    ]);

    var options = {
        // width: 900,
        chart: {
            title: 'Số phòng đêm qua 2 năm',
            subtitle: '2015 và 2016'
        },
        series: {
            0: { axis: '2015' }, // Bind series 0 to an axis named 'distance'.
            1: { axis: '2016' } // Bind series 1 to an axis named 'brightness'.
        },
        // axes: {
        //     y: {
        //         2015: {label: 'parsecs'}, // Left y-axis.
        //         2016: {side: 'right', label: 'apparent magnitude'} // Right y-axis.
        //     }
        // },
        hAxis: {
            textStyle: {color:'#f00'},
            slantedText: true,
            showTextEvery: 1
        },
        chartArea: {
            bottom: 50
        }
    };

    var chart1 = new google.charts.Bar(document.getElementById('chart1'));
    // var imglink1 = document.getElementById('imglink1');
    // google.visualization.events.addListener(chart1, 'ready', function () {
    //     imglink1.innerHTML = '<img src="' + chart1.getImageURI() + '">';
    // });
    chart1.draw(data, options);
};

function drawChart2() {
    var data = new google.visualization.arrayToDataTable([
        ['Khách sạn', '2015', '2016']
        <? foreach ($theVenues as $venue) { ?>
        , ["<?= $venue['name'] ?>", <?= $venue['stats']['p2015'] ?>, <?= $venue['stats']['p2016'] ?>]
        <? } ?>
    ]);

    var options = {
        // width: 900,
        chart: {
            title: 'Số khách qua 2 năm',
            subtitle: '2015 và 2016'
        },
        series: {
            0: { axis: '2015' }, // Bind series 0 to an axis named 'distance'.
            1: { axis: '2016' } // Bind series 1 to an axis named 'brightness'.
        },
        // axes: {
        //     y: {
        //         2015: {label: 'parsecs'}, // Left y-axis.
        //         2016: {side: 'right', label: 'apparent magnitude'} // Right y-axis.
        //     }
        // },
        hAxis: {
            slantedText: true,
            showTextEvery: 1
        },
        chartArea: {
            bottom: 50
        }
    };

    var chart2 = new google.charts.Bar(document.getElementById('chart2'));
    // var imglink1 = document.getElementById('imglink1');
    // google.visualization.events.addListener(chart1, 'ready', function () {
    //     imglink1.innerHTML = '<img src="' + chart1.getImageURI() + '">';
    // });
    chart2.draw(data, options);
};

function drawChart3() {
    var data = new google.visualization.arrayToDataTable([
        ['Khách sạn', '2015', '2016']
        <? foreach ($theVenues as $venue) { ?>
        , ["<?= $venue['name'] ?>", <?= $venue['stats']['t2015'] ?>, <?= $venue['stats']['t2016'] ?>]
        <? } ?>
    ]);

    var options = {
        // width: 900,
        chart: {
            title: 'Số tour qua 2 năm',
            subtitle: '2015 và 2016'
        },
        series: {
            0: { axis: '2015' }, // Bind series 0 to an axis named 'distance'.
            1: { axis: '2016' } // Bind series 1 to an axis named 'brightness'.
        },
        // axes: {
        //     y: {
        //         2015: {label: 'parsecs'}, // Left y-axis.
        //         2016: {side: 'right', label: 'apparent magnitude'} // Right y-axis.
        //     }
        // },
        hAxis: {
            slantedText: true,
            showTextEvery: 1
        },
        chartArea: {
            bottom: 50
        }
    };

    var chart3 = new google.charts.Bar(document.getElementById('chart3'));
    // var imglink1 = document.getElementById('imglink1');
    // google.visualization.events.addListener(chart1, 'ready', function () {
    //     imglink1.innerHTML = '<img src="' + chart1.getImageURI() + '">';
    // });
    chart3.draw(data, options);
};
</script>
<?
$js = <<<'TXT'
$('tr.nok a.startme').on('click', function(){
    var tr = $(this).parent().parent();
    var id = $(this).data('id');
    $.ajax({
        type: 'POST',
        url: '/special/nguyentv/ajax?xh',
        data: {
            id: id,
            yr: {yr}
        }
    })
    .done(function(data){
        tr.addClass('ok').removeClass('nok');
        tr.find('td:eq(3)').html(data.t2015);
        tr.find('td:eq(4)').html(data.p2015);
        tr.find('td:eq(5)').html(data.rn2015);
        tr.find('td:eq(6)').html(data.t2016);
        tr.find('td:eq(7)').html(data.p2016);
        tr.find('td:eq(8)').html(data.rn2016);
        // window.scrollTo(0, $('tr.nok:eq(0)').offset().top - 50);
        $('tr.nok:eq(0)').find('a.startme').trigger('click');
    })
    .fail(function(){
        alert('Operation failed!');
    });
    return false;
});
TXT;

