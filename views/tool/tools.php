<?
use yii\helpers\Html;

$this->title = 'Các tiện ích';
$this->params['breadcrumb'] = [['Tools', '@web/tools']];

?>
<div class="col-md-12">
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width=""></th>
					<th width="">Tên</th>
					<th width="">Miêu tả</th>
					<th width="">Ghi chú</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>1</td>
					<td><?= Html::a('Nhà dân', '@web/tools/nhadan') ?></td>
					<td>Tra cứu nhà dân xem có đoàn khách nghỉ hay không</td>
					<td></td>
				</tr>
				<tr>
					<td>2</td>
					<td><?= Html::a('Lịch khách trả tiền', '@web/tools/lichkhachtratien') ?></td>
					<td>Tra cứu lịch thu tiền của khách mua tour theo invoice</td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>