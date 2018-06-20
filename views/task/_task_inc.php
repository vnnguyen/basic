<?

$this->params['icon'] = 'bell';

$this->title = 'Tasks';

$this->params['breadcrumb'] = [
	['Tasks', '@web/tasks'],
];

$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New', 'link'=>'tasks/c'],
	],
	[
		['icon'=>'square-o', 'label'=>'Assigned', 'link'=>'tasks/assigned'],
		['icon'=>'check-square-o', 'label'=>'Completed', 'link'=>'tasks/completed'],
	]
];

$due = [
	''=>'today',
];