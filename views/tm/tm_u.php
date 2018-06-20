<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_tm_inc.php');

if ($theProgram->isNewRecord) {
    Yii::$app->params['page_title'] = 'New sample tour';
} else {
    Yii::$app->params['page_title'] = 'Edit sample tour: '.$theProgram['title'];
}

$form = ActiveForm::begin();
?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-8"><?= $form->field($theProgram, 'title')->label('Name') ?></div>
                <div class="col-md-4"><?= $form->field($theProgram, 'language')->dropdownList($languageList)->label('Language') ?></div>
            </div>
            <?= $form->field($theProgram, 'intro')->textArea(['rows'=>10])->label('Description') ?>
            <?= $form->field($theProgram, 'tags')->label('Tags') ?>
            <?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?>
            <? if (!$theProgram->isNewRecord) { ?>
            <hr>Update: <?= $theProgram['updatedBy']['name'] ?> @<?= Yii::$app->formatter->asDate($theProgram['updated_at'], 'php:j/n/Y (l) H:i') ?>
            <? } ?>
        </div>
    </div>
</div>
<? ActiveForm::end(); ?>
<?
$js = <<<TXT
$('#nm-body').ckeditor({
    allowedContent: 'p sub sup strong em s a i u ul ol li img blockquote;',
    entities: false,
    entities_greek: false,
    entities_latin: false,
    uiColor: '#ffffff',
    height:400,
    contentsCss: '/assets/css/style_ckeditor.css'
    //contentCss:'https://my.amicatravel.com/assets/css/ckeditor_160828.css'
});
TXT;
$this->registerJs($js);

$this->registerJsFile('https://cdn.ckeditor.com/4.5.11/basic/ckeditor.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdn.ckeditor.com/4.5.11/basic/adapters/jquery.js', ['depends'=>'yii\web\JqueryAsset']);
