<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
$this->registerCssFile('/css/plugins/fileupload/jquery.fileupload-ui.css');
$this->registerCss('
    .panel {padding-top: 10px}
    #avatar { margin-bottom: 10px; position: relative; }
    #avatar:not(.img-fill) {
        border: 1px solid #ccc;
        background: #f2f2f2;
    }
    a.fileinput-button {display: inline-block; position: relative;}
    .attach-input{
        cursor:pointer;
        opacity: 0;
        display: block;
        height: 100%;
        opacity: 0;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;}
    #upload-avatar {
        cursor:pointer;
        display: block;
        height: 100%;
        left: 0;
        opacity: 0;
        position: absolute;
        top: 0;
        width: 100%;
    }
    .not_thumb {
        display: block;
        width: 80px;
        height: 60px;
        background: #6666;
        color: #fff;
        text-align: center;
        line-height: 60px;
        font-size: 30px;
    }
    tr td:first-child{
        width: 100px;
    }
');
?>
<div class="col-md-8">
    <div class="panel">
        <div class="panel-body">
            <?php
            $form = ActiveForm::begin([
                'id' => 'noteForm',
                'options' => [
                    'enctype' => 'multipart/form-data',
                ],
            ]);
            ?>
                <?php if ($action == 'update'): ?>
                    <input type="hidden" name="remove_ids" value="">
                <?php endif ?>
                <div class="col-md-9">
                    <?= $form->field($model, 'title')->textInput()?>
                </div>

                <div class="pull-right col-md-3">
                    <div id="avatar">
                        <img class="img-responsive img-avat" src="<?= $model->avatar == '' ? 'http://placehold.it/300x300&text=NO+IMAGE' : $model->avatar ?>" alt="Image">
                        <input type="hidden" name="img-avatar" value="<?= $model->avatar != ''? $model->avatar: ''?>">
                        <input id="upload-avatar" type="file" name="files[]" data-url="/fileupload/index">
                    </div>
                </div>
                <div class="col-md-9">
                    <?= $form->field($model, 'body')->textarea([
                        'rows' => 5
                    ]) ?>
                </div>
                <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                <div class="row fileupload-buttonbar">
                    <div class="col-md-12">
                        <a class="fileinput-button">
                                + attach file
                                <input  id= "at_input" class="attach-input" type="file" name="files[]" multiple>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                    <!-- The global progress state -->
                    <div class="fileupload-progress fade">
                        <!-- The global progress bar -->
                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                        </div>
                        <!-- The extended global progress state -->
                        <div class="progress-extended">&nbsp;</div>
                    </div>
                </div>
                <!-- The table listing the files available for upload/download -->
                <table role="presentation" class="table table-striped"><tbody class="files">
                    <?php if ($action == 'update' && isset($model['files'])): ?>
                        <?php foreach ($model['files'] as $key => $file): ?>
                            <tr class="template-download in" data-id="<?= $file['id']?>">
                                <td><span class="preview <?= ($file['thumbnail_url'] == '')? 'not_thumb': ''?>"><?= ($file['thumbnail_url'] == '')? pathinfo($file['url'])['extension']: ''?>
                                    <a data-gallery="" href="<?= $file['url']?>">
                                    <img src="<?= $file['thumbnail_url'] ?>">
                                    </a></span>
                                </td>
                                <td>
                                    <p class="name">
                                        <a data-gallery="" href="http://amica.dev/files/34718/ucjes1t5rmlrn5p0m101ls13s0/Hydrangeas.jpg"><?= $file['name'].$file['ext'] ?>
                                        </a>
                                        <br>
                                        <span class="size"><?= $file['size']/1000 ?> KB</span>
                                    </p>
                                </td>
                                <td><a class="delete" data-type="DELETE" data-url="<?= $file['delete_url'] //.'&id='.$file['id'] ?>">Delete</a>
                                </td>

                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>

                </tbody></table>

                <?= Html::submitButton('Save', ['class'=>'btn btn-primary pull-right']) ?>
            <?php ActiveForm::end();?>
        </div>
    </div>
</div>


<?php
$jsText = <<<TXT
$(function () {
    'use strict';
    var remove_ids = [];
    $('.template-download').find('.delete').click(function(){
        var that = $(this),
            tr = $(that).closest('tr'),
            id = $(that).closest('tr').data('id');
            remove_ids.push(id);
            $('input[name="remove_ids"]').val(remove_ids.toString());
    });
    // Initialize the jQuery File Upload widget:
    $('#at_input').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '/fileupload/index/',
        filesContainer: $('table .files'),
	    uploadTemplateId: null,
	    downloadTemplateId: null,
        autoUpload: true,
        progressall: function (e, data) {
            var progress = parseInt(data.percent * 100, 10);
            $(e.currentTarget).find('.progress .bar').css(
                'width',
                progress + '%'
            );
        },
        progressServerRate: 0.3,
	    uploadTemplate: function (o) {
	        var rows = $();
	        $.each(o.files, function (index, file) {
	            var row = $('<tr class="template-upload ">' +
	                '<td><span class="preview"></span></td>' +
	                '<td><p class="name"></p>' +
	                '<div class="error"></div>' +
	                '</td>' +
	                '<td><p class="size"></p>' +
	                '<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"> <div class="progress-bar progress-bar-success" style="width:0%;"></div> </div>' +
	                '</td>' +
	                '<td>' +
	                (!index && !o.options.autoUpload ?
	                    '<button class="start" disabled>Start</button>' : '') +
	                (!index ? '<button class="cancel">Cancel</button>' : '') +
	                '</td>' +
	                '</tr>');
	            row.find('.name').text(file.name);
	            row.find('.size').text(o.formatFileSize(file.size));
	            if (file.error) {
	                row.find('.error').text(file.error);
	            }
	            rows = rows.add(row);
	        });
	        return rows;
	    },
	    downloadTemplate: function (o) {
	        var rows = $();
	        $.each(o.files, function (index, file) {
	            var row = $('<tr class="template-download">' +
	                '<td><span class="preview"><input type="hidden" name="tmp_thubnail_url[]" value=""></span></td>' +
	                '<td><p class="name"><input type="hidden" name="tmp_attach_url[]" value=""></p>' +
	                (file.error ? '<div class="error"></div>' : '') +
                    // '<span class="size"></span>' +
	                '</td>' +
	                '<td><a class="delete">Delete<input type="hidden" name="delete_url[]" value=""></a></td>' +
	                '</tr>');
	            row.find('.size').text(o.formatFileSize(file.size));
	            if (file.error) {
	                row.find('.name').text(file.name);
	                row.find('.error').text(file.error);
	            } else {
	                row.find('.name').append($('<a></a>').text(file.name));
                    row.find('.name').append($('<br/>'));
                    row.find('.name').append($('<span></span>').text(o.formatFileSize(file.size)));
	                if (file.thumbnailUrl) {
	                    row.find('.preview').append(
	                        $('<a></a>').append(
	                             $('<img>').prop('src', file.thumbnailUrl)
	                       )
	                    );
                        row.find('.preview input[type="hidden"]').val(file.thumbnailUrl);
	                }
                    if (file.thumbnailUrl == undefined) {
                        // var re = /(?:\.([^.]+))?$/;
                        // var ext = re.exec(file.name);
                        // console.log();
                        row.find('.preview').addClass('not_thumb').text(getFileExtension(file.name));
                    }
                    
                    row.find('p.name input[type="hidden"]').val(file.url);
	                row.find('.name a')
	                    .attr('data-gallery', '')
	                    .prop('href', file.url);
	                row.find('.delete')
	                    .attr('data-type', file.deleteType)
	                    .attr('data-url', file.deleteUrl);
                    row.find('.delete input[type="hidden"]').val(file.deleteUrl);
	            }
	            rows = rows.add(row);
	        });
	        return rows;
	    }

	});
});

var getFileExtension = function (url) {
    "use strict";
    if (url === null) {
        return "";
    }
    var index = url.lastIndexOf("/");
    if (index !== -1) {
        url = url.substring(index + 1); // Keep path without its segments
    }
    index = url.indexOf("?");
    if (index !== -1) {
        url = url.substring(0, index); // Remove query
    }
    index = url.indexOf("#");
    if (index !== -1) {
        url = url.substring(0, index); // Remove fragment
    }
    index = url.lastIndexOf(".");
    return index !== -1
        ? url.substring(index + 1) // Only keep file extension
        : ""; // No extension found
};


// $(function () {
//     'use strict';
//     // console.log( window.location.hostname); return false;
//     // Change this to the location of your server-side upload handler:
//     var url = '/fileupload/index',
// 		deleteButton = $('<button/>')
//             .addClass('btn btn-danger delete')
//             .prop('data-type', 'DELETE')
//             .prop('data-url', '')
//             .text('Delete')
//             .on('click', function () {
//                 var that = $(this),
//                     data = that.data();
//                 data.submit().always(function () {
//                 });
//                 return false;
//             }),
//          uploadButton = $('<button/>')
//             .addClass('btn btn-primary')
//             .prop('disabled', true)
//             .text('Processing...')
//             .on('click', function () {
//                 var that = $(this),
//                     data = that.data();
//                 that
//                     .off('click')
//                     .text('Abort')
//                     .on('click', function () {
//                         that.remove();
//                         data.abort();
//                     });
//                 data.submit().always(function () {
//                     that.remove();
//                 });
//                 return false;
//             });
//     // console.log( url); return false;
//     $('#fileupload').fileupload({
//         url: url,
//         dataType: 'json',
//         // autoUpload: false,
//         
//         maxFileSize: 999000,
//         // Enable image resizing, except for Android and Opera,
//         // which actually support image resizing, but fail to
//         // send Blob objects via XHR requests:
//         disableImageResize: /Android(?!.*Chrome)|Opera/
//             .test(window.navigator.userAgent),
//         previewMaxWidth: 100,
//         previewMaxHeight: 100,
//         previewCrop: true
//     }).on('fileuploadadd', function (e, data) {
//         data.context = $('<div/>').appendTo('#files');
//         $.each(data.files, function (index, file) {
//             var node = $('<p/>')
//                     .append($('<span/>').text(file.name))
//                     .append(deleteButton.clone(true));
//             node.appendTo(data.context);
//         });
//     }).on('fileuploadprocessalways', function (e, data) {
//         var index = data.index,
//             file = data.files[index],
//             node = $(data.context.children()[index]);
//         if (file.preview) {
//             node
//                 .prepend('<br>')
//                 .prepend(file.preview);
//         }
//         if (file.error) {
//             node
//                 .append('<br>')
//                 .append($('<span class="text-danger"/>').text(file.error));
//         }
//         if (index + 1 === data.files.length) {
//             data.context.find('button')
//                 .text('Upload')
//                 .prop('disabled', !!data.files.error);
//         }
//     }).on('fileuploadprogressall', function (e, data) {
//         var progress = parseInt(data.loaded / data.total * 100, 10);
//         $('#progress .progress-bar').css(
//             'width',
//             progress + '%'
//         );
//     }).on('fileuploaddone', function (e, data) {
//         $.each(data.result.files, function (index, file) {
//             if (file.url) {
//                 var link = $('<a>')
//                     .attr('target', '_blank')
//                     .prop('href', file.url);
//                 $(data.context.children()[index])
//                     .wrap(link);
//             } else if (file.error) {
//                 var error = $('<span class="text-danger"/>').text(file.error);
//                 $(data.context.children()[index])
//                     .append('<br>')
//                     .append(error);
//             }
//         });
//     }).on('fileuploadfail', function (e, data) {
//         $.each(data.files, function (index) {
//             var error = $('<span class="text-danger"/>').text('File upload failed.');
//             $(data.context.children()[index])
//                 .append('<br>')
//                 .append(error);
//         });
//     }).prop('disabled', !$.support.fileInput)
//         .parent().addClass($.support.fileInput ? undefined : 'disabled');
// });
$(function () {
    $('#upload-avatar').fileupload({
        filesContainer: $('#avatar'),
        dataType: 'json',
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        disableImageResize: false,
        imageMaxWidth: 800,
        imageMaxHeight: 800,
        imageCrop: true, // Force cropped images
        autoUpload: true,
        imageOrientation: true,
        done: function (e, data) {
            var file = data.result.files[0];
            console.log(file);
            $('#avatar').find('img.img-avat').prop('src', file.url);
            $('#avatar').find('[name="img-avatar"]').val(file.url);
        }
    }).on('fileuploadadd', function (e, data) {
            var file    = data.files[0];
            var reader = new FileReader();

            reader.addEventListener("load", function () {
                $('#avatar').find('img.img-avat').prop('src', reader.result);
            }, false);
            // Read in the image file as a data URL.
            reader.readAsDataURL(file);
            // return false;
        });
    //$('#fileupload').fileupload({autoUpload:true});
});
TXT;
$this->registerJsFile('/js/plugins/fileupload/vendor/jquery.ui.widget.js', ['depends'=>'app\assets\MainAsset']);
// $this->registerJsFile('//blueimp.github.io/JavaScript-Templates/js/tmpl.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js', ['depends'=>'app\assets\MainAsset']);

$this->registerJsFile('//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('/js/plugins/fileupload/jquery.iframe-transport.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('/js/plugins/fileupload/jquery.fileupload.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('/js/plugins/fileupload/jquery.fileupload-process.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('/js/plugins/fileupload/jquery.fileupload-image.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('/js/plugins/fileupload/jquery.fileupload-audio.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('/js/plugins/fileupload/jquery.fileupload-validate.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('/js/plugins/fileupload/jquery.fileupload-ui.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($jsText);
?>