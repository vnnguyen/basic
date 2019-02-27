<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$baseUrl = Yii::$app->request->baseUrl;
$this->registerJsfile('/js/pages/form_inputs.js', ['depends' => \yii\web\JqueryAsset::className()]);
?>



<dv class="panel panel-flat">
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
		<div class="col-md-8 ">
	          <?= Html::input('file', 'import', '', ['class' => 'file-styled-primary']);?>
	    </div>
	    <div class="col-md-1">
	    	<?= Html::submitButton('Submit', ['class' => 'form-control submit']) ?>
	    </div>
	<?php ActiveForm::end(); ?>
	<div class="tabel-responsive">
		<table class="table">
			<caption>table title and/or explanatory text</caption>
			<thead>
				<tr>
					<th>A</th>
					<th>B</th>
					<th>C</th>
					<th>D</th>
					<th>E</th>
				</tr>
			</thead>
			<tbody>
			<?php if (count($arr) > 0) {
				foreach ($arr as $k => $v) {
					if ($k == 1) continue; ?>
				<tr>
					<td><?= $v['A']?></td>
					<td><?= $v['B']?></td>
					<td><?= $v['C']?></td>
					<td><?= $v['D']?></td>
					<td><?= $v['E']?></td>
				</tr>
			<?php } }?>
			</tbody>
		</table>
	</div>
	
</dv>
