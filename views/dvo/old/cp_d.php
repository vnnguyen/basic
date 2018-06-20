<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Markdown;

include('_cp_inc.php');

$this->title = 'Delete: '.$theCp['name'];
if ($theCp['venue_id'] != 0) {
	$this->title .= ' @'.Html::a($theCp['venue']['name'], 'venues/r/'.$theCp['venue_id']);
}
$this->params['breadcrumb'][] = ['Xem', 'cp/r/'.$theCp['id']];

?>
<div class="col-md-8">
	<div class="alert alert-danger">
		<i class="fa fa-warning"></i>
		Chi phí dịch vụ và các bảng giá của nó sẽ bị xoá.<br>
		Are you sure you want to delete this?
	</div>
	<form method="post" action="" class="form-inline well well-sm">
		<select name="confirm" class="form-control">
			<option value="no">- Select -</option>
			<option value="delete">Yes, delete it now</option>
		</select>
		<?= Html::submitButton('Submit', ['class'=>'btn btn-danger']) ?>
	</form>
</div>
