<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
Yii::$app->params['body_class'] = 'sidebar-xs';
$this->title = 'Total feedback';
// var_dump($result);die;
?>
<div class="col-md-12">
	<div class="panel">
		<p><strong>MONTH VIEW</strong></p>
		<ul class="nav nav-tabs mb-1em" data-tabs="tabs" id="btn-group">
			<? for ($yr = $minYear; $yr <= $maxYear; $yr ++) { ?>
			<li class="<?= $yr == date('Y') ? 'active' : ''?>"><a data-toggle="tab" href="#year<?= $yr ?>"><?= $yr ?></a></li>
			<? } ?>
		</ul>
		<div id="tab-content" class="tab-content">
			<? for ($yr = $minYear; $yr <= $maxYear; $yr ++) { ?>
			<div id="year<?= $yr ?>" class="<?= $yr == date('Y') ? 'active' : '' ?> tab-pane">
				<table class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th class="text-center" colspan="2"></th>
							<? for ($mo = 1; $mo <= 12; $mo ++) { ?>
							<th class="text-center"><?= $mo ?></th>
							<? } ?>
							<th class="text-center">Total</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($version['questions'] as $q => $q_content):
							$cnt = count($q_content['options']);
							$category = '';
							if ($q == 'q1') {
								$category = 'Services';
							}
							if ($q == 'q2') {
								$category = 'Guide';
							}
							if ($q == 'q3') {
								$category = 'Chauffeur';
							}
							if ($q == 'q4') {
								$category = 'Général';
							}
						?>
							<?php foreach ($q_content['options'] as $op_k => $op_name):?>
							<tr>
								<?php if ($cnt == count($q_content['options'])):?>
									<th class="text-center" rowspan="<?=$cnt?>"><strong><?= $category?></strong></th>
								<?php $cnt--; endif ?>
								<td class="text-center" width="25%"><?= $op_name?></td>
								<? for ($mo = 1; $mo <= 12; $mo ++) {?>
									<?
										$total = [];
										$val = 0;
										$response_text = '--';
										if (isset($result[$yr][$mo], $result[$yr][$mo][$q], $result[$yr][$mo][$q][$op_k])) {
											$sum = $result[$yr][$mo][$q][$op_k];
											$options_value = $q_content['v_op_v'][$op_k];
											for ($i=0; $i < count($options_value); $i++) {
												if ($sum > $options_value[$i] + 5) {
													continue;
												}
												if (!isset($options_value[$i+1])) {
													$options_value[$i+1] = $options_value[$i];
												}
												if ($sum >= ($options_value[$i] + $options_value[$i+1])/2) {
													$val = $options_value[$i+1];
													$response_text = $q_content['options_value'][$i+1];
												}
												if ($sum < ($options_value[$i] + $options_value[$i+1])/2) {
													$val = $options_value[$i];
													$response_text = $q_content['options_value'][$i];
												}
												if ($val == 0) {
													$val = $options_value[$i];
													$response_text = $q_content['options_value'][$i];
												}
												break;
											}
										}
										$total[] = $val;
									?>
									<td class="text-center"><span class=""><small><?= $response_text?></small></span></td>
								<?
									// if (!isset($result[$yr][$mo])) continue;
								?>
								<?}?>
								<td>0</td>
							</tr>
							<?php endforeach ?>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
			<? } ?>
		</div>
	</div>
</div>