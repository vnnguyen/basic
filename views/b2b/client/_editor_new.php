<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;

// $ownerAtList = [];
// foreach ($theTour['bookings'] as $booking) {
//     if (USER_ID != $booking['case']['owner_id']) {
//         $ownerAtList[$booking['case']['owner']['id']] = '@['.$booking['case']['owner']['nickname'].']';
//     }
// }
// foreach ($theTour['tour']['operators'] as $user) {
//     if (USER_ID != $user['id']) {
//         $ownerAtList[$user['id']] = '@['.$user['nickname'].']';
//     }
// }
// foreach ($theTour['tour']['cskh'] as $user) {
//     if (USER_ID != $user['id']) {
//         $ownerAtList[$user['id']] = '@['.$user['nickname'].']';
//     }
// }

// $ownerAt = implode(' ', array_values($ownerAtList)).' ';

// if (isset($theCase['owner_id']) && Yii::$app->user->id != $theCase['owner_id']) {
//     $ownerAt = '@['.$theCase['owner']['nickname'].'] ';
// }


$theUsers = \app\models\User::find()
    ->select(['users.id', 'users.name', 'users.email', 'users.image', 'contact_id'])
    ->where(['users.status'=>'on'])
    ->innerJoinWith('contact')
    ->orderBy('contacts.lname, contacts.fname')
    ->asArray()
    ->all();

$replyToIdList = [];

$uid = Yii::$app->security->generateRandomString(10).'_'.time().'_'.USER_ID;

?>
<script>
var
post_id = 0,
post_uid = '<?= $uid ?>',
post_rtype = 'company',
post_rid = <?= $theClient['id'] ?>,
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
</script>
<style type="text/css">
.select2.select2-container {width:100%; display:block;}
</style>
<div id="div-click-to-post" class="text-center cursor-pointer" style="height:64px; border-radius:5px; background-color:#eee; line-height:64px;">
    <?= Yii::t('x', 'Click here to post') ?>
</div>
<div id="div-post-here" class="d-none">
    <div class="message-body">
        <div class="form-group">
            <div class="text-muted"><?= Yii::t('x', 'TIP: Use tags #important or #urgent to mark your post as such') ?></div>
            <textarea style="width:100%; font-size: 25px; padding:8px; border:1px solid #ccc; resize: none;" rows="1" name="title" id="title" placeholder="<?= Yii::t('x', '(No title)') ?>"></textarea>
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
            <label><i class="fa fa-info-circle"></i> <?= Yii::t('x', 'The following people will be notified of your message') ?>:</label>
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

// Click to post
$('#div-click-to-post').on('click', function(e){
    e.preventDefault()
    $('#div-click-to-post, #div-post-here').toggleClass('d-none')
    $('#title').focus()
})

// Cancel post
$('.action-cancel-post').on('click', function(e){
    e.preventDefault()
    $('#div-click-to-post, #div-post-here').toggleClass('d-none')
})

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

