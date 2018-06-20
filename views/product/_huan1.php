<?php

use yii\helpers\Html;

include(Yii::getAlias('@app').'/views/day/_day_u_modal.php');
$theDays = \yii\helpers\ArrayHelper::index($theProduct['days'], 'id');
$theProgram = $theProduct;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">
            <?= Yii::t('p', 'Itinerary') ?>
            <small>
                <a data-toggle="modal" data-target="#help-modal" href="#">Help</a>
                &middot;
                <a class="toggle-day-contents" href="#">Toggle all days</a>
            </small>
        </h6>
        <div class="heading-elements">
            <ul class="heading-thumbnails">
                <li><img class="img-circle" src="/timthumb.php?w=100&h=100&src=<?= $theProduct['updatedBy']['image'] ?>"></li>
            </ul>
        </div>
    </div>
    <div id="xhome" class="hidden">
        <? if (in_array(USER_ID, [1, $theProduct['created_by'], $theProduct['updated_by']])) { ?>
        <i title="<?= Yii::t('app', 'Go top') ?>" class="xyz text-muted fa fa-fw fa-arrow-up cursor-pointer"></i>
        <i title="<?= Yii::t('app', 'Delete') ?>" class="x text-muted fa fa-fw fa-trash-o delete-day text-danger cursor-pointer"></i>
        <i title="<?= Yii::t('app', 'Edit') ?>" class="x text-muted fa fa-fw fa-edit edit-day cursor-pointer" xdata-toggle="modal" xdata-target="#edit-day"></i>
        <i title="<?= Yii::t('app', 'Copy down') ?>" class="x text-muted fa fa-fw fa-copy insert-from cursor-pointer"></i>
        <i title="<?= Yii::t('app', 'Add blank') ?>" class="x text-muted fa fa-fw fa-file-o insert-from cursor-pointer"></i>
        <i title="<?= Yii::t('app', 'Add from DB') ?>" class="x text-muted fa __fa-fw fa-plus insert-from cursor-pointer"></i>
        <i title="<?= Yii::t('app', 'Link to this day') ?>" class="xyz text-muted fa fa-fw fa-hashtag cursor-pointer"></i>
        <? } ?>
    </div>
    <div id="divsortable">
        <table id="tblCurrentProg" class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th width="20" class="text-center"><i class="text-muted fa fa-arrows-v"></i></th>
                    <th class="no-padding-left">
                        Activity
                        <? if (in_array(USER_ID, [$theProduct['created_by'], $theProduct['updated_by']])) { ?> 
                        <div class="day-actions text-nowrap text-right pull-right position-right">
                            <i title="Add blank" class="fa fa-fw fa-file-o first insert-from text-muted cursor-pointer"></i>
                            <i title="Add from DB" class="fa __fa-fw fa-plus first insert-from text-muted cursor-pointer"></i>
                        </div>
                        <? } ?>
                    </th>
                </tr>
            </thead>
            <tbody id="sortable" style="overflow:auto">
                <?
                $cnt = 0;
                foreach ($dayIdList as $dayId) {
                    if (isset($theDays[$dayId])) {
                        $day = $theDays[$dayId];
                        $dayDate = date('Y-m-d', strtotime('+ '.$cnt.' days', strtotime($theProduct['day_from'])));
                        $cnt ++;
                ?>
                <tr class="tr-day" data-id="<?= $day['id'] ?>" id="ngay_<?= $day['id'] ?>">
                    <td class="text-center" width="20">
                        <span title="<?= Yii::t('p', 'Drag to sort') ?>" class="handle cursor-move text-muted"><?= $cnt ?></span>
                    </td>
                    <td class="no-padding-left">
                        <div class="day-actions text-nowrap text-right pull-right position-right">
                        </div>
                        <span class="day-date"><?= Yii::$app->formatter->asDate($dayDate, 'php:j/n/Y D') ?></span>
                        <a class="day-name" href="/days/r/<?= $day['id'] ?>"><?= $day['name'] ?></a>
                        <em class="day-meals text-nowrap"><?= $day['meals'] ?></em>
                        <div class="day-content mt-20" style="display:none;">
                            <p>
                                <span class="day-guides"><?= $day['guides'] == '' ? '' : '<i class="fa fa-user"></i> '.$day['guides'] ?></span>
                                <span class="day-transport"><?= $day['transport'] == '' ? '' : '<i class="fa fa-car"></i> '.$day['transport']?></span>
                            </p>
                            <div class="day-body" id="day-body-<?= $day['id'] ?>">
                            <?
                            if (substr($day['body'], 0, 1) == '<') {
                                echo $day['body'];
                            } else {
                                echo $parser->parse($day['body']);
                            }
                            ?>
                            </div>
                            <div style="display:none">
                                <div class="day-image"><?= $day['image'] ?></div>
                                <div class="day-note"><?= $day['note'] ?></div>
                            </div>
                        </div>    
                    </td>
                </tr>
                <?
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <script type="text/javascript">
    var start_date = '<?= $theProduct['day_from'] ?>';
    </script>
<?
$js = <<<'TXT'
$('.x').hide();
$('#tblCurrentProg').on('mouseenter', 'tr.tr-day td.no-padding-left', function(){
    $('i.x').prependTo($(this).find('.day-actions')).show();
});

$('a.toggle-day-contents').on('click', function(){
    if ($('#tblCurrentProg .day-content:visible').length > 0){
        $('.day-content').hide();
    } else {
        $('#tblCurrentProg .day-content').toggle();
    }
    return false;
});

// Toggle day content
$('#tblCurrentProg').on('click', '.day-name', function(){
    $(this).closest('td').find('.day-content').toggle();
    return false;
});

// Edit day
$('#tblCurrentProg').on('click', 'i.edit-day', function(){
    var tr = $(this).closest('tr.tr-day');
    var id = tr.data('id');
    $('#day-id').val(id);
    $('#day-name').val(tr.find('.day-name:eq(0)').html());
    $('#day-image').val(tr.find('.day-image:eq(0)').html());
    $('#day-meals').val(tr.find('.day-meals:eq(0)').html());
    $('#day-guides').val(tr.find('.day-guides:eq(0)').html().replace('<i class="fa fa-user"></i> ', ''));
    $('#day-transport').val(tr.find('.day-transport:eq(0)').html().replace('<i class="fa fa-car"></i> ', ''));
    $('#day-body').val(tr.find('.day-body:eq(0)').html());
    $('#day-note').val(tr.find('.day-note:eq(0)').html());
    $('#edit-day').modal('show');
});

$(document).on("beforeSubmit", "#editDayForm", function () {
    var id = $('#day-id').val();
    var tr = $('tr.tr-day[data-id="'+id+'"]');
    $('#editDayForm').find('button[type="submit"]').html('Saving...').prop('disabled', true); 
    $.ajax({
        method: "POST",
        url: '?action=edit-day&xh',
        data: $(this).serialize()
    })
    .done(function() {
        tr.find('.day-name:eq(0)').html($('#day-name').val());
        tr.find('.day-image:eq(0)').html($('#day-image').val());
        var dayguides = $('#day-guides').val();
        if (dayguides != '') {
            dayguides = '<i class="fa fa-user"></i> ' + dayguides;
        }
        var daytrans = $('#day-transport').val();
        if (daytrans != '') {
            daytrans = '<i class="fa fa-car"></i> ' + daytrans;
        }
        tr.find('.day-meals:eq(0)').html($('#day-meals').val());
        tr.find('.day-guides:eq(0)').html(dayguides);
        tr.find('.day-transport:eq(0)').html(daytrans);
        tr.find('.day-body:eq(0)').html($('#day-body').val());
        tr.find('.day-note:eq(0)').html($('#day-note').val());
        $('#edit-day').modal('hide');
    })
    .fail(function(){
        alert( "Error saving data" );
    })
    .always(function(){
        $('#editDayForm').find('button[type="submit"]').html('Submit').prop('disabled', false); 
    })
    ;
    return false;
});

// Delete day
$('#tblCurrentProg tbody').on('click', 'i.delete-day', function(){
    var idx = $(this).closest('tr.tr-day').index();
    if (!confirm('Delete day #'+ (1 + idx) + '?')) {
        return false;
    }
    $('i.x').prependTo($('#xhome'));
    $('#tblCurrentProg').block({ 
        message: '<div><i class="fa fa-refresh fa-spin"></i> Processing</div>', 
        css: { border: '3px solid #a00', padding:20 } 
    });
    $.ajax({
        method: "POST",
        url: "?action=delete-day&xh",
        data: {day: idx}
    })
    .done(function( msg ) {
        $('#tblCurrentProg tbody tr:eq('+idx+')').remove();
        recountDays();
    })
    .fail(function( msg ) {
        alert('Error! Day could not be deleted.');
    })
    .always(function(){
        $('#tblCurrentProg').unblock();
    })
    return false;
});

// Mark insert day at
var insertAt = 0;
$('#tblCurrentProg').on('click', 'i.insert-from.fa-plus', function(){
    $('#tblCurrentProg i.insert-from').not($(this)).removeClass('text-pink');
    $(this).toggleClass('text-pink');
    if ($(this).hasClass('text-pink')) {
        if ($(this).hasClass('first')) {
            idx = -1;
        } else {
            var idx = $(this).closest('tr.tr-day').index();
        }
        insertAt = idx;
        $('#huan2').modal('show');
    } else {
        insertAt = 0;
        $('#huan2').modal('hide');
    }
    return false;
});

// Dong form
$("#huan2").on("hidden.bs.modal", function () {
    $('#tblCurrentProg i.insert-from.fa-plus.text-pink').removeClass('text-pink');
});

// Copy day down
$('#tblCurrentProg').on('click', 'i.insert-from.fa-copy', function(){
    var idx = $(this).closest('tr.tr-day').index();
    var id = $('tr.tr-day:eq(' + idx + ')').data('id');

    $('#tblProgList, #tblCurrentProg').block({ 
        message: '<div><i class="fa fa-refresh fa-spin"></i> Processing</div>', 
        css: { border: '3px solid #a00', padding:20 } 
    }); 
    $.ajax({
        method: "POST",
        url: "?action=insert-day&xh",
        data: {from: 'previous', id: id, at: idx + 1}
    })
    .done(function( response ) {
        insertDay(response, idx);
        recountDays();
    })
    .fail(function( msg ) {
        alert('Error! Day could not be added.');
    })
    .always(function(){
        $('#tblProgList, #tblCurrentProg').unblock();
    })
    return false;
});

// Insert blank day
$('#tblCurrentProg').on('click', 'i.insert-from.fa-file-o', function(){
    var idx = $(this).closest('tr.tr-day').index();

    $('#tblProgList, #tblCurrentProg').block({ 
        message: '<div><i class="fa fa-refresh fa-spin"></i> Processing</div>', 
        css: { border: '3px solid #a00', padding:20 } 
    }); 
    $.ajax({
        method: "POST",
        url: "?action=insert-day&xh",
        data: {from: 'blank', id: 0, at: idx + 1}
    })
    .done(function( response ) {
        insertDay(response, idx);
        recountDays();
    })
    .fail(function( msg ) {
        alert('Error! Day could not be added.');
    })
    .always(function(){
        $('#tblProgList, #tblCurrentProg').unblock();
    })
    return false;
});

$( "#sortable" ).sortable({
    handle: ".handle",
    update: function( event, ui ) {
        $('#divsortable').block({ 
            message: '<div><i class="fa fa-refresh fa-spin"></i> Processing</div>', 
            css: { border: '3px solid #a00', padding:20 } 
        }); 
        var data = $( "#sortable" ).sortable( "serialize" );
        $.ajax({
            method: "POST",
            url: "?action=sort-day",
            data: data
        })
        .done(function( msg ) {
            recountDays();
        })
        .fail(function( msg ) {
            $('#sortable').sortable('cancel')
        })
        .always(function(){
            $('#divsortable').unblock();
        })
        ;
    }
});

// XYX
function recountDays() {
    $('#tblCurrentProg tbody tr.tr-day').each(function(i){
        $(this).find('td:eq(0) span').html(i + 1);
        $(this).find('td:eq(1) .day-date').html(moment(start_date).add(i, 'days').format('D/M/Y ddd'));
    });
}

function insertDay(response, idx) {
    var row = $(
'<tr class="tr-day" data-id="' + response.id + '" id="ngay_' + response.id + '">'
+'    <td title="Drag to sort" class="text-center" width="20"><span class="handle cursor-move text-muted"></span></td>'
+'    <td class="no-padding-left">'
+'        <div class="day-actions text-nowrap text-right pull-right position-right"></div>'
+'        <span class="day-date"></span>'
+'        <a class="day-name" href="/days/r/'+response.id+'">'+response.title+'</a>'
+'        <em class="text-nowrap day-meals">'+response.meals+'</em>'
+'        <div class="day-content mt-20" style="display:none;">'
+'            <p>'
+'                <span class="day-guides">' + (response.guides == '' ? '' : '<i class="fa fa-user"></i> ' + response.guides) + '</span>'
+'                <span class="day-transport">' + (response.transport == '' ? '' : '<i class="fa fa-car"></i> ' + response.transport) + '</span>'
+'            </p>'
+'            <div class="day-body">' + response.body + '</div>'
+'        </div>'
+'    </td>'
+'</tr>');
    if (idx == -1) {
        row.prependTo($('#tblCurrentProg tbody'));
    } else {
        row.insertAfter($('#tblCurrentProg tbody tr:eq(' + idx + ')'));
    }
}

TXT;
        $this->registerJsFile('https://code.jquery.com/ui/1.12.1/jquery-ui.js', ['depends'=>'yii\web\JqueryAsset']);
        $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js', ['depends'=>'yii\web\JqueryAsset']);
        $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js', ['depends'=>'yii\web\JqueryAsset']);
        $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/moment-with-locales.min.js', ['depends'=>'yii\web\JqueryAsset']);
        $this->registerJs($js);
    ?>
</div>
