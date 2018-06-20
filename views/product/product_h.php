<?
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;

require_once('/var/www/vendor/textile/php-textile/Parser.php');
$parser = new \Netcarver\Textile\Parser();

include('_products_inc.php');

Yii::$app->params['page_icon'] = 'map-o';
Yii::$app->params['body_class'] = 'sidebar-xs';

Yii::$app->params['page_breadcrumbs'][] = [$ctTypeList[$theProduct['offer_type']] ?? $theProduct['offer_type'], 'products?type='.$theProduct['offer_type']];
Yii::$app->params['page_breadcrumbs'][] = ['By '.$theProduct['createdBy']['name'], 'products?ub='.$theProduct['created_by']];
Yii::$app->params['page_breadcrumbs'][] = ['View'];
Yii::$app->params['page_actions'] = [
    [
		['icon'=>'arrow-left', 'label'=>'Return to previous form / Quay lại giao diện cũ', 'link'=>'products/r/'.$theProduct['id']]
    ],
];

$dayIdList = explode(',', $theProduct['day_ids']);
if (!$dayIdList) {
    $dayIdList = [];
}

if ($theProduct['image'] == '') {
    $theProduct['image'] = '/upload/devis-banners/halong2.jpg';
} else {
    $theProduct['image'] = '/upload/devis-banners/'.$theProduct['image'];
}

?>
<style>
td, th {vertical-align:top!important;}
.popover {max-width:500px;}
.table.table-summary td {background-color:#f0f0f0; border:1px solid #fff;}
.label.op {background-color:#369;}
</style>

<div class="col-md-6">
    <? include('_huan1.php'); ?>
</div>
<div class="col-md-6">
    <? include('_huan2.php'); ?>
</div>