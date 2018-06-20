<?
use yii\helpers\Html;
use yii\helpers\Markdown;
?>

<div class="tab-pane" id="t-tours">
    <p>Các tour có sử dụng dịch vụ ở đây (max 100 tour). <?= Html::a('Click vào đây để xem tất cả', '@web/tools/tour-ks?ks='.$theVenue['id'], ['rel'=>'external']) ?></p>
    <table class="table table-striped table-condensed table-bordered">
        <tbody>
            <? foreach ($venueTours as $tour) { ?>
            <tr>
                <td class="text-nowrap"><?= date('j/n/Y', strtotime($tour['dvtour_day'])) ?></td>
                <td><?= Html::a($tour['code'].' - '.$tour['name'], '@web/tours/r/'.$tour['id']) ?></td>
                <td><?= Html::a($tour['dvtour_name'], '@web/cpt/r/'.$tour['dvtour_id']) ?></td>
                <td class="text-center"><?= number_format($tour['qty'], intval($tour['qty']) == $tour['qty'] ? 0 : 2) ?></td>
                <td><?= $tour['unit'] ?></td>
                <td><?= number_format($tour['price'], intval($tour['price']) == $tour['price'] ? 0 : 2) ?> <span class="text-muted"><?= $tour['unitc'] ?></span></td>
            </tr>
            <? } ?>
        </tbody>
    </table>
</div>
