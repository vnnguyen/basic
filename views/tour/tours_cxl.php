<?
use yii\helpers\Html;

include('_tours_inc.php');

/*
	$db->query('UPDATE at_tours SET status="deleted" WHERE id=%i LIMIT 1', $theTour['id']);
	$db->query('UPDATE at_ct SET op_finish="canceled", op_finish_dt=%s WHERE id=%i LIMIT 1', NOW, $theCt['id']);
*/

$this->title = 'Cancel tour: '.$theTour['op_code'];
$this->params['breadcrumb'] = [
	['Tour operation', '#'],
	['Tours', 'tours'],
	[substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
	[$theTour['op_code'], 'tours/r/'.$theTourOld['id']],
	['Cancel', URI],
];
?>
<div class="col-md-8">
	<div class="alert alert-danger">
		<strong>CHÚ Ý:</strong> Chỉ người quản lý và người điều hành tour mới có thể huỷ tour. Các điều sau đây sẽ xảy ra trên hệ thống khi bạn quyết định huỷ tour này:
		<br />+ Tour được đánh dấu trạng thái BỊ HUỶ (canceled)
		<br />+ Các booking (đã được confirm) sẽ được chuyển thành BỊ HUỶ (canceled)
		<p>Are you sure you want to cancel this tour?</p>
	</div>
	<form method="post" action="">
		<?= Html::hiddenInput('confirm', 'cancel') ?>
		<div><?= Html::submitButton('Yes, cancel this tour', ['class'=>'btn btn-danger']) ?> or <?= Html::a('Go back', '@web/tours/r/'.$theTourOld['id']) ?></div>
	</form>
</div>