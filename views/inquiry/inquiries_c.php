<?
// contact admin.amica-travel.com/inquiries/x/maxId

use yii\helpers\Html;
include('_inquiries_inc.php');
$this->title = 'Import new inquiries';
$this->params['icon'] = 'plus';
$this->params['breadcrumb'] = [
	['Sales', '@web/spaces/sales'],
	['Inquiries', '@web/inquiries'],
	['Import', '@web/inquiries/c'],
];
?>
<div class="col-lg-12">
	<? if (empty($inquiries)) { ?>
	<div class="alert alert-warning">No inquiries found.</div>
	<? } else { ?>
	<div class="alert alert-success">These inquiries have been impoerted. Click a name to view or <a href="<?=DIR?>inquiries/c" class="alert-link">try importing more inquiries</a>.</div>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th>Time (UTC)</th>
					<th>Form</th>
					<th>Name</th>
					<th>Email</th>
					<th>IP address</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($inquiries as $li) {
				$eData = unserialize($li['data']);
				if (!isset($eData['country'])) $eData['country'] = '';
				?>
				<tr>
					<td class="text-nowrap"><?=substr($li['created_at'], 0, 16)?></td>
					<td class="text-nowrap"><?=$li['form_name']?></td>
					<td class="text-nowrap"><img src="http://my.amicatravel.com/images/flags/16x11/<?=$eData['country']?>.png"> <?=Html::a($li['name'], '@web/inquiries/r/'.$li['id'])?></td>
					<td class="text-nowrap"><?=$li['email']?></td>
					<td><?=Html::a($li['ip'], 'http://whatismyipaddress.com/ip/'.$li['ip'], ['rel'=>'external'])?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? } ?>
</div>
