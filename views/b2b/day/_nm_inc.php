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
    ['B2B', 'b2b'],
    ['Sample days', SEG3 == '' ? null : 'b2b/days'],
    !in_array(SEG3, ['c']) ? null : ['Add new'],
    !in_array(SEG3, ['r', 'u', 'd']) ? null : ['View', SEG3 == 'r' ? null : 'b2b/days/r/'.$theDay['id']],
    in_array(SEG3, ['u']) ? ['Edit'] : null,
    in_array(SEG3, ['d']) ? ['Delete'] : null,
];

Yii::$app->params['page_actions'] = [
    array_merge(
    [
        ['icon'=>'list', 'title'=>Yii::t('app', 'View all'), 'link'=>'b2b/days'],
        ['icon'=>'plus', 'title'=>Yii::t('app', 'Add new'), 'link'=>'b2b/days/c'],
    ],
    !isset($theDay['id']) ? [] : [
        ['icon'=>'edit', 'title'=>Yii::t('app', 'Edit'), 'link'=>'b2b/days/u/'.$theDay['id']],
        ['icon'=>'trash-o', 'class'=>'text-danger', 'title'=>Yii::t('app', 'Delete'), 'link'=>'b2b/days/d/'.$theDay['id']],
    ])
];
