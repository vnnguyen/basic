<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\helpers\DateTimeHelper;

include('_mails_inc.php');

?>
<div class="col-md-12">
	<? if (empty($theMappings)) { ?>
	<p>No mappings found.</p>
	<? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="100">Email</th>
					<th width="50">Action</th>
					<th>Case Id</th>
					<th width="30"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theMappings as $mapping) { ?>
				<tr>
					<td class="text-nowrap"><?= $mapping['email'] ?></td>
					<td><?= $mapping['action'] ?></td>
					<td><?= $mapping['case_id'] ?></td>
					<td class="text-nowrap">
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<div class="text-center">
		<?=LinkPager::widget(array(
		'pagination' => $pages,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
		));?>
	</div>
	<? } ?>
</div>
