<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

include('_qhkh_inc.php');

Yii::$app->params['page_title'] = 'Chốt các tour theo tháng kết thúc';
Yii::$app->params['body_class'] = 'sidebar-xs';

for ($y = 2007; $y <= date('Y'); $y++) {
    $yearList[$y] = $y;
}
for ($m = 1; $m <= 12; $m++) {
    $monthList[$m] = $m;
}

$fgList = [
    'f'=>'Chỉ tour F',
    'all'=>'Tất cả tour',
];

?>
<div class="col-md-12">
    <form class="form-inline mb-2">
        <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>'Năm']) ?>
        <?= Html::dropdownList('month', $month, $monthList, ['class'=>'form-control', 'prompt'=>'Tháng']) ?>
        <?= Html::dropdownList('fg', $fg, $fgList, ['class'=>'form-control']) ?>
        <?= Html::dropdownList('staff', $staff, $staffList, ['class'=>'form-control', 'prompt'=>'Mọi nhân viên']) ?>
        <?= Html::dropdownList('ketthuc', $ketthuc, $qhkhChotKetthucList, ['class'=>'form-control', 'prompt'=>'Tình trạng kết thúc tour']) ?>
        <?= Html::dropdownList('khaithac', $khaithac, $qhkhChotKhaithacList, ['class'=>'form-control', 'prompt'=>'Khai thác sau tour']) ?>
        <?= Html::dropdownList('diem', $diem, $qhkhChotDiemList, ['class'=>'form-control', 'prompt'=>'Điểm hài lòng']) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '?') ?>
    </form>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-narrow table-striped">
                <thead>
                    <tr>
                        <th width="15"></th>
                        <th width="40"></th>
                        <th>Tên tour</th>
                        <th class="text-center">Thời gian</th>
                        <th class="text-center">Ngày</th>
                        <th class="text-center">Pax</th>
                        <th>QHKH</th>
                        <th>Tên liên hệ</th>
                        <th>Email liên hệ</th>
                        <th class="text-center">Chốt tour</th>
                        <th class="text-center">Điểm QHKH</th>
                        <th class="text-center">Điểm pax</th>
                        <th>Khai thác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($theTours as $cnt=>$tour) {
                        $tour['contact_name'] = '';
                        $tour['contact_email'] = '';
                        $tour['pax_count'] = 0;
                        foreach ($tour['bookings'] as $booking) {
                            $tour['pax_count'] += $booking['pax'];
                            foreach ($booking['case']['people'] as $person) {
                                $tour['contact_name'] = Html::a($person['name'], '/contacts/'.$person['id']);
                                foreach ($person['metas'] as $meta) {
                                    $tour['contact_email'] = $meta['value'];
                                }
                                // break;
                            }
                        }
                        ?>
                    <tr class="<?= substr($tour['op_code'], 0, 1) == 'G' ? 'warning' : '' ?>">
                        <td class="text-center"><?= Html::a('<i class="fa fa-edit"></i>', '?tour_id='.$tour['id'], ['class'=>'text-muted']) ?></td>
                        <td class="text-center text-muted"><?= 1 + $cnt ?></td>
                        <td>
                            <?= Html::a($tour['op_code'].' - '.$tour['op_name'], '/products/op/'.$tour['id']) ?>
                        </td>
                        <td class="text-center text-nowrap"><?= date('j/n', strtotime($tour['start_date'])) ?> - <?= date('j/n', strtotime($tour['end_date'])) ?></td>
                        <td class="text-center"><?= $tour['day_count'] ?></td>
                        <td class="text-center"><?= $tour['pax_count'] ?></td>
                        <td><?php foreach ($tour['tour']['cskh'] as $i=>$qhkh) {
                            if ($i > 0) {
                                echo ', ';
                            }
                            echo $qhkh['name'];
                            } ?></td>
                        <td><?= $tour['contact_name'] ?></td>
                        <td><?= $tour['contact_email'] ?></td>
                        <?php if (isset($tour['tourStats']['qhkh_ketthuc']) && $tour['tourStats']['qhkh_ketthuc'] != '') { ?>
                        <td class="text-center"><?= $qhkhChotKetthucList[$tour['tourStats']['qhkh_ketthuc']] ?? $tour['tourStats']['qhkh_ketthuc'] ?? '' ?></td>
                        <?php
                        // Class diem
                        $class = '';
                        $diem = $tour['tourStats']['qhkh_diem'] ?? 0;
                        if ($diem == 1 || $diem == 5) {
                            $class .= ' text-bold';
                        }
                        if ($diem == 4 || $diem == 5) {
                            $class .= ' text-success';
                        }
                        if ($diem == 1 || $diem == 2) {
                            $class .= ' text-danger';
                        }
                        $diem = $tour['tourStats']['qhkh_diem'] ?? 0;
                        if ($diem == 0){
                            $diem = '';
                        }
                        ?>
                        <td class="text-center <?= $class ?>"><?= $diem ?></td>
                        <td class="text-center"><?= number_format($tour['tour']['pax_ratings'] / 10, 1) ?></td>
                        <td><?php
                        $qhkhKhaithac = explode('|', $tour['tourStats']['qhkh_khaithac'] ?? '');
                        $mktKhaithac = explode('|', $tour['tourStats']['mkt_khaithac'] ?? '');
                        
                        $khaithacList = [];
                        foreach ($qhkhChotKhaithacList as $itemId=>$itemName) {
                            if (in_array($itemId, $qhkhKhaithac) || in_array($itemId, $mktKhaithac)) {
                                $khaithacList[] = [
                                    'name'=>$itemName,
                                    'qhkh'=>in_array($itemId, $qhkhKhaithac) ? ($itemId <= 3 ? 'đã' : 'đề xuất') : '',
                                    'mkt'=>in_array($itemId, $mktKhaithac) ? 'da' : '',
                                ];
                            }
                        }

                        if (!empty($khaithacList)) {
                            foreach ($khaithacList as $i=>$khaithac) {
                                echo '<div>';
                                echo $khaithac['name'], ': ';
                                if ($khaithac['qhkh'] != '') {
                                    echo '<span title="QHKH ', $khaithac['qhkh'], ' khai thác" class="text-primary">Q</span>';
                                }
                                if ($khaithac['mkt'] != '') {
                                    echo '<span title="Marketing đã khai thác" class="text-warning">M</span>';
                                }
                                echo '</div>';
                            }
                        }
                        ?>
                        </td>
                        <?php } else { ?>
                        <td colspan="4" class="text-center text-danger">Chưa có dữ liệu</td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>