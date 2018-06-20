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
    ['Sample tours (B2C)', SEG2 == '' ? null : 'tm'],
    !in_array(SEG2, ['c']) ? null : ['Add new'],
    !in_array(SEG2, ['r', 'u', 'd']) ? null : ['View', SEG2 == 'r' ? null : 'tm/r/'.$theProgram['id']],
    in_array(SEG2, ['u']) ? ['Edit'] : null,
    in_array(SEG2, ['d']) ? ['Delete'] : null,
];

Yii::$app->params['page_actions'] = [
    array_merge(
    [
        ['icon'=>'list', 'title'=>Yii::t('app', 'View all'), 'link'=>'tm'],
        ['icon'=>'plus', 'title'=>Yii::t('app', 'Add new'), 'link'=>'tm/c'],
    ],
    !isset($theProgram['id']) ? [] : [
        ['icon'=>'eye', 'title'=>Yii::t('app', 'View'), 'link'=>'tm/r/'.$theProgram['id']],
        ['icon'=>'edit', 'title'=>Yii::t('app', 'Edit'), 'link'=>'tm/u/'.$theProgram['id']],
        ['icon'=>'trash-o', 'class'=>'text-danger', 'title'=>Yii::t('app', 'Delete'), 'link'=>'tm/d/'.$theProgram['id']],
    ])
];
