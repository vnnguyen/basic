<?
use yii\helpers\Html;
$this->title = 'Các báo cáo cá nhân';

$this->params['breadcrumb'] = [
	['Manager', '@web/manager'],
	['Reports'],
];

$this->params['icon'] = 'area-chart';
Yii::$app->params['body_class'] = 'sidebar-xs';

?>
<div class="col-md-12 wrap_card">
	<div class="card">
		<div class="card-header">
			<h6 class="card-title"><?= Yii::t('re', '1: THEO THÁNG LÀM VIỆC CỦA NHÂN VIÊN BÁN HÀNG') ?></h6>
		</div>

		<div class="card-body">
	        <ul class="nav nav-tabs click_tab" id="btn-group">
				<? for ($yr = $minYear; $yr <= $maxYear; $yr ++) { ?>
				<li class="nav-item"><a class="nav-link <?= ($yr == date('Y')) ? 'active' : ''?>" data-toggle="tab" href="#year1<?= $yr ?>"><?= $yr ?></a></li>
				<? } ?>
			</ul>
			<div id="tab-content" class="tab-content">
				<? for ($yr = $minYear; $yr <= $maxYear; $yr ++) { ?>
				<div id="year1<?= $yr ?>" class="<?= $yr == date('Y') ? 'active' : '' ?> tab-pane">
			        <div class="table-responsive">
			            <table class="table table-striped table-narrow">
			                <thead>
			                    <tr>
			                        <th>Chỉ số \ Tháng</th>
			                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
			                        <th class="text-center" ><?= $m ?></th>
			                        <?php } ?>
			                        <th class="text-center" >cả năm</th>
			                    </tr>
			                    <tr>
			                        <th></th>
			                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
			                        <th class="text-center"><?= Yii::t('app', 'TH'); ?></th>
			                        <?php } ?>
			                        <th class="text-center"><?= Yii::t('app', 'TH'); ?></th>
			                    </tr>
			                </thead>
			                <tbody>
								<?php foreach ($indexList1 as $index=>$name) { ?>
			                    <tr class="togglable">
			                        <th> <?= $name ?> </th>
			                        <?php $totals = 0;?>
			                        <?php
			                        $t_link = '';
		                        	switch ($index) {
		                        		case 'c_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;
		                        		case 'b_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;
		                        		case 'dt_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;
		                        		case 'cp_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;
		                        		case 'lg_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;
		                        		case 'pc_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;
		                        		case 'day_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;
		                        		case 'pax_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;
		                        		default:
		                        			$link = '/';
		                        			break;

		                        	}
			                        ?>
			                        <?php for ($m = 1; $m <= 12; $m ++) {
			                        	$total = isset($result[$yr][$m][$index]) ? $result[$yr][$m][$index]: 0;
			                        	$totals += $total;
			                        ?>
			                        <td class="text-center <?= $index == 'total' ? 'text-bold' : '' ?>">
			                            <?= Html::a($total, $t_link .'&month='.$m) ?>
			                        </td>
			                        <?php } ?>
			                        <td class="text-center text-bold"><?= Html::a($totals, $t_link) ?> </td>
			                    </tr>
			                    <?php } ?>
			                </tbody>
			            </table>
			        </div>
			    </div>
			<?php } ?>
			</div>
	    </div>
	</div>
	<div class="card">
		<div class="card-header">
			<h6 class="card-title"><?= Yii::t('re', '2: THEO THÁNG THỰC HIỆN TOURS (NGÀY KẾT THÚC)') ?></h6>
		</div>
	    <div class="card-body">

	        <ul class="nav nav-tabs click_tab" id="btn-group">
				<? for ($yr = $minYear; $yr <= $maxYear; $yr ++) { ?>
				<li class="nav-item"><a class="nav-link <?= ($yr == date('Y')) ? 'active' : ''?>" data-toggle="tab" href="#year2<?= $yr ?>"><?= $yr ?></a></li>
				<? } ?>
			</ul>
	        <div id="tab-content" class="tab-content">
				<? for ($yr = $minYear; $yr <= $maxYear; $yr ++) { ?>
				<div id="year2<?= $yr ?>" class="<?= $yr == date('Y') ? 'active' : '' ?> tab-pane">
			        <div class="table-responsive">
			            <table class="table table-striped table-narrow">
			                <thead>
			                    <tr>
			                        <th>Chỉ số \ Tháng</th>
			                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
			                        <th class="text-center" ><?= $m ?></th>
			                        <?php } ?>
			                        <th class="text-center" >cả năm</th>
			                    </tr>
			                    <tr>
			                        <th></th>
			                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
			                        <th class="text-center"><?= Yii::t('app', 'TH'); ?></th>
			                        <?php } ?>
			                        <th class="text-center"><?= Yii::t('app', 'TH'); ?></th>
			                    </tr>
			                </thead>
			                <tbody>
								<?php foreach ($indexList2 as $index=>$name) { ?>
			                    <tr class="togglable">
			                        <th>
			                            <?= $name ?>
			                        </th>
			                        <?php
			                        $t_link = '';
		                        	switch ($index) {
		                        		case 'c_end_dt_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;
		                        		case 'c_lost_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;
		                        		case 'c_won_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;
		                        		case 'c_won_pc_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;
		                        		case 'c_pax_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;
		                        		case 'c_day_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;
		                        		case 'c_dt_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;
		                        		case 'c_cp_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;
		                        		case 'c_laigop_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;
		                        		case 'c_ltdt_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;
		                        		case 'c_lgcp_total':
		                        			$t_link = '/cases?view=assigned&year='.$yr.'&owner_id=4829';
		                        			break;

		                        		default:
		                        			$link = '/';
		                        			break;
		                        	}
			                        ?>
			                        <?php $totals = 0; ?>
			                        <?php for ($m = 1; $m <= 12; $m ++) {
			                        	$total = isset($result[$yr][$m][$index]) ? $result[$yr][$m][$index]: 0;
			                        	$totals += $total;
			                        ?>
			                        <td class="text-center <?= $index == 'total' ? 'text-bold' : '' ?>">
			                            <?= Html::a($total, $t_link .'&month='.$m)?>
			                        </td>
			                        <?php } ?>
			                        <td class="text-center text-bold"> <?= Html::a($totals, $t_link)?> </td>
			                    </tr>
			                    <?php } ?>
			                </tbody>
			            </table>
			        </div>
			    </div>
			<?php } ?>
			</div>
	    </div>
	</div>
</div>
