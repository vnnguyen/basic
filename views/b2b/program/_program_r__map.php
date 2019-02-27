<?
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;


$programMap = '';
$files = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@webroot').'/upload/products/'.$theProgram['id']);
foreach ($files as $file) {
    $name = str_replace(Yii::getAlias('@webroot').'/upload/products/'.$theProgram['id'].'/', '', $file);
    if (substr($name, 0, 4) == 'map/') {
        $programMap = str_replace(Yii::getAlias('@webroot'), '', $file);
    } else {
        $programFiles[] = str_replace(Yii::getAlias('@webroot'), '', $file);
    }
}

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.0/plupload.min.js', ['depends'=>'yii\web\JqueryAsset']);
// $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.0/i18n/vi.js', ['depends'=>'yii\web\JqueryAsset']);
?>
<div id="upload-map-container"></div>
<div id="upload-files-container"></div>

<div id="section-map" class="section section-map mb-20">
    <div class="section-header">
        <p class="section-title">
            <? if (in_array(USER_ID, [1, $theProgram['created_by'], $theProgram['updated_by']])) { ?>
            <a href="#" style="<?= $programMap == '' ? '' : 'display:none' ?>" class="pull-right" id="action-upload-map"><?= Yii::t('app', 'Upload') ?></a>
            <a href="#" style="<?= $programMap == '' ? 'display:none' : '' ?>" class="pull-right text-danger" id="action-remove-map"><?= Yii::t('app', 'Remove') ?></a>
            <? } ?>
            <span class="text-bold text-uppercase"><?= Yii::t('p', 'Map') ?></span>
        </p>
    </div>
    <div class="section-body">
        <div id="upload-map-list">
            <? if ($programMap != '') { ?>
            <a href="<?= $programMap ?>" target="_blank"><img class="img-thumbnail img-responsive" src="/timthumb.php?h=400&src=<?= $programMap ?>" alt="Map"></a>
            <? } ?>
        </div>
        <div id="upload-map-console" class="text-danger"></div>
    </div>
</div>

<div id="section-files" class="section section-files mb-20">
    <div class="section-header">
        <? if (in_array(USER_ID, [1, $theProgram['created_by'], $theProgram['updated_by']])) { ?>
        <?= Html::a(Yii::t('p', 'Upload'), '#', ['class'=>'pull-right', 'id'=>'action-upload-files']) ?>
        <? } ?>
        <p class="text-bold text-uppercase"><?= Yii::t('p', 'Attachments') ?></p>
    </div>
    <div class="section-body">
        <div id="upload-files-list">
<?
if (!empty($programFiles)) {
    foreach ($programFiles as $file) { ?>
            <div class="file-list-item"><?
        $fileName = urlencode(substr(strrchr($file, "/"), 1));
        $fileExt = strtolower(substr(strrchr($fileName, "."), 1));
        if ($fileExt == 'pdf') {
            echo '<i class="fa fa-fw fa-file-pdf-o"></i> ';
        } elseif (in_array($fileExt, ['jpg', 'jpeg', 'webm', 'png', 'gif', 'bmp'])) {
            echo '<i class="fa fa-fw fa-file-image-o"></i> ';
        } elseif (in_array($fileExt, ['doc', 'docx', 'docm'])) {
            echo '<i class="fa fa-fw fa-file-word-o"></i> ';
        } elseif (in_array($fileExt, ['xls', 'xlsx', 'xlsm', 'xlsb'])) {
            echo '<i class="fa fa-fw fa-file-excel-o"></i> ';
        } else {
            echo '<i class="fa fa-fw fa-file-text-o"></i> ';
        }
        echo Html::a(urldecode($fileName), Yii::getAlias('@www').$file, ['title'=>'View', 'target'=>'_blank']);
        // echo Html::a(urldecode($fileName), 'https://docs.google.com/viewer?url='.Yii::getAlias('@www').$file, ['title'=>'View', 'target'=>'_blank']);
        // echo ' ', Html::a('<i class="fa fa-download"></i>', $file, ['class'=>'text-muted', 'title'=>'Download']);
        echo ' ', '<i title="Remove file" data-filename="'.substr(strrchr($file, "/"), 1).'" class="fa fa-trash-o cursor-pointer text-danger"></i>';
        ?>
            </div><?

    }
}
?>
        </div>
        <div id="upload-files-console" class="text-danger"></div>
    </div>
</div>
<style type="text/css">
.moxie-shim.moxie-shim-html5 {top:0!important; left:0!important;}
</style>
<?
$js = <<<'TXT'
// Custom example logic
var uploader = new plupload.Uploader({
    runtimes : 'html5,flash',
    browse_button : 'action-upload-map',
    container: document.getElementById('upload-map-container'),
    url : '/b2b/programs/upload-handler/' + product_id + '?action=upload-map',
    flash_swf_url : 'https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.0/Moxie.swf',
    unique_names: true,
    multi_selection: false,
    // drop_element: 'upload-map-list',

    // multipart_params: {
    //     action: 'upload_map',
    //     product_id: product_id,
    // },

    filters : {
        max_file_size : '10mb',
        prevent_duplicates: true,
        mime_types: [
            {title : "Image files", extensions : "jpg,gif,png"},
        ]
    },

    init: {
        FilesAdded: function(up, files) {
            plupload.each(files, function(file) {
                $('#upload-map-list').append ('<div id="' + file.id + '">' + file.name + ' <span class="text-muted">' + plupload.formatSize(file.size) + '</span> <strong></strong></div>');
            });
            uploader.start();
        },

        UploadProgress: function(up, file) {
            $('#' + file.id).find('strong').html(file.percent + '%');
        },

        FileUploaded: function(up, file, res) {
            $('#' + file.id).find('strong').empty();
            $('#' + file.id).find('.cancel-file-upload').remove();
            $('#upload-map-list').html('<a href="/upload/products/' + product_id +'/map/' + file.name + '" target="_blank"><img class="img-responsive img-thumbnail" src="/timthumb.php?h=400&src=/upload/products/'+ product_id +'/map/' + file.name + '"></a>');
            $('#action-upload-map').hide();
            $('#action-remove-map').show();
        },

        Error: function(up, err) {
            $('#upload-map-console').append('<div>Error #' + err.code + ': ' + err.message);
        }
    }
});

uploader.init();

$(document).on('click', '.cancel-file-upload', function(e) {
    uploader.removeFile($(this).data('file'))
    $(this).parent().remove();
})

$('#action-upload-map').on('click', function(e){
    e.preventDefault();
    // uploader.start()    
})
$('#action-remove-map').on('click', function(e){
    if (!confirm('Remove map?')) {
        return false;
    }
    e.preventDefault();
    var jqxhr = $.ajax({
        url: '/b2b/programs/ajax',
        type: 'post',
        data: {
            action: 'remove-map',
            product_id: product_id,
        },
        dataType: 'json'
    }).
    done(function(data) {
        $('#upload-map-list').empty()
        $('#action-upload-map').show()
        $('#action-remove-map').hide()
    })
    .fail(function(data) {
        alert('Request failed! Please try again.');
    })
})

// Other files
var uploader2 = new plupload.Uploader({
    runtimes : 'html5,flash',
    browse_button : 'action-upload-files',
    container: document.getElementById('upload-files-container'),
    url : '/b2b/programs/upload-handler/' + product_id + '?action=upload-files',
    flash_swf_url : 'https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.0/Moxie.swf',
    unique_names: true,
    drop_element: 'upload-files-list',

    // multipart_params: {
    //     action: 'upload_map',
    //     product_id: product_id,
    // },

    filters : {
        max_file_size : '50mb',
        prevent_duplicates: true,
        mime_types: [
            {title : "Image files", extensions : "jpg,gif,png,bmp,webm"},
            {title : "Document files", extensions : "doc,docx,docm,pdf,xls,xlsx,xlsm,xlsb,txt,odt"},
            {title : "Archive files", extensions : "zip,rar,tar,gz,7z"},
            {title : "Video files", extensions : "mp4,mov,3gp"},
        ]
    },

    init: {
        FilesAdded: function(up, files) {
            plupload.each(files, function(file) {
                $('#upload-files-list').append ('<div id="' + file.id + '">' + file.name + ' <span class="text-muted">' + plupload.formatSize(file.size) + '</span> <strong></strong></div>');
                $('#' + file.id).append(' <a class="cancel-files-upload text-danger" href="javascript:;" data-id="'+ file.id +'">Cancel</a>');
            });
            uploader2.start();
        },

        UploadProgress: function(up, file) {
            $('#' + file.id).find('strong').html(file.percent + '%');
        },

        FileUploaded: function(up, file, res) {
            $('#' + file.id).remove();
            // $('#' + file.id).find('strong').empty();
            // $('#' + file.id).find('.cancel-files-upload').remove();
            $('#upload-files-list').append('<div class="file-list-item"><i class="fa fa-fw fa-file-text-o"></i> <a href="/upload/products/'+product_id+'/'+file.name+'">'+ file.name +'</a> <i title="Remove file" data-filename="' + file.name + '" class="fa fa-trash-o cursor-pointer text-danger"></i></div>');
        },

        Error: function(up, err) {
            $('#upload-files-console').append('<div>Error #' + err.code + ': ' + err.message);
        }
    }
});

uploader2.init();

$(document).on('click', '.cancel-files-upload', function(e) {
    uploader2.removeFile($(this).data('file'))
    $(this).parent().remove();
})

// Remove uploaded files
$('#upload-files-list').on('click', '.file-list-item i.fa-trash-o', function(){
    if (!confirm('Remove file?')) {
        return false;
    }
    var item = $(this).parents('.file-list-item')
    var jqxhr = $.ajax({
        url: '/b2b/programs/ajax',
        type: 'post',
        data: {
            action: 'remove-file',
            file_name: $(this).data('filename'),
            product_id: product_id,
        },
        dataType: 'json'
    }).
    done(function(data) {
        item.remove()
    })
    .fail(function(data) {
        alert('Request failed! Please try again.');
    })
})

TXT;

if (in_array(USER_ID, [1, $theProgram['created_by'], $theProgram['updated_by']])) {
    $this->registerJs($js);
}
