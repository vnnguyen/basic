<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_collections_inc.php');

$this->title = 'Gallery ảnh Amica Travel ('.number_format($pagination->totalCount, 0).')';

?>
<div class="col-md-12"><?
if (empty($theCollections)) { ?>
	<p>No collections found.</p><?
} else { ?>
	<div class="row"><?
	$cnt = 0;
	foreach ($theCollections as $collection) {
		$cnt ++; ?>
	<div class="col-lg-3 col-md-4 col-sm-6">
		<div class="row" style="padding:12px 0;">
			<div class="col-xs-6 col-sm-12 col-md-12 col-lg-12">
				<?= Html::a(Html::img('/timthumb.php?w=600&h=450&src='.$collection['image'], ['class'=>'img-responsive']), '@web/gallery/collections/r/'.$collection['id']) ?>
			</div>
			<div class="col-xs-6 col-sm-12 col-md-12 col-lg-12">
				<h3><?= Html::a($collection['title'], '@web/gallery/collections/r/'.$collection['id']) ?> <small><?= date('j/n/Y', strtotime($collection['event_date'])) ?></small></h3>
			</div>
		</div>
	</div>
	<div class="clearfix visible-xs-block"></div><?
		if (in_array($cnt, [2,4,6,8,10,12,14,16,18,20,22,24])) { ?>
	<div class="clearfix visible-sm-block"></div><?
		}
		if (in_array($cnt, [3,6,9,12,15,18,21,24])) { ?>
	<div class="clearfix visible-md-block"></div><?
		}
		if (in_array($cnt, [4,8,12,16,20,24])) { ?>
	<div class="clearfix visible-lg-block"></div><?
		}
	} // foreach collections
} // if empty collections
if ($pagination->pageSize < $pagination->totalCount) { ?>
	<div class="text-center">
	<?= LinkPager::widget([
		'pagination' => $pagination,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	]) ?>
	</div><?
} ?>
	</div>
</div>

