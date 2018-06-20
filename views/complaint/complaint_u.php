<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_complaint_inc.php');

Yii::$app->params['body_class'] = 'sidebar-xs';

if ($theComplaint->isNewRecord) {
    Yii::$app->params['page_title'] = 'New: tour complaint';
} else {
    Yii::$app->params['page_title'] = 'Edit tour complaint: '.$theComplaint['name'];
}

$complaintUsers = [];

if ($theTour != null) {
    $complaintUsers = ArrayHelper::map($theTour['pax'], 'id', 'name');
}
$form = ActiveForm::begin();
?>
<style>
/*#complaint-involving label {display:block;}*/
</style>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-body">
            <fieldset>
                <legend><?= Yii::t('complaint', 'Detail of complaint') ?></legend>
                <?= $form->field($theComplaint, 'name')->label(Yii::t('complaint', 'Name of complaint')) ?>
                <?= $form->field($theComplaint, 'description')->textArea(['rows'=>5])->label(Yii::t('complaint', 'Description')) ?>

                <div class="row">
                    <div class="col-md-6"><?= $form->field($theComplaint, 'tour_code')->label(Yii::t('complaint', 'Tour code')) ?></div>
                    <div class="col-md-6"><?= $form->field($theComplaint, 'complaint_date')->label(Yii::t('complaint', 'Date of complaint')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                    <?= $form->field($theComplaint, 'incident_id')->dropDownList(ArrayHelper::map($incidentList, 'id', 'name'), ['prompt'=>Yii::t('app', '- Select -'), 'class'=>'form-control select2'])->label(Yii::t('complaint', 'Incident')) ?>
                    </div>
                    <div class="col-md-6"><?= $form->field($theComplaint, 'complaint_user')->dropDownList($complaintUsers, ['prompt'=>Yii::t('app', '- Select -'), 'class'=>'form-control select2'])->label(Yii::t('complaint', 'Complaint users')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($theComplaint, 'stype')->dropdownList($complaintTypeList, ['prompt'=>Yii::t('app', '- Select -')])->label(Yii::t('complaint', 'Type of complaint')) ?></div>
                </div>
            </fieldset>
            <fieldset>
                <legend><?= Yii::t('complaint', 'Related people') ?></legend>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($theComplaint, 'owner_id')->dropdownList(ArrayHelper::map($staffList, 'id', 'name'), ['class'=>'form-control select2', 'prompt'=>Yii::t('app', '- Select -')])->label(Yii::t('complaint', 'In charge')) ?></div>
                </div>
                <?= $form->field($theComplaint, 'owners', ['enableClientValidation'=>false])->dropdownList(ArrayHelper::map($staffList, 'id', 'name'), ['class'=>'form-control select2', 'multiple'=>'multiple'])->label(Yii::t('complaint', 'Other staff participating')) ?>
            </fieldset>
            <fieldset>
                <legend><?= Yii::t('complaint', 'Solution') ?></legend>
                    <div class="row">
                    <div class="col-md-6"><?= $form->field($theComplaint, 'status')->dropdownList($complaintStatusList, ['prompt'=>Yii::t('app', '- Select -')])->label(Yii::t('complaint', 'Status')) ?></div>
                </div>
            </fieldset>
            <? if (!$theComplaint->isNewRecord) { ?>
            <p class="text-muted"><?= Yii::t('complaint', 'This was last updated by {user} {time}', [
            'user'=>$theComplaint['updatedBy']['name'],
            'time'=>Yii::$app->formatter->asRelativetime($theComplaint['updated_dt']),
            ]) ?></p>
            <? } ?>
            <?= Html::submitButton(Yii::t('app', 'Save changes'), ['class' => 'btn btn-primary']) ?>
            <?= Yii::t('app', 'or') ?>
            <?= Html::a(Yii::t('app', 'Cancel'), '/complaints') ?>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-body">
            <fieldset>
                <legend><?= Yii::t('complaint', 'Development') ?></legend>
                <p>How this complaint develops and leads to solution.</p>
            </fieldset>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <fieldset>
                <legend><?= Yii::t('complaint', 'Cost incurred') ?></legend>
                <?= $form->field($theComplaint, 'test')->label(Yii::t('complaint', 'Cost incurred'))->hint(Yii::t('complaint', 'Select all that are applicable')) ?>
            </fieldset>
        </div>
    </div>
</div>
<?

ActiveForm::end();

$js = <<<'TXT'
$('.select2').select2();

$('#complaint-complaint_date').datepicker({
    format: 'yyyy-mm-dd',
    weekStart: 1,
    maxViewMode: 2,
    todayBtn: "linked",
    clearBtn: true,
    language: "{$lang}",
    autoclose: true,
    todayHighlight: true
});

$('#complaint-tour_code').on('keyup', function(){
    $.ajax({
        url: "/complaint/datas",
        method: "GET",
        data: { tour_code : $(this).val()},
        dataType: "json"
    }).done(function( result ) {
        if (result['err'] != undefined) {
             $('#complaint-incident_id').html('').select2({
            data: null
            })
            $('#complaint-complaint_user').html('').select2({
                data: null
            })
                console.log(result['err']); return;
            }
        var incidentList = $.map(result['incidents'], function (obj) {
                        obj.id = obj.id;
                        obj.text = obj.text || obj.name;
                        return obj;
                    });
        var complaintUsers = $.map(result['complaintUsers'], function (obj) {
                        obj.id = obj.id;
                        obj.text = obj.text || obj.name;
                        return obj;
                    });
        $('#complaint-incident_id').html('').append($('<option>', {value: '', text : '-Select-'})).select2({
            data: incidentList
        })
        $('#complaint-complaint_user').html('').append($('<option>', {value: '', text : '-Select-'})).select2({
            data: complaintUsers
        })
    }).fail(function( jqXHR, textStatus ) {
        alert( "Request failed: " + textStatus );
    });
});
TXT;

$js = str_replace('{$lang}', \Yii::$app->language, $js);

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/locales/bootstrap-datepicker.'.\Yii::$app->language.'.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);

$this->registerJs($js);