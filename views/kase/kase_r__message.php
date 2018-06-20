<?
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\HtmlPurifier;
use app\helpers\DateTimeHelper;

// BEGIN NOTE
$userAvatar = '//secure.gravatar.com/avatar/'.md5($note['from']['id']).'?s=100&d=wavatar';
if ($note['from']['image'] != '') {
    $userAvatar = '/timthumb.php?zc=1&w=100&h=100&src='.$note['from']['image'];
}
//$note->from->image != '' ? DIR.'timthumb.php?src='.$note->from->image.'&w=300&h=300&zc=1' : 'http://0.gravatar.com/avatar/'.md5($li->from_id).'.jpg?s=64&d=wavatar';;

?>
        <li class="note-list-item clearfix">
            <a name="anchor-note-<?= $note['id'] ?>"></a>
            <div class="note-avatar">
            <?= Html::a(Html::img($userAvatar, ['class'=>'img-circle note-author-avatar']), '@web/users/r/'.$note['from']['id']) ?>
            </div>
            <?
            if ($note['n_id'] != 0) {
                $title = 'replied';
            } else {
                $title = $note['title'] == '' ? '( no title )' : $note['title'];
            }
            $body = $note['body'];
            /*
            // Name mentions
            $toEmailList = [];
            foreach ($thePeople as $person) {
                $mention = '@[user-'.$person['id'].']';
                if (strpos($body, $mention) !== false) {
                    $body = str_replace($mention, '@'.Html::a($person['name'], '@web/users/r/'.$person['id'], ['style'=>'font-weight:bold;']), $body);
                    $toEmailList[] = $person['email'];
                }
            }
            $toEmailList = array_unique($toEmailList);
            */
            $body = str_replace(['width:', 'height:', 'font-size:', '<table ', '<p>&nbsp;</p>'], ['x:', 'x:', 'x:', '<table class="table table-condensed table-bordered" ', ''], $body);
            $body = HtmlPurifier::process($body);
            ?>
            <div class="note-content">
                <h5 class="note-heading">
                    <? if ($note['via'] == 'email') { ?><i class="fa fa-envelope-o"></i><? } ?>
                    <?= Html::a($note['from']['nickname'], '@web/users/r/'.$note['from_id'], ['class'=>'note-author-name']) ?>: 
                    <? if (substr($note['priority'], 0, 1) == 'C') { ?><strong style="background-color:#ffd; padding:0 4px; color:#c00;">#important</strong><? } ?>
                    <? if (substr($note['priority'], -1) == '3') { ?><strong style="background-color:#ffd; padding:0 4px; color:#c00;">#urgent</strong><? } ?>

                    <?= Html::a($title, '@web/notes/r/'.$note['id'], ['class'=>'note-title']) ?>
                    <?
                    if ($note['to']) {
                        echo ' <i class="fa fa-caret-right text-muted"></i> ';
                        $cnt = 0;
                        foreach ($note['to'] as $to) {
                            $cnt ++;
                            if ($cnt > 1) echo ', ';
                            echo Html::a($to['nickname'], '@web/users/r/'.$to['id'], ['class'=>'note-recipient-name']);
                        }
                    }
                    ?>
                </h5>
                <div class="note-meta mb-1em">
                    <span class="text-muted timeago" title="<?= date('Y-m-d\TH:i:s', strtotime($note['co'])) ?>+07"><?= date('j/n/Y H:i', strtotime($time)) ?></span>
                    - <?= Html::a('Edit', '@web/notes/u/'.$note['id']) ?>
                    - <?= Html::a('Delete', '@web/notes/d/'.$note['id']) ?>
                </div>
                <? if ($note['files']) { ?>
                <div class="note-file-list">
                    <? foreach ($note['files'] as $file) { ?>
                    <div class="note-file-list-item">+ <?= Html::a($file['name'], '@web/files/r/'.$file['id']) ?> <span class="text-muted"><?= number_format($file['size'] / 1024, 2) ?> KB</span></div>
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
<?
                // END NOTE