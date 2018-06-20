<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_message_inc.php');

$this->title = 'Edit message: '.$theNote['title'];

\app\assets\CkeditorOnlyAsset::register($this);
$this->registerJs(\app\assets\CkeditorOnlyAsset::ckeditorJs('#message-body'));
?>
<div class="col-lg-6 col-md-8 col-sm-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <? if ($theNote['files']) { ?>
            <p><strong>FILE LIST</strong></p>
            <ul class="list list-unstyled note-file-list">
                <? foreach ($theNote['files'] as $file) { ?>
                <li>+ <?= Html::a($file['name'], '@web/files/r/'.$file['id']) ?> <?= number_format($file['size'] / 1024, 2) ?>KiB <?= Html::a('Remove', DIR.URI.'?action=removefile&file='.$file['id'], ['class'=>'text-danger']) ?></li>
                <? } ?>
            </ul>
            <hr>
            <? } ?>
            <? $form = ActiveForm::begin(); ?>
            <?= $form->field($theNote, 'body')->textArea(['rows'=>10]) ?>
            <?= $form->field($theNote, 'title') ?>
            <p>These people will be notified about your edit: EMAIL_LIST</p>
            <div class="text-right"><?= Html::submitButton('Save note', ['class'=>'btn btn-primary']) ?></div>
            <? ActiveForm::end(); ?>
            
        </div>
    </div>
</div>
