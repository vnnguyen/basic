<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->title = 'Lịch tour của xe Hoàng Phú ('.$getMonth.')';
$this->params['breadcrumb'] = [
	['Tools', '@web/tools'],
	['Xe Hoàng Phú', '@web/tools/xehoangphu'],
];
$dow = ['Hai', 'Ba', 'Tư', 'Năm', 'Sáu', 'Bảy', 'CN'];
?>
<div class="col-md-12">
	<form class="form-inline well well-sm">
		<?= Html::dropdownList('month', $getMonth, ArrayHelper::map($monthList, 'ym', 'ym'), ['class'=>'form-control', 'prompt'=>'Tháng']) ?>
		<?= Html::submitButton('Xem lịch', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', '@web/tools/xehoangphu') ?>
	</form>
	<div class="row">
		<div class="col-md-6">
			<div class="table-responsive">
				<p><strong>CÁC DV HOÀNG PHÚ CỦA CÁC TOUR KHỞI HÀNH TRONG THÁNG</strong></p>
				<table class="table table-condensed table-bordered">
					<thead>
						<tr>
							<th width="120">Tour / khởi hành</th>
							<th width="80">Ngày</th>
							<th width="80">Nội dung</th>
							<th width="30">SL</th>
							<th width="80">Đơn vị</th>
							<th width="80">Giá</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<? foreach ($theCptx2 as $cpt) { ?>
						<tr>
							<td class="text-nowrap">
								<? if ($cpt['status'] == 'deleted') { ?><span class="label label-danger">CXL</span><? } ?>
								<?= Html::a($cpt['code'], '@web/tours/services/'.$cpt['id']) ?>
								<?= $cpt['name'] ?>
								/
								<?= $cpt['day_from'] ?>
							</td>
							<td class="text-nowrap"><?= $cpt['dvtour_day'] ?></td>
							<td class="text-nowrap"><?= Html::a($cpt['dvtour_name'], '@web/cpt/r/'.$cpt['dvtour_id']) ?></td>
							<td class="text-nowrap text-center"><?= number_format($cpt['qty'], 0) ?></td>
							<td class="text-nowrap text-center"><?= $cpt['unit'] ?></td>
							<td class="text-nowrap text-right">
								<?= number_format($cpt['price'], 0) ?>
								<span class="text-muted"><?= $cpt['unitc'] ?></span>
							</td>
							<td></td>
						</tr>
						<? } ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-md-6">
			<p><strong>CÁC DV HOÀNG PHÚ SỬ DỤNG TRONG THÁNG</strong></p>
			<div class="table-responsive">
				<table class="table table-condensed table-bordered">
					<thead>
						<tr>
							<th width="120">Tour / khởi hành</th>
							<th width="80">Ngày</th>
							<th width="80">Nội dung</th>
							<th width="30">SL</th>
							<th width="80">Đơn vị</th>
							<th width="80">Giá</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<? foreach ($theCptx as $cpt) { ?>
						<tr>
							<td class="text-nowrap">
								<? if ($cpt['status'] == 'deleted') { ?><span class="label label-danger">CXL</span><? } ?>
								<?= Html::a($cpt['code'], '@web/tours/services/'.$cpt['id']) ?>
								<?= $cpt['name'] ?>
								/
								<?= $cpt['day_from'] ?>
							</td>
							<td class="text-nowrap"><?= $cpt['dvtour_day'] ?></td>
							<td class="text-nowrap"><?= Html::a($cpt['dvtour_name'], '@web/cpt/r/'.$cpt['dvtour_id']) ?></td>
							<td class="text-nowrap text-center"><?= number_format($cpt['qty'], 0) ?></td>
							<td class="text-nowrap text-center"><?= $cpt['unit'] ?></td>
							<td class="text-nowrap text-right">
								<?= number_format($cpt['price'], 0) ?>
								<span class="text-muted"><?= $cpt['unitc'] ?></span>
							</td>
							<td></td>
						</tr>
						<? } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>