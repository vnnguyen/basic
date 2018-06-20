<?php
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\HtmlPurifier;
use app\helpers\DateTimeHelper;

if (substr($note['title'], 0, 11) == $theTour['op_code'].' - ' && USER_ID == 1) {
    /*$title = substr($note['title'], 11);
    echo $title;
    $sql = 'UPDATE at_messages SET title = :title WHERE id=:id LIMIT 1';
    Yii::$app->db->createCommand($sql, [
        ':title'=>$title,
        ':id'=>$note['id'],
        ])->execute();
    */
}

?>
<!-- MESSAGE BEGIN -->
        <li class="note-list-item clearfix" data-id="<?= $note['id'] ?>">
            <a name="anchor-note-<?= $note['id'] ?>"></a>
            <div class="note-avatar">
            <?= Html::a(Html::img($userAvatar, ['class'=>'img-circle note-author-avatar']), '@web/users/r/'.$note['from']['id']) ?>
            </div>
            <?
            if ($note['n_id'] != 0) {
                $title = Yii::t('op', 'replied');
            } else {
                $title = $note['title'] == '' ? Yii::t('op', '( no title )') : $note['title'];
            }
            // #client hashtag
            $clientHash = false;
            if (strpos($title, '#client') !== false) {
                $title = str_replace('#client', '', $title);
                $clientHash = true;
            }
            $clientReservStatusHash = false;
            if (strpos($title, '#reservation-status') !== false) {
                $title = str_replace('#reservation-status', '', $title);
                $clientReservStatusHash = true;
            }
            $body = $note['body'];

            $body = str_replace(['width:', 'height:', 'font-size:', '<table', '</table>', '<p>&nbsp;</p>'], ['x:', 'x:', 'x:', '<div class="table-responsive"><table class="table table-condensed table-bordered" ', '</table></div>', ''], $body);
            $body = HtmlPurifier::process($body);
            ?>
            <div class="note-content">
                <h5 class="note-heading">
                    <? if ($note['via'] == 'email') { ?><i class="fa fa-envelope-o"></i><? } ?>
                    <?= Html::a($note['from']['nickname'], '@web/users/r/'.$note['from_id'], ['class'=>'note-author-name']) ?>:

                    <? if ($clientHash) { ?><strong style="background-color:#BD499B; padding:0 4px; color:#fff;">#client</strong><? } ?>
                    <? if ($clientReservStatusHash) { ?><strong style="background-color:#BD499B; padding:0 4px; color:#fff;">#reservation-status</strong><? } ?>

                    <? if (substr($note['priority'], 0, 1) == 'C') { ?><strong style="background-color:#f60; padding:0 4px; color:#fff;">#important</strong><? } ?>
                    <? if (substr($note['priority'], -1) == '3') { ?><strong style="background-color:#c00; padding:0 4px; color:#fff;">#urgent</strong><? } ?>

                    <?= Html::a($title, '@web/notes/r/'.$note['id'], ['class'=>'note-title text-semibold']) ?>
                    <? if ($note['to']) { ?>
                    <i class="fa fa-caret-right text-muted"></i>
                    <?
                        $cnt = 0;
                        foreach ($note['to'] as $to) {
                            $cnt ++;
                            if ($cnt > 1) echo ', ';
                            echo Html::a($to['nickname'], '@web/users/r/'.$to['id'], ['class'=>'note-recipient-name text-small']);
                        }
                    }
                    ?>

                </h5>
                <div class="mb-1em">
                    <span class="text-muted">
                        <?= date('j/n/Y H:i', strtotime($time)) ?>
                        <?= $note['co'] != $note['uo'] ? Yii::t('op', ' edited') : '' ?>
                    </span>
                    &middot;
                    <?= Html::a(Yii::t('op', 'Edit'), '@web/notes/u/'.$note['id'], ['class'=>'u-message']) ?>
                    &middot;
                    <?= Html::a(Yii::t('op', 'Delete'), '@web/notes/d/'.$note['id']) ?>
                </div>
                <? if ($note['files']) { ?>
                <div class="note-file-list">
                    <? foreach ($note['files'] as $file) { ?>
                    <div class="note-file-list-item">+ <?= Html::a($file['name'], '@web/files/r/'.$file['id']) ?> <span class="text-muted"><?= Yii::$app->formatter->asShortSize($file['size'], 0) ?></span></div>
                    <? } ?>
                </div>
                <? } ?>
                <div class="note-body">
                    <?= $body ?>
                </div>
                <?
                $replies = [];
                foreach ($theNotes as $reply) {
                    if ($reply['n_id'] == $note['id']) {
                        $replies[] = $reply;
                    }
                }
                $replies = array_reverse($replies);

                foreach ($replies as $reply) {
                ?>
                <div class="mt-10">
                    <?= Html::img('/timthumb.php?zc=1&w=20&h=20&src='.$reply['from']['image'], ['class'=>'img-circle']) ?>
                    <a href="#anchor-note-<?= $reply['id'] ?>">
                        <?= $reply['from']['nickname'] ?> <?= Yii::t('op', 'replied') ?> <?= Yii::$app->formatter->asRelativetime($reply['co']) ?>
                    </a>
                </div>
                <?
                }
                ?>
            </div>
        </li>
<!-- MESSAGE END -->