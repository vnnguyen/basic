<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

Yii::$app->params['page_title'] = '(TEST) Dependent dropdown DV';

$sql = 'SELECT id, name FROM venues WHERE stype="hotel" ORDER BY name';
$hotels = \Yii::$app->db->createCommand($sql)->queryAll();
$hotelList = [];
foreach ($hotels as $hotel) {
    $hotelList[$hotel['id']] = $hotel['name'];
}

$dvList = [];
$form = ActiveForm::begin();
?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6"><?= $form->field($theForm, 'ncc_id')->dropdownList($hotelList) ?></div>
                <div class="col-md-6"><?= $form->field($theForm, 'dv_id')->dropdownList($dvList) ?></div>
            </div>
            <div class="row">
                <div class="col-md-3"><?= $form->field($theForm, 'note')->label('Price') ?></div>
                <div class="col-md-3"><?= $form->field($theForm, 'note')->label('x Qty') ?></div>
                <div class="col-md-3"><?= $form->field($theForm, 'note')->label('x Day/Nights') ?></div>
                <div class="col-md-3"><?= $form->field($theForm, 'note')->dropdownList([''=>'', 'xn'=>'Nights', 'xd'=>'Days'])->label('&nbsp;') ?></div>
            </div>
            <p><a class="moreless more" href="#" style="">Show more...</a><a class="moreless less" href="#" style="display:none">Show less</a></p>
            <div id="moreless" style="display:none;">
            <div class="row">
                <div class="col-md-3"><?= $form->field($theForm, 'note')->label('Price') ?></div>
                <div class="col-md-3"><?= $form->field($theForm, 'note')->label('x Qty') ?></div>
                <div class="col-md-3"><?= $form->field($theForm, 'note')->label('x Day/Nights') ?></div>
                <div class="col-md-3"><?= $form->field($theForm, 'note')->dropdownList([''=>'', 'xn'=>'Nights', 'xd'=>'Days'])->label('&nbsp;') ?></div>
            </div>
            </div>
            <?= $form->field($theForm, 'note')->textArea(['rows'=>5]) ?>
            <?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?>            
        </div>
    </div>

</div>
<style type="text/css">
#w0 input:focus, select:focus, textarea:focus {border-color:red!important;}
</style>
<?
ActiveForm::end();

$js = <<<'TXT'
$('#huantestdvform-ncc_id').select2()
$("#huantestdvform-dv_id").depdrop({
    depends: ['huantestdvform-ncc_id'],
    url: '/huan/ajax?action=ncc-dv'
});
$('.moreless.more').on('click', function(){
    $('#moreless, .moreless.less').show();
    $('#moreless').find(':input:eq(0)').focus();
    $(this).hide();
    return false;
})
$('.moreless.less').on('click', function(){
    $('#moreless').hide();
    $('.moreless.more').show();
    $(this).hide();
    return false;
})
TXT;

$this->registerCssFile('/assets/dependent-dropdown_1.4.4/css/dependent-dropdown.min.css?x', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('/assets/dependent-dropdown_1.4.4/js/dependent-dropdown.min.js?x', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);
