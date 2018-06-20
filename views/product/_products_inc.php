<?
use yii\helpers\Html;

Yii::$app->params['page_breadcrumbs'] = [
    ['Products', 'products'],
];
Yii::$app->params['page_actions'] = [
    [
        ['icon'=>'plus', 'title'=>'New', 'link'=>'products/c', 'active'=>SEG2 == 'c'],
        ['submenu'=>[
            ['icon'=>'plus', 'label'=>'New (B2C)', 'link'=>'products/c', 'active'=>SEG2 == 'c'],
            ['icon'=>'plus', 'label'=>'New (B2B)', 'link'=>'products/c?b2b=yes', 'active'=>SEG2 == 'c' && Yii::$app->request->get('b2b') == 'yes'],
            ['icon'=>'plus', 'label'=>'New (B2B PROD)', 'link'=>'products/c?b2b=prod', 'active'=>SEG2 == 'c' && Yii::$app->request->get('b2b') == 'prod'],
            ],
        ],
    ],
];

if (isset($theProduct['id']) && in_array(SEG2, ['r', 'u', 'd', 'print-old', 'copy', 'print', 'upload'])) {
    Yii::$app->params['page_actions'][] = [
        ['icon'=>'eye', 'title'=>'View', 'link'=>'products/r/'.$theProduct['id'], 'active'=>SEG2 == 'r'],
        ['icon'=>'edit', 'title'=>'Edit', 'link'=>'products/u/'.$theProduct['id'], 'active'=>SEG2 == 'u'],
        ['submenu'=>[
            ['icon'=>'heart', 'label'=>'Make proposal', 'link'=>'bookings/c?product_id='.$theProduct['id'], 'visible'=>$theProduct['offer_count'] == 0],
            ['icon'=>'files-o', 'label'=>'Copy as new', 'link'=>'products/copy/'.$theProduct['id'], 'active'=>SEG2 == 'copy'],
            ['icon'=>'file-text-o', 'label'=>'Upload attachments', 'link'=>'products/upload/'.$theProduct['id'], 'active'=>SEG2 == 'upload'],
            ['icon'=>'print', 'label'=>'NEW: Auto Word file', 'link'=>'http://www.amica-travel.com/imsprint/'.$theProduct['id'].'/'.md5($theProduct['created_at'])],
            ['icon'=>'print', 'label'=>'Print (English)', 'link'=>'ct/print-old/'.$theProduct['id'].'/en', 'active'=>SEG2 == 'print'],
            ['icon'=>'print', 'label'=>'Print (Francais)', 'link'=>'products/print/'.$theProduct['id'], 'active'=>SEG2 == 'print'],
            ],
        ],
    ];
    Yii::$app->params['page_actions'][] = [
        ['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'products/d/'.$theProduct['id'], 'active'=>SEG2 == 'd', 'class'=>'text-danger'],
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

if (isset($theProduct['id'])) {
    $productViewTabs = [
        ['label'=>'Product overview', 'link'=>'products/r/'.$theProduct['id']],
        ['label'=>'Sales & Bookings', 'link'=>'products/sb/'.$theProduct['id']],
        ['label'=>'Operation', 'link'=>'products/op/'.$theProduct['id']],
    ];
}

if (isset($theProduct)) {
    if ($theProduct['op_status'] == 'op') {
        if (isset($theTour)) {
            Yii::$app->params['page_title'] = Html::a($theProduct['op_code'], '@web/tours/r/'.$theTour['id'], ['style'=>'background-color:#ffc; padding:0 3px; color:#148040;']). ' ';
        }
    }

    if ($theProduct['op_finish'] == 'canceled') {
        Yii::$app->params['page_title'] .= '<span style="color:#c00;">(CXL)</span> ';
    }
    if ($theProduct['offer_type'] == 'combined2016') {
        Yii::$app->params['page_title'] .= '<span class="text-uppercase text-light" style="background-color:#cff; padding:0 3px; color:#148040;">Combined</span> ';
    }

    Yii::$app->params['page_title'] .= $theProduct['title'];
}

Yii::$app->params['page_meta_title'] = strip_tags(Yii::$app->params['page_title']);