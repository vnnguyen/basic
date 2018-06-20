<?
use yii\helpers\Html;

Yii::$app->params['page_breadcrumbs'] = [
    ['B2B', 'b2b'],
    ['Production', 'b2b'],
    ['Tour programs', SEG2 != 'programs' ? 'b2b/programs' : null],
];
Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'plus', 'title'=>'New', 'link'=>'b2b/programs/c', 'active'=>SEG3 == 'c'],
        ['submenu'=>[
            ['icon'=>'plus', 'label'=>'New (B2B)', 'link'=>'b2b/programs/c', 'active'=>SEG3 == 'c' && Yii::$app->request->get('b2b') == 'yes'],
            ['icon'=>'plus', 'label'=>'New (B2B PROD)', 'link'=>'b2b/programs/c?type=b2b-prod', 'active'=>SEG3 == 'c' && Yii::$app->request->get('type') == 'b2b-prod'],
            ],
        ],
    ],
];

if (isset($theProgram['id']) && in_array(SEG3, ['r', 'u', 'd', 'print-old', 'copy', 'print', 'upload'])) {
    Yii::$app->params['page_actions'][] = [
        ['icon'=>'eye', 'title'=>'View', 'link'=>'b2b/programs/r/'.$theProgram['id'], 'active'=>SEG3 == 'r'],
        ['icon'=>'edit', 'title'=>'Edit', 'link'=>'b2b/programs/u/'.$theProgram['id'], 'active'=>SEG3 == 'u'],
        ['submenu'=>[
            ['icon'=>'heart', 'label'=>'Make proposal', 'link'=>'bookings/c?product_id='.$theProgram['id'], 'visible'=>$theProgram['offer_count'] == 0],
            ['icon'=>'files-o', 'label'=>'Copy as new', 'link'=>'products/copy/'.$theProgram['id'], 'active'=>SEG3 == 'copy'],
            ['icon'=>'file-text-o', 'label'=>'Upload attachments', 'link'=>'products/upload/'.$theProgram['id'], 'active'=>SEG3 == 'upload'],
            ['icon'=>'print', 'label'=>'NEW: Auto Word file', 'link'=>'http://www.amica-travel.com/imsprint/'.$theProgram['id'].'/'.md5($theProgram['created_at'])],
            ['icon'=>'print', 'label'=>'Print (English)', 'link'=>'ct/print-old/'.$theProgram['id'].'/en', 'active'=>SEG3 == 'print'],
            ['icon'=>'print', 'label'=>'Print (Francais)', 'link'=>'products/print/'.$theProgram['id'], 'active'=>SEG3 == 'print'],
            ],
        ],
    ];
    Yii::$app->params['page_actions'][] = [
        ['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'products/d/'.$theProgram['id'], 'active'=>SEG3 == 'd', 'class'=>'text-danger'],
    ];
}

$languageList = [
    'en'=>'English',
    'fr'=>'Francais',
    'it'=>'Italiano',
    'vi'=>'Tiếng Việt',
];

$ctTypeList = [
    'private'=>'Private tour',
    'vpc'=>'VPC tour',
    'tcg'=>'TCG tour',
    'agent'=>'GIT tour',
    'b2b-prod'=>'B2B PROD',
    'combined2016'=>'Combined tour',
    ''=>'Other',
];

$this->params['icon'] = 'gift';

if (isset($theProgram['id'])) {
    $productViewTabs = [
        ['label'=>'Product overview', 'link'=>'products/r/'.$theProgram['id']],
        ['label'=>'Sales & Bookings', 'link'=>'products/sb/'.$theProgram['id']],
        ['label'=>'Operation', 'link'=>'products/op/'.$theProgram['id']],
    ];
}

if (isset($theProgram)) {
    if ($theProgram['op_status'] == 'op') {
        if (isset($theTour)) {
            Yii::$app->params['page_title'] = Html::a($theProgram['op_code'], '@web/tours/r/'.$theTour['id'], ['style'=>'background-color:#ffc; padding:0 3px; color:#148040;']). ' ';
        }
    }

    if ($theProgram['op_finish'] == 'canceled') {
        Yii::$app->params['page_title'] .= '<span style="color:#c00;">(CXL)</span> ';
    }
    if ($theProgram['offer_type'] == 'combined2016') {
        Yii::$app->params['page_title'] .= '<span class="text-uppercase text-light" style="background-color:#cff; padding:0 3px; color:#148040;">Combined</span> ';
    }

    Yii::$app->params['page_title'] .= $theProgram['title'];
}

Yii::$app->params['page_meta_title'] = strip_tags(Yii::$app->params['page_title']);