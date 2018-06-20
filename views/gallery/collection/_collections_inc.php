<?

Yii::$app->params['page_icon'] = 'picture-o';
Yii::$app->params['page_breadcrumbs'] = [
	['Gallery', 'gallery'],
	['Albums', 'gallery/collections'],
];
Yii::$app->params['page_actions'] = [
	[
		['icon'=>'plus', 'label'=>'Thêm', 'link'=>'gallery/collections/c', 'active'=>SEG3 == 'c',],
	],
];

if (isset($theCollection['id'])) {
	Yii::$app->params['page_actions'][] = [
		['icon'=>'eye', 'title'=>'View', 'link'=>'gallery/collections/r/'.$theCollection['id'], 'active'=>SEG3 == 'r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'gallery/collections/u/'.$theCollection['id'], 'active'=>SEG3 == 'u'],
	];
	Yii::$app->params['page_actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'gallery/collections/d/'.$theCollection['id'], 'active'=>SEG3 == 'd', 'class'=>'btn-danger'],
	];
}