<?

yap('page_icon', 'briefcase');

yap('page_breadcrumbs', [
	['Cases', 'cases'],
]);

Yii::$app->params['page_actions'] = [
	[
		['icon'=>'plus', 'label'=>'New', 'link'=>'cases/c', 'active'=>SEG2 == 'c'],
	],
];

if (isset($theCase['id'])) {
	Yii::$app->params['page_actions'][] = [
		['icon'=>'briefcase', 'title'=>'View', 'link'=>'cases/r/'.$theCase['id'], 'active'=>SEG2 == 'r'],
		['icon'=>'edit', 'title'=>'Edit', 'link'=>'cases/u/'.$theCase['id'], 'active'=>SEG2 == 'u'],
		['submenu'=>[
			['icon'=>'align-left', 'label'=>'View all email & notes', 'link'=>'cases/r/'.$theCase['id'].'?allnotes=yes'],
			'-',
			['icon'=>'user', 'label'=>'People in this case', 'link'=>'cases/people/'.$theCase['id'], 'active'=>SEG2 == 'people'],
			['icon'=>'comment', 'label'=>'Customer\'s request', 'link'=>'cases/request/'.$theCase['id'], 'active'=>SEG2 == 'request'],
			['icon'=>'link', 'label'=>'Send Registration request', 'link'=>'cases/send-cpl/'.$theCase['id'], 'active'=>SEG2 == 'send-cpl'],
			'-',
			['icon'=>'lock', 'label'=>'Close', 'link'=>'cases/close/'.$theCase['id'], 'active'=>SEG2 == 'close', 'visible'=>$theCase['status'] != 'closed'],
			['icon'=>'unlock', 'label'=>'Re-open', 'link'=>'cases/reopen/'.$theCase['id'], 'active'=>SEG2 == 'reopen', 'visible'=>$theCase['status'] == 'closed'],
			['icon'=>'clock-o', 'label'=>'Put on hold', 'link'=>'cases/hold/'.$theCase['id'], 'active'=>SEG2 == 'hold', 'visible'=>$theCase['status'] != 'onhold'],
			['icon'=>'clock-o', 'label'=>'Re-activate', 'link'=>'cases/unhold/'.$theCase['id'], 'active'=>SEG2 == 'unhold', 'visible'=>$theCase['status'] == 'onhold'],
			],
		],
	];
	Yii::$app->params['page_actions'][] = [
		['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'cases/d/'.$theCase['id'], 'active'=>SEG2 == 'd', 'class'=>'btn-danger'],
	];
}

// How they contacted us
$caseHowContactedList = [
	'web'=>'Website form',
	'agent'=>'Via a tour company',
	'email'=>'Email',
	'phone'=>'Phone',
	'direct'=>'In person',
	'other'=>'Other', // web pages like Fb, fax, snail mail
];

// Web referral
$caseWebReferralList = array(
	'direct'=>'Direct',
	'link'=>'Link',
	'search'=>'Search',
	'search/google'=>'- Google',
	'search/bing'=>'- Bing',
	'search/yahoo'=>'- Yahoo',
	'mediasearch'=>'Media search',
	'ad'=>'Advertisement',
	'ad/adwords'=>'- Adwords',
	'ad/adsense'=>'- Adsense',
	'email'=>'Email',
	'syndication'=>'Syndication',
	'social'=>'Social media',
);

// How they found us
$caseHowFoundList = array(
	'web'=>'Web search/ad/link',
	'print'=>'Book/Print',
	'tv'=>'TV/Radio',
	'event'=>'Event/Seminar',
	'returning'=>'Returning',
	'word'=>'Word of mouth',
	'other'=>'Other', // travel agent, by chance
	'unknown'=>'Not known',
);

// Close
$caseWhyClosedList = array(
	'won'=>'A tour has been confirmed | Đã bán được tour',
	'lost/duplicate'=>'Duplicate case | Trùng khách với hồ sơ khác',
	'lost/nodeal'=>'Not a potential customer | Khách không có tiềm năng',
	'lost/noreply'=>'Person does not reply | Khách không hồi âm',
	'lost/refused'=>'Person has refused | Khách từ chối mua tour',
	'lost/other'=>'Other reason | Không bán được vì lý do khác',
);