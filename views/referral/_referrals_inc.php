<?


$this->params['icon'] = 'briefcase';
$this->params['breadcrumb'] = [
	['Referrals', 'referrals'],
];

if (isset($theReferral['id']) && in_array(SEG2, ['r', 'u', 'd'])) {
	$this->params['actions'][] = [
		['icon'=>'briefcase', 'title'=>'View', 'link'=>'referrals/r/'.$theReferral['id'], 'active'=>SEG2 == 'r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'referrals/u/'.$theReferral['id'], 'active'=>SEG2 == 'u'],
	];
	$this->params['actions'][] = [
		['icon'=>'lock', 'title'=>'Delete', 'link'=>'referrals/d/'.$theReferral['id'], 'active'=>SEG2 == 'd', 'class'=>'btn-danger'],
	];
}

// How they contacted us
$giftList = [
	''=>'Not yet',
	'ca'=>'CA',
	'ch'=>'CH',
	'dh'=>'DH',
	'dv'=>'DVD Vietnam',
	'li'=>'Livre',
	'no'=>'NO credits/gifts',
];

$giftSelectList = [
	'all'=>'All credits',
	'no'=>'No credits',
	'yes'=>'With credits',
	''=>'- No gift yet',
	'ca'=>'- CA',
	'ch'=>'- CH',
	'dh'=>'- DH',
	'dv'=>'- DVD',
	'li'=>'- LIVRE',
];
