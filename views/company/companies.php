<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Companies ('.$pagination->totalCount.')';
$this->params['breadcrumb'] = [
	['Companies', '@web/companies'],
];
$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New', 'link'=>'companies/c', 'active'=>SEG2 == 'c'],
	],
];
?>
<div class="col-md-12">
	<form class="form-inline well well-sm">
		<?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Search name']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', '@web/companies') ?>
	</form>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="">Company name</th>
					<th width="">Website</th>
					<th width="">Address</th>
					<th width="">Venues</th>
					<th width="">Services</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? if (empty($theCompanies)) { ?><tr><td colspan="7">No data found.</td></tr><? } ?>
				<? foreach ($theCompanies as $company) { ?>
				<tr>
					<td><?=Html::a($company['name'], '@web/companies/r/'.$company['id'])?></td>
					<td><?
					if (count($company['metas']) > 0) {
						foreach ($company['metas'] as $company2) {
							if ($company2['k'] == 'website') {
								echo Html::a($company2['v'], $company2['v'], ['rel'=>'external']);
								break;
							}
						}
					}
					?></td>
					<td><?
					if (count($company['metas']) > 0) {
						foreach ($company['metas'] as $company2) {
							if ($company2['k'] == 'address') {
								echo $company2['v'];
								break;
							}
						}
					}
					?></td>
					<td><?
					if (count($company['venues']) > 0) {
						foreach ($company['venues'] as $company2) {
							echo Html::a($company2['name'], 'venues/r/'.$company2['id']), ' &nbsp; ';
						}
					}
					?></td>
					<td><?= $company['info'] ?></td>
					<td class="muted td-n">
						<a title="<?=Yii::t('mn', 'Edit')?>" class="text-muted" href="<?=DIR?>companies/u/<?=$company['id']?>"><i class="fa fa-edit"></i></a>
						<a title="<?=Yii::t('mn', 'Delete')?>" class="text-muted" href="<?=DIR?>companies/d/<?=$company['id']?>"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<div class="text-center">
	<?=LinkPager::widget(array(
		'pagination' => $pagination,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	));?>
	</div>
</div>
