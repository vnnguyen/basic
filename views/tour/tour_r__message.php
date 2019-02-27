<?php
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\HtmlPurifier;
use app\helpers\DateTimeHelper;


$opCodeName = $theTour['op_code'].' - '.$theTour['op_name'].' ';
$opCodeOnly = $theTour['op_code'].' ';
if ($note['n_id'] == 0 && strpos($note['title'], $opCodeName) === 0) {
    $title = str_replace($opCodeName, '', $note['title']);
    if (substr($title, 0, 2) == '- ') {
        $title = substr($title, 2);
    }
    $note['title'] = $title;
    $sql = 'UPDATE at_messages SET title = :title WHERE id=:id LIMIT 1';
    Yii::$app->db->createCommand($sql, [
        ':title'=>$title,
        ':id'=>$note['id'],
        ])->execute();
} elseif ($note['n_id'] == 0 && strpos($note['title'], $opCodeOnly) === 0) {
    $title = str_replace($opCodeOnly, '', $note['title']);
    if (substr($title, 0, 2) == '- ') {
        $title = substr($title, 2);
    }
    $note['title'] = $title;
    $sql = 'UPDATE at_messages SET title = :title WHERE id=:id LIMIT 1';
    Yii::$app->db->createCommand($sql, [
        ':title'=>$title,
        ':id'=>$note['id'],
        ])->execute();
}

?>
<!-- MESSAGE BEGIN -->

        <li class="note-list-item clearfix" data-id="<?= $note['id'] ?>" id="note-list-item-<?= $note['id'] ?>">
            <a name="anchor-note-<?= $note['id'] ?>"></a>
            <div class="note-avatar">
            <?= Html::a(Html::img($userAvatar, ['class'=>'rounded-circle note-author-avatar']), '@web/users/'.$note['from']['id']) ?>
            </div>
            <?php
            if ($note['n_id'] != 0) {
                $title = Yii::t('x', 'replied');
            } else {
                $title = $note['title'];// == '' ? Yii::t('x', '(No title)') : $note['title'];
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
            $body = str_replace(['<a href="/mentions/', '?mention=user">@'], ['<span class="text-muted">@</span><a class="text-pink font-weight-bold" href="/mentions/', '">'], $body);

            $body = str_replace(['width:', 'height:', 'font-size:', '<table', '</table>', '<p>&nbsp;</p>'], ['x:', 'x:', 'x:', '<div class="table-responsive"><table class="table table-condensed table-bordered" ', '</table></div>', ''], $body);
            $body = HtmlPurifier::process($body);
            ?>
            <div class="note-content">
                <h5 class="note-heading">
                    <?php if ($note['via'] == 'email') { ?><i class="fa fa-envelope-o"></i><?php } ?>
                    <?= Html::a($note['from']['nickname'], '@web/users/'.$note['from_id'], ['class'=>'note-author-name']) ?>:

                    <?php if ($clientHash) { ?><strong style="background-color:#BD499B; padding:0 4px; color:#fff;">#client</strong><?php } ?>
                    <?php if ($clientReservStatusHash) { ?><strong style="background-color:#BD499B; padding:0 4px; color:#fff;">#reservation-status</strong><?php } ?>

                    <?php if ($note['is_urgent'] == 'yes') { ?><span class="text-danger">#urgent</span><?php } ?>
                    <?php if ($note['is_important'] == 'yes') { ?><span class="text-warning">#important</span><?php } ?>

                    <?= $title == '' ? '' : Html::a($title, '@web/posts/'.($note['n_id'] == 0 ? $note['id'] : $note['n_id']), ['class'=>'note-title'.($note['n_id'] == 0 ? ' font-weight-bold' : '')]) ?>
                    <?php
                    if ($note['n_id'] != 0) {
                        foreach ($theNotes as $note2) {
                            if ($note2['id'] == $note['n_id']) {
                                echo ' <i class="fa fa-caret-left text-muted"></i> ', Html::a($note2['title'], '#note-list-item-'.$note2['id'], ['class'=>'text-muted']);
                                break;
                            }
                        }
                    }

                    if ($note['to']) {
                        echo ' <i class="fa fa-caret-right text-muted"></i> ';

                        $cnt = 0;
                        foreach ($note['to'] as $to) {
                            $cnt ++;
                            if ($cnt > 1) echo ', ';
                            echo Html::a($to['nickname'], '@web/users/'.$to['id'], ['class'=>'note-recipient-name text-small']);
                        }
                    }
                    ?>

                </h5>
                <div class="mt-0">
                    <span class="text-muted">
                        <?= date('j/n/Y H:i', strtotime($time)) ?>
                        <?= $note['co'] != $note['uo'] ? Yii::t('x', ' edited') : '' ?>
                    </span>

                    <?php if (strpos($note['title'], '#share') !== false) { ?>
                    - <span class="text-success">SHARED</span><?php } ?>
                    <?php if (in_array(USER_ID, [$note['cb'], $note['ub']])) { ?>
                    - <?= Html::a(Yii::t('x', 'Edit'), '@web/posts/'.$note['id'].'/u', ['class'=>'action-u-message text-muted', 'data-thread_id'=>$note['n_id'] == 0 ? $note['id'] : $note['n_id']]) ?>
                    - <?= Html::a(Yii::t('x', 'Delete'), '@web/posts/'.$note['id'].'/d', ['class'=>'action-delete-post text-muted', 'data-id'=>$note['id']]) ?>
                    <?php } ?>

                </div>
                <?php if ($note['files']) { ?>
                <div class="note-file-list mt-2">
                    <?php foreach ($note['files'] as $file) { ?>
                    <div class="note-file-list-item">+ <?= Html::a($file['name'], '@web/attachments/'.$file['id']) ?> <span class="text-muted"><?= Yii::$app->formatter->asShortSize($file['size'], 0) ?></span></div>
                    <?php } ?>
                </div>
                <?php } ?>
                <div class="note-body mt-2">
                    <?= $body ?>
                </div>
                <?php
                $replies = [];
                foreach ($theNotes as $reply) {
                    if ($reply['n_id'] == $note['id']) {
                        $replies[] = $reply;
                    }
                }
                $replies = array_reverse($replies);

                foreach ($replies as $reply) {
                ?>
                <div class="mt-2">
                    <?= Html::img('/timthumb.php?zc=1&w=20&h=20&src='.$reply['from']['image'], ['class'=>'rounded-circle']) ?>
                    <a href="#anchor-note-<?= $reply['id'] ?>">
                        <?= $reply['from']['nickname'] ?> <?= Yii::t('op', 'replied') ?> <?= Yii::$app->formatter->asRelativetime($reply['co']) ?>
                    </a>
                </div>
                <?php } ?>
                <div class="mt-2 div-action-reply-post"><span class=""><img src="/timthumb.php?w=20&h=20&src=<?= Yii::$app->user->identity->image ?>" class="rounded-circle"> <?= Html::a(Yii::t('x', 'Reply').' &raquo;', '@web/posts/'.($note['n_id'] == 0 ? $note['id'] : $note['n_id']), ['class'=>'action-reply-post', 'data-thread_id'=>$note['n_id'] == 0 ? $note['id'] : $note['n_id'], 'data-reply_ids' => '12952,8162']) ?></span></div>
                <div class="you-are-replying mt-2 d-none"></div>
            </div>
        </li>

<!-- MESSAGE END -->