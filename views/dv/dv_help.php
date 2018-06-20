<?
use yii\helpers\Html;

include('_dv_inc.php');

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_title'] = 'Tài liệu về Dịch vụ, Chi phí, Giá';
Yii::$app->params['page_icon'] = 'book';
Yii::$app->params['page_breadcrumbs'] = [
	['DVCP', 'dv'],
	['Tài liệu'],
];
?>
<div class="col-md-9">
    <? if (file_exists(Yii::getAlias('@app').'/views/dv/dv_help__'.$page.'.php')) { ?>
    <? include('dv_help__'.$page.'.php') ?>
    <? } ?>
</div>

<div class="col-md-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">TOC</h6>
        </div>
        <div class="panel-body">
            <ul>
                <li><a href="/dv/help?page=00" class="<?= $page == '00' ? 'text-bold' : '' ?>">Khái niệm chung</a></li>
                <li><a href="/dv/help?page=01" class="<?= $page == '01' ? 'text-bold' : '' ?>">Tra cứu dịch vụ, chi phí</a></li>
                <li><a href="/dv/help?page=02" class="<?= $page == '02' ? 'text-bold' : '' ?>">Ký hiệu loại dịch vụ</a></li>
            </ul>
            <p>LINK TO CPDV</p>
            <ul>
                <li><a href="/dv">Dịch vụ tour</a></li>
            </ul>
        </div>
    </div>
</div>
