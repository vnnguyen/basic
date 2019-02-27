<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;


$this->title = 'Over view';
$this->params['breadcrumb'] = [
	['Venues', 'venues'],
];
// Yii::$app->params['body_class'] = 'sidebar-xs';
$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'Mới', 'link'=>'venues/c', 'active'=>SEG2 == 'c'],
	],
];

$classification_s = [Yii::t('ov', 'Standard'), Yii::t('ov', 'Superior'), Yii::t('ov', 'Deluxe'), Yii::t('ov', 'Luxury')];

$architecture_style_s = [Yii::t('ov', 'Small Building'), Yii::t('ov', 'Big Building'), Yii::t('ov', 'Colonial style'), Yii::t('ov', 'Tradition house'), Yii::t('ov', 'Bungalows'), Yii::t('ov', 'Atypical'),];
$property_type_s = [Yii::t('ov', 'hotel'), Yii::t('ov', 'apartments'), Yii::t('ov', 'villas'), Yii::t('ov', 'guest houses'), Yii::t('ov', 'farm stays'), Yii::t('ov', 'resorts'), Yii::t('ov', 'campsites'), Yii::t('ov', 'hostels'), Yii::t('ov', 'homestays'), Yii::t('ov', 'campsites'), Yii::t('ov', 'motels'), Yii::t('ov', 'lodges')
];
$style_s = [Yii::t('ov', 'Charming'), Yii::t('ov', 'Boutique'), Yii::t('ov', 'Character'), Yii::t('ov', 'International'), Yii::t('ov', 'No style'), ];
$facilities_s = [Yii::t('ov', 'Lift'), Yii::t('ov', 'Indoor Swimming pool'), Yii::t('ov', 'Outdoor Swimming pool'), Yii::t('ov', 'Garden'), Yii::t('ov', 'Private beach'), Yii::t('ov', 'Spa'), Yii::t('ov', 'Massage sauna'), Yii::t('ov', 'Bicycle or motorbike'), Yii::t('ov', 'Restaurant to recommend'), Yii::t('ov', 'Breakfast International buffet'), Yii::t('ov', 'Gym/ Fitness centre'), Yii::t('ov', 'Conference room'), Yii::t('ov', 'Disabled Facilities'), Yii::t('ov', 'Eco - Responsible Approach'), Yii::t('ov', 'Room service'), Yii::t('ov', 'Free wifi'), Yii::t('ov', 'Airport shuttle'), Yii::t('ov', 'Laundry service'), Yii::t('ov', 'Terrace'), Yii::t('ov', 'Pet allowed'), Yii::t('ov', 'Non-smoking room'), Yii::t('ov', 'Family rooms'), Yii::t('ov', 'Baby cot'), Yii::t('ov', 'Air conditioning'), Yii::t('ov', 'Bath tub'), Yii::t('ov', 'Balcony'), Yii::t('ov', 'Internet computers'), Yii::t('ov', 'Coffee and tea facilities'), Yii::t('ov', 'Electric kettle'), Yii::t('ov', 'iron'), Yii::t('ov', 'hair dresser'), Yii::t('ov', 'Electric fan'), Yii::t('ov', 'refrigerator'), Yii::t('ov', 'Massage'), Yii::t('ov', 'Sauna'), Yii::t('ov', 'Babysitter upon request'), Yii::t('ov','Kid’s pool'), Yii::t('ov','Meeting/ banquet facilities'), Yii::t('ov','Balcony'), Yii::t('ov','Telephone'), Yii::t('ov','TV'), Yii::t('ov','Airport drop off'), Yii::t('ov','Airport pick up'), Yii::t('ov','Children’s playground'), Yii::t('ov','BBQ facilities'), Yii::t('ov','Garden'), Yii::t('ov','Special diet menu'), Yii::t('ov','Baby sitting/ child service'), Yii::t('ov','The staff speaks English'),
Yii::t('ov','The staff speaks French'),];
sort($facilities_s);
$recommendedForList_s = ['Couple', 'Family', 'Group', 'Honeymoon', 'Demanding travelers', 'Old people', 'Young people', ];


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
$cnt = 0;
?>
<div class="col-md-6">
	<div class="">
		<div class="panel-body">
			<form method="POST">
				<div class="form-group">
					<strong> <?= ++$cnt . '. ' . Yii::t('ov', 'Classification')?></strong>
					<?= Html::radioList('classification', isset($over_view_options['classification']) ? $over_view_options['classification']: '', $classification, ['class' => '']) ?>
				</div>
				<div class="form-group">
					<strong> <?= ++$cnt . '. ' . Yii::t('ov', 'Architecture style')?></strong>
					<?= Html::radioList('architecture_style', isset($over_view_options['architecture_style']) ? $over_view_options['architecture_style']: '', $architecture_style, ['class' => '']) ?>
				</div>
				<div class="form-group">
					<strong> <?= ++$cnt . '. ' . Yii::t('ov', 'Property type')?></strong>
					<?= Html::radioList('property_type', isset($over_view_options['property_type']) ? $over_view_options['property_type']: '', $property_type, ['class' => '']) ?>
				</div>
				<div class="form-group">
					<strong> <?= ++$cnt . '. ' . Yii::t('ov', 'Style')?></strong>
					<?= Html::checkboxList('style', isset($over_view_options['style']) ? $over_view_options['style']: '', $style) ?>
				</div>
				<div class="form-group">
					<strong> <?= ++$cnt . '. ' . Yii::t('ov', 'Location')?></strong>

					<div class="input-group">
						<span class="input-group-addon"><?= Yii::t('ov', 'Distance from the city center')?> (km)</span>
						<?= Html::textInput('location_dis_center', isset($over_view_options['location_dis_center']) ? $over_view_options['location_dis_center']: '', ['class' => 'form-control', 'type' => 'number']) ?>
					</div>
					<div class="input-group">
						<span class="input-group-addon"><?= Yii::t('ov', 'Distance from the beach')?> (km)</span>
						<?= Html::textInput('location_dis_beach', isset($over_view_options['location_dis_beach']) ? $over_view_options['location_dis_beach']: '', ['class' => 'form-control', 'type' => 'number']) ?>
					</div>
					<div class="input-group">
						<span class="input-group-addon"><?= Yii::t('ov', 'Distance from the airport')?> (km)</span>
						<?= Html::textInput('location_dis_airport', isset($over_view_options['location_dis_airport']) ? $over_view_options['location_dis_airport']: '', ['class' => 'form-control', 'type' => 'number']) ?>
					</div>
				</div>
				<div class="form-group">
					<strong> <?= ++$cnt . '. ' . Yii::t('ov', 'Price range')  . ' (30-40, 60-80, ...)'?></strong>
					<?= Html::textInput('price_range', isset($over_view_options['price_range']) ? $over_view_options['price_range']: '', ['class' => 'form-control']) ?>
				</div>
				<div class="form-group">
					<strong> <?= ++$cnt . '. ' . Yii::t('ov', 'Facilities and services')?></strong>
					<?= Html::checkboxList('facilities', isset($over_view_options['facilities']) ? $over_view_options['facilities']: '', $facilities, ['item' => function($index, $label, $name, $checked, $value) {
						return  '<label class="mr-20">' . Html::checkbox($name, $checked, ['label' => $label, 'value' => $value, 'data-bind' => 'checked:fieldName']) . '</label>';
							}

					]) ?>
				</div>
				<div class="form-group">
					<strong> <?= ++$cnt . '. ' . Yii::t('ov', 'recommended')?></strong>
					<?= Html::checkboxList('recommended', isset($over_view_options['recommended']) ? $over_view_options['recommended']: '', $recommendedForList) ?>
				</div>
				<div class="form-group">
					<strong> <?= ++$cnt . '. ' . Yii::t('ov', 'Description')?></strong>
			        <?= Html::textarea('description', isset($over_view_options['description']) ? $over_view_options['description']: '', ['class' => 'form-control'])?>
			    </div>
				<div class="clearfix">
					<div class="pull-right">
						<?= Html::submitButton(Yii::t('ov', 'Save'), ['class' => 'form-control btn-primary', 'name' => 'btn_save']) ?>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
