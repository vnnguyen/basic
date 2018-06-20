<?php
use yii\helpers\Html;

include('_events_inc.php');

$this->title = 'Delete event: '.$theEvent['name'];

?>
<div class="col-md-8">
<div class="alert alert-warning">
	<strong>CHÚ Ý: Bạn sắp xoá một sự kiện / sự việc</strong>
	<br />Thông tin đã xoá không thể lấy lại được. Hãy xác nhận dưới đây.
</div>
<form method="post" action="">
	<input type="hidden" name="action" value="delete" />
	<button class="btn btn-danger" type="submit">Tôi muốn xoá sự kiện này</button>
	<a class="" href="<?=DIR?>events">Thôi, quay lại</a>
</form>
</div>