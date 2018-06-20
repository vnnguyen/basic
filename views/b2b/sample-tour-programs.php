<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_days_inc.php');

Yii::$app->params['page_title'] = 'Sample tour programs ('.$pagination->totalCount.')';

Yii::$app->params['page_breadcrumbs'] = [
	['B2B', 'b2b'],
	['Sample tour programs'],
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
				<?= Html::dropdownList('language', $language, ['en'=>'English', 'fr'=>'Français'], ['class'=>'form-control', 'prompt'=>'Language']) ?>
				<?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Search name']) ?>
				<?= Html::dropdownList('days', $days, ['01-07'=>'1 to 7 days', '07-14'=>'7 to 14 days', '15-up'=>'15+ days'], ['class'=>'form-control', 'prompt'=>'Length']) ?>
				<?= Html::textInput('tags', $tags, ['class'=>'form-control', 'placeholder'=>'Search tags']) ?>
				<?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
				<?= Html::a(Yii::t('app', 'Reset'), '/b2b/sample-tour-programs') ?>
			</form>
		</div>
		<div class="table-responsive">
			<table id="tbl-days" class="table table-bordered table-condensed">
				<thead>
					<tr>
						<th>Lang</th>
						<th>Name & Content</th>
						<th>Days</th>
						<th>Tags</th>
						<th>Updated</th>
					</tr>
				</thead>
				<tbody>
				<? foreach ($thePrograms as $program) { ?>
				<tr>
					<td><?= strtoupper($program['language']) ?></td>
					<td>
						<span class="td-n tipped" data-tipped="nm-<?= $program['id'] ?>" data-tipped-options="inline: true, hook:'rightmiddle', maxWidth:640, skin:'royalblue', fadeDuration:0">
							<?= Html::a($program['title'], '/products/u/'.$program['id']) ?>
						</span>
						<div id="nm-<?=$program['id']?>" style="display:none;">
							<p><strong><?= $program['title']?></strong></p>
							<?= $program['intro'] ?>
						</div>
					</td>
					<td class="text-center"><?= count($program['days']) ?></td>
					<td><?= Html::encode($program['tags']) ?></td>
					<td><?= $program['updatedBy']['name'] ?></td>
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