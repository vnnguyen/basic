<?
use yii\helpers\Html;
use yii\helpers\Markdown;

?>
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
                <?php if (in_array(USER_ID, [1, 8, 9198, 34718, 44378])) { ?>
                <?= Html::a('+New contract', '/dvc/c?venue_id='.$theVenue['id']) ?>,
                <?= Html::a('+New promo', '/dvc/c?stype=promo&venue_id='.$theVenue['id']) ?>
                <?php } ?>
            </p>
            <div id="show_contract"></div>
        </div>
    </div>
</div>

<?php
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