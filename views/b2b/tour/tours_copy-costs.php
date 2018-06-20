<?

use yii\helpers\Html;

if (isset($sourceTour) && isset($destTour)) {
    $this->title = 'Copy costs, from tour '.Html::a($sourceProduct['op_code'], '@web/products/r/'.$sourceProduct['id']).' to tour '.Html::a($destProduct['op_code'], '@web/products/r/'.$destProduct['id']);
} else {
    $this->title = 'Copy costs, please select tours';
}
$this->params['icon'] = 'euro';
$this->params['breadcrumb'] = [
    ['Tours', '@web/tours'],
    ['Copy tour costs', '@web/tours/copy-costs'],
];
/*
$this->params['actions'] = [
    [
        ['icon'=>'font', 'title'=>'Font', 'link'=>'', 'active'=>isset($_GET['font'])],
        ['icon'=>'fire', 'title'=>'Fire', 'link'=>'',  'active'=>isset($_GET['fire'])],
        ['icon'=>'cog', 'title'=>'Cog', 'link'=>'',  'active'=>isset($_GET['cog'])],
    ],
    [
        ['icon'=>'truck', 'title'=>'Truck', 'label'=>'Truck', 'link'=>'',  'active'=>isset($_GET['truck'])],
        ['icon'=>'edit', 'title'=>'Edit', 'link'=>'',  'active'=>isset($_GET['edit'])],
        ['icon'=>'info', 'title'=>'Info', 'link'=>'',  'active'=>isset($_GET['info'])],
    ],
    [
        ['icon'=>'magic', 'label'=>'Magic', 'link'=>'',  'active'=>isset($_GET['magic'])],
        ['title'=>'More', 'submenu'=>[
            ['icon'=>'question', 'label'=>'Question', 'link'=>'',  'active'=>isset($_GET['question'])],
            ['icon'=>'flag', 'label'=>'This is a very long text to test the menu', 'link'=>'',  'active'=>isset($_GET['flag'])],
            '-',
            ['icon'=>'plus', 'label'=>'Add more...', 'link'=>'',  'active'=>isset($_GET['more'])],
            ]
        ],
    ],
];
*/
?>
<div class="col-md-12">
    <form class="form-inline mb-20" method="get" action="">
        <input type="text" class="form-control" name="s" value="<?= isset($sourceProduct['op_code']) ? $sourceProduct['op_code'] : '' ?>" autocomplete="off" placeholder="Source tour">
        <input type="text" class="form-control" name="d" value="<?= isset($destProduct['op_code']) ? $destProduct['op_code'] : '' ?>" autocomplete="off" placeholder="Dest tour">
<?
if (isset($sourceTour) && isset($destTour)) {
?>
        <input type="text" class="form-control" name="sd" value="" autocomplete="off" placeholder="Source day">
        <select class="form-control" name="dc">
            <option value="1">1 day</option>
            <? for ($i = 2; $i <= count($sourceProduct['days']); $i ++ ) { ?>
            <option value="<?= $i ?>"><?= $i ?> days</option>
            <? } ?>
        </select>
        <input type="text" class="form-control" name="dd" value="" autocomplete="off" placeholder="Dest day">
<?
} // if isset sourceTour
?>
        <button type="submit" class="btn btn-primary">Go</button>
<?
if (isset($sourceTour) && isset($destTour)) {
?>
        <a id="a-clear-all" href="#">Clear all</a>
        |
<?
} // if isset sourceTour
?>
        <a id="a-reset" href="<?= DIR ?>tours/copy-costs">Reset</a>
    </form>
</div>
<? if (isset($sourceTour) && isset($destTour)) { ?>
<div class="col-md-6">
    <div class="table-responsive">
        <table id="st" class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th width="20"></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
<?
            $cnt = 0;
            $dayIds = explode(',', $sourceProduct['day_ids']);
            foreach ($dayIds as $id) {
                foreach ($sourceProduct['days'] as $day) {
                    if ($day['id'] == $id) {
                        $theDate = date('d-m-Y', strtotime('+ '.$cnt.' days', strtotime($sourceProduct['day_from'])));
                        $cnt ++;
?>
            <tr class="tr-day info">
                <td colspan="6">
                    <a class="pull-right a-sd" data-id="<?= $theDate ?>" href="#">Add to copy</a>
                    <span class="label label-danger"><?= $cnt ?></span>
                    <strong><?= $theDate ?></strong>
                    <?= $day['name'] ?>
                    (<em><?= $day['meals'] ?></em>)
                </td>
            </tr>
<?
                        foreach ($sourceTour['cpt'] as $cpt) {
                            if (date('d-m-Y', strtotime($cpt['dvtour_day'])) == $theDate) {
?>
            <tr>
                <td><?= $cpt['dvtour_name'] ?></td>
                <td class="text-center"><?= rtrim($cpt['qty'], '.00') ?></td>
                <td><?= $cpt['unit'] ?></td>
                <td><?= isset($cpt['venue']) ? $cpt['venue']['name'] : $cpt['oppr'] ?></td>
                <td class="text-nowrap text-right"><?= number_format($cpt['price'], intval($cpt['price']) == $cpt['price'] ? 0 : 2) ?> <span class="text-muted"><?= $cpt['unitc'] ?></span></td>
            </tr>
<?
                            } // if dvtour_day
                        } // foreach cpt
                    } // if day_id
                } // foreach days
            } // foreach dayIds
?>
        </table>
    </div>
</div>
<div class="col-md-6">
    <div class="table-responsive">
        <table id="dt" class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th width="20"></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
<?
            $cnt = 0;
            $dayIds = explode(',', $destProduct['day_ids']);
            foreach ($dayIds as $id) {
                foreach ($destProduct['days'] as $day) {
                    if ($day['id'] == $id) {
                        $theDate = date('d-m-Y', strtotime('+ '.$cnt.' days', strtotime($destProduct['day_from'])));
                        $cnt ++;
?>
            <tr class="tr-day info">
                <td colspan="6">
                    <a class="pull-right a-dd" data-id="<?= $theDate ?>" href="#">Add to copy</a>
                    <span class="label label-danger"><?= $cnt ?></span>
                    <strong><?= $theDate ?></strong>
                    <?= $day['name'] ?>
                    (<em><?= $day['meals'] ?></em>)
                </td>
            </tr>
<?
                        foreach ($destTour['cpt'] as $cpt) {
                            if (date('d-m-Y', strtotime($cpt['dvtour_day'])) == $theDate) {
?>
            <tr>
                <td><?= $cpt['dvtour_name'] ?></td>
                <td class="text-center"><?= rtrim($cpt['qty'], '.00') ?></td>
                <td><?= $cpt['unit'] ?></td>
                <td><?= isset($cpt['venue']) ? $cpt['venue']['name'] : $cpt['oppr'] ?></td>
                <td class="text-nowrap text-right"><?= number_format($cpt['price'], intval($cpt['price']) == $cpt['price'] ? 0 : 2) ?> <span class="text-muted"><?= $cpt['unitc'] ?></span></td>
            </tr>
<?
                            } // if dvtour_day
                        } // foreach cpt
                    } // if day_id
                } // foreach days
            } // foreach dayIds
?>
        </table>
    </div>
</div>
<?

$js = <<<'TXT'
$('a.a-sd').click(function(){
    var val = $(this).data('id');
    $('input[name="sd"]').val(val);
    $('table#st tr.danger').removeClass('danger').addClass('info');
    $(this).parent().parent().removeClass('info').addClass('danger');
    return false;
});
$('a.a-dd').click(function(){
    var val = $(this).data('id');
    $('input[name="dd"]').val(val);
    $('table#dt tr.danger').removeClass('danger').addClass('info');
    $(this).parent().parent().removeClass('info').addClass('danger');
    return false;
});
$('a#a-clear-all').click(function(){
    $('tr.tr-day').removeClass('danger').addClass('info');
    $('input[name="sd"], input[name="dd"]').val('');
    return false;
});
$('input[name="s"], input[name="d"]').on('change', function(){
    $('input[name="sd"], input[name="dd"]').val('');
    $('tr.tr-day').removeClass('danger').addClass('info');
});
TXT;
$this->registerJs($js);

} // if isset sourceTour
