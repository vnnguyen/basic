<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->title = 'Các tour dùng dịch vụ của: '.$theCompany['name'];
?>
<div class="col-md-12">
	<? if (empty($theTours)) { ?>
	<p>No data found.</p>
	<? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="30"></th>
					<th>Tour code</th>
					<th>Tên tour</th>
					<th>Trạng thái</th>
					<th>Khởi hành</th>
					<th>Số ngày</th>
					<th>Số pax</th>
				</tr>
			</thead>
			<tbody>
				<? $cnt = 0; foreach ($theTours as $tour) { $cnt ++; ?>
				<tr>
					<td><?= $cnt ?></td>
					<td class="text-nowrap"><?= Html::a($tour['code'], '@web/tours/r/'.$tour['id']) ?></td>
					<td class="text-nowrap"><?= Html::a($tour['name'], '@web/tours/r/'.$tour['id']) ?> </td>
					<td class="text-nowrap"><?= $tour['status'] == 'deleted' ? 'CANCELED' : 'OK' ?></td>
					<td class="text-nowrap"><?= date('d-m-Y', strtotime($tour['day_from'])) ?></td>
					<td class="text-nowrap"><?= $tour['day_count'] ?></td>
					<td class="text-nowrap"><?= $tour['pax'] ?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? } // if empty ?>
</div>