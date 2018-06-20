<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use app\helpers\DateTimeHelper;

include('_diemlx_inc.php');


?>
<div class="col-md-12">
	<form class="form-inline well well-sm">
		<?= Html::dropdownList('tour_id', $getTourId, ArrayHelper::map($tourList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'- Tour -']) ?>
		<?= Html::dropdownList('driver_user_id', $getDriverId, ArrayHelper::map($driverList, 'id', 'name'), ['class'=>'form-control', 'prompt'=>'- Lái xe -']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', '@web/tools/diemlx') ?>
	</form>
	<? if (empty($theEntries)) { ?>
	<p>No data found.</p>
	<? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="">Tour</th>
					<th width="">Lái xe</th>
					<th width="">Từ ngày</th>
					<th width="">Đến ngày</th>
					<th width="">Điểm</th>
					<th>Ghi chú</th>
					<th width="">Update</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theEntries as $entry) { ?>
				<tr>
					<td class="text-nowrap"><?= Html::a($entry['tour']['code'], '@web/tours/r/'.$entry['tour']['id']) ?> <?= $entry['tour']['name'] ?></td>
					<td class="text-nowrap"><?= Html::a($entry['driver']['name'], '@web/users/r/'.$entry['driver']['id']) ?> <?= $entry['driver']['phone'] ?></td>
					<td class="text-nowrap"><?= date('d-m-Y', strtotime($entry['from_dt'])) ?></td>
					<td class="text-nowrap"><?= date('d-m-Y', strtotime($entry['until_dt'])) ?></td>
					<td class="text-nowrap text-center"><?= $entry['points'] ?></td>
					<td><?= $entry['note'] ?></td>
					<td class="text-nowrap"><?= $entry['updatedBy']['name'] ?>, <?= DateTimeHelper::convert($entry['updated_at'], 'd-m-Y H:i', 'UTC', 'Asia/Ho_Chi_Minh') ?></td>
					<td>
						<a class="text-muted" title="<?=Yii::t('mn', 'Edit')?>" href="<?= DIR ?>tools/diemlx?action=u&id=<?= $entry['id'] ?>"><i class="fa fa-edit"></i></a>
						<a class="text-muted" title="<?=Yii::t('mn', 'Delete')?>" href="<?= DIR ?>tools/diemlx?action=d&id=<?= $entry['id'] ?>"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? if ($pages->pageSize < $pages->totalCount) { ?>
	<div class="text-center">
		<?= LinkPager::widget([
			'pagination' => $pages,
			'firstPageLabel' => '<<',
			'prevPageLabel' => '<',
			'nextPageLabel' => '>',
			'lastPageLabel' => '>>',
		]) ?>
	</div>
	<? } // if pages ?>
	<? } // if empty ?>
</div>