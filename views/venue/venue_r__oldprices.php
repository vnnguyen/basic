<?
use yii\helpers\Html;
use yii\helpers\Markdown;

?>
        <div class="col-lg-6 col-md-7">
            <p>Xem giá từ: <?php foreach ($fromDTArray as $item) {
            echo Html::a($item, '#', ['class'=>(strtotime($item) > strtotime('now') ? 'from-future' : 'from-past').' from-dt', 'id'=>'from-'.$item]);
            echo ' - ';
        } ?></p>
<?php
$dvGrouping = 'Không phân nhóm';
$dvType = 'xxx';
foreach ($theVenue['dvo'] as $dvo) {
    if ($dvType != $dvo['stype']) {
        $dvType = $dvo['stype'];
?>
            <table class="table table-bordered table-narrow">
                <thead>
                <tr>
                    <th width="50%">Loại dịch vụ: <?= isset($dvTypes[$dvType]) ? $dvTypes[$dvType] : '-'?></th>
                    <th width="13%">Đơn vị</th>
                    <th width="37%" class="ta-r">Áp dụng từ <span id="quote-from-dt">-</span></th>
                </tr>
                </thead>
                <tbody>
                    <?php } // if sC != s[]?>
                    <?php if ($dvo['grouping'] != $dvGrouping) { 
                        $dvGrouping = $dvo['grouping']; ?>
                    <tr class="info"><td colspan="4"><?=$dvo['grouping'] == '' ? '(Tất cả)' : $dvo['grouping']?></td></tr>
                    <?php } ?>
                    <tr>
                        <td>
                            <?//$s['requires_dvc_id'] == 0 ? '' : '&mdash;'?>
                            <?= Html::a($dvo['name'], '@web/dvo/r/'.$dvo['id'], ['title'=>$dvo['search']]) ?>
                            <?php if (in_array(Yii::$app->user->id, [1, 1118, 1119198])) { ?>
                            <?= Html::a('e', '@web/dvo/u/'.$dvo['id'], ['class'=>'text-muted', 'title'=>'Sửa'])?>
                            <?= Html::a('d', '@web/dvo/d/'.$dvo['id'], ['class'=>'text-danger', 'title'=>'Xoá'])?>
                            <?= Html::a('p', '@web/cpo/c?cp_id='.$dvo['id'], ['class'=>'text-muted', 'title'=>'Giá'])?>
                            <?php } ?>
                        </td>
                        <td>
                            <?
                            if (trim($dvo['note']) != '') {
                                echo '<i class="fa fa-info-circle" rel="popover" data-title="'.$dvo['name'].'" data-content="'.str_replace('"', '', $dvo['note']).'"></i> ';
                            }
                            ?>
                            <?=$dvo['unit']?>
                        </td>
                        <td class="text-right">
                        <?
                        $cnt = 0;
                        foreach ($dvo['cpo'] as $cpo) {
                            //if ($cpo['cp_id'] == $dvo['id']) {
                                $cnt++; 
                                echo '<div class="hide from-dt from-'.$cpo['from_dt'].'">';
                                echo Html::a($cpo['name'], '@web/cpo/u/'.$cpo['id'], ['title'=>$cpo['search'], 'style'=>'float:left']);
                                if (trim($cpo['info']) != '') echo ' <i data-content="'.$cpo['info'].'" data-title="'.$cpo['name'].' : '.number_format($cpo['price'], 2).' '.$cpo['currency'].'" rel="popover" class="fa fa-info-circle"></i>';
                                ?>
                                <?= number_format($cpo['price'], intval($cpo['price']) == $cpo['price'] ? 0 : 2) ?>
                                <span class="text-muted"><?=$cpo['currency']?></span><?
                                echo '</div>';
                            //}
                                }
                                ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="col-lg-6 col-md-5">
            <?= Markdown::process($theVenue['info_pricing']) ?>
        </div>                         
