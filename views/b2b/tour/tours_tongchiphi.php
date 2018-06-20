<?
use yii\helpers\Html;

$this->title = 'Chi phí tour tháng '.$month.' (x tour)';
$this->params['breadcrumb'] = [
	['Tours', 'tours'],
	[$month, 'tours?month='.$month],
	['Tổng chi phí', 'tours/tongchiphi?month='.$month],
];
$this->params['icon'] = 'dollar';

?>
<div class="col-lg-12">
	<div class="alert alert-info">
		<strong>Chú ý:</strong> Để copy dữ liệu bảng này sang Excel, chọn cả bảng ở IMS rồi sang Excel, chọn Paste Special (menu Edit) -> Paste as Unicode text
	</div>
	<form method="get" action="" class="well well-sm form-inline">
		<select class="form-control" name="month" style="width:150px">
			<option value="0">All months</option>
		</select>
		<select class="form-control" style="width:auto;" name="se">
			<option value="0">- Bán hàng -</option>
		</select>
		<select class="form-control" style="width:auto;" name="op">
			<option value="0">- Điều hành -</option>
		</select>
		<select class="form-control" style="width:auto;" name="cs">
			<option value="0">- CSKH -</option>
		</select>
		<select class="form-control" style="width:auto;" name="status">
			<option value="0">- Trạng thái -</option>
		</select>
		<select class="form-control" style="width:auto;" name="orderby">
			<option value="tourcode">Sắp theo tour code</option>
			<option value="startdate">Sắp theo ngày vào</option>
		</select>
		<button type="submit" class="btn blue">Go</button>
		<?= Html::a('Reset', DIR.URI) ?>
	</form>
	<div class="table-responsive">
		<table id="tourlist" class="table table-condensed table-bordered">
			<thead><tr>
				<th width="30">#</th>
				<th width="30">Vào</th>
				<th width="30">Ra</th>
				<th>Code / Tên tour</th>
				<th>Seller</th>
				<th class="text-right">Ngày</th>
				<th class="text-right">Pax</th>
				<th width="120" class="text-right">Chi phí</th>
				<th width="50"></th>
			</tr></thead>
			<tbody>
			<?
$tourSes = [];
$tourOps = [];

			$theTotal = 0;
			$dayIn = '';
			$cnt = 0;
			foreach ($theTours as $tour) {
				/*
				$tour['cs'] = 0;
				$tour['op'] = 0;
				if (1 == 1
				&& ($getSe == 0 || ($getSe != 0 && $tour['se'] == $getSe))
				&& ($getOp == 0 || ($getOp != 0 && isset($monthTourOpList[$tour['id']]) && in_array($getOp, $monthTourOpList[$tour['id']])))
				&& ($getCs == 0 || ($getCs != 0 && isset($monthTourCsList[$tour['id']]) && in_array($getCs, $monthTourCsList[$tour['id']])))
				) {*/
				$cnt ++;
				
			?>
			<tr>
				<td class="text-center text-muted"><?=$cnt?></td>
				<td><?
				if ($dayIn != $tour['day_from']) {
					$dayIn = $tour['day_from'];
					echo substr($dayIn, -2);
				}
					?>
				</td>
				<td><?=date('d', strtotime($tour['day_from'].' + '.($tour['day_count'] - 1).'days'))?></td>
				<td>
					<?=$tour['status'] == 'deleted' ? '<strong style="color:#c00;">(CXL)</strong> ' : ''?>
					<?= Html::a($tour['code'].' - '.$tour['name'], 'tours/r/'.$tour['id'])?>				
				</td>
				<td><? foreach ($tourSes as $u) { if ($u['id'] == $tour['se']) { ?><?=$u['name']?></em><? break; } } ?></td>
				<td class="text-right" style="white-space:nowrap;"><?= Html::a($tour['day_count'].' ngày', '/tours/services/'.$tour['id'])?></td>
				<td class="text-right" style="white-space:nowrap;"><?= Html::a($tour['pax'].' pax', 'tours/pax/'.$tour['id'])?></td>
				<td class="text-right"><?= number_format($result[$tour['id']], 0)?></td>
				<td class="muted">VND</td>
			</tr>
			<?
					$theTotal += $result[$tour['id']];
				//} // Conditions
			}
			?>
			<tr>
				<td colspan="7" class="text-right">Thống kê tại thời điểm <?=date('d/m/Y H:i')?></td>
				<td style="white-space:nowrap;" class="text-right"><strong><?=number_format($theTotal, 0)?></strong></td>
				<td>VND</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
<style>
tr.selected td {background-color:#ffc;}
</style>
<script>
$(function(){
	$('#tourlist tr td').click(function(){
		$('#tourlist tr.selected').removeClass('selected');
		$(this).parent().addClass('selected');
	});
});
</script>