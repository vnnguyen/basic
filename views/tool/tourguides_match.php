<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

Yii::$app->params['page_title'] = 'Ghép tên Hướng dẫn trả tiền tour';
Yii::$app->params['page_breadcrumbs'] = [
    ['Tools', '@web/tools']
];

$tours = [];

$tourIdList = [0];
foreach ($theTours as $tour) {
    $tourIdList[] = $tour['id'];
}

$sql = 'SELECT * FROM link_guide_ncc WHERE tour_id IN ('.implode(',', $tourIdList).')';
$nccTg = Yii::$app->db->createCommand($sql)->queryAll();


foreach ($theTours as $tour) {
    $tours[$tour['op_code']] = [
        'id'=>$tour['id'],
        'payer'=>[],
        'guide'=>[],
    ];
    foreach ($tour['tour']['cpt'] as $cpt) {
        if (!isset($tours[$tour['op_code']]['payer'][$cpt['payer']])) {
            $tours[$tour['op_code']]['payer'][$cpt['payer']] = [strtotime($cpt['dvtour_day'])];
        } else {
            $tours[$tour['op_code']]['payer'][$cpt['payer']][] = strtotime($cpt['dvtour_day']);
        }
    }
    foreach ($tour['guides'] as $guide) {
        $id = $guide['guide_user_id'] != 0 ? $guide['guide_user_id'] : $guide['guide_name'];
        if (!isset($tours[$tour['op_code']]['guide'][$id])) {
            $name = $guide['profile']['ma_ncc'] == '' ? ' #'.$guide['guide_user_id'].' '.$guide['guide_name'] : '#'.$guide['guide_user_id'].' '.$guide['profile']['ma_ncc'].' '.$guide['guide_name'];
            $tours[$tour['op_code']]['guide'][$id] = [
                'name'=>$name,
                'time'=>[strtotime($guide['use_from_dt']), strtotime($guide['use_until_dt'])],
            ];
        } else {
            $tours[$tour['op_code']]['guide'][$id]['time'][] = strtotime($guide['use_from_dt']);
            $tours[$tour['op_code']]['guide'][$id]['time'][] = strtotime($guide['use_until_dt']);
        }
    }
}

$cnt = 0;
?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <form class="form-inline">
                <?= Html::dropdownList('view', $view, ['end_date'=>'Kết thúc', 'start_date'=>'Bắt đầu'], ['class'=>'form-control']) ?>
                <?= Html::textInput('search', $search, ['class'=>'form-control', 'placeholder'=>'Tháng']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-xxs table-striped">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th>Tour</th>
                        <th>Hướng dẫn</th>
                        <th>Ngày tour</th>
                        <th>Link với</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    $nowCode = '';
                    foreach ($tours as $code=>$tour) {
                        foreach ($tour['payer'] as $name=>$payer) {
                            ?>
                    <tr>
                        <td class="text-center text-muted"><?= ++$cnt ?></td>
                        <td><?
                        if ($nowCode != $code) {
                            $nowCode = $code;
                            echo Html::a($code, '/tours/guides/'.$tour['id'], ['target'=>'_blank']);
                        }
                        ?></td>
                        <td class="text-nowrap"><?= $name ?></td>
                        <td class="text-center"><?= date('j/n', min($payer)) ?> - <?= date('j/n', max($payer)) ?></td>
                        <td>
<?
// Check xem da duoc link chua
$linked = 0;
foreach ($nccTg as $ncc) {
    foreach ($tour['guide'] as $id=>$guide) {
        if ($ncc['tour_id'] == $tour['id'] && $ncc['name'] == $name && ($ncc['guide_user_id'] == $id || $ncc['ma_ncc'] == $id)) {
            $linked = $id;
            break;
        }
    }
}
?>
                            <select class="form-control" <?= $linked != 0 ? 'disabled="disabled"' : '' ?> id="select_<?= $cnt ?>">
                                <option value="0">Không link</option>
                                <? foreach ($tour['guide'] as $id=>$guide) { ?>
                                <option value="<?= $id ?>" <?= $linked == $id ? 'selected="selected"' : '' ?>><?= $guide['name'] ?> (<?= date('j/n', min($guide['time'])) ?> - <?= date('j/n', max($guide['time'])) ?>)</option>
                                <? } ?>
                            </select>
                        </td>
                        <td class="text-nowrap">
                            <?= Html::button('<i class="fa fa-check-circle"></i>', ['class'=>'ok btn '.($linked == 0 ? '' : ' btn-success'), 'data-tour_id'=>$tour['id'], 'data-name'=>$name, 'data-select_id'=>'select_'.$cnt]) ?>
                            <?//= Html::button('<i class="fa fa-times"></i>', ['class'=>'nok btn _btn-danger']) ?>
                        </td>
                    </tr>
                        <? } ?>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?
$js = <<<'TXT'
$('button.btn.ok').on('click', function(){
    if ($(this).hasClass('btn-success')) {
        return false;
    }
    var tour_id=$(this).data('tour_id');
    var name = $(this).data('name');
    var value = $('select#' + $(this).data('select_id')).val();
    var item = $(this);
    $.ajax({
        method: "POST",
        url: "/tools/tourguides-match?action=ajax&xh",
        data: {tour_id:tour_id, name:name, value: value }
    }).done(function(msg) {
        item.addClass('btn-success');
        $('select#' + item.data('select_id')).attr('disabled', 'disabled');
        //alert("Data Saved: " + msg);
    }).fail(function(msg) {
        alert("Data Not Saved: " + msg);
    });
})
TXT;
$this->registerJs($js);