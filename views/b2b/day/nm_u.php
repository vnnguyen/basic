<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

require_once('/var/www/vendor/textile/php-textile/Parser.php');
$parser = new \Netcarver\Textile\Parser();

include('_nm_inc.php');

if ($theDay->isNewRecord) {
    Yii::$app->params['page_title'] = 'New sample day';
} else {
    Yii::$app->params['page_title'] = 'Edit sample day: '.$theDay['title'];
}

$files = scandir('/var/www/my.amicatravel.com/www/upload/devis-days/', 1);
asort($files);
$imageList = [];
foreach ($files as $k=>$v) {
    if (substr($v, -4) == '.jpg') {
        $imageList[$v] = $v;
    }
}

?>

<div class="col-md-8">
    <div class="card">
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-8"><?= $form->field($theDay, 'title')->label('Name') ?></div>
                <div class="col-md-4"><?= $form->field($theDay, 'language')->dropdownList($languageList)->label('Language') ?></div>
            </div>
            <?= $form->field($theDay, 'body')->textArea(['rows'=>15])->label('Activities') ?>
            <?= $form->field($theDay, 'tags')->label('Tags') ?>
            <div class="row">
                <div class="col-md-2"><?= $form->field($theDay, 'meals')->dropdownList($dayMealList)->label('Meals') ?></div>
                <div class="col-md-6"><?= $form->field($theDay, 'guides')->label('Tour guide') ?></div>
                <div class="col-md-4"><?= $form->field($theDay, 'transport')->label('Transport') ?></div>
            </div>
            <?= $form->field($theDay, 'note')->textArea(['rows'=>5])->label('Note') ?>
            <div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?></div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div class="col-md-4">
    <div class="card">
        <div class="card-body">
            <?= $form->field($theDay, 'image')->dropdownList($imageList, ['prompt'=>'( No image )'])->label('Image') ?>
            <div class="mb-1em" id="image-preview">
                <?
                if ($theDay['image'] != '') {
                    echo Html::img('/upload/devis-days/'.$theDay['image']);
                }
                ?>
            </div>
            <hr>
            <?php if (!$theDay->isNewRecord) { ?>
            Update: <?= $theDay['updatedBy']['name'] ?> @<?= Yii::$app->formatter->asDate($theDay['updated_dt'], 'php:j/n/Y (l) H:i') ?>
            <?php } ?>
        </div>
    </div>
</div>

<?php
Yii::$app->params['page_js'] = '';

$js = <<<'TXT'
$('#nm-image').change(function(){
    var image = $(this).val();
    $('#image-preview').html('<img src="/upload/devis-days/'+image+'" />');
});

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
$this->registerJsFile('https://cdn.ckeditor.com/4.10.1/basic/ckeditor.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdn.ckeditor.com/4.10.1/basic/adapters/jquery.js', ['depends'=>'yii\web\JqueryAsset']);
