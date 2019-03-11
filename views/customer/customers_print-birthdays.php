<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

Yii::$app->params['page_title'] = 'Customers birthdates ('.count($theUsers).')';
Yii::$app->params['page_icon'] = 'group';
Yii::$app->params['page_breadcrumbs'] = [
	['Customers', 'customers'],
	['Birthdates', 'customers/birthdays'],
];

?>
<div class="col-md-12">
	<?php if (empty($theUsers)) { ?>
	<div class="text-danger">No data found.</div>
	<?php } else { ?>
	<table class="table table-bordered table-narrow table-striped">
		<thead>
			<tr>
				<th width="30">Nationality</th>
				<th colspan="2">Name</th>
				<th width="80">DOB</th>
				<th width="30">Age</th>
				<th width="">Email</th>
				<th width="">Phone</th>
				<th width="">Address</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($theUsers as $li) { ?>
			<tr>
				<td><?= strtoupper($li['country_code']) ?></td>
				<td><?= $li['fname'] ?></td>
				<td><?= $li['lname'] ?></td>
				<td class="text-center"><?= $li['bday'] ?>/<?= $li['bmonth'] ?>/<?= $li['byear'] ?></td>
				<td class="text-center"><?= $li['byear'] == 0 ? '' : (date('Y') - $li['byear']) ?></td>
				<td>
					<?
					foreach ($li['metas'] as $meta) {
						if ($meta['name'] == 'email') {
							echo $meta['value'];
							break;
						}
					}
					?>
				</td>
				<td>
					<?
					foreach ($li['metas'] as $meta) {
						if ($meta['name'] == 'tel' || $meta['name'] == 'mobile') {
							echo $meta['value'];
							break;
						}
					}
					?>
				</td>
				<td>
					<?
					foreach ($li['metas'] as $meta) {
						if ($meta['name'] == 'address') {
							echo $meta['value'];
							break;
						}
					}
					?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php } ?>
</div>