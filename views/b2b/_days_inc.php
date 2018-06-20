<?

$dayTypes = array(
	'sample'=>'Làm mẫu',
	'ctr'=>'Chương trình',
	'tour'=>'Ngày tour',
);

$ctrTourTypes = array(
	'private'=>'Tour riêng',
	'vpc'=>'Tour VPC',
);

$ctrStatuses = array(
	'on'=>'Được dùng',
	'off'=>'Không dùng',
	'draft'=>'Nháp',
	'deleted'=>'Bị xoá',
);

$dayMealList = [
	'---'=>'---',
	'B--'=>'B--',
	'-L-'=>'-L-',
	'--D'=>'--D',
	'BL-'=>'BL-',
	'B-D'=>'B-D',
	'-LD'=>'-LD',
	'BLD'=>'BLD',
];

Yii::$app->params['page_actions'] = [
	[
		['label'=>'Days', 'link'=>'b2b/sample-tour-days'],
		['icon'=>'plus', 'title'=>'Add new', 'link'=>'b2b/sample-tour-days-c'],
	],
	[
		['label'=>'Programs', 'link'=>'b2b/sample-tour-programs'],
		['icon'=>'plus', 'title'=>'Add new', 'link'=>'b2b/sample-tour-programs-c'],
	],
];

Yii::$app->params['page_icon'] = 'calendar';
