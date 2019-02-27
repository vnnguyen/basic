<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

Yii::$app->params['page_title'] = 'Phân bổ tour cho QHKH theo điểm khởi hành theo tháng';

include('_report_qhkh_inc.php');

$destList = [
    'vn'=>Yii::t('x', 'Vietnam'),
    'la'=>Yii::t('x', 'Laos'),
    'kh'=>Yii::t('x', 'Cambodia'),
    'Other'=>Yii::t('x', 'Other'),
];

$staffList = [];
foreach ($result[$year] as $m=>$dest) {
    foreach ($dest as $d=>$user) {
        foreach ($user as $u=>$count) {
            if ($u != 0 && !in_array($u, $staffList)) {
                $staffList[] = $u;
            }
        }
    }
}
$users = \common\models\User::find()->select(['id', 'name'])->where(['id'=>$staffList])->asArray()->indexBy('id')->all();

?>

<div class="col-md-12">
    <form method="get" action="" class="form-inline mb-2">
        Xem năm
        <?= Html::dropdownList('year', $year, $yearList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Year')]) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '?') ?>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-narrow table-bordered">
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center" width="1%"><?= Yii::t('x', 'Month') ?></th>
                        <th rowspan="2" class="text-center"><?= Yii::t('x', 'Staff') ?></th>
                        <th colspan="4" class="text-center" width="48%"><?= Yii::t('x', 'Country of departure') ?></th>
                        <th rowspan="2" class="text-center" width="12%"><?= Yii::t('x', 'Points') ?></th>
                    </tr>
                    <tr>
                        <?php foreach ($destList as $destCode=>$destName) { ?>
                        <th class="text-center" width="12%"><?= $destName ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($m = 12; $m >= 1; $m --) { ?>
                    <tr>
                        <th class="text-center"><?= $m ?></th>
                        <th>
                            <?php foreach ($staffList as $staffId) { if (!empty($result[$year][$m]['vn'][$staffId]) || !empty($result[$year][$m]['la'][$staffId]) || !empty($result[$year][$m]['kh'][$staffId]) || !empty($result[$year][$m]['Other'][$staffId])) { ?>
                            <div class="text-nowrap" style="border-bottom:1px solid #eee"><?= $users[$staffId]['name'] ?? $staffId ?></div>
                            <?php } } ?>
                            <div class="text-warning text-nowrap"><?= Yii::t('x', 'No staff assigned') ?></div>
                        </th>
                        <?php
                        $results_point = [];
                        $results_tour = [];
                        $result_unknown = [];
                        $result_unknown_tour = [];
                        ?>
                        <?php foreach ($destList as $destCode=>$destName) { ?>
                        <?php
                            $result_unknown_tour[] = $unknown = isset($result[$year][$m][$destCode][0]) && count($result[$year][$m][$destCode][0]) > 0  ? count($result[$year][$m][$destCode][0]): 0;
                            $total_point_unknown = 0;
                            if ($unknown != 0) {
                                $total_point_unknown = $result_unknown[0][] = array_sum($result[$year][$m][$destCode][0]);
                            } else {
                                $unknown = '-';
                            }

                        ?>
                        <td>
                            <?php foreach ($staffList as $staffId) {
                                if (!empty($result[$year][$m]['vn'][$staffId]) || !empty($result[$year][$m]['la'][$staffId]) || !empty($result[$year][$m]['kh'][$staffId]) || !empty($result[$year][$m]['Other'][$staffId]))
                                {
                                    $tour_count = isset($result[$year][$m][$destCode][$staffId]) && count($result[$year][$m][$destCode][$staffId]) > 0 ? count($result[$year][$m][$destCode][$staffId]): 0;
                                    $results_tour[$staffId][] = $tour_count;
                                    $total_point = '-';
                                    if ($tour_count != 0) {

                                        $results_point[$staffId][] = $total_point = array_sum($result[$year][$m][$destCode][$staffId]);
                                    } else {
                                        $tour_count = '-';
                                    }
                                ?>
                            <div class="text-nowrap" style="border-bottom:1px solid #eee"><?= Html::a($tour_count . ' | p '. $total_point, '/tours?orderby=startdate&time='.$year.'-'.str_pad($m, 2, '0', STR_PAD_LEFT).'&departure='.$destCode.'&cservice='.$staffId, ['target'=>'_blank']) ?></div>
                            <?php } } ?>
                            <div class="text-nowrap"><?= Html::a($unknown .' | p '. $total_point_unknown, '/tours?orderby=startdate&time='.$year.'-'.str_pad($m, 2, '0', STR_PAD_LEFT).'&departure='.$destCode.'&cservice=no', ['target'=>'_blank']) ?> </div>
                        </td>
                        <?php } ?>
                        <td>
                            <?php foreach ($results_point as $user_id => $points) {?>
                            <div class="text-nowrap" style="border-bottom:1px solid #eee"><?= Html::a(array_sum($results_tour[$user_id]) . '| p-' .array_sum($points), '', ['target'=>'_blank']) ?></div>
                            <?php } ?>
                            <?php
                                $total_unknown = isset($result_unknown[0]) && count($result_unknown[0]) > 0? array_sum($result_unknown[0]): '-';
                             ?>
                            <div class="text-nowrap" ><?= Html::a(array_sum($result_unknown_tour) . ' | p ' . $total_unknown, '', ['target'=>'_blank']) ?></div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <pre>
Tiêu chí tính điểm      1 điểm          2 điểm          3 điểm          4 điểm
Độ dài ngày tour        từ 1-8          từ 9-12         từ 13-21        từ 22
Số khách                từ 1-4          từ 5-7          từ 8-11         từ 12
Tuổi khách              từ 2 pax u11
Địa điểm                Việt Nam only   Cam, Thái       Lào, Myanmar
Điểm tối đa/1 tour: 12 điểm
            </pre>
        </div>
    </div>
</div>
