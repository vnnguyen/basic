<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
Yii::$app->params['page_title'] = 'Hotels';
Yii::$app->params['page_small_title'] = 'Khách sạn';
Yii::$app->params['page_icon'] = 'building-o';
Yii::$app->params['page_breadcrumbs'] = [
    ['Ref', 'ref'],
    ['Hotels'],
];
$typeList = [
    ''=>'All hotels',
    's'=>'Strategic hotels',
    'r'=>'Recommended hotels',
    'sr'=>'Strategic & Recommended',
];
?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <form class="form-inline">
                <?=Html::dropDownList('destination', $destination, ArrayHelper::map($destinations, 'id', 'name_en', 'country_name'),
                    [
                        'class'=>'form-control',
                        'prompt'=>'- Destination -',
                    ]) ?>
                <?= Html::dropDownList('type', $type, $typeList, ['class'=>'form-control', 'placeholder'=>'Stars, tags, ..']) ?>
                <?= Html::textInput('search', $search, ['class'=>'form-control', 'placeholder'=>'Stars, tags, ..']) ?>
                <?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Hotel name']) ?>
                <?= Html::submitButton(Yii::t('app', 'Go'), ['class'=>'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Reset'), '/ref/hotels') ?>
                |
                <a href="#" onclick="$('#pagehelp').toggle(); return false;">Help</a>
            </form>
        </div>
        <div class="panel-body" id="pagehelp" style="display:none;">
            <div class="alert alert-info">
                Hàng trên là các trường tìm kiếm:
                <br>- Địa điểm: chọn địa điểm. Chỉ những địa điểm có khách sạn mới được liệt kê ở đây.
                <br>- Tag: chọn một hoặc nhiều tag liên quan đến: số sao (vd: 1s, 2s, 3s, 4s, 5s), năm hợp đồng (vd: 2016), được recommend (re), các tag khác (vd: fam del cla v.v). Nếu tìm nhiều tag thì cách nhau bằng dấu cách, vd "3s re 2016" có nghĩa là tìm ks 3 sao có hợp đồng 2016 và được khuyên dùng
                <br>- Tên: tìm theo tên khách sạn (chỉ cần đánh một phần tên là được)
                <br>Hàng bên dưới: nơi trình bày kết quả.
                <br>- Click tên cột để sắp xếp các ks theo cột đó. Dùng Shift+Click để sort nhiều cột.
            </div>            
        </div>
        <? if (empty($theVenues)) { ?><div class="panel-body text-danger">No items found.</div>
        <? } else { ?>
        <div class="table-responsive">
            <table id="tblHotels" class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th width="">Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th width="">Star</th>
                        <th>Rates$</th>
                        <th>Tags</th>
                        <th>Contracts</th>
                    </tr>
                </thead>
                <tbody>
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
                                $venueContracts[] = (int)substr($tag, 2) >= (int)date('Y') ? '<span style="color:blue;">'.substr($tag, 2).'</span>' : substr($tag, 2);
                            } elseif (substr($tag, 0, '2') == 'tr' && $tag != 'trekking') {
                                $venueTripAdv = substr($tag, 2);
                            } else {
                                if ($tag == 're1') {
                                    $tag = '<span style="color:green">recommended++</span>';
                                } elseif ($tag == 'str') {
                                    $tag = '<span class="text-pink">strategic</span>';
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
                            <? if ($li['images_booking'] != '') { ?><i class="text-muted fa fa-picture-o"></i><? } ?>
                            <? if ($li['company_id'] != 0 && in_array(USER_ID, [1, 7766])) { ?><i class="text-muted fa fa-home"></i><? } ?>
                            <?=Html::a($li['name'], '@web/venues/r/'.$li['id'])?>
                            <?
                            foreach ($li['metas'] as $li2) {
                                if ($li2['k'] == 'website') {
                                    echo Html::a('<i class="fa fa-external-link"></i>', 'http://'.str_replace('http://', '', $li2['v']), ['title'=>$li2['v'], 'rel'=>'external', 'class'=>'text-muted']);
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
                                break;
                            }
                        }
                        ?></td>
                        <td class="text-center"><?=$venueStar?></td>
                        <td class="text-center"><?=implode(', ', $venueRates)?></td>
                        <td><?=implode(', ', $venueTags)?></td>
                        <td class="text-center"><?=implode(', ', $venueContracts)?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
        <? } ?>
    </div>
</div>
<?
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.27.8/css/theme.default.min.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.27.8/js/jquery.tablesorter.min.js', ['depends'=>'yii\web\JqueryAsset']);
$js = <<<TXT
$('#tblHotels').tablesorter({ sortList: [[5,1], [0,0]] });
TXT;
$this->registerJs($js);
