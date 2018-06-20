<?php

Yii::$app->params['page_title'] = 'B2B Tool';
Yii::$app->params['page_breadcrumbs'] = [
	['B2B', 'b2b'],
	['Tools'],
];

?>
<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h6 class="panel-title">Mark private tours / series tours</h6>
		</div>
		<div class="panel-body">
			<form method="post" action="">
				<div class="form-group">
					<label class="control-label">Month</label>
					<?= Html::input('month', $month, ['class'=>'form-control']) ?>
				</div>
			</form>
		</div>
	</div>
</div>