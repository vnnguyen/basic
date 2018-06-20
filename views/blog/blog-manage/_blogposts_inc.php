<?
Yii::$app->params['acc1/blog/cats'] =[
	['id'=>1, 'name'=>'Tin công ty'],
	['id'=>2, 'name'=>'Tin công đoàn'],
	['id'=>3, 'name'=>'Tin nhân sự'],
	['id'=>4, 'name'=>'Tin khác'],
];


$this->params['icon'] = 'newspaper2';
$this->params['breadcrumb'] = [
	['Tin tức', 'blog'],
	['Bài viết', 'blog/posts'],
];
$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New post', 'link'=>'blog/posts/c', 'active'=>SEG3 == 'c',],
	],
];

if (isset($theEntry['id'])) {
	$this->params['actions'][] = [
		['icon'=>'file-text-o', 'title'=>'View', 'link'=>'blog/posts/r/'.$theEntry['id'], 'active'=>SEG3 == 'r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'blog/posts/u/'.$theEntry['id'], 'active'=>SEG3 == 'u'],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'blog/posts/d/'.$theEntry['id'], 'active'=>SEG3 == 'd', 'class'=>'btn-danger'],
	];
}