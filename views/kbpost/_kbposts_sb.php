<?
use yii\helpers\Html;
?>
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