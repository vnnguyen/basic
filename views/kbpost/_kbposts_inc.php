<?
$this->params['icon'] = 'puzzle-piece';
$this->params['breadcrumb'] = [
	['Kiến thức', 'kb'],
	['Posts', 'kb/posts'],
];

if (SEG3 == 'c') {
	$this->params['breadcrumb'][] = ['Add', 'kb/posts/c'];
}

if (in_array(SEG3, ['r', 'u', 'd']) && isset($theEntry['id'])) {
	$this->params['breadcrumb'][] = ['View', 'kb/posts/r/'.$theEntry['id']];
}

if (SEG3 == 'u' && isset($theEntry['id'])) {
	$this->params['breadcrumb'][] = ['Edit', URI];
}

if (SEG3 == 'd' && isset($theEntry['id'])) {
	$this->params['breadcrumb'][] = ['Delete', URI];
}

$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New post', 'link'=>'kb/posts/c', 'active'=>SEG3 == 'c'],
	],
];

if (in_array(SEG3, ['r', 'u', 'd']) && isset($theEntry['id'])) {
	$this->params['actions'][] = [
		['icon'=>'file-text-o', 'title'=>'View', 'link'=>'kb/posts/r/'.$theEntry['id'], 'active'=>SEG3 == 'r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'kb/posts/u/'.$theEntry['id'], 'active'=>SEG3 == 'u'],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'kb/posts/d/'.$theEntry['id'], 'active'=>SEG3 == 'd', 'class'=>'btn-danger'],
	];
}
