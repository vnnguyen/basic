<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

include('_permission_inc.php');

$this->title = 'Assign permission: '.$thePermission['name'];
$this->params['breadcrumb'][] = ['View', 'permissions/r/'.$thePermission['id']];

?>
<div class="col-md-8">
	<table class="table table-bordered table-condensed table-stripedx">
		<tbody>
			<tr><td width="100"><strong>Name:</strong></td><td><?= $thePermission['name'] ?> (<?= $thePermission['alias'] ?>)</td></tr>
			<tr><td><strong>Description:</strong></td><td><?= $thePermission['info'] ?></td></tr>
		</tbody>
	</table>
	<table class="table table-condensed table-striped table-bordered">
		<thead>
			<tr>
				<th>Name</th>
				<th width="40"></th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($theGroups as $group) { ?>
			<tr>
				<td>
					<?= Html::checkbox('allow_group_'.$group['id'], null, null, ['tag'=>'span', 'label'=>$group['name']]) ?>
					<?//= Html::a($group['name'], 'groups/r/'.$group['id']) ?>
				</td>
				<td>

				</td>
			</tr>
			<? } ?>
		</tbody>
	</table>
</div>
