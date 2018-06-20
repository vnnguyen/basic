<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_cp_inc.php');

if ($theCp->isNewRecord) {
	$this->title = 'Thêm chi phí dịch vụ';
} else {
	$this->title = 'Sửa: '.$theCp['name'];
	$this->params['breadcrumb'][] = ['Xem', '@web/cp/r/'.$theCp['id']];
}

?>
<div class="col-lg-8">
	<div class="row">
		<div class="col-lg-6">
			<label>Company</label>
			<p class="form-control-static well well-sm"><?= $theCompany ? Html::a($theCompany['name'], 'companies/r/'.$theCompany['id'], ['rel'=>'external']) : '(No company)' ?></p>
		</div>
		<div class="col-lg-6">
			<label>Venue</label>
			<p class="form-control-static well well-sm"><?= $theVenue ? Html::a($theVenue['name'], 'venues/r/'.$theVenue['id'], ['rel'=>'external']).' '.$theVenue['abbr'] : '(No venue)' ?></p>
		</div>
	</div>
	<? $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-lg-4">
			<?=$form->field($theCp, 'stype')->dropdownList($cpTypeList, ['prompt'=>'- Select type -']) ?>
		</div>
		<div class="col-lg-4">
			<?=$form->field($theCp, 'grouping'); ?>
		</div>
		<div class="col-lg-4">
			<?=$form->field($theCp, 'name'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-2">
			<?=$form->field($theCp, 'total'); ?>
		</div>
		<div class="col-lg-2">
			<?=$form->field($theCp, 'unit') ?>
		</div>
		<div class="col-lg-4">
			<?=$form->field($theCp, 'abbr'); ?>
		</div>
		<div class="col-lg-4">
			<?=$form->field($theCp, 'search'); ?>
		</div>
	</div>
	<?=$form->field($theCp, 'info')->textArea(['rows'=>4]); ?>
	<?=$form->field($theCp, 'variants')->textArea(['rows'=>4]); ?>

	<div class="text-right"><?= Html::submitButton(Yii::t('mn', 'Submit'), ['class' => 'btn btn-primary']) ?></div>
	<? ActiveForm::end(); ?>
</div>
<div class="col-lg-4">
	<p><strong>CÁC CHI PHÍ HIỆN CÓ</strong></p>
	<table class="table table-condensed table-striped table-bordered">
		<thead>
			<tr>
				<th>Type</th>
				<th>Name</th>
				<th>Unit</th>
				<th>Abbr</th>
			</tr>
		</thead>
		<tbody>
			<? $currentGroup = ''; foreach ($relatedCpx as $cp) { ?>
			<? if ($currentGroup != $cp['grouping']) { $currentGroup = $cp['grouping']; ?>
			<tr><th colspan="4"><?= $currentGroup ?></th></tr>
			<? } ?>
			<tr>
				<td class="text-muted"><?= $cp['stype'] ?></td>
				<td><?= Html::a($cp['name'], 'cp/r/'.$cp['id'], ['rel'=>'external']) ?></td>
				<td><?= $cp['unit'] ?></td>
				<td><?= $cp['abbr'] ?></td>
			</tr>
			<? } ?>
		</tbody>
	</table>
</div>
