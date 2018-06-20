<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;


$this->title = 'Venues';
$this->params['breadcrumb'] = [
	['Venues', 'venues'],
];
$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'Mới', 'link'=>'venues/c', 'active'=>SEG2 == 'c'],
	],
];

$typeList = [
	// ''=>'',
	'all'=>'All types',
	'hotel'=>'Hotels',
	'home'=>'Local homes',
	'cruise'=>'Cruise vessels',
	'restaurant'=>'Restaurants',
	'sightseeing'=>'Sightseeing spots',
	'train'=>'Night trains',
	'other'=>'Other',
];

$statusList = [
	'all'=>'All status',
	'on'=>'On',
	'off'=>'Off',
	'draft'=>'Draft',
	'deleted'=>'Deleted',
];
// options over view
$classification_s = [''=>Yii::t('ov', 'Select classification'), Yii::t('ov', 'Standard'), Yii::t('ov', 'Superior'), Yii::t('ov', 'Deluxe'), Yii::t('ov', 'Luxury')];

$architecture_style_s = [''=>Yii::t('ov', 'Select architecture'), Yii::t('ov', 'Small Building'), Yii::t('ov', 'Big Building'), Yii::t('ov', 'Colonial style'), Yii::t('ov', 'Tradition house'), Yii::t('ov', 'Bungalows'), Yii::t('ov', 'Atypical'),];
$property_type_s = [''=>Yii::t('ov', 'Select property type'), Yii::t('ov', 'hotel'), Yii::t('ov', 'apartments'), Yii::t('ov', 'villas'), Yii::t('ov', 'guest houses'), Yii::t('ov', 'farm stays'), Yii::t('ov', 'resorts'), Yii::t('ov', 'campsites'), Yii::t('ov', 'hostels'), Yii::t('ov', 'homestays'), Yii::t('ov', 'campsites'), Yii::t('ov', 'motels'), Yii::t('ov', 'lodges')
];
$style_s = [Yii::t('ov', 'Charming'), Yii::t('ov', 'Boutique'), Yii::t('ov', 'Character'), Yii::t('ov', 'International'), Yii::t('ov', 'No style'), ];
$facilities_s = [Yii::t('ov', 'Lift'), Yii::t('ov', 'Indoor Swimming pool'), Yii::t('ov', 'Outdoor Swimming pool'), Yii::t('ov', 'Garden'), Yii::t('ov', 'Private beach'), Yii::t('ov', 'Spa'), Yii::t('ov', 'Massage sauna'), Yii::t('ov', 'Bicycle or motorbike'), Yii::t('ov', 'Restaurant to recommend'), Yii::t('ov', 'Breakfast International buffet'), Yii::t('ov', 'Gym/ Fitness centre'), Yii::t('ov', 'Conference room'), Yii::t('ov', 'Disabled Facilities'), Yii::t('ov', 'Eco - Responsible Approach'), Yii::t('ov', 'Room service'), Yii::t('ov', 'Free wifi'), Yii::t('ov', 'Airport shuttle'), Yii::t('ov', 'Laundry service'), Yii::t('ov', 'Terrace'), Yii::t('ov', 'Pet allowed'), Yii::t('ov', 'Non-smoking room'), Yii::t('ov', 'Family rooms'), Yii::t('ov', 'Baby cot'), Yii::t('ov', 'Air conditioning'), Yii::t('ov', 'Bath tub'), Yii::t('ov', 'Balcony'), Yii::t('ov', 'Internet computers'), Yii::t('ov', 'Coffee and tea facilities'), Yii::t('ov', 'Electric kettle'), Yii::t('ov', 'iron'), Yii::t('ov', 'hair dresser'), Yii::t('ov', 'Electric fan'), Yii::t('ov', 'refrigerator'), Yii::t('ov', 'Massage'), Yii::t('ov', 'Sauna'), Yii::t('ov', 'Babysitter upon request'), Yii::t('ov','Kid’s pool'), Yii::t('ov','Meeting/ banquet facilities'), Yii::t('ov','Balcony'), Yii::t('ov','Telephone'), Yii::t('ov','TV'), Yii::t('ov','Airport drop off'), Yii::t('ov','Airport pick up'), Yii::t('ov','Children’s playground'), Yii::t('ov','BBQ facilities'), Yii::t('ov','Garden'), Yii::t('ov','Special diet menu'), Yii::t('ov','Baby sitting/ child service'), Yii::t('ov','The staff speaks English'),
Yii::t('ov','The staff speaks French'),];
$recommendedForList_s = ['Couple', 'Family', 'Group', 'Honeymoon', 'Demanding travelers', 'Old people', 'Young people', ];
// end options
sort($facilities_s);
$classification = [];
foreach ($classification_s as $key => $value) {
	if ($key === '') {$classification[$key] = $value; continue; }
	$classification['cla_'.$key] = $value;
}
$architecture_style = [];
foreach ($architecture_style_s as $key => $value) {
	if ($key === '') {$architecture_style[$key] = $value; continue; }
	$architecture_style['arc_'.$key] = $value;
}
$property_type = [];
foreach ($property_type_s as $key => $value) {
	if ($key === '') {$property_type[$key] = $value; continue; }
	$property_type['pro_'.$key] = $value;
}
$style = [];
foreach ($style_s as $key => $value) {
	if ($key === '') {$style[$key] = $value; continue; }
	$style['sty_'.$key] = $value;
}
$facilities = [];
foreach ($facilities_s as $key => $value) {
	if ($key === '') {$facilities[$key] = $value; continue; }
	$facilities['fac_'.$key] = $value;
}
$recommendedForList = [];
foreach ($recommendedForList_s as $key => $value) {
	if ($key === '') {$recommendedForList[$key] = $value; continue; }
	$recommendedForList['rec_'.$key] = $value;
}
?>
<style>
	.date_hover{color: #f3f3f3;}
</style>
<div class="col-md-12">
	<form method="get" action="" class="form-inline well well-sm">
		<?= Html::dropdownList('type', $getType, $typeList, ['class'=>'form-control']) ?>
		<?= Html::dropdownList('destination_id', $getDestinationId, ArrayHelper::map($allDestinations, 'id', 'name_en'), ['class'=>'form-control', 'prompt'=>'All destinations']) ?>
		<?= Html::dropdownList('status', $getStatus, $statusList, ['class'=>'form-control']) ?>
		<?= Html::textInput('name', $getName, ['class'=>'form-control', 'placeholder'=>'Search name']) ?>
		<div class="clearfix"></div>
		<?= Html::dropdownList('classification', $getClassification, $classification, ['class'=>'form-control']) ?>
		<?= Html::dropdownList('architecture_style', $getArchitecture_style, $architecture_style, ['class'=>'form-control']) ?>
		<?= Html::dropdownList('property_type', $getProperty_type, $property_type, ['class'=>'form-control']) ?>
		<?= Html::dropdownList('style', $getStyle, $style, ['class'=>'form-control multiselect_style', 'multiple'=>'multiple']) ?>
		<?= Html::dropdownList('facilities', $getFacilities, $facilities, ['class'=>'form-control multiselect_facilities', 'multiple'=>'multiple']) ?>
		<?= Html::dropdownList('recommended', $getRecommended, $recommendedForList, ['class'=>'form-control multiselect_recommended', 'multiple'=>'multiple']) ?>
		<?= Html::textInput('price_range', $getPrice_range, ['class'=>'form-control', 'placeholder'=>'Search price']) ?>









		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Reset', 'venues') ?>
	</form>
	<? if (empty($theVenues)) { ?><p>No data found</p><? } else { ?>
	<div class="table-responsive">
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th width="50">ID</th>
					<th width="">Name</th>
					<th width="">Địa điểm</th>
					<th width="">Info</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($theVenues as $li) { ?>
				<tr>
					<td class="text-muted text-center"><?= $li['id'] ?></td>
					<td>
						<? if ($li['stype'] == 'home') { ?><i class="text-danger fa fa-home"></i><? } ?>
						<? if ($li['stype'] == 'hotel') { ?><i class="fa fa-buiding-o"></i><? } ?>
						<?=Html::a($li['name'], 'venues/r/'.$li['id'])?>
					</td>
					<td><?= $li['destination']['name_vi'] ?></td>
					<td><?= substr($li['info'], 0, 200) ?></td>
					<td class="muted td-n">
						<a class="text-muted" title="<?=Yii::t('mn', 'Edit')?>" href="<?=DIR?>venues/u/<?=$li['id']?>"><i class="fa fa-edit"></i></a>
						<a class="text-muted" title="<?=Yii::t('mn', 'Delete')?>" href="<?=DIR?>venues/d/<?=$li['id']?>"><i class="fa fa-trash-o"></i></a>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
	<? if ($pages->totalCount > $pages->pageSize) { ?>
	<div class="text-center">
	<?= LinkPager::widget([
		'pagination' => $pages,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	]);
	?>
	</div>
	<? } ?>
	<? } ?>
</div>



<?php
$this->registerJsFile('assets_limitless/js/plugins/forms/selects/bootstrap_multiselect.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('assets_limitless/js/plugins/forms/styling/uniform.min.js', ['depends' => 'app\assets\MainAsset']);
$this->registerJsFile('assets_limitless/js/plugins/forms/selects/bootstrap_multiselect.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('assets_limitless/js/plugins/forms/selects/bootstrap_multiselect.js', ['depends' => 'yii\web\JqueryAsset']);
?>
<?php
$js = <<<TXT
$('.multiselect_style').multiselect({
    nonSelectedText: 'Select_style',
    onChange: function() {
        $.uniform.update();
    }
});
$('.multiselect_facilities').multiselect({
    nonSelectedText: 'Select_facilities',
    onChange: function() {
        $.uniform.update();
    }
});
$('.multiselect_recommended').multiselect({
    nonSelectedText: 'Select_recommended',
    onChange: function() {
        $.uniform.update();
    }
});

// multiselect_facilities
// multiselect_recommended
TXT;
$js = str_replace(['Select_style', 'Select_facilities', 'Select_recommended'], [Yii::t('ov', 'select style'),Yii::t('ov', 'select facilities'), Yii::t('ov', 'select recommended')], $js);
$this->registerJs($js);

?>
