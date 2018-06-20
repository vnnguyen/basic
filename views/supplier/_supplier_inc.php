<?

$this->params['icon'] = 'home';

$this->title = 'Service suppliers';

$this->params['breadcrumb'] = [
	['Suppliers', '@web/suppliers'],
];
$this->params['actions'] = [
	[
		['icon'=>'plus', 'title'=>'New', 'link'=>'suppliers/c', 'active'=>SEG2 == 'c'],
	],
];

if (isset($theSupplier['id'])) {
	$this->params['actions'][] = [
		['icon'=>'eye', 'title'=>'View', 'link'=>'suppliers/r/'.$theSupplier['id'], 'active'=>SEG2 == 'r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'suppliers/u/'.$theSupplier['id'], 'active'=>SEG2 == 'u'],
	];
	$this->params['actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'suppliers/d/'.$theSupplier['id'], 'active'=>SEG2 == 'd', 'class'=>'text-danger'],
	];
}