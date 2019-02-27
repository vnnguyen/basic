<?php
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\HtmlPurifier;
use app\helpers\DateTimeHelper;

$venuePosts = \app\models\Post::find()
    ->where(['rtype'=>'venue', 'rid'=>$theVenue['id']])
    ->andWhere('rid!=0')
    ->with([
        'from',
        'to',
        'replies',
        'replies.from',
        'attachments',
    ])
    ->orderBy('created_dt DESC')
    ->asArray()
    ->all();

?>

<div class="col-md-8">

    <ul class="note-list">
        <li class="first note-list-item clearfix">
            <div class="note-avatar"><?= Html::a(Html::img('/timthumb.php?zc=1&w=100&h=100&src='.Yii::$app->user->identity->image, ['class'=>'note-author-avatar rounded-circle']), '@web/contacts/'.USER_ID) ?></div>
            <div class="note-content">
                <?= $this->render('_editor_new.php', ['theVenue'=>$theVenue]) ?>
            </div>
        </li>

        <?php if (USER_ID == 1) { ?>
        <li class="post-list-item">
            <div class=""></div>
            <div class="post-content">
                <div class="post-header">
                    <a href="#" class="post-from">Name from</a>
                    <a class="post-title" href="#">This is the title</a>
                    <span class="post-tos">
                        <i class="fa fa-caret-right text-muted"></i>
                        <a href="#" class="post-to">Name 1</a>, <a href="#" class="post-to">Name 2</a>
                    </span>
                </div>
                <div class="post-body">
                    <p>Tuy nhiên năm 2015, nhân vật "người mẹ" trong bức ảnh đã đứng ra vạch trần toàn bộ sự thật. Cô là Heidi Yeh, một diễn viên kiêm người mẫu xinh đẹp tại Đài Loan. Cô nói rằng bức ảnh trên hoàn toàn là dàn dựng. Người đàn ông cùng 3 đứa trẻ trong ảnh không phải chồng con của cô. Hơn thế nữa, 3 bé cũng không hề quá xấu, họ đã photoshop để chúng trông xấu xí hơn.</p>
                    <p>Trong một cuộc họp báo, Yeh vừa khóc vừa nói kể từ khi bức ảnh đó được lan truyền, cuộc sống của cô thay đổi hoàn toàn, tiêu tan sự nghiệp đang phát triển.</p>
                    <p>Yeh cho biết cô đã ký hợp đồng với công ty quảng cáo JWT, chụp ảnh cho một trung tâm thẩm mỹ. Hợp đồng ghi rõ bức ảnh này sẽ chỉ được sử dụng trên những sản phẩm in ấn. Nhưng không lâu sau, công ty này đã đưa hình ảnh của cô cho một trung tâm thẩm mỹ khác. Họ đã sử dụng bức hình trong chiến dịch quảng cáo trực tuyến. Nó nhanh chóng được lan truyền trên khắp cộng đồng mạng, trở thành đề tài giễu cợt của mọi người.</p>
                </div>
            </div>
        </li>
        <?php } ?>

<?php

foreach ($venuePosts as $post) {
    $time = DateTimeHelper::convert($post['updated_dt'] ?? $post['created_dt'], 'j.n.Y H:i', 'UTC', Yii::$app->user->identity->timezone);
// BEGIN NOTE
$userAvatar = '//secure.gravatar.com/avatar/'.md5($post['from']['id']).'?s=100&d=wavatar';
if ($post['from']['image'] != '') {
    $userAvatar = '/timthumb.php?zc=1&w=100&h=100&src='.$post['from']['image'];
}
//$post->from->image != '' ? DIR.'timthumb.php?src='.$post->from->image.'&w=300&h=300&zc=1' : 'http://0.gravatar.com/avatar/'.md5($li->from_id).'.jpg?s=64&d=wavatar';;

?>
        <li class="note-list-item clearfix" style="border:none; padding:16px 0">
            <a name="anchor-note-<?= $post['id'] ?>"></a>
            <div class="note-avatar">
                <?= Html::a(Html::img($userAvatar, ['class'=>'rounded-circle note-author-avatar']), '@web/contacts/'.$post['from']['id']) ?>
            </div>
            <?php
            if ($post['n_id'] != 0) {
                $title = Yii::t('x', 'replied');
            } else {
                $title = $post['title'] == '' ? Yii::t('x', '(No title)') : $post['title'];
            }
            $body = $post['body'];
            /*
            // Name mentions
            $toEmailList = [];
            foreach ($thePeople as $person) {
                $mention = '@[user-'.$person['id'].']';
                if (strpos($body, $mention) !== false) {
                    $body = str_replace($mention, '@'.Html::a($person['name'], '@web/contacts/'.$person['id'], ['style'=>'font-weight:bold;']), $body);
                    $toEmailList[] = $person['email'];
                }
            }
            $toEmailList = array_unique($toEmailList);
            */
            $body = str_replace(['<a href="/mentions/', '?mention=user">@'], ['<span class="text-muted">@</span><a class="text-pink font-weight-bold" href="/mentions/', '">'], $body);
            $body = str_replace(['width:', 'height:', 'font-size:', '<table ', '<p>&nbsp;</p>'], ['x:', 'x:', 'x:', '<table class="table table-narrow table-bordered" ', ''], $body);
            $body = @HtmlPurifier::process($body);
            ?>
            <div class="note-content">
                <h4 class="note-heading mb-0">
                    <?php if ($post['via'] == 'email') { ?><i class="fa fa-envelope-o"></i><?php } ?>
                    <?php if ($post['from_id'] == 36386) { echo Html::a('Violette (Pacific Voyages)', '/contacts/r/36386', ['class'=>'note-author-name']), ': '; } else { ?>
                    <?= Html::a($post['from']['nickname'], '@web/contacts/'.$post['from_id'], ['class'=>'note-author-name']) ?>: 
                    <?php } ?>
                    <?php if (substr($post['priority'], 0, 1) == 'C') { ?><strong style="background-color:#ffd; padding:0 4px; color:#c00;">#important</strong><?php } ?>
                    <?php if (substr($post['priority'], -1) == '3') { ?><strong style="background-color:#ffd; padding:0 4px; color:#c00;">#urgent</strong><?php } ?>

                    <?= Html::a($title, '@web/posts/'.$post['id'], ['class'=>'note-title']) ?>
                    <?php
                    if ($post['to']) {
                        echo ' <i class="fa fa-caret-right text-muted"></i> ';
                        $cnt = 0;
                        foreach ($post['to'] as $to) {
                            $cnt ++;
                            if ($cnt > 1) echo ', ';
                            echo Html::a($to['nickname'], '@web/contacts/'.$to['id'], ['class'=>'note-recipient-name']);
                        }
                    }
                    ?>
                </h4>
                <div class="note-meta pb-1 mb-2" style="border-bottom:1px solid #ccc">
                    <span class="text-muted timeago" title="<?= date('Y-m-d\TH:i:s', strtotime($post['created_dt'])) ?>+07"><?= date('j/n/Y H:i', strtotime($time)) ?></span>
                    - <?= Html::a(Yii::t('x', 'Edit'), '@web/posts/'.$post['id'].'/u') ?>
                    - <?= Html::a(Yii::t('x', 'Delete'), '@web/posts/'.$post['id'].'/d') ?>
                </div>
                <?php if (!empty($post['attachments'])) { ?>
                <div class="note-file-list">
                    <?php foreach ($post['attachments'] as $file) { ?>
                    <div class="note-file-list-item">+ <?= Html::a($file['name'], '@web/attachments/'.$file['id']) ?> <span class="text-muted"><?= number_format($file['size'] / 1024, 2) ?> KB</span></div>
                    <?php } ?>
                </div>
                <?php } ?>
                <div class="note-body">
                    <?= $body ?>
                </div>
                <?php
                $replies = [];
                foreach ($post['replies'] as $reply) {
                    if ($reply['n_id'] == $post['id']) {
                        $replies[] = $reply;
                    }
                }
                $replies = array_reverse($replies);

                foreach ($replies as $reply) {
                ?>
                <div class="mt-2">
                    <?= Html::img('/timthumb.php?zc=1&w=20&h=20&src='.$reply['from']['image'], ['class'=>'img-circle']) ?>
                    <a href="#anchor-note-<?= $reply['id'] ?>">
                        <?= $reply['from']['nickname'] ?> <?= Yii::t('op', 'replied') ?> <?= Yii::$app->formatter->asRelativetime($reply['co']) ?>
                    </a>
                </div>
                <?php
                }
                ?>
                <div class="mt-2 d-none">
                    <a href="#" class="bg-light p-1 text-muted action-reply-post" data-postid="<?= $post['n_id'] == 0 ? $post['id'] : $post['n_id'] ?>" data-repliesto=""><?= Yii::t('x', 'Reply') ?></a>
                    <div id="div-reply-post-<?= $post['n_id'] == 0 ? $post['id'] : $post['n_id'] ?>">
                    </div>
                </div>
            </div>
        </li>
<?php
                // END NOTE
}
?>  
    </ul>
</div>
<?php 
$js2 = <<<'TXT'
$('.action-reply-post').on('click', function(e){
    e.preventDefault()
    post_thread_id = $(this).data('postid')
    $('#div-post-here').removeClass('d-none').appendTo('#div-reply-post-' + post_thread_id)
    $('#title').hide();
    // CKEDITOR.replace('editor1', CKEconfig)
    // CKEDITOR.instances.editor1.focus()

})
TXT;

$this->registerJs($js2);
?>