<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

// include('_nm_inc.php');

Yii::$app->params['page_title'] = 'Sửa thông tin NCC: '.$theNcc['ten'];
Yii::$app->params['page_breadcrumbs'] = [['NCC', 'ncc']];

$statusList = [
    ''=>'Chưa sửa',
    'ok'=>'OK - đã sửa',
    'nok'=>'NOK - không dùng',
];

$venueList = \common\models\Venue::find()
    ->select(['id', 'name'])
    ->asArray()
    ->all();

?>
<? $form = ActiveForm::begin(); ?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6"><?= $form->field($theNcc, 'status')->dropdownList($statusList)->label('Status') ?></div>
                <div class="col-md-6"><?= $form->field($theNcc, 'ma')->label('Mã NCC của KT') ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theNcc, 'ten')->label('Tên ngắn') ?></div>
                <div class="col-md-6"><?= $form->field($theNcc, 'venue_id')->dropdownList(ArrayHelper::map($venueList, 'id', 'name'), ['prompt'=>'- Chọn -'])->label('Link đến') ?></div>
            </div>
            <?= $form->field($theNcc, 'ten_cty')->label('Tên đầy đủ (cty)') ?>
            <?= $form->field($theNcc, 'diachi')->label('Địa chỉ') ?>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theNcc, 'mst')->label('MST') ?></div>
                <div class="col-md-6"><?= $form->field($theNcc, 'so_tk')->label('Số TK') ?></div>
            </div>
            <?= $form->field($theNcc, 'nganhang')->label('Ngân hàng') ?>
            <?= $form->field($theNcc, 'note')->textArea(['rows'=>5])->label('Note') ?>
            <div class="text-right"><?=Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']); ?></div>
        </div>
    </div>
</div>
<? ActiveForm::end();

$js = <<<TXT
$('#ncc2-venue_id').select2();
TXT;

$this->registerJs($js);