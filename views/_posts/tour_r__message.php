<?php
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\HtmlPurifier;
use app\helpers\DateTimeHelper;

// BEGIN NOTE
$userAvatar = '//secure.gravatar.com/avatar/'.md5($note['from']['id']).'?s=100&d=wavatar';
if ($note['from']['image'] != '') {
    $userAvatar = '/timthumb.php?zc=1&w=100&h=100&src='.$note['from']['image'];
}

?>
<style type="text/css">
.post-avatar {width:64px    }
</style>
<!-- POST -->
<?php if (USER_ID != 111) {
$post = $note;

$userAvatar = '//secure.gravatar.com/avatar/'.md5($post['from']['id']).'?s=100&d=wavatar';
if ($note['from']['image'] != '') {
    $userAvatar = '/timthumb.php?zc=1&w=100&h=100&src='.$post['from']['image'];
}

$post['body'] = str_replace(['<a href="/mentions/', '?mention=user">@'], ['<span class="text-pink">@</span><a class="text-pink font-weight-bold" href="/mentions/', '">'], $post['body']);
$post['body'] = str_replace(['width:', 'height:', 'font-size:', '<table', '</table>', '<p>&nbsp;</p>'], ['x:', 'x:', 'x:', '<div class="table-responsive"><table class="table table-condensed table-bordered" ', '</table></div>', ''], $post['body']);
$post['body'] = HtmlPurifier::process($post['body']);

    ?>
<hr>
<div class="media" id="div-post-<?= $post['id'] ?>">
    <?= Html::a(Html::img('/timthumb.php?zc=1&w=100&h=100&src='.($post['from']['image'] == '' ? '/assets/img/placeholder.jpg' : $post['from']['image']), ['class'=>'align-self-start post-avatar mr-3 rounded-circle']), '/users/'.$post['from']['id']) ?>
    <div class="media-body post-content">
        <h5 class="post-header my-0 py-0">
            <a href="#" class="post-from text-brown"><?= $post['from']['name'] ?></a>:
            <?php if ($post['is_urgent'] == 'yes') { ?><span class="text-danger">#urgent</span><?php } ?>
            <?php if ($post['is_important'] == 'yes') { ?><span class="text-warning">#important</span><?php } ?>
            <?php if ($post['n_id'] == 0) { ?>
            <a class="post-title font-weight-bold" href="/posts/<?= $post['id'] ?>"><?= $post['title'] ?></a>
            <?php } else { ?>
            <a class="post-title" href="/posts/<?= $post['title'] ?>"><?= Yii::t('x', 'replied') ?></a>
            <?php
            foreach ($theNotes as $post2) {
                if ($post2['id'] == $post['n_id']) {
                    echo ' <i class="fa fa-caret-left text-muted"></i> ', Html::a($post2['title'], '#div-post-'.$post2['id'], ['class'=>'text-muted']);
                    break;
                }
            }
            ?>
            <?php } ?>
            <?php if (!empty($post['to'])) { ?>
            <span class="post-tos">
                <?php foreach ($post['to'] as $toCnt=>$to) { ?>
                <?php if ($toCnt == 0) { ?><i class="fa fa-caret-right text-muted"></i><?php } else { ?>, <?php } ?>
                <a href="/users/<?= $to['id'] ?>" class="post-to text-purple text-small"><?= $to['name'] ?></a>
                <?php } ?>
            </span>
            <?php } ?>
        </h5>
        <div class="post-meta mt-1">
            <span class="post-time text-muted" title="<?= DateTimeHelper::convert($post['co'], 'H:i j/n/Y') ?>"><i class="fa fa-clock-o"></i> <?= Yii::$app->formatter->asRelativetime($post['co']) ?></span>
            <?php if (strpos($post['title'], '#share') !== false) { ?><span class="text-success ml-1"><?= Yii::t('x', 'SHARED') ?></span><?php } ?>
            <a class="post-action action-edit-post ml-1" href="#"><?= Yii::t('x', 'Edit') ?></a>
            <a class="post-action action-delete-post ml-1" href="#"><?= Yii::t('x', 'Delete') ?></a>
        </div>
        <?php if ($post['attachments']) { ?>
        <div class="post-attachments mt-3 pl-3">
            <?php foreach ($post['attachments'] as $attachment) { ?>
            <div class="post-attachment">+ <a href="/attachments/<?= $attachment['id'] ?>"><?= $attachment['name'] ?></a> <span class="text-muted"><?= Yii::$app->formatter->asShortSize($attachment['size'], 0) ?></span></div>
            <?php } ?>
        </div>
        <?php } ?>
        <div class="post-body mt-3">
            <?= $post['body'] ?>
        </div>
        <?php
        $replies = [];
        foreach ($theNotes as $reply) {
            if ($reply['n_id'] == $post['id']) {
                $replies[] = $reply;
            }
        }
        $replies = array_reverse($replies);

        foreach ($replies as $reply) {
        ?>
        <div class="mt-2">
            <?= Html::img('/timthumb.php?zc=1&w=20&h=20&src='.$reply['from']['image'], ['class'=>'rounded-circle']) ?>
            <a href="#div-post-<?= $reply['id'] ?>">
                <?= $reply['from']['name'] ?> <?= Yii::t('op', 'replied') ?> <?= Yii::$app->formatter->asRelativetime($reply['co']) ?>
            </a>
        </div>
        <?php } ?>
        <div class="mt-2 div-action-reply-post"><span class=""><img src="/timthumb.php?w=20&h=20&src=<?= Yii::$app->user->identity->image ?>" class="rounded-circle"> <?= Html::a(Yii::t('x', 'Reply').' &raquo;', '@web/posts/'.($post['n_id'] == 0 ? $post['id'] : $post['n_id']), ['class'=>'action-reply-post', 'data-thread_id'=>$post['n_id'] == 0 ? $post['id'] : $post['n_id']]) ?></span></div>
        <div class="you-are-replying mt-2 d-none"></div>
    </div>
</div>
<?php } else { ?>
<div class="note-list-item clearfix" data-id="<?= $note['id'] ?>" id="note-list-item-<?= $note['id'] ?>">
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
            <?= Html::a($note['from']['name'], '@web/users/'.$note['from_id'], ['class'=>'note-author-name']) ?>:

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
                    echo Html::a($to['name'], '@web/users/'.$to['id'], ['class'=>'note-recipient-name text-small']);
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
            - <?= Html::a(Yii::t('x', 'Edit'), '@web/posts/'.$note['id'].'/u', ['class'=>'text-muted']) ?>
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
                <?= $reply['from']['name'] ?> <?= Yii::t('op', 'replied') ?> <?= Yii::$app->formatter->asRelativetime($reply['co']) ?>
            </a>
        </div>
        <?php } ?>
        <div class="mt-2 div-action-reply-post"><span class=""><img src="/timthumb.php?w=20&h=20&src=<?= Yii::$app->user->identity->image ?>" class="rounded-circle"> <?= Html::a(Yii::t('x', 'Reply').' &raquo;', '@web/posts/'.($note['n_id'] == 0 ? $note['id'] : $note['n_id']), ['class'=>'action-reply-post', 'data-thread_id'=>$note['n_id'] == 0 ? $note['id'] : $note['n_id']]) ?></span></div>
        <div class="you-are-replying mt-2 d-none"></div>
    </div>
</div>
<?php } ?>
<!-- /POST -->
