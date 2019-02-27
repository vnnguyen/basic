<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;

$ownerAtList = [];
foreach ($theTour['bookings'] as $booking) {
    if (USER_ID != $booking['case']['owner_id']) {
        $ownerAtList[$booking['case']['owner']['id']] = '@['.$booking['case']['owner']['nickname'].']';
    }
}
foreach ($theTour['tour']['operators'] as $user) {
    if (USER_ID != $user['id']) {
        $ownerAtList[$user['id']] = '@['.$user['nickname'].']';
    }
}
foreach ($theTour['tour']['cskh'] as $user) {
    if (USER_ID != $user['id']) {
        $ownerAtList[$user['id']] = '@['.$user['nickname'].']';
    }
}

$ownerAt = implode(' ', array_values($ownerAtList)).' ';

$theUsers = \app\models\User::find()
    ->select(['users.id', 'users.name', 'users.email', 'users.image', 'contact_id'])
    ->where(['users.status'=>'on'])
    ->innerJoinWith('contact')
    ->orderBy('contacts.lname, contacts.fname')
    ->asArray()
    ->all();

$replyToIdList = array_keys($ownerAtList);

$uid = Yii::$app->security->generateRandomString(10).'_'.time().'_'.USER_ID;

?>
<script>
var
page_save_key = 'autosave_<?= USER_ID ?>_<?= md5(URI) ?>',
language = '<?= Yii::$app->language ?>',
post_id = 0,
post_uid = '<?= $uid ?>',
post_rtype = 'tour',
post_rid = <?= $theTour['tour']['id'] ?>,
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
    // add by nguyen
    $('.action-u-message.updating').removeClass('updating')
    if (CKEDITOR.instances.editor1) {
        CKEDITOR.instances.editor1.setData('');
    }

    $("#replyto").val('').trigger('change');
    if ($('#uploaded-previews').length > 0) {
        $('#uploaded-previews').remove();
    }
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
<div id="post-form" class="message-body d-none">
    <div class="form-group">
        <div class="text-muted"><?= Yii::t('x', 'TIP: Use tags #important or #urgent to mark your post as such') ?></div>
        <textarea style="width:100%; font-size: 25px; padding:8px; border:1px solid #ccc; resize: none;" rows="1" name="title" id="title" placeholder="<?= Yii::t('x', '(No title)') ?>"></textarea>
        <div class="text-danger"><?= Yii::t('x', 'NOTE: You SHOULD NOT insert the tour\'s code or name before the title as this will be done automatically.') ?></div>
    </div>
    <div class="form-group">
        <div class="text-muted"><?= Yii::t('x', 'TIP: You can press @... to mention people in your post - They will be notified via email.') ?></div>
        <textarea id="editor1" name="editor1" rows="10" class="form-control" placeholder="Post your message here"></textarea>
    </div>
    <div class="form-group" style="border:1px solid #ccc;">
        <div class="table table-striped" id="previews"></div>
        <div id="mydzfiles"><?= Yii::t('x', 'Click or drag and drop files here to attach') ?></div>
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
    $('.text-saving-changes').removeClass('d-none');
    var post_replyto = Array.from(new Set($('#replyto').val()));
    var jqxhr = $.post( "/posts/ajax?action=save-post&xh", {
        post_id: post_id,
        post_uid: post_uid,
        post_thread_id: post_thread_id,
        post_rtype: post_rtype,
        post_rid: post_rid,

        post_title: $('#title').val(),
        post_body: CKEDITOR.instances.editor1.getData(),
        post_replyto: post_replyto,

        post_attachments: files,
        post_remove_attachments: delfiles,
    })
    .done(function(data) {
        if (data.redir) {
            var thePost = {
                title: 'title',
                n_id: 0,

            };
            var files = thePost.files ? thePost.files: null;
            var image_url = '';
            var post_nickname = 'Nguyen IT';
            post_uid = post_uid || 1;
            post_body = 'text body' || thePost.body;
            post_id = post_id || thePost.id;
            var html_li = '<li class="note-list-item clearfix" data-id="' + post_id + '" id="note-list-item-' + post_id + '">' +
                          '<a name="anchor-note-' + post_id + '"></a>' +
                          '<div class="note-avatar"> <a href="/users/' + post_uid + '"><img class="rounded-circle note-author-avatar" src="' + image_url + '" alt=""></a> </div>' +
                          '<div class="note-content"> <h5 class="note-heading">' +
                            '<a class="note-author-name" href="/users/' + post_uid + '"> ' + post_nickname + ' </a>:';

            if (actionPost == "edit") {//(actionPost == "edit"? "edited" : "")
                html_li += '<a class="note-title font-weight-bold" href="/posts/' + post_id + '"> ' + thePost.title + '</a> ' +
                            '<i class="fa fa-caret-right text-muted"></i>';
                var cnt = 0;
                $.each(post_replyto, function(index, recip_id){
                    $.each(users, function(idex, user){
                        if (user.id == recip_id) {
                            if (cnt > 0) {html_li += ','}
                            html_li += ' <a class="note-recipient-name text-small" href="/users/' +recip_id+ '">' + user.name +'</a>';
                            cnt++;
                            return;
                        }
                    });
                });
            } else {
                html_li += '<a class="note-title" href="/posts/' + post_id + '"> replied </a>' +
                        '<i class="fa fa-caret-left text-muted"></i>' +
                        '<a class="text-muted" href="#note-list-item-' + post_id + '"> ' + thePost.title + '</a>';
            }

            html_li += '</h5>' +
                        '<div class="mt-0">' +
                            '<span class="text-muted"> 16/5/2018 16:00 ' + (actionPost == "edit"? "edited" : "") +
                            '</span> - ' +
                            '<a class="action-u-message text-muted" data-thread_id=" ' + post_thread_id + ' ">Edit</a>' +
                            '- <a class="action-delete-post text-muted" href="/posts/' + post_id + '/d" data-id="' + post_id + '">Delete</a>' +
                        '</div>' +
            '<div class="note-file-list mt-2"><div class="note-file-list-item">';
            $.each(files, function(index, file){
                var file_href = '/attachments/'.file.id;
                html_li +=  '<a href=" ' + file_href + ' ">' + file.name + '</a><span class="text-muted">' + file.size + ' KiB</span>';
            });
            html_li += '</div></div>' +
                        '<div class="note-body mt-2"> ' + post_body + ' </div>';
            html_li += '<div class="mt-2 div-action-reply-post"><span class="">' +
                        '<img src="/timthumb.php?w=20&amp;h=20&amp;src=https://my.amicatravel.com/upload/user-files/34718/99359BD6-EAE1-42B5-B66D-CB27E1C92301.JPG" class="rounded-circle">' +
                            '<a class="action-reply-post" href="/posts/495817" data-thread_id="495817" data-reply_ids="12952,8162">Reply »</a>' +
                        '</span> </div>';

            html_li += '</li>';
            if(actionPost == "edit") {
                $(html_li).insertBefore(CurentMessage);
                $(CurentMessage).remove();
            } else {
                $(html_li).insertAfter($('#div-my-editor'));
                html_replied = '<div class="mt-2">' +
                    '<img class="rounded-circle" src="' + image_url + '" alt="">' +
                    '<a href="#anchor-note-' + post_id + '"> ' + post_nickname + ' replied </a>' +
                '</div> ';
                console.log($(CurentMessage).find('.div-action-reply-post'));
                $(html_replied).insertBefore($(CurentMessage).find('.div-action-reply-post'));
                CurentMessage = '';
            }
            $('#post-form').addClass('d-none');
            cancelAllPosts();

            // location.href = "/" + data.redir
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

$this->registerJs($js);

$this->registerJsFile('https://cdn.ckeditor.com/4.11.1/full-all/ckeditor.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdn.ckeditor.com/4.11.1/full-all/adapters/jquery.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/autosize.js/4.0.2/autosize.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js', ['depends'=>'yii\web\JqueryAsset']);

