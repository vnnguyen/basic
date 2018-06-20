<?
use yii\helpers\Html;
use app\helpers\DateTimeHelper;
use yii\widgets\LinkPager;
$this->title = 'Exchange rates';
$this->params['breadcrumb'] = [
	['Exchange rates', 'xrates'],
];
include('xrates__inc.php');
?>
<div class="col-lg-12">
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th>Time</th>
					<th width="100">Currency 1</th>
					<th width="100">Rate</th>
					<th width="100">Currency 2</th>
					<th>Note</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? if (empty($models)) { ?><tr><td colspan="6">No items found.</td></tr><? } ?>
				<? foreach ($models as $te) { ?>
				<tr>
					<td><?= Html::a(DateTimeHelper::format($te['rate_dt'], 'd-m-Y (D) H:i'), 'xrates/u/'.$te['id']) ?></td>
					<td>1 <?=$te['currency1']?> =</td>
					<td class="text-right"><?=number_format($te['rate'], 2)?></td>
					<td><?=$te['currency2']?></td>
					<td><?=$te['note']?></td>
					<td class="td-n">
						<a class="text-muted" title="<?=Yii::t('mn', 'Edit')?>" href="<?= DIR ?>xrates/u/<?=$te['id']?>"><i class="fa fa-edit"></i></a>
						<a class="text-muted" title="<?=Yii::t('mn', 'Delete')?>" href="<?= DIR ?>xrates/d/<?=$te['id']?>"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<div class="text-center">
	<?=LinkPager::widget(array(
		'pagination' => $pages,
	));
	?>
	</div>
</div>
