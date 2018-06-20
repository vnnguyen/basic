<?
use yii\helpers\Html;

Yii::$app->params['page_title'] = 'Countries';
Yii::$app->params['page_breadcrumbs'] = [
	['Countries', 'countries'],
];
Yii::$app->params['body_class'] = 'bg-white';
?>
<div class="col-lg-12">
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="50">Code</th>
					<th width="">English name</th>
					<th width="">Vietnamese name</th>
					<th width="">French name</th>
					<th width="">Local name</th>
					<th width="">Dial code</th>
				</tr>
			</thead>
			<tbody>
				<? if (empty($models)) { ?><tr><td colspan="7">No items found.</td></tr><? } ?>
				<? foreach ($models as $te) { ?>
				<tr>
					<td style="white-space:nowrap;"><span class="flag-icon flag-icon-<?=$te['code']?>"></span> <?= strtoupper($te['code']) ?></td>
					<td>
						<?=$te['name_en']?>
						<?=Html::a('<i class="fa fa-external-link"></i>', 'http://en.wikipedia.org/wiki/'.str_replace(' ', '_', $te['name_en']), ['rel'=>'external', 'class'=>'text-muted'])?>
					</td>
					<td><?=$te['name_vi']?></td>
					<td><?=$te['name_fr']?></td>
					<td><?=$te['name_local']?></td>
					<td class="text-right" style="white-space:nowrap;">+<?=$te['dial_code']?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
</div>
