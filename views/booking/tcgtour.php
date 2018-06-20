<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_tcgtour_inc.php');

$this->title = 'Tam Coc Garden tours';

?>
<div class="col-lg-12">
	<? if (empty($theBookings)) { ?><p>No tours found.</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="120">Date</th>
					<th>Tour</th>
					<th>Title</th>
					<th width="40">Days</th>
					<th>Case name</th>
					<th width="80">Pax</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theBookings as $li) { ?>
				<tr>
					<td><?= $li['day_from'] ?></td>
					<td><?= $li['code'] ?></td>
					<td><?= Html::a($li['title'], 'v2ct/r/'.$li['ctid']) ?></td>
					<td><?= $li['days'] ?></td>
					<td><?= Html::a($li['name'], 'v2cases/r/'.$li['caseid']) ?></td>
					<td><?= $li['pax'] ?></td>
					<td>
						<a title="<?=Yii::t('mn', 'Edit')?>" href="<?=DIR?>bookings/u/<?= $li['id'] ?>"><i class="fa fa-edit"></i></a>
						<a title="<?=Yii::t('mn', 'Delete')?>" href="<?=DIR?>bookings/d/<? $li['id'] ?>"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? } // if no tours ?>
</div>
