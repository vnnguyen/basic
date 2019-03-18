<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;

$replyToIdList = [];

if (isset($theCase)) {
    if (isset($theCase['owner_id'])) {
        $replyToIdList[] = $theCase['owner_id'];
    }
    if ($theCase['cofr'] !=0 ) {
        $replyToIdList[] = $theCase['cofr'];
    }
}

if (isset($theTour)) {
    foreach ($theTour['bookings'] as $booking) {
        if (USER_ID != $booking['case']['owner_id']) {
            $replyToIdList[] = $booking['case']['owner']['id'];
        }
    }
    foreach ($theTour['tour']['operators'] as $user) {
        if (USER_ID != $user['id']) {
            $replyToIdList[] = $user['id'];
        }
    }
    foreach ($theTour['tour']['cskh'] as $user) {
        if (USER_ID != $user['id']) {
            $replyToIdList[] = $user['id'];
        }
    }
}
$theUsers = \app\models\User::find()
    ->select(['users.id', 'users.name', 'users.email', 'users.image', 'contact_id'])
    ->where(['users.status'=>'on'])
    ->innerJoinWith('contact')
    ->orderBy('contacts.lname, contacts.fname')
    ->asArray()
    ->all();

$uid = Yii::$app->security->generateRandomString(10).'_'.time().'_'.USER_ID;

?>
<script>
var
page_save_key = 'autosave_<?= USER_ID ?>_<?= md5(URI) ?>',
language = '<?= Yii::$app->language ?>',
post_id = 0,
post_uid = '<?= $uid ?>',
post_rtype = '<?= isset($theTour) ? 'tour' :  'case' ?>',
post_rid = <?= isset($theTour) ? $theTour['tour']['id'] : $theCase['id'] ?>,
post_thread_id = 0,
post_title = '',
post_body = '',

users = [
    <?php foreach ($theUsers as $i=>$user) { ?>
    <?= $i == 0 ? '' : ', ' ?>{id: <?= $user['id'] ?>, name: '<?= $user['name'] ?>', email: '<?= strstr($user['email'], '@', true) ?>', image: '/timthumb.php?w=100&h=100&src=<?= $user['image'] ?>', search: '<?= strtolower(Inflector::transliterate(str_replace([' ', '-'], ['', ''], $user['name'].strstr($user['email'], '@', true)))) ?>'}
    <?php } ?>
],

tags = [
    'important',
    'urgent',
    'client',
    'status',
    'feedback',
];

function cancelAllPosts() {
    if ($('#div-click-to-post').hasClass('d-none')) {
        $('#div-click-to-post, #div-post-here').toggleClass('d-none')
    }
    $('#post-form').addClass('d-none')
    $('.action-reply-post.d-none').removeClass('d-none')
    if (CKEDITOR.instances.editor1) {
        CKEDITOR.instances.editor1.destroy()
    }
    $('.action-reply-post.replying').removeClass('replying')
    $('.you-are-replying').addClass('d-none')
}
</script>
<style type="text/css">
.replying {font-weight:bold;}
.select2.select2-container {width:100%; display:block;}
</style>
<div id="div-click-to-post" class="text-center cursor-pointer" style="height:64px; border-radius:5px; background-color:#eee; line-height:64px;">
    <?= Yii::t('x', 'Click here to post') ?>
</div>
<div id="div-post-here" class="d-none"></div>

<?php echo $this->render('//_posts/upload_preview_template'); ?>

<div id="post-form" class="message-body d-none">
    <div class="form-group">
        <textarea style="width:100%; font-size: 25px; padding:8px; border:1px solid #ccc; resize: none;" rows="1" name="title" id="title" placeholder="<?= Yii::t('x', '(No title)') ?>"></textarea>
        <div class="text-muted small"><?= Yii::t('x', 'TIP: Use tags #important or #urgent to mark your post as such') ?></div>
    </div>
    <div class="form-group">
        <textarea id="editor1" name="editor1" rows="10" class="form-control" placeholder="Post your message here"></textarea>
        <div class="text-muted small"><?= Yii::t('x', 'TIP: You can press @... to mention people in your post - They will be notified via email.') ?></div>
    </div>
    <div class="form-group" style="border:1px solid #ccc;">
        <div class="table table-striped" id="previews"></div>
        <div id="mydzfiles"><i class="fa fa-paperclip"></i> <?= Yii::t('x', 'Click or drag and drop files here to attach') ?></div>
    </div>
    <div class="form-group">
        <label><i class="fa fa-info-circle"></i> <?= Yii::t('x', 'The following people will be notified of your message') ?>: (<a href="javascript:void($('#replyto').val(null).trigger('change'));"><?= Yii::t('x', 'Clear all') ?></a>)</label>
        <select id="replyto" class="select2 form-control" data-placeholder="<?= Yii::t('x', 'Nobody') ?>" name="replyto[]" multiple>
            <?php foreach ($theUsers as $user) { /*if (in_array($user['id'], $replyToIdList)) { */ ?>
            <option value="<?=$user['id']?>" <?= $user['id'] != USER_ID && in_array($user['id'], $replyToIdList) ? 'selected="selected"' : ''?>><?= USER_ID == $user['id'] ? Yii::t('x', 'Me').' ('.$user['name'].')' : $user['name'] ?> (<?= strstr($user['email'], '@', true) ?>)</option>
            <? } /* } */ ?>
        </select>
    </div>
    <div>
        <button type="submit" class="action-save-post btn btn-primary">
            <span class="text-save-changes"><?= Yii::t('x', 'Save changes') ?></span>
            <span class="d-none text-saving-changes"><?= Yii::t('x', 'Saving changes...') ?></span>
        </button>
        <span class="action-cancel-post text-danger cursor-pointer"><?= Yii::t('x', 'Cancel') ?></span>
    </div>
</div>

<?php
$js = <<<'TXT'
CKEDITOR.plugins.addExternal( 'autosave', '/assets/ckeditor_4.11.1/plugins/autosave/', 'plugin.js?xv' );
CKEDITOR.config.autosave = { 
    SaveKey : page_save_key,
    NotOlderThen : 1440,
    saveOnDestroy : false,
    saveDetectionSelectors : "button[type='submit']",
    messageType : "statusbar",
    delay : 20,
    diffType : "inline",
    autoLoad: true
};
CKEconfig = {
    language: language,
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

    autoGrow_onStartup: true,
    autoGrow_minHeight: 200,
    autoGrow_maxHeight: 600,
    autoGrow_bottomSpace: 50,

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

    mentions: [
        {
            feed: dataFeed,
            itemTemplate: '<li data-id="{id}">' +
                '<img class="rounded-circle d-inline mr-1" src="{image}" style="float:left; width:24px; height:24px; margin-right:2px;">' +
                '<strong class="username">{name}</strong><br><span class="text-muted">{email}</span>' +
                '</li>',
            outputTemplate: '<a href="/mentions/{id}?mention=user">{name}</a>',
            minChars: 0
        }
    ]
}

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

autosize($('#title'));

// Click to post
$('#div-click-to-post').on('click', function(e){
    e.preventDefault()
    cancelAllPosts()
    post_thread_id = 0
    $('#div-click-to-post, #div-post-here').toggleClass('d-none')
    $('#post-form').removeClass('d-none').appendTo('#div-post-here')
    $('#title').closest('.form-group').show()
    $('#title').focus()
    CKEDITOR.replace('editor1', CKEconfig)
})

// Cancel post
$('.action-cancel-post').on('click', function(e){
    e.preventDefault()
    cancelAllPosts()
})


$('.action-save-post').on('click', function(e){
    if ($(this).hasClass('disabled')) {
        return false;
    }
    $(this).addClass('disabled')
    $('.text-save-changes').addClass('d-none')
    $('.text-saving-changes').removeClass('d-none')

    post_title = $('#title').val()
    post_body = CKEDITOR.instances.editor1.getData()

    var jqxhr = $.post( "/posts/ajax?action=save-post&xh", {
        post_id: post_id,
        post_uid: post_uid,
        post_thread_id: post_thread_id,
        post_rtype: post_rtype,
        post_rid: post_rid,

        post_title: post_title,
        post_body: post_body,
        post_replyto: $('#replyto').val(),

        post_attachments: files,
        post_remove_attachments: delfiles,
    })
    .done(function(data) {

        cancelAllPosts()

        if (data.redir) {
            location.href = "/" + data.redir
            return;
        }

        if (data.post_id) {
            var html = $('#hidden-zone .post-template:eq(0)').html()
            html = html.replace(/{\$post_id}/g, data.post_id)
            html = html.replace('{$post_title}', post_title)
            html = html.replace('{$post_body}', post_body)
            html = html.replace('{$post_time}', data.post_time)
            html = html.replace('{$post_time_ago}', data.post_time_ago)
            if (data.post_attachments != '') {
                html = html.replace('post-attachments mt-3 pl-3 d-none', 'post-attachments mt-3 pl-3')
                html = html.replace('{$post_attachments}', data.post_attachments)
            }
            if (data.post_tos != '') {
                html = html.replace('post-tos d-none', 'post-tos')
                html = html.replace('{$post_tos}', data.post_tos)
            }

            $('.media.div-post:eq(0)').after(html)
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

if (USER_ID == 1) {
    $js .= <<<'TXT'

TXT;
}

$this->registerJs($js);

$this->registerJsFile('https://cdn.ckeditor.com/4.11.2/full-all/ckeditor.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdn.ckeditor.com/4.11.2/full-all/adapters/jquery.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/autosize.js/4.0.2/autosize.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js', ['depends'=>'yii\web\JqueryAsset']);

