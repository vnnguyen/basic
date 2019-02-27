<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

Yii::$app->params['page_title'] = 'Thưởng điều hành';

// $userIdList = array_unique(ArrayHelper::getColumn($theTkx, 'updated_by'));

// $userList = [];
// foreach ($theUsers as $user) {
//     if (in_array($user['id'], $userIdList)) {
//         $userList[$user['id']] = $user['name'];
//     }
// }
if (!function_exists('trimnf')) {
    // Trim trimnf
    function trimnf($num)
    {
        return is_numeric($num) ? rtrim(rtrim(number_format($num, 2), '0'), '.') : $num;
    }
}
$bonusList_title = [
    'vn-100'=>'Vietnam - 100% (normal) bonus',
    'vn-70'=>'Vietnam - 70% bonus',
    'vn-30'=>'Vietnam - 30% bonus',
    'vn-c'=>'Vietnam - An Hoa',
    'vn-200'=>'Vietnam - 200% bonus',
    'la-100'=>'Laos - 100% (normal) bonus',
    'la-s'=>'Laos - Southern Laos bonus (per tour)',
    'la-s-30'=>'Laos - 30% Southern Laos bonus (per tour)',
    'kh-100'=>'Cambodia - normal bonus',
    ''=>'Bonus unknown or not specified',
];
$bonusList = [
    'vn-100',
    'vn-70',
    'vn-30',
    'vn-c',
    'vn-200',
    'la-100',
    'la-s',
    'la-s-30',
    'kh-100',
    'unknown',
];
$this->registerCss('
.table tr.header_t .t_h {
    // -moz-transform: rotate(90deg);
}
.detail{ display: block;}
.detail:hover{
 cursor: pointer;
}
');
?>
<style>
    
</style>
<div class="col-md-12">
    <form class="form-inline mb-1em">
        <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>'Năm']) ?>
        <?= Html::dropdownList('month', $month, $monthList, ['class'=>'form-control', 'prompt'=>'Tháng']) ?>
        <? //Html::dropdownList('operator', $operator, $userList, ['class'=>'form-control', 'prompt'=>'Mọi điều hành']) ?>
        <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
        <?= Html::a('Reset', '?') ?>
    </form>
    <div class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-narrow table-bordered">
                <thead>
                    <tr>
                        <th rowspan="2"><?= Yii::t('dh', 'Operator')?></th>
                        <th class="text-center" colspan="<?= count($bonusList)?>"><?= Yii::t('dh', 'Tour code F')?></th>
                        <th class="text-center" colspan="<?= count($bonusList)?>"><?= Yii::t('dh', 'Tour code G')?></th>
                    </tr>
                    <tr class="header_t">
                        <?php foreach ($bonusList_title as $key => $level){ ?>
                            <th class="text-center"><div class="t_h"><?= $level?></div></th>
                        <?php } ?>
                        <?php foreach ($bonusList_title as $key => $level){ ?>
                            <th class="text-center"><div class="t_h"><?= $level?></div></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                	<?php
                    $arr_id = [];
                    $cnt = 0;
                    $arr_user_name = [];
                    $arr_mien_vung = [
                        15081 => 'mb', 8162 => 'mb', 24820 => 'mb',
                        7915 => 'mt',
                        37675 => 'mn', 27726 => 'mn',
                        9146 => 'lao', 30554 => 'lao', 25727 => 'lao',
                        19371 => 'cam', 31399 => 'cam', 1906 => 'cam',
                    ];
                    $total = [];
                    foreach ($bonusList as $key => $level){
                        $total['mb']['F'][$level] = 0;
                        $total['mt']['F'][$level] = 0;
                        $total['mn']['F'][$level] = 0;
                        $total['lao']['F'][$level] = 0;
                        $total['cam']['F'][$level] = 0;
                        $total['mb']['G'][$level] = 0;
                        $total['mt']['G'][$level] = 0;
                        $total['mn']['G'][$level] = 0;
                        $total['lao']['G'][$level] = 0;
                        $total['cam']['G'][$level] = 0;
                    }
                	foreach ($tours as $tu) {
                        $user_id = intval($tu['user_id']);
                        $arr_user_name[$tu['user_id']] = $tu['nickname'];
                        if (!isset($arr_id[$user_id])) {
                             $arr_id[$user_id]['F'] = [];
                             $arr_id[$user_id]['G'] = [];
                             foreach ($bonusList as $key => $level){
                                $arr_id[$user_id]['F'][$level] = 0;
                                $arr_id[$user_id]['G'][$level] = 0;
                             }
                            $arr_id[$user_id]['F']['desc'] = [];
                            $arr_id[$user_id]['G']['desc'] = [];
                        }
                        $tour_type = substr($tu['code'], 0, 1);
                		if($tu['days'] != '' && strpos($tu['days'], '|') !== false && count(explode('|', $tu['days'])) > 1){
                			$arr_days = explode('|', $tu['days']);

                			foreach ($arr_days as $k => $rday) {
                				if (strpos($rday, ':') === false) {
                					var_dump($rday);die;
                				}
                				$arr_ds = explode(':', $rday);
                                $d_cnt = 0;
                                $arr_dc = explode(',', $arr_ds[0]);
                                foreach ($arr_dc as $dc) {
                                    if (strpos($dc, '-') !== false) {
                                        $arr_r = explode('-', $dc);
                                        for ($i = $arr_r[0]; $i <= $arr_r[1] ; $i++) {
                                            $d_cnt ++;
                                        }
                                    } else {
                                        $d_cnt ++;
                                    }
                                    if (!isset($arr_ds[1]) || $arr_ds[1] == '') {
                                        $arr_ds[1] = 'unknown';

                                    }
                                    $arr_id[$user_id][$tour_type][$arr_ds[1]] += $d_cnt;

                                }
                            }
                        }
                        else {
                            if ($tu['days'] == '') {
                                // $arr_id[$user_id][$tour_type]['unknown'] ++;
                                continue;
                            }
                            $d_cnt = 0;
                            $arr_ds = explode(':', $tu['days']);
                            $arr_dc = explode(',', $arr_ds[0]);
                            foreach ($arr_dc as $dc) {
                                if (strpos($dc, '-') !== false) {
                                    $arr_r = explode('-', $dc);
                                    for ($i = $arr_r[0]; $i <= $arr_r[1] ; $i++) { 
                                        $d_cnt ++;
                                    }
                                } else {
                                    if ($dc == '') {
                                        continue;
                                    }
                                    $d_cnt ++;
                                }
                                if (!isset($arr_ds[1]) || $arr_ds[1] == '') {
                                    $arr_ds[1] = 'unknown';

                                }
                                $arr_id[$user_id][$tour_type][$arr_ds[1]] += $d_cnt;

                            }
                        }
                        $arr_id[$user_id][$tour_type]['desc'][] = $tu['code'] . '/'. $tu['days'];
                        if ($user_id == 8162) {
                        }
                    }

                     foreach ($arr_user_name as $id => $name){
                        ?>
                        <!--description-->

                        <?php
                        $des = '';
                        if (count($arr_id[$id]['F']['desc']) > 0) {
                            $des .= implode('--', $arr_id[$id]['F']['desc']);


                        }
                        if (count($arr_id[$id]['G']['desc']) > 0) {
                            $des .= implode('--', $arr_id[$id]['G']['desc']);
                        }
                        ?>
                        <tr data-des="<?= $des?>">
                            <td><?= $name.'-'.$id?></td>
                            <?php foreach ($arr_id[$id]['F'] as $key => $cnt){
                                if ($key == 'desc') continue;
                                $total[$arr_mien_vung[$id]]['F'][$key] += $cnt;
                            ?>
                                <td class="text-center"><?= $cnt > 0 ? '<span class="detail">'.$cnt. '</span>': '' ?></td>
                            <?php } ?>
                            <?php foreach ($arr_id[$id]['G'] as $key => $cnt){
                                if ($key == 'desc') continue;
                                $total[$arr_mien_vung[$id]]['G'][$key] += $cnt;
                            ?>
                                <td class="text-center"><?= $cnt > 0 ? '<span class="detail">'.$cnt. '</span>': '' ?></td>
                            <?php } ?>
                        </tr>
                    <?php }?>
                        <tr><td colspan="" rowspan="" headers=""></td></tr>
                        <tr>
                            <td>Miền Bắc</td>
                            <?php foreach ($total['mb']['F'] as $key => $cnt){ ?>
                                <td class="text-center"><?= $cnt > 0 ? $cnt: '' ?></td>
                            <?php } ?>
                            <?php foreach ($total['mb']['G'] as $key => $cnt){ ?>
                                <td class="text-center"><?= $cnt > 0 ? $cnt: '' ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>Miền Trung</td>
                            <?php foreach ($total['mt']['F'] as $key => $cnt){ ?>
                                <td class="text-center"><?= $cnt > 0 ? $cnt: '' ?></td>
                            <?php } ?>
                            <?php foreach ($total['mt']['G'] as $key => $cnt){ ?>
                                <td class="text-center"><?= $cnt > 0 ? $cnt: '' ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>Miền Nam</td>
                            <?php foreach ($total['mn']['F'] as $key => $cnt){ ?>
                                <td class="text-center"><?= $cnt > 0 ? $cnt: '' ?></td>
                            <?php } ?>
                            <?php foreach ($total['mn']['G'] as $key => $cnt){ ?>
                                <td class="text-center"><?= $cnt > 0 ? $cnt: '' ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>Lao</td>
                            <?php foreach ($total['lao']['F'] as $key => $cnt){ ?>
                                <td class="text-center"><?= $cnt > 0 ? $cnt: '' ?></td>
                            <?php } ?>
                            <?php foreach ($total['lao']['G'] as $key => $cnt){ ?>
                                <td class="text-center"><?= $cnt > 0 ? $cnt: '' ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>Cam</td>
                            <?php foreach ($total['cam']['F'] as $key => $cnt){ ?>
                                <td class="text-center"><?= $cnt > 0 ? $cnt: '' ?></td>
                            <?php } ?>
                            <?php foreach ($total['cam']['G'] as $key => $cnt){ ?>
                                <td class="text-center"><?= $cnt > 0 ? $cnt: '' ?></td>
                            <?php } ?>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Info</h4>
      </div>
      <div class="modal-body">
        <div class="content-div">
            <div class="table-responsive">
            <table class="table table-narrow table-bordered">
                <thead>
                    <tr>
                        <th>Tour code</th>
                        <th>Days</th>
                    </tr>
                </thead>
                <tbody id="modal_tbdy">
                </tbody>
            </table>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<?

$js = <<<'TXT'
var data_on_modal = '';
$(document).on('click', '.detail', function(){
    data_on_modal = $(this).closest('tr').data('des');
    $("#myModal").modal('show');
});
$("#myModal").on('show.bs.modal', function () {
    $(this).find('#modal_tbdy').empty();
    var arr_data = data_on_modal.split('--');
    $.each(arr_data, function(i, item){
        var tr = '<tr>';
        var arr_c = item.split('/');

        tr += '<td>' + arr_c[0] + '</td>' + '<td>' + arr_c[1] + '</td>';
        $('#modal_tbdy').append(tr);
    });
    // $(this).find('.content-div').text(data_on_modal);
});


TXT;

$this->registerJs($js);