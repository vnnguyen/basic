<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_cp_inc.php');

Yii::$app->params['page_breadcrumbs'][] = ['Chi phí', 'cp'];

if ($theCp->isNewRecord) {
    $this->title = 'Thêm chi phí mới';
    Yii::$app->params['page_breadcrumbs'][] = ['Thêm'];
} else {
    $this->title = 'Sửa chi phí: '.$theCp['name'];
    Yii::$app->params['page_breadcrumbs'][] = ['Xem', 'cp/r/'.$theCp['id']];
    Yii::$app->params['page_breadcrumbs'][] = ['Sửa'];
}


?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Cost details</h6>
        </div>
        <div class="panel-body">
            <? $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-6 mb-10">
                    Venue:<br><?= $theVenue ? Html::a($theVenue['name'], '/venues/r/'.$theVenue['id']) : '( default )' ?>
                </div>
                <div class="col-md-6 mb-10">
                    Supplier:<br><?= $theCompany ? Html::a($theCompany['name'], '/suppliers/r/'.$theCompany['id']) : '( default )' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theCp, 'stype')->dropdownList($cpTypeList, ['prompt'=>'- Select -']) ?></div>
                <div class="col-md-6"><?= $form->field($theCp, 'tk')->label('Mã tài khoản kế toán') ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theCp, 'grouping') ?></div>
                <div class="col-md-6"><?= $form->field($theCp, 'name') ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theCp, 'unit') ?></div>
                <div class="col-md-6"><?= $form->field($theCp, 'search') ?></div>
            </div>
            <?= $form->field($theCp, 'note')->textArea(['rows'=>5]) ?>
            <div class="text-right"><?=Html::submitButton('Save changes', ['class' => 'btn btn-primary']); ?></div>
            <? ActiveForm::end(); ?>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Related costs</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-xxs">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($relatedCpx as $cp) { ?>
                    <tr>
                        <td><?= $cp['stype'] ?></td>
                        <td><?= Html::a($cp['name'], '/cp/u/'.$cp['id']) ?></td>
                        <td><?= $cp['unit'] ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>