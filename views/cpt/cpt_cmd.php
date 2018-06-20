<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_cpt_inc.php');
$this->title = 'Command-line CPT';

?>
<div class="col-md-8">
	<form method="post" action="">
		<p><?= Html::textarea('cmd', Yii::$app->request->post('cmd'), ['rows'=>10, 'class'=>'form-control', 'style'=>'font:18px/22px Courier New;']) ?></p>
		<p class="text-right"><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></p>
	</form>
	<table class="table table-condensed table-striped">
		<thead>
			<tr>
				<th>Name</th>
				<th>Value</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Command</td>
				<td><?= $theCmd ?></td>
				<td></td>
			</tr>
			<? foreach ($theParams as $i=>$p) { ?>
			<tr>
				<td><?= $p['name'] ?></td>
				<td><?= $p['value'] ?></td>
				<td><?= $i ?></td>
			</tr>
			<? } ?>
		</tbody>
	</table>
</div>