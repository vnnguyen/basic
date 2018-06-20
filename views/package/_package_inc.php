<?

Yii::$app->params['page_title'] = 'Package tours by Amica Travel';

Yii::$app->params['page_breadcrumbs'] = [
    ['Packages', '@web/packages'],
];

Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'list', 'title'=>'All packages', 'link'=>'packages', 'active'=>SEG2 == ''],
        ['icon'=>'plus', 'title'=>'New', 'link'=>'packages/c', 'active'=>SEG2 == 'c'],
    ],
];

if (isset($thePackage['id'])) {
    Yii::$app->params['page_actions'][] = [
        ['icon'=>'eye', 'title'=>'View', 'link'=>'packages/r/'.$thePackage['id'], 'active'=>SEG2 == 'r'],
        ['icon'=>'edit', 'title'=>'Edit', 'link'=>'packages/u/'.$thePackage['id'], 'active'=>SEG2 == 'u'],
    ];
    Yii::$app->params['page_actions'][] = [
        ['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'packages/d/'.$thePackage['id'], 'active'=>SEG2 == 'd', 'class'=>'text-danger'],
    ];
}