<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

Yii::$app->params['page_breadcrumbs'] = [
    ['B2B', 'b2b'],
    ['Cases', SEG3 != '' ? 'b2b/cases' : null],
    SEG3 == 'c' ? ['New'] : null,
    in_array(SEG3, ['r', 'u', 'd']) ? ['View', SEG3 != 'r' ? 'b2b/cases/r/'.$theCase['id'] : null] : null,
    SEG3 == 'u' ? ['Edit'] : null,
];


Yii::$app->params['page_title'] = Yii::t('k', 'Cases');
if (SEG3 == 'c') {
    Yii::$app->params['page_title'] = Yii::t('k', 'New case');
} elseif (SEG3 == 'u') {
    Yii::$app->params['page_title'] = Yii::t('k', 'Edit case').': '.$theCase['name'];
}

Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'list', 'title'=>'View all', 'link'=>'b2b/cases', 'active'=>SEG3 == ''],
        ['icon'=>'plus', 'title'=>'+New', 'link'=>'b2b/cases/c', 'active'=>SEG3 == 'c'],
    ],
];

$kaseTypeList = [
    'b2b'=>'B2B - Request',
    'b2b-series'=>'B2B - Series',
    'b2b-prod'=>'B2B - Prod',
];