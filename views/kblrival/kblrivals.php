<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Rivals';
$this->params['small'] = 'Các công ty cạnh tranh';
$this->params['icon'] = 'home';
$this->params['breadcrumb'] = [
	['Community', '#'],
	['Knowledge base', '@web/kb'],
	['Lists', '@web/kb/lists'],
	['Rivals', '@web/kb/lists/rivals'],
];
$this->params['active'] = 'kb';
$this->params['active2'] = 'kblist';

?>
<div class="col-lg-12">
	<div class="table-responsive">
		<table class="table table-condensed table-hover">
			<thead>
				<tr>
					<th width="200">Name</th>
					<th width="50">Since</th>
					<th>Strength</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($models as $li) { ?>
				<tr>
					<td>
						<?=Html::a($li['name'], '@web/kb/lists/rivals/r/'.$li['id'])?>
						<a title="<?=$li['website']?>" class="text-muted" href="<?=$li['website']?>" rel="external"><i class="icon-external-link"></i></a>
					</td>
					<td><?=$li['byear'] != '0000' ? $li['byear'] : ''?></td>
					<td>
						<div style="color:#ccc; height:20px; overflow:hidden"><?=$li['diemmanh']?></div>
					</td>
					<td>
						<a title="Edit" class="text-muted" href="<?=DIR?>kb/lists/rivals/u/<?=$li['id']?>"><i class="fa fa-edit"></i></a>
						<a title="Delete" class="text-muted" href="<?=DIR?>kb/lists/rivals/d/<?=$li['id']?>"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<div class="text-center">
	<?=LinkPager::widget(array(
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'pagination' => $pages,
		'lastPageLabel' => '>>',
		'nextPageLabel' => '>',
	));?>
	</div>
</div>