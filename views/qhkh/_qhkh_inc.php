<?php

Yii::$app->params['page_title'] = Yii::t('a', 'Customer relations');

Yii::$app->params['page_breadcrumbs'] = [
    [Yii::t('a', 'Customer relations'), SEG2 != '' ? 'qhkh' : null],
    SEG2 == 'quy-qhkh' ? [Yii::t('a', 'Fund')] : null,
    SEG2 == 'chot-tour' ? [Yii::t('a', 'Chốt tour'), isset($_GET['tour_id']) ? 'qhkh/chot-tour' : null] : null,
    SEG2 == 'quy-trinh-thu-mau' ? [Yii::t('a', 'Quy trình và thư mẫu')] : null,
    SEG2 == 'service-plus' ? [Yii::t('a', 'Services Plus'), Yii::$app->request->get('action') != 'list' ? 'qhkh/service-plus' : null] : null,
    Yii::$app->request->get('action') == 'add' ? ['Add'] : null,
    Yii::$app->request->get('action') == 'edit' ? ['Edit'] : null,
    Yii::$app->request->get('action') == 'delete' ? ['Delete'] : null,
];
// Yii::$app->params['page_actions'] = [
//     [
//         ['icon'=>'plus', 'title'=>Yii::t('app', '+New'), 'link'=>'permissions/c', 'active'=>SEG2 == 'c'],
//     ],
// ];

$qhkhChotKetthucList = [
    'nc'=>'Everything OK',
    'csk'=>'Complaints, solution OK',
    'csn'=>'Complaints, solution not OK',
    'csu'=>'Complaints, solution unknown result',
    'nf'=>'No feedback',
];

$qhkhChotDaKhaithacList = [
    1=>'Témoignages',
    2=>'Blog Amica',
    3=>'Forum (khách đã chủ động viết review trên diễn đàn)',
];

$qhkhChotDeXuatKhaithacList = [
    9=>'Re tour',
    4=>'Re chez Pa',
    5=>'Re chez Ich',
    6=>'Re chez Nguyen',
    7=>'Re chez Thanh',
    8=>'Re chez Tap',
];

$qhkhChotKhaithacList = [
    1=>'Témoignages',
    2=>'Blog Amica',
    3=>'Forum',
    9=>'Re tour',
    4=>'Re chez Pa',
    5=>'Re chez Ich',
    6=>'Re chez Nguyen',
    7=>'Re chez Thanh',
    8=>'Re chez Tap',
];
$qhkhChotDiemList = [
    1=>'1 - Very unsatisfied',
    2=>'2 - Unsatisfied',
    3=>'3 - Average',
    4=>'4 - Satisfied',
    5=>'5 - Very satisfied',
];
