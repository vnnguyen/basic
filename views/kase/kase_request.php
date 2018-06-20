<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;
// use yii\bootstrap\ActiveForm;
use app\helpers\DateTimeHelper;

include('_kase_inc.php');

Yii::$app->params['page_title'] = 'Edit customer request: '.$theCase['name'];
Yii::$app->params['page_breadcrumbs'][] = ['View', '@web/cases/r/'.$theCase['id']];
Yii::$app->params['page_breadcrumbs'][] = ['Customer request'];
if ($caseStats->req_web_tour != '' && !in_array($caseStats->req_web_tour, $kaseRequestedTourList)) {
    $kaseRequestedTourList[$caseStats->req_web_tour] = $caseStats->req_web_tour;
}
$tourTheme = [
    'Découverte des sites incontournables' => 'Découverte des sites incontournables',
    'Immersion dans la vie locale' => 'Immersion dans la vie locale',
    'Randonnées et treks' => 'Randonnées et treks',
    'Balades à vélo' => 'Balades à vélo',
    'Cours de cuisine et autres découvertes culinaires' => 'Cours de cuisine et autres découvertes culinaires',
    'Détente' => 'Détente',
    'Séjour balnéaire' => 'Séjour balnéaire',
    'Croisière' => 'Croisière',
    'Artisanat local' => 'Artisanat local',
    'Bien-être et massages' => 'Bien-être et massages',
    'Retour aux sources' => 'Retour aux sources',
    'Voyage en amoureux' => 'Voyage en amoureux',
];
?>
<div class="col-md-8">
    <div class="alert alert-warning">Form này đang trong quá trình hoàn thiện</div>
    <div class="panel panel-default">
        <div class="panel-body">
            <? $form = ActiveForm::begin() ?>
            <fieldset>
                <legend>Người liên hệ yêu cầu tour</legend>
                <?= $form->field($caseStats, 'contact_addr_country')->dropdownList(ArrayHelper::map($countryList, 'code', 'name_en'), ['class'=>'form-control  select2', ])->label('Quốc gia cư trú')->hint(false) ?>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($caseStats, 'contact_addr_region')->label('Tiểu bang/Khu vực')->hint('') ?></div>
                    <div class="col-md-6"><?= $form->field($caseStats, 'contact_addr_city')->label('Thành phố')->hint('') ?></div>
                </div>
                <?= $form->field($caseStats, 'contact_nationality')->dropdownList(ArrayHelper::map($countryList, 'code', 'name_en'), ['class'=>'form-control select2'])->label('Quốc tịch')->hint(false) ?>
            </fieldset>

            <fieldset>
                <legend>Nhóm khách đi tour</legend>
                <p>Số khách theo nhóm tuổi cụ thể, nếu biết</p>
                <div class="row">
                    <div class="col-md-2"><?= $form->field($caseStats, 'group_age_0_1', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>0, 'max'=>999]])->label('<2')->hint(false) ?></div>
                    <div class="col-md-2"><?= $form->field($caseStats, 'group_age_2_11', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>0, 'max'=>999]])->label('2-11')->hint(false) ?></div>
                    <div class="col-md-2"><?= $form->field($caseStats, 'group_age_12_17', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>0, 'max'=>999]])->label('12-17')->hint(false) ?></div>
                    <div class="col-md-2"><?= $form->field($caseStats, 'group_age_18_25', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>0, 'max'=>999]])->label('18-25')->hint(false) ?></div>
                    <div class="col-md-2"><?= $form->field($caseStats, 'group_age_26_34', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>0, 'max'=>999]])->label('26-34')->hint(false) ?></div>
                    <div class="col-md-2"><?= $form->field($caseStats, 'group_age_35_50', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>0, 'max'=>999]])->label('35-50')->hint(false) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-2"><?= $form->field($caseStats, 'group_age_51_60', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>0, 'max'=>999]])->label('51-60')->hint(false) ?></div>
                    <div class="col-md-2"><?= $form->field($caseStats, 'group_age_61_70', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>0, 'max'=>999]])->label('61-70')->hint(false) ?></div>
                    <div class="col-md-2"><?= $form->field($caseStats, 'group_age_71_up', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>0, 'max'=>999]])->label('>70')->hint(false) ?></div>
                    <div class="col-md-4 col-md-offset-2"><?= $form->field($caseStats, 'group_pax_count')->label('Hoặc tổng số pax nếu k biết tuổi')->hint('VD: 10 hoặc 10-13') ?></div>
                </div>
                <?= $form->field($caseStats, 'group_nationalities')->dropdownList(ArrayHelper::map($countryList, 'code', 'name_en'), ['multiple'=>'multiple', 'class'=>'form-control select2'])->label('Quốc tịch nhóm khách')->hint('Chọn nhiều nếu có') ?>
            </fieldset>

            <fieldset>
                <legend>Nhu cầu đi tour</legend>
                <?= $form->field($caseStats, 'pa_destinations')->checkboxList(ArrayHelper::map($tourCountryList, 'code', 'name_en'))->label(Yii::t('k', 'Quốc gia muốn thăm')) ?>
                <?= $form->field($caseStats, 'req_destinations')->label('Các địa điểm/địa danh muốn thăm')->hint('Cách nhau bằng dấu phẩy') ?>
                <!-- <?//= $form->field($caseStats, 'pa_pax_ages')->label('Số tuổi pax')->hint('30 or 30-40 or 30,32,50') ?> -->
                <div class="row">
                    <div class="col-md-6"><?= $form->field($caseStats, 'pa_start_date')->label('Ngày bắt đầu tour')->hint('VD: 2018 hoặc 2018-01 hoặc 2018-01-12') ?></div>
                    <div class="col-md-6"><?= $form->field($caseStats, 'pa_days')->label('Số ngày đi tour')->hint('VD: 10 hoặc 20-25') ?></div>
                </div>
                
                
<!--             <?//= $form->field($caseStats, 'pa_tour_type') ?>
            <?//= $form->field($caseStats, 'pa_group_type') ?>
 -->            
            </fieldset>
<style type="text/css" media="screen">
    #kasestats-note label { width: 40%; }
</style>
            <fieldset>
                <legend>Loại hình hay chương trình tour</legend>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($caseStats, 'req_travel_type')->dropdownList($kaseTravelTypeList, ['class'=>'form-control', 'multiple' => 'multiple'])->label('Tính chất nhóm đi tour')->hint('') ?></div>
                    <div class="col-md-6"><?= $form->field($caseStats, 'req_is_homevisit')->checkbox(['label'=>'Đây là khách quay về thăm nơi sinh của mình'])->label('Khách ngoại kiều')->hint('') ?></div>
                </div>
                <!--
                * - Anh Huân Uncoment dòng dưới để hiển thị chủ đề tour nhé
                *
                **
             -->
                <?//= $form->field($caseStats, 'note')->checkboxList($tourTheme)->label(Yii::t('k', 'Chủ đề Tour')) ?>

                <?= $form->field($caseStats, 'req_web_tour')->dropdownList($kaseRequestedTourList, ['class'=>'form-control select2', 'prompt'=>'- Chọn -'])->label('Tour yêu cầu (khách chọn trên website)') ?>
                <?= $form->field($caseStats, 'req_web_formula')->dropdownList($kaseFormuleList, ['class'=>'form-control', 'multiple' => 'multiple'])->label('Chương trình yêu cầu (Khách chọn trên website)') ?>


                <?//= $form->field($caseStats, 'test')->dropdownList($kaseDeviceList, ['prompt'=>'- Chọn -'])->label('Loại thiết bị truy cập web')->hint('') ?>
                
                <?//= $form->field($caseStats, 'test', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>0, 'max'=>999999999]])->label('Ngân sách')->hint('') ?>
            </fieldset>
            <?//= $form->field($caseStats, 'pa_tags') ?>
            <fieldset>
                <legend>Ghi chú</legend>
                <?= $form->field($caseStats, 'note')->textArea(['rows'=>3])->label(false) ?>
            </fieldset>
            <div>
                <?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?>
                hoặc
                <?= Html::a('Cancel', '/cases/r/'.$theCase['id']) ?>
            </div>
            <? ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?

$js = <<<'TXT'

$('.select2').select2({
    allowClear: true,
    placeholder: 'select', 
});
$('#kasestats-req_travel_type').select2({
    placeholder: 'select',
    allowClear: false
}).on("select2:select", function (e) {
    var selected_element = $(e.currentTarget);
    $('[name="KaseStats[req_travel_type]"]').val(selected_element.val());
});
$('#kasestats-req_web_formula').select2({
    placeholder: 'select',
    allowClear: false
}).on("select2:select", function (e) {
    var selected_element = $(e.currentTarget);
    $('[name="KaseStats[req_web_formula]"]').val(selected_element.val());
});

$(document).ready(function(){
    $('#kasestats-req_travel_type').on('change', function(e){
        var selected_element = $(e.currentTarget);
        $('[name="KaseStats[req_travel_type]"]').val(selected_element.val());
    });
    $('#kasestats-req_web_formula').on('change', function(e){
        var selected_element = $(e.currentTarget);
        $('[name="KaseStats[req_web_formula]"]').val(selected_element.val());
    });
    if ($("#kasestats-req_travel_type").val() != '') {
        $('[name="KaseStats[req_travel_type]"]').val($("#kasestats-req_travel_type").val().toString());
    }
    if ($("#kasestats-req_web_formula").val() != '') {
        $('[name="KaseStats[req_web_formula]"]').val($("#kasestats-req_web_formula").val().toString());
    }
});




TXT;

$this->registerJs($js);