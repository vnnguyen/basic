<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_dvg_inc.php');

Yii::$app->params['page_breadcrumbs'][] = ['CPDV', 'cp'];
Yii::$app->params['page_breadcrumbs'][] = ['Chi phí', 'cp'];
Yii::$app->params['page_breadcrumbs'][] = [$theDv['name'], 'cp/r/'.$theDv['id']];
Yii::$app->params['page_breadcrumbs'][] = ['Giá', 'dvg?dv_id='.$theDv['id']];

if ($theDvg->isNewRecord) {
    $this->title = 'Thêm chi phí dịch vụ';
} else {
    $this->title = 'Sửa: '.$theDvg['name'];
    Yii::$app->params['page_breadcrumbs'][] = ['Xem', 'dvg/r/'.$theDvg['id']];
}

// \fCore::expose($theDv); exit;

?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Cost price</h6>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Tên chi phí</label>
                        <p class="form-control-static"><?= Html::a($theDv['name'], '/cp/r/'.$theDv['id']) ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Điểm / Nhà cung cấp dv</label>
                        <p class="form-control-static">
                            <?= $theDv['venue'] ? Html::a($theDv['venue']['name'], '/venues/r/'.$theDv['venue']['id'], ['rel'=>'external']) : '' ?>
                            <?= !$theDv['venue'] && $theDv['byCompany'] ? Html::a($theDv['byCompany']['name'], '/companies/r/'.$theDv['byCompany']['id'], ['rel'=>'external']) : '' ?>
                        </p>
                    </div>
                </div>
            </div>
            <? $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-3"><?= $form->field($theDvg, 'from_dt')->label('Từ ngày') ?></div>
                <div class="col-md-3"><?= $form->field($theDvg, 'until_dt')->label('Đến ngày') ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?=$form->field($theDvg, 'name') ?></div>
                <div class="col-md-6"><?= $form->field($theDvg, 'search') ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theDvg, 'via_company_id')->dropdownList(ArrayHelper::map($companyList, 'id', 'name'), ['prompt'=>'- Đặt trực tiếp -'])->label('Đặt qua') ?></div>
                <div class="col-md-4"><?=$form->field($theDvg, 'price') ?></div>
                <div class="col-md-2"><?= $form->field($theDvg, 'currency')->dropdownList(['VND'=>'VND', 'USD'=>'USD']) ?></div>
            </div>
            <?=$form->field($theDvg, 'info')->textArea(['rows'=>4]) ?>

            <div class="text-right"><?= Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']) ?></div>
            <? ActiveForm::end(); ?>            
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Related prices</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-xxs">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>Name</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($relatedDvgx as $dvg) { ?>
                    <tr>
                        <td class="text-nowrap"><?= $dvg['from_dt'] ?></td>
                        <td><?= $dvg['name'] ?></td>
                        <td class="text-right text-nowrap"><?= Html::a(number_format($dvg['price']), '/dvg/u/'.$dvg['id']) ?> <?= $dvg['currency'] ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?
$this->registerJsFile('https://code.jquery.com/ui/1.12.0/jquery-ui.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css', ['depends'=>'yii\web\JqueryAsset']);
$js = <<<'TXT'
$(function(){
    $('#dvg-from_dt, #dvg-until_dt').datepicker({
        changeYear: true,
        changeMonth: true,
        //yearRange: '-5y:+5y',
        //minDate: '+1 d',
        showOtherMonths: true,
        showButtonPanel: true,
        firstDay: 1,
        duration: 0,
        dateFormat: 'yy-mm-dd'
    });
});
TXT;
$this->registerJs($js);