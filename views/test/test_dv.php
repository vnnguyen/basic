<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

Yii::$app->params['page_title'] = 'Các dịch vụ tour';
Yii::$app->params['page_breadcrumbs'] = [
	['Dữ liệu', '@web/test'],
	['Dịch vụ tour', '@web/test/dv'],
];

$dvTypeList = [
	1=>'Đi lại, vận chuyển',
	2=>'Ngủ nghỉ',
	3=>'Ăn uống',
	4=>'Tham quan, mua sắm, xem',
	5=>'Giấy tờ thủ tục',
	6=>'Guide, porter, dịch',
	7=>'Chăm sóc sức khoẻ',
	8=>'Học tập, hội họp',
	9=>'Loại khác',
];
?>
<div class="col-md-12">
	<form class="well well-sm form-inline">
		<?= Html::dropdownList('type', $type, $dvTypeList, ['class'=>'form-control', 'prompt'=>'Loại dv']) ?>
		<?= Html::dropdownList('dest', $dest, ArrayHelper::map($destList, 'id', 'name_vi'), ['class'=>'form-control', 'prompt'=>'Địa điểm']) ?>
		<?= Html::dropdownList('provider', $provider, ArrayHelper::map($venueList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'Nhà cung cấp']) ?>
		<?= Html::textInput('name', $name, ['class'=>'form-control']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', '@web/test/dv') ?>
		| <?= Html::a('+Thêm dv', '/test/dvc') ?>
	</form>
	<div class="table-responsive">
		<table class="table table-condensed table-bordered table-striped">
			<thead>
				<tr>
					<th>Xem</th>
					<th>Sửa	</th>
					<th>Loại DV</th>
					<th>Địa điểm</th>
					<th>Tên & Nhà cung cấp</th>
					<th>Thời hạn</th>
					<th>Điều kiện</th>
					<th>Ghi chú</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theDvx as $dv) { ?>
				<?
if ($dv['venue_id'] != 0) {
	$dv['name'] = str_replace(':p', Html::a($dv['venue']['name'], '/venues/r/'.$dv['venue_id']), $dv['name']);
}
				?>
				<tr>
					<td><?= Html::a('Xem', '/test/dvr?id='.$dv['id']) ?></td>
					<td><?= Html::a('Sửa', '/test/dvu?id='.$dv['id']) ?></td>
					<td><?= $dvTypeList[$dv['stype']] ?></td>
					<td><?= $dv['destination']['name_vi'] ?></td>
					<td><?= $dv['name'] ?> <?= $dv['provider_id'] != 0 ? ' ('.Html::a($dv['provider']['name'], '/companies/r/'.$dv['provider_id']).')' : '' ?></td>
					<td><?= $dv['id'] ?></td>
					<td><?= $dv['conditions'] ?></td>
					<td><?= $dv['note'] ?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
</div>
