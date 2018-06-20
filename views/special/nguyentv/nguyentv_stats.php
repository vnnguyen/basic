<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;


Yii::$app->params['page_title'] = 'Venues';
Yii::$app->params['page_breadcrumbs'] = [
    ['Venues', 'venues'],
];

$typeList = [
    'all'=>'All types',
    'hotel'=>'Hotels',
    'home'=>'Local homes',
    'cruise'=>'Cruise vessels',
    'restaurant'=>'Restaurants',
    'sightseeing'=>'Sightseeing spots',
    'train'=>'Night trains',
    'other'=>'Other',
];

$statusList = [
    'all'=>'All status',
    'on'=>'On',
    'off'=>'Off',
    'draft'=>'Draft',
    'deleted'=>'Deleted',
];
?>
<style type="text/css">
tr.ok td {background-color:#ffffcc;}
</style>
<div class="col-md-12">
    <? if (empty($theVenues)) { ?>
    <p>No data found</p>
    <? } else { ?>
    <div class="table-responsive">
        <table id="tbl-startme" class="table table-bordered table-condensed table-striped">
            <thead>
                <tr>
                    <th width="30" rowspan="2">ID</th>
                    <th width="140" rowspan="2">Địa điểm</th>
                    <th width="" rowspan="2">Name</th>
                    <th width="150" colspan="3" class="text-center">2015</th>
                    <th width="150" colspan="3" class="text-center">2016</th>
                </tr>
                <tr>
                    <th>Tour</th>
                    <th>Pax</th>
                    <th>RN</th>
                    <th>Tour</th>
                    <th>Pax</th>
                    <th>RN</th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($theVenues as $venue) { ?>
                <tr class="nok startme">
                    <td class="text-muted text-center"><?= $venue['id'] ?></td>
                    <td><?= $venue['destination']['name_vi'] ?></td>
                    <td>
                        <? if ($venue['stype'] == 'home') { ?><i class="text-pink fa fa-home"></i><? } ?>
                        <? if ($venue['stype'] == 'hotel') { ?><i class="fa fa-hotel"></i><? } ?>
                        <?= Html::a($venue['name'], '/tools/tour-ks?ks='.$venue['id'], ['class'=>'startme', 'data-id'=>$venue['id'], 'target'=>'_blank'])?>
                    </td>
                    <td class="text-center"><?= $venue['stats']['t2015'] ?></td>
                    <td class="text-center"><?= $venue['stats']['p2015'] ?></td>
                    <td class="text-center"><?= $venue['stats']['rn2015'] ?></td>
                    <td class="text-center"><?= $venue['stats']['t2016'] ?></td>
                    <td class="text-center"><?= $venue['stats']['p2016'] ?></td>
                    <td class="text-center"><?= $venue['stats']['rn2016'] ?></td>
                </tr>
                <? } ?>
            </tbody>
        </table>
    </div>
    <? } ?>
</div>
<?
$js = <<<'TXT'
$('tr.nok a.startme').on('click', function(){
    var tr = $(this).parent().parent();
    var id = $(this).data('id');
    $.ajax({
        type: 'POST',
        url: '/special/nguyentv/ajax?xh',
        data: {
            id: id,
            yr: {yr}
        }
    })
    .done(function(data){
        tr.addClass('ok').removeClass('nok');
        tr.find('td:eq(3)').html(data.t2015);
        tr.find('td:eq(4)').html(data.p2015);
        tr.find('td:eq(5)').html(data.rn2015);
        tr.find('td:eq(6)').html(data.t2016);
        tr.find('td:eq(7)').html(data.p2016);
        tr.find('td:eq(8)').html(data.rn2016);
        // window.scrollTo(0, $('tr.nok:eq(0)').offset().top - 50);
        $('tr.nok:eq(0)').find('a.startme').trigger('click');
    })
    .fail(function(){
        alert('Operation failed!');
    });
    return false;
});
TXT;

if (USER_ID == 1 && isset($_GET['xh'])) {
    $this->registerJs(str_replace(['{yr}'], [$yr], $js));
}
