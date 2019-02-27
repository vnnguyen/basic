<?php
use yii\helpers\Html;

Yii::$app->params['page_title'] = Yii::t('x', 'B2B Clients');

Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'list', 'title'=>Yii::t('x', 'View all'), 'link'=>'b2b/clients'],
        ['icon'=>'plus', 'title'=>Yii::t('x', 'Add new'), 'link'=>'b2b/clients/c'],
    ]
];

if (isset($theClient['id'])) {
    Yii::$app->params['page_actions'][] = [
        ['icon'=>'eye', 'title'=>Yii::t('x', 'View'), 'link'=>'b2b/clients/r/'.$theClient['id']],
        ['icon'=>'edit', 'title'=>Yii::t('x', 'Edit'), 'link'=>'b2b/clients/u/'.$theClient['id']],
        ['icon'=>'trash-o', 'title'=>Yii::t('x', 'Delete'), 'link'=>'b2b/clients/d/'.$theClient['id']],
    ];
}

Yii::$app->params['page_breadcrumbs'] = [
    ['B2B', 'b2b'],
    [Yii::t('x', 'Clients'), SEG3 == '' ? null : 'b2b/clients'],
    in_array(SEG3, ['r', 'u', 'd', 'login']) ? [Yii::t('x', 'View'), 'b2b/clients/r/'.$theClient['id']] : null,
    SEG3 == 'u' ? [Yii::t('x', 'Edit')] : null,
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
    'skype'=>'Skype',
    'linkedin'=>'LinkedIn',
    'url'=>'Other URL',
];

$dataAddrList = [
    'address'=>'Address',
    'other'=>'Other address',
];