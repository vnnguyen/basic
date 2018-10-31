<?php

Yii::$app->params['page_icon'] = 'user';

Yii::$app->params['page_title'] = Yii::t('x', 'Contacts');
if (SEG2 == 'c') {
    Yii::$app->params['page_title'] = Yii::t('x', 'Add new');
}
if (SEG2 == 'r') {
    Yii::$app->params['page_title'] = $theContact['name'];
}
if (SEG2 == 'u') {
    Yii::$app->params['page_title'] = Yii::t('x', 'Edit').': '.$theContact['name'];
}
if (SEG2 == 'd') {
    Yii::$app->params['page_title'] = Yii::t('x', 'Delete').': '.$theContact['name'];
}

if (in_array(SEG2, ['', 'tourguides', 'drivers', 'members'])) {
    $this->beginBlock('page_tabs'); ?>
<ul class="nav nav-tabs nav-tabs-bottom border-0 mb-0">
    <li class="nav-item"><a class="nav-link<?= SEG2 == '' ? ' active' : '' ?>" href="/contacts"><?= Yii::t('nav', 'All contacts') ?></a></li>
    <li class="nav-item"><a class="nav-link<?= SEG2 == 'members' ? ' active' : '' ?>" href="/contacts/members"><?= Yii::t('nav', 'Amica members') ?></a></li>
    <li class="nav-item"><a class="nav-link<?= SEG2 == 'tourguides' ? ' active' : '' ?>" href="/contacts/tourguides"><?= Yii::t('nav', 'Tour guides') ?></a></li>
    <li class="nav-item"><a class="nav-link<?= SEG2 == 'drivers' ? ' active' : '' ?>" href="/contacts/drivers"><?= Yii::t('nav', 'Tour drivers') ?></a></li>
    <li class="nav-item"><a class="nav-link" href="/customers"><?= Yii::t('nav', 'Customers') ?></a></li>
    <!-- <li class="nav-item"><a class="nav-link" href="#"><?= Yii::t('x', 'Customers (SI)') ?></a></li> -->
</ul><?php
    $this->endBlock();
}


if ((int)SEG2 != 0 && in_array(SEG3, ['', 'uploads', 'discussions', 'more'])) {
    $this->beginBlock('page_tabs'); ?>
<ul class="nav nav-tabs nav-tabs-bottom border-0 mb-0">
    <li class="nav-item"><a class="nav-link<?= SEG3 == '' ? ' active' : '' ?>" href="/contacts/<?= $theContact['id'] ?>"><?= Yii::t('x', 'Overview') ?></a></li>
    <li class="nav-item"><a class="nav-link<?= SEG3 == 'discussions' ? ' active' : '' ?>" href="#/contacts/<?= $theContact['id'] ?>/discussions"><?= Yii::t('x', 'Discussions') ?></a></li>
    <li class="nav-item"><a class="nav-link<?= SEG3 == 'uploads' ? ' active' : '' ?>" href="/contacts/<?= $theContact['id'] ?>/uploads"><?= Yii::t('x', 'Uploads') ?></a></li>
    <li class="nav-item"><a class="nav-link<?= SEG3 == 'more' ? ' active' : '' ?>" href="#/contacts/<?= $theContact['id'] ?>/more"><?= Yii::t('x', 'More') ?></a></li>
</ul><?php
    $this->endBlock();
}

Yii::$app->params['page_breadcrumbs'] = [
    [Yii::t('x', 'Directory'), '#'],
    [Yii::t('x', 'Contacts'), SEG2 != '' ? 'contacts' : null],
    SEG2 == 'members' ? [Yii::t('x', 'Amica members')] : null,
    SEG2 == 'tourguides' ? [Yii::t('x', 'Tour guides')] : null,
    SEG2 == 'drivers' ? [Yii::t('x', 'Tour drivers')] : null,
    SEG2 == 'c' ? [Yii::t('x', 'Add new')] : null,
    SEG2 != '' && in_array(SEG3, ['u', 'd']) ? [Yii::t('x', $theContact['name']), in_array(SEG3, ['u', 'd']) ? 'contacts/'.$theContact['id'] : null ] : null,
    is_int(SEG2) && SEG3 == '' ? [$theContact['name']] : null,
    SEG3 == 'u' ? [Yii::t('x', 'Edit')] : null,
    SEG3 == 'd' ? [Yii::t('x', 'Delete')] : null,
];

Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'list', 'title'=>Yii::t('x', 'View all'), 'link'=>'contacts', 'active'=>SEG2 == ''],
        ['icon'=>'plus', 'title'=>Yii::t('x', 'Add new'), 'link'=>'contacts/c', 'active'=>SEG2 == 'c'],
    ],
];

if (isset($theContact['id'])) {
    Yii::$app->params['page_actions'][] = [
            ['submenu'=>[
                ['icon'=>'eye', 'label'=>Yii::t('x', 'View'), 'link'=>'contacts/'.$theContact['id'], 'active'=>SEG2 == 'r'],
                ['icon'=>'edit', 'label'=>Yii::t('x', 'Edit'), 'link'=>'contacts/'.$theContact['id'].'/u', 'active'=>SEG2 == 'u'],
                ['icon'=>'trash-o', 'label'=>Yii::t('x', 'Delete'), 'link'=>'contacts/'.$theContact['id'].'/d', 'active'=>SEG2 == 'd', 'class'=>'text-danger'],
                ],
            ],
        ];
}

$contactGenderList = [
    'male'=>Yii::t('p', 'Male'),
    'female'=>Yii::t('p', 'Female'),
];

$contactTypeList = [
    'design'=>Yii::t('p', 'Thiết kế'),
    'construction'=>Yii::t('p', 'Thi công'),
    'media'=>Yii::t('p', 'Truyền thông'),
    'consultation'=>Yii::t('p', 'Tư vấn'),
    'other'=>Yii::t('p', 'Loại khác'),
];

$contactStatusList = [
    'new'=>Yii::t('p', 'Mới'),
    'active'=>Yii::t('p', 'Tiến hành'),
    'complete'=>Yii::t('p', 'Hoàn thành'),
    'onhold'=>Yii::t('p', 'Trì hoãn'),
    'canceled'=>Yii::t('p', 'Huỷ'),
];

$contactPctList = [
    0=>'0%',
    10=>'10%',
    20=>'20%',
    30=>'30%',
    40=>'40%',
    50=>'50%',
    60=>'60%',
    70=>'70%',
    80=>'80%',
    90=>'90%',
    100=>'100%',
];

$dataTelList = [
    'tel'=>'Phone',
    'mobile'=>'Mobile',
    'fax'=>'Fax',
    'other'=>'Other phone',
];

$dataEmailList = [
    'email'=>'Email',
    'other'=>'Other email',
];


$dataUrlList = [
    'website'=>'Website',
    'facebook'=>'Facebook',
    'twitter'=>'Twitter',
    'google-plus'=>'Google+',
    'youtube'=>'Youtube',
    'tripadvisor'=>'TripAdvisor',
    'linkedin'=>'LinkedIn',
    'skype'=>'Skype',
    'url'=>'Other URL',
];

$dataAddrList = [
    'address'=>'Address',
    'other'=>'Other address',
];

$dataRelList = [
    'spouse'=>Yii::t('x', 'Spouse'),
    'parent'=>Yii::t('x', 'Parent'),
    'child'=>Yii::t('x', 'Child'),
    'sibling'=>Yii::t('x', 'Sibling'),
    'grandparent'=>Yii::t('x', 'Grandparent'),
    'grandchild'=>Yii::t('x', 'Grandchild'),
    'aunt_uncle'=>Yii::t('x', 'Aunt/Uncle'),
    'nephew_niece'=>Yii::t('x', 'Nephew/Niece'),
    'cousin'=>Yii::t('x', 'Cousin'),
    'relative'=>Yii::t('x', 'Relative'),
    'friend'=>Yii::t('x', 'Friend'),
    'acquaintance'=>Yii::t('x', 'Acquaintance'),
    'colleague'=>Yii::t('x', 'Colleague'),
    'partner'=>Yii::t('x', 'Domestic partner'),
    'in-law'=>Yii::t('x', 'In-law'),
];

$reverseRelList = [
    'spouse'=>Yii::t('x', 'Spouse'),
    'parent'=>Yii::t('x', 'Child'),
    'child'=>Yii::t('x', 'Parent'),
    'sibling'=>Yii::t('x', 'Sibling'),
    'grandparent'=>Yii::t('x', 'Grandchild'),
    'grandchild'=>Yii::t('x', 'Grandparent'),
    'aunt_uncle'=>Yii::t('x', 'Nephew/Niece'),
    'nephew_niece'=>Yii::t('x', 'Aunt/Uncle'),
    'cousin'=>Yii::t('x', 'Cousin'),
    'relative'=>Yii::t('x', 'Relative'),
    'friend'=>Yii::t('x', 'Friend'),
    'acquaintance'=>Yii::t('x', 'Acquaintance'),
    'colleague'=>Yii::t('x', 'Colleague'),
    'partner'=>Yii::t('x', 'Domestic partner'),
    'in-law'=>Yii::t('x', 'In-law'),
];

$dataPassportList = [
    'passport'=>Yii::t('x', 'Passport'),
    'passport_old'=>Yii::t('x', 'Passport (obsolete)'),
];

$guideLanguageList = [
    'fr'=>'Français',
    'de'=>'Deustch',
    'en'=>'English',
    'es'=>'Español',
    'it'=>'Italiano',
    'ja'=>'日本語',
    'vi'=>'Tiếng Việt',
    'zh'=>'中文',
];