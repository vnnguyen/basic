<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

require_once('/var/www/vendor/textile/php-textile/Parser.php');
$parser = new \Netcarver\Textile\Parser();


include('_day_inc.php');

if ($theDay->isNewRecord) {
    $this->title = 'New day';
    $this->params['breadcrumb'][] = ['Add', URI];
} else {
    $this->title = 'Edit: '.$theDay['name'];
    $this->params['breadcrumb'][] = ['View', 'days/r/'.$theDay['id']];
    $this->params['breadcrumb'][] = ['Edit', 'days/u/'.$theDay['id']];
}

$files = scandir('/var/www/my.amicatravel.com/www/upload/devis-days/', 1);
asort($files);
$imageList = [];
foreach ($files as $k=>$v) {
    if (substr($v, -4) == '.jpg') {
        $imageList[$v] = $v;
    }
}

// Convert to richtext
if (substr($theDay['body'], 0, 1) != '<') {
    $theDay['body'] = $parser->parse($theDay['body']);
}

?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-body">
    <? if ($theDay['product']) { ?>
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i>
        This day belongs to the Product named <?= Html::a($theDay['product']['title'], '@web/products/r/'.$theDay['product']['id'], ['class'=>'alert-link']) ?>
    </div>
    <? } ?>
    <? $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-8"><?= $form->field($theDay, 'name') ?></div>
        <div class="col-md-4"><?= $form->field($theDay, 'image')->dropdownList($imageList, ['prompt'=>'( No image )']) ?></div>
    </div>
    <div class="row">
        <div class="col-md-2"><?= $form->field($theDay, 'meals')->dropdownList($dayMealList) ?></div>
        <div class="col-md-6"><?= $form->field($theDay, 'guides') ?></div>
        <div class="col-md-4"><?= $form->field($theDay, 'transport') ?></div>
    </div>
    <?= $form->field($theDay, 'body')->textArea(['rows'=>15]) ?>
    <?= $form->field($theDay, 'note')->textArea(['rows'=>5]) ?>
    <div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?></div>
    <? ActiveForm::end(); ?>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-body">
            <p><strong>IMAGE PREVIEW</strong></p>
            <div class="mb-1em" id="image-preview">
                <?
                if ($theDay['image'] != '') {
                    echo Html::img('/upload/devis-days/'.$theDay['image']);
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?
$js = <<<TXT
$('#day-image').change(function(){
    var image = $(this).val();
    $('#image-preview').html('<img src="/upload/devis-days/'+image+'" />');
});

$('#day-body').ckeditor({
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

$this->registerJsFile('https://cdn.ckeditor.com/4.6.0/basic/ckeditor.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdn.ckeditor.com/4.6.0/basic/adapters/jquery.js', ['depends'=>'yii\web\JqueryAsset']);
