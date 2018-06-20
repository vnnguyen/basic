<?php
use yii\helpers\Html;

include('_tm_inc.php');
Yii::$app->params['page_title'] = $theProgram['title'];

$dayIdList = explode(',', $theProgram['day_ids']);

?>
<style>
td, th {vertical-align:top!important}
</style>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Program info</h6>
        </div>
        <table class="table table-condensed table-bordered">
            <tbody>
                <tr>
                    <th>Update</th>
                    <td><?= $theProgram['updatedBy']['name'] ?> @<?= Yii::$app->formatter->asDate($theProgram['updated_at'], 'php:j/n/Y (l) H:i') ?></td>
                </tr>
                <tr>
                    <th>Miêu tả</th>
                    <td><?= $theProgram['intro'] ?></td>
                </tr>
                <tr>
                    <th>Tags</th>
                    <td><?= $theProgram['tags'] ?></td>
                </tr>
                <tr><th>Note</th><td><?=$theProgram['id']?></td></tr>
            </tbody>
        </table>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Detailed itinerary</h6>
        </div>
        <? if (!empty($theDays)) { ?>
        <div class="table-responsive" id="divsortable">
            <table class="table table-striped table-condensed">
                <thead>
                    <tr>
                        <th width="20"><i class="text-muted fa fa-arrows-v"></i></th>
                        <th class="no-padding-left no-padding-right">Activity</th>
                        <th width="20"><?= Html::a('+', '/nm?action=prepare-add-day-sample&to='.$theProgram['id'].'&at=0', ['title'=>'Add sample day at start']) ?></th>
                    </tr>
                </thead>
                <tbody id="sortable">
                    <?
                    $cnt = 0;
                    foreach ($dayIdList as $dayId) {
                        if (isset($theDays[$dayId])) {
                            $cnt ++;
                            $day = $theDays[$dayId]
                    ?>
                    <tr id="trday_<?= $day['id'] ?>">
                        <td title="Drag to sort" class="handle cursor-move text-muted text-center" width="20">
                            <?= $cnt ?>
                        </td>
                        <td class="no-padding-left no-padding-right">
                            <?= Html::a($day['title'], '/nm/r/'.$day['id'], ['class'=>'toggle-day', 'data-day'=>$day['id']]) ?>
                            <div class="small daybody mt-20" id="day-body-<?= $day['id'] ?>" style="display:none">
                                <p>&rarr; <?= Html::a('View day', '/nm/r/'.$day['id'], ['target'=>'_blank']) ?></p>
                                <?= $day['body'] ?>
                            </div>    
                        </td>
                        <td class="text-nowrap" width="20">
                            <?= Html::a('+', '/nm?action=prepare-add-day-sample&to='.$theProgram['id'].'&at='.$cnt, ['title'=>'Add a day after this day']) ?>
                            <?= Html::a('&mdash;', '?action=remove-day-sample&at='.$cnt, ['title'=>'Remove this day', 'class'=>'text-danger']) ?>
                        </td>
                    </tr>
                    <?
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?
        $js = <<<'TXT'
$('.toggle-day').on('click', function(){
    var day = $(this).data('day');
    $('#day-body-' + day).toggle();
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
// var el = document.getElementById('sortable');
// var sortable = Sortable.create(el, {
//     handle: '.text-muted.text-center',
//     animation: 150,
//     onEnd: function (/**Event*/evt) {
//         $.ajax({
//             method: "POST",
//             url: "?action=sort-day",
//             data: { old: evt.oldIndex, new: evt.newIndex }
//         })
//         .done(function( msg ) {
//             // alert( "Data Saved: " + msg );
//             $('tbody#sortable tr').each(function(i){
//                 $(this).find('td:eq(0)').html(i + 1);
//             });
//         });
//     },
// });
TXT;
        $this->registerJsFile('https://code.jquery.com/ui/1.12.1/jquery-ui.js', ['depends'=>'yii\web\JqueryAsset']);
        $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js', ['depends'=>'yii\web\JqueryAsset']);
        $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js', ['depends'=>'yii\web\JqueryAsset']);
        $this->registerJs($js);
        ?>
        <? } else { ?>
        <div class="panel-body">
            No days found. <?= Html::a('Add first day', '/nm?action=prepare-add-day-sample&to='.$theProgram['id'].'&at=0', ['title'=>'Add sample day at start']) ?>.
        </div>
        <? } ?>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Feature image</h6>
        </div>
        <div class="panel-body">
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Maps</h6>
        </div>
        <div class="panel-body">
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Video/Photo gallery</h6>
        </div>
        <div class="panel-body">
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Price table</h6>
        </div>
        <div class="panel-body">
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Other notes</h6>
        </div>
        <div class="panel-body">
        </div>
    </div>

</div>
