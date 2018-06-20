<?php
$dealTypeList = array(
	'privatetour'=>'Private tour',
	'vpctour'=>'VPC tour',
	'airticket'=>'Air ticket'
	);

// How they contacted us
$caseHowContactedUs = array(
	''=>'',
	'web'=>'Web',
	'agent'=>'Travel agent',
	'email'=>'Email',
	'phone'=>'Phone',
	'direct'=>'In person',
	'other'=>'Other', // web pages like Fb, fax, snail mail
);

// Web referral
$caseWebReferralList = array(
	''=>'',
	'direct'=>'Direct',
	'link'=>'Link',
	'search'=>'Search',
	'search/google'=>'Google',
	'search/bing'=>'Bing',
	'search/yahoo'=>'Yahoo',
	'mediasearch'=>'Media search',
	'ad'=>'Advertisement',
	'ad/adwords'=>'Adwords',
	'ad/adsense'=>'Adsense',
	'email'=>'Email',
	'syndication'=>'Syndication',
	'social'=>'Social media',
);

// How they found us
$caseHowFoundUs = array(
	''=>'',
	'web'=>'Web',
	'print'=>'Book/Print',
	'tv'=>'TV/Radio',
	'returning'=>'Returning',
	'word'=>'Word of mouth',
	'other'=>'Other', // travel agent, by chance
	'unknown'=>'Not known',
);

// Close
$caseWhyClosed = array(
	'won'=>'Đã bán được tour',
	'lost/duplicate'=>'Trùng khách với hồ sơ khác',
	'lost/nodeal'=>'Khách không có tiềm năng',
	'lost/noreply'=>'Khách không hồi âm',
	'lost/refused'=>'Khách từ chối mua tour',
	'lost/other'=>'Không bán được vì lý do khác',
);