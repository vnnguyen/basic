<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_incident_inc.php');

Yii::$app->params['body_class'] = 'sidebar-xs';

if ($theIncident->isNewRecord) {
    Yii::$app->params['page_title'] = 'New: tour incident';
} else {
    Yii::$app->params['page_title'] = 'Edit tour incident: '.$theIncident['name'];
}

$form = ActiveForm::begin();
?>
<style>
/*#incident-involving label {display:block;}*/
</style>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-body">
            <fieldset>
                <legend><?= Yii::t('incident', 'Detail of incident') ?></legend>
                <?= $form->field($theIncident, 'name')->label(Yii::t('incident', 'Name of incident')) ?>
                <?= $form->field($theIncident, 'description')->textArea(['rows'=>5])->label(Yii::t('incident', 'Description')) ?>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($theIncident, 'tour_code')->label(Yii::t('incident', 'Tour code')) ?></div>
                    <div class="col-md-6"><?= $form->field($theIncident, 'incident_date')->label(Yii::t('incident', 'Date of incident')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($theIncident, 'stype')->dropdownList($incidentTypeList, ['prompt'=>Yii::t('app', '- Select -')])->label(Yii::t('incident', 'Type of incident')) ?></div>
                    <div class="col-md-6"><?= $form->field($theIncident, 'severity')->dropdownList($severityList, ['prompt'=>Yii::t('app', '- Select -')])->label(Yii::t('incident', 'Severity')) ?></div>
                </div>
                <?= $form->field($theIncident, 'incident_location')->label(Yii::t('incident', 'Location of incident (if applicable)')) ?>
                <div style="display:none"><?= $form->field($theIncident, 'involving')->checkboxList($incidentInvolvementList)->label(Yii::t('incident', 'This incident involves')) ?></div>
            </fieldset>
            <fieldset>
                <legend><?= Yii::t('incident', 'Related people') ?></legend>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($theIncident, 'owner_id')->dropdownList(ArrayHelper::map($staffList, 'id', 'name'), ['class'=>'form-control select2', 'prompt'=>Yii::t('app', '- Select -')])->label(Yii::t('incident', 'In charge')) ?></div>
                </div>
                <?= $form->field($theIncident, 'owners', ['enableClientValidation'=>false])->dropdownList(ArrayHelper::map($staffList, 'id', 'name'), ['class'=>'form-control select2', 'multiple'=>'multiple'])->label(Yii::t('incident', 'Other staff participating')) ?>
            </fieldset>
            <fieldset>
                <legend><?= Yii::t('incident', 'Solution') ?></legend>
                    <?= $form->field($theIncident, 'actions')->checkboxList($incidentActionList)->label(Yii::t('incident', 'Actions taken')) ?>                <div class="row">
                    <div class="col-md-6"><?= $form->field($theIncident, 'status')->dropdownList($incidentStatusList, ['prompt'=>Yii::t('app', '- Select -')])->label(Yii::t('incident', 'Status')) ?></div>
                </div>
            </fieldset>
            <? if (!$theIncident->isNewRecord) { ?>
            <p class="text-muted"><?= Yii::t('incident', 'This was last updated by {user} {time}', [
            'user'=>$theIncident['updatedBy']['name'],
            'time'=>Yii::$app->formatter->asRelativetime($theIncident['updated_dt']),
            ]) ?></p>
            <? } ?>
            <?= Html::submitButton(Yii::t('app', 'Save changes'), ['class' => 'btn btn-primary']) ?>
            <?= Yii::t('app', 'or') ?>
            <?= Html::a(Yii::t('app', 'Cancel'), '/incidents') ?>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-body">
            <fieldset>
                <legend><?= Yii::t('incident', 'Development') ?></legend>
                <p>How this incident develops and leads to solution.</p>
            </fieldset>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <fieldset>
                <legend><?= Yii::t('incident', 'Cost incurred') ?></legend>
                <?= $form->field($theIncident, 'test')->label(Yii::t('incident', 'Cost incurred'))->hint(Yii::t('incident', 'Select all that are applicable')) ?>
            </fieldset>
        </div>
    </div>
</div>
<?

ActiveForm::end();

$js = <<<'TXT'
$('.select2').select2();

$('#incident-incident_date').datepicker({
    format: 'yyyy-mm-dd',
    weekStart: 1,
    maxViewMode: 2,
    todayBtn: "linked",
    clearBtn: true,
    language: "{$lang}",
    autoclose: true,
    todayHighlight: true
});
TXT;

$js = str_replace('{$lang}', \Yii::$app->language, $js);

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/locales/bootstrap-datepicker.'.\Yii::$app->language.'.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css', ['depends'=>'yii\web\JqueryAsset']);

$this->registerJs($js);