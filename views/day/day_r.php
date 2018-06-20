<?
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\ActiveForm;

require_once('/var/www/vendor/textile/php-textile/Parser.php');
$parser = new \Netcarver\Textile\Parser();

$files = scandir('/var/www/my.amicatravel.com/www/upload/devis-days/', 1);
asort($files);
$imageList = [];
foreach ($files as $k=>$v) {
    if (substr($v, -4) == '.jpg') {
        $imageList[$v] = $v;
    }
}

$statusList = [
    'replace'=>'Only this option',
    'append'=>'This and other options',
];

$caseList[0] = 'All cases / customers';
foreach ($theDay['product']['bookings'] as $booking) {
    $caseList[$booking['case']['id']] = 'Case: '.$booking['case']['name'];
}

include('_day_inc.php');
Yii::$app->params['page_title'] = $theDay['name'];
Yii::$app->params['page_breadcrumbs'] = [
    ['Tour days', 'days'],
    ['View', URI],
];
Yii::$app->params['page_actions'] = [
    [
        ['New day', 'days/c', 'icon-plus'],
    ]
];

$booking_id = 0;
$status = 'replace';
if ($action == 'add-option') {
    Yii::$app->params['page_title'] = 'Add option to tour day: '.$theDay['name'];
    Yii::$app->params['page_breadcrumbs'][] = ['Add option'];
}

if ($action == 'edit-option') {
    Yii::$app->params['page_title'] = 'Edit option to tour day: '.$theDay['name'];
    Yii::$app->params['page_breadcrumbs'][] = ['Add option'];
    foreach ($caseList as $bid=>$bname) {
        if (strpos($newDay['name'], ';'.$bid.';') !== false) {
            $booking_id = $bid;
        }
    }
    $status = 'replace';
    if (strpos($newDay['name'], ';APPEND;') !== false) {
        $status = 'append';
    }
}
?>
<div class="col-md-8">
    <? if ($theDay['product']) { ?>
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i>
        This day belongs to a tour program: <?= Html::a($theDay['product']['title'], '/ct/r/'.$theDay['product']['id'], ['class'=>'alert-link']) ?>
    </div>
    <? } ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><?= $theDay['name'] ?> <em><?= $theDay['meals'] ?></em></h6>
        </div>
        <div class="panel-body">
        <?= substr($theDay['body'], 0, 1) == '<' ? $theDay['body'] : $parser->parse($theDay['body']) ?>
        </div>
    </div>

    <? if ($action == 'add-option' || $action == 'edit-option') { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Option</h6>
            <? if ($action == 'edit-option') { ?>
            <div class="heading-elements">
                <span class="heading-text"><a class="text-danger" href="/days/r/<?= $theDay['id'] ?>?action=delete-option&option=<?= $newDay['id'] ?>">Delete</a></span>
            </div>
            <? } ?>
        </div>
        <div class="panel-body">
            <? $form = ActiveForm::begin(); ?>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group"><label class="control-label">This option applies to</label><?= Html::dropdownList('booking_id', $booking_id, $caseList, ['class'=>'form-control']) ?></div>
                </div>
                <div class="col-md-4">
                    <div class="form-group"><label class="control-label">When displaying this day, show</label><?= Html::dropdownList('status', $status, $statusList, ['class'=>'form-control']) ?></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8"><?//= $form->field($newDay, 'name') ?></div>
                <div class="col-md-4"><?//= $form->field($newDay, 'image')->dropdownList($imageList, ['prompt'=>'( No image )']) ?></div>
            </div>
            <div class="row">
                <div class="col-md-2"><?= $form->field($newDay, 'meals')->dropdownList($dayMealList) ?></div>
                <div class="col-md-6"><?= $form->field($newDay, 'guides') ?></div>
                <div class="col-md-4"><?= $form->field($newDay, 'transport') ?></div>
            </div>
            <?= $form->field($newDay, 'body')->textArea(['rows'=>15]) ?>
            <?= $form->field($newDay, 'note')->textArea(['rows'=>5]) ?>
            <div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?></div>
            <? ActiveForm::end(); ?>
        </div>
    </div>
    <? } ?>
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
    height:300,
    contentsCss: '/assets/css/style_ckeditor.css'
    //contentCss:'https://my.amicatravel.com/assets/css/ckeditor_160828.css'
});
TXT;

if ($action == 'add-option' || $action == 'edit-option') {
    $this->registerJs($js);
    $this->registerJsFile('https://cdn.ckeditor.com/4.5.11/basic/ckeditor.js', ['depends'=>'yii\web\JqueryAsset']);
    $this->registerJsFile('https://cdn.ckeditor.com/4.5.11/basic/adapters/jquery.js', ['depends'=>'yii\web\JqueryAsset']);
}
