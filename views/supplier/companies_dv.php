<?
use yii\helpers\Html;

$this->title = 'Dịch vụ của: '.$theCompany['name'];
?>
<div class="col-md-8">
	<div class="table-responsive">
		<table class="table table-condensed table-striped table-bordered">
			<thead>
				<tr>
					<th>Venue</th>
					<th>Grouping</th>
					<th>Name</th>
					<th>Unit</th>
					<th>Info</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theDvx as $dv) { ?>
				<tr>
					<td><?= $dv['venue_id'] ?></td>
					<td><?= $dv['grouping'] ?></td>
					<td><?= Html::a($dv['name'], 'dv/u/'.$dv['id']) ?></td>
					<td><?= $dv['unit'] ?></td>
					<td><?= $dv['info'] ?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
</div>