<?php

$languageList = [
    'en'=>'English',
    'fr'=>'Français',
    'vi'=>'Tiếng Việt',
];

$dayMealList = [
    '---'=>'---',
    'B--'=>'B--',
    '-L-'=>'-L-',
    '--D'=>'--D',
    'BL-'=>'BL-',
    'B-D'=>'B-D',
    '-LD'=>'-LD',
    'BLD'=>'BLD',
];

Yii::$app->params['page_breadcrumbs'] = [
    ['Products', 'products'],
    ['Sample days (B2C)', SEG2 == '' ? null : 'nm'],
    !in_array(SEG2, ['c']) ? null : ['Add new'],
    !in_array(SEG2, ['r', 'u', 'd']) ? null : ['View', SEG2 == 'r' ? null : 'nm/r/'.$theDay['id']],
    in_array(SEG2, ['u']) ? ['Edit'] : null,
    in_array(SEG2, ['d']) ? ['Delete'] : null,
];

Yii::$app->params['page_actions'] = [
    array_merge(
    [
        ['icon'=>'list', 'title'=>Yii::t('app', 'View all'), 'link'=>'nm'],
        ['icon'=>'plus', 'title'=>Yii::t('app', 'Add new'), 'link'=>'nm/c'],
    ],
    !isset($theDay['id']) ? [] : [
        ['icon'=>'eye', 'title'=>Yii::t('app', 'View'), 'link'=>'nm/r/'.$theDay['id']],
        ['icon'=>'edit', 'title'=>Yii::t('app', 'Edit'), 'link'=>'nm/u/'.$theDay['id']],
        ['icon'=>'trash-o', 'class'=>'text-danger', 'title'=>Yii::t('app', 'Delete'), 'link'=>'nm/d/'.$theDay['id']],
    ])
];
