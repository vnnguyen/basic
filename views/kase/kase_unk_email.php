<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\helpers\DateTimeHelper;

// include('_mails_inc.php');

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_layout'] = '-t';
?>
<div class="col-md-12">
    <div class="panel panel-default">
        
        <? if (empty($theMails)) { ?>
        <p>No mails found.</p>
        <? } else { ?>
        <div class="table-responsive">
            <table class="table table-inbox table-xxs">
                <!--
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
                -->
                <tbody>
                    <? foreach ($theMails as $mail) { ?>
                    <tr id="mail<?= $mail['id'] ?>">
                        <td class="table-inbox-star rowlink-skip">
                            <? if (random_int(0, 10) > 8) { ?><i class="fa fa-star text-pink"></i><? } ?>
                        </td>
                        <td class="table-inbox-image">
                            <img src="https://secure.gravatar.com/avatar/<?= md5($mail['from_email']) ?>.jpg?s=50&d=wavatar" class="img-circle img-xs" alt="">
                        </td>
                        <td class="table-inbox-message">
                            <? if (in_array(USER_ID, [1, 15081]) && $mail['case_id'] == 0) { ?>
                            <?= Html::a('<i class="fa fa-trash-o"></i>', '@web/mails/d/'.$mail['id'], ['style'=>'color:red', 'class'=>'mail_d', 'data-id'=>$mail['id']]) ?>
                            <? } ?>
                            <span class="table-inbox-subject"><?= Html::a($mail['subject'] == '' ? '( No subject )' : $mail['subject'], '@web/mails/r/'.$mail['id']) ?></span>
                            <span class="table-inbox-preview"><?= $mail['from_email'] ?> &rarr; <?= $mail['to_email'] ?></span>
                        </td>
                        <td class="table-inbox-attachment">
                            <? if ($mail['attachment_count'] > 0) { ?><i class="fa fa-paperclip"></i><? } ?>
                        </td>
                        <td class="table-inbox-name">
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

                        <td class="table-inbox-time" title="<?= $mail['sent_dt_text'] ?>">
                            <?
                            $created = DateTimeHelper::convert($mail['created_at'], 'j/n/Y H:i', 'UTC', Yii::$app->user->identity->timezone);
                            $created = str_replace([date('j/n/Y '), date('/Y')], ['', ''], $created);
                            echo $created;
                            ?>
                        </td>
                        <!--
                        <td class="text-nowrap" title="<?= $mail['sent_dt_text'] ?>"><?= DateTimeHelper::convert($mail['created_at'], 'd-m-Y H:i', 'UTC', Yii::$app->user->identity->timezone) ?></td>
                        <td>
                            <? if (in_array(USER_ID, [1, 15081]) && $mail['case_id'] == 0) { ?>
                            <?= Html::a('Del', '@web/mails/d/'.$mail['id'], ['style'=>'color:red', 'class'=>'mail_d', 'data-id'=>$mail['id']]) ?>
                            <? } ?>
                            <?= $mail['status'] == 'on' ? '' : '('. $mail['status']. ') ' ?>
                            <? if ($mail['attachment_count'] > 0) { ?><i class="fa fa-paperclip"></i><? } ?>
                            <?= Html::a($mail['subject'] == '' ? '( No subject )' : $mail['subject'], '@web/mails/r/'.$mail['id']) ?>
                        </td>
                        <td class="text-nowrap"><?= $mail['from_email'] ?></td>
                        <td class="text-nowrap"><?= $mail['to_email'] ?></td>
                        <td class="text-nowrap">
                        </td>
                        <td class="text-nowrap">
                            <a class="text-muted" title="<?=Yii::t('mn', 'Edit')?>" href="<?= DIR ?>mails/u/<?= $mail['id'] ?>"><i class="fa fa-edit"></i></a>
                            <a class="text-muted mail_d" data-id="<?= $mail['id'] ?>" title="<?=Yii::t('mn', 'Delete')?>" href="<?= DIR ?>mails/d/<?= $mail['id'] ?>"><i class="fa fa-trash-o"></i></a>
                        </td>
                        -->
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
        <div class="panel-body text-center">
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
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery-noty/2.4.1/packaged/jquery.noty.packaged.min.js', ['depends'=>'yii\web\JqueryAsset']);
$js = <<<'TXT'
$('.mail_d').on('click', function(){
    var id = $(this).data('id');
    var jqxhr = $.ajax('/mails/d/' + id)
    .done(function() {
        $('tr#mail' + id).remove();
        PNotify.removeAll();
        new PNotify({
            type: 'success',
            title: 'Success',
            text: 'Mail message #' + id + ' has been deleted.'
        });
    })
    .fail(function() {
        PNotify.removeAll();
        new PNotify({
            type: 'error',
            title: 'Failure',
            text: 'Mail message #' + id + ' could not be deleted.'
        });
    })
    .always(function() {
        // alert( "complete" );
    });
    return false;
});
TXT;

$this->registerJs($js);