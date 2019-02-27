<?php
use yii\helpers\Html;

if (!function_exists('renderDropdownMenu')) {
    function renderDropdownMenu($items) {
        foreach ($items as $item) {
            $hidden = isset($item['hidden']) && $item['hidden'];
            if (!$hidden) {
                if (isset($item['html'])) {
                    // Output html
                    echo $item['html'];
                } else {
                    // Break down
                    if ($item == ['-']) {
                        // A divider
                        echo '<li class="divider"></li>';
                    } elseif (isset($item['header'])) {
                        // A divider
                        echo '<li class="dropdown-header">';
                        if (count(Yii::$app->params['active_languages']) > 1) {
                            echo Yii::t('nav', $item['header']);
                        } else {
                            echo $item['header'];
                        }
                        echo '</li>';
                    } else {
                        $icon = isset($item['icon']) ? '<i class="slicon-'.$item['icon'].'"></i> ' : '';
                        $label = isset($item['label']) ? $item['label'] : '';
                        if (count(Yii::$app->params['active_languages']) > 1) {
                            $label = Yii::t('nav', $label);
                        }
                        $title = isset($item['title']) ? $item['title'] : '';
                        $link = isset($item['link']) ? $item['link'] : '#';
                        if (substr($link, 0, 1) != '#' && substr($link, 0, 5) != '@web/' && strpos($link, '//') === false) {
                            $link = '@web/'.$link;
                        }

                        $attr = [];
                        foreach (['class', 'target', 'id'] as $a_attr) {
                            if (isset($item[$a_attr])) {
                                $attr[$a_attr] = $item[$a_attr];
                            }
                        }

                        $html = '<li ';
                        if (isset($item['li_id'])) {
                            $html .= 'id="'.$item['li_id'].'" ';
                        }
                        if (isset($item['li_class'])) {
                            $html .= 'class="'.(isset($item['active']) && $item['active'] ? 'active ' : '').(isset($item['li_class']) ? $item['li_class'] : '').'"';
                        }
                        $html .= '>';
                        $html .= \yii\helpers\Html::a($icon.$label, $link, $attr);
                        $html .= '</li>';
                        echo $html;
                    } // if
                }
            } // if not hidden
        } // foreach
    }
}

Yii::$app->params['brand_name'] = 'AMICA TRAVEL';

Yii::$app->params['top_nav']['sections'] = [
    ['label'=>'Tours', 'link'=>'tours'],
    ['label'=>'Tour calendar', 'link'=>'tours/calendar'],
    //['label'=>Yii::t('mn', 'Groups & Spaces'), 'link'=>'spaces'],
    ['-'],
    ['label'=>'News', 'link'=>'blog'],
    // ['label'=>'Discussion forums', 'link'=>'https://discourse.amicatravel.com', 'target'=>'_blank'],
    // ['label'=>'Events', 'link'=>'eventful'],
    // ['label'=>'Gallery', 'link'=>'gallery'],
    // ['label'=>'Knowledge base', 'link'=>'kb'],
    ['-'],
    // ['label'=>'Website management', 'link'=>'https://admin.amica-travel.com/', 'target'=>'_blank'],
    ['label'=>'B2B', 'link'=>'b2b'],
    ['-'],
    ['label'=>'Organization', 'link'=>'members']
];

Yii::$app->params['top_nav']['search'] = true;

Yii::$app->params['top_nav']['links'] = [
    ['icon'=>'home', 'label'=>'Hotels', 'link'=>'venues'],
    ['icon'=>'home', 'label'=>'Homestays', 'link'=>'venues?stra=h'],
    ['icon'=>'anchor', 'label'=>'Cruises', 'link'=>'ref/halongcruises'],
    ['icon'=>'truck', 'label'=>'Sightseeing', 'link'=>'ref/ssspots'],
    ['icon'=>'coffee', 'label'=>'Restaurants', 'link'=>'venues?type=restaurant&destination_id=1'],
    ['icon'=>'table', 'label'=>'Other tables', 'link'=>'ref/tables'],
    ['-'],
    ['icon'=>'car', 'label'=>'Tours starting this month', 'link'=>'tours'],
    ['-'],
    ['icon'=>'font', 'label'=>'Amica members', 'link'=>'members'],
];

Yii::$app->params['top_nav']['help'] = [
    ['icon'=>'bullhorn', 'label'=>'IMS news', 'link'=>'help/news'],
    ['-'],
    ['icon'=>'question-circle', 'label'=>'FAQ', 'link'=>'help/faq'],
    ['icon'=>'book', 'label'=>'Documentation', 'link'=>'help/docs'],
    ['icon'=>'check', 'label'=>'Change log', 'link'=>'help/changelog'],
    ['icon'=>'road', 'label'=>'Road map', 'link'=>'help/roadmap'],
    ['-'],
    ['icon'=>'bug', 'label'=>'Report a bug', 'link'=>'help/report-a-bug'],
    ['-'],
    ['icon'=>'info-circle', 'label'=>'About this software', 'link'=>'help/about'],
];

Yii::$app->params['top_nav']['lang'] = [
    ['flag'=>'us', 'code'=>'en', 'name'=>'English', 'link'=>'select/lang/en'],
    ['flag'=>'fr', 'code'=>'fr', 'name'=>'Français', 'link'=>'select/lang/fr'],
    ['flag'=>'vn', 'code'=>'vi', 'name'=>'Tiếng Việt', 'link'=>'select/lang/vi'],
];

Yii::$app->params['top_nav']['user'] = [
    ['icon'=>'user', 'label'=>Yii::t('x', 'My profile'), 'link'=>'me/profile'],
    ['icon'=>'key', 'label'=>Yii::t('x', 'Change password'), 'link'=>'me/account'],
    ['icon'=>'settings', 'label'=>Yii::t('x', 'Preferences'), 'link'=>'me/preferences'],
    ['-'],
    ['icon'=>'check', 'label'=>Yii::t('x', 'My tasks'), 'link'=>'tasks'],
    ['icon'=>'envelope', 'label'=>Yii::t('x', 'My emails'), 'link'=>'mails'],
    ['icon'=>'notebook', 'label'=>Yii::t('x', 'My notes'), 'link'=>'notes'],
    ['icon'=>'pie-chart', 'label'=>Yii::t('x', 'Seller report'), 'link'=>'me/reports'],
    ['-'],
    //['header'=>'Theme colors'],
    //['html'=>$themeColorsHtml],
    //['-'],
    ['icon'=>'power', 'label'=>Yii::t('x', 'Log out'), 'link'=>'logout'],
];

Yii::$app->params['side_nav']['travel'] = [
    ['heading'=>Yii::t('x', 'Work')],
    ['icon'=>'home', 'label'=>'Home', 'submenu'=>[
        ['label'=>'Dashboard', 'active'=>in_array(SEG1, [''])],
        ['label'=>'My tasks', 'link'=>'tasks', 'active'=>in_array(SEG1, ['tasks'])],
        ['label'=>'My cases', 'link'=>'cases/open', 'active'=>in_array(SEG2, ['open'])],
        ['label'=>'My tour programs', 'link'=>'products?ub='.MY_ID, 'active'=>false],
        ['label'=>'My tours', 'link'=>'tours', 'active'=>in_array(SEG2, ['dopen'])],
        ],
    ],
    ['icon'=>'umbrella', 'label'=>'Service vendors', 'submenu'=>[
        ['label'=>'Service suppliers', 'link'=>'suppliers', 'active'=>SEG1 == 'suppliers'],
        ['label'=>'Hotels', 'link'=>'venues', 'active'=>SEG1 == 'venues' && SEG2 == ''],
        ['label'=>'Homestays', 'link'=>'venues?stra=h'],
        ['label'=>'Cruises', 'link'=>'ref/halongcruises', 'active'=>SEG1 == 'ref' && SEG2 == 'hotels'],
        ['label'=>'Restaurants', 'link'=>'https://my.amicatravel.com/venues?type=restaurant&destination_id=1', 'active'=>SEG1 == 'ref' && SEG2 == 'hotels'],
        ['label'=>'Tour costs', 'link'=>'dv', 'active'=>SEG1 == 'dv'],
        ], 'active'=>in_array(SEG1, ['venues', 'dvt', 'ref', 'suppliers', 'dv'])
    ],
    ['icon'=>'location-pin', 'label'=>'Destinations', 'submenu'=>[
        ['label'=>'Countries', 'link'=>'countries', 'active'=>SEG1 == 'countries'],
        ['label'=>'Destinations', 'link'=>'destinations', 'active'=>SEG1 == 'destinations'],
        ['label'=>'Tour routes', 'link'=>'td', 'active'=>SEG1 == 'td'],
        ], 'active'=>in_array(SEG1, ['countries', 'destinations', 'td'])
    ],
    ['icon'=>'diamond', 'label'=>'Products & services', 'active'=>in_array(SEG1, ['products', 'packages', 'nm']), 'submenu'=>[
        ['label'=>'Tour programs', 'link'=>'products', 'active'=>SEG1 == 'products' && SEG2 != 'b2b'],
        ['label'=>'Tour programs (B2B)', 'link'=>'b2b/programs'],
        ['label'=>'Sample days', 'link'=>'nm', 'active'=>SEG1 == 'nm'],
        ['label'=>'Sample days (B2B)', 'link'=>'b2b/days'],
        ['label'=>'Sample programs', 'link'=>'tm', 'active'=>SEG1 == 'tm'],
        ['label'=>'Sample programs (B2B)', 'link'=>'b2b/programs'],
        ['label'=>'Package tours', 'link'=>'packages', 'active'=>SEG1 == 'packages'],
        ],
    ],
    ['icon'=>'microphone', 'label'=>'Marketing', 'active'=>in_array(SEG1, ['campaigns', 'promotions']), 'submenu'=>[
        ['label'=>'Campaigns', 'link'=>'campaigns', 'active'=>in_array(SEG1, ['campaigns'])],
        ['label'=>'Promotions', 'link'=>'promotions', 'active'=>in_array(SEG1, ['promotions'])],
        ['label'=>'Websites', 'link'=>'http://admin.amica-travel.com', 'terget'=>'_blank'],
        ],
    ],
    ['icon'=>'bubbles', 'label'=>'Communications', 'active'=>in_array(SEG1, ['mails', 'inquiries']), 'submenu'=>[
        ['label'=>'Web inquiries', 'link'=>'inquiries', 'active'=>in_array(SEG1, ['inquiries'])],
        ['label'=>'Email messages', 'link'=>'mails', 'active'=>in_array(SEG1, ['mails'])],
        ['label'=>'IMS messages', 'link'=>'messages', 'active'=>in_array(SEG1, ['messages'])],
        ],
    ],
    ['icon'=>'wallet', 'label'=>'Sales B2C', 'active'=>in_array(SEG1, ['cases', 'bookings', 'invoices', 'payments']), 'submenu'=>[
        ['label'=>'Cases', 'link'=>'cases', 'active'=>in_array(SEG1, ['cases'])],
        ['label'=>'Proposals', 'link'=>'proposals', 'active'=>in_array(SEG1, ['proposals'])],
        ['label'=>'Bookings', 'link'=>'bookings', 'active'=>in_array(SEG1, ['bookings'])],
        ['label'=>'Invoices', 'link'=>'invoices', 'active'=>in_array(SEG1, ['invoices'])],
        ['label'=>'Payments', 'link'=>'payments', 'active'=>in_array(SEG1, ['payments'])],
        ],
    ],
    ['icon'=>'briefcase', 'label'=>'Sales B2B', 'submenu'=>[
        ['label'=>'B2B cases', 'link'=>'b2b/cases', 'active'=>SEG1 == 'b2b' && in_array(SEG2, ['cases'])],
        ['label'=>'B2B tours', 'link'=>'b2b/tours', 'active'=>SEG1 == 'b2b' && in_array(SEG2, ['tours'])],
        ['label'=>'B2B clients', 'link'=>'b2b/clients', 'active'=>SEG1 == 'b2b' && in_array(SEG2, ['clients', 'client', 'client-login'])],
        ],
        'active'=>SEG1 == 'b2b'
    ],
    ['icon'=>'paper-plane', 'label'=>'Tour operation', 'active'=>in_array(SEG1, ['tours', 'tourguides', 'drivers', 'incidents']), 'submenu'=>[
        ['label'=>'Tours', 'link'=>'tours', 'active'=>in_array(SEG1, ['tours']) && !in_array(SEG2, ['nhadan', 'calendar', 'calendar-month', 'tong-hop-roi-nuoc', 'tong-hop-nuoc-uong', 'tong-hop-tiet-kiem'])],
        ['label'=>'Tour guides', 'link'=>'tourguides', 'active'=>in_array(SEG1, ['tourguides'])],
        ['label'=>'Birthdays of t/guides', 'link'=>'tourguides/birthdays', 'active'=>SEG1 == 'tourguides' && SEG2 == 'birthdays'],
        ['label'=>'Drivers', 'link'=>'drivers', 'active'=>in_array(SEG1, ['drivers'])],
        ['label'=>'Tour incidents', 'link'=>'incidents', 'active'=>in_array(SEG1, ['incidents'])],
        ['label'=>'Local homes calendar', 'link'=>'tours/nhadan', 'active'=>SEG1 == 'tours' && SEG2 == 'nhadan'],
        ['label'=>'Tour calendar (week)', 'link'=>'tours/calendar', 'active'=>SEG1 == 'tours' && SEG2 == 'calendar'],
        ['label'=>'Tour calendar (30 days)', 'link'=>'tours/calendar-month', 'active'=>SEG1 == 'tours' && SEG2 == 'calendar-month'],
        ['label'=>'Tour calendar (Google Drive)', 'link'=>'default/redir?url=https://docs.google.com/spreadsheets/d/1jmjbn7bhe423eYOTV_Pf3vtxgZDnGLhK_zjuDgXlS3g/htmlembed', 'active'=>false],
        ['label'=>'Summary: Water-puppet', 'link'=>'tours/tong-hop-roi-nuoc', 'active'=>SEG1 == 'tours' && SEG2 == 'tong-hop-roi-nuoc'],
        ['label'=>'Summary: Drinking water', 'link'=>'tours/tong-hop-nuoc-uong', 'active'=>SEG1 == 'tours' && SEG2 == 'tong-hop-nuoc-uong'],
        ['label'=>'Summary: Tour cost savings', 'link'=>'tours/tong-hop-tiet-kiem', 'active'=>SEG1 == 'tours' && SEG2 == 'tong-hop-tiet-kiem'],
        ],
    ],
    ['icon'=>'people', 'label'=>'Customers', 'active'=>URI == 'users/tags' || in_array(SEG1, ['complaints', 'customers', 'referrals', 'feedbacks', 'qhkh']), 'submenu'=>[
        ['label'=>'Customers', 'link'=>'customers', 'active'=>in_array(SEG1, ['customers']) && SEG2 == ''],
        ['label'=>'Customer feedbacks', 'link'=>'feedbacks', 'active'=>in_array(SEG1, ['feedbacks']) && SEG2 == ''],
        ['label'=>'Complaints', 'link'=>'complaints', 'active'=>in_array(SEG1, ['complaints']) && SEG2 == ''],
        ['label'=>'Referrals', 'link'=>'referrals', 'active'=>in_array(SEG1, ['referrals'])],
        ['label'=>'Customer care tasks', 'link'=>'customers/tasks', 'active'=>SEG2 == 'tasks'],
        ['label'=>'Customers visits', 'link'=>'customers/tasks-ac', 'active'=>SEG2 == 'tasks-ac'],
        ['label'=>'Customers birthdays', 'link'=>'customers/birthdays', 'active'=>in_array(SEG2, ['birthdays'])],
        ['label'=>'CR Fund', 'link'=>'qhkh/quy-qhkh', 'active'=>in_array(SEG1, ['qhkh']) && SEG2 == 'quy-qhkh'],
        ['label'=>'Tour results', 'link'=>'qhkh/chot-tour', 'active'=>in_array(SEG1, ['qhkh']) && SEG2 == 'chot-tour'],
        ['label'=>'Types of letters', 'link'=>'qhkh/quy-trinh-thu-mau', 'active'=>in_array(SEG1, ['qhkh']) && SEG2 == 'quy-trinh-thu-mau'],
        ['label'=>'Service Plus', 'link'=>'qhkh/service-plus', 'active'=>in_array(SEG1, ['qhkh']) && SEG2 == 'service-plus'],
        ['label'=>'User tags', 'link'=>'users/tags', 'active'=>in_array(SEG1, ['users']) && SEG2 == 'tags'],
        ],
    ],
    ['icon'=>'calculator', 'label'=>'Money & accounting', 'link'=>'accounting', 'active'=>in_array(SEG1, ['ketoan', 'baccounts', 'cpt', 'xrates']), 'submenu'=>[
        ['label'=>'Bank & cash accounts', 'link'=>'baccounts'],
        ['label'=>'Chi phí tour (cpt)', 'link'=>'cpt', 'active'=>SEG1 == 'cpt' && in_array(SEG2, ['', 'r', 'thanh-toan'])],
        ['label'=>'Lịch thanh toán cpt', 'link'=>'cpt/lich-thanh-toan', 'active'=>URI == 'cpt/lich-thanh-toan'],
        ['label'=>'Cpt đã thanh toán', 'link'=>'cpt/da-thanh-toan', 'active'=>URI == 'cpt/da-thanh-toan'],
        ['label'=>'Xuất cpt Excel', 'link'=>'tools/ketoan-xuat-cpt'],
        ['label'=>'Tổng chi phí tour', 'link'=>'tours/tongchiphi', 'active'=>URI == 'tours/tongchiphi'],
        ['label'=>'Tỉ giá', 'link'=>'xrates', 'active'=>SEG1 == 'xrates'],
        ['label'=>'Công cụ và thông tin', 'link'=>'ketoan'],
        ],
    ],/*
    ['icon'=>'diamond', 'label'=>'Assets', 'submenu'=>[
        ['label'=>'Vehicles', 'link'=>'vehicles'],
        ],
    ],
    ['icon'=>'user', 'label'=>'HR', 'link'=>'hr', 'submenu'=>[
        ['label'=>'HR overview', 'link'=>'hr'],
        ],
    ],*/
    ['icon'=>'calendar', 'label'=>'Calendar & Events', 'active'=>in_array(SEG1, ['calendar', 'events']), 'submenu'=>[
        ['label'=>'Calendar', 'link'=>'calendar', 'active'=>in_array(SEG1, ['calendar'])],
        ['label'=>'Events', 'link'=>'events', 'active'=>in_array(SEG1, ['events'])],
        ],
    ],
    ['icon'=>'wrench', 'label'=>'Manager', 'active'=>in_array(SEG1, ['manager', 'reports']), 'submenu'=>[
        ['label'=>'Manager dashboard', 'link'=>'manager', 'active'=>in_array(SEG1, ['manager']) && SEG2 == ''],
        ['label'=>'Reports', 'link'=>'manager/reports', 'active'=>in_array(SEG1, ['manager']) && SEG2 == 'reports'],
        ],
    ],
];

Yii::$app->params['side_nav']['blog'] = [
    ['icon'=>'book-open', 'label'=>'Tin tức', 'link'=>'blog', 'submenu'=>[
        ['label'=>'Toàn bộ bài viết', 'link'=>'blog/posts', 'active'=>SEG1 == 'blog' && SEG2 == 'posts' && (!isset($_GET['cat']) || $_GET['cat'] == 0)],
        ['label'=>'Tin công ty', 'link'=>'blog/posts?cat=1', 'active'=>SEG1 == 'blog' && SEG2 == 'posts' && isset($_GET['cat']) && $_GET['cat'] == 1],
        ['label'=>'Tin công đoàn', 'link'=>'blog/posts?cat=2', 'active'=>SEG1 == 'blog' && SEG2 == 'posts' && isset($_GET['cat']) && $_GET['cat'] == 2],
        ['label'=>'Tin nhân sự', 'link'=>'blog/posts?cat=3', 'active'=>SEG1 == 'blog' && SEG2 == 'posts' && isset($_GET['cat']) && $_GET['cat'] == 3],
        ['label'=>'Tin khác', 'link'=>'blog/posts?cat=4', 'active'=>SEG1 == 'blog' && SEG2 == 'posts' && isset($_GET['cat']) && $_GET['cat'] == 4],
        ],
        'active'=>SEG1 == 'blog' && in_array(SEG2, ['', 'posts']),
    ],
    ['icon'=>'user', 'label'=>'Tin của tôi', 'submenu'=>[
        ['label'=>'Xem tất cả bài viết', 'link'=>'blog/my-posts', 'active'=>SEG2 == 'my-posts'],
        ],
        'active'=>SEG1 == 'blog' && SEG2 == 'my-posts',
    ],
    ['icon'=>'settings', 'label'=>'Quản lý blog', 'submenu'=>[
        ['label'=>'Xem tất cả bài viết', 'link'=>'blog/manage', 'active'=>SEG2 == 'manage'],
        ],
        'active'=>SEG1 == 'blog' && SEG2 == 'manage',
    ],
];

Yii::$app->params['side_nav']['eventful'] = [
    ['icon'=>'fire', 'label'=>'Sự kiện', 'link'=>'eventful', 'submenu'=>[
        ['label'=>'Tất cả sự kiện', 'link'=>'eventful/events', 'active'=>SEG1 == 'eventful' && SEG2 == 'events'],
        ],
        'active'=>SEG1 == 'eventful' && in_array(SEG2, ['', 'events']),
    ],
    ['icon'=>'cog', 'label'=>'Quản lý sự kiện', 'submenu'=>[
        ['label'=>'Xem tất cả sự kiện', 'link'=>'eventful/manage', 'active'=>SEG2 == 'manage'],
        ],
        'active'=>SEG1 == 'blog' && SEG2 == 'manage',
    ],
];

Yii::$app->params['side_nav']['gallery'] = [
    ['icon'=>'camera', 'label'=>'Trang chủ gallery', 'link'=>'gallery', 'active'=>SEG1 == 'gallery' && in_array(SEG2, ['']),],
    ['icon'=>'picture', 'label'=>'Gallery trên Google Drive', 'link'=>'https://drive.google.com/folderview?id=1OYVVokQRwIV9BJvqpGkPMPOrOCF5q6vsvoGQP-i-Sos#grid', 'target'=>'_blank'],
    ['icon'=>'settings', 'label'=>'Quản lý gallery', 'link'=>'gallery/manage', 'active'=>SEG1 == 'gallery' && in_array(SEG2, ['manage']), 'hidden'=>MY_ID != 1],
];

Yii::$app->params['side_nav']['kb'] = [
    ['icon'=>'puzzle', 'label'=>'Knowledge base', 'submenu'=>[
        ['label'=>'KB Home', 'link'=>'kb'],
        ['label'=>'All posts', 'link'=>'kb/posts', 'active'=>SEG1 == 'kb' && SEG2 == 'posts'],
        ['label'=>'Special lists', 'link'=>'kb/lists', 'active'=>SEG1 == 'kb' && SEG2 == 'lists'],
        ['label'=>'Books and documents', 'link'=>'kb/books', 'active'=>SEG1 == 'kb' && SEG2 == 'books'],
        ],
        'active'=>SEG1 == 'kb',
    ],
];

// CMS TEST

Yii::$app->params['active_cms_sites'] = [
    'amica-fr'=>['name'=>'Amica Francais', 'url'=>'', 'path'=>'',],
    'amica-val'=>['name'=>'Voyager au Laos', 'url'=>'', 'path'=>'',],
    'amica-vac'=>['name'=>'Voyager au Cambodge', 'url'=>'', 'path'=>'',],
    'amica-vpc'=>['name'=>'Voyager pas cher', 'url'=>'', 'path'=>'',],
    'amica-ami'=>['name'=>'Club Ami Amica', 'url'=>'', 'path'=>'',],
    'amica-en'=>['name'=>'Amica English', 'url'=>'', 'path'=>'',],
    'amica-tcg'=>['name'=>'Tam Coc Garden', 'url'=>'', 'path'=>'',],
    'amica-si'=>['name'=>'Secret Indochina', 'url'=>'', 'path'=>'',],
];

$activeCmsSites = [];
foreach (Yii::$app->params['active_cms_sites'] as $code=>$site) {
    $activeCmsSites[] = ['label'=>$site['name'], 'link'=>'cms/default/site/'.$code];
}

Yii::$app->params['active_cms_content_channels'] = [];

$activeCmsContentChannels = [];

Yii::$app->params['side_nav']['cms'] = [
    ['icon'=>'home', 'label'=>'Home (Site name)', 'link'=>'cms', 'active'=>in_array(SEG2, [''])],
    ['icon'=>'sitemap', 'label'=>'Select a site', 'link'=>'cms/sites', 'active'=>in_array(SEG2, ['x']), 'submenu'=>
        $activeCmsSites,
    ],
    ['icon'=>'file-text-o', 'label'=>'Content', 'link'=>'cms/content/channels', 'active'=>in_array(SEG2, ['xx']), 'submenu'=>
        $activeCmsContentChannels,
    ],
];

// Yii::$app->params['side_nav']['org'] = [
//     ['heading'=>'ORGANIZATION'],
//     ['icon'=>'globe', 'label'=>'Organization', 'submenu'=>[
//         ['label'=>'About us', 'link'=>'org'],
//         ['label'=>'Members', 'link'=>'org/members'],
//         ['label'=>'Companies', 'link'=>'org/companies'],
//         ['label'=>'Departments', 'link'=>'org/departments'],
//         ],
//     ],
//     ['heading'=>'MY ACCOUNT'],
//     ['icon'=>'user', 'label'=>'My profile', 'link'=>'me/profile'],
//     ['icon'=>'key', 'label'=>'Change password', 'link'=>'me/account'],
//     ['icon'=>'cog', 'label'=>'Preferences', 'link'=>'me/preferences'],
//     ['icon'=>'envelope-o', 'label'=>'My emails', 'link'=>'mails'],
//     ['icon'=>'file-text-o', 'label'=>'My notes', 'link'=>'notes'],
//     ['icon'=>'bar-chart-o', 'label'=>'Seller report', 'link'=>'me/reports'],
//     ['icon'=>'tasks', 'label'=>'My tasks', 'link'=>'tasks'],
//     ['icon'=>'comment-o', 'label'=>'Góp ý (ẩn danh)', 'link'=>'blog/posts/r/111'],
//     ['icon'=>'power-off', 'label'=>'Log out', 'link'=>'logout'],
// ];

// Yii::$app->params['side_nav']['me'] = [
//     ['icon'=>'user', 'label'=>'My profile', 'link'=>'me/profile'],
//     ['icon'=>'key', 'label'=>'Change password', 'link'=>'me/account'],
//     ['icon'=>'settings', 'label'=>'Preferences', 'link'=>'me/preferences'],
//     ['icon'=>'envelope', 'label'=>'My emails', 'link'=>'mails'],
//     ['icon'=>'file-text-o', 'label'=>'My notes', 'link'=>'notes'],
//     ['icon'=>'bar-chart-o', 'label'=>'Seller report', 'link'=>'me/reports'],
//     ['icon'=>'tasks', 'label'=>'My tasks', 'link'=>'tasks'],
//     ['-'],
//     ['icon'=>'power', 'label'=>'Log out', 'link'=>'logout'],
// ];

// Yii::$app->params['side_nav']['b2b'] = [
//     ['heading'=>'B2B / Secret Indochina', 'class'=>'text-pink'],
//     ['icon'=>'home', 'label'=>'B2B Home', 'link'=>'b2b', 'active'=>SEG1 == 'b2b' && SEG2 == ''],
//     ['icon'=>'umbrella', 'label'=>'Products', 'submenu'=>[
//         ['label'=>'Sample days', 'link'=>'b2b/days', 'active'=>in_array(SEG2, ['days'])],
//         ['label'=>'Tour programs', 'link'=>'b2b/programs', 'active'=>in_array(SEG2, ['programs']) && Yii::$app->request->get('type') != 'b2b-prod'],
//         ['label'=>'Tour programs (PROD)', 'link'=>'b2b/programs?type=b2b-prod', 'active'=>in_array(SEG2, ['programs']) && Yii::$app->request->get('type') == 'b2b-prod'],
//         ],
//         'active'=>in_array(SEG2, ['days', 'programs']),
//     ],
//     ['icon'=>'basket', 'label'=>'Sales', 'submenu'=>[
//         ['label'=>'Cases', 'link'=>'b2b/cases', 'active'=>in_array(SEG2, ['cases'])],
//         ],
//         'active'=>in_array(SEG2, ['cases']),
//     ],
//     ['icon'=>'magic-wand', 'label'=>'Tour operation', 'submenu'=>[
//         ['label'=>'Tours', 'link'=>'b2b/tours', 'active'=>in_array(SEG2, ['tours'])],
//         ],
//         'active'=>in_array(SEG2, ['tours']),
//     ],
//     ['icon'=>'people', 'label'=>'Clients', 'submenu'=>[
//         ['label'=>'Clients', 'link'=>'b2b/clients', 'active'=>in_array(SEG2, ['clients'])],
//         ],
//         'active'=>in_array(SEG2, ['cases', 'clients', 'tours', 'programs']),
//     ],
//     ['icon'=>'chart', 'label'=>'Reports', 'submenu'=>[
//         ['label'=>'Reports', 'link'=>'b2b/reports', 'active'=>in_array(SEG2, ['reports'])],
//         ],
//     ],
//     ['icon'=>'arrow-left-circle', 'label'=>'Back to IMS home', 'link'=>''],
// ];

// Yii::$app->params['side_nav']['acp'] = [
//     ['heading'=>'SYSTEM'],
//     ['icon'=>'share', 'label'=>'Common', 'submenu'=>[
//         ['label'=>'Common items', 'link'=>'org'],
//         ],
//     ],
//     ['icon'=>'user', 'label'=>'User management', 'link'=>'system/users', 'submenu'=>[
//         ['label'=>'Users', 'link'=>'users'],
//         ['label'=>'Groups', 'link'=>'groups'],
//         ['label'=>'Roles', 'link'=>'roles'],
//         ['label'=>'Permissions', 'link'=>'permissions'],
//         ],
//     ],
//     ['icon'=>'folder', 'label'=>'Files', 'submenu'=>[
//         ['label'=>'Folders', 'link'=>'folders'],
//         ['label'=>'Files', 'link'=>'files'],
//         ],
//     ],
//     ['icon'=>'info-circle', 'label'=>'Stats', 'submenu'=>[
//         ['label'=>'PHP information', 'link'=>'system/stats/phpinfo'],
//         ],
//     ],
// ];

// Yii::$app->params['side_nav']['mcp'] = [
//     ['heading'=>'Master CP'],
//     ['icon'=>'home', 'label'=>'Master CP Home', 'active'=>SEG2 == ''],
//     ['icon'=>'info', 'label'=>'Stats', 'submenu'=>[
//         ['label'=>'Access log', 'link'=>'mcp/log', 'active'=>SEG2 == 'log'],
//         ['label'=>'PHP information', 'link'=>'mcp/phpinfo', 'active'=>SEG2 == 'phpinfo'],
//         ],
//     ],
// ];

// Yii::$app->params['side_nav']['help'] = [
//     ['heading'=>'Help & Support'],
//     ['icon'=>'info', 'label'=>'About this software', 'link'=>'help/about', 'active'=>SEG2 == 'about'],
//     ['icon'=>'question', 'label'=>'FAQ', 'link'=>'help/faq', 'active'=>SEG2 == 'faq'],
//     ['icon'=>'docs', 'label'=>'Documentation', 'link'=>'help/docs', 'active'=>SEG2 == 'docs'],
//     ['icon'=>'list', 'label'=>'Changelog', 'link'=>'help/changelog', 'active'=>SEG2 == 'changelog'],
//     ['icon'=>'map', 'label'=>'Roadmap', 'link'=>'help/roadmap', 'active'=>SEG2 == 'roadmap'],
//     ['-'],
//     ['icon'=>'feed', 'label'=>'Development news', 'link'=>'help/news', 'active'=>SEG2 == 'news'],
//     ['-'],
//     ['icon'=>'support', 'label'=>'Report a bug', 'link'=>'help/report-a-bug', 'active'=>SEG2 == 'report-a-bug'],
// ];

Yii::$app->params['side_nav']['travel'] = [
    ['icon'=>'home', 'label'=>'Home', 'submenu'=>[
        ['label'=>'Dashboard', 'active'=>in_array(SEG1, [''])],
        ['label'=>'My tasks', 'link'=>'tasks', 'active'=>in_array(SEG1, ['tasks'])],
        ['label'=>'My cases', 'link'=>'cases/open', 'active'=>in_array(SEG2, ['open'])],
        ['label'=>'My tour programs', 'link'=>'programs?updated_by='.USER_ID, 'active'=>false],
        ['label'=>'My tours', 'link'=>'tours', 'active'=>in_array(SEG2, ['dopen'])],
        ],
        'active'=>in_array(SEG1, ['', 'tasks'])
    ],
    // ['heading'=>'Sales - B2C'],
    ['icon'=>'location-pin', 'label'=>'Destinations', 'submenu'=>[
        ['label'=>'Countries', 'link'=>'countries', 'active'=>SEG1 == 'countries'],
        ['label'=>'Destinations', 'link'=>'destinations', 'active'=>SEG1 == 'destinations'],
        ['label'=>'Tour routes', 'link'=>'td', 'active'=>SEG1 == 'td'],
        ], 'active'=>in_array(SEG1, ['countries', 'destinations', 'td'])
    ],
    ['icon'=>'umbrella', 'label'=>'Service vendors', 'submenu'=>[
        ['label'=>'Vendors', 'link'=>'vendors', 'active'=>SEG1 == 'vendors'],
        ['label'=>'Hotels', 'link'=>'venues', 'active'=>SEG1 == 'venues' && SEG2 == ''],
        ['label'=>'Homestays', 'link'=>'venues?stra=h'],
        ['label'=>'Homestay calendar', 'link'=>'venues/homestay-calendar', 'active'=>SEG1 == 'venues' && SEG2 == 'homestay-calendar'],
        ['label'=>'Cruises', 'link'=>'ref/halongcruises', 'active'=>SEG1 == 'ref' && SEG2 == 'hotels'],
        ['label'=>'Restaurants', 'link'=>'venues/search?type=restaurant&destination_id=1', 'active'=>SEG1 == 'ref' && SEG2 == 'hotels'],
        ['label'=>'Tour costs [1]', 'link'=>'dv', 'active'=>SEG1 == 'dv', 'hidden'=>USER_ID != 1],
        ['label'=>'Price tables', 'link'=>'ref/tables', 'active'=>SEG1 == 'ref' && SEG2 == 'tables'],
        ], 'active'=>in_array(SEG1, ['venues', 'dvt', 'ref', 'vendors', 'dv'])
    ],
    ['icon'=>'diamond', 'label'=>'Products & services', 'submenu'=>[
        ['label'=>'Tour programs (B2C)', 'link'=>'programs', 'active'=>SEG1 == 'programs'],
        ['label'=>'Tour programs (B2B)', 'link'=>'b2b/programs'],
        ['label'=>'Sample tour days (B2C)', 'link'=>'sample-days', 'active'=>SEG1 == 'sample-days'],
        ['label'=>'Sample tour days (B2B)', 'link'=>'b2b/days'],
        // ['label'=>'Sample tour programs (B2C)', 'link'=>'tm', 'active'=>SEG1 == 'tm'],
        // ['label'=>'Sample tour programs (B2B)', 'link'=>'b2b/programs'],
        ['label'=>'Package tours', 'link'=>'packages', 'active'=>SEG1 == 'packages'],
        ],
        'active'=>in_array(SEG1, ['programs', 'products', 'packages', 'sample-days']),
    ],
    // ['icon'=>'microphone', 'label'=>'Marketing', 'active'=>in_array(SEG1, ['campaigns', 'promotions']), 'submenu'=>[
    //     ['label'=>'Campaigns', 'link'=>'campaigns', 'active'=>in_array(SEG1, ['campaigns'])],
    //     ['label'=>'Promotions', 'link'=>'promotions', 'active'=>in_array(SEG1, ['promotions'])],
    //     ['label'=>'Websites', 'link'=>'http://admin.amica-travel.com', 'terget'=>'_blank'],
    //     ],
    // ],
    ['icon'=>'wallet', 'label'=>'Sales B2C', 'active'=>in_array(SEG1, ['cases', 'bookings', 'invoices', 'payments']), 'submenu'=>[
        ['label'=>'Cases', 'link'=>'cases', 'active'=>in_array(SEG1, ['cases'])],
        // ['label'=>'Proposals', 'link'=>'proposals', 'active'=>in_array(SEG1, ['proposals'])],
        ['label'=>'Bookings', 'link'=>'bookings', 'active'=>in_array(SEG1, ['bookings'])],
        ['label'=>'Invoices', 'link'=>'invoices', 'active'=>in_array(SEG1, ['invoices'])],
        ['label'=>'Payments', 'link'=>'payments', 'active'=>in_array(SEG1, ['payments'])],
        ],
    ],
    ['icon'=>'briefcase', 'label'=>'Sales B2B', 'submenu'=>[
        ['label'=>'B2B cases', 'link'=>'b2b/cases', 'active'=>SEG1 == 'b2b' && in_array(SEG2, ['cases'])],
        ['label'=>'B2B tours', 'link'=>'b2b/tours', 'active'=>SEG1 == 'b2b' && in_array(SEG2, ['tours'])],
        ['label'=>'B2B clients', 'link'=>'b2b/clients', 'active'=>SEG1 == 'b2b' && in_array(SEG2, ['clients', 'client', 'client-login'])],
        ],
        'active'=>SEG1 == 'b2b'
    ],
    ['icon'=>'people', 'label'=>'Customer Relations', 'active'=>URI == 'users/tags' || in_array(SEG1, ['complaints', 'referrals', 'feedbacks', 'qhkh']), 'submenu'=>[
        ['label'=>'Customer feedbacks', 'link'=>'feedbacks', 'active'=>in_array(SEG1, ['feedbacks'])],
        ['label'=>'Complaints', 'link'=>'complaints', 'active'=>in_array(SEG1, ['complaints']) && SEG2 == ''],
        ['label'=>'Referrals', 'link'=>'referrals', 'active'=>in_array(SEG1, ['referrals'])],
        ['label'=>'Customer related tasks', 'link'=>'qhkh/tasks', 'active'=>SEG2 == 'tasks'],
        ['label'=>'Customer visits', 'link'=>'qhkh/tasks-ac', 'active'=>SEG2 == 'tasks-ac'],
        ['label'=>'CR Fund', 'link'=>'qhkh/quy-qhkh', 'active'=>in_array(SEG1, ['qhkh']) && SEG2 == 'quy-qhkh'],
        ['label'=>'Tour results', 'link'=>'qhkh/chot-tour', 'active'=>in_array(SEG1, ['qhkh']) && SEG2 == 'chot-tour'],
        ['label'=>'Types of letters', 'link'=>'qhkh/quy-trinh-thu-mau', 'active'=>in_array(SEG1, ['qhkh']) && SEG2 == 'quy-trinh-thu-mau'],
        ['label'=>'Service Plus', 'link'=>'qhkh/service-plus', 'active'=>in_array(SEG1, ['qhkh']) && SEG2 == 'service-plus'],
        ['label'=>'User tags', 'link'=>'users/tags', 'active'=>in_array(SEG1, ['users']) && SEG2 == 'tags'],
        ],
    ],
    // ['heading'=>'Tour operation'],
    ['icon'=>'paper-plane', 'label'=>'Tour operation', 'active'=>in_array(SEG1, ['tours', 'tourguides', 'drivers', 'incidents']), 'submenu'=>[
        ['label'=>'Tours', 'link'=>'tours', 'active'=>in_array(SEG1, ['tours']) && !in_array(SEG2, ['series', 'calendar', 'calendar-month', 'tong-hop-roi-nuoc', 'tong-hop-nuoc-uong', 'tong-hop-tiet-kiem'])],
        ['label'=>'Tour series', 'link'=>'tours/series', 'active'=>in_array(SEG2, ['series'])],
        ['label'=>'Tour incidents', 'link'=>'incidents', 'active'=>in_array(SEG1, ['incidents'])],
        ['label'=>'Homestay calendar', 'link'=>'venues/homestay-calendar'],
        ['label'=>'Tour calendar (week)', 'link'=>'tours/calendar', 'active'=>SEG1 == 'tours' && SEG2 == 'calendar'],
        ['label'=>'Tour calendar (30 days)', 'link'=>'tours/calendar-month', 'active'=>SEG1 == 'tours' && SEG2 == 'calendar-month'],
        ['label'=>'Tour calendar (Google Drive)', 'link'=>'default/redir?url=https://docs.google.com/spreadsheets/d/1jmjbn7bhe423eYOTV_Pf3vtxgZDnGLhK_zjuDgXlS3g/htmlembed', 'active'=>false],
        ['label'=>'Summary: Water-puppet', 'link'=>'tours/tong-hop-roi-nuoc', 'active'=>SEG1 == 'tours' && SEG2 == 'tong-hop-roi-nuoc'],
        ['label'=>'Summary: Drinking water', 'link'=>'tours/tong-hop-nuoc-uong', 'active'=>SEG1 == 'tours' && SEG2 == 'tong-hop-nuoc-uong'],
        ['label'=>'Summary: Tour cost savings', 'link'=>'tours/tong-hop-tiet-kiem', 'active'=>SEG1 == 'tours' && SEG2 == 'tong-hop-tiet-kiem'],
        ],
    ],
    ['icon'=>'people', 'label'=>'Directory', 'submenu'=>[
        ['label'=>'All contacts', 'link'=>'contacts', 'active'=>in_array(SEG1, ['contacts']) && !in_array(SEG2, ['tourguides', 'drivers', 'members'])],
        ['label'=>'Amica members', 'link'=>'contacts/members', 'active'=>SEG1 == 'contacts' && in_array(SEG2, ['members'])],
        ['label'=>'Tour guides', 'link'=>'contacts/tourguides', 'active'=>SEG1 == 'contacts' && in_array(SEG2, ['tourguides'])],
        ['label'=>'Tour drivers', 'link'=>'contacts/drivers', 'active'=>SEG1 == 'contacts' && in_array(SEG2, ['drivers'])],
        ['label'=>'Customers', 'link'=>'customers', 'active'=>in_array(SEG1, ['customers'])],
        ],
        'active'=>in_array(SEG1, ['contacts', 'members', 'customers'])
    ],
    ['icon'=>'bubbles', 'label'=>'Communications', 'active'=>in_array(SEG1, ['mails', 'inquiries', 'posts']), 'submenu'=>[
        ['label'=>'Web inquiries', 'link'=>'inquiries', 'active'=>in_array(SEG1, ['inquiries'])],
        ['label'=>'Email messages', 'link'=>'mails', 'active'=>in_array(SEG1, ['mails'])],
        ['label'=>'IMS messages', 'link'=>'posts', 'active'=>in_array(SEG1, ['posts'])],
        ],
    ],

    ['icon'=>'calculator', 'label'=>'Money & accounting', 'link'=>'accounting', 'active'=>in_array(SEG1, ['ketoan', 'accounts', 'cpt', 'xrates']), 'submenu'=>[
        ['label'=>'Bank & cash accounts', 'link'=>'accounts', 'active'=>in_array(SEG1, ['accounts'])],
        ['label'=>'Chi phí tour (cpt)', 'link'=>'cpt', 'active'=>SEG1 == 'cpt' && in_array(SEG2, ['', 'r', 'thanh-toan'])],
        ['label'=>'Lịch thanh toán cpt', 'link'=>'cpt/lich-thanh-toan', 'active'=>URI == 'cpt/lich-thanh-toan'],
        ['label'=>'Cpt đã thanh toán', 'link'=>'cpt/da-thanh-toan', 'active'=>URI == 'cpt/da-thanh-toan'],
        ['label'=>'Xuất cpt Excel', 'link'=>'tools/ketoan-xuat-cpt'],
        ['label'=>'Tổng chi phí tour', 'link'=>'tours/tongchiphi', 'active'=>URI == 'tours/tongchiphi'],
        ['label'=>'Tỉ giá', 'link'=>'xrates', 'active'=>SEG1 == 'xrates'],
        ['label'=>'Công cụ và thông tin', 'link'=>'ketoan'],
        ],
    ],/*
    ['icon'=>'diamond', 'label'=>'Assets', 'submenu'=>[
        ['label'=>'Vehicles', 'link'=>'vehicles'],
        ],
    ],
    ['icon'=>'user', 'label'=>'HR', 'link'=>'hr', 'submenu'=>[
        ['label'=>'HR overview', 'link'=>'hr'],
        ],
    ],*/
    // ['icon'=>'calendar', 'label'=>'Calendar & Events', 'active'=>in_array(SEG1, ['calendar', 'events']), 'submenu'=>[
    //     ['label'=>'Calendar', 'link'=>'calendar', 'active'=>in_array(SEG1, ['calendar'])],
    //     ['label'=>'Events', 'link'=>'events', 'active'=>in_array(SEG1, ['events'])],
    //     ],
    // ],
    ['icon'=>'wrench', 'label'=>'Reports', 'active'=>in_array(SEG1, ['reports']), 'submenu'=>[
        ['label'=>'Reports', 'link'=>'reports', 'active'=>in_array(SEG1, ['reports'])],
        ],
    ],
];

Yii::$app->params['side_nav']['blog'] = [
    ['icon'=>'book-open', 'label'=>'Tin tức', 'link'=>'blog', 'submenu'=>[
        ['label'=>'Toàn bộ bài viết', 'link'=>'blog/posts', 'active'=>SEG1 == 'blog' && SEG2 == 'posts' && (!isset($_GET['cat']) || $_GET['cat'] == 0)],
        ['label'=>'Tin công ty', 'link'=>'blog/posts?cat=1', 'active'=>SEG1 == 'blog' && SEG2 == 'posts' && isset($_GET['cat']) && $_GET['cat'] == 1],
        ['label'=>'Tin công đoàn', 'link'=>'blog/posts?cat=2', 'active'=>SEG1 == 'blog' && SEG2 == 'posts' && isset($_GET['cat']) && $_GET['cat'] == 2],
        ['label'=>'Tin nhân sự', 'link'=>'blog/posts?cat=3', 'active'=>SEG1 == 'blog' && SEG2 == 'posts' && isset($_GET['cat']) && $_GET['cat'] == 3],
        ['label'=>'Tin khác', 'link'=>'blog/posts?cat=4', 'active'=>SEG1 == 'blog' && SEG2 == 'posts' && isset($_GET['cat']) && $_GET['cat'] == 4],
        ],
        'active'=>SEG1 == 'blog' && in_array(SEG2, ['', 'posts']),
    ],
    ['icon'=>'user', 'label'=>'Tin của tôi', 'submenu'=>[
        ['label'=>'Xem tất cả bài viết', 'link'=>'blog/my-posts', 'active'=>SEG2 == 'my-posts'],
        ],
        'active'=>SEG1 == 'blog' && SEG2 == 'my-posts',
    ],
    ['icon'=>'settings', 'label'=>'Quản lý blog', 'submenu'=>[
        ['label'=>'Xem tất cả bài viết', 'link'=>'blog/manage', 'active'=>SEG2 == 'manage'],
        ],
        'active'=>SEG1 == 'blog' && SEG2 == 'manage',
    ],
];

Yii::$app->params['side_nav']['eventful'] = [
    ['icon'=>'fire', 'label'=>'Sự kiện', 'link'=>'eventful', 'submenu'=>[
        ['label'=>'Tất cả sự kiện', 'link'=>'eventful/events', 'active'=>SEG1 == 'eventful' && SEG2 == 'events'],
        ],
        'active'=>SEG1 == 'eventful' && in_array(SEG2, ['', 'events']),
    ],
    ['icon'=>'cog', 'label'=>'Quản lý sự kiện', 'submenu'=>[
        ['label'=>'Xem tất cả sự kiện', 'link'=>'eventful/manage', 'active'=>SEG2 == 'manage'],
        ],
        'active'=>SEG1 == 'blog' && SEG2 == 'manage',
    ],
];

Yii::$app->params['side_nav']['gallery'] = [
    ['icon'=>'camera', 'label'=>'Trang chủ gallery', 'link'=>'gallery', 'active'=>SEG1 == 'gallery' && in_array(SEG2, ['']),],
    ['icon'=>'picture', 'label'=>'Gallery trên Google Drive', 'link'=>'https://drive.google.com/folderview?id=1OYVVokQRwIV9BJvqpGkPMPOrOCF5q6vsvoGQP-i-Sos#grid', 'target'=>'_blank'],
    ['icon'=>'settings', 'label'=>'Quản lý gallery', 'link'=>'gallery/manage', 'active'=>SEG1 == 'gallery' && in_array(SEG2, ['manage']), 'hidden'=>MY_ID != 1],
];

Yii::$app->params['side_nav']['kb'] = [
    ['icon'=>'puzzle', 'label'=>'Knowledge base', 'submenu'=>[
        ['label'=>'KB Home', 'link'=>'kb'],
        ['label'=>'All posts', 'link'=>'kb/posts', 'active'=>SEG1 == 'kb' && SEG2 == 'posts'],
        ['label'=>'Special lists', 'link'=>'kb/lists', 'active'=>SEG1 == 'kb' && SEG2 == 'lists'],
        ['label'=>'Books and documents', 'link'=>'kb/books', 'active'=>SEG1 == 'kb' && SEG2 == 'books'],
        ],
        'active'=>SEG1 == 'kb',
    ],
];


Yii::$app->params['section_name'] = 'IMS';
Yii::$app->params['side_nav_name'] = 'travel';

if (in_array(SEG1, ['org'])) {
    Yii::$app->params['side_nav_name'] = 'org';
    Yii::$app->params['section_name'] = 'Tổ chức';
} elseif (in_array(SEG1, ['me'])) {
    Yii::$app->params['side_nav_name'] = 'me';
    Yii::$app->params['section_name'] = 'Cá nhân';
} elseif (in_array(SEG1, ['blog'])) {
    Yii::$app->params['side_nav_name'] = 'blog';
    Yii::$app->params['section_name'] = 'Tin tức';
} elseif (in_array(SEG1, ['eventful'])) {
    Yii::$app->params['side_nav_name'] = 'eventful';
    Yii::$app->params['section_name'] = 'Sự kiện';
} elseif (in_array(SEG1, ['gallery'])) {
    Yii::$app->params['side_nav_name'] = 'gallery';
    Yii::$app->params['section_name'] = 'Gallery';
} elseif (in_array(SEG1, ['kb'])) {
    Yii::$app->params['side_nav_name'] = 'kb';
    Yii::$app->params['section_name'] = 'Kiến thức';
} elseif (in_array(SEG1, ['forum'])) {
    Yii::$app->params['side_nav_name'] = 'comm';
    Yii::$app->params['section_name'] = 'Diễn đàn';
} elseif (in_array(SEG1, ['help'])) {
    Yii::$app->params['side_nav_name'] = 'help';
    Yii::$app->params['section_name'] = 'Trợ giúp';
} elseif (in_array(SEG1, ['acp'])) {
    Yii::$app->params['side_nav_name'] = 'acp';
    Yii::$app->params['section_name'] = 'Tài khoản';
} elseif (in_array(SEG1, ['mcp'])) {
    Yii::$app->params['side_nav_name'] = 'mcp';
    Yii::$app->params['section_name'] = 'Hệ thống';
} elseif (in_array(SEG1, ['b2b'])) {
    Yii::$app->params['side_nav_name'] = 'b2b';
    Yii::$app->params['section_name'] = 'B2B';
} elseif (in_array(SEG1, ['cms'])) {
    Yii::$app->params['side_nav_name'] = 'cms';
    Yii::$app->params['section_name'] = 'Nội dung web';
}
