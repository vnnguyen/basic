<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = 'Lịch xem rối nước tháng '.date('n/Y', strtotime($month));
$this->params['breadcrumb'] = [
  ['Tours', 'tours'],
  [$month, 'tours?month='.$month],
];

Yii::$app->params['body_class'] = 'sidebar-xs';

// Dieu hanh mien Bac
$dhmb = [8162, 24820, 15081, 29212, 34734, 118, 5270];

?>
<style type="text/css">
.text-bold {font-weight:bold;}
.text-line {text-decoration:line-through;}
</style>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading hidden-print">
            <form method="get" action="" class="form-inline">
                <select class="form-control" style="width:auto" name="month">
                    <? foreach ($monthList as $li) { ?>
                    <option value="<?= $li['ym'] ?>" <?= $li['ym'] == $month ? 'selected="selected"' : ''?>>Tháng <?= $li['ym'] ?></option>
                    <? } ?>
                </select>
                <?= Html::dropdownList('venue', $venue, ArrayHelper::map($venueList, 'id', 'name'), ['class'=>'form-control']) ?>
                <button type="submit" class="btn btn-primary">Go</button>
            </form>
        </div>
        <div class="table-responsive">
    <table class="table table-xxs table-striped table-bordered">
        <thead>
            <tr>
                <th width="20">TT</th>
                <th width="80" class="text-center">Ngày</th>
                <th width="12%">Giờ xem</th>
                <th>Tour code</th>
                <th width="10%">Điều hành</th>
                <th width="100">Loại vé</th>
                <th width="30">SL</th>
                <th width="120">Thành $</th>
                <th>Ghi chú / guide</th>
            </tr>
        </thead>
        <tbody>
            <?
            $total = 0;
            $cnt = 0;
            $currentDay = 0;
            foreach ($theCptx as $cpt) {
                $cnt ++;
                if ($cpt['plusminus'] == 'minus') {
                    $x = -1;
                    $spanClass = 'text-danger text-bold text-line';
                } else {
                    $x = 1;
                    $spanClass = '';
                }

                $total += $x * $cpt['qty'] * $cpt['price'];
            ?>
            <tr>
                <td class="text-center text-muted"><?= $cnt ?></td>
                <td class="text-center text-nowrap">
                    <?
                    if ($cpt['dvtour_day'] != $currentDay) {
                        $currentDay = $cpt['dvtour_day'];
                        echo date('d-m-Y', strtotime($currentDay));
                    } else {
                        // echo '-';
                        echo date('d-m-Y', strtotime($currentDay));
                    }
                    ?>
                </td>
                <td class="text-center"><?= Html::a($cpt['dvtour_name'], '@web/cpt/r/'.$cpt['dvtour_id']) ?></td>
                <td><?= Html::a($cpt['tour']['code'], '@web/cpt/r/'.$cpt['tour']['id']) ?></td>
                <td class="text-nowrap"><?
                $names = [];
                foreach ($tourOperators as $operator) {
                    if ($operator['tour_id'] == $cpt['tour']['id'] && in_array($operator['id'], $dhmb)) {
                        $names[] = $operator['name'];
                    }
                }
                echo implode(', ', $names);
                ?></td>
                <td class="text-right text-nowrap">
                    <?= $cpt['plusminus'] == 'plus' ? '' : '&mdash;' ?>
                    <span class="<?= $spanClass ?>"><?= number_format($cpt['price']) ?></span>
                    <small class="text-muted">VND</small>
                </td>
                <td class="text-center">
                    <?= number_format($cpt['qty']) ?></td>
                <td class="text-right text-nowrap">
                    <?= number_format($x * $cpt['qty'] * $cpt['price']) ?>
                    <small class="text-muted">VND</small>
                </td>
                <td><?
                $names = [];
                foreach ($cpt['tour']['product']['guides'] as $tourguide) {
                    if (strtotime($tourguide['use_from_dt']) <= strtotime($cpt['dvtour_day']) && strtotime($tourguide['use_until_dt']) >= strtotime($cpt['dvtour_day'])) {
                        $names[] =  $tourguide['guide_name'];
                    }
                }
                echo implode(', ', $names);
                ?></td>
            </tr>
            <? } ?>
            <tr>
                <td colspan="6" class="text-right">Tổng tiền</td>
                <td colspan="2" class="text-right text-nowrap"><strong><?= number_format($total) ?></strong> <small class="text-muted">VND</small></td>
                <td></td>
            </tr>
        </tbody>
    </table>
        </div>
    </div>
</div>
