<?
$this->title = 'People database';
$this->params['icon'] = 'user';

$this->params['breadcrumb'] = [
    ['People', '@web/persons'],
];

$this->params['actions'] = [
    [
        ['icon'=>'list', 'title'=>'View all', 'link'=>'persons', 'active'=>SEG2 == ''],
        ['icon'=>'plus', 'title'=>'New', 'link'=>'persons/c', 'active'=>SEG2 == 'c'],
    ],
];

if (isset($theUser['id']) && in_array(SEG2, ['r', 'u', 'd'])) {
    $this->params['actions'][] = [
        ['icon'=>'eye', 'title'=>'View', 'link'=>'persons/r/'.$theUser['id'], 'active'=>SEG2 == 'r'],
        ['icon'=>'edit', 'title'=>'Edit', 'link'=>'persons/u/'.$theUser['id'], 'active'=>SEG2 == 'u'],
        ['submenu'=>[
            ['icon'=>'key', 'label'=>'Account & Login', 'link'=>'persons/account/'.$theUser['id'], 'active'=>SEG2 == 'account', 'visible'=>Yii::$app->user->id == 1],
            ['icon'=>'user', 'label'=>'Log in as user', 'link'=>'persons/loginas/'.$theUser['id'], 'active'=>SEG2 == 'loginas', 'visible'=>Yii::$app->user->id <= 4],
            ['-'],
            ['icon'=>'edit', 'label'=>'Amica member profile', 'link'=>'members/u/'.$theUser['id']],
            ['icon'=>'edit', 'label'=>'Tour guide profile', 'link'=>'tourguides/u/'.$theUser['id']],
            ['icon'=>'edit', 'label'=>'Driver profile', 'link'=>'drivers/u/'.$theUser['id']],
            ['-'],
            ['icon'=>'trash-o', 'label'=>'Delete', 'link'=>'persons/d/'.$theUser['id'], 'active'=>SEG2 == 'd'],
            ],
        ],
    ];
}
