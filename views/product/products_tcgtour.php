<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_tcgtour_inc.php');

$this->title = 'TCG tours';

?>
<div class="col-lg-12">
	<? if (empty($models)) { ?><p>No tours found.</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="50">Op?</th>
					<th width="100">Start date</th>
					<th>Tour code, name & description</th>
					<th width="40">Days</th>
					<th width="40">Pax</th>
					<th width="50">Itinerary</th>
					<th>Owner</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($models as $li) { ?>
				<tr>
					<td><?= strtoupper($li['status']) ?></td>
					<td><?= $li['start_date'] ?></td>
					<td>
						<?= Html::a($li['code'], 'products/r/'.$li['id']) ?>
						-
						<?= Html::a($li['name'], 'products/r/'.$li['id']) ?>
						<span class="text-muted"><?= $li['about'] ?></span>
					</td>
					<td class="text-center"><?= $li['day_count'] ?></td>
					<td class="text-center"><?= $li['pax_count'] ?></td>
					<td class="text-center"><?= Html::a($li['ct_id'], 'ct/r/'.$li['ct_id']) ?></td>
					<td><?= $li['updated_by'] ?></td>
					<td>
						<a title="<?=Yii::t('mn', 'Edit')?>" class="text-muted" href="<?=DIR?>products/tcgtour/u/<?= $li['id'] ?>"><i class="fa fa-edit"></i></a>
						<a title="<?=Yii::t('mn', 'Delete')?>" class="text-muted" href="<?=DIR?>products/tcgtour/d/<? $li['id'] ?>"><i class="fa fa-trash-o"></i></a>
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
