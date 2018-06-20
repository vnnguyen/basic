<?php

use yii\helpers\Html;

if (111 !== USER_ID) {
    include(Yii::getAlias('@app').'/views/day/_day_u_modal.php');
    $theDays = \yii\helpers\ArrayHelper::index($theProduct['days'], 'id');
    $theProgram = $theProduct;
        ?>
    <? if (in_array(USER_ID, [$theProduct['created_by'], $theProduct['updated_by']])) { ?> 
    <div class="alert alert-info">NEW! Test the newly upgraded form! Hãy thử nghiệm form làm chương trình mới tiện lợi hơn! <a href="/products/h/<?= $theProduct['id'] ?>">Click this link</a>.</div>
    <? } ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">
                Itinerary
                <small><?= $theProduct['day_count'] ?>d <?= $theProduct['pax'] ?>p <?= date('j/n/Y', strtotime($theProduct['day_from'])) ?> &middot; <a class="toggle-day-contents" href="#">Toggle content</a></small>
            </h6>
            <div class="heading-elements">
                <ul class="heading-thumbnails">
                    <li><img class="img-circle" src="/timthumb.php?w=100&h=100&src=<?= $theProduct['updatedBy']['image'] ?>"></li>
                </ul>
            </div>
        </div>
        <? if (!empty($theDays)) { ?>
        <div id="divsortable">
            <table _style="border:1px solid #ccc;" class="table table-striped table-condensed">
                <thead>
                    <tr>
                        <th width="20" class="text-center"><i class="text-muted fa fa-arrows-v"></i></th>
                        <th class="no-padding-left">
                            Activity
                            <? if (in_array(USER_ID, [$theProduct['created_by'], $theProduct['updated_by']])) { ?> 
                            <?= Html::a('<i class="fa fa-plus"></i>', '/nm?action=prepare-add-day&to='.$theProgram['id'].'&at=0', ['title'=>'Add day at start', 'class'=>'pull-right position-right text-muted']) ?>
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
                    <tr id="ngay_<?= $day['id'] ?>">
                        <a name="ngay-<?= $day['id'] ?>"></a>
                        <td title="Drag to sort" class="handle cursor-move text-muted text-center" width="20">
                            <?= $cnt ?>
                        </td>
                        <td class="no-padding-left">
                            <div id="day-actions-<?= $day['id'] ?>" class="day-actions text-nowrap text-right pull-right position-right">
                                <? if (in_array(USER_ID, [$theProduct['created_by'], $theProduct['updated_by']])) { ?>
                                <a title="<?= Yii::t('app', 'Go top') ?>" class="x text-muted" href="#divsortable"><i class="fa fa-arrow-up"></i></a>
                                <a title="<?= Yii::t('app', 'Delete') ?>" class="x delete-day text-danger" data-day="<?= $cnt - 1?>" href="#"><i class="fa fa-trash-o"></i></a>
                                <a title="<?= Yii::t('app', 'Edit') ?>" class="x edit-day text-muted" data-toggle="modal" data-target="#edit-day" data-id="<?= $day['id'] ?>" data-href="/days/u/<?= $day['id'] ?>"><i class="fa fa-edit"></i></a>
                                <a title="<?= Yii::t('app', '+Day after') ?>" class="xx text-muted" href="/nm?action=prepare-add-day&to=<?= $theProduct['id'] ?>&at=<?= $day['id'] ?>"><i class="fa fa-plus"></i></a>
                                <? } else { ?>
                                <a title="<?= Yii::t('app', 'Go top') ?>" class="xx text-muted" href="#divsortable"><i class="fa fa-arrow-up"></i></a>
                                <? } ?>
                            </div>
                            <span class="day-date"><?= Yii::$app->formatter->asDate($dayDate, 'php:j/n/Y D') ?></span>
                            <?= Html::a($day['name'], '/days/r/'.$day['id'], ['class'=>'day-name toggle-day-content', 'id'=>'day-name-'.$day['id'], 'data-id'=>$day['id']]) ?>
                            <em class="text-nowrap day-meals" id="day-meals-<?= $day['id'] ?>"><?= $day['meals'] ?></em>
                            <div class="day-content mt-20" id="day-content-<?= $day['id'] ?>" style="">
                                <p>
                                    <em><u>Guide</u></em>: <span class="day-guides" id="day-guides-<?= $day['id'] ?>"><?= $day['guides'] ?></span>
                                    <em><u>Transport</u></em>: <span class="day-transport" id="day-transport-<?= $day['id'] ?>"><?= $day['transport'] ?></span>
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
                                    <div class="day-image" id="day-image-<?= $day['id'] ?>"><?= $day['image'] ?></div>
                                    <div class="day-note" id="day-note-<?= $day['id'] ?>"><?= $day['note'] ?></div>
                                </div>
                                <? if (in_array(MY_ID, [$theProduct['created_by'], $theProduct['updated_by']])) { ?>
                                <div class="text-right text-muted" style="font-size:11px;">
                                    <?= Html::a('+Day after', DIR.'nm?action=prepare-add-day&to='.$theProduct['id'].'&at='.$day['id'])?>&nbsp;
                                    <?= Html::a('+Blank day after', DIR.'ct/rr/'.$theProduct['id'].'?action=day-add-blank-after&id='.$day['id']) ?>&nbsp;
                                    <?//= Html::a('+Blank day after', '#', ['class'=>'add-blank-day', 'data-day'=>$cnt, 'data-id'=>$day['id']]) ?>&nbsp;
                                    <?= Html::a('Copy down', DIR.'ct/rr/'.$theProduct['id'].'?action=day-copy-down&id='.$day['id']) ?>&nbsp;
                                    <?= Html::a('Edit', '#', ['class'=>'edit-day', 'data-toggle'=>'modal', 'data-target'=>'#edit-day', 'data-id'=>$day['id']]) ?>&nbsp;
                                    <?= Html::a('Delete', '#', ['class'=>'delete-day text-danger', 'data-day'=>$cnt - 1]) ?>&nbsp;
                                </div>
                                <? } ?>
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
$('.day-actions')
    .on('mouseover', function(){
        $(this).find('.x').show();
    })
    .on('mouseout', function(){
        $(this).find('.x').hide();
    })
$('a.toggle-day-contents').on('click', function(){
    if ($('.day-content:visible').length > 0){
        $('.day-content').hide();
    } else {
        $('.day-content').toggle();
    }
    return false;
});
// Toggle day content
$('#sortable').on('click', '.toggle-day-content', function(){
    var id = $(this).data('id');
    // alert(id);
    $('#day-content-' + id).toggle();
    return false;
});

// Add blank day
$('tbody#sortable').on('click', 'a.add-blank-day', function(){
    $('#divsortable').block({ 
        message: '<div><i class="fa fa-refresh fa-spin"></i> Processing</div>', 
        css: { border: '3px solid #a00', padding:20 } 
    });
    var day = $(this).data('day');
    var id = $(this).data('id');
    $.ajax({
        method: "POST",
        url: '?action=add-blank-day&xh',
        data: {at: day}
    })
    .done(function(data) {
        var tr = $('#sortable tr:eq(' + (day - 1) + ')');
        var tr2 = tr.clone(true, true).insertAfter(tr)
        tr2.attr('id', 'ngay_' + data.id)
        tr2.find('#day-content-' + id).attr('id', 'day-content-' + data.id);
        tr2.find('#day-actions-' + id).attr('id', 'day-actions-' + data.id);
        tr2.find('#day-name-' + id).attr('id', 'day-name-' + data.id).html('(no title)');
        tr2.find('#day-image-' + id).attr('id', 'day-image-' + data.id).html('');
        tr2.find('#day-meals-' + id).attr('id', 'day-meals-' + data.id).html('---');
        tr2.find('#day-guides-' + id).attr('id', 'day-guides-' + data.id).html('');
        tr2.find('#day-transport-' + id).attr('id', 'day-transport-' + data.id).html('');
        tr2.find('#day-body-' + id).attr('id', 'day-body-' + data.id).html('');
        tr2.find('#day-note-' + id).attr('id', 'day-note-' + data.id).html('');
        tr2.find('[data-id=' + id + ']').attr('data-id', data.id);

        $('tbody#sortable tr').each(function(i){
            $(this).find('td:eq(0)').html(i + 1);
            $(this).find('td:eq(1) .day-date').html(moment(start_date).add(i, 'days').format('D/M/Y ddd'));
            $(this).find('a.delete-day').attr('data-day', i);
        });

    })
    .fail(function(){
        alert( "Error saving data" );
    })
    .always(function(){
        $('#divsortable').unblock();
    })
    ;
    return false;
});

// Edit day
$('tbody#sortable').on('click', 'a.edit-day', function(){
    var id = $(this).data('id');
    $('#day-id').val(id);
    $('#day-name').val($('#day-name-' + id).html());
    $('#day-image').val($('#day-image-' + id).html());
    $('#day-meals').val($('#day-meals-' + id).html());
    $('#day-guides').val($('#day-guides-' + id).html());
    $('#day-transport').val($('#day-transport-' + id).html());
    $('#day-body').val($('#day-body-' + id).html());
    $('#day-note').val($('#day-note-' + id).html());
});
$(document).on("beforeSubmit", "#editDayForm", function () {
    var id = $('#day-id').val();
    $('#editDayForm').find('button[type="submit"]').html('Saving...').prop('disabled', true); 
    $.ajax({
        method: "POST",
        url: '?action=edit-day&xh',
        data: $(this).serialize()
    })
    .done(function() {
        $('#day-name-' + id).html($('#day-name').val());
        $('#day-image-' + id).html($('#day-image').val());
        $('#day-meals-' + id).html($('#day-meals').val());
        $('#day-guides-' + id).html($('#day-guides').val());
        $('#day-transport-' + id).html($('#day-transport').val());
        $('#day-body-' + id).html($('#day-body').val());
        $('#day-note-' + id).html($('#day-note').val());
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
$('tbody#sortable').on('click', 'a.delete-day', function(){
    if (!confirm('Are you sure you want to delete this?')) {
        return false;
    }
    var day = $(this).data('day');
    $('#divsortable').block({ 
        message: '<div><i class="fa fa-refresh fa-spin"></i> Processing</div>', 
        css: { border: '3px solid #a00', padding:20 } 
    }); 
    $.ajax({
        method: "POST",
        url: "?action=delete-day&xh",
        data: {day: day}
    })
    .done(function( msg ) {
        $('tbody#sortable tr:eq('+day+')').remove();
        $('tbody#sortable tr').each(function(i){
            $(this).find('td:eq(0)').html(i + 1);
            $(this).find('td:eq(1) .day-date').html(moment(start_date).add(i, 'days').format('D/M/Y ddd'));
            $(this).find('a.delete-day').attr('data-day', i);
        });
    })
    .fail(function( msg ) {
        alert('Error! Day could not be deleted.');
    })
    .always(function(){
        $('#divsortable').unblock();
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
            $('tbody#sortable tr').each(function(i){
                $(this).find('td:eq(0)').html(i + 1);
                $(this).find('td:eq(1) .day-date').html(moment(start_date).add(i, 'days').format('D/M/Y ddd'));
                $(this).find('a.delete-day').attr('data-day', i);
            });
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
TXT;
        // $this->registerJsFile('https://cdn.ckeditor.com/4.6.0/basic/ckeditor.js', ['depends'=>'yii\web\JqueryAsset']);
        $this->registerJsFile('https://code.jquery.com/ui/1.12.1/jquery-ui.js', ['depends'=>'yii\web\JqueryAsset']);
        $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js', ['depends'=>'yii\web\JqueryAsset']);
        $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js', ['depends'=>'yii\web\JqueryAsset']);
        $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/moment-with-locales.min.js', ['depends'=>'yii\web\JqueryAsset']);
        $this->registerJs($js);
        ?>
        <? } else { ?>
        <div class="panel-body">
            No days found. <?= Html::a('Add first day', '/nm?action=prepare-add-day-sample&to='.$theProgram['id'].'&at=0', ['title'=>'Add sample day at start']) ?>.
        </div>
        <? } ?>
    </div>
    <? } ?>
