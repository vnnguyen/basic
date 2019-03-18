<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

require_once(Yii::getAlias('@webroot').'/../textile/php-textile/Parser.php');
$parser = new \Netcarver\Textile\Parser();


include('_sample-days_inc.php');

if ($theDay->isNewRecord) {
    Yii::$app->params['page_title'] = 'New sample day';
} else {
    Yii::$app->params['page_title'] = 'Edit sample day: '.$theDay['title'];
}

$files = scandir(Yii::getAlias('@webroot').'/upload/devis-days/', 1);
asort($files);
$imageList = [];
foreach ($files as $k=>$v) {
    if (substr($v, -4) == '.jpg') {
        $imageList[$v] = $v;
    }
}

$uid = 'sample-day_'.Yii::$app->security->generateRandomString(10).'_'.time().'_'.USER_ID;


?>
<?php echo $this->render('//_posts/upload_preview_template'); ?>

<script>
var
language = '<?= Yii::$app->language ?>',
post_id = 0,
post_uid = '<?= $uid ?>',
post_rtype = 'sample-day',
post_rid = <?= $theDay['id'] ?>,
post_thread_id = 0,
post_title = '',
post_body = '',
test = '';
</script>

<div class="col-md-8">
    <?php if ($theDay->isNewRecord) { ?>
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i>
        <?= Yii::t('x', 'You are creating a single day.') ?> <?= Html::a(Yii::t('x', 'Click here if you want to create a multiple-day segment.'), '?segment=yes', ['class'=>'alert-link']) ?>
    </div>
    <?php } ?>
    <?php $form = ActiveForm::begin(); ?>
    <?= Html::hiddenInput('post_uid', $uid) ?>
    <?= Html::hiddenInput('rtype', 'sample-day') ?>
    <?= Html::hiddenInput('rid', $theDay['id']) ?>
    <?= Html::hiddenInput('post_attachments', '') ?>
    <?= Html::hiddenInput('post_remove_attachments', '') ?>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2"><?= $form->field($theDay, 'language')->dropdownList($languageList)->label(Yii::t('x', 'Language')) ?></div>
                <div class="col-md-10"><?= $form->field($theDay, 'title')->label(Yii::t('x', 'Title of day')) ?></div>
            </div>
            <div class="row">
                <div class="col-md-2"><?= $form->field($theDay, 'meals')->dropdownList($dayMealList)->label(Yii::t('x', 'Meals')) ?></div>
                <div class="col-md-5"><?= $form->field($theDay, 'guides')->label(Yii::t('x', 'Tour guide')) ?></div>
                <div class="col-md-5"><?= $form->field($theDay, 'transport')->label(Yii::t('x', 'Transport')) ?></div>
            </div>
            <?= $form->field($theDay, 'body')->textArea(['rows'=>15])->label(Yii::t('x', 'Activity')) ?>
            <?= $form->field($theDay, 'summary')->textArea(['rows'=>5])->label(Yii::t('x', 'Highlight/Summary of day')) ?>

            <div class="form-group" style="border:1px solid #ccc;">
                <?php if (!empty($theDay['attachments'])) { ?>
                <div class="table table-striped" id="uploaded-previews">
                    <?php foreach ($theDay['attachments'] as $attachment) { ?>
                    <div class="file-row">
                        <div class="preview"><a href="@web/attachments/<?= $attachment['id'] ?>"><img data-dz-thumbnail src="/assets/img/placeholder.jpg" style="width:64px; height:64px;"></a></div>
                        <div>
                            <div>
                                <span class="name" data-dz-name><?= Html::a($attachment['name'], '@web/attachments/'.$attachment['id']) ?></span>
                                &mdash;
                                <small class="size text-muted" data-dz-size><strong><?= number_format($attachment['size'] / 1024, 1) ?></strong> KB</small>
                                <small class="pull-right text-muted"><?= Yii::$app->formatter->asRelativetime(strtotime('-7 hours', strtotime($attachment['created_dt']))) ?></small>
                            </div>

                            <div>
                                <a class="text-danger action-remove-file" href="#" data-file_id="<?= $attachment['id'] ?>">
                                    <i class="fa fa-trash-o"></i>
                                    <span><?= Yii::t('x', 'Delete') ?></span>
                                </a>
                                <div class="div-unremove-file" style="display:none;">
                                    <?= Yii::t('x', 'File will be removed when form is submitted.') ?> &mdash;
                                    <a href="#" class="text-info action-unremove-file" data-file_id="<?= $attachment['id'] ?>">
                                        <i class="fa fa-trash-o"></i>
                                        <span><?= Yii::t('x', 'Restore') ?></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
                <div class="table table-striped" id="previews"></div>
                <div id="mydzfiles"><i class="fa fa-paperclip"></i> <?= Yii::t('x', 'Click or drag and drop files here to attach') ?></div>
            </div>

            <?= $form->field($theDay, 'tags')->label(Yii::t('x', 'Tags')) ?>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="SampleDay[is_halfday]" name="SampleDay[is_halfday]" value="yes" <?= $theDay->is_halfday == 'yes' ? 'checked="checked"' : '' ?>>
                <label class="form-check-label" for="SampleDay[is_halfday]"><?= Yii::t('x', 'Mark this as a half-day') ?></label>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="SampleDay[is_selectable]" name="SampleDay[is_selectable]" value="no" <?= $theDay->is_selectable == 'no' ? 'checked="checked"' : '' ?>>
                <label class="form-check-label" for="SampleDay[is_selectable]"><?= Yii::t('x', 'Do not allow users to directly select and insert this day in a tour program. Only use this day in a multiple-day tour segment.') ?></label>
            </div>
            <?= $form->field($theDay, 'note')->textArea(['rows'=>5])->label(Yii::t('x', 'Note')) ?>
            <div>
                <?=Html::submitButton(Yii::t('x', 'Save changes'), ['class' => 'btn btn-primary']) ?>
                <?= Yii::t('x', 'or') ?>
                <?= Html::a(Yii::t('x', 'Cancel'), '/sample-days/'.$theDay['id']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$js = <<<TXT
// $('#sampletourday-image').change(function(){
//     var image = $(this).val();
//     $('#image-preview').html('<img src="/upload/devis-days/'+image+'" />');
// });

$('#sampleday-body').ckeditor({
    allowedContent: 'p sub sup strong em s a i u ul ol li img blockquote;',
    entities: false,
    entities_greek: false,
    entities_latin: false,
    uiColor: '#ffffff',
    height:400,
    contentsCss: '/assets/css/style_ckeditor.css'
});
$('#sampleday-summary, #sampleday-note').ckeditor({
    allowedContent: 'p sub sup strong em s a i u ul ol li img blockquote;',
    entities: false,
    entities_greek: false,
    entities_latin: false,
    uiColor: '#ffffff',
    height:200,
    contentsCss: '/assets/css/style_ckeditor.css'
});

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
            // console.log('File uploaded: ' + file.name)
            console.log(JSON.stringify(files))
            $('input[name="post_attachments"]').val(JSON.stringify(files))
        });
        this.on("canceled", function(file) {
            var pos = files.indexOf(file.name)
            if (pos >= 0) {
               files.splice(pos, 1)
               // console.log('File removed: ' + file.name)
               // console.log(files)
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
    $('input[name="post_remove_attachments"]').val(JSON.stringify(delfiles))
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
    $('input[name="post_remove_attachments"]').val(JSON.stringify(delfiles))
})
TXT;


$this->registerJs($js);

$this->registerJsFile('https://cdn.ckeditor.com/4.11.3/basic/ckeditor.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdn.ckeditor.com/4.11.3/basic/adapters/jquery.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js', ['depends'=>'yii\web\JqueryAsset']);
