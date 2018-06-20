<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_cpo_inc.php');

$this->title = '[OLD] Xoá giá chi phí dịch vụ: '.$theCpo['name'].' / '.$theCpo['dvo']['name'];

$this->params['breadcrumb'][] = ['Xem', 'cpo/r/'.$theCpo['id']];
$this->params['breadcrumb'][] = ['Xoá', 'cpo/d/'.$theCpo['id']];

?>
<div class="col-md-8">
	<div class="alert alert-danger">
		<i class="fa fa-fw fa-warning"></i> Bạn sắp xoá một dòng giá chi phí
		<br>Thông tin đã xoá sẽ không lấy lại được. Bạn có chắc xoá không?
	</div>
	<form method="post" action="">
		<?= Html::hiddenInput('confirm', 'yes') ?>
		<?= Html::submitButton('Xoá ngay bây giờ', ['class'=>'btn btn-danger']) ?>
		hoặc <?= Html::a('Thôi, quay lại', '/dvo/r/'.$theCpo['dvo']['id']) ?>
	</form>
</div>
