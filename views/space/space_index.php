<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

//include('_kbposts_inc.php');
$this->title  = 'Work spaces';

?>
<div class="col-md-8">
	<div class="table-responsive">
		<table class="table table-condensed table-hover table-bordered">
			<thead>
				<tr>
					<th width="20"></th>
					<th>Title</th>
					<th>Author</th>
					<th>Updated</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theSpaces as $space) { ?>
				<tr>
					<td><img src="<?= $space['author']['image'] ?>" style="width:20px; height:20px;"></td>
					<td>
						<? if ($space['status'] != 'on') { ?><span class="label label-default status-<?=$space['status']?>"><?=$space['status']?></span><? } ?>
						<a href="<?=DIR?>kb/posts/r/<?=$space['id']?>"><?= $space['name'] ?></a>
					</td>
					<td><?=$space['author']['name']?></td>
					<td><?=date('d-m-Y', strtotime($space['updated_dt']))?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<div class="text-center">
	<?=LinkPager::widget(array(
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'pagination' => $pagination,
		'lastPageLabel' => '>>',
		'nextPageLabel' => '>',
	));?>
	</div>
</div>
<div class="col-md-4">

</div>
