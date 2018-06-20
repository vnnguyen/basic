<?
use yii\helpers\Html;

include('_cp_inc.php');

Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_title'] = 'Tài liệu về Dịch vụ, Chi phí, Giá';
Yii::$app->params['page_icon'] = 'book';
Yii::$app->params['page_breadcrumbs'] = [
	['DVCP', 'dv'],
	['Tài liệu'],
];
?>
<div class="col-md-9">
    <? if (file_exists(Yii::getAlias('@app').'/views/cp/cp_doc__'.$page.'.php')) { ?>
    <? include('cp_doc__'.$page.'.php') ?>
    <? } ?>
</div>

<div class="col-md-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">TOC</h6>
        </div>
        <div class="panel-body">
            <ul>
                <li><a href="/cp/doc?page=00" class="<?= $page == '00' ? 'text-bold' : '' ?>">Khái niệm chung</a></li>
                <!--
                <li><a href="/cp/doc?page=10" class="<?= $page == '10' ? 'text-bold' : '' ?>">Dịch vụ</a></li>
                <li><a href="/cp/doc?page=20" class="<?= $page == '20' ? 'text-bold' : '' ?>">Chi phí</a></li>
                <li><a href="/cp/doc?page=30" class="<?= $page == '30' ? 'text-bold' : '' ?>">Giá</a></li>
                <li><a href="/cp/doc?page=40" class="<?= $page == '40' ? 'text-bold' : '' ?>">Thanh toán</a></li>
                <li><a href="/cp/doc?page=50" class="<?= $page == '50' ? 'text-bold' : '' ?>">Thống kê</a></li>
                -->
            </ul>
            <p>LINK TO CPDV</p>
            <ul>
                <li><a href="/dv">Dịch vụ tour</a></li>
            </ul>
        </div>
    </div>
</div>

<?
$js = <<<'TXT'
$('.fancybox').fancybox();
TXT;
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);