<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_dv_inc.php');

Yii::$app->params['page_title'] = 'Xoá dịch vụ & chi phí: '.$theDv['name'];

Yii::$app->params['page_breadcrumbs'][] = ['Xem', 'dv/r/'.$theDv['id']];
Yii::$app->params['page_breadcrumbs'][] = ['Xoá'];

?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Delete confirmation</h6>
        </div>
        <div class="panel-body">
            <div class="alert alert-danger">
                <i class="fa fa-fw fa-warning"></i> Bạn sắp xoá một dịch vụ. Các chi phí liên quan cũng sẽ bị xoá.
                <br>Thông tin đã xoá sẽ không lấy lại được. Bạn có chắc xoá không?
            </div>
            <form method="post" action="">
                <?= Html::hiddenInput('confirm', 'yes') ?>
                <?= Html::submitButton('Xoá ngay bây giờ', ['class'=>'btn btn-danger']) ?>
                hoặc <?= Html::a('Thôi, quay lại', '/dv/r/'.$theDv['id']) ?>
            </form>
        </div>
    </div>
</div>
