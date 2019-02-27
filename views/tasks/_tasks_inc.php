<?php
Yii::$app->params['page_icon'] = 'check-square-o';


Yii::$app->params['page_breadcrumbs'] = [
    [Yii::t('x', 'My tasks'), 'tasks'],
    SEG2 == 'c' ? [Yii::t('x', 'New task')] : null,
    SEG3 == 'u' ? [Yii::t('x', 'Edit')] : null,
    SEG3 == 'd' ? [Yii::t('x', 'Delete')] : null,
];
Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'plus', 'title'=>'Add new task', 'link'=>'tasks/c', 'active'=>SEG2 == 'c'],
    ],
];
