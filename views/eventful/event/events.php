<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_events_inc.php');

$this->title = 'Các sự kiện Amica Travel ('.number_format($pagination->totalCount, 0).')';

?>
<div class="col-md-12"><?
if (empty($theEvents)) { ?>
	<p>No events found.</p><?
} else { ?>
	<div class="row"><?
	$cnt = 0;
	$year = 0;
	foreach ($theEvents as $event) {
		if ($year != substr($event['from_dt'], 0, 4)) {
			$year = substr($event['from_dt'], 0, 4); ?>
		<!--div class="well"><?= $year ?></div--><?
		}
		$cnt ++; ?>
	<div class="col-lg-4 col-md-6 mb-1em">
		<div class="thumbnail">
			<?= Html::a(Html::img($event['image'], ['class'=>'img-responsive']), '@web/eventful/events/r/'.$event['id']) ?>
		</div>
		<h3>
			<?= Html::a($event['name'], '@web/eventful/events/r/'.$event['id']) ?>
			<small><?= date('j/n/Y', strtotime($event['from_dt'])) ?></small>
		</h3>
		<p><?= $event['summary'] ?></p>
	</div><?
		if (in_array($cnt, [2,4,6,8,10,12])) { ?>
	<div class="clearfix visible-md-block"></div><?
		}
		if (in_array($cnt, [3,6,9,12])) { ?>
	<div class="clearfix visible-lg-block"></div><?
		}
	} // foreach events
} // if empty events
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

