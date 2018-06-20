<?
//myID == 1 || die('HUAN');
//include('_days_inc.php');
/*
$getType = fRequest::getValid('rtype', array('all', 'sample', 'ctr', 'tour'));
$getName = fRequest::get('name', 'string', '', true);
$getTag = fRequest::get('tag', 'string', '', true);
$getPage = fRequest::get('page', 'integer', 1, true);

$whereType = ' AND rid'.$getType == 'sample' ? '=0' : '!=0'; if ($getType == 'all') $whereType = '';
$whereName = ' AND LOCATE("'.$getName.'", name)!=0'; if ($getName == '') $whereName = '';
$whereTag = ' AND LOCATE("'.$getTag.'", note)!=0'; if ($getTag == '') $whereTag = '';

// Pages
$q = $db->query('SELECT COUNT(*) FROM at_days WHERE 1=1 '.$whereType.$whereName.$whereTag);
$pg = new hxPagination($q->fetchScalar(), '?rtype='.$getType.'&name='.$getName.'&tag='.$getTag.'&page=', $getPage, 20, 3);

// Get cases
$q = $db->query('SELECT * FROM at_days WHERE 1=1 '.$whereType.$whereName.$whereTag.' LIMIT '.$pg->limitFrom.', '.$pg->perPage);
$theDays = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

$metaT = 'Ngày tour ('.$pg->itemCount.')';
$pageM = 'ct';
$pageB = array(
	anchor('days', 'Ngày tour'),
	// anchor('days/r/'.$theDay['id'], $theDay['name']),
	);
include('__hd.php'); */
use yii\helpers\Html;
use yii\widgets\LinkPager;

Yii::$app->params['page_title'] = 'Sample tour days ('.$pagination->totalCount.')';

Yii::$app->params['page_breadcrumbs'] = [
	['B2B', 'b2b'],
	['Sample tour days'],
];

?>
<style>
.t_Content_light {font-size: 14px; line-height:1.5}
.t_Tooltip_light {width: 500px!important;}
</style>
<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-body">
			<form class="form-inline">
				<?= Html::dropdownList('language', $language, ['en'=>'English', 'fr'=>'Français'], ['class'=>'form-control']) ?>
				<?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Search name']) ?>
				<?= Html::textInput('tags', $tags, ['class'=>'form-control', 'placeholder'=>'Search tags']) ?>
				<?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
				<?= Html::a(Yii::t('app', 'Reset'), '/b2b/sample-tour-days') ?>
			</form>
		</div>
		<div class="table-responsive">
			<table id="tbl-days" class="table table-bordered table-condensed">
				<thead>
					<tr>
						<th>Lang</th>
						<th>Name & Content</th>
						<th>Meals</th>
						<th>Tags</th>
					</tr>
				</thead>
				<tbody>
				<? foreach ($theDays as $day) { ?>
				<tr>
					<td><?= strtoupper($day['language']) ?></td>
					<td>
						<span class="td-n tipped" data-tipped="nm-<?= $day['id'] ?>" data-tipped-options="inline: true, hook:'rightmiddle', maxWidth:640, skin:'royalblue', fadeDuration:0">
							<?= Html::a($day['ngaymau_title'], '/b2b/sample-tour-days-u/'.$day['id']) ?>
						</span>
						<div id="nm-<?=$day['id']?>" style="display:none;">
							<p><strong><?= $day['ngaymau_title']?> (<?=$day['ngaymau_meals'] ?>)</strong></p>
							<?= $day['ngaymau_body'] ?>
						</div>
						<? if ($day['ngaymau_title'] == '') { ?>
						<i class="tipped fa fa-info-circle text-muted" data-tipped="day-note-<?= $day['id'] ?>" data-tipped-options="inline: true, hook:'rightmiddle', maxWidth:640, skin:'royalblue', fadeDuration:0"></i>
						<div id="day-note-<?=$day['id']?>" style="display:none;">
							<?//= Html::encode($day['note']) ?>
						</div>
						<? } ?>
					</td>
					<td><?=$day['ngaymau_meals'] ?></td>
					<td><?=$day['ngaymau_tags'] ?></td>
				</tr>
				<? } ?>
				</tbody>
			</table>
		</div>
	</div>

	<? if ($pagination->totalCount > $pagination->pageSize) { ?>
	<div class="text-center">
	<?= LinkPager::widget(array(
		'pagination' => $pagination,
		'prevPageLabel'=>'<',
		'nextPageLabel'=>'>',
		'firstPageLabel'=>'<<',
		'lastPageLabel'=>'>>',
	));?>
	</div>
	<? } ?>
</div>

<?
$js = <<<'TXT'
Tipped.create(".tipped", 
function(element) {
	var tipID = $(element).data('tipped');
	return document.getElementById(tipID);
},
{
	inline: true,
	fadeIn: 0,
	fadeOut: 0,
	skin: 'light',
	border: { size: 8, color: '#000', opacity: .4 },
	radius: { size: 8, position: 'border' },
	maxWidth: 460,
	target: 'mouse',
	fixed: true,
	hook: { target: 'rightmiddle', tooltip: 'leftmiddle' },
	shadow: false
});
TXT;
$this->registerJsFile('/js/tipped/js/tipped/tipped.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerCssFile('/js/tipped/css/tipped.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);