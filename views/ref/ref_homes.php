<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;

Yii::$app->params['page_title'] = 'Nhà dân';
Yii::$app->params['page_small_title'] = 'Local homes';

Yii::$app->params['page_icon'] = 'home';
Yii::$app->params['page_breadcrumbs'] = [
    ['Ref', 'ref'],
    ['Local homes'],
];
?>
<style>
select[size] {height:auto!important;}
</style>
<div class="col-md-12">
    <div class="table-responsive">
        <table id="tblHotels" class="table dataTable">
            <thead>
                <tr>
                    <th width="">Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Tags</th>
                    <th width="40"></th>
                </tr>
            </thead>
            <tbody>
                <? if (empty($theVenues)) { ?><tr><td colspan="7">No items found.</td></tr><? } ?>
                <? foreach ($theVenues as $li) {
                    $tags = explode(' ', $li['search']);

                    // Stars
                    $venueStar = '';
                    $venueRates = [];
                    $venueTags = [];
                    $venueContracts = [];
                    $venueTripAdv = '';
                    $venueLocations = [];

                    // Rates
                    foreach ($tags as $tag) {
                        if (in_array($tag, ['1s', '2s', '3s', '4s', '5s'])) {
                            $venueStar = substr($tag, 0, 1);
                        } elseif (substr($tag, 0, '2') == 'rf') {
                            $venueRates[] = substr($tag, 2);
                        } elseif (substr($tag, 0, '2') == 'hd') {
                            $venueContracts[] = substr($tag, 2) == '2014' ? '<span style="color:blue;">'.substr($tag, 2).'</span>' : substr($tag, 2);
                        } elseif (substr($tag, 0, '2') == 'tr' && $tag != 'trekking') {
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
                                $tag = '<s style="color:red">not OK</s>';
                            } elseif ($tag == 'see') {
                                $tag = 'đợi khảo sát';
                            } elseif ($tag == 'far') {
                                $tag = 'xa trung tâm';
                            }

                            if (substr($tag, 0, 1) == '@') $tag = '';
                            if ($tag == 're' || $tag == 'ks') $tag = '';
                            if (str_replace('_', '', fURL::makeFriendly($li['name'], '_')) == $tag) $tag = '';
                            if (trim($tag) != '')
                                $venueTags[] = $tag;
                        }
                    }

                    ?>
                <tr>
                    <td>
                        <?=Html::a($li['name'], '@web/venues/r/'.$li['id'])?>
                        <?
                        foreach ($li['metas'] as $li2) {
                            if ($li2['k'] == 'website') {
                                echo Html::a('<i class="fa fa-external-link"></i>', 'http://'.str_replace('http://', '', $li2['v']), ['title'=>$li2['v'], 'rel'=>'external']);
                                break;
                            }
                        }
                        ?>
                    </td>
                    <td><?
                    foreach ($li['metas'] as $li2) {
                        if ($li2['k'] == 'address') {
                            echo $li2['v'];
                            break;
                        }
                    }
                    ?></td>
                    <td><?
                    foreach ($li['metas'] as $li2) {
                        if ($li2['k'] == 'tel' || $li2['k'] == 'mobile') {
                            echo $li2['v'];
                            if ($li2['x'] != '') echo ' <em>', $li2['x'], '</em>';
                            break;
                        }
                    }
                    ?></td>
                    <td><?=implode(', ', $venueTags)?></td>
                    <td>
                        <a title="<?=Yii::t('mn', 'Edit')?>" class="text-muted" href="<?=DIR?>venues/u/<?=$li['id']?>"><i class="fa fa-edit"></i></a>
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
        "sDom": "<'dt_header row'<'col-sm-6'l><'text-right col-sm-6'f>r>t<'dt_footer row'<'col-sm-6'i><'text-right col-sm-6'p>>",
        //"sPaginationType": "bootstrap",
        "bStateSave": true,
        "aoColumns": [
            null,
            null,
            null,
            null,
            {"bSortable": false}
        ]
    });
TXT;
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/js/jquery.dataTables.min.js', ['depends'=>'yii\web\JqueryAsset']);
//$this->registerJsFile(DIR.'assets/js/datatables/paging-b3.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);
