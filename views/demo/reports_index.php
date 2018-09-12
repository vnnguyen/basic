<?
use yii\helpers\Html;
$this->title = 'Các báo cáo cá nhân';

$this->params['breadcrumb'] = [
	['Manager', '@web/manager'],
	['Reports'],
];

$this->params['icon'] = 'area-chart';
Yii::$app->params['body_class'] = 'sidebar-xs';

$current_month = date('m', strtotime(NOW));

?>
<style>
	.td_head:hover{ cursor: pointer; }
</style>
<div class="col-md-12 wrap_card ">
	<form class="well well-sm form-inline">
		<input class="form-control" name="year" value="<?= $year?>" min="2007" max="2028" placeholder="Năm, vd. 2018" type="number">
		<button type="submit" class="btn btn-primary">Go</button>
	</form>
	<!-- 1 -->
	<div class="card">
		<div class="card-header">
			<h6 class="card-title"><?= Yii::t('re', '1: THEO THÁNG LÀM VIỆC CỦA NHÂN VIÊN BÁN HÀNG') ?></h6>
		</div>
		<div class="card-body">
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
	                    <tr class="">
	                        <th> <?= $name ?> </th>
	                        <?php $totals = 0;?>
	                        <?php
	                        $t_link = '';
                        	switch ($index) {
                        		case 'c_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;
                        		case 'b_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;
                        		case 'dt_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;
                        		case 'cp_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;
                        		case 'lg_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;
                        		case 'pc_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;
                        		case 'day_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;
                        		case 'pax_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;
                        		default:
                        			$link = '/';
                        			break;

                        	}
	                        ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$total = isset($result[$year][$m][$index]) ? $result[$year][$m][$index]: 0;
	                        	$totals += $total;
	                        ?>
	                        <td class="text-center">
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
	</div>
	<!-- 2 -->
	<div class="card">
		<div class="card-header">
			<h6 class="card-title"><?= Yii::t('re', '2: THEO THÁNG THỰC HIỆN TOURS (NGÀY KẾT THÚC)') ?></h6>
		</div>
	    <div class="card-body">
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
	                    <tr class="">
	                        <th>
	                            <?= $name ?>
	                        </th>
	                        <?php
	                        $t_link = '';
                        	switch ($index) {
                        		case 'c_end_dt_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;
                        		case 'c_lost_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;
                        		case 'c_won_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;
                        		case 'c_won_pc_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;
                        		case 'c_pax_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;
                        		case 'c_day_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;
                        		case 'c_dt_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;
                        		case 'c_cp_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;
                        		case 'c_laigop_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;
                        		case 'c_ltdt_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;
                        		case 'c_lgcp_total':
                        			$t_link = '/cases?view=assigned&year='.$year.'&owner_id=4829';
                        			break;

                        		default:
                        			$link = '/';
                        			break;
                        	}
	                        ?>
	                        <?php $totals = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$total = isset($result[$year][$m][$index]) ? $result[$year][$m][$index]: 0;
	                        	$totals += $total;
	                        ?>
	                        <td class="text-center">
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
	</div>
	<!-- 3 -->
	<div class="card">
		<div class="card-header">
			<h6 class="card-title">
				<a href="#collapse-link-howfound" class="font-weight-semibold collapsed" data-toggle="collapse" aria-expanded="false"> <?= Yii::t('re', 'Theo loại khách hàng lọc theo " Khách biết Amica bằng cách nào"') ?> </a>
			</h6>
		</div>
	    <div class="card-body collapse" id="collapse-link-howfound">
	        <div class="table-responsive">
	            <table class="table table-striped table-narrow">
	                <thead>
	                    <tr>
	                        <th class="w-25">Chỉ số \ Tháng</th>
	                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
	                        <th class="text-center" ><?= $m ?></th>
	                        <?php } ?>
	                        <th class="text-center" >cả năm</th>
	                    </tr>
	                </thead>
	                <tbody>
	                	<?php $arr_name = [];?>
						<?php foreach ($links_howfound as $index=>$t_link) { ?>

						<?php
							$arr = explode('_', $index);
							if (!in_array($arr[0], $arr_name)){
								$arr_name[] = $arr[0];
						?>
						<tr class="">
	                    	<th colspan="15" style="text-transform: capitalize; background-color: #e0f7fa">
	                    	<?php
	                    		echo Yii::t('re', $arr[0]);
	                    	?>
	                        </th>
	                    </tr>
						<?php }?>
	                    <tr class="">
	                        <td class="td_head">
	                            <?= count($arr) == 1 ?Yii::t('re', 'Theo tháng nhận hồ sơ xử lý') :  Yii::t('re', 'Theo tháng kết thúc tour')?>
	                        </td>
	                        <?php $pc_canam = 0;  $total_status_won = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['how_found:'.$index]['won']) ? count($totalCases_statusInMonth[$year][$m]['how_found:'.$index]['won']): 0;
	                        	$total_status_won += $num_status;
	                        	$total = isset($result[$year][$m][$index]) ? $result[$year][$m][$index]: 0;
	                        	$pc_canam += $total;
	                        ?>
	                        <td class="text-center">
	                        	<?php $pc = ($total > 0)? number_format($num_status*100/$total, 0): 0; ?>
	                            <?= Html::a( $pc.' %', $t_link .'&month='.$m, ['class' => 'text-success'])?>
	                        </td>
	                        <?php } ?>
	                        <?php $total_pc = ($pc_canam > 0)? number_format($total_status_won*100/$pc_canam, 0): 0; ?>
	                        <td class="text-center text-bold"> <?= Html::a($total_pc . ' %', $t_link, ['class' => 'text-success'])?> </td>
	                    </tr>
	                    <tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#2196f3"></i> Pending </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['how_found:'.$index]['pending']) ? count($totalCases_statusInMonth[$year][$m]['how_found:'.$index]['pending']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=pending')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=pending')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#4caf50"></i> Won </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['how_found:'.$index]['won']) ? count($totalCases_statusInMonth[$year][$m]['how_found:'.$index]['won']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=won')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=won')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#f44336"></i> Lost </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['how_found:'.$index]['lost']) ? count($totalCases_statusInMonth[$year][$m]['how_found:'.$index]['lost']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=lost')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=lost')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#333"></i> Total </th>
							<?php $totals = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$total = isset($result[$year][$m][$index]) ? $result[$year][$m][$index]: 0;
	                        	$totals += $total;
	                        ?>
	                        <td class="text-center">
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
	</div>
	<!-- 4 -->
	<div class="card">
		<div class="card-header">
		<h6 class="card-title">
				<a href="#collapse-link-howcontact" class="font-weight-semibold collapsed" data-toggle="collapse" aria-expanded="false"> <?= Yii::t('re', 'Theo nguồn khách hàng: lọc theo "Khách liên hệ bằng cách nào:"') ?> </a>
			</h6>
		</div>
	    <div class="card-body collapse" id="collapse-link-howcontact">
	        <div class="table-responsive">
	            <table class="table table-striped table-narrow">
	                <thead>
	                    <tr>
	                        <th class="w-25">Chỉ số \ Tháng</th>
	                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
	                        <th class="text-center" ><?= $m ?></th>
	                        <?php } ?>
	                        <th class="text-center" >cả năm</th>
	                    </tr>
	                </thead>
	                <tbody>
	                	<?php $arr_name = [];?>
						<?php foreach ($links_howcontacted as $index=>$t_link) { ?>

						<?php
							$arr = explode('_', $index);
							if (!in_array($arr[0], $arr_name)){
								$arr_name[] = $arr[0];
						?>
						<tr class="">
	                    	<th colspan="15" style="text-transform: capitalize; background-color: #e0f7fa">
	                    	<?php
	                    		echo Yii::t('re', $arr[0]);
	                    	?>
	                        </th>
	                    </tr>
						<?php }?>
	                    <tr class="">
	                        <td class="td_head">
	                            <?= count($arr) == 1 ?Yii::t('re', 'Theo tháng nhận hồ sơ xử lý') :  Yii::t('re', 'Theo tháng kết thúc tour')?>
	                        </td>
	                        <?php $pc_canam = 0;  $total_status_won = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['how_contacted:'.$index]['won']) ? count($totalCases_statusInMonth[$year][$m]['how_contacted:'.$index]['won']): 0;
	                        	$total_status_won += $num_status;
	                        	$total = isset($result[$year][$m][$index]) ? $result[$year][$m][$index]: 0;
	                        	$pc_canam += $total;
	                        ?>
	                        <td class="text-center">
	                        	<?php $pc = ($total > 0)? number_format($num_status*100/$total, 0): 0; ?>
	                            <?= Html::a( $pc.' %', $t_link .'&month='.$m, ['class' => 'text-success'])?>
	                        </td>
	                        <?php } ?>
	                        <?php $total_pc = ($pc_canam > 0)? number_format($total_status_won*100/$pc_canam, 0): 0; ?>
	                        <td class="text-center text-bold"> <?= Html::a($total_pc . ' %', $t_link, ['class' => 'text-success'])?> </td>
	                    </tr>
	                    </tr>
	                    <tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#2196f3"></i> Pending </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['how_contacted:'.$index]['pending']) ? count($totalCases_statusInMonth[$year][$m]['how_contacted:'.$index]['pending']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=pending')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=pending')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#4caf50"></i> Won </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['how_contacted:'.$index]['won']) ? count($totalCases_statusInMonth[$year][$m]['how_contacted:'.$index]['won']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=won')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=won')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#f44336"></i> Lost </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['how_contacted:'.$index]['lost']) ? count($totalCases_statusInMonth[$year][$m]['how_contacted:'.$index]['lost']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=lost')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=lost')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#333"></i> Total </th>
							<?php $totals = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$total = isset($result[$year][$m][$index]) ? $result[$year][$m][$index]: 0;
	                        	$totals += $total;
	                        ?>
	                        <td class="text-center">
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
	</div>
	<!-- 5 -->
	<div class="card">
		<div class="card-header">
			<h6 class="card-title">
				<a href="#collapse-link-prospect" class="font-weight-semibold collapsed" data-toggle="collapse" aria-expanded="false"> <?= Yii::t('re', 'Theo mức độ tiềm năng') ?> </a>
			</h6>
		</div>
	    <div class="card-body collapse" id="collapse-link-prospect">
	        <div class="table-responsive">
	            <table class="table table-striped table-narrow">
	                <thead>
	                    <tr>
	                        <th class="w-25">Chỉ số \ Tháng</th>
	                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
	                        <th class="text-center" ><?= $m ?></th>
	                        <?php } ?>
	                        <th class="text-center" >cả năm</th>
	                    </tr>
	                </thead>
	                <tbody>
	                	<?php $arr_name = [];?>
						<?php foreach ($links_prospect as $index=>$t_link) { ?>

						<?php
							$arr = explode('_', $index);
							if (!in_array($arr[0], $arr_name)){
								$arr_name[] = $arr[0];
						?>
						<tr class="">
	                    	<th colspan="15" style="text-transform: capitalize; background-color: #e0f7fa">
	                    	<?php
	                    		echo $arr[0] . ' ' . str_repeat('*', $arr[0]);
	                    	?>
	                        </th>
	                    </tr>
						<?php }?>
	                    <tr class="">
	                        <td class="td_head">
	                            <?= count($arr) == 1 ?Yii::t('re', 'Theo tháng nhận hồ sơ xử lý') :  Yii::t('re', 'Theo tháng kết thúc tour')?>
	                        </td>
	                        <?php $pc_canam = 0;  $total_status_won = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['prospect:'.$index]['won']) ? count($totalCases_statusInMonth[$year][$m]['prospect:'.$index]['won']): 0;
	                        	$total_status_won += $num_status;
	                        	$total = isset($result[$year][$m]['prospect_' . $index]) ? $result[$year][$m]['prospect_' . $index]: 0;
	                        	$pc_canam += $total;
	                        ?>
	                        <td class="text-center">
	                        	<?php $pc = ($total > 0)? number_format($num_status*100/$total, 0): 0; ?>
	                            <?= Html::a( $pc.' %', $t_link .'&month='.$m, ['class' => 'text-success'])?>
	                        </td>
	                        <?php } ?>
	                        <?php $total_pc = ($pc_canam > 0)? number_format($total_status_won*100/$pc_canam, 0): 0; ?>
	                        <td class="text-center text-bold"> <?= Html::a($total_pc . ' %', $t_link, ['class' => 'text-success'])?> </td>
	                    </tr>
	                    <tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#2196f3"></i> Pending </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['prospect:'.$index]['pending']) ? count($totalCases_statusInMonth[$year][$m]['prospect:'.$index]['pending']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=pending')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=pending')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#4caf50"></i> Won </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['prospect:'.$index]['won']) ? count($totalCases_statusInMonth[$year][$m]['prospect:'.$index]['won']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=won')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=won')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#f44336"></i> Lost </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['prospect:'.$index]['lost']) ? count($totalCases_statusInMonth[$year][$m]['prospect:'.$index]['lost']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=lost')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=lost')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#333"></i> Total </th>
							<?php $totals = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$total = isset($result[$year][$m]['prospect_' . $index]) ? $result[$year][$m]['prospect_' . $index]: 0;
	                        	$totals += $total;
	                        ?>
	                        <td class="text-center">
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
	</div>
	<!-- 6 -->
	<div class="card">
		<div class="card-header">
			<h6 class="card-title">
				<a href="#collapse-link-daycount" class="font-weight-semibold collapsed" data-toggle="collapse" aria-expanded="false"> <?= Yii::t('re', 'Theo độ dài tours') ?> </a>
			</h6>
		</div>
	    <div class="card-body collapse" id="collapse-link-daycount">
	        <div class="table-responsive">
	            <table class="table table-striped table-narrow">
	                <thead>
	                    <tr>
	                        <th class="w-25">Chỉ số \ Tháng</th>
	                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
	                        <th class="text-center" ><?= $m ?></th>
	                        <?php } ?>
	                        <th class="text-center" >cả năm</th>
	                    </tr>
	                </thead>
	                <tbody>
	                	<?php $arr_name = [];?>
						<?php foreach ($links_day_count as $index=>$t_link) {// ?>

						<?php
							$arr = explode('_', $index);
							if (!in_array($arr[0], $arr_name)){
								$arr_name[] = $arr[0];
						?>
						<tr class="">
	                    	<th colspan="15" style="text-transform: capitalize; background-color: #e0f7fa">
	                    	<?php
	                    		echo Yii::t('re', $arr[0]);
	                    	?>
	                        </th>
	                    </tr>
						<?php }?>
	                    <tr class="">
	                        <td class="td_head">
	                            <?= count($arr) == 1 ?Yii::t('re', 'Theo tháng nhận hồ sơ xử lý') :  Yii::t('re', 'Theo tháng kết thúc tour')?>
	                        </td>
	                        <?php $pc_canam = 0;  $total_status_won = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['day_count:'.$index]['won']) ? count($totalCases_statusInMonth[$year][$m]['day_count:'.$index]['won']): 0;
	                        	$total_status_won += $num_status;
	                        	$total = isset($result[$year][$m][$index]) ? $result[$year][$m][$index]: 0;
	                        	$pc_canam += $total;
	                        ?>
	                        <td class="text-center">
	                        	<?php $pc = ($total > 0)? number_format($num_status*100/$total, 0): 0; ?>
	                            <?= Html::a( $pc.' %', $t_link .'&month='.$m, ['class' => 'text-success'])?>
	                        </td>
	                        <?php } ?>
	                        <?php $total_pc = ($pc_canam > 0)? number_format($total_status_won*100/$pc_canam, 0): 0; ?>
	                        <td class="text-center text-bold"> <?= Html::a($total_pc . ' %', $t_link, ['class' => 'text-success'])?> </td>
	                    </tr>
	                    <tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#2196f3"></i> Pending </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['day_count:'.$index]['pending']) ? count($totalCases_statusInMonth[$year][$m]['day_count:'.$index]['pending']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=pending')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=pending')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#4caf50"></i> Won </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['day_count:'.$index]['won']) ? count($totalCases_statusInMonth[$year][$m]['day_count:'.$index]['won']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=won')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=won')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#f44336"></i> Lost </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['day_count:'.$index]['lost']) ? count($totalCases_statusInMonth[$year][$m]['day_count:'.$index]['lost']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=lost')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=lost')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#333"></i> Total </th>
							<?php $totals = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$total = isset($result[$year][$m][$index]) ? $result[$year][$m][$index]: 0;
	                        	$totals += $total;
	                        ?>
	                        <td class="text-center">
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
	</div>
	<!-- 7 -->
	<div class="card">
		<div class="card-header">
			<h6 class="card-title">
				<a href="#collapse-link-pax_count" class="font-weight-semibold collapsed" data-toggle="collapse" aria-expanded="false"> <?= Yii::t('re', 'Theo số khách') ?> </a>
			</h6>
		</div>
	    <div class="card-body collapse" id="collapse-link-pax_count">
	        <div class="table-responsive">
	            <table class="table table-striped table-narrow">
	                <thead>
	                    <tr>
	                        <th class="w-25">Chỉ số \ Tháng</th>
	                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
	                        <th class="text-center" ><?= $m ?></th>
	                        <?php } ?>
	                        <th class="text-center" >cả năm</th>
	                    </tr>
	                </thead>
	                <tbody>
	                	<?php $arr_name = [];?>
						<?php foreach ($links_pax_count as $index=>$t_link) { ?>

						<?php
							$arr = explode('_', $index);
							if (!in_array($arr[0], $arr_name)){
								$arr_name[] = $arr[0];
						?>
						<tr class="">
	                    	<th colspan="15" style="text-transform: capitalize; background-color: #e0f7fa">
	                    	<?php
	                    		echo Yii::t('re', $arr[0]);
	                    	?>
	                        </th>
	                    </tr>
						<?php }?>
	                    <tr class="">
	                        <td class="td_head">
	                            <?= count($arr) == 1 ?Yii::t('re', 'Theo tháng nhận hồ sơ xử lý') :  Yii::t('re', 'Theo tháng kết thúc tour')?>
	                        </td>
	                        <?php $pc_canam = 0;  $total_status_won = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['pax_count:'.$index]['won']) ? count($totalCases_statusInMonth[$year][$m]['pax_count:'.$index]['won']): 0;
	                        	$total_status_won += $num_status;
	                        	$total = isset($result[$year][$m]['pax_' . $index]) ? $result[$year][$m]['pax_' . $index]: 0;
	                        	$pc_canam += $total;
	                        ?>
	                        <td class="text-center">
	                        	<?php $pc = ($total > 0)? number_format($num_status*100/$total, 0): 0; ?>
	                            <?= Html::a( $pc.' %', $t_link .'&month='.$m, ['class' => 'text-success'])?>
	                        </td>
	                        <?php } ?>
	                        <?php $total_pc = ($pc_canam > 0)? number_format($total_status_won*100/$pc_canam, 0): 0; ?>
	                        <td class="text-center text-bold"> <?= Html::a($total_pc . ' %', $t_link, ['class' => 'text-success'])?> </td>
	                    </tr>
	                    <tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#2196f3"></i> Pending </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['pax_count:'.$index]['pending']) ? count($totalCases_statusInMonth[$year][$m]['pax_count:'.$index]['pending']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=pending')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=pending')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#4caf50"></i> Won </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['pax_count:'.$index]['won']) ? count($totalCases_statusInMonth[$year][$m]['pax_count:'.$index]['won']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=won')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=won')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#f44336"></i> Lost </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['pax_count:'.$index]['lost']) ? count($totalCases_statusInMonth[$year][$m]['pax_count:'.$index]['lost']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=lost')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=lost')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#333"></i> Total </th>
							<?php $totals = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$total = isset($result[$year][$m]['pax_' . $index]) ? $result[$year][$m]['pax_' . $index]: 0;
	                        	$totals += $total;
	                        ?>
	                        <td class="text-center">
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
	</div>
	<!-- 8 -->
	<div class="card">
		<div class="card-header">
			<h6 class="card-title">
				<a href="#collapse-link-req_travel" class="font-weight-semibold collapsed" data-toggle="collapse" aria-expanded="false"> <?= Yii::t('re', 'Theo hình thức đi tours') ?> </a>
			</h6>
		</div>
	    <div class="card-body collapse" id="collapse-link-req_travel">
	        <div class="table-responsive">
	            <table class="table table-striped table-narrow">
	                <thead>
	                    <tr>
	                        <th class="w-25">Chỉ số \ Tháng</th>
	                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
	                        <th class="text-center" ><?= $m ?></th>
	                        <?php } ?>
	                        <th class="text-center" >cả năm</th>
	                    </tr>
	                </thead>
	                <tbody>
	                	<?php $arr_name = [];?>
						<?php foreach ($links_req_travel as $index=>$t_link) {// ?>

						<?php
							$arr = explode('_', $index);
							if (!in_array($arr[0], $arr_name)){
								$arr_name[] = $arr[0];
						?>
						<tr class="">
	                    	<th colspan="15" style="text-transform: capitalize; background-color: #e0f7fa">
	                    	<?php
	                    		echo Yii::t('re', $arr[0]);
	                    	?>
	                        </th>
	                    </tr>
						<?php }?>
	                    <tr class="">
	                        <td class="td_head">
	                            <?= count($arr) == 1 ?Yii::t('re', 'Theo tháng nhận hồ sơ xử lý') :  Yii::t('re', 'Theo tháng kết thúc tour')?>
	                        </td>
	                        <?php $pc_canam = 0;  $total_status_won = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['req_travel_type:'.$index]['won']) ? count($totalCases_statusInMonth[$year][$m]['req_travel_type:'.$index]['won']): 0;
	                        	$total_status_won += $num_status;
	                        	$total = isset($result[$year][$m][$index]) ? $result[$year][$m][$index]: 0;
	                        	$pc_canam += $total;
	                        ?>
	                        <td class="text-center">
	                        	<?php $pc = ($total > 0)? number_format($num_status*100/$total, 0): 0; ?>
	                            <?= Html::a( $pc.' %', $t_link .'&month='.$m, ['class' => 'text-success'])?>
	                        </td>
	                        <?php } ?>
	                        <?php $total_pc = ($pc_canam > 0)? number_format($total_status_won*100/$pc_canam, 0): 0; ?>
	                        <td class="text-center text-bold"> <?= Html::a($total_pc . ' %', $t_link, ['class' => 'text-success'])?> </td>
	                    </tr>
	                    <tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#2196f3"></i> Pending </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['req_travel_type:'.$index]['pending']) ? count($totalCases_statusInMonth[$year][$m]['req_travel_type:'.$index]['pending']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=pending')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=pending')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#4caf50"></i> Won </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['req_travel_type:'.$index]['won']) ? count($totalCases_statusInMonth[$year][$m]['req_travel_type:'.$index]['won']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=won')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=won')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#f44336"></i> Lost </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['req_travel_type:'.$index]['lost']) ? count($totalCases_statusInMonth[$year][$m]['req_travel_type:'.$index]['lost']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=lost')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=lost')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#333"></i> Total </th>
							<?php $totals = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$total = isset($result[$year][$m][$index]) ? $result[$year][$m][$index]: 0;
	                        	$totals += $total;
	                        ?>
	                        <td class="text-center">
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
	</div>
	<!-- 9 -->
	<div class="card">
		<div class="card-header">
			<h6 class="card-title">
				<a href="#collapse-link-tour_end" class="font-weight-semibold collapsed" data-toggle="collapse" aria-expanded="false"> <?= Yii::t('re', 'Theo tháng đi tours (tính theo ngày tour kết thúc)') ?> </a>
			</h6>
		</div>
	    <div class="card-body collapse" id="collapse-link-tour_end">
	        <div class="table-responsive">
	            <table class="table table-striped table-narrow">
	                <thead>
	                    <tr>
	                        <th class="w-25">Tháng Giao \ Tháng kết thúc</th>
	                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
	                        <th class="text-center" ><?= $m ?></th>
	                        <?php } ?>
	                        <th class="text-center" >cả năm</th>
	                    </tr>
	                </thead>
	                <tbody>
	                	<?php $arr_name = [];?>
						<?php for ($m_ao = 1; $m_ao <= 12; $m_ao ++) { ?>

	                    <tr class="">
	                        <td class="td_head">
	                            <?= $m_ao?>
	                        </td>

	                        <?php $pc_canam = 0;  $total_status_won = 0; ?>
	                        <?php for ($m_end = 1; $m_end <= 12; $m_end ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m_ao]['tour_end_date:'.$m_end]['won']) ? count($totalCases_statusInMonth[$year][$m_ao]['tour_end_date:'.$m_end]['won']): 0;
	                        	$total_status_won += $num_status;
	                        	$total = isset($result[$year][$m_ao]['tour_end_mo_'. $m_end]) ? $result[$year][$m_ao]['tour_end_mo_'. $m_end]: 0;
	                        	$pc_canam += $total;
	                        ?>
	                         <td class="text-center">
	                        	<?php $pc = ($total > 0)? number_format($num_status*100/$total, 0): 0; ?>
	                            <?= Html::a( $pc.' %', $t_link .'&month='.$m_ao, ['class' => 'text-success'])?>
	                        </td>
	                        <?php } ?>
	                        <?php $total_pc = ($pc_canam > 0)? number_format($total_status_won*100/$pc_canam, 0): 0; ?>
	                        <td class="text-center text-bold"> <?= Html::a($total_pc . ' %', $t_link, ['class' => 'text-success'])?> </td>
	                    </tr>
	                    <tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#2196f3"></i> Pending </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m_ao]['tour_end_date:'.$m]['pending']) ? count($totalCases_statusInMonth[$year][$m_ao]['tour_end_date:'.$m]['pending']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=pending')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=pending')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#4caf50"></i> Won </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m_ao]['tour_end_date:'.$m]['won']) ? count($totalCases_statusInMonth[$year][$m_ao]['tour_end_date:'.$m]['won']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=won')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=won')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#f44336"></i> Lost </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {

	                        	$num_status = isset($totalCases_statusInMonth[$year][$m_ao]['tour_end_date:'.$m]['lost']) ? count($totalCases_statusInMonth[$year][$m_ao]['tour_end_date:'.$m]['lost']): 0;
	                        	$totals_status += $num_status;

	                        	$total_tour_end_in_mon = $totalCases_statusInMonth[$year][$m]['total_tour_end_date:'];//total_tour_end_date
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=lost')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=lost')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#333"></i> Total </th>
							<?php $totals = 0; ?>
	                        <?php for ($m_end = 1; $m_end <= 12; $m_end ++) {
	                        	$total = isset($result[$year][$m_ao]['tour_end_mo_'. $m_end]) ? $result[$year][$m_ao]['tour_end_mo_'. $m_end]: 0;
	                        	$totals += $total;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($total, $t_link .'&month='.$m_end)?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals, $t_link)?> </td>
						</tr>

	                    <?php } ?>

	                </tbody>
	            </table>
	        </div>
	    </div>
	</div>
	<!-- 10 -->
	<div class="card">
		<div class="card-header">
			<h6 class="card-title">
				<a href="#collapse-link-req_countries" class="font-weight-semibold collapsed" data-toggle="collapse" aria-expanded="false"> <?= Yii::t('re', 'Theo điểm đến') ?> </a>
			</h6>
		</div>
	    <div class="card-body collapse" id="collapse-link-req_countries">
	        <div class="table-responsive">
	            <table class="table table-striped table-narrow">
	                <thead>
	                    <tr>
	                        <th class="w-25">Chỉ số \ Tháng</th>
	                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
	                        <th class="text-center" ><?= $m ?></th>
	                        <?php } ?>
	                        <th class="text-center" >cả năm</th>
	                    </tr>
	                </thead>
	                <tbody>
	                	<?php $arr_name = [];?>
						<?php foreach ($link_destinations as $index=>$t_link) { ?>

						<?php
							$arr = explode('_', $index);
							if (!in_array($arr[0], $arr_name)){
								$arr_name[] = $arr[0];
						?>
						<tr class="">
	                    	<th colspan="15" style="text-transform: capitalize; background-color: #e0f7fa">
	                    	<?php
	                    		echo Yii::t('re', $arr[0]);
	                    	?>
	                        </th>
	                    </tr>
						<?php }?>
	                    <tr class="">
	                        <td class="td_head">
	                            <?= count($arr) == 1 ?Yii::t('re', 'Theo tháng nhận hồ sơ xử lý') :  Yii::t('re', 'Theo tháng kết thúc tour')?>
	                        </td>
	                        <?php $pc_canam = 0;  $total_status_won = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['req_countries:'.$index]['won']) ? count($totalCases_statusInMonth[$year][$m]['req_countries:'.$index]['won']): 0;
	                        	$total_status_won += $num_status;
	                        	$total = isset($result[$year][$m][$index]) ? $result[$year][$m][$index]: 0;
	                        	$pc_canam += $total;
	                        ?>
	                        <td class="text-center">
	                        	<?php $pc = ($total > 0)? number_format($num_status*100/$total, 0): 0; ?>
	                            <?= Html::a( $pc.' %', $t_link .'&month='.$m, ['class' => 'text-success'])?>
	                        </td>
	                        <?php } ?>
	                        <?php $total_pc = ($pc_canam > 0)? number_format($total_status_won*100/$pc_canam, 0): 0; ?>
	                        <td class="text-center text-bold"> <?= Html::a($total_pc . ' %', $t_link, ['class' => 'text-success'])?> </td>
	                    </tr>
	                    <tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#2196f3"></i> Pending </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['req_countries:'.$index]['pending']) ? count($totalCases_statusInMonth[$year][$m]['req_countries:'.$index]['pending']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=pending')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=pending')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#4caf50"></i> Won </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['req_countries:'.$index]['won']) ? count($totalCases_statusInMonth[$year][$m]['req_countries:'.$index]['won']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=won')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=won')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#f44336"></i> Lost </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['req_countries:'.$index]['lost']) ? count($totalCases_statusInMonth[$year][$m]['req_countries:'.$index]['lost']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=lost')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=lost')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#333"></i> Total </th>
							<?php $totals = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$total = isset($result[$year][$m][$index]) ? $result[$year][$m][$index]: 0;
	                        	$totals += $total;
	                        ?>
	                        <td class="text-center">
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
	</div>
	<!-- 11 -->
	<div class="card">
		<div class="card-header">
			<h6 class="card-title">
				<a href="#collapse-link-req_cofr" class="font-weight-semibold collapsed" data-toggle="collapse" aria-expanded="false"> <?= Yii::t('re', 'Theo tư vấn pháp') ?> </a>
			</h6>
		</div>
	    <div class="card-body collapse" id="collapse-link-req_cofr">
	        <div class="table-responsive">
	            <table class="table table-striped table-narrow">
	                <thead>
	                    <tr>
	                        <th class="w-25">Chỉ số \ Tháng</th>
	                        <?php for ($m = 1; $m <= 12; $m ++) { ?>
	                        <th class="text-center" ><?= $m ?></th>
	                        <?php } ?>
	                        <th class="text-center" >cả năm</th>
	                    </tr>
	                </thead>
	                <tbody>
	                	<?php $arr_name = [];?>
						<?php foreach ($link_france_ids as $index=>$t_link) { ?>

						<?php
							$arr = explode('_', $index);
							if (!in_array($arr[0], $arr_name)){
								$arr_name[] = $arr[0];
						?>
						<tr class="">
	                    	<th colspan="15" style="text-transform: capitalize; background-color: #e0f7fa">
	                    	<?php
	                    		echo $cofrList[$arr[0]]['nickname'];
	                    	?>
	                        </th>
	                    </tr>
						<?php }?>
	                    <tr class="">
	                        <td class="td_head">
	                            <?= count($arr) == 1 ?Yii::t('re', 'Theo tháng nhận hồ sơ xử lý') :  Yii::t('re', 'Theo tháng kết thúc tour')?>
	                        </td>
	                        <?php $pc_canam = 0;  $total_status_won = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['cofr:'.$index]['won']) ? count($totalCases_statusInMonth[$year][$m]['cofr:'.$index]['won']): 0;
	                        	$total_status_won += $num_status;
	                        	$total = isset($result[$year][$m][$index]) ? $result[$year][$m][$index]: 0;
	                        	$pc_canam += $total;
	                        ?>
	                        <td class="text-center">
	                        	<?php $pc = ($total > 0)? number_format($num_status*100/$total, 0): 0; ?>
	                            <?= Html::a( $pc.' %', $t_link .'&month='.$m, ['class' => 'text-success'])?>
	                        </td>
	                        <?php } ?>
	                        <?php $total_pc = ($pc_canam > 0)? number_format($total_status_won*100/$pc_canam, 0): 0; ?>
	                        <td class="text-center text-bold"> <?= Html::a($total_pc . ' %', $t_link, ['class' => 'text-success'])?> </td>
	                    </tr>
	                    <tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#2196f3"></i> Pending </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['cofr:'.$index]['pending']) ? count($totalCases_statusInMonth[$year][$m]['cofr:'.$index]['pending']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=pending')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=pending')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#4caf50"></i> Won </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['cofr:'.$index]['won']) ? count($totalCases_statusInMonth[$year][$m]['cofr:'.$index]['won']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=won')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=won')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#f44336"></i> Lost </th>
							<?php $totals_status = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$num_status = isset($totalCases_statusInMonth[$year][$m]['cofr:'.$index]['lost']) ? count($totalCases_statusInMonth[$year][$m]['cofr:'.$index]['lost']): 0;
	                        	$totals_status += $num_status;
	                        ?>
	                        <td class="text-center">
	                            <?= Html::a($num_status, $t_link .'&month=' . $m . '&status=lost')?>
	                        </td>
	                        <?php } ?>
	                        <td class="text-center text-bold"> <?= Html::a($totals_status, $t_link . '&status=lost')?> </td>
						</tr>
						<tr class="togglable">
							<th> <i class="fa fa-square position-left" style="color:#333"></i> Total </th>
							<?php $totals = 0; ?>
	                        <?php for ($m = 1; $m <= 12; $m ++) {
	                        	$total = isset($result[$year][$m][$index]) ? $result[$year][$m][$index]: 0;
	                        	$totals += $total;
	                        ?>
	                        <td class="text-center">
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
	</div>
</div>
<?php
$js = <<<'TXT'
$('.td_head').on('click', function(){
	$('.togglable').fadeToggle( "slow", function(){
		return false;
	});

});
TXT;
$this->registerJs($js);
?>
