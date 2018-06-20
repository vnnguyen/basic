<?
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Bank accounts';
$this->params['icon'] = 'money';

$this->params['breadcrumb'] = [
	['Money', '@web/spaces/money'],
	['Bank accounts', '@web/baccounts'],
];

?>
<div class="col-md-12">
	<div class="table-responsive">
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th>Type</th>
					<th>Name</th>
					<th>Cur</th>
					<th>Information</th>
					<th>Note</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theBaccounts as $baccount) { ?>
				<tr>
					<td><?= $baccount['stype'] ?></td>
					<td><?= Html::a($baccount['name'], Url::to(['baccount/r', 'id'=>$baccount['id']])) ?></td>
					<td><?= $baccount['currency'] ?></td>
					<td><?= nl2br($baccount['info']) ?></td>
					<td><?= nl2br($baccount['note']) ?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
</div>
