<?php
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\ActiveForm;

include('_posts_inc.php');

Yii::$app->params['page_icon'] = 'sticky-note-o';

Yii::$app->params['page_title'] = $thePost['title'] == '' ? Yii::t('x', '(No title)') : $thePost['title'];

$userAvatar = '/timthumb.php?w=100&h=100&src='.$thePost['from']['image'];

$uid = Yii::$app->security->generateRandomString(10).'_'.time().'_'.USER_ID;

$theUsers = \app\models\User::find()
    ->select(['users.id', 'users.name', 'users.email', 'contacts.fname', 'contacts.lname', 'users.image', 'contact_id'])
    ->where(['users.status'=>'on'])
    ->innerJoinWith('contact')
    ->orderBy('lname, fname')
    ->asArray()
    ->all();

?>
<script>
var
post_id = 0,
post_uid = '<?= $uid ?>',
post_rtype = '<?= $thePost['rtype'] ?>',
post_rid = <?= $thePost['rid'] ?>,
post_thread_id = <?= $thePost['id'] ?>,
post_title = '',
post_body = '',

users = [
    <?php foreach ($theUsers as $i=>$user) { ?>
    <?= $i == 0 ? '' : ', ' ?>{id: <?= $user['id'] ?>, name: '<?= $user['name'] ?>', email: '<?= strstr($user['email'], '@', true) ?>', image: '/timthumb.php?w=100&h=100&src=<?= $user['image'] ?>', search: '<?= strtolower(Inflector::transliterate(str_replace([' ', '-'], ['', ''], $user['name'].$user['fname'].$user['lname'].strstr($user['email'], '@', true)))) ?>'}
    <?php } ?>
],

tags = [
    'important',
    'urgent',
    'client',
    'status',
    'feedback',
];

</script>
<style type="text/css">
.message-attachments {margin-top:16px; padding-left:16px;}
</style>
<div class="col-md-8 col-sm-10 col-xs-12">
    <div class="card">
        <?php if (in_array($thePost['rtype'], ['case', 'tour', 'venue', 'user'])) { ?>
        <div class="alert alert-info m-0" style="border:0;">
            <i class="fa fa-fw fa-info-circle"></i>
            This discussion referes to: <?= Html::a($relName, '/'.$relLink, ['class'=>'alert-link']) ?>
        </div>
        <?php } ?>
        <div class="card-body">
            <div class="media">
                <img class="rounded-circle mr-3" style="width:64px; height:64px;" src="/timthumb.php?zc=1&w=100&h=100&src=<?= $thePost['from']['image'] == '' ? '/assets/img/placeholder.jpg' : $thePost['from']['image'] ?>" alt="Avatar">
                <div class="media-body">
                    <h3 class="m-0">
                        <span class="message-from"><?= Html::a($thePost['from']['name'], '/users/'.$thePost['from']['id'], ['class'=>'text-brown font-weight-semibold']) ?>:</span>
                        <span class="message-emph">
                            <? if (substr($thePost['priority'], 0, 1) == 'C') { ?><span class="message-emph-important">#important</span><?php } ?>
                            <? if (substr($thePost['priority'], 0, 1) == '3') { ?><span class="message-emph-urgent">#urgent</span><?php } ?>
                        </span>
                        <span class="message-title"><a href="#"><?= $thePost['title'] ?></a></span>
                        <?php if (!empty($thePost['to'])) { ?>
                        <span class="message-tos">
                            <?php foreach ($thePost['to'] as $toCnt=>$to) { ?>
                            <?= $toCnt == 0 ? '<i class="fa fa-caret-right text-muted"></i>' : ', ' ?>
                            <span class="message-to"><?= Html::a($to['name'], '/users/'.$to['id'], ['class'=>'text-purple']) ?></span>
                            <?php } ?>
                        </span>
                        <?php } ?>
                    </h3>

                    <div class="message-meta mt-1">
                        <span class="message-time text-muted" title="<?= \app\helpers\DateTimeHelper::convert($thePost['co'], 'j/n/Y H:i', 'UTC', Yii::$app->user->identity->timezone) ?>"><i class="fa fa-clock-o"></i> <?= Yii::$app->formatter->asRelativetime($thePost['uo']) ?><?php if ($thePost['co'] != $thePost['uo']) { ?> <?= Yii::t('x', 'edited') ?><?php } ?></span>
                        <?php if (in_array(USER_ID, [$thePost['cb'], $thePost['ub'], 1])) { ?>
                        <span class="message-actions ml-3">
                            <?= Html::a(Yii::t('x', 'Edit'), '/posts/'.$thePost['id'].'/u') ?>
                            &middot;
                            <?= Html::a(Yii::t('x', 'Delete'), '/posts/'.$thePost['id'].'/d', ['class'=>'text-danger']) ?>
                        </span>
                        <?php } ?>
                    </div>

                    <?php if ($thePost['files']) { ?>
                    <div class="message-attachments">
                        <?php foreach ($thePost['files'] as $file) { ?>
                        <div class="message-attachment">+ <?= Html::a($file['name'], '@web/attachments/'.$file['id']) ?> <span class="text-muted"><?= number_format($file['size'] / 1024, 2) ?> KB</span></div>
                        <?php } ?>
                    </div>
                    <?php } ?>

                    <div class="message-body mt-3">
                        <?php
                        $thePost['body'] = str_replace(['<a href="/mentions/', '?mention=user">@'], ['<span class="text-muted">@</span><a class="text-pink font-weight-bold" href="/mentions/', '">'], $thePost['body']);
                        ?>
                        <?= str_replace(['font-size:', '<table>', '<p>&nbsp;</p>'], ['x:', '<table class="table table-condensed table-bordered">', ''], $thePost['body']) ?>
                    </div>
                </div>
            </div>
        </div>

        <?php foreach ($thePost['replies'] as $reply) { ?>
        <div class="card-body">
            <div class="media">
                <img class="rounded-circle mr-3" style="width:64px; height:64px;" src="/timthumb.php?w=100&h=100&src=<?= $reply['from']['image'] ?>">
                <div class="media-body">
                    <h4 class="m-0">
                        <span class="message-from"><?= Html::a($reply['from']['name'], '/users/'.$reply['from']['id'], ['class'=>'text-brown font-weight-semibold']) ?></span>
                        <span class="text-muted"><?= Yii::t('x', 'replied') ?></span>
                    </h4>
                    <div class="message-meta mt-1">
                        <span class="message-time text-muted" title="<?= \app\helpers\DateTimeHelper::convert($reply['co'], 'j/n/Y H:i', 'UTC', Yii::$app->user->identity->timezone) ?>"><i class="fa fa-clock-o"></i> <?= Yii::$app->formatter->asRelativetime($reply['uo']) ?><?php if ($reply['co'] != $reply['uo']) { ?> <?= Yii::t('x', 'edited') ?><?php } ?></span>
                        <?php if (in_array(USER_ID, [$reply['cb'], $reply['ub'], 1])) { ?>
                        <span class="message-actions ml-3">
                            <?= Html::a(Yii::t('x', 'Edit'), '/posts/'.$reply['id'].'/u') ?>
                            &middot;
                            <?= Html::a(Yii::t('x', 'Delete'), '/posts/'.$reply['id'].'/d', ['class'=>'text-danger']) ?>
                        </span>
                        <?php } ?>
                    </div>

                    <?php if ($reply['files']) { ?>
                    <div class="message-attachments">
                        <?php foreach ($reply['files'] as $file) { ?>
                        <div class="message-attachment">+ <?= Html::a($file['name'], '@web/attachments/'.$file['id']) ?> <span class="text-muted"><?= number_format($file['size'] / 1024, 2) ?> KB</span></div>
                        <?php } ?>
                    </div>
                    <?php } ?>

                    <div class="message-body mt-3">
                        <?php
                        $reply['body'] = str_replace(['<a href="/mentions/', '?mention=user">@'], ['<span class="text-muted">@</span><a class="text-pink font-weight-bold" href="/mentions/', '">'], $reply['body']);
                        ?>
                        <?= str_replace(['font-size:', '<table>', '<p>&nbsp;</p>'], ['x:', '<table class="table table-condensed table-bordered">', ''], $reply['body']) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php } // foreach replies ?>

        <div class="card-body">
            <div class="media">
                <img class="rounded-circle mr-3" style="width:64px; height:64px;" src="/timthumb.php?zc=1&w=100&h=100&src=<?= Yii::$app->user->identity->image ?>" alt="Avatar">
                <div class="media-body">
                    <h4 class="m-0">
                        <span class="message-from"><?= Html::a(Yii::$app->user->identity->name, '/me', ['class'=>'text-brown font-weight-semibold']) ?>:</span>
                        <span class="text-muted"><?= Yii::t('x', 'type your reply here') ?></span>
                    </h4>
                    <div class="text-danger">NOTE: Để gửi email thông báo cho người không tham gia thảo luận này, khi viết trả lời bạn có thể nhấn @... để nhắc đến (mention) họ.</div>
                    <div class="message-body mt-3">
                        <input type="hidden" name="title" id="title" value="<?= $thePost['title'] ?>">
                        <div class="form-group">
                            <textarea id="editor1" name="editor1" rows="10" class="form-control" placeholder="Post your reply/comment here"></textarea>
                        </div>
                        <div class="form-group" style="border:1px solid #ccc;">
                            <div class="table table-striped" id="previews"></div>
                            <div id="mydzfiles" style="padding:8px; text-align:center; cursor:pointer; color:#fff; background-color:#86789a"><?= Yii::t('x', 'Click or drag and drop files here to attach') ?></div>
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-info-circle"></i> <?= Yii::t('x', 'The following people will be notified of your reply') ?>:</label>
                            <select id="replyto" class="select2 form-control" data-placeholder="<?= Yii::t('x', 'Nobody') ?>" name="replyto[]" multiple>
                                <?php foreach ($theUsers as $user) { if (in_array($user['id'], $replyToIdList)) { ?>
                                <option value="<?=$user['id']?>" <?= $user['id'] != USER_ID ? 'selected="selected"' : ''?>><?= USER_ID == $user['id'] ? Yii::t('x', 'Me').' ('.$user['name'].')' : $user['name'] ?> (<?= strstr($user['email'], '@', true) ?>)</option>
                                <? } } ?>
                            </select>
                        </div>

                        <div>
                            <button type="submit" class="action-save-post btn btn-primary">
                                <span class="text-save-changes"><?= Yii::t('x', 'Save changes') ?></span>
                                <span class="d-none text-saving-changes"><?= Yii::t('x', 'Saving changes...') ?></span>
                            </button>
                            <a class="pull-right text-warning" href="/messages/r/<?= $thePost['id'] ?>?old"><?= Yii::t('x', 'Use old form?') ?></a>
                            <!-- <a class="pull-right action-cancel-post text-danger" href="/posts/<?= $thePost['id'] ?>"><?= Yii::t('x', 'Cancel post') ?></a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-none" id="preview_template">
        <div class="file-row">
            <div class="preview"><img data-dz-thumbnail src="/assets/img/placeholder.jpg" style="width:64px; height:64px;"></div>
            <div>
                <div>
                    <span class="name font-weight-semibold" data-dz-name></span>
                    &mdash;
                    <small class="size text-muted" data-dz-size></small>
                    <small class="pull-right text-info"><?= Yii::t('x', 'just now') ?></small>
                </div>

                <strong class="error text-danger" data-dz-errormessage></strong>

                <div>
                    <a data-dz-remove class="text-warning cancel" href="#">
                        <i class="fa fa-ban"></i>
                        <span><?= Yii::t('x', 'Cancel upload') ?></span>
                    </a>
                    <a data-dz-remove class="text-danger delete" href="#">
                        <i class="fa fa-trash-o"></i>
                        <span><?= Yii::t('x', 'Delete') ?></span>
                    </a>
                </div>

                <div class="progress" style="height:0.375rem;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width:0%;" data-dz-uploadprogress></div>
                </div>
            </div>
        </div>
    </div>

</div>
<style>
   /* Mimic table appearance */
    div.table {
      display: table;
    }
    div.table .file-row {
      display: table-row;
    }
    div.table .file-row > div {
        display: table-cell;
        vertical-align: top;
        border-bottom: 2px solid #fff;
        padding:8px;
    }
    div.table .file-row:nth-child(odd) {
        -background: #f9f9f9;
    }

    div.table#previews .file-row {
        background-color:#e3f1ff;
    }
    div.table#uploaded-previews .file-row {
        background-color:#f6f6f6;
    }

    /* The total progress gets shown by event listeners */
    #total-progress {
      opacity: 0;
      transition: opacity 0.3s linear;
    }

    /* Hide the progress bar when finished */
    #previews .file-row.dz-success .progress {
      opacity: 0;
      transition: opacity 0.3s linear;
    }

    /* Hide the delete button initially */
    #previews .file-row .delete {
        display: none;
    }

    /* Hide the start and cancel buttons and show the delete button */

    #previews .file-row.dz-success .start,
    #previews .file-row.dz-success .cancel {
        display: none;
    }
    #previews .file-row.dz-success .delete {
        display: initial;
    }
    div.preview {width:80px;}

    .cke_textarea_inline.cke_editable.cke_editable_inline {padding:16px; border:1px solid #ccc;}
</style>

<?php
$js = <<<'TXT'
CKEDITOR.plugins.addExternal( 'autosave', '/assets/ckeditor_4.11.1/plugins/autosave/', 'plugin.js?xv' );
CKEDITOR.config.autosave = { 
    SaveKey : 'autosave_{$user}_' + location.href + $('#' + editor1.name).attr('name'),
    NotOlderThen : 1440,
    saveOnDestroy : false,
    saveDetectionSelectors : "button[type='submit']",
    messageType : "statusbar",
    delay : 20,
    diffType : "inline",
    autoLoad: true
};
CKEDITOR.replace('editor1', {
    language: '{$lang}',
    toolbarCanCollapse: true,
    toolbarStartupExpanded: false,
    allowedContent: 'h1 h2 h3 h4 h5 h6 p figure figcaption hr dd dt sub sup iframe embed table thead tbody tfoot tr th td span strong em s a i u ul ol li img blockquote[*]{*}(*);',
    disallowedContent: '*{font*}',
    contentsCss: '/assets/css/style_ckeditor.css',
    entities: false,
    entities_greek: false,
    entities_latin: false,
    // plugins: 'mentions,emoji,basicstyles,undo,link,wysiwygarea,toolbar',
    extraPlugins: 'autogrow,magicline,tableresize,widget,autosave,autocomplete,mentions',
    placeholder: 'Post your reply here',
    height: 300,

    autoGrow_onStartup: true,
    autoGrow_minHeight: 200,
    autoGrow_maxHeight: 600,
    autoGrow_bottomSpace: 50,

    $toolbarConfig
    mentions: [
        {
            feed: dataFeed,
            itemTemplate: '<li data-id="{id}">' +
                '<img class="rounded-circle d-inline mr-1" src="{image}" style="float:left; width:24px; height:24px; margin-right:2px;">' +
                '<strong class="username">{name}</strong><br><span class="text-muted">{email}</span>' +
                '</li>',
            outputTemplate: '<a href="/mentions/{id}?mention=user">{name}</a>',
            minChars: 0
        },
        {
            feed: tags,
            marker: '#',
            itemTemplate: '<li data-id="{id}"><strong>{name}</strong></li>',
            outputTemplate: '<a href="#" class="tag" style="color:purple">{name}</a> ',
            minChars: 1
        }
    ]
});


function dataFeed(opts, callback) {
    var matchProperty = 'search',
        data = users.filter(function(item) {
            return item[matchProperty].indexOf(opts.query.toLowerCase()) >= 0;
        });

    data = data.sort(function(a, b) {
        return a[matchProperty].localeCompare(b[matchProperty], undefined, {
            sensitivity: 'accent'
        });
    });

    callback(data);
}

$('.action-save-post').on('click', function(e){
    if ($(this).hasClass('disabled')) {
        return false;
    }
    $(this).addClass('disabled')
    $('.text-save-changes').addClass('d-none')
    $('.text-saving-changes').removeClass('d-none')

    var jqxhr = $.post( "/posts/ajax?action=save-post&xh", {
        post_id: post_id,
        post_uid: post_uid,
        post_thread_id: post_thread_id,
        post_rtype: post_rtype,
        post_rid: post_rid,

        post_title: $('#title').val(),
        post_body: CKEDITOR.instances.editor1.getData(),
        post_replyto: $('#replyto').val(),

        post_attachments: files,
        post_remove_attachments: delfiles,
    })
    .done(function(data) {
        if (data.redir) {
            location.href = "/" + data.redir
        } else {
            alert( "Error saving data" );
        }
    })
    .fail(function() {
        alert( "Error saving data" );
    })
    .always(function() {
        $('.action-save-post.disabled').removeClass('disabled')
        $('.text-save-changes').removeClass('d-none')
        $('.text-saving-changes').addClass('d-none')
    });
})

autosize($('#title'));

var previewTemplate = $('#preview_template').html();

var delfiles = [];
var files = [];

$("div#mydzfiles").dropzone({
    url: "/posts/ajax?action=upload&xh",
    maxFilesize: 200,
    thumbnailWidth: 64,
    thumbnailHeight: 64,
    parallelUploads: 20,
    uploadMultiple: true,
    previewTemplate: previewTemplate,
    previewsContainer: "#previews",
    clickable: "#mydzfiles",
    params: {
        uid: post_uid
    },
    init: function() {
        this.on("addedfile", function(file) {
            // If file with same name has been uploaded, deny it
            if (files.indexOf(file.name) >= 0) {
               this.removeFile(file)
               alert('A file with the same name has been uploaded: ' + file.name)
            } else {
                $('.action-save-post').addClass('disabled')
                $('.text-save-changes').addClass('d-none')
                $('.text-saving-changes').removeClass('d-none')
            }
        });
        this.on("success", function(file, response) {
            // alert(response.message);
            files.push(file.name)
            console.log('File uploaded: ' + file.name)
            console.log(files)
        });
        this.on("canceled", function(file) {
            var pos = files.indexOf(file.name)
            if (pos >= 0) {
               files.splice(pos, 1)
               console.log('File removed: ' + file.name)
               console.log(files)
            }
        });
        this.on("complete", function(file, response) {
            $('.action-save-post.disabled').removeClass('disabled')
            $('.text-save-changes').removeClass('d-none')
            $('.text-saving-changes').addClass('d-none')
        });
    }
});


$('.action-remove-file').on('click', function(e){
    e.preventDefault()
    var file_id = $(this).data('file_id')
    delfiles.push(file_id)
    $(this).hide()
    $(this).parent().find('.div-unremove-file').show()
    console.log(delfiles)
})

$('.action-unremove-file').on('click', function(e){
    e.preventDefault()
    var file_id = $(this).data('file_id')
    var pos = delfiles.indexOf(file_id)
    if (pos >= 0) {
       delfiles.splice(pos, 1)
    }
    $(this).parent().hide()
    $(this).parent().parent().find('.action-remove-file').show()
    console.log(delfiles)
})

$('.select2').select2()

TXT;
$toolbarConfig = <<<'TXT'
    toolbar: [
        // { name: 'document', items: ['Source'] },
        { name: 'styles', items: [ 'Format'] },
        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike','RemoveFormat' ] },
        { name: 'clipboard', items: ['Undo', 'Redo', 'PasteText', 'PasteFromWord' ] },
        { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight'] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        { name: 'links', items: [ 'Link', 'Unlink'] },
        { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Iframe' ] },
        { name: 'tools', items: [ 'Maximize'] }
    ],
TXT;
$js = str_replace(['{$lang}', '{$user}', '{$el}', '$toolbarConfig'], [\Yii::$app->language, USER_ID, '$el', $toolbarConfig], $js);

$this->registerJs($js);

$this->registerJsFile('https://cdn.ckeditor.com/4.11.1/full-all/ckeditor.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdn.ckeditor.com/4.11.1/full-all/adapters/jquery.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/autosize.js/4.0.2/autosize.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js', ['depends'=>'yii\web\JqueryAsset']);
