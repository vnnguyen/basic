<?
//myID == 1 || die('HUAN');
include('_day_inc.php');
/*
$getType = fRequest::getValid('rtype', array('all', 'sample', 'ctr', 'tour'));
$getName = fRequest::get('name', 'string', '', true);
$getTag = fRequest::get('tag', 'string', '', true);
$getPage = fRequest::get('page', 'integer', 1, true);

$whereType = ' AND rid'.$getType == 'sample' ? '=0' : '!=0'; if ($getType == 'all') $whereType = '';
$whereName = ' AND LOCATE("'.$getName.'", name)!=0'; if ($getName == '') $whereName = '';
$whereTag = ' AND LOCATE("'.$getTag.'", note)!=0'; if ($getTag == '') $whereTag = '';

// Pages
$q = $db->query('SELECT COUNT(*) FROM at_days WHERE 1=1 '.$whereType.$whereName.$whereTag);
$pg = new hxPagination($q->fetchScalar(), '?rtype='.$getType.'&name='.$getName.'&tag='.$getTag.'&page=', $getPage, 20, 3);

// Get cases
$q = $db->query('SELECT * FROM at_days WHERE 1=1 '.$whereType.$whereName.$whereTag.' LIMIT '.$pg->limitFrom.', '.$pg->perPage);
$theDays = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

$metaT = 'Ngày tour ('.$pg->itemCount.')';
$pageM = 'ct';
$pageB = array(
	anchor('days', 'Ngày tour'),
	// anchor('days/r/'.$theDay['id'], $theDay['name']),
	);
include('__hd.php'); */
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Tour days ('.$pages->totalCount.')';
$this->params['icon'] = 'flag';
$this->params['breadcrumb'] = [
	['Tour days', 'days'],
];
?>
<div class="col-lg-12">
	<div class="table-responsive">
		<table id="tbl-days" class="table table-bordered table-striped table-condensed">
			<thead>
				<tr>
					<th class="ta-c">Loại</th>
					<th>Tên, Nội dung, Ghi chú, Tag</th>
					<th>From</th>
					<th>To</th>
					<th width="60"></th>
				</tr>
			</thead>
			<tbody>
			<? foreach ($theDays as $day) { ?>
			<tr>
				<td class="text-center text-muted"><?=$day['rid'] == 0 ? 'SAMPLE' : Html::a('PRODUCT', 'ct/r/'.$day['rid'])?></td>
				<td><?=Html::a($day['name'], 'days/r/'.$day['id'], ['class'=>'fw-b'])?> <span class="muted"><?= \fUTF8::sub($day['body'], 0, 100)?>...</span>
					<? if ($day['note'] != '') { ?> <i class="icon-tag"></i> <?=substr($day['note'], 0, 100)?><? } ?>
				</td>
				<td>Dest</td>
				<td>Name</td>
				<td>
					<a title="Copy" class="text-muted td-n" href="<?=DIR?>days/copy/<?=$day['id']?>"><i class="fa fa-copy"></i></a>
					<a title="Sửa" class="text-muted td-n" href="<?=DIR?>days/u/<?=$day['id']?>"><i class="fa fa-edit"></i></a>
					<a title="Xoá" class="text-muted td-n" href="<?=DIR?>days/d/<?=$day['id']?>"><i class="fa fa-trash-o"></i></a>
				</td>
			</tr>
			<? } ?>
			</tbody>
		</table>
	</div>
	<? if ($pages->totalCount > $pages->pageSize) { ?>
	<div class="text-center">
	<?= LinkPager::widget(array(
		'pagination' => $pages,
		'prevPageLabel'=>'<',
		'nextPageLabel'=>'>',
		'firstPageLabel'=>'<<',
		'lastPageLabel'=>'>>',
	));?>
	</div>
	<? } ?>
</div>

