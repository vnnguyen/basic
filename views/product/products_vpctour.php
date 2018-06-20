<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_vpctour_inc.php');

$this->title = 'VPC tours';

?>
<div class="col-lg-12">
	<? if (empty($models)) { ?><p>No tours found.</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="100">Khởi hành</th>
					<th>Tên chương trình</th>
					<th width="50">Ngày</th>
					<th width="50">Pax</th>
					<th>CT</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($models as $li) { ?>
				<tr>
					<td><?= $li->start_date ?></td>
					<td><?= Html::a($li->name, 'products/vpctour/r/'.$li->id) ?></td>
					<td class="text-center"><?= $li->day_count ?></td>
					<td class="text-center"><?= $li->min_pax ?> - <?= $li->max_pax ?></td>
					<td><?= Html::a($li['ct_id'], 'ct/r/'.$li['ct_id']) ?></td>
					<td>
						<a title="<?=Yii::t('mn', 'Edit')?>" class="text-muted" href="<?=DIR?>products/vpctour/u/<?= $li->id ?>"><i class="fa fa-edit"></i></a>
						<a title="<?=Yii::t('mn', 'Delete')?>" class="text-muted" href="<?=DIR?>products/vpctour/d/<? $li->id ?>"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<div class="text-center">
		<?=LinkPager::widget(array(
		'pagination' => $pages,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
		));?>
	</div>
	<? } // if no tours ?>
</div>
