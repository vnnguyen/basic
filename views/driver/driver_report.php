<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_drivers_inc.php');

$this->title = 'List: Tour drivers';
$this->params['icon'] = 'truck';
$this->params['breadcrumb'] = [
	['Drivers', 'drivers'],
	['Report'],
];

?>
<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-body">
			<form class="form-inline">
			<?= Html::textInput('month', $month, ['class'=>'form-control', 'placeholder'=>'yyyy or yyyy-mm']) ?>
			<?= Html::textInput('company', $company, ['class'=>'form-control', 'placeholder'=>'Search company']) ?>
			<?= Html::textInput('driver', $driver, ['class'=>'form-control', 'placeholder'=>'Search name']) ?>
			<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
			<?= Html::a('Reset', '/drivers/report') ?>
			</form>

			<? if (empty($theTourDrivers)) { ?>
			<div class="alert alert-warning">No drivers found.</div>
		</div>
	</div>
			<? } else { ?>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered table-condensed table-striped">
				<thead>
					<tr>
						<th>Tour</th>
						<th>Company</th>
						<th>Driver</th>
						<th>Service time</th>
						<th>Points</th>
					</tr>
				</thead>
				<tbody>
					<? foreach ($theTourDrivers as $driver) { ?>
					<tr>
						<td><?= $driver['tour']['op_code'] ?></td>
						<td><?= $driver['driver_company'] ?></td>
						<td>
							<?= $driver['driver_name'] ?>
							<?= $driver['driver_user_id'] == 0 ? '' : ' - '.Html::a('Link', '/drivers/r/'.$driver['driver_user_id'], ['target'=>'_blank']) ?>
						</td>
						<td>
							<?
							$from = date('j/n/Y', strtotime($driver['use_from_dt']));
							$until = date('j/n/Y', strtotime($driver['use_until_dt']));
							echo $from;
							if ($from != $until) {
								echo ' - ', $until;
							}
							?>
						</td>
						<td><?= $driver['points'] ?></td>
					</tr>
					<? } ?>
				</tbody>
			</table>
		</div>
	</div>
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
</div>