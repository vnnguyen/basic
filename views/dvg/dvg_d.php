<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_dvg_inc.php');

$this->title = 'Xoá chi phí dịch vụ: '.$theDvg['name'].' / '.$theDvg['cp']['name'];

$this->params['breadcrumb'][] = ['Xem', 'cp/r/'.$theDvg['id']];
$this->params['breadcrumb'][] = ['Xoá', 'cp/d/'.$theDvg['id']];

?>
<div class="col-md-8">
	<div class="alert alert-danger">
		<i class="fa fa-fw fa-warning"></i> Bạn sắp xoá một dòng giá chi phí
		<br>Thông tin đã xoá sẽ không lấy lại được. Bạn có chắc xoá không?
	</div>
	<form method="post" action="">
		<?= Html::hiddenInput('confirm', 'yes') ?>
		<?= Html::submitButton('Xoá ngay bây giờ', ['class'=>'btn btn-danger']) ?>
		hoặc <?= Html::a('Thôi, quay lại', '/dv/r/'.$theDvg['cp']['id']) ?>
	</form>
</div>
