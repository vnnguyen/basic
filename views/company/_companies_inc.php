<?

$this->params['icon'] = 'home';

$this->title = 'Companies';

$this->params['breadcrumb'] = [
	['Companies', '@web/companies'],
];
$this->params['actions'] = [
	[
		['icon'=>'plus', 'label'=>'New', 'link'=>'companies/c', 'active'=>SEG2 == 'c'],
	],
];

if (isset($theCompany['id'])) {
	$this->params['actions'][] = [
		['icon'=>'home', 'title'=>'View', 'link'=>'companies/r/'.$theCompany['id'], 'active'=>SEG2 == 'r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'companies/u/'.$theCompany['id'], 'active'=>SEG2 == 'u'],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'companies/d/'.$theCompany['id'], 'active'=>SEG2 == 'd', 'class'=>'btn-danger'],
	];
}