<?php
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\HtmlPurifier;
use app\helpers\DateTimeHelper;

$post['body'] = str_replace(['<a href="/mentions/', '?mention=user">@'], ['<span class="text-pink">@</span><a class="text-pink font-weight-bold" href="/mentions/', '">'], $post['body']);
$post['body'] = str_replace(['padding:', 'padding-top:', 'padding-bottom:', 'margin:', 'margin-top:', 'margin-bottom:', 'width:', 'height:', 'font-size:', '<table', '</table>', '<p>&nbsp;</p>'], ['x:', 'x:', 'x:', 'x:', 'x:', 'x:', 'x:', 'x:', 'x:', '<div class="table-responsive"><table class="table table-narrow table-bordered" ', '</table></div>', ''], $post['body']);
$post['body'] = @HtmlPurifier::process($post['body']);

    ?>
<!-- POST -->
<hr id="hr-post-<?= $post['id'] ?>">
<div class="media div-post" id="div-post-<?= $post['id'] ?>">
    <?= Html::a(Html::img('/timthumb.php?zc=1&w=100&h=100&src='.($post['from']['image'] == '' ? '/assets/img/placeholder.jpg' : $post['from']['image']), ['class'=>'align-self-start post-avatar mr-3 rounded-circle']), '/users/'.$post['from']['id']) ?>
    <div class="media-body post-content">
        <h5 class="post-header my-0 py-0">
            <?php if ($post['via'] == 'email') { ?><i class="fa fa-envelope-o"></i><?php } ?>
            <?php
            if (isset($theCase) && !$post['from']) {
                if ($post['from_id'] == 36386) {
                    echo Html::a('Violette (Pacific Voyages)', '/contacts/r/36386', ['class'=>'post-from text-brown']), ': ';
                } else {
                    foreach ($theCase['people'] as $contact) {
                        if ($contact['id'] == $post['from_id']) {
                            echo Html::a($contact['name'], '@web/contacts/'.$contact['id'], ['class'=>'post-from text-brown']), ': ';
                            break;
                        }
                    }
                }
            } else {
            ?>
            <a href="#" class="post-from text-brown"><?= $post['from']['name'] ?></a>:
            <?php
            } ?>
            <?php if ($post['is_urgent'] == 'yes') { ?><span class="text-danger">#urgent</span><?php } ?>
            <?php if ($post['is_important'] == 'yes') { ?><span class="text-warning">#important</span><?php } ?>
            <?php
            // #client hashtag
            if (strpos($post['title'], '#client ') !== false) {
                $post['title'] = str_replace('#client ', '', $post['title']);
                echo '<span class="text-slate alpha-slate">#client</span>';
            }
            if (strpos($post['title'], '#reservation-status') !== false) {
                $post['title'] = str_replace('#reservation-status', '', $post['title']);
                echo '<span class="text-orange alpha-orange">#reservation-status</span>';
            }
            ?>
            <?php if ($post['n_id'] == 0) { ?>
            <a class="post-title font-weight-bold" href="/posts/<?= $post['id'] ?>"><?= $post['title'] ?></a>
            <?php } else { ?>
            <a class="post-title" href="/posts/<?= $post['n_id'] ?>"><?= Yii::t('x', 'replied') ?></a>
            <?php
            foreach ($thePosts as $post2) {
                if ($post2['id'] == $post['n_id']) {
                    echo ' <i class="fa fa-caret-left text-muted"></i> ', Html::a($post2['title'] == '' ? Yii::t('x', '(No title)') : $post2['title'], '#div-post-'.$post2['id'], ['class'=>'text-muted']);
                    break;
                }
            }
            ?>
            <?php } ?>

            <!-- CASE m_to -->
            <?php
            if (isset($theCase) && $post['m_to'] != 0) {
                foreach ($theCase['people'] as $contact) {
                    if ($contact['id'] == $post['m_to']) {
                        echo ' <i class="fa fa-caret-right text-muted"></i> ';
                        echo Html::a($contact['name'], '@web/contacts/'.$contact['id'], ['class'=>'text-purple']);
                        break;
                    }
                }
            }
            ?>

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
            <span class="post-time text-muted" title="<?= Yii::$app->formatter->asRelativetime($post['created_dt']) ?>"><i class="fa fa-clock-o"></i> <?= DateTimeHelper::convert($post['created_dt'], 'j/n/Y H:i') ?> <?= $post['created_dt'] != $post['updated_dt'] ? Yii::t('x', 'edited') : '' ?></span>
            <span class="post-shared text-success text-uppercase ml-2 <?= strpos($post['tags'], 'share') === false ? 'd-none' : '' ?>"><?= Yii::t('x', 'Shared') ?></span>
            <a class="ml-2 text-muted dropdown-toggle" href="#" data-toggle="dropdown"><?= Yii::t('x', 'Edit/Share') ?></a>
            <div class="dropdown-menu">
                <?php if (isset($theCase) && in_array(USER_ID, [1, $post['created_by'], $post['updated_by']])) { ?>
                    <?php if (!empty($post['attachments'])) { ?>
                <?= Html::a(Yii::t('x', 'Share attachments in tour'), '#', ['class'=>'dropdown-item action-share-post share-attachments-only', 'data-id'=>$post['id']]) ?>
                    <?php } ?>
                <?= Html::a(Yii::t('x', 'Share post in tour'), '#', ['class'=>'dropdown-item action-share-post', 'data-id'=>$post['id']]) ?>
                <?= Html::a(Yii::t('x', 'Stop sharing in tour'), '#', ['class'=>'dropdown-item action-share-post stop-sharing', 'data-id'=>$post['id']]) ?>
                <?php } ?>
                <?php if (in_array(USER_ID, [1, $post['created_by'], $post['updated_by']])) { ?>
                <a class="dropdown-item action-edit-post" href="/posts/<?= $post['id'] ?>/u" data-id="<?= $post['id'] ?>"><?= Yii::t('x', 'Edit') ?></a>
                <a class="dropdown-item action-delete-post text-danger" href="/posts/<?= $post['id'] ?>/d" data-id="<?= $post['id'] ?>"><?= Yii::t('x', 'Delete') ?></a>
                <?php } ?>
            </div>
        </div>
        <?php if (!empty($post['attachments'])) { ?>
        <div class="post-attachments mt-3 pl-3">
            <?php foreach ($post['attachments'] as $attachment) { ?>
            <div class="post-attachment">+ <a href="/attachments/<?= $attachment['id'] ?>"><?= $attachment['name'] ?></a> <span class="text-muted"><?= Yii::$app->formatter->asShortSize($attachment['size'], 0) ?></span></div>
            <?php } ?>
        </div>
        <?php } ?>
        <div class="post-body mt-3" style="max-width:709px">
            <div style="max-width:100%">
        <?php if (isset($theTour) && strpos($post['tags'], 'share-attachments') !== false) { ?>
            <span class="text-muted"><?= Yii::t('x', '(Post body not displayed)') ?></span>
        <?php } else { ?>
            <?= $post['body'] ?>
        <?php } ?>
            <?php if ($post['id'] == 564803) { // JOKE ?>
            <div class="mt-2">
                <span style="border:1px solid #eee; border-radius:4px; padding:8px; background-color:#f6f6f6"><i class="fa fa-thumbs-up text-pink"></i> <a href="/contacts/46803">Phương Thảo</a> liked this</span>
            </div>
            <?php } // JOKE ?>
            <?php if ($post['id'] == 565646) { // JOKE ?>
            <div class="mt-2">
                <span style="border:1px solid #eee; border-radius:4px; padding:8px; background-color:#f6f6f6"><i class="fa fa-thumbs-up text-pink"></i> <a href="/contacts/1">Huân H.</a> liked this</span>
            </div>
            <?php } // JOKE ?>
            </div>
        </div>

        <?php
        $replies = [];
        foreach ($thePosts as $reply) {
            if ($reply['n_id'] == $post['id']) {
                $replies[] = $reply;
            }
        }
        $replies = array_reverse($replies);

        foreach ($replies as $reply) {
        ?>
        <div class="mt-2" id="div-reply-<?= $reply['id'] ?>">
            <?= Html::img('/timthumb.php?zc=1&w=20&h=20&src='.$reply['from']['image'], ['class'=>'rounded-circle']) ?>
            <a class="action-load-reply reply-not-loaded" data-id="<?= $reply['id'] ?>" href="#div-post-<?= $reply['id'] ?>" title="<?= Yii::$app->formatter->asRelativetime($reply['created_dt']) ?>">
                <?= $reply['from']['name'] ?> <?= Yii::t('op', 'replied') ?> <span class="text-muted font-weight-normal"><?= DateTimeHelper::convert($reply['created_dt'], 'j/n/Y H:i') ?></span>
            </a>
            <a class="small text-muted" href="#div-post-<?= $reply['id'] ?>" title="<?= Yii::t('x', 'Link to repky') ?>">#</a>
        </div>
        <?php } ?>
        <div class="mt-2 div-action-reply-post"><span class=""><img src="/timthumb.php?w=20&h=20&src=<?= Yii::$app->user->identity->image ?>" class="rounded-circle"> <?= Html::a(Yii::t('x', 'Reply').' &raquo;', '@web/posts/'.($post['n_id'] == 0 ? $post['id'] : $post['n_id']), ['class'=>'action-reply-post', 'data-thread_id'=>$post['n_id'] == 0 ? $post['id'] : $post['n_id']]) ?></span></div>
        <div class="you-are-replying mt-2 d-none"></div>
    </div>
</div>
<!-- /POST -->