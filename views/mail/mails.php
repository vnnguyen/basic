<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\helpers\DateTimeHelper;

include('_mails_inc.php');

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_layout'] = '-t';
?>
<div id="noti" style="z-index:100000; display:none; position:fixed; top:10px; left: 10px; background-color:yellow;"></div>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <form method="get" action="" class="form-inline">
                <?= Html::textInput('from_email', $getFromEmail, ['class'=>'form-control', 'placeholder'=>'From email']) ?>
                <?= Html::textInput('to_email', $getToEmail, ['class'=>'form-control', 'placeholder'=>'To email']) ?>
                <?= Html::textInput('subject', $subject, ['class'=>'form-control', 'placeholder'=>'Subject']) ?>
                <?= Html::dropdownList('attachments', $getAttachments, ['all'=>'Attachments?', 'yes'=>'With attachments', 'no'=>'No attachments'], ['class'=>'form-control']) ?>
                <?= Html::dropdownList('case_id', $getCaseId, ['all'=>'Case?', 'yes'=>'In a case', 'no'=>'Not in a case'], ['class'=>'form-control']) ?>
                <?= Html::dropdownList('tags', $getTags, [''=>'Sharing?', 'op'=>'Shared', 'nop'=>'Not shared'], ['class'=>'form-control']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
                <?= Html::a('Reset', '@web/mails') ?>
            </form>
        </div>
        <? if (empty($theMails)) { ?>
        <p>No mails found.</p>
        <? } else { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-condensed table-striped">
                <thead>
                    <tr>
                        <th width="100">Saved</th>
                        <th width="">Subject</th>
                        <th width="100">Form</th>
                        <th width="100">To</th>
                        <th width="100">Case</th>
                        <th width="30"></th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theMails as $mail) { ?>
                    <tr id="mail<?= $mail['id'] ?>">
                        <td class="text-nowrap" title="<?= $mail['sent_dt_text'] ?>"><?= DateTimeHelper::convert($mail['created_at'], 'd-m-Y H:i', 'UTC', Yii::$app->user->identity->timezone) ?></td>
                        <td>
                            <? if (1 == USER_ID && $mail['case_id'] == 0) { ?>
                            <?= Html::a('Del', '@web/mails/d/'.$mail['id'], ['style'=>'color:red', 'class'=>'mail_d', 'data-id'=>$mail['id']]) ?>
                            <? } ?>
                            <?= $mail['status'] == 'on' ? '' : '('. $mail['status']. ') ' ?>
                            <? if ($mail['attachment_count'] > 0) { ?><i class="fa fa-paperclip"></i><? } ?>
                            <?= Html::a($mail['subject'] == '' ? '( No subject )' : $mail['subject'], '@web/mails/r/'.$mail['id']) ?>
                        </td>
                        <td class="text-nowrap"><?= $mail['from_email'] ?></td>
                        <td class="text-nowrap"><?= $mail['to_email'] ?></td>
                        <td class="text-nowrap">
                            <? if (isset($mail['case'])) { ?>
                            <?= Html::a($mail['case']['name'], '@web/cases/r/'.$mail['case']['id']) ?>
                            <? if ($mail['case']['status'] == 'closed') { ?><i class="fa fa-lock text-muted"></i><? } ?>
                            <? if ($mail['case']['deal_status'] == 'won') { ?>
                            <i class="fa fa-dollar text-success"></i>
                            <? } else { ?>
                                <? if ($mail['case']['status'] == 'closed') { ?>
                            <i class="fa fa-dollar text-danger"></i>
                                <? } ?>
                            <? } ?>
                            <span class="text-muted"><?= $mail['case']['owner']['name'] ?></span>
                            <? } ?>
                        </td>
                        <td class="text-nowrap">
                            <a class="text-muted" title="<?=Yii::t('mn', 'Edit')?>" href="<?= DIR ?>mails/u/<?= $mail['id'] ?>"><i class="fa fa-edit"></i></a>
                            <a class="text-muted mail_d" data-id="<?= $mail['id'] ?>" title="<?=Yii::t('mn', 'Delete')?>" href="<?= DIR ?>mails/d/<?= $mail['id'] ?>"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
        <div class="text-center">
            <?=LinkPager::widget(array(
            'pagination' => $pages,
            'firstPageLabel' => '<<',
            'prevPageLabel' => '<',
            'nextPageLabel' => '>',
            'lastPageLabel' => '>>',
            ));?>
        </div>
        <? } ?>
    </div>
</div>
<?
$js = <<<'TXT'
$('.mail_d').on('click', function(){
    var id = $(this).data('id');
    var jqxhr = $.ajax('/mails/d/' + id)
    .done(function() {
        $('tr#mail' + id).remove();
        $('#noti').show().html('Mail message #' + id + ' has been deleted.').fadeOut(2000);
        // new PNotify({
        //     title: 'Success',
        //     text: 'Mail message has been deleted.',
        //     //icon: 'fa fa-info-circle',
        //     type: 'success'
        // });
    })
    .fail(function() {
        $('#noti').show().html('Mail message #' + id + ' could not be deleted.').fadeOut(2000);
        // new PNotify({
        //     title: 'Error',
        //     text: 'Could not delete email message.',
        //     //icon: 'fa fa-info-circle',
        //     type: 'error'
        // });
    })
    .always(function() {
        // alert( "complete" );
    });
    return false;
});
TXT;

$this->registerJs($js);