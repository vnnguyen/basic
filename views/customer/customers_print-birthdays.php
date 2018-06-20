<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;


$this->title = 'Customers birthdates ('.count($theUsers).')';
$this->params['icon'] = 'group';
$this->params['breadcrumb'] = [
	['Customers', 'customers'],
	['Birthdates', 'customers/birthdays'],
];

?>
<div class="col-md-12">
	<? if (empty($theUsers)) { ?><p>No data found.</p><? } else { ?>
	<table class="table table-bordered table-condensed table-striped">
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
			<? foreach ($theUsers as $li) { ?>
			<tr>
				<td><?= strtoupper($li['country_code']) ?></td>
				<td><?= $li['fname'] ?></td>
				<td><?= $li['lname'] ?></td>
				<td class="text-center"><?= $li['bday'] ?>/<?= $li['bmonth'] ?>/<?= $li['byear'] ?></td>
				<td class="text-center"><?= $li['byear'] == 0 ? '' : (date('Y') - $li['byear']) ?></td>
				<td>
					<?
					foreach ($li['metas'] as $meta) {
						if ($meta['k'] == 'email') {
							echo $meta['v'];
							break;
						}
					}
					?>
				</td>
				<td>
					<?
					foreach ($li['metas'] as $meta) {
						if ($meta['k'] == 'tel' || $meta['k'] == 'mobile') {
							echo $meta['v'];
							break;
						}
					}
					?>
				</td>
				<td>
					<?
					foreach ($li['metas'] as $meta) {
						if ($meta['k'] == 'address') {
							echo $meta['v'];
							break;
						}
					}
					?>
				</td>
			</tr>
			<? } ?>
		</tbody>
	</table>
	<? } ?>
</div>