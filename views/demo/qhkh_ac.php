<style>
    caption { caption-side: top!important }
</style>
<div class="d-flex flex-wrap w-100">
    <div class="col-md-4">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-striped table-narrow mb-0" >
                    <caption>AC</caption>
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Hà Nội</th>
                            <th>Sài Gòn</th>
                            <th>Luang</th>
                            <th>Conf</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $total_year = []; ?>
                        <?php for($m = 1; $m <= 12; $m ++) {?>
                            <?php
                            $cnt_hn = count($results[$m]['hn']);
                            $cnt_sg = count($results[$m]['sg']);
                            $cnt_lu = count($results[$m]['lu']);
                            $cnt_conf = count($results[$m]['conf']);
                            $cnt_total = $cnt_hn + $cnt_sg + $cnt_lu + $cnt_conf;
                            $total_year['hn'][] = $cnt_hn;
                            $total_year['sg'][] = $cnt_sg;
                            $total_year['lu'][] = $cnt_lu;
                            $total_year['conf'][] = $cnt_conf;
                            $total_year['total'][] = $cnt_total;
                            ?>
                            <tr class="">
                                <td class="text-nowrap "><?= $m?></td>
                                <td class="text-nowrap"><?= $cnt_hn?></td>
                                <td class="text-nowrap"><?= $cnt_sg?></td>
                                <td class="text-nowrap"><?= $cnt_lu?></td>
                                <td class="text-nowrap"><?= $cnt_conf?></td>
                                <td class="text-nowrap"><?= $cnt_total?></td>
                            </tr>
                        <?php } ?>
                        <tr class="">
                            <td class="text-nowrap "><?= $year?></td>
                            <td class="text-nowrap"><?= array_sum($total_year['hn'])?></td>
                            <td class="text-nowrap"><?= array_sum($total_year['sg'])?></td>
                            <td class="text-nowrap"><?= array_sum($total_year['lu'])?></td>
                            <td class="text-nowrap"><?= array_sum($total_year['conf'])?></td>
                            <td class="text-nowrap"><?= array_sum($total_year['total'])?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-striped table-narrow mb-0" >
                    <caption>Incidents</caption>
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Incidents <?= $year - 1?></th>
                            <th>Incidents <?= $year?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $total_year = []; $total_year_prev = []; ?>
                        <?php for($m = 1; $m <= 12; $m ++) {?>
                            <?php
                            $cnt_year = count($result_tours[$year][$m]);
                            $cnt_year_prev = count($result_tours[$year-1][$m]);
                            $total_year[] = $cnt_year;
                            $total_year_prev[] = $cnt_year_prev;
                            ?>
                            <tr class="">
                                <td class="text-nowrap "><?= $m?></td>
                                <td class="text-nowrap"><?= $cnt_year_prev?></td>
                                <td class="text-nowrap"><?= $cnt_year?></td>
                            </tr>
                        <?php } ?>
                        <tr class="">
                            <td class="text-nowrap "></td>
                            <td class="text-nowrap"><?= array_sum($total_year_prev)?></td>
                            <td class="text-nowrap"><?= array_sum($total_year)?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
        $incidentTypeList = [
            6=>Yii::t('incident', 'Visa/Travel documents'),
            7=>Yii::t('incident', 'Payment'),
            8=>Yii::t('incident', 'Transportation'),
            9=>Yii::t('incident', 'Air travel'),
            10=>Yii::t('incident', 'Accommodation'),
            11=>Yii::t('incident', 'Meal/Restaurant'),
            12=>Yii::t('incident', 'Guide'),
            13=>Yii::t('incident', 'Itinerary'),
            3=>Yii::t('incident', 'Security'),
            2=>Yii::t('incident', 'Health'),
            1=>Yii::t('incident', 'Service'),
            4=>Yii::t('incident', 'Internal'),
            5=>Yii::t('incident', 'Other'),
        ];
     ?>
     <div class="col-md-4">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-striped table-narrow mb-0" >
                    <caption>Nguyên nhân</caption>
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $total_year = []; $total_year_prev = []; ?>
                        <?php foreach ($incidentTypeList as $k => $type_name) {?>
                            <?php if (!isset($result_tours[$k])) continue; ?>
                            <tr class="">
                                <td class="text-nowrap "><?= $type_name?></td>
                                <td class="text-nowrap"><?= count($result_tours[$k])?></td>
                            </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-striped table-narrow mb-0" >
                    <!-- <caption></caption> -->
                    <thead>
                        <tr>
                            <th colspan="3">Tour complaints (<?= $tour_complaint?>)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total_cnt = array_sum($result_tours_point); ?>
                        <?php foreach ($result_tours_point as $k => $count) {?>
                            <?php $percent =  $count * 100 / $total_cnt?>
                        <tr class="">
                            <td class="text-nowrap "><?= $k?></td>
                            <td class="text-nowrap "><?= $count?></td>
                            <td class="text-nowrap "><?= number_format($percent, 2)?>%</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-striped table-narrow mb-0" >
                    <caption> Hài lòng về dịch vụ </caption>
                    <thead>
                        <tr>
                            <th>Dịch vụ</th>
                            <th>Khen</th>
                            <th>Chê/góp ý</th>
                            <th>Độ hài lòng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result_tours_fb as $stype_service => $fb) {?>
                            <?php
                                $smile = isset($fb['smile']) ? $fb['smile']: 0;
                                $frown = isset($fb['frown']) ? $fb['frown']: 0;
                                $meh = isset($fb['meh']) ? $fb['meh']: 0;
                                $percent = number_format($smile * 100 / ($frown + $meh + $smile), 2);

                            ?>
                            <tr class="">
                                <td class="text-nowrap "><?= $stype_service?></td>
                                <td class="text-nowrap"><?= $smile?></td>
                                <td class="text-nowrap"><?= $frown + $meh?></td>
                                <td class="text-nowrap"><?= $percent?>%</td>
                            </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-striped table-narrow mb-0" >
                    <caption> Dịch vụ trái tim </caption>
                    <tbody>
                        <tr class="">
                            <td class="text-nowrap "><?= count($result_tours_sv_plus['services']); ?> dịch vụ được triển khai / <?=$result_tours_sv_plus['tours']?> đoàn ( <?= number_format(count($result_tours_sv_plus['services']) * 100 / $result_tours_sv_plus['tours'], 2)?>% )</td>
                        </tr>
                        <tr class="">
                            <td class="text-nowrap "><?= count($result_tours_sv_plus['yes'])?> dịch vụ thành công, chạm được cảm xúc (<?= number_format(count($result_tours_sv_plus['yes']) * 100 / count($result_tours_sv_plus['services']), 2) ?>% )</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>