<?
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\FileHelper;

Yii::$app->params['body_class'] = 'bg-white sidebar-xs';
Yii::$app->params['page_layout'] = '-t';
Yii::$app->params['page_title'] = $theVenue['name'];

// Stars
$stars = '';
if ($theVenue['stype'] == 'hotel') {
    for ($i = 3; $i <= 5; $i ++) {
        if (strpos($theVenue['search'], $i.'s') !== false) {
            $stars = ' '.str_repeat('<i class="fa fa-star text-orange-300"></i>', $i);
        }
    }
    if ($stars != '') {
        Yii::$app->params['page_small_title'] = $stars.' ';
    }
}

include('_venue_inc.php');

// if (strpos($theVenue['search'], 'str ') !== false || substr($theVenue['search'], -3) == 'str') {
//     Yii::$app->params['page_small_title'] .= ' <em>strategic</em>';
// }
// if (strpos($theVenue['search'], 're ') !== false || substr($theVenue['search'], -2) == 're') {
//     Yii::$app->params['page_small_title'] .= ' <em>recommended</em>';
// }

Yii::$app->params['page_small_title'] .= $theVenue['destination']['name_en'];
// Price dates
$fromDTArray = [];
foreach ($theVenue['dvo'] as $dvo) {
    foreach ($dvo['cpo'] as $cpo) {
        if (!in_array($cpo['from_dt'], $fromDTArray)) {
            $fromDTArray[] = $cpo['from_dt'];
        }
    }
}
rsort($fromDTArray);

$range = [];
foreach ($theVenue['dvc'] as $dvc) {
    foreach ($dvc['dvd'] as $dvd) {
        if ($dvd['stype'] == 'date') {
            $subRange = explode(';', $dvd['def']);
            foreach ($subRange as $sr) {
                $arr = [
                    'range'=>$sr,
                    'code'=>$dvd['code'],
                    'name'=>$dvd['desc'],
                    'group'=>$dvc['name'],
                ];
                $range[] = $arr;
            }
        }
    }
}
// \fCore::expose($range);

$cnt = 0;
$data_set = '';
$range_set = '';
foreach ($range as $i=>$rg) {
    $cnt ++;
    $r = explode('-', $rg['range']);
    if (!isset($r[1])) {
        $r[1] = $r[0];
    }

    $bg = 9;
    foreach ($theVenue['dvc'] as $dvc) {
        foreach ($dvc['dvd'] as $j=>$dvd) {
            if ($dvd['stype'] == 'date' && strpos($dvd['def'], $rg['range']) !== false) {
                $bg = $j + 1;
            }
        }
    }

    try {
    $from = \DateTime::createFromFormat('j/n/Y', $r[0])->format('Y-m-d');
        
    } catch (Exception $e) {
        echo $r[0]; exit;        
    }
    $until = \DateTime::createFromFormat('j/n/Y', $r[1])->format('Y-m-d');
    $data_set .= "{id: $cnt, group: '{$rg['group']}', content: '{$rg['code']}', start: '$from 00:00:00', end: '$until 23:59:59', type: 'background', className: 'bg$bg'}";
    $range_set .= "ranges.push ( moment.range(moment('{$from} 00:00:00'), moment('{$until} 23:59:59')));";
    if ($cnt < count($range)) {
        $data_set .= ",\n";
    }
}

$js = <<<'TXT'
// function showPriceOn(date)
// {
//     $('#view-price-on').html(moment(date).format('D/M/Y dddd'));
//     $('.range').hide();
//     cnt = 0;
//     ranges.forEach(function(element) {
//         if (element.contains(date)) {
//             $('.range.range'+cnt).show();
//         }
//         cnt ++;
//     });
// }

// var container = document.getElementById('price-periods');

// var items = new vis.DataSet([
//     // {id:999, content: 'Promo XYZ', editable: false, start: '2017-08-26', end: '2017-09-02'},
//     {$DATASET}
// ]);

// var options = {
//     // type: 'background',
//     stack: false,
//     zoomMin: 1000000000,
//     zoomMax: 32000000000,
// };

// var timeline = new vis.Timeline(container, items, options);
// timeline.addCustomTime(moment().format('YYYY-MM-DD 12:00:00'));

// var ranges = [];

// {$RANGES}

// timeline.on('click', function (event) {
//     timeline.setCustomTime(event.time);
//     showPriceOn(event.time);
// });

// timeline.on('timechange', function (event) {
//     showPriceOn(event.time);
// });
TXT;

// $this->registerJs(str_replace(['{$DATASET}', '{$RANGES}'], [$data_set, $range_set], $js));

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/moment.min.js', ['depends'=>'yii\web\JqueryAsset']);
if (Yii::$app->language != 'en') {
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/locale/'.Yii::$app->language.'.js', ['depends'=>'yii\web\JqueryAsset']);
}
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment-range/2.2.0/moment-range.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/vis/4.16.1/vis.css', ['depends'=>'yii\web\JqueryAsset']);

?>
<style>
.flex-container {
  padding: 0;
  margin: 0;
  list-style: none;
  height: 200px;
  
  -ms-box-orient: horizontal;
  display: -webkit-box;
  display: -moz-box;
  display: -ms-flexbox;
  display: -moz-flex;
  display: -webkit-flex;
  display: flex;
  
  -webkit-justify-content: space-around;
  justify-content: space-around;
  -webkit-flex-flow: row wrap;
  flex-flow: row wrap;
  -webkit-align-items: stretch;
  align-items: stretch;
}

.header,
.footer  { flex: 1 100%; }
.sidebar { flex: 1; }
.main    { flex: 2; }

.flex-item {
background:#ffc;
padding: 10px;
width: 100px;
border: 1px solid rgba(0,0,0,.1);
/*color: white;*/
}
</style>
<div class="col-sm-4 col-sm-push-8" style="padding-left:10px; border-left:1px solid #eee;">
    <p>
        <span class="text-bold text-uppercase"><?= $theVenue['name'] ?></span>
        <?php if (strpos($theVenue['search'], ' str ') !== false) { ?><span class="text-bold text-pink">strategic</span><?php } ?>
        <?php if (strpos($theVenue['search'], ' re ') !== false) { ?><span class="text-bold text-primary">recommended</span><?php } ?>
    </p>
    <?php
    if ($theVenue['image'] == '') {
        if ($theVenue['images_booking'] != '') {
            $pos = strpos($theVenue['images_booking'], '">');
            if (false !== $pos) {
                $img = substr($theVenue['images_booking'], 0, $pos + 2);
                $img = str_replace('src=', 'class="img-responsive" src=', $img);
                echo $img;
            } else {
                $imgs = explode(';|', $theVenue['images_booking']);
                if (isset($imgs[0]) && $imgs[0] != '') {
                    $theVenue['image'] = $imgs[0];
                }
            }

        }
    }
    
    echo Html::a(Html::img($theVenue['image'], ['class'=>'img-responsive']), $theVenue['image'], ['data-fancybox'=>'gallery', 'title'=>'View image gallery']);
    ?>

    <table class="table table-narrow" style="border-bottom:1px solid #ddd;">
        <?php if ($theVenue['latlng'] != '') { ?>
        <tr>
            <th style="padding-left:0!important">Map</th>
            <td style="padding-right:0!important"><a href="javascript:;" onclick="$('.view_map').toggle()"><span class="view_map">View map</span><span class="view_map" style="display:none;">Hide map</span></a></td>
        </tr>
        <tr style="display:none;" class="view_map">
            <td colspan="2" style="padding:0!important;">
                <p><a target="_blank" href="https://www.google.com/maps/search/<?= urlencode($theVenue['name']) ?>+Hotel/@<?= $theVenue['latlng'] ?>,16z"><img class="img-responsive" src="https://maps.googleapis.com/maps/api/staticmap?markers=color:blue%7Clabel:V%7C<?= $theVenue['latlng'] ?>&center=<?= $theVenue['latlng'] ?>&zoom=16&scale=2&size=480x300&sensor=true"></a></p>
                <p class="text-center">
                    <a target="_blank" href="https://www.google.com/maps/search/<?= urlencode($theVenue['name']) ?>+Hotel/@<?= $theVenue['latlng'] ?>,16z">Google Maps</a>
                    -
                    <a target="_blank" href="http://maps.vietbando.com/maps/?t=1&st=0&sk=<?= urlencode($theVenue['name']) ?>&l=16&kv=<?= $theVenue['latlng'] ?>">VietBando</a>
                </p>
            </td>
        </tr>
        <?php } ?>
        <? foreach ($venueMetas as $li) { ?>
        <tr>
            <th style="padding-left:0!important"><?= ucfirst($li['name']) ?></th>
            <td style="padding-right:0!important">
                <?
                if ($li['name'] == 'website') {
                    if (substr($li['value'], 0, 7) != 'http://' && substr($li['value'], 0, 8) != 'https://') {
                        $li['value'] = 'http://'.$li['value'];
                    }
                    echo Html::a($li['value'], $li['value'], ['target'=>'_blank']);
                } else {
                    echo $li['value'];
                }
                ?>
                <?= $li['note'] != '' ? '<em>'.$li['note'].'</em>' : '' ?>
            </td>
        </tr>
        <? } ?>
        <tr>
            <th style="padding-left:0!important">View on</th>
            <td style="padding-right:0!important">
<? if ($theVenue['link_tripadvisor'] != '') { ?><?= Html::a(Html::img(DIR.'assets/img/logo-tripadvisor.jpg', ['style'=>'height:20px; margin-right:16px;']), $theVenue['link_tripadvisor'], ['rel'=>'external', 'title'=>'Hotel on TripAdvisor.com']) ?><? } ?>
<? if ($theVenue['link_booking'] != '') { ?><?= Html::a(Html::img(DIR.'assets/img/logo-booking.jpg', ['style'=>'height:20px; margin-right:16px;']), $theVenue['link_booking'], ['rel'=>'external', 'title'=>'Hotel on Booking.com']) ?><? } ?>
<? if ($theVenue['link_agoda'] != '') { ?><?= Html::a(Html::img(DIR.'assets/img/logo-agoda.jpg', ['style'=>'height:20px; margin-right:16px;']), $theVenue['link_agoda'], ['rel'=>'external', 'title'=>'Hotel on Agoda.com']) ?><? } ?>
<?= Html::a(Html::img('https://www.google.com.vn/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png', ['style'=>'height:20px; margin-right:16px;']), 'https://www.google.com.vn/search?hl=vi&q='.urlencode($theVenue['name']), ['rel'=>'external', 'title'=>'Search on Google.com']) ?>
            </td>
        </tr>
        <?php if ($venueSupplier) { ?>
        <tr>
            <th style="padding-left:0!important">Supplier</th>
            <td style="padding-right:0!important">
                <?= $venueSupplier['name'] ?>
                (<a href="javascript:;" onclick="$('.view_supplier').toggle()">
                <span class="view_supplier">More</span>
                <span class="view_supplier" style="display:none;">Less</span>
                </a>)
            </td>
        </tr>
        <tr class="view_supplier" style="display:none;">
            <td colspan="2" style="padding-left:0!important; padding-right:0!important">
                <p><strong><?= $venueSupplier['name'] ?></strong>
                    <br><?= $venueSupplier['name_full'] ?>
                </p>
                <p><strong>Tax info:</strong><br><?= nl2br($venueSupplier['tax_info']) ?></p>
                <p><strong>Bank info:</strong><br><?= nl2br($venueSupplier['bank_info']) ?></p>
                <p><?= Html::a('View supplier', '@web/suppliers/r/'.$theVenue['company_id']) ?></p>
                <? if (count($venueSupplier['venues']) > 1) { ?>
                <hr>
                <p><strong>All venues by this supplier</strong></p>
                <? foreach ($venueSupplier['venues'] as $venue) { ?>
                <div class="mb-10">
                    <? if ($venue['image'] != '') { ?>
                    <img src="<?= $venue['image'] ?>" class="float:left;" style="width:50%; margin:0 1em 1em 0;">
                    <?= Html::a($venue['name'], '/venues/r/'.$venue['id']) ?>
                    <? } ?>
                </div>
                <? } ?>
                <? } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

<div class="col-sm-8 col-sm-pull-4">
    <ul class="tab-menu list-inline">
        <li><a class="tab cursor-pointer text-uppercase text-bold text-pink" href="t-overview"><i class="fa fa-caret-right"></i> Overview</a></li>
        <li><a class="tab cursor-pointer text-uppercase" href="t-notes"><i class="fa fa-caret-right"></i> Files & Notes</a></li>
        <li><a class="tab cursor-pointer text-uppercase" href="t-prices"><i class="fa fa-caret-right"></i> Price</a></li>
        <?php if (!empty($theVenue['dvo'])) { ?>
        <li><a class="tab cursor-pointer text-uppercase" href="t-oldprices"><i class="fa fa-caret-right"></i> Price (old)</a></li>
        <?php } ?>
        <li><a class="tab cursor-pointer text-uppercase" href="t-media"><i class="fa fa-caret-right"></i> Photos</a></li>
        <li><a class="cursor-pointer text-uppercase" href="/tools/tour-ks?ks=<?= $theVenue['id'] ?>" target="_blank"><i class="fa fa-caret-right"></i> Tours</a> <i style="font-size:80%" class="fa fa-external-link text-muted"></i></li>
        <li><a class="cursor-pointer text-uppercase" href="/feedbacks?venue_id=<?= $theVenue['id'] ?>&what=<?= $theVenue['name'] ?>" target="_blank"><i class="fa fa-caret-right"></i> Feedback</a> <i style="font-size:80%" class="fa fa-external-link text-muted"></i></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="t-overview" >
            <? if ($theVenue['hotel_meta'] != '') { ?>
            <!--p><a rel="external" href="<?= $theVenue['link_tripadvisor'] ?>"><img class="img-responsive img-thumbnail" src="https://my.amicatravel.com/files/r/24783"></a></p-->
            <table class="table table-striped table-condensed table-bordered">
                <thead></thead>
                <tbody>
                    <tr>
                        <th>Tags</th><td><?= str_replace(['str ', 're '], ['<span class="text-pink">strategic</span> ', '<span class="text-success">recommended</span> ', ], $theVenue['search']) ?></td>
                    </tr>
                    <?
                    $data = unserialize($theVenue['hotel_meta']);
                    foreach ($data as $k=>$v) {
                         if ($k != 'image2') { 
                    ?>
                    <tr>
                        <th><?= ucfirst($k) ?></th>
                        <td><?= nl2br($v) ?></td>
                    </tr>
                    <?
                        }
                    }
                    ?>                  
                </tbody>
            </table>
            <? } ?>
            <div><?= Markdown::process($theVenue['info']) ?></div>
        </div>
        <? include('venue_r__notes.php') ?>
        <? include('venue_r__prices.php') ?>
        <? include('venue_r__oldprices.php') ?>
        <? include('venue_r__media.php') ?>
    </div>
</div>

<style type="text/css">
    .fancybox-overlay {z-index:1000!important}
    a.from-past {color:#c00;}
</style>
<?
$js = <<<'TXT'
$('.tab-menu li a.tab').on('click', function(e){
    e.preventDefault()
    $('.tab-menu li a.tab').removeClass('text-bold text-pink')
    $(this).addClass('text-bold text-pink')
    $('.tab-pane.active').removeClass('active').hide()
    $('#' + $(this).attr('href')).addClass('active').show()
})

$.fancybox.defaults.thumbs = {
    autoStart   : true,   // Display thumbnails on opening
    hideOnClose : true     // Hide thumbnail grid when closing animation starts
};

$('a.from-dt').on('click', function(){
    $('a.from-dt').removeClass('fw-b');
    $(this).addClass('fw-b');
    var id = $(this).attr('id');
    var txt = $(this).text();
    $('div.from-dt').addClass('hide');
    $('div.'+id).removeClass('hide');
    $('span#quote-from-dt').text(txt);
    return false;
});
$('a.from-past:first').click();
$('[rel="popover"]').popover({
    'trigger':'hover'
});

$('#t-prices').on('click', 'a.dv_d', function(event){
    event.preventDefault();
    var id = $(this).data('id');
    var jqxhr = $.post('/dv/d/' + id, {x:'x'})
    .done(function(data) {
        $('tr#tr_dv_' + id).remove();
    }, 'text')
    .fail(function() {
        alert('Error deleting DV!');
    });
});

$.fn.datepicker.language['vi'] = {
    days: ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'],
    daysShort: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
    daysMin: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
    months: ['Tháng giêng', 'Tháng hai', 'Tháng ba', 'Tháng tư', 'Tháng năm', 'Tháng sáu', 'Tháng bảy', 'Tháng tám', 'Tháng chín', 'Tháng mười', 'Tháng mười một', 'Tháng mười hai'],
    monthsShort: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
    today: 'Hôm nay',
    clear: 'Xoá',
    dateFormat: 'mm/dd/yyyy',
    timeFormat: 'hh:ii aa',
    firstDay: 1
};

$('#selme').datepicker().data('datepicker').selectDate(new Date());

var date_select = '';
var ncc_id = $('#table_dv').data('venue-id');
var tday = new Date();
var id_dvc = 0;
var current_dvd = [];;
var dvc_current;

if (tday) {
    $.ajax({
        method: "GET",
        url: "/venues/list_dv",
        data: { venue_id: ncc_id, date_selected: tday.getDate()+'/'+(tday.getMonth()+1)+'/'+tday.getFullYear()},
        dataType: 'json'
    })
    .done(function(result) {
        console.log(result);//return;
        if (result.err && result.err != undefined) { $('#list_dv').empty(); return;}
        var dvc = dvc_current = result['dvc'];
        var venue = dvc['venue'];
        current_dvd = dvc.dvd.def.split(';');
        // $('#wrap_dvd').empty();
        // $('#wrap_dvd').append('<a class="masterTooltip dvd-a" title="'+dvc.dvd.def+'">'+dvc.dvd.code+'</a>');
        if (id_dvc != dvc.id) {
            $('.note_display').html(dvc.body);
            id_dvc = dvc.id
        }
        $('#list_dv').empty();
        jQuery.each(venue['dv'], function(i, dv){
            var html = '<tr> <td><a href="/dv/r/'+dv.id+'" target="_blank">'+dv.name+'</a></td> <td class=""> <span class="masterTooltip" title="'+dvc.dvd.def+'">'+dvc.dvd.code+'</span><a class="text-danger dv_d" href="#" data-id="'+dv.id+'"></a> </td> <td class="content_price text-right"> </td> </tr>';
            $('#list_dv').append(html);
            jQuery.each(dv['cp'], function(k, cp){
                if (cp.period == dvc.dvd.code) {
                    var curren = new Number(cp.price).format(2);
                    var curren_arr = curren.split('.');
                    if (parseInt(curren_arr[1]) == 0 ) {
                        curren = curren_arr[0];
                    }
                    var td_html = '<div><span class="pull-left text-muted">'+cp.conds+'</span> <a href="#">'+curren+'</a> <span class="text-muted"> '+cp.currency+'</span></div>';
                    $('#list_dv').find('tr:last td.content_price').append(td_html);
                }
            });
        });

    })
    .fail(function() {
        alert( "Error" );
    });
}

$('#selme').datepicker({
    firstDay: 1,
    todayButton: new Date(),
    clearButton: true,
    autoClose: true,
    language: '{$LANG}',
    dateFormat: 'd/m/yyyy',
    onSelect: function(fd, d, picker) {
        if (!d) return;
        date_selected = fd;
        var venue_id = $('#table_dv').data('venue-id');
        // if (current_dvd.length == 0) {return false;}
        var exist = false;
        jQuery.each(current_dvd, function(index, item){
            var dt_items = item.split('-');
            if (dt_items.length != 2) { return false;}
            var dt1 = dt_items[0].split('/'),
                dt_f = dt1[2]+'/'+dt1[1]+'/'+dt1[0];
            var dt2 = dt_items[1].split('/'),
                dt_s = dt2[2]+'/'+dt2[1]+'/'+dt2[0];
            if (new Date(dt_f).valueOf() <= d.valueOf() && d.valueOf() <= new Date(dt_s).valueOf()) {
                exist = true;
            }

        });
        if (!exist) {
            $.ajax({
                method: "GET",
                url: "/venues/list_dv",
                data: { venue_id: venue_id, date_selected:date_selected},
                dataType: 'json'
            })
            .done(function(result) {
                if (result.err && result.err != undefined) { $('#list_dv').empty(); return;}
                var dvc = dvc_current = result['dvc'];
                var venue = dvc['venue'];
                current_dvd = dvc.dvd.def.split(';');
                if (id_dvc != dvc.id) {
                    $('.note_display').html(dvc.body);
                    id_dvc = dvc.id
                }
                $('.note_display').html(dvc.body);
                $('#list_dv').empty();
                jQuery.each(venue['dv'], function(i, dv){
                    var html = '<tr> <td><a href="/dv/r/'+dv.id+'" target="_blank">'+dv.name+'</a></td> <td class=""> <span class="masterTooltip" title="'+dvc.dvd.def+'">'+dvc.dvd.code+'</span><a class="text-danger dv_d" href="#" data-id="'+dv.id+'"></a> </td> <td class="content_price text-right"> </td> </tr>';
                    $('#list_dv').append(html);
                    jQuery.each(dv['cp'], function(k, cp){
                        if (cp.period == dvc.dvd.code) {
                            var curren = new Number(cp.price).format(2);
                            var curren_arr = curren.split('.');
                            if (parseInt(curren_arr[1]) == 0 ) {
                                curren = curren_arr[0];
                            }
                            var td_html = '<div><span class="pull-left text-muted">'+cp.conds+'</span> <a href="#">'+curren+'</a> <span class="text-muted"> '+cp.currency+'</span></div>';
                            $('#list_dv').find('tr:last td.content_price').append(td_html);
                        }
                    });
                });

            })
            .fail(function() {
                alert( "Error" );
            });
        } else {
            var dvc = dvc_current;
            var venue = dvc['venue'];
            current_dvd = dvc.dvd.def.split(';');
            if (id_dvc != dvc.id) {
                $('.note_display').html(dvc.body);
                id_dvc = dvc.id
            }
            $('.note_display').html(dvc.body);
            $('#list_dv').empty();
            jQuery.each(venue['dv'], function(i, dv){
                var html = '<tr> <td><a href="/dv/r/'+dv.id+'" target="_blank">'+dv.name+'</a></td> <td class=""> <span class="masterTooltip" title="'+dvc.dvd.def+'">'+dvc.dvd.code+'</span><a class="text-danger dv_d" href="#" data-id="'+dv.id+'"></a> </td> <td class="content_price text-right"> </td> </tr>';
                $('#list_dv').append(html);
                jQuery.each(dv['cp'], function(k, cp){
                    if (cp.period == dvc.dvd.code) {
                        var curren = new Number(cp.price).format(2);
                        var curren_arr = curren.split('.');
                        if (parseInt(curren_arr[1]) == 0 ) {
                            curren = curren_arr[0];
                        }
                        var td_html = '<div><span class="pull-left text-muted">'+cp.conds+'</span> <a href="#">'+curren+'</a> <span class="text-muted"> '+cp.currency+'</span></div>';
                        $('#list_dv').find('tr:last td.content_price').append(td_html);
                    }
                });
            });
        }
    }
});

////////period//////////////
$('[name="ranger_dt"]').change(function(){
    console.log('event change');
    var arr_dt = $(this).children("option:selected").text().split('-');
    var date_selected = arr_dt[0].trim();
    $('#selme').val(date_selected);
    var venue_id = $('#table_dv').data('venue-id');
    $.ajax({
        method: "GET",
        url: "/venues/list_dv",
        data: { venue_id: venue_id, date_selected:date_selected},
        dataType: 'json'
    })
    .done(function(result) {
        if (result.err && result.err != undefined) { $('#list_dv').empty(); return;}
        var dvc = dvc_current = result['dvc'];
        var venue = dvc['venue'];
        if (dvc.dvd.length == 0) {
            console.log("dvc[dvd] = null"); return;
        }
        current_dvd = dvc.dvd.def.split(';');
        if (id_dvc != dvc.id) {
            $('.note_display').html(dvc.body);
            id_dvc = dvc.id
        }
        $('.note_display').html(dvc.body);
        $('#list_dv').empty();
        jQuery.each(venue['dv'], function(i, dv){
            var html = '<tr> <td><a href="/dv/r/'+dv.id+'" target="_blank">'+dv.name+'</a></td> <td class=""> <span class="masterTooltip" title="'+dvc.dvd.def+'">'+dvc.dvd.code+'</span><a class="text-danger dv_d" href="#" data-id="'+dv.id+'"></a> </td> <td class="content_price text-right"> </td> </tr>';
            $('#list_dv').append(html);
            jQuery.each(dv['cp'], function(k, cp){
                if (cp.period == dvc.dvd.code) {
                    var curren = new Number(cp.price).format(2);
                    var curren_arr = curren.split('.');
                    if (parseInt(curren_arr[1]) == 0 ) {
                        curren = curren_arr[0];
                    }
                    var td_html = '<div><span class="pull-left text-muted">'+cp.conds+'</span> <a href="#">'+curren+'</a> <span class="text-muted"> '+cp.currency+'</span></div>';
                    $('#list_dv').find('tr:last td.content_price').append(td_html);
                }
            });
        });

    })
    .fail(function() {
        alert( "Error" );
    });
});
////////tooltip//////////////
$(document).on({
    mouseenter: function(){// Hover over code
        var title = $(this).attr('title');
        $(this).data('tipText', title).removeAttr('title');
        $('<p class="my_tooltip"></p>')
        .text(title)
        .appendTo('body')
        .fadeIn('slow');
    },
    mouseout: function(){
        // Hover out code
        $(this).attr('title', $(this).data('tipText'));
        $('.my_tooltip').remove();
    },
    mousemove: function(e){
        var mousex = e.pageX + 20; //Get X coordinates
        var mousey = e.pageY + 10; //Get Y coordinates
        $('.my_tooltip')
        .css({ top: mousey, left: mousex , zIndex: 999999})
    }

},'.masterTooltip');

Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};
TXT;

$js = str_replace(['{$LANG}'], [Yii::$app->language], $js);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.en.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.fr.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/css/datepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);