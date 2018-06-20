<?
use yii\helpers\Html;
Yii::$app->params['page_layout'] = '-t';
Yii::$app->params['page_title'] = 'Lịch đón tiếp khách tại vp, tuần từ '.$thisWeek.' ('.count($theTasks).' lượt)';
Yii::$app->params['page_breadcrumbs'] = [
    ['Customers', '@web/customers'],
    ['Customer care tasks', '@web/customers/tasks'],
    ['Customer receiving'],
];

$timeList = [
    't'=>'Cụ thể',
    'm'=>'Sáng',
    'a'=>'Chiều',
    'e'=>'Chưa biết',
];

$purpose = ['#c'];
$purposeList = [
    't'=>'Thu/Trả lại tiền',
    's'=>'Tổ chức SN',
    'q'=>'Tặng quà SN',
    'c'=>'Tặng quà khách cũ',
];
$table = '#1';
$tableList = [
    ''=>'Không có',
    '1'=>'Bàn 1',
    '2'=>'Bàn 2',
    '3'=>'Bàn 3',
    '4'=>'Bàn 4',
];

$atList = [
    'all'=>'All locations',
    'hanoi'=>'Hanoi office',
    'saigon'=>'Saigon office',
    'luangprabang'=>'Luang Prabang office',
];

?>
<style>
.table.table-narrow>tbody>tr>td, .table.table-narrow>tbody>tr>th, .table.table-narrow>tfoot>tr>td, .table.table-narrow>tfoot>tr>th, .table.table-narrow>thead>tr>td, .table.table-narrow>thead>tr>th {padding-left:8px; padding-right:8px;}
</style>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <form action="" class="form-inline">
            <strong>Customer reception
            (<?= count($theTasks) ?>)</strong>
            |
            Week from
            <input type="text" id="date" style="width:100px; display:inline-block;" class="form-control" name="date" value="<?= $thisWeek ?>">
            at
            <?= Html::dropdownList('at', $at, $atList, ['class'=>'form-control', 'style'=>'width:140px; display:inline-block;']) ?>
            <?= Html::submitButton('Go', ['class'=>'btn btn-default']) ?>
            |
            <?= Html::a('Previous week', '/customers/tasks-ac?at='.$at.'&date='.$prevWeek) ?>
            |
            <?= Html::a('Next week', '/customers/tasks-ac?at='.$at.'&date='.$nextWeek) ?>
            |
            <?= Html::a('This week', '/customers/tasks-ac?at='.$at) ?>
            |
            <?= Html::a('On Google Drive', 'https://docs.google.com/spreadsheets/d/1QK7o5KYOHFkb1WYVVB9fm7WtTYBmsb2oDPyZ1W1mNFU/edit#gid=342913708', ['class'=>'text-pink', 'target'=>'_blank']) ?>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-narrow">
                <thead>
                    <tr>
                        <th>Ngày đón tiếp</th>
                        <th>Giờ</th>
                        <th>Tour code</th>
                        <th>Pax</th>
                        <th>Bán hàng</th>
                        <th>CSKH</th>
                        <th title="Vị trí">VT</th>
                        <th title="Mục đích">MĐ</th>
                        <th>Note</th>
                        <th>Guide</th>
                        <th>Nội dung ngày tour</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    $cnt = 0;
                    $ymdNow = '';
                    foreach ($theTasks as $task) {
                        $ymd = substr($task['due_dt'], 0, 10);
                        $task['due_dt'] = date('H:i', strtotime($task['due_dt']));
                    ?>
                    <tr class="qhkh <? foreach ($task['tour']['cskh'] as $user) echo 'qhkh', $user['id'], ' '; ?>">
                        <td class="<?= $ymd == date('Y-m-d') ? 'text-bold' : '' ?> text-right text-nowrap <?= strtotime('now') > strtotime($task['due_dt']) ? 'text-muted' : '' ?>">
                            <?
                            if ($ymdNow != $ymd) {
                                $cnt = 1;
                                $ymdNow = $ymd;
                                echo Yii::$app->formatter->asTime($ymd, 'php:j/n l');
                            } else {
                                $cnt ++;
                                echo '<span class="text-muted">', $cnt, '</span>';
                            }
                            ?>
                        </td>
                        <td class="text-nowrap">
                            <a href="#" id="r_time_<?= $task['id'] ?>" class="task_time" data-id="<?= $task['id'] ?>" title="Sửa" data-toggle="modal" data-target="#taskModal">
                                <?
                                if ($task['due_dt'] == '11:59') {
                                    echo 'Sáng';
                                } elseif ($task['due_dt'] == '17:59') {
                                    echo 'Chiều';
                                } elseif ($task['due_dt'] == '23:59') {
                                    echo 'TBA';
                                } else {
                                    echo $task['due_dt'];
                                }
                                ?>
                            </a>
                        </td>
                        <td>
                            <?= Html::a('<i class="fa fa-check-circle"></i>', '@web/tasks/u/'.$task['id'], ['title'=>$task['description'], 'class'=>$task['status'] == 'on' ? 'text-muted' : 'text-success']) ?>
                            <?= Html::a($task['tour']['code'].' - '.$task['tour']['name'], '@web/tours/r/'.$task['tour']['id'], ['rel'=>'external'])?>
<?
$oldTours = false;
$birthDays = [];
$startDate = date('Y-m-d', strtotime($task['tour']['product']['day_from']));
$endDate = date('Y-m-d', strtotime('+'.($task['tour']['product']['day_count'] - 1).' day', strtotime($task['tour']['product']['day_from'])));
if (!empty($task['tour']['product']['pax'])) {
    foreach ($task['tour']['product']['pax'] as $p) {
        $bd = strtotime(substr($endDate, 0, 4).substr($p['pp_birthdate'], 4));
        if (strtotime($startDate) <= $bd && $bd <= strtotime($endDate)) {
            $birthDays[] = '<div><i class="fa fa-birthday-cake text-warning"></i> '.date('j/n', strtotime($p['pp_birthdate'])).' '.$p['name'].'</div>';
        }
    }
} else {
    foreach ($task['tour']['product']['bookings'] as $booking) {
        foreach ($booking['people'] as $p) {
            if (count($p['bookings']) > 1) {
                $oldTours = true;
            }
            if (!in_array(0, [$p['bday'], $p['bmonth']])) {
                $bd = date_create_from_format('j/n/Y', $p['bday'].'/'.$p['bmonth'].'/'.substr($endDate, 0, 4));
                $bd = strtotime($bd->format('Y-m-d'));
                if (strtotime($startDate) <= $bd && $bd <= strtotime($endDate)) {
                    $birthDays[] = '<div><i class="fa fa-birthday-cake text-warning"></i> '.$p['bday'].'/'.$p['bmonth'].' '.$p['name'].'</div>';
                }
            }
        }
    }
}
if (!empty($birthDays)) {
    echo implode(', ', $birthDays);
}
if ($oldTours) {
    echo '<div><i class="fa fa-refresh text-info"></i> returning</div>';
}
?>
                        </td>
                        <td class="text-center" title="<?= $task['tour']['product']['day_count'] ?>d <?= date('j/n', strtotime($task['tour']['product']['day_from'])) ?>">
                            <?
$paxCount = 0;
foreach ($task['tour']['product']['bookings'] as $booking) {
    $paxCount += $booking['pax'];
}
echo $paxCount;
?>
                        </td>
                        <td class="text-nowrap"><?
                        foreach ($task['tour']['product']['bookings'] as $booking) {
                            ?><div class=""><?= $booking['createdBy']['name'] ?></div><?
                        }
                        ?>
                        </td>
                        <td class="text-nowrap"><?
                            foreach ($task['tour']['cskh'] as $user) {
                            ?><div><?= Html::a($user['name'], '@web/users/r/'.$user['id'], ['onclick'=>'$("tr.qhkh").removeClass("info"); $("tr.qhkh'.$user['id'].'").toggleClass("info");return false;']) ?></div><?
                            }
                        ?></td>
                        <td id="r_table_<?= $task['id'] ?>">
                            <?
                            for ($i = 1; $i <= 4; $i ++) {
                                if (strpos($task['description'], ' #'.$i)) {
                                    echo $i;
                                    $task['description'] = str_replace(' #'.$i, '', $task['description']);
                                }
                            }
                            ?>
                        </td>
                        <td class="text-nowrap" id="r_icons_<?= $task['id'] ?>">
                            <?
                            $showicons = [
                                ' #t'=>'<i title="Thu/trả lại tiền" class="fa fa-fw fa-dollar text-success"></i>',
                                ' #s'=>'<i title="Tổ chức sinh nhật" class="fa fa-fw fa-birthday-cake text-warning"></i>',
                                ' #q'=>'<i title="Tặng quà sinh nhật" class="fa fa-fw fa-gift text-pink"></i>',
                                ' #c'=>'<i title="Tặng quà khách cũ" class="fa fa-fw fa-user text-danger"></i>',
                            ];
                            foreach ([' #t', ' #s', ' #q', ' #c'] as $check) {
                                if (strpos($task['description'], $check)) {
                                    echo $showicons[$check];
                                    $task['description'] = str_replace($check, '', $task['description']);
                                }
                            }
                            ?>
                        </td>
                        <td id="r_note_<?= $task['id'] ?>"><?= Html::encode(substr($task['description'], 2)) ?></td>
                        <td><?
                            foreach ($task['tour']['product']['guides'] as $tg) {
                                if (strtotime(substr($tg['use_from_dt'], 0, 10)) <= strtotime($ymd) && strtotime(substr($tg['use_until_dt'], 0, 10)) >= strtotime($ymd)) {
                            ?><div><?= $tg['guide_name'] ?></div><?
                                }
                            }
                        ?></td>
                        <td>
                            <?
                            foreach ($task['tour']['product']['days'] as $td) {
                            ?><span title="<?= Html::encode($td['name']) ?>"><?= mb_strimwidth($td['name'], 0, 40, '&hellip;') ?></span><?
                            }
                            ?>
                        </td>
                    </tr>
                    <?
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="taskModal" tabindex="-1" data-backdrop="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title"><?= Yii::t('app','Sửa thông tin') ?></h6>
                </div>
                <div class="modal-body">
                    <form id="taskForm" method="post" class="">
                        <?= Html::hiddenInput('id', 0) ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label"><?= Yii::t('app', 'Thời gian') ?></label>
                                    <?= Html::dropdownList('time_fuzzy', '', $timeList, ['class'=>'form-control']) ?>
                                </div>
                            </div>
                            <div class="col-md-3" id="time_detail">
                                <div class="form-group">
                                    <label class=" control-label"><?= Yii::t('app', 'Giờ') ?></label>
                                    <?= Html::textInput('time', '09:00', ['class'=>'form-control']) ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label"><?= Yii::t('app', 'Số phút') ?></label>
                                    <?= Html::textInput('mins', '60', ['class'=>'form-control']) ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label"><?= Yii::t('app', 'Vị trí') ?></label>
                                    <?= Html::dropdownList('table', '', $tableList, ['class'=>'form-control']) ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?= Yii::t('app', 'Mục đích đặc biệt') ?></label>
                            <?= Html::checkboxList('purpose', [], $purposeList) ?>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?= Yii::t('app', 'Ghi chú') ?></label>
                            <?= Html::textInput('note', '', ['class'=>'form-control']) ?>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Ghi các thay đổi</button>
                            hoặc <a href="javascript:;" data-dismiss="modal">Thôi</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.datepicker>div {display:block;}
</style>
<?
$js = <<<'TXT'
// $('a.task_time').editable();
$('#date').datepicker({
    format: "yyyy-mm-dd",
    weekStart: 1,
    todayBtn: "linked",
    clearBtn: true,
    language: "{LANG}",
    autoclose: true
});
$('#date').on('change', function(){
    var loc = $('select[name="at"]').val();
    var val = $(this).val();
    if (val != '') {
        location.href = '/customers/tasks-ac?at=' + loc + '&date=' + val;
    }
});

$('#taskForm')
    .formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'fa fa-check',
            invalid: 'fa fa-times',
            validating: 'fa fa-refresh'
        },
        fields: {
            time_fuzzy: {
                validators: {
                    notEmpty: {
                        message: 'The time is required'
                    }
                }
            },
            time: {
                validators: {
                    notEmpty: {
                        message: 'The time is required'
                    },
                    regexp: {
                        regexp: /^(?:0?\d|1[012]|2[01234]):[0-5]\d$/,
                        message: 'Must be of hh:mm format'
                    }
                }
            },
            'mins': {
                validators: {
                    notEmpty: {
                        message: 'Required'
                    },
                    regexp: {
                        regexp: /^\d+$/,
                        message: 'Must be a number'
                    }
                }
            },
        }
    })
    .on('success.form.fv', function(e) {
        // Save the form data via an Ajax request
        e.preventDefault();

        var $form = $(e.target),
            id    = $form.find('[name="id"]').val();

        // The url and method might be different in your application
        $.ajax({
            url: '/customers/ajax?action=update_task_ac&task_id=' + id,
            method: 'POST',
            data: $form.serialize()
        }).done(function(response) {
            // Update the cell data
            $('#r_time_' + id).html(response.time).end()
            $('#r_table_' + id).html(response.table).end()
            $('#r_icons_' + id).html(response.icons).end()
            $('#r_note_' + id).html(response.note).end()
        }).fail(function(){
            alert('Fail to save data!');
        }).always(function(){
            $('#taskModal').modal('hide');
        });
    });
$('a.task_time').on('click', function(){
    // Get the record's ID via attribute
    var id = $(this).attr('data-id');

    $.ajax({
        url: '/customers/ajax?action=load_task_ac&task_id=' + id,
        method: 'GET'
    }).done(function(response) {
        // Populate the form fields with the data returned from server
        $('#taskForm')
            .find('[name="id"]').val(id).end()
            .find('[name="time_fuzzy"]').val(response.time_fuzzy).end()
            .find('[name="time"]').val(response.time).end()
            .find('[name="mins"]').val(response.mins).end()
            .find('[name="table"]').val(response.table).end()
            .find('input:checkbox:eq(0)').prop('checked', $.inArray('t', response.icons) == -1 ? false : true).end()
            .find('input:checkbox:eq(1)').prop('checked', $.inArray('s', response.icons) == -1 ? false : true).end()
            .find('input:checkbox:eq(2)').prop('checked', $.inArray('q', response.icons) == -1 ? false : true).end()
            .find('input:checkbox:eq(3)').prop('checked', $.inArray('c', response.icons) == -1 ? false : true).end()
            .find('[name="note"]').val(response.note).end();
        // Hide time if fuzzy
        if (response.time_fuzzy == 't') {
            $('#time_detail').addClass('col-md-3').css('display', 'block');
        } else {
            $('#time_detail').removeClass('col-md-3').css('display', 'none');
        }
        // Show the dialog
        $('#taskModal').modal('show').find('#taskForm').formValidation('resetForm');
    }).fail(function(){
        alert('Error!');
    });
});
// Switch time
$('[name="time_fuzzy"]').on('change', function(){
    var val = $(this).val();
    if (val == 't') {
        $('#time_detail').addClass('col-md-3').css('display', 'block');
    } else {
        $('#time_detail').removeClass('col-md-3').css('display', 'none');
    }
});

// //Disable cut copy paste
// $('body').bind('cut copy paste', function (e) {
//     e.preventDefault();
// });

// //Disable mouse right click
// $("body").on("contextmenu",function(e){
//     return false;
// });
TXT;

$this->registerJsFile('/assets/formvalidation_0.8.1/js/formValidation.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('/assets/formvalidation_0.8.1/js/framework/bootstrap.min.js', ['depends'=>'yii\web\JqueryAsset']);

$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js', ['depends'=>'app\assets\MainAsset']);

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/locales/bootstrap-datepicker.'.Yii::$app->language.'.min.js', ['depends'=>'yii\web\JqueryAsset']);

$this->registerJs(str_replace([
    '{LANG}', '{AT}'
    ], [
    Yii::$app->language, $at
    ], $js));