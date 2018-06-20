<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

Yii::$app->params['page_title'] = 'Thong ke so khach, so dem khach san, tau Ha Long, nha dan';
Yii::$app->params['page_breadcrumbs'] = [];
$venueTypes = ['hotel'=>'Khách sạn', 'home'=>'Nhà dân', 'cruise'=>'Tàu ngủ đêm', 'restaurant'=>'Nhà hàng'];
$sum = ['b'=>0, 'p'=>0, 'n'=>0];
?>
<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h6 class="panel-title">Thống kê năm 2015. Chú ý: Các khách sạn chỉ dùng tắm sáng có số đêm = 0 (số trong report không chính xác)</h6>
		</div>
		<div class="panel-body">
			<form class="form-inline">
				<?= Html::dropdownList('dest', $dest, ArrayHelper::map($destList, 'id', 'name_vi'), ['class'=>'form-control', 'prompt'=>'- Chọn -']) ?>
				<?= Html::dropdownList('type', $type, $venueTypes, ['class'=>'form-control', 'prompt'=>'- Chọn -']) ?>
				<?= Html::dropdownList('star', $star, ['2'=>'2 sao', '3'=>'3 sao', '4'=>'4 sao', '5'=>'5 sao'], ['class'=>'form-control', 'prompt'=>'- Chọn -']) ?>
				<?= Html::dropdownList('zero', $zero, ['yes'=>'Xem tất cả tên', 'no'=>'Chỉ xem tên có booking'], ['class'=>'form-control']) ?>
				<?= Html::dropdownList('orderby', $orderby, ['name'=>'Xếp theo tên', 'stars'=>'Xếp theo số sao', 'bookings'=>'Xếp theo số booking'], ['class'=>'form-control']) ?>
				<?= Html::dropdownList('sort', $sort, ['desc'=>'Thứ tự giảm dần', 'asc'=>'Thứ tự tăng dần'], ['class'=>'form-control']) ?>
				<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
				<?= Html::a('Đặt lại', '/test/chinh') ?>
			</form>
		</div>
		<table class="table table-condensed table-bordered table-striped">
			<thead>
				<tr>
					<th>Địa điểm</th>
					<th>Loại dịch vụ</th>
					<th>Tên</th>
					<th>Sao</th>
					<th>Số booking</th>
					<th>Số khách</th>
					<th>Số đêm</th>
				</tr>
			</thead>
			<tbody>
			<? if (empty($results)) { ?><tr><td colspan="7">Không có kết quả</td></tr><? } else { ?>
			<? foreach ($results as $result) { ?>
			<?
			$sum['b'] += $result['bookings_2015'];
			$sum['p'] += $result['pax_2015'];
			$sum['n'] += $result['nights_2015'];
			?>
			<tr>
				<td><?= $result['name_vi'] ?></td>
				<td><?= $venueTypes[$result['venue_type']] ?></td>
				<td><?= Html::a($result['venue_name'], '@web/venues/r/'.$result['venue_id'], ['target'=>'_blank']) ?></td>
				<td class="text-center"><?= $result['venue_stars'] > 1 ? $result['venue_stars'] : '' ?></td>
				<td class="text-right"><?= $result['bookings_2015'] ?></td>
				<td class="text-right"><?= $result['pax_2015'] ?></td>
				<td class="text-right"><?= $result['nights_2015'] ?></td>
			</tr>
			<? } ?>
			<tr>
				<th colspan="4" class="text-right">Tổng (<?= Yii::$app->formatter->asSpellout($sum['p']) ?> pax)</th>
				<th class="text-right"><?= $sum['b'] ?></th>
				<th class="text-right"><?= $sum['p'] ?></th>
				<th class="text-right"><?= $sum['n'] ?></th>
			</tr>
			<? } ?>
		</table>
	</div>
</div>