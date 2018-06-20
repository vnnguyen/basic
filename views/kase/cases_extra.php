<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->params['icon'] = 'briefcase';
$this->params['breadcrumb'] = [
	['Sales', '@web'],
	['Cases', '@web/cases'],
];

$this->title = 'Công cụ đánh giá hồ sơ ('.number_format($pagination->totalCount, 0).')';

?>
<style>
.editable-click, a.editable-click, a.editable-click:hover {border:none!important;}
.editable-empty, a.editable-empty {color:#999!important; border-bottom:1px dotted #999!important; font-style:normal!important;}
i.fa-smile-o {color:#090!important;}
i.fa-frown-o {color:#c00!important;}
</style>
<div class="col-md-12">
	<form class="form-inline well well-sm">
		<?= Html::dropdownList('month', $month, ArrayHelper::map($monthList, 'ym', 'ym'), ['class'=>'form-control']) ?>
		<?= Html::dropdownList('b2c', $b2c, ['b2c'=>'Chỉ HS B2C', 'b2b'=>'Chỉ HS B2B', 'all'=>'Cả B2B và B2C'], ['class'=>'form-control']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', '@web/cases/extra') ?>
	</form>
	<? if (empty($theCases)) { ?>
	<p>No cases found.</p>
	<? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="50">ID</th>
					<th width="100">Ngày mở</th>
					<th width="100">Tên hồ sơ</th>
					<th width="100">Bán hàng</th>
					<th>Nguồn</th>
					<th>Liên hệ</th>
					<th>Tiềm năng (click để sửa)</th>
					<th>Ghi chú</th>
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
					<td class="text-muted"><?= $case['id'] ?></td>
					<td class="text-nowrap"><?=date_format(date_timezone_set(date_create($case['created_at']), timezone_open('Asia/Saigon')), 'j/n/Y H:i')?></td>
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
					<td>
						<? if ($case['stats']['destinations'] != '') { ?>
						<?= $case['stats']['destinations'] ?> <?= $case['stats']['pax_count_min'] ?>p <?= $case['stats']['day_count_min'] ?>d <?= date('j/n/Y', strtotime($case['stats']['avail_from_date'])) ?>
						<? } ?>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
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
$('a.editable').editable();
$('a.editable-prospect').editable({
	emptytext: 'Chưa xử lý',
	display: function(value, sourceData) {
		if (value == 1) {
			$(this).html('<i class="fa fa-frown-o"></i>');
		}
		if (value == 1) {
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
$this->registerCssFile(DIR.'assets/x-editable_1.5.1/css/bootstrap-editable.css', ['depends'=>'app\assets\MainAsset']);
$this->registerJsFile(DIR.'assets/x-editable_1.5.1/js/bootstrap-editable.min.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);