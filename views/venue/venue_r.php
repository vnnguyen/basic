<?php
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\FileHelper;

Yii::$app->params['body_class'] = 'bg-white sidebar-xs';
Yii::$app->params['page_layout'] = '-t';
Yii::$app->params['page_title'] = $theVenue['name'];

// Stats
// 1 - Rating
// 2 - Number of tours
$sql = 'SELECT tour_id FROM cpt WHERE venue_id=:id GROUP BY tour_id';
$numTours = Yii::$app->db->createCommand($sql, [':id'=>$theVenue['id']])->queryAll();
$numTours = count($numTours);
// 3 - Num something
// 4 - Number of years since first tour
$sql = 'SELECT YEAR(dvtour_day) FROM cpt WHERE venue_id=:id ORDER BY dvtour_day LIMIT 1';
$numYears = Yii::$app->db->createCommand($sql, [':id'=>$theVenue['id']])->queryScalar();
if (!$numYears) {
    $numYears = 0;
} else {
    $numYears = date('Y') - $numYears + 1;
}

// Stars
$stars = '';
$starNum = '';
if ($theVenue['stype'] == 'hotel') {
    for ($i = 2; $i <= 5; $i ++) {
        if (strpos($theVenue['search'], $i.'s') !== false) {
            $stars = ' '.str_repeat('<i class="fa fa-star text-orange-300"></i>', $i);
            $starNum = $i;
        }
    }
    if ($stars != '') {
        Yii::$app->params['page_small_title'] = $stars.' ';
    }
}

include('_venue_inc.php');

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
            $subRange = explode(';', str_replace(',', ';', $dvd['def']));
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
?>
<script>
var venue_id = <?= $theVenue['id'] ?> 
var lang = '<?= Yii::$app->language ?>'
</script>
<?php
/*
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
*/
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

<div class="col-md-12">
    <ul class="nav nav-tabs list-inline" role="tablist">
        <li class="nav-item"><a class="nav-link active cursor-pointer text-uppercase text-bold text-pink" data-toggle="tab" href="t-overview"><i class="fa fa-caret-right"></i> Overview</a></li>
        <li class="nav-item"><a class="nav-link tab cursor-pointer text-uppercase" data-toggle="tab" href="t-prices"><i class="fa fa-caret-right"></i> Price</a></li>
        <?php if ($theVenue['stype'] != 'hotel' && (!empty($theVenue['dvo']) || !empty($theVenue['info_pricing']))) { ?>
        <li class="nav-item"><a class="tab cursor-pointer text-uppercase" href="t-oldprices"><i class="fa fa-caret-right"></i> Price (old)</a></li>
        <?php } ?>
        <li ><a class="nav-link cursor-pointer text-uppercase link" data-toggle="tab"  href="/tools/tour-ks?ks=<?= $theVenue['id'] ?>" target="_blank"><i class="fa fa-caret-right"></i> Tours</a></li>
        <li><a class="nav-link cursor-pointer text-uppercase link" data-toggle="tab"  href="/feedbacks?venue_id=<?= $theVenue['id'] ?>&what=<?= $theVenue['name'] ?>" target="_blank"><i class="fa fa-caret-right"></i> Feedback</a>
    </ul>
    <hr>
    <div class="tab-content">
        <div id="t-overview" class=" row tab-pane fade show active "><div class="d-flex flex-sm-row-reverse">
        <?php include('venue_r__overview.php') ?></div></div><!-- t-overview -->
        <div id="t-notes" class="row tab-pane fade"><?php include('venue_r__notes.php') ?></div>
        <div id="t-prices" class="row tab-pane fade"><?php include('venue_r__prices.php') ?></div>
        <?php if ($theVenue['stype'] != 'hotel' && (!empty($theVenue['dvo']) || !empty($theVenue['info_pricing']))) { ?>
        <div id="t-oldprices" class="row tab-pane"><?php include('venue_r__oldprices.php') ?></div>
        <?php } ?>
    </div>
</div>

<style type="text/css">
    .fancybox-overlay {z-index:1000!important}
    a.from-past {color:#c00;}
</style>
<?
$js = <<<'TXT'
$('.tab-pane:not(.active)').hide();
$('.nav-tabs li a.nav-link').on('click', function(e){
    e.preventDefault()
    var href = $(this).attr('href');
    $('.nav-tabs li a.nav-link').removeClass('text-bold text-pink');
    $(this).addClass('text-bold text-pink');
    $('.tab-pane.active').removeClass('active').hide();
    if($(this).hasClass('link')) {
        window.open(href);
    } else {
        $('#' + href).addClass('show active').show();
    }

});



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



var date_select = '';
var ncc_id = $('#table_dv').data('venue-id');
var tday = new Date();
var id_dvc = 0;
var current_dvd = [];;
var dvc_current;


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
if (Yii::$app->language == 'fr') {
    $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.fr.min.js', ['depends'=>'yii\web\JqueryAsset']);
} else {
    $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.en.min.js', ['depends'=>'yii\web\JqueryAsset']);
}
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/css/datepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);