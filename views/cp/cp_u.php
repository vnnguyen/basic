<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_cp_inc.php');

Yii::$app->params['page_breadcrumbs'][] = ['CPDV', 'cp'];

if ($dv_id != 0) {
    Yii::$app->params['page_title'] = 'New cp for: '.$theDv['name'];
} else {
    Yii::$app->params['page_title'] = 'Edit price (cp)';
}

$currencyList = [
    'USD'=>'USD',
    'VND'=>'VND',
    'LAK'=>'LAK',
    'KHR'=>'KHR',
];

$results = \Yii::$app->db->createCommand('select id, name from dvc WHERE venue_id=:venue_id ORDER BY name', [':venue_id'=>$theDv['venue_id']])->queryAll();
$dvcList = [];
foreach ($results as $result) {
    $dvcList[$result['id']] = $result['name'];
}

?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Edit price</h6>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">Service</label>
                        <p class="form-control-static"><?= Html::a($theDv['name'], '/dv/r/'.$theDv['id']) ?></p>
                    </div>
                </div>
            </div>
            <? $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theCp, 'dvc_id')->dropdownList($dvcList)->label('Contract') ?></div>
            </div>
            <div class="row">
                <div class="col-md-3"><?= $form->field($theCp, 'period')->label('Period') ?></div>
                <div class="col-md-3"><?= $form->field($theCp, 'conds')->label('Conditions') ?></div>
                <div class="col-md-4"><?= $form->field($theCp, 'price')->label('Price') ?></div>
                <div class="col-md-2"><?= $form->field($theCp, 'currency')->dropdownList($currencyList)->label('Currency') ?></div>
            </div>
            <?= $form->field($theCp, 'info')->label('Information') ?>
            <div>
                <?= Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']) ?>
                or <?= Html::a('Cancel', '/dv/r/'.$theDv['id']) ?>
            </div>
            <? ActiveForm::end(); ?>
        </div>
    </div>
</div>
