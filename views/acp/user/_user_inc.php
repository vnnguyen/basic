<?
use yii\helpers\Html;

Yii::$app->params['page_icon'] = 'users';
Yii::$app->params['page_title'] = 'Users';

Yii::$app->params['page_breadcrumbs'] = [
    ['Account CP', 'acp'],
    ['Users', 'acp/users'],
];

Yii::$app->params['page_actions'][] = [
    ['icon'=>'list', 'title'=>'All users', 'link'=>'acp/users', 'active'=>SEG3 == ''],
];

Yii::$app->params['page_actions'][] = [
    ['icon'=>'plus', 'title'=>'New user', 'link'=>'acp/users/c', 'active'=>SEG3 == 'c'],
];

if (isset($theUser['id'])) {
    Yii::$app->params['page_breadcrumbs'][] = ['View', 'acp/users/r/'.$theUser['id']];

    if (SEG3 == 'r') {
        Yii::$app->params['page_title'] = 'User information: '.$theUser['name'];
    } elseif (SEG3 == 'u') {
        Yii::$app->params['page_title'] = 'Edit user: '.$theUser['name'];
        Yii::$app->params['page_breadcrumbs'][] = ['Edit', 'acp/users/u/'.$theUser['id']];
    } elseif (SEG3 == 'd') {
        Yii::$app->params['page_title'] = 'Delete user: '.$theUser['name'];
        Yii::$app->params['page_breadcrumbs'][] = ['Delete'];
    }

    Yii::$app->params['page_actions'][] = [
        ['icon'=>'eye', 'title'=>'View', 'link'=>'acp/users/r/'.$theUser['id'], 'active'=>SEG3 == 'r'],
        ['icon'=>'edit', 'title'=>'Edit', 'link'=>'acp/users/u/'.$theUser['id'], 'active'=>SEG3 == 'u'],
    ];
    Yii::$app->params['page_actions'][] = [
        ['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'acp/users/d/'.$theUser['id'], 'active'=>SEG3 == 'd', 'class'=>'text-danger'],
    ];

} else {
    if (SEG3 == 'c') {
        Yii::$app->params['page_title'] = 'New user';
        Yii::$app->params['page_breadcrumbs'][] = ['New', 'acp/users/c'];
    }   
}

$userRoleList = [
    'admin'=>'Admin',
    'valuer'=>'Valuer',
    'qa'=>'QA',
    'manager'=>'Manager',
    'sysadmin'=>'Sys admin',
];