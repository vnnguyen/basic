<?php

use yii\helpers\Html;

$timeList = [
    't'=>Yii::t('x', 'Exact time'),
    'm'=>Yii::t('x', 'Morning'),
    'a'=>Yii::t('x', 'Afternoon'),
    'e'=>Yii::t('x', 'Anytime'),
];

$assigneeList = [];
foreach ($thePeople as $user) {
    $assigneeList[$user['id']] = $user['nickname'].' '.$user['email'];
}

?>
<style>
.daterangepicker.single {z-index:999999;}
</style>
    <div class="modal fade" id="taskModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h6 class="modal-title"><?= Yii::t('x','New/Edit task') ?></h6>
                </div>
                <div class="modal-body">
                    <form id="taskForm" method="post" class="">
                        <?= Html::hiddenInput('id', 0) ?>
                        <p class="text-warning"><i class="fa fa-exclamation"></i> <?= Yii::t('x', 'Task status will become "Not completed".') ?></p>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label class="control-label"><?= Yii::t('x', 'Task description') ?></label>
                                    <?= Html::textInput('description', '', ['class'=>'form-control']) ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label"><?= Yii::t('x', 'Priority?') ?></label>
                                    <?= Html::dropdownList('is_priority', '', ['no'=>Yii::t('x', 'No'), 'yes'=>Yii::t('x', 'Yes')], ['class'=>'form-control']) ?>
                                </div>                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label"><?= Yii::t('x', 'Date') ?></label>
                                    <?= Html::textInput('date', '', ['class'=>'form-control datepicker']) ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label"><?= Yii::t('x', 'When') ?></label>
                                    <?= Html::dropdownList('time_fuzzy', '', $timeList, ['class'=>'form-control']) ?>
                                </div>
                            </div>
                            <div class="col-md-3" id="time_detail">
                                <div class="form-group">
                                    <label class=" control-label"><?= Yii::t('x', 'Time') ?></label>
                                    <?= Html::textInput('time', '09:00', ['class'=>'form-control']) ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label"><?= Yii::t('x', 'Duration (mins)') ?></label>
                                    <?= Html::textInput('mins', '60', ['class'=>'form-control']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label"><?= Yii::t('x', 'Assign task to') ?></label>
                            <?= Html::dropdownList('who', [], $assigneeList, ['class'=>'select2 form-control', 'style'=>'width:100%', 'multiple'=>'multiple']) ?>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><?= Yii::t('x', 'Save changes') ?></button>
                            <?= Yii::t('x', 'or') ?>
                            <a href="javascript:;" data-dismiss="modal"><?= Yii::t('x', 'Cancel') ?></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
    $js = <<<'TXT'
$('.select2').select2();
$('#taskForm').formValidation({
    framework: 'bootstrap',
    icon: {
        valid: 'fa fa-check',
        invalid: 'fa fa-times',
        validating: 'fa fa-refresh'
    },
    fields: {
        description: {
            validators: {
                notEmpty: {
                    message: 'Required'
                },
            }
        },
        date: {
            validators: {
                notEmpty: {
                    message: 'Required'
                },
                date: {
                    format: 'YYYY-MM-DD',
                    message: 'yyyy-mm-dd'
                }
            }
        },
        time_fuzzy: {
            validators: {
                notEmpty: {
                    message: 'Required'
                }
            }
        },
        time: {
            validators: {
                notEmpty: {
                    message: 'Required'
                },
                regexp: {
                    regexp: /^(?:0?\d|1\d|2[0123]):[0-5]\d$/,
                    message: 'hh:mm'
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
    e.preventDefault();

    var $form = $(e.target),
        id    = $form.find('[name="id"]').val(),
        rtype = $('a.task-add').data('rtype'),
        rid = $('a.task-add').data('rid'),
        rname = $('a.task-add').data('rname')
        ;

    // The url and method might be different in your application
    $.ajax({
        url: '/tasks/ajax?xh&action=update_task&rtype=' + rtype + '&rid=' + rid + '&rname=&task_id=' + id,
        method: 'POST',
        data: $form.serialize()
    }).done(function(response) {
        if (id != 0) {
            // Update the data
            $('#div-task-' + id)
            .removeClass('task-overdue')
            .addClass(response.task_overdue)
            .find('.task-date').html(response.task_date + response.task_today).end()
            .find('.task-time').html(response.task_time).end()
            .find('.task-priority').html(response.is_priority == 'yes' ? '<i class="fa fa-star text-danger"></i>' : '').end()
            .find('.task-description').html(response.description).end()
            .find('.task-assignees').html(response.assignees).end()
            .find('i#icon-' + id).removeClass('fa-check-square-o').addClass('fa-square-o').end()
            ;
        } else {
            $('<div id="div-task-' + response.task_id + '" class="task-list-item' + response.task_overdue + ' task"><i id="icon-' + response.task_id + '" data-task_id="' + response.task_id + '" class="cursor-pointer task-check fa fa-square-o"></i> <span class="task-date"></span> <span class="task-time"></span> <span class="task-priority"></span> <span class=""><a title="Edit task" data-id="'+ response.task_id +'" href="/tasks/u/' + response.task_id + '" class="task-description"></a></span> <span class="task-assignees"></span></div></div></div>').prependTo('#task-list')
            .find('.task-date').html(response.task_date + response.task_today).end()
            .find('.task-time').html(response.task_time).end()
            .find('.task-priority').html(response.is_priority == 'yes' ? '<i class="fa fa-star text-danger"></i>' : '').end()
            .find('.task-description').html(response.description).end()
            .find('.task-assignees').html(response.assignees).end()
            ;
        }
        $('#taskModal').modal('hide');
    }).fail(function(){
        alert('Fail to save data!');
    }).always(function(){
        $('#taskForm').formValidation('resetForm');
    });
});

$('a.task-add').on('click', function(e){
    var id = 0;
    var rtype = $(this).data('rtype');
    var rid = $(this).data('rid');
    $('#taskForm')
        .find('[name="id"]').val(0).end()
        // .find('a#edit_link').attr('href', '/tasks/c?rtype=' + rtype + '&rid=' + rid).end()
        .find('[name="description"]').val('').end()
        .find('[name="is_priority"]').val('no').end()
        .find('[name="date"]').val('{NOW}').end()
        .find('[name="time"]').val('09:00').end()
        .find('[name="mins"]').val('10').end()
        .find('[name="time_fuzzy"]').val('e').end()
        ;
    $("select[name='who[]']").val('');
    $("select[name='who[]'] option[value='{USER_ID}']").prop("selected", "selected").trigger('change');
    $('#taskModal').modal('show').find('#taskForm').formValidation('resetForm');
    return false;
});

$('#task-list').on('click', 'a.task-description', function(e){
    var id = $(this).attr('data-id');

    $.ajax({
        url: '/tasks/ajax?action=load_task&task_id=' + id,
        method: 'GET'
    }).done(function(response) {
        // Populate the form fields with the data returned from server
        $('#taskForm')
            .find('[name="id"]').val(id).end()
            // .find('a#edit_link').attr('href', '/tasks/u/' + id).end()
            .find('[name="description"]').val(response.description).end()
            .find('[name="is_priority"]').val(response.is_priority).end()
            .find('[name="date"]').val(response.date).end()
            .find('[name="time"]').val(response.time).end()
            .find('[name="mins"]').val(response.mins).end()
            .find('[name="time_fuzzy"]').val(response.time_fuzzy).end()
            ;
        $("select[name='who[]']").val('');
        $.each(response.who, function(i,e){
            $("select[name='who[]'] option[value='" + e + "']").prop("selected", "selected").trigger('change');
        });
        // Hide time if fuzzy
        if (response.time_fuzzy == 't') {
            $('#time_detail').addClass('col-md-3').css('display', 'block');
        } else {
            $('#time_detail').removeClass('col-md-3').css('display', 'none');
        }
        // Show the dialog
        $('#taskModal').modal('show').find('#taskForm').formValidation('resetForm');
    }).fail(function(){
        alert('Error loading task!');
    });
    return false;
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

$('input.datepicker').daterangepicker({
    locale: {
        firstDay: 1,
        format: 'YYYY-MM-DD'
    },
    singleDatePicker: true,
    showDropdowns: true
});


TXT;

    $js = str_replace(['{NOW}', '{USER_ID}'], [substr(NOW, 0, 10), USER_ID], $js);
    $this->registerJs($js);

    $this->registerJsFile('/assets/formvalidation_0.8.1/js/formValidation.min.js', ['depends'=>'yii\web\JqueryAsset']);
    $this->registerJsFile('/assets/formvalidation_0.8.1/js/framework/bootstrap.min.js', ['depends'=>'yii\web\JqueryAsset']);

    $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/moment.min.js', ['depends'=>'yii\web\JqueryAsset']);
    $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24/daterangepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
