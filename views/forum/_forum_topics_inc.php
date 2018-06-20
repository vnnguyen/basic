<?

$this->params['icon'] = 'comments';

$this->params['breadcrumb'] = [
	['Forum', 'forum'],
	['Topics', 'forum/topics'],
];

$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New topic', 'link'=>'forum/topics/c', 'active'=>SEG3 == 'c'],
	],
];

if (isset($theTopic['id'])) {
	$this->params['actions'][] = [
		['icon'=>'eye', 'title'=>'View', 'link'=>'forum/topics/'.$theTopic['id'], 'active'=>SEG4 == ''],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'forum/topics/'.$theTopic['id'].'/u', 'active'=>SEG4 == 'u'],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'forum/topics/'.$theTopic['id'].'/d', 'active'=>SEG4 == 'd', 'class'=>'btn-danger'],
	];
}

$forumCatList = [
	'admin'=>'Thông tin chung',
	'amica'=>'Nói về Amica Travel',
	'work'=>'Kiến thức, kỹ năng, kinh nghiệm',
	'members'=>'Thành viên, tập thể',
	'offices'=>'Các văn phòng',
];