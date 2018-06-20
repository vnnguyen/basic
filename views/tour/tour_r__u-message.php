<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div id="div-u-message" style="display:none">
    <div class="note-content-edit">
        <? $form = ActiveForm::begin() ?>
        <?= $form->field($theMessage, 'title') ?>
        <?= $form->field($theMessage, 'body')->textArea(['rows'=>10]) ?>
        <?= $form->field($theMessage, 'rtype')->label('Notify these people') ?>
        <?= Html::submitButton('Save changes', ['class'=>'btn btn-primary']) ?>
        or <?= Html::a('Cancel', '#', ['class'=>'cancel-u-message']) ?>
        <? ActiveForm::end(); ?>
    </div>
</div>
<?
$js = <<<'TXT'
var note = false;
$('a.u-message').on('click', function(e){
    e.preventDefault();
    var id = $(this).closest('li.note-list-item').data('id');
    note = $('li.note-list-item[data-id="'+id+'"]').find('div.note-content');
    note.hide().before($('div#div-u-message'));
    // var editor = $( '#message-body' ).ckeditor().editor;
    var html = note.find('.note-body').html();
    $( '#message-body' ).val(html)
    $('div#div-u-message').addClass('note-content').show();
});
$('.cancel-u-message').on('click', function(e){
    e.preventDefault();
    $('#div-u-message').hide().removeClass('note-content');
    note.show();
});
TXT;

if (USER_ID == 1) {
    $this->registerJs($js);
}
