<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_day_inc.php');
$files = [];
if (file_exists(Yii::getAlias('@webroot').'/upload/devis-days/')) {
    $files = scandir(Yii::getAlias('@webroot').'/upload/devis-days/', 1);
}


$imageList = [];

if ($files) {
    asort($files);
    foreach ($files as $k=>$v) {
        if (substr($v, -4) == '.jpg') {
            $imageList[$v] = $v;
        }
    }
}



// Convert to richtext
if (substr($theDay['body'], 0, 1) != '<') {
    // $theDay['body'] = $parser->parse($theDay['body']);
}

?>
<? $form = ActiveForm::begin([
    'id'=>'editDayForm',
    // 'enableAjaxValidation' => true
]); ?>
<div class="modal fade modal-primary" data-backdrop="static" id="edit-day" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h6 class="modal-title text-pink text-semibold">Edit day</h6>
            </div> 
            <div class="modal-body">
                <?= $form->field($theDay, 'id')->hiddenInput()->label(false) ?>
                <div class="row">
                    <div class="col-md-8"><?= $form->field($theDay, 'name')->label('Name') ?></div>
                    <div class="col-md-4"><?= $form->field($theDay, 'image')->dropdownList($imageList, ['prompt'=>'( No image )']) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-2"><?= $form->field($theDay, 'meals')->dropdownList($dayMealList)->label('Meals') ?></div>
                    <div class="col-md-5"><?= $form->field($theDay, 'guides')->label('Tour guide') ?></div>
                    <div class="col-md-5"><?= $form->field($theDay, 'transport')->label('Transport') ?></div>
                </div>
                <?= $form->field($theDay, 'body')->textArea(['rows'=>15])->label('Activities') ?>
                <?= $form->field($theDay, 'note')->textArea(['rows'=>5])->label('Note') ?>
                <div><?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?>
                    or <a href="#" data-dismiss="modal">Cancel</a>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<? ActiveForm::end(); ?>
<?
$js = <<<TXT
$('#day-body').ckeditor({
    allowedContent: 'p sub sup strong em s a i u ul ol li img blockquote;',
    entities: false,
    entities_greek: false,
    entities_latin: false,
    height:400,
    contentsCss: '/assets/css/style_ckeditor.css'
});
TXT;
$this->registerJs($js);

$this->registerJsFile('https://cdn.ckeditor.com/4.6.0/basic/ckeditor.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdn.ckeditor.com/4.6.0/basic/adapters/jquery.js', ['depends'=>'yii\web\JqueryAsset']);
