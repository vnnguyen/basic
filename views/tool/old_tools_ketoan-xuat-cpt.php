<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Xuất chi phí tour cho kế toán ('.number_format($pagination->totalCount).' dòng)';
$this->params['breadcrumb'] = [
	['Tools', '@web/tools']
];
//$date = date('d/m/Y', strtotime($theProduct['day_from']));

$xrate['EUR'] = isset($_GET['eur']) && (int)$_GET['eur'] != 0 ? (int)$_GET['eur'] : 26000;
$xrate['USD'] = isset($_GET['usd']) && (int)$_GET['usd'] != 0 ? (int)$_GET['usd'] : 21380;
$xrate['VND'] = 1;
?>
<div class="col-md-12">
	<form class="form-inline well well-sm">
		Kiểu xem / Tour (code) hoặc tháng (yyyy-mm) / tỉ giá EUR / USD / VND. Chỉ hiển thị tối đa 4,000 dòng.<br>
		<?= Html::dropdownList('view', $view, ['tour'=>'Tour', 'month'=>'Month'], ['class'=>'form-control']) ?>
		<?= Html::textInput('search', $search, ['class'=>'form-control']) ?>
		<?= Html::textInput('eur', $xrate['EUR'], ['class'=>'form-control', 'style'=>'width:100px']) ?>
		<?= Html::textInput('usd', $xrate['USD'], ['class'=>'form-control', 'style'=>'width:100px']) ?>
		<?= Html::dropdownList('output', $output, ['view'=>'View', 'download'=>'Download'], ['class'=>'form-control']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Sửa mã', '@web/tools/ketoan-xuat-cpt-update-code', ['rel'=>'external']) ?>
	</form>
	<table class="table table-condensed table-bordered">
		<tbody>
<?
foreach ($theTours as $tour) {
	$startDate = date('d/m/Y', strtotime($tour['day_from']));
	foreach ($theCptx as $cpt) {
		if ($tour['oid'] == $cpt['tour_id']) {
?>
			<tr>
				<td><?= $tour['op_code'] ?></td>
				<td><?= $startDate ?></td>
				<td>
<?
$text = '';
if ($cpt['payer'] == 'Amica Hà Nội') {
	if ($cpt['venue']) {
		$text = $cpt['venue']['name'];
	} elseif ($cpt['company']) {
		$text = $cpt['company']['name'];
	} else {
		$text = $cpt['oppr'];
	}
} else {
	$text = $cpt['payer'];
}
$name = '';
if (isset($list[$text])) {
	$name = $list[$text]['code'];
	echo mb_strtoupper($name);
} else {
	echo Html::a($text, '@web/tools/ketoan-xuat-cpt-update-code?name='.urlencode($text), ['rel'=>'external']);
}

?>
				</td>
				<td><?= $cpt['dvtour_name'] ?> <?= trim(trim($cpt['qty']), '.00') ?> <?= $cpt['unit'] ?></td>
				<td><?= $tour['op_code'] ?></td>
				<td><?= isset($list[$tour['cbname']]) ? $list[$tour['cbname']]['code'] : Html::a($tour['cbname'], '@web/tools/ketoan-xuat-cpt-update-code?name='.urlencode($tour['cbname']), ['rel'=>'external']) ?></td>
				<td>
<?
// Check code vs name
$code = '02';

if (isset($list[$text])) {
	$code = $list[$text]['cost'];
}

foreach ($list as $item) {
	if ($item['code'] == '') {
		if (strpos(strtolower($cpt['dvtour_name']), $item['name']) !== false) {
			$code = $item['cost'];
		}
	}
}

echo $code;
?>
				</td>
				<td>1541</td>
				<td>
<?
$code = '3311';
if (in_array($name, ['BUNTHOL', 'ERIC', 'FEUANG', 'ITRAVEL', 'MEDSANH', 'PHMINH', 'THONGLIS', 'VISA'])) {
	$code = '3312';
}
if (in_array($name, ['TCGARDEN'])) {
	$code = '336';
}
echo $code;
?>
				</td>
				<td class="text-right text-nowrap">
					<?= $cpt['plusminus'] == 'minus' ? '-' : '' ?><?= number_format($cpt['qty'] * $cpt['price'] * $xrate[$cpt['unitc']], 0) ?>
				</td>
				<td>
					V:<?= $cpt['venue']['name'] ?> 
					C:<?= $cpt['company']['name'] ?> 
					O:<?= $cpt['oppr'] ?> 
					P:<?= $cpt['payer'] ?>
				</td>
			</tr>
<?
		}
	}
}
?>
		</tbody>
	</table>

	<? if ($pagination->pageSize < $pagination->totalCount) { ?>
	<div class="text-center">
	<?= LinkPager::widget([
		'pagination' => $pagination,
		'firstPageLabel' => '<<',
		'prevPageLabel' => '<',
		'nextPageLabel' => '>',
		'lastPageLabel' => '>>',
	]) ?>
	</div>
	<? } ?>
</div>
