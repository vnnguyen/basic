<?
use yii\helpers\Html;
use yii\helpers\Markdown;

?>
<div class="tab-pane" id="t-prices">
    <? if (!empty($theVenue['dvo'])) { ?>
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="active"><a href="#t-oldprices" data-toggle="tab" aria-expanded="true">Old prices</a></li>
        <li class=""><a href="#t-newprices" data-toggle="tab" aria-expanded="false">Current prices <span class="label label-warning">beta</span></a></li>
    </ul>
    <? } ?>
    <div class="tab-content">
        <? if (!empty($theVenue['dvo'])) { ?>
        <div class="tab-pane active" id="t-oldprices">
            <div class="row">
                <div class="col-md-8">
                    <p>Xem giá từ: <? foreach ($fromDTArray as $item) {
                    echo Html::a($item, '#', ['class'=>(strtotime($item) > strtotime('now') ? 'from-future' : 'from-past').' from-dt', 'id'=>'from-'.$item]);
                    echo ' - ';
                } ?></p>
<?
        $dvGrouping = 'Không phân nhóm';
        $dvType = 'xxx';
        foreach ($theVenue['dvo'] as $dvo) {
            if ($dvType != $dvo['stype']) {
                $dvType = $dvo['stype'];
        ?>
                    <table class="table table-bordered table-xxs">
                        <thead>
                        <tr>
                            <th width="50%">Loại dịch vụ: <?=isset($dvTypes[$dvType]) ? $dvTypes[$dvType] : '-'?></th>
                            <th width="13%">Đơn vị</th>
                            <th width="37%" class="ta-r">Áp dụng từ <span id="quote-from-dt">-</span></th>
                        </tr>
                        </thead>
                        <tbody>
                            <? } // if sC != s[]?>
                            <? if ($dvo['grouping'] != $dvGrouping) { 
                                $dvGrouping = $dvo['grouping']; ?>
                            <tr class="info"><td colspan="4"><?=$dvo['grouping'] == '' ? '(Tất cả)' : $dvo['grouping']?></td></tr>
                            <? } ?>
                            <tr>
                                <td>
                                    <?//$s['requires_dvc_id'] == 0 ? '' : '&mdash;'?>
                                    <?= Html::a($dvo['name'], '@web/dvo/r/'.$dvo['id'], ['title'=>$dvo['search']]) ?>
                                    <? if (in_array(Yii::$app->user->id, [1, 1118, 1119198])) { ?>
                                    <?= Html::a('e', '@web/dvo/u/'.$dvo['id'], ['class'=>'text-muted', 'title'=>'Sửa'])?>
                                    <?= Html::a('d', '@web/dvo/d/'.$dvo['id'], ['class'=>'text-danger', 'title'=>'Xoá'])?>
                                    <?= Html::a('p', '@web/cpo/c?cp_id='.$dvo['id'], ['class'=>'text-muted', 'title'=>'Giá'])?>
                                    <? } ?>
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
                            <? } ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <?= Markdown::process($theVenue['info_pricing']) ?>
                </div>                         
            </div><!-- .row -->
        </div>
        <? } // if not empty dvo ?>
        <? if (!empty($theVenue['dvo'])) { ?>
        <div class="tab-pane" id="t-newprices">
        <? } ?>
            <div class="row">
                <div class="col-md-8">
                    <? if (empty($theVenue['dvc'])) { ?>
                    <p><span class="text-uppercase text-bold">PRICING INFO:</span></p>
                    <p><?= Markdown::process($theVenue['info_pricing']) ?></p>
                    <? } ?>
                    <? if (in_array(USER_ID, [1, 8, 9198, 11134718])) { ?>
                    <?= Html::a('+New service', '/dv/c?venue_id='.$theVenue['id'], ['class'=>'pull-right']) ?>
                    <? } ?>
                    <p class="text-uppercase text-bold"><?= Yii::t('dv', 'Price table') ?></p>
                    <div class="row">
                        <div class="col-md-3">
                            Select a date:<br>
                            <input id="selme" type="text" data-today-button="<?= NOW ?>" class="form-control" name="" value="">
                        </div>
                        <div class="col-md-6">
                            Or select a period:<br>
                            <select name="ranger_dt" class="form-control">
                                <? foreach ($theVenue['dvc'] as $dvc) { ?>
                                    <? foreach ($dvc['dvd'] as $dvd) { ?>
                                        <? if ($dvd['stype'] == 'date') { ?>
                                <option value="<?= $dvd['id'] ?>"><?= $dvd['def'] ?> (<?= $dvd['code'] ?>, contract <?= $dvc['name'] ?>)</option>
                                        <? } ?>
                                    <? } ?>
                                <? } ?>
                            </select>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                    <br>
                    <? if (!empty($theVenue['dv'])) { ?>
                    <div class="table-responsive mb-20">
                        <table class="table table-bordered table-xxs" id="table_dv" data-venue-id="<?= $theVenue['id']?>">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Period</th>
                                    <th>Code & Prices</th>
                                </tr>
                            </thead>
                            <tbody id="list_dv"></tbody>
                        </table>
                    </div>
                    <? } ?>
                </div>
                <div class="col-md-4">
                    <p><span class="text-uppercase text-bold">CONTRACTS:</span>
                        <? foreach ($theVenue['dvc'] as $dvc) { ?>
                        <?= Html::a($dvc['name'], '/dvc/r/'.$dvc['id'], ['title'=>date('j/n/Y', strtotime($dvc['valid_from_dt'])).' - '.date('j/n/Y', strtotime($dvc['valid_until_dt']))]) ?>,
                        <? } ?>
                        <? if (in_array(USER_ID, [1, 8, 9198, 11134718])) { ?>
                        <?= Html::a('+New contract', '/dvc/c?venue_id='.$theVenue['id'], ['class'=>'pull-right']) ?>
                        <? } ?>
                    </p>
                    <hr>
                    <div class="note_display"></div>
                </div>
            </div>
        <? if (!empty($theVenue['dvo'])) { ?>
        </div>
        <? } ?>
    </div>
</div>