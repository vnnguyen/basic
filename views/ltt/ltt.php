<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Các lượt thanh toán chi phí tour';
include('_ltt_inc.php');

?>
<div class="col-md-12">
	<? if (empty($theLttx)) { ?>
	<p>Không tìm thấy dữ liệu. <?= Html::a('Tạo lượt mới', '@web/ltt/c')?>.</p>
	<? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th>Ngày TT</th>
					<th>Tình trạng</th>
					<th>Tài khoản</th>
					<th>Mã phí</th>
					<th>Loại tiền</th>
					<th>Tỉ giá</th>
					<th>Ghi chú</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theLttx as $ltt) { ?>
				<tr>
					<td><?= date('j/n/Y', strtotime($ltt['payment_dt'])) ?></td>
					<td><?= $statusList[$ltt['status']] ?></td>
					<td><?= $ltt['tkgn'] ?></td>
					<td><?= $ltt['mp'] ?></td>
					<td><?= $ltt['currency'] ?></td>
					<td><?= $ltt['xrate'] ?></td>
					<td><?= $ltt['note'] ?></td>
					<td>
						<?= Html::a('<i class="fa fa-edit"></i>', '@web/ketoan/ltt/u/'.$ltt['id'], ['class'=>'text-muted'])?>
						<?= Html::a('<i class="fa fa-trash-o"></i>', '@web/ketoan/ltt/d/'.$ltt['id'], ['class'=>'text-muted'])?>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>

	<? if ($pagination->pageSize < $pagination->totalCount) { ?>
	<div class="text-center">
	<?= LinkPager::widget([
		'pagination' => $pagination,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	]) ?>
	</div>
	<? } ?>

	<? } ?>
</div>