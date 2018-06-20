<?
use yii\helpers\Html;

Yii::$app->params['page_title'] = Yii::t('m', 'Issues');

Yii::$app->params['page_icon'] = 'edit';

Yii::$app->params['page_breadcrumbs'] = [
    [Yii::t('m', 'Master CP'), 'mcp'],
    [Yii::t('m', 'Issues'), SEG3 == '' ? null : 'mcp/issues'],
];

Yii::$app->params['page_actions'][] = [
    ['icon'=>'list', 'title'=>Yii::t('m', 'All issues'), 'link'=>'mcp/issues', 'active'=>SEG3 == ''],
    ['icon'=>'plus', 'title'=>Yii::t('app', 'Add new'), 'link'=>'mcp/issues/c', 'active'=>SEG3 == 'c'],
];

if (isset($theIssue['id'])) {
    Yii::$app->params['page_actions'][] = [
    ['icon'=>'eye', 'title'=>Yii::t('app', 'View'), 'link'=>'mcp/issues/r/'.$theIssue['id'], 'active'=>SEG3 == 'r'],
    ['icon'=>'edit', 'title'=>Yii::t('app', 'Edit'), 'link'=>'mcp/issues/u/'.$theIssue['id'], 'active'=>SEG3 == 'u'],
    ['icon'=>'trash-o', 'title'=>Yii::t('app', 'Delete'), 'class'=>'text-danger', 'link'=>'mcp/issues/d/'.$theIssue['id'], 'active'=>SEG3 == 'd'],
    ];
}

$categoryList = [
    '- Select -',
    'Bug',
    'Feature',
    'Design',
];

$pctList = [
    '0'=>'0%',
    '10'=>'10%',
    '20'=>'20%',
    '30'=>'30%',
    '40'=>'40%',
    '50'=>'50%',
    '60'=>'60%',
    '70'=>'70%',
    '80'=>'80%',
    '90'=>'90%',
    '100'=>'100%',
];

$statusList = [
    'new'=>'New',
    'confirmed'=>'Confirmed',
    'wip'=>'Working in progress',
    'ud'=>'Under discussion',
    'completed'=>'Completed',
    'notabug'=>'Not a bug',
    'wontfix'=>'WONTFIX',
];

$projectList = [
    1=>'IMS',
    99=>'Other',
];

$milestoneList = [
    'Bán hàng'=>'Bán hàng',
    'Điều hành'=>'Điều hành',
    'Kế toán'=>'Kế toán',
    'Quan hệ khách hàng'=>'Quan hệ khách hàng',
    'Khác'=>'Khác',
];