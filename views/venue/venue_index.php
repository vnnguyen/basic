<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
// use yii\widgets\LinkPager;
use app\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;

include('_venue_inc.php');

Yii::$app->params['page_layout'] = '-t';
Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_title'] = 'Hotels & Homestays';
Yii::$app->params['page_breadcrumbs'] = [
    ['Hotels & Homestays', 'venues'],
];
$this->params['actions'] = [
    [
        ['icon'=>'plus', 'label'=>'Mới', 'link'=>'venues/c', 'active'=>SEG2 == 'c'],
    ],
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

$venueStraList = [
    ''=>'All hotels',
    's'=>'Strategic hotels',
    'r'=>'Recommended hotels',
    'sr'=>'Strategic & Recommended',
    'h'=>'Homestays',
];

function url_to_domain($url)
{
    $host = @parse_url($url, PHP_URL_HOST);
    // If the URL can't be parsed, use the original URL
    // Change to "return false" if you don't want that
    if (!$host)
        $host = $url;
    // The "www." prefix isn't really needed if you're just using
    // this to display the domain to the user
    if (substr($host, 0, 4) == "www.")
        $host = substr($host, 4);
    // You might also want to limit the length if screen space is limited
    if (strlen($host) > 50)
        $host = substr($host, 0, 47) . '...';
    return $host;
}

?>
<div class="col-md-12">
    <form method="get" action="" class="form-inline mb-1em">
        <?= Html::dropDownList('stra', $stra, $venueStraList, ['class'=>'form-control']) ?>
        <?= Html::dropdownList('dest', $dest, ArrayHelper::map($allDestinations, 'id', 'name_en'), ['class'=>'form-control', 'prompt'=>'All destinations']) ?>
        <?= Html::dropdownList('type', $type, $venueTypeList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '- Type -')]) ?>
        <?= Html::dropdownList('class', $class, $venueClassiList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '- Hạng -')]) ?>
        <?= Html::dropdownList('style', $style, $venueStyleList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '- Kiểu -')]) ?>
        <?= Html::textInput('price', $price, ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Price, eg. 45 or 45-55')]) ?>
        <?= Html::dropdownList('faci', $faci, $venueFaciList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '- Tiện nghi -')]) ?>
        <?= Html::dropdownList('recc', $recc, $venueReccList, ['class'=>'form-control', 'prompt'=>Yii::t('x', '- Khuyên dùng cho -')]) ?>
        <?= Html::textInput('search', $search, ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Tags')]) ?>
        <?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Name')]) ?>
        <?= Html::submitButton(Yii::t('x', 'Go'), ['class'=>'btn btn-primary']) ?>
        <?= Html::a(Yii::t('x', 'Reset'), '/venues?stra=sr') ?>
    </form>
    <?php if (empty($theVenues)) { ?><p><?= Yii::t('x', 'No data found.') ?></p><?php } else { ?>
    <div class="table-responsive panel panel-body no-padding">
        <table class="table table-bordered table-narrow table-striped">
            <thead>
                <tr>
                    <th width="15"></th>
                    <th colspan="2">Name</th>
                    <?php if ($dest == '') { ?>
                    <th width="">Địa điểm</th>
                    <?php } ?>
                    <th width="80" class="text-center">Class</th>
                    <th width="50" class="text-center">Price</th>
                    <th>Tags</th>
                    <th><?= Yii::t('x', 'Contract') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($theVenues as $venue) {
                    $newTags = explode(';|', $venue['new_tags']);

                    $tags = explode(' ', $venue['search']);

                    // Stars
                    $venueStar = '';
                    $venueRates = [];
                    $venueTags = [];
                    $venueContracts = [];
                    $venueTripAdv = '';
                    $venueLocations = [];


                    // Rates
                    foreach ($newTags as $tag) {
                        if (in_array($tag, ['s_1s', 's_2s', 's_3s', 's_4s', 's_5s'])) {
                            $venueStar = substr($tag, 2, 1);
                        }
                    }
                    foreach ($tags as $tag) {
                        if (substr($tag, 0, '2') == 'rf') {
                            $venueRates[] = substr($tag, 2);
                        } elseif (substr($tag, 0, '2') == 'hd') {
                            $venueContracts[] = (int)substr($tag, 2) >= (int)date('Y') ? '<span style="color:blue;">'.substr($tag, 2).'</span>' : substr($tag, 2);
                        } elseif (substr($tag, 0, '2') == 'tr' && $tag != 'trekking') {
                            $venueTripAdv = substr($tag, 2);
                        } else {
                            if ($tag == 'charm') {
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
                            if (str_replace('_', '', fURL::makeFriendly($venue['name'], '_')) == $tag) $tag = '';
                            if (trim($tag) != '')
                                $venueTags[] = $tag;
                        }
                    }

                    foreach ($newTags as $tag) {
                        $tag = '';
                        if (in_array($tag, ['1s', '2s', '3s', '4s', '5s'])) {
                            $venueStar = substr($tag, 0, 1);
                        } elseif (substr($tag, 0, '2') == 'tr' && $tag != 'trekking') {
                            $venueTripAdv = substr($tag, 2);
                        } elseif ($tag == 'sr_s') {
                            $tag = '<span class="text-pink">strategic</span>';
                        } elseif ($tag == 'sr_r') {
                            $tag = '<span style="color:green">recommended+</span>';
                        }
                        if ($tag != '') {
                            $venueTags[] = $tag;
                        }
                    }

                    if (strpos($venue['new_tags'], 'sr_s') !== false) {
                        $venueTags[] = '<span class="text-pink">strategic</span>';
                    }
                    if (strpos($venue['new_tags'], 'sr_r') !== false) {
                        $venueTags[] = '<span style="color:green">recommended</span>';
                    }
                    ?>
                <tr>
                    <td><?= Html::a('<i class="fa fa-edit"></i>', '/venues/u/'.$venue['id'], ['class'=>'text-muted']) ?></td>
                    <td class="no-padding-right no-border-right" width="90">
                        <?php
                        if ($venue['image'] == '') {
                            if ($venue['images'] != '') {
                                if (substr($venue['images'], 0, 1) != '<') {
                                    $venue['image'] = explode(';|', $venue['images'])[0];
                                } else {
                                    $venue['images'] = str_replace(['<img src="', '">'], [';|'], $venue['images']);
                                    $venue['image'] = explode(';|', $venue['images'])[0];
                                    $venue['image'] = '/assets/img/placeholder.jpg';
                                }
                            } else {
                                $venue['image'] = '/assets/img/placeholder.jpg';
                            }
                        }
                        ?>
                        <a data-fancybox="<?= $venue['id'] ?>" href="<?= $venue['image'] ?>"><div style="height:60px; width:90px; background:url(<?= $venue['image'] ?>) center center no-repeat; background-size:cover;"></div></a>
                    </td>
                    <td class="text-nowrap no-padding-left no-border-left">
                        <?= Html::a($venue['name'], '/venues/r/'.$venue['id'], ['class'=>'text-bold text-black']) ?>
                        <?php if ($venue['stype'] == 'home') { ?><span class="label-info label">Homestay</span><?php } ?>
                        <?= (int)$venueStar == 0 ? '' : str_repeat('<i class="small fa fa-star text-warning"></i>', $venueStar) ?>
                        <?php if ($venue['about'] != '') { ?><span class="small">(formerly <em><?= $venue['about'] ?></em>)</span><?php } ?>
                        <?php
                        foreach ($venue['metas'] as $meta) {
                            if ($meta['name'] == 'address') { ?>
                        <div class="small"><i class="text-muted fa fa-home"></i> <?= $meta['value'] ?></div><?php
                                break;
                            }
                        }
                        ?>
                        <div class="small"><?php
                        foreach ($venue['metas'] as $meta) {
                            if ($meta['name'] == 'tel' || $meta['name'] == 'mobile') { ?>
                        <i class="text-muted fa fa-phone"></i> <?= $meta['value'] ?><?php
                                break;
                            }
                        }

                        foreach ($venue['metas'] as $meta) {
                            if ($meta['name'] == 'website') {
                                echo ' <i class="fa fa-globe"></i> ', Html::a(url_to_domain($meta['value']), 'http://'.str_replace('http://', '', $meta['value']), ['title'=>'Website', 'rel'=>'external']);
                                break;
                            }
                        }

                        ?>
                        </div>
                    </td>
                    <?php if ($dest == '') { ?>
                    <td><?= $venue['destination']['name_vi'] ?></td>
                    <?php } ?>
                    <td class="text-center">
                        <?php
                        foreach ($newTags as $newTag) { ?>
                            <?php if (array_key_exists($newTag, $venueClassiList)) { ?>
                            <?= $venueClassiList[$newTag] ?>
                            <?php break; } ?>
                        <?php } ?>
                    </td>
                    <td class="text-center">
                        <?php
                        if ($venue['new_pricemin'] != 0) {
                            echo $venue['new_pricemin'];
                        }
                        if ($venue['new_pricemax'] != 0 && $venue['new_pricemax'] > $venue['new_pricemin']) {
                            echo ' - ', $venue['new_pricemax'];
                        }
                        ?>
                    </td>
                    <td><?= implode(', ', $venueTags) ?></td>
                    <td class="text-center"><?=implode(', ', $venueContracts)?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php if ($pagination->totalCount > $pagination->pageSize) { ?>
    <div class="text-center">
    <?= LinkPager::widget([
        'pagination' => $pagination,
        'firstPageLabel' => '<<',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '>>',
    ]);
    ?>
    </div>
    <?php } ?>
    <?php } ?>
</div>
<?php
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.js', ['depends'=>'yii\web\JqueryAsset']);
// $this->registerJs($js);
