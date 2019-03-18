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

$dayTypeList = [
    '1'=>Yii::t('x', 'Single, user-selectable day'),
    'ns'=>Yii::t('x', 'Single, not user-selectable'),
    '5'=>Yii::t('x', 'Half day'),
    '2'=>Yii::t('x', 'Multiple-day segment'),
];

Yii::$app->params['page_breadcrumbs'] = [
    ['Products', 'products'],
    [Yii::t('x', 'Sample days (B2C)'), SEG2 == '' ? null : 'sample-days'],
    !in_array(SEG2, ['c']) ? null : [Yii::t('x', 'Add new')],
    !isset($theDay['id']) ? null : [Yii::t('x', 'View'), SEG3 == '' ? null : 'sample-days/'.$theDay['id']],
    in_array(SEG3, ['u']) ? [Yii::t('x', 'Edit')] : null,
    in_array(SEG3, ['d']) ? [Yii::t('x', 'Delete')] : null,
];

Yii::$app->params['page_actions'] = [
    array_merge(
    [
        ['icon'=>'slicon-list', 'title'=>Yii::t('x', 'View all'), 'link'=>'sample-days'],
        ['icon'=>'slicon-plus', 'title'=>Yii::t('x', 'Add new'), 'link'=>'sample-days/c'],
    ],
    !isset($theDay['id']) ? [] : [
        ['icon'=>'slicon-eye', 'title'=>Yii::t('x', 'View'), 'link'=>'sample-days/'.$theDay['id']],
        ['icon'=>'slicon-pencil', 'title'=>Yii::t('x', 'Edit'), 'link'=>'sample-days/'.$theDay['id'].'/u'],
        ['icon'=>'slicon-trash', 'class'=>'text-danger', 'title'=>Yii::t('x', 'Delete'), 'link'=>'sample-days/'.$theDay['id'].'/d'],
    ])
];
