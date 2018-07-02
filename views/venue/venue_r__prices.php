<?php 
use yii\helpers\Html;
use yii\helpers\Markdown;

?>
<?php if (strpos($theVenue['new_tags'], 'new_p_new') !== false || strpos($theVenue['new_tags'], 'new_p_both') !== false) { ?>
<style>
table.table-pricenew.table.table-narrow {width:auto!important}
.table-pricenew.table-narrow tr>th, .table-pricenew.table-narrow tr>td {padding:2px 9px!important;}
.table-pricenew tr>th {background-color:#fffff3!important;}
.money {font-weight: 600; text-align: right;}
</style>
<div class="col-md-12">
    <p>Select date of stay <input id="seldate_new" type="text" style="width:150px" data-today-button="<?= NOW ?>" data-venue-id="<?= $theVenue['id']?>" class="form-control" name="" value=""></p>

<?php
$data = [];
$table = str_replace(['<table>', '</table>', '<tbody>', '</tbody>', '<tr>'], ['', '', '', '', ''], $theVenue['new_pricetable']);
$lines = explode('</tr>', $table);
  

$arr_t_name = [
1 => Yii::t('price', 'Room rates'),
2 => Yii::t('price', 'Meal'),
3 => Yii::t('price', 'Child policy'),
4 => Yii::t('price', 'Early check - Late check - Group policy'),
5 => Yii::t('price', 'Activity rates'),
6 => Yii::t('price', 'Agency staff policy'),
7 => Yii::t('price', 'Promotion'),
8 => Yii::t('price', 'Reservation'),
9 => Yii::t('price', 'Payment'),
10 => Yii::t('price', 'Cancellation policy'),
];
foreach ($lines as $line) {
    $cells = explode('</td>', $line);
    $ccnt = 0;
    $cspn = 0;
    $arr = [];
    foreach ($cells as $cnt=>$cell) {
        $cell = trim(str_replace(['&nbsp;'], [''], $cell));
        $arr[] = $cell;
        // $arr[] = $cell;
        // TODO read colspan, strip <td> tags
        if (substr($cell, -1) != '>') {
            for ($i = 2; $i <= 10; $i++) {
                if (strpos($cell, ' colspan="'.$i.'"') !== false) {
                    $cspn += $i;
                }
            }
            $ccnt = $cnt;
        }
    }
    $ccnt += $cspn - 3;
    if (isset($arr[1]) && trim($arr[1]) != '<td>') {
        $th = substr($arr[1], -1) == '*';
        $tbl = str_replace(['<td>', '*', '[', ']'], ['', '', '', ''], $arr[1]);

        // This is a data line
        if (!isset($data[$tbl])) {
            $data[$tbl] = [
                'name'=>$arr_t_name[$tbl],
                'ccnt'=>$ccnt,
                'rows'=>[],
                'note'=>'',
            ];
        }
        $arr[0] = str_replace(['<td>', '{', '}', ' '], ['', '', '', ''], $arr[0]);
        $class = $arr[0] == '' ? '' : md5($arr[0]).' hide has_daterange '.$arr[0];
        unset($arr[0], $arr[1]);
        $data[$tbl]['ccnt'] = $ccnt;
        // for ($i = $ccnt; $i <= 30; $i ++) {
        //     unset($arr[$i]);
        // }
       
        $data[$tbl]['rows'][] = [
            'th'=>$th,
            // 'dates'=>$arr[0],
            'class'=>$class,
            'cols'=>implode('</td>', $arr),
        ];
    }
}

if (isset($_GET['x'])) {
    \fCore::expose($data);
    exit;
}

for ($i = 1; $i <= count($arr_t_name) ; $i ++) {

    if (!isset($data[$i])) {
        echo '<h4 class="text-slate">', $i.'. '.$arr_t_name[$i], '</h4><table class="table table-pricenew table-narrow table-bordered t_cutcol"> <tr class="text-muted">'.Yii::t('price', 'empty'). '</tr>';
    } else {
        $table = $data[$i];
        echo '<h4 class="text-slate">', $i.'. '.$table['name'], '</h4><table class="table table-pricenew table-narrow table-bordered t_cutcol">';

        foreach ($table['rows'] as $k => $row) {
            if ($row['th'] === true) {
                $row['cols'] = str_replace(['<td', '</td>'], ['<th', '</th>'], $row['cols']);
                // if ($i ==2) {
                //     var_dump($row['cols']);die;
                // }
            }
            echo '<tr class="', $row['class'], '">', $row['cols'], '</tr>';
        }
    }
    echo '</table><br>';
}
if (USER_ID == 1) {
    // \fCore::expose($data);
    // exit;
}
?>
<hr>

</div>
<?php } ?>

<?php if (strpos($theVenue['new_tags'], 'new_p_new') === false || strpos($theVenue['new_tags'], 'new_p_both') !== false) { ?>

<div class="col-md-12">
    <div class="row">
        <div class="col-lg-6 col-md-7">
            <div class="row mb-20">
                <div class="col-md-3">
                    Select a date:<br>
                    <input id="seldate" type="text" data-today-button="<?= NOW ?>" class="form-control" name="" value="">
                </div>
                <div class="col-md-9">
                    Or select a period:<br>
                    <select name="selrange" class="form-control">
                        <option value="">- Select -</option>
                        <?php foreach ($theVenue['dvc'] as $dvc) { ?>
                        <optgroup label="Contract <?= $dvc['name'] ?>">
                            <?php foreach ($dvc['dvd'] as $dvd) { ?>
                                <?php if ($dvd['stype'] == 'date') { ?>
                        <option value="<?
                        $ranges = $this->context->parseDateConditionString($dvd['def']);
                        echo date('j/n/Y', strtotime($ranges[0][0]));
                        ?>"><?= $dvd['def'] ?> (<?= $dvd['code'] ?>)</option>
                                <?php } ?>
                            <?php } ?>
                        </optgroup>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div id="show_price"></div>
            <?php if (in_array(USER_ID, [1, 8, 9198, 11134718])) { ?>
            <div><?= Html::a('+New service', '/dv/c?venue_id='.$theVenue['id']) ?></div>            <?php } ?>

        </div>
        <div class="col-lg-6 col-md-5">
            <p><span class="text-uppercase text-bold">CONTRACTS:</span>
                <?php foreach ($theVenue['dvc'] as $dvc) { ?>
                <?= Html::a($dvc['name'], '/dvc/r/'.$dvc['id'], ['title'=>'Validity: '.date('j/n/Y', strtotime($dvc['valid_from_dt'])).' - '.date('j/n/Y', strtotime($dvc['valid_until_dt']))]) ?>,
                <?php } ?>
            </p>
            <div id="show_contract"></div>
        </div>
    </div>
</div>

<?php } ?>

<?php

if (strpos($theVenue['new_tags'], 'new_p_new') !== false || strpos($theVenue['new_tags'], 'new_p_both') !== false) {

$js = <<<'JS'

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

$('#seldate_new').datepicker({
    firstDay: 1,
    todayButton: new Date(),
    clearButton: true,
    autoClose: true,
    language: 'en',
    dateFormat: 'dd/mm/yyyy',
    onSelect: function(fd, d, picker) {
        if (!d) return;
        var val = fd;
        if (val == '') {
            return;
        }
        var venue_id = $('#seldate_new').data('venue-id');
        $.ajax({
            method: 'POST',
            url: '/venues/price-table-2018?id=' + venue_id,
            datatype: 'json',
            data: {
                venue_id: venue_id,
                date: val,
            },
        })
        .done(function(data){
            $('.has_daterange').addClass('hide')
            $.each(data, function(idx, md5){
                $('.has_daterange.' + md5).removeClass('hide')
            })
        })
        .fail(function(data){
            if (data.message) {
                alert(data.message)
            } else {
                alert('Request failed. Please try again.')
            }
        })
    }
});
cutCol();
function cutCol()
{
    $.each($('.t_cutcol'), function(t_i, table){
         $(table).find('tr th').each(function(i) {
            if($(this).text() == ''){
                //select all tds in this column
                var tds = $(this).parents('table')
                 .find('tr td:nth-child(' + (i + 1) + ')');
                if(tds.is(':empty')) {
                    //hide header
                    // $(this).remove();
                    $(this).hide();
                    //hide cells
                    // tds.remove();
                    tds.hide();
                } 
            }
        });
    });

    $.each($('.t_cutcol'), function(t_i, table){
        var cnt_col = [];
        var Rows = $(table).find('tr');
        $.each(Rows, function(r_i, row){
            cnt_col[r_i] = 0;
            $(row).find('td,th').each(function(c_i, col){
                if($(col).text().length > 0 ){
                    cnt_col[r_i] ++;
                }
            });
        });
        var max_col = cnt_col[0];

        for(var i = 0; i < cnt_col.length; i++) {
          if(cnt_col[i] > max_col) max_col = cnt_col[i];
        }

        $.each(Rows, function(r_i, row){
            $(row).find('td, th').each(function(c_i, col){
                if(c_i >= max_col){
                    $(col).remove();
                }

                var content = $(this).text();
                if(content.length > 0){
                    //check money
                    var re = /(\d{1,3},\d{1,3})+\d/;
                    if(re.exec(content) != null) {
                        $(this).addClass('money');
                    }
                    // format number
                    var re = /^(\d+)$/;//check money;
                    if(re.exec(content) != null) {
                        $(this).addClass('text-center');
                    }
                }
            });
        });
    });
}

$('#seldate_new').datepicker().data('datepicker').selectDate(new Date());
JS;
    $this->registerJs($js);
}

if (strpos($theVenue['new_tags'], 'new_p_new') === false || strpos($theVenue['new_tags'], 'new_p_both') !== false) {
    $js = <<<'JS'
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

$('#seldate').datepicker({
    firstDay: 1,
    todayButton: new Date(),
    clearButton: true,
    autoClose: true,
    language: lang,
    dateFormat: 'd/m/yyyy',
    onSelect: function(fd, d, picker) {
        if (!d) return;
        var val = fd;
        if (val == '') {
            return;
        }

        $.ajax({
            method: 'post',
            url: '/venues/price-table/' + venue_id,
            data: {
                date: val,
            },
        })
        .done(function(data){
            $('#show_price').html(data.left)
            $('#show_contract').html(data.right)
        })
        .fail(function(data){
            if (data.message) {
                alert(data.message)
            } else {
                alert('Request failed. Please try again.')
            }
        })
    }
});

$('[name="selrange"]').on('change', function(){
    var val = $(this).val()
    $('#seldate').val(val);

    if (val == '') {
        return;
    }
    $.ajax({
        method: 'post',
        url: '/venues/price-table/' + venue_id,
        data: {
            date: val,
        },
    })
    .done(function(data){
        $('#show_price').html(data.left)
        $('#show_contract').html(data.right)
    })
    .fail(function(data){
        if (data.message) {
            alert(data.message)
        } else {
            alert('Request failed. Please try again.')
        }
    })
    .always()
});

$('#seldate').datepicker().data('datepicker').selectDate(new Date());

JS;
    $this->registerJs($js);
}
