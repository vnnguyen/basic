<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;

$this->title = 'Cruises';
Yii::$app->params['page_icon'] = 'anchor';
Yii::$app->params['page_small_title'] = 'Tàu ngủ đêm';
Yii::$app->params['page_breadcrumbs'] = [
	['Ref', '@web/ref'],
	['Tàu ngủ đêm'],
];

Yii::$app->params['page_class'] = 'bg-white';

?>
<div class="col-lg-12">
	<form class="form-inline well well-sm">
		<?=Html::dropDownList('destination', $destination, ArrayHelper::map($destinations, 'id', 'name_en', 'country_name'),
			[
				'class'=>'form-control',
				'prompt'=>'- Destination -',
			]) ?>
		<?= Html::textInput('search', $search, ['class'=>'form-control', 'placeholder'=>'Stars, tags, ..']) ?>
		<?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Cruiser/Boat name']) ?>
		<?= Html::submitButton('Go', ['class'=>'btn btn-default']) ?>
		<?= Html::a('Reset', '/ref/hotels') ?>
		|
		<a href="#" onclick="$('#pagehelp').toggle(); return false;">Help</a>
	</form><br>
	<div id="pagehelp" style="display:none;" class="mt-10 alert alert-info">
		Hàng trên là các trường tìm kiếm:
		<br>- Địa điểm: chọn địa điểm. Chỉ những địa điểm có khách sạn mới được liệt kê ở đây.
		<br>- Tag: chọn một hoặc nhiều tag liên quan đến: số sao (vd: 1s, 2s, 3s, 4s, 5s), năm hợp đồng (vd: 2016), được recommend (re), các tag khác (vd: fam del cla v.v). Nếu tìm nhiều tag thì cách nhau bằng dấu cách, vd "3s re 2016" có nghĩa là tìm tàu 3 sao có hợp đồng 2016 và được khuyên dùng
		<br>- Tên: tìm theo tên tàu (chỉ cần đánh một phần tên là được)
	</div>
	<div class="table-responsive">
		<table id="tblHotels" class="table table-bordered table-condensed table-striped">
			<thead>
				<tr>
					<th>Company</th>
					<th>Cruiser name</th>
					<th>Stars</th>
					<th>Cabins</th>
					<th>2D1N$</th>
					<th>Route 2D1N</th>
					<th>3D2N$</th>
					<th>Route 3D2N</th>
					<th>Port</th>
					<th>Tags</th>
					<th>Contracts</th>
					<th>TripAdv</th>
					<th width="40"></th>
				</tr>
			</thead>
			<tbody>
				<? if (empty($theVenues)) { ?><tr><td colspan="10">No items found.</td></tr><? } ?>
				<? foreach ($theVenues as $li) {
                    // var_dump($li);die;
					// Stars
					$venueStar = '';
					$venueRates = [];
					$venueCabins = '';
					$venueTags = [];
					$venueContracts = [];
					$venueTripAdv = '';
					$venueLocations = [];

					$i2d1n = '';
					$i3d2n = '';
					$r2d1n = '';
					$r3d2n = '';
					$port = '';
					$com = '';

					$meta = explode(';', $li['cruise_meta']);
					foreach ($meta as $li_meta) {
						$li2_meta = explode(':', $li_meta);
						if (is_array($li2_meta) && count($li2_meta) == 2) {
							if (trim($li2_meta[0]) == 'com') $com = trim($li2_meta[1]);
							if (trim($li2_meta[0]) == 'r2d1n') $r2d1n = trim($li2_meta[1]);
							if (trim($li2_meta[0]) == 'r3d2n') $r3d2n = trim($li2_meta[1]);
							if (trim($li2_meta[0]) == 'i2d1n') $i2d1n = trim($li2_meta[1]);
							if (trim($li2_meta[0]) == 'i3d2n') $i3d2n = trim($li2_meta[1]);
							if (trim($li2_meta[0]) == 'port') $port = trim($li2_meta[1]);
						}
					}


					$tags = explode(' ', $li['search']);



					// Rates
					foreach ($tags as $tag) {
						if (in_array($tag, ['1s', '2s', '3s', '4s', '5s'])) {
							$venueStar = substr($tag, 0, 1);
						} elseif (substr($tag, 0, 2) == 'rf') {
							$venueRates[] = substr($tag, 2).'$';
						} elseif (substr($tag, 0, 2) == 'rm') {
							$venueCabins = substr($tag, 2);
						} elseif (substr($tag, 0, 2) == 'hd') {
							$venueContracts[] = (int)substr($tag, 2) >= (int)date('Y') ? '<span style="color:blue;">'.substr($tag, 2).'</span>' : substr($tag, 2);
						} elseif (substr($tag, 0, 2) == 'tr' && $tag != 'trekking') {
							$venueTripAdv = substr($tag, 2);
						} else {
							if ($tag == 're1') {
								$tag = '<span style="color:green">recommended++</span>';
							} elseif ($tag == 're2') {
								$tag = '<span style="color:green">recommended+</span>';
							} elseif ($tag == 're') {
								$tag = '<span style="color:green">recommended</span>';
							} elseif ($tag == 'charm') {
								$tag = '<span style="color:blue">charming</span>';
							} elseif ($tag == 'not') {
								$tag = '<i style="color:red" class="fa fa-thumbs-down"></i>';
							}

							if (substr($tag, 0, 1) == '@') $tag = '';
							if ($tag == 'see') $tag = 'đang đánh giá';
							if (str_replace('_', '', fURL::makeFriendly($li['name'], '_')) == $tag) $tag = '';
							if (trim($tag) != '')
								$venueTags[] = $tag;
						}
					}

					?>
				<tr>
					<td><?=Html::a($com, '@web/suppliers/r/'.$li['id'])?></td>
					<td><?=Html::a($li['name'], '@web/venues/r/'.$li['id'])?></td>
					<td class="text-center"><?=$venueStar?></td>
					<td class="text-center"><?=str_replace('&amp;', ', ', $venueCabins)?></td>
					<td class="text-center"><?=$r2d1n?></td>
					<td><?=$i2d1n?></td>
					<td class="text-center"><?=$r3d2n?></td>
					<td><?=$i3d2n?></td>
					<td><?=$port?></td>
					<td><?= implode(', ', $venueTags) ?></td>
					<td class="text-center"><?=implode(', ', $venueContracts)?></td>
					<td class="text-center">
						<?=$venueTripAdv?>
						<? if ($li['link_tripadvisor'] != '') { ?>
						<a rel="external" href="<?=$li['link_tripadvisor']?>"><i class="fa fa-external-link"></i></a>
						<? } ?>
					</td>
					<td class="text-muted td-n">
						<a class="text-muted" title="<?=Yii::t('mn', 'Edit')?>" href="<?=DIR?>venues/u/<?=$li['id']?>"><i class="fa fa-edit"></i></a>
					</td>
				</tr>
				<? } ?>
			</tbody>
		</table>
	</div>
</div>
<?
$js = <<<TXT
	$('#tblHotels').dataTable({
		"iDisplayLength": 100,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
		"sDom": "<'dt_header row'<'col-lg-2'f><'col-lg-2'l><'col-lg-4'i><'text-right col-lg-4'p>r>t>",
		"sPaginationType": "bootstrap",
		"bStateSave": true,
		"aoColumns": [
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			{"bSortable": false}
		],
		"oLanguage": {
			"sLengthMenu": "_MENU_",
			"sSearch": "_INPUT_",
			"oPaginate": {
				"sPrevious": "",
				"sNext": ""
			},
			"sInfo": "Showing _START_ to _END_ of _TOTAL_",
			"sInfoFiltered": " - filtering from _MAX_"
		}
	});
TXT;
//$this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/jquery.dataTables.min.js', ['depends'=>'app\assets\MainAsset']);
//$this->registerJsFile(DIR.'assets/js/datatables/paging-b3.js', ['depends'=>'app\assets\MainAsset']);
//$this->registerJs($js);
