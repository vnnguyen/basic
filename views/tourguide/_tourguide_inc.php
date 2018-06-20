<?

Yii::$app->params['page_title'] = Yii::t('a', 'Tour guides');
Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_layout'] = '-t';

Yii::$app->params['page_breadcrumbs'] = [
    [Yii::t('a', 'Tour operation'), '#'],
    [Yii::t('a', 'Tour guides'), SEG1 == 'tourguides' ? null : 'tourguides'],
];

Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'list', 'title'=>Yii::t('a', 'View all'), 'link'=>'tourguides', 'active'=>SEG2==''],
        ['icon'=>'plus', 'title'=>Yii::t('a', 'Add new'), 'link'=>'tourguides/c', 'active'=>SEG2=='c'],
    ]   
];


if (in_array(SEG2, ['r', 'u', 'd']) && isset($theGuide['id'])) {
    $this->params['actions'][] = [
        ['icon'=>'user', 'label'=>'Xem user', 'link'=>'users/r/'.$theGuide['id']],
    ];
    $this->params['actions'][] = [
        ['icon'=>'user', 'title'=>'View', 'link'=>'tourguides/r/'.$theGuide['id'], 'active'=>SEG2=='r'],
        ['icon'=>'edit', 'title'=>'Edit', 'link'=>'tourguides/u/'.$theGuide['id'], 'active'=>SEG2=='u'],
    ];
    $this->params['actions'][] = [
        ['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'tourguides/d/'.$theGuide['id'], 'active'=>SEG2=='d', 'class'=>'btn-danger'],
    ];
}

