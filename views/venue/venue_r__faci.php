<?
use yii\helpers\Html;
use yii\helpers\Markdown;

?>
<div class="tab-pane" id="t-faci">
    <h3>Properties</h3>
    <div class="table-responsive">
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th>Room type</th>
                    <th>Total</th>
                    <th>Unit</th>
                    <th>Information</th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($theVenue['dvo'] as $dvo) { ?>
                <tr>
                    <td class="text-nowrap;"><?= $dvo['name'] ?></td>
                    <td><?//= $dvo['total'] ?></td>
                    <td><?= $dvo['unit'] ?></td>
                    <td><?= nl2br($dvo['note']) ?></td>
                </tr>
                <? } ?>
            </tbody>
        </table>
    </div>
    <? if ($theVenue['info_facilities'] != '') { ?>
    <h3>Facilities</h3>
    <?=Markdown::process($theVenue['info_facilities'])?>
    <? } ?>
    <? if ($theVenue['stype'] == 'hotel') { ?>
    <h3>Hotel features</h3>
    <?
    $theVenue['features'] = unserialize($theVenue['features']);
    if (!is_array($theVenue['features'])) $theVenue['features'] = array();
    foreach ($hotelFeatureList as $k=>$v) { ?>
    <h4><?=$k?></h4>
    <div class="clearfix" style="margin-bottom:10px;">
    <? $cnt = 0; foreach ($v as $kk=>$vv) { $cnt ++; ?>
    <div style="width:150px; float:left; margin:0 10px 0 5px;"><i class="fa fa<?=in_array($vv, $theVenue['features']) ? '-check' : ''?>-square-o"></i> <?=$vv?></div>
    <? } ?>
    </div>
    <? } // end for ?>
    <? } // end if ?>
</div>