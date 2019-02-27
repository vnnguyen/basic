<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
// Yii::$app->params['page_actions'][] = [
//     ['icon'=>'list', 'label'=>'R01', 'link'=>'reports/qhkh-01', 'active'=>SEG2 == 'qhkh-01'],
//     ['icon'=>'list', 'label'=>'R02', 'link'=>'reports/qhkh-02', 'active'=>SEG2 == 'qhkh-02'],
//     ['icon'=>'list', 'label'=>'R03', 'link'=>'reports/qhkh-03', 'active'=>SEG2 == 'qhkh-03'],
//     // ['submenu'=>[
//     //     ['icon'=>'file-text-o', 'label'=>'View/edit itinerary', 'link'=>'products/r/'.$theTour['id']],
//     //     ['-'],
//     //     ['icon'=>'car', 'label'=>'Tour guides and drivers', 'link'=>'tours/gx/'.$theTourOld['id']],
//     //     ['-'],
//     //     ['icon'=>'file-pdf-o', 'label'=>'PDF summary', 'link'=>'tours/summary/'.$theTour['id']],
//     //     ['-'],
//     //     ['icon'=>'edit', 'label'=>'Edit tour info', 'link'=>'tours/u/'.$theTourOld['id']],
//     //     ['icon'=>'times', 'label'=>'Cancel tour', 'link'=>'tours/cxl/'.$theTour['id'], 'visible'=>$theTourOld['status'] != 'deleted'],
//     //     ],
//     // ],
// ];

// Yii::$app->params['page_icon'] = 'chart';

Yii::$app->params['page_breadcrumbs'] = [
    [Yii::t('x', 'Reports'), '@web/reports'],
    [Yii::t('x', 'Sales B2C'), 'reports/sales-b2c'],
    [Yii::t('x', 'Warning 01'), 'reports/sales-b2c'],
    ['View'],
];

