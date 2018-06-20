<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->params['icon'] = 'briefcase';
$this->params['breadcrumb'] = [
	['Sales', '@web'],
	['Cases', '@web/cases'],
	['Stats', '@web/cases/stats'],
];

$this->title = 'Đánh giá yêu cầu khách hàng qua các hồ sơ ('.number_format($pagination->totalCount, 0).')';

?>
<style>
.editable-click, a.editable-click, a.editable-click:hover {border:none!important;}
.editable-empty, a.editable-empty {color:#999!important; border-bottom:1px dotted #999!important; font-style:normal!important;}
i.fa-smile-o {color:#090!important;}
i.fa-frown-o {color:#c00!important;}
</style>
<div class="col-md-12">
	<form class="form-inline panel-search">
		<?= Html::dropdownList('month', $month, ArrayHelper::map($monthList, 'ym', 'ym'), ['class'=>'form-control']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
	</form>

	<? if (empty($theCases)) { ?>
	<p>No cases found.</p>
	<? } else { ?>

	<div class="panel panel-default">
		<div class="table-responsive">
			<table class="table table-bordered table-condensed table-striped">
				<thead>
					<tr>
						<th>Ngày mở</th>
						<th>Tên hồ sơ</th>
						<th>Bán hàng</th>
						<th>Nguồn biết</th>
						<th>Liên hệ qua</th>
						<th>Tiềm năng</th>
						<th>Điểm đến</th>
						<th>Số pax</th>
						<th>Độ tuổi</th>
						<th>Số ngày</th>
						<th>Khởi hành</th>
						<th>Kiểu tour</th>
						<th>Kiểu đi</th>
						<th>Tag</th>
					</tr>
				</thead>
				<tbody>
					<? foreach ($theCases as $case) {
						$class = 'meh';
						if ($case['stats']['prospect'] < 3) {
							$class = 'frown';
						} elseif ($case['stats']['prospect'] > 3) {
							$class = 'smile';
						}
						?>
					<tr>
						<td class="text-nowrap"><?=date_format(date_timezone_set(date_create($case['created_at']), timezone_open('Asia/Saigon')), 'j/n')?></td>
						<td class="text-nowrap">
							<?= Html::a($case['name'], '@web/cases/r/'.$case['id'], ['style'=>$case['is_priority'] == 'yes' ? 'font-weight:bold' : '', 'rel'=>'external']) ?>
							<? if ($case['status'] == 'onhold') { ?><i class="text-warning fa fa-clock-o"></i><? } ?>
							<? if ($case['status'] == 'closed') { ?><i class="text-muted fa fa-lock"></i><? } ?>
							<? if ($case['deal_status'] == 'won') { ?><i class="text-success fa fa-dollar"></i><? } ?>
							<? if ($case['deal_status'] == 'lost' || ($case['status'] == 'closed' && $case['deal_status'] != 'won')) { ?><i class="text-danger fa fa-dollar"></i><? } ?>
						</td>
						<td class="text-nowrap">
							<?=Html::a($case['owner']['name'], '@web/users/r/'.$case['owner']['id'])?>
						</td>
						<td class="text-nowrap"><?= $case['how_found'] ?></td>
						<td class="text-nowrap"><?= $case['how_contacted'] ?> / <?= $case['web_referral'] ?></td>
						<td class="text-nowrap"><?= Html::a(str_repeat('<i class="fa fa-'.$class.'-o"></i> ', $case['stats']['prospect']), '#', ['class'=>'editable-prospect', 'data-name'=>'prospect', 'data-type'=>'select', 'data-pk'=>$case['id'], 'data-url'=>DIR.URI, 'data-title'=>'Tiềm năng']) ?></td>
						<td class="text-nowrap"><?= Html::a($case['stats']['pa_destinations'], '#', ['class'=>'editable-destinations', 'data-name'=>'destinations', 'data-type'=>'text', 'data-pk'=>$case['id'], 'data-url'=>DIR.URI]) ?></td>
						<td class="text-nowrap"><?= Html::a($case['stats']['pa_pax'], '#', ['class'=>'editable-pax', 'data-name'=>'pax', 'data-type'=>'text', 'data-pk'=>$case['id'], 'data-url'=>DIR.URI]) ?></td>
						<td class="text-nowrap"><?= Html::a($case['stats']['pa_pax_ages'], '#', ['class'=>'editable-pax_ages', 'data-name'=>'pax_ages', 'data-type'=>'text', 'data-pk'=>$case['id'], 'data-url'=>DIR.URI]) ?></td>
						<td class="text-nowrap"><?= Html::a($case['stats']['pa_days'], '#', ['class'=>'editable-days', 'data-name'=>'days', 'data-type'=>'text', 'data-pk'=>$case['id'], 'data-url'=>DIR.URI]) ?></td>
						<td class="text-nowrap"><?= Html::a($case['stats']['pa_start_date'], '#', ['class'=>'editable-start_date', 'data-name'=>'start_date', 'data-type'=>'text', 'data-pk'=>$case['id'], 'data-url'=>DIR.URI]) ?></td>
						<td class="text-nowrap"><?= Html::a($case['stats']['pa_tour_type'], '#', ['class'=>'editable-tour_type', 'data-name'=>'tour_type', 'data-type'=>'text', 'data-pk'=>$case['id'], 'data-url'=>DIR.URI]) ?></td>
						<td class="text-nowrap"><?= Html::a($case['stats']['pa_group_type'], '#', ['class'=>'editable-group_type', 'data-name'=>'group_type', 'data-type'=>'text', 'data-pk'=>$case['id'], 'data-url'=>DIR.URI]) ?></td>
						<td class="text-nowrap"><?= Html::a($case['stats']['pa_tags'], '#', ['class'=>'editable-tags', 'data-name'=>'tags', 'data-type'=>'text', 'data-pk'=>$case['id'], 'data-url'=>DIR.URI]) ?></td>
					</tr>
					<? } ?>
				</tbody>
			</table>
		</div>
	</div>

	<? if ($pagination->pageSize < $pagination->totalCount) { ?>
	<div class="text-center">
	<?= LinkPager::widget([
		'pagination' => $pagination,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	]) ?>
	</div>
	<? } ?>

	<? } ?>
</div>
<?
$js = <<<TXT

$('a.editable-destinations').editable({});
$('a.editable-pax').editable({});
$('a.editable-pax_ages').editable({});
$('a.editable-days').editable({});
$('a.editable-start_date').editable({});
$('a.editable-tour_type').editable({});
$('a.editable-group_type').editable({});
$('a.editable-tags').editable({});

$('a.editable-prospect').editable({
	display: function(value, sourceData) {
		if (value == 1) {
			$(this).html('<i class="fa fa-frown-o"></i>');
		}
		if (value == 2) {
			$(this).html('<i class="fa fa-frown-o"></i> <i class="fa fa-frown-o"></i>');
		}
		if (value == 3) {
			$(this).html('<i class="fa fa-meh-o"></i> <i class="fa fa-meh-o"></i> <i class="fa fa-meh-o"></i>');
		}
		if (value == 4) {
			$(this).html('<i class="fa fa-smile-o"></i> <i class="fa fa-smile-o"></i> <i class="fa fa-smile-o"></i> <i class="fa fa-smile-o"></i>');
		}
		if (value == 5) {
			$(this).html('<i class="fa fa-smile-o"></i> <i class="fa fa-smile-o"></i> <i class="fa fa-smile-o"></i> <i class="fa fa-smile-o"></i> <i class="fa fa-smile-o"></i>');
		}
	},
	showbuttons:false,
	source: [
		{value: 1, text: '+'},
		{value: 2, text: '++'},
		{value: 3, text: '+++'},
		{value: 4, text: '++++'},
		{value: 5, text: '+++++'}
	]
});
TXT;
if (USER_ID != 1 || isset($_GET['x'])) {
$this->registerCssFile('//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js', ['depends'=>'app\assets\MainAsset']);
} else {
$this->registerCssFile(DIR.'assets/x-editable_1.5.1/css/bootstrap-editable.css', ['depends'=>'app\assets\MetronicAsset']);
$this->registerJsFile(DIR.'assets/x-editable_1.5.1/js/bootstrap-editable.min.js', ['depends'=>'app\assets\MetronicAsset']);
}

$this->registerJs($js);