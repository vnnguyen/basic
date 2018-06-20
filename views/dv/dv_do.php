<?
// Copy venues to dv

use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\LinkPager;

include('_dv_inc.php');

Yii::$app->params['page_title'] = 'Copy thông tin dv';
Yii::$app->params['page_icon'] = 'magic';
Yii::$app->params['page_layout'] = '-h';
Yii::$app->params['body_class'] = 'sidebar-xs';

//\fCore::expose($theDvx);

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Tra cứu dịch vụ - chi phí</h6>
            <div class="heading-elements">
                <ul class="list-inline list-inline-separate heading-text">
                    <li><a href="/dv/c">+New</a></li>
                    <li><a href="/dv/help" target="_blank">Help</a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
        <?
        $cnt = 0;
        foreach ($theVenues as $venue) {
            ?>
            <div><strong><?= $venue['name'] ?></strong> <?= Html::a('View', '/venues/r/'.$venue['id']) ?></div>
            <? foreach ($venue['cp'] as $cp) {
                $cnt ++;

                ?>
            <div><?= $cnt ?>. <?= $cp['name'] ?><?= $cp['grouping'] != '' ? ', '.$cp['grouping'] : '' ?></div>
            <? } ?>
            <?
        }
        ?>
        </div>
    </div>
</div>