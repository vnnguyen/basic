<?
use yii\helpers\Html;

$this->title = 'Knowledge base';
$this->params['icon'] = 'puzzle-piece';
$this->params['small'] = 'Cơ sở dữ liệu Kiến thức';
$this->params['breadcrumb'] = [
	['Kiến thức', 'kb'],
];

?>
<div class="col-md-8">
	<p><strong>LATEST POSTS</strong></p>
	<ul>
		<? foreach ($kbPosts as $li) { ?>
		<li><a href="<?=DIR?>kb/posts/r/<?=$li['id']?>"><?=$li['title']?></a> <?=$li['author']['name']?> <em><?=date('j/n/Y', strtotime($li['created_at']))?></em></li>
		<? } ?>
	</ul>
	<p><strong>SPECIAL LISTS</strong></p>
	<ul>
		<? foreach ($kbLists as $li) { ?>
		<li><i class="fa fa-list"></i> <a href="<?=DIR?>kb/lists/<?=$li['alias']?>"><?=$li['title']?></a></li>
		<? } ?>
	</ul>
</div>
<div class="col-md-4">
	<p><i class="fa fa-folder-open"></i> <strong>CATEGORIES / CÁC CHỦ ĐỀ</strong></p>
	<div class="mb-1em" style="margin-left:1em;">
	<?
	foreach (Yii::$app->params['amica/kb/cats'] as $cat) {
?>
	<div><?= str_repeat('&mdash;', $cat['depth'] - 1)?> <?= Html::a($cat['name'], '@web/kb/posts?cat='.$cat['id']) ?></div>
<?
	}
	?>
	</div>

	<p><i class="fa fa-tag"></i> <strong>TAGS / CÁC THẺ</strong></p>
	<div class="mb-1em" style="margin-left:1em;"></div>

	<p><i class="fa fa-star"></i> <strong>TOP POSTS / BÀI NỔI BẬT</strong></p>
	<div class="mb-1em" style="margin-left:1em;"></div>
</div>
