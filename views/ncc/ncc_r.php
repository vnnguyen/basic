<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'gắn khách sạn với nhà cung cấp';
$this->params['icon'] = 'home';
$this->params['breadcrumb'] = [
	['NCC', 'v2ncc'],
];

?>
<div class="col-lg-12">
	<form method="post" action="" class="form-vertical">
		<p>Tên khách sạn:<br><strong><?=$model['name']?></strong></p>
		<p>Tên nhà cung cấp (dữ liệu từ kế toán):<br>
			<select class="form-control" name="ncc_id">
				<option value="0">- Select -</option>
				<? foreach ($nccx as $li) { ?>
				<option value="<?=$li['id']?>"><?=trim($li['code_kt'])?> | <?=trim(Html::encode($li['ten']))?> | <?=trim(Html::encode($li['dc']))?></option>
				<? } ?>
			</select>
		</p>
		<p><button type="submit" class="btn btn-primary">Save changes</button></p>
	</form>
</div>