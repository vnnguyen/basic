<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_tours_inc.php');

Yii::$app->params['page_title'] = 'Tour stats: '.$theTour['op_code'];
Yii::$app->params['page_breadcrumbs'] = [
    ['Tour operation', '#'],
    ['Tours', 'tours'],
    [substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
    ['View', 'tours/r/'.$theTourOld['id']],
    ['Stats'],
];

$countryList = [
    'vn'=>'Vietnam',
    'la'=>'Laos',
    'kh'=>'Cambodia',
    'mm'=>'Myanmar',
    'id'=>'Indonesia',
    'my'=>'Malaysia',
    'th'=>'Thailand',
    'cn'=>'China',
];

?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-body">
            <? $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-4"><?= $form->field($theStats, 'tour_code') ?></div>
                <div class="col-md-8"><?= $form->field($theStats, 'tour_name') ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?= $form->field($theStats, 'day_count') ?></div>
                <div class="col-md-4"><?= $form->field($theStats, 'start_date') ?></div>
                <div class="col-md-4"><?= $form->field($theStats, 'end_date') ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?= $form->field($theStats, 'pax_count') ?></div>
                <!--div class="col-md-8"><?//= $form->field($theStats, 'countries')->dropdownList($countryList, ['multiple'=>'multiple', 'class'=>'form-control xselect2']) ?></div-->
                <div class="col-md-8"><?= $form->field($theStats, 'countries')->checkboxList($countryList) ?></div>
            </div>
            <div>
                <?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?>
                or
                <?= Html::a(Yii::t('app', 'Cancel'), '#cancel') ?>
            </div>
            <? ActiveForm::end(); ?>            
        </div>
    </div>
</div>
<?
$js = <<<'TXT'
$('.select2').select2({
    placeholder: "- Select -",
    maximumSelectionLength: 10
});
$('input[type="text"]').attr('disabled', 'disabled');
TXT;
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);