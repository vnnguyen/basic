<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_cpo_inc.php');

Yii::$app->params['page_breadcrumbs'][] = ['CPDV', 'cp'];
Yii::$app->params['page_breadcrumbs'][] = ['Chi phí', 'cp'];
Yii::$app->params['page_breadcrumbs'][] = [$theDvo['name'], 'cp/r/'.$theDvo['id']];
Yii::$app->params['page_breadcrumbs'][] = ['Giá', 'cpo?cp_id='.$theDvo['id']];

if ($theCpo->isNewRecord) {
    $this->title = 'Thêm giá chi phí dịch vụ';
} else {
    $this->title = 'Sửa: '.$theCpo['name'];
    Yii::$app->params['page_breadcrumbs'][] = ['Xem', 'cpo/r/'.$theCpo['id']];
}

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
                        <p class="form-control-static"><?= Html::a($theDvo['name'], '/cp/r/'.$theDvo['id']) ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Điểm / Nhà cung cấp dv</label>
                        <p class="form-control-static">
                            <?= $theDvo['venue'] ? Html::a($theDvo['venue']['name'], '/venues/r/'.$theDvo['venue']['id'], ['rel'=>'external']) : '' ?>
                            <?= !$theDvo['venue'] && $theDvo['byCompany'] ? Html::a($theDvo['byCompany']['name'], '/companies/r/'.$theDvo['byCompany']['id'], ['rel'=>'external']) : '' ?>
                        </p>
                    </div>
                </div>
            </div>
            <? $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-3"><?= $form->field($theCpo, 'from_dt')->label('Từ ngày') ?></div>
                <div class="col-md-3"><?= $form->field($theCpo, 'until_dt')->label('Đến ngày') ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?=$form->field($theCpo, 'name') ?></div>
                <div class="col-md-6"><?= $form->field($theCpo, 'search') ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theCpo, 'via_company_id')->dropdownList(ArrayHelper::map($companyList, 'id', 'name'), ['prompt'=>'- Đặt trực tiếp -'])->label('Đặt qua') ?></div>
                <div class="col-md-4"><?=$form->field($theCpo, 'price') ?></div>
                <div class="col-md-2"><?= $form->field($theCpo, 'currency')->dropdownList(['VND'=>'VND', 'USD'=>'USD']) ?></div>
            </div>
            <?=$form->field($theCpo, 'info')->textArea(['rows'=>4]) ?>

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
                    <? foreach ($relatedCpox as $cpo) { ?>
                    <tr>
                        <td class="text-nowrap"><?= $cpo['from_dt'] ?></td>
                        <td><?= $cpo['name'] ?></td>
                        <td class="text-right text-nowrap"><?= Html::a(number_format($cpo['price']), '/cpo/u/'.$cpo['id']) ?> <?= $cpo['currency'] ?></td>
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
    $('#cpo-from_dt, #cpo-until_dt').datepicker({
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