<?
use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\LinkPager;

include('_dv_inc.php');

Yii::$app->params['page_title'] = 'Dịch vụ ('.number_format($pagination->totalCount).')';
Yii::$app->params['page_icon'] = 'magic';
Yii::$app->params['page_layout'] = '-h';
Yii::$app->params['body_class'] = 'sidebar-xs';

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Tra cứu dịch vụ - chi phí</h6>
            <div class="heading-elements">
                <ul class="list-inline list-inline-separate heading-text">
                    <li><a href="/dv/c">+New</a></li>
                    <li><a href="/dv/help" target="_blank">Help</a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body" style="background-color:#fef;">
            <form class="form-inline search-div" style="<?= isset($_GET['form']) && $_GET['form'] == 'full' ? 'display:none' : '' ?>">
                <input type="hidden" name="form" value="compact">
                <?= Html::textInput('search', $search, ['class'=>'form-control', 'style'=>'width:85%', 'placeholder'=>'Tìm theo nhiều tiêu chí']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
                <?= Html::a('Reset', '/dv') ?> &middot; <a class="search-toggle" href="#">Toggle</a>
            </form>
            <form class="form-inline search-div" style="<?= !isset($_GET['form']) || $_GET['form'] == 'compact' ? 'display:none' : '' ?>">
                <input type="hidden" name="form" value="full">
                <?= Html::dropdownList('status', '', [], ['class'=>'form-control', 'prompt'=>'- Trạng thái -']) ?>
                <?= Html::dropdownList('type', '', [], ['class'=>'form-control', 'prompt'=>'- Loại cp -']) ?>
                <?= Html::textInput('name', '', ['class'=>'form-control', 'placeholder'=>'Tìm theo tên cp']) ?>
                <?= Html::textInput('venue', '', ['class'=>'form-control', 'placeholder'=>'Tìm theo điểm/NCC']) ?>
                <?= Html::textInput('tk', '', ['class'=>'form-control', 'placeholder'=>'Tìm theo TK']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
                <?= Html::a('Reset', '/dv') ?> &middot; <a class="search-toggle" href="#">Toggle</a>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-xxs table-striped">
                <thead>
                    <tr>
                        <th>Tên / Điểm / NCC</th>
                        <th>Ký hiệu</th>
                        <th>Điều kiện</th>
                        <th>Chi phí</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theDvx as $dv) {
                        $dv['name'] = str_replace(
                            [
                                '_o', '_v', '_p', '_f', '_t',
                            ], [
                                '<span class="text-pink">'.$dv['object'].'</span>',
                                '<span class="text-pink">'.$dv['venue']['name'].'</span>',
                                '<span class="text-pink">'.$dv['byCompany']['name'].'</span>',
                                '<span class="text-pink">'.$dv['from_loc'].'</span>',
                                '<span class="text-pink">'.$dv['to_loc'].'</span>',
                            ], $dv['name']);
                        ?>
                    <tr>
                        <td>
                            <i class="fa fa-check-circle <?= $dv['status'] == 'on' ? 'text-success' : 'text-muted' ?>"></i>
                            <? if ($dv['note'] != '') { ?>
                            <i class="fa fa-info-circle pull-right" title="<?= Html::encode($dv['note']) ?>"></i>
                            <? } ?>
                            <? if ($dv['is_dependent'] == 'yes') { ?> &mdash; <? } ?>
                            <?= Html::a($dv['name'], '/dv/u/'.$dv['id']) ?>
                            <?
                        if ($dv['venue_id'] != 0) {
                            echo Html::a('<i class="text-pink fa fa-map-marker"></i>', '/venues/r/'.$dv['venue']['id'], ['target'=>'_blank', 'title'=>'Xem']);
                            echo ' ', $dv['venue']['name'];
                        }
                        if ($dv['by_company_id'] != 0) {
                            if ($dv['venue_id'] != 0) {
                                echo ' / ';
                            }
                            echo Html::a('<i class="text-pink fa fa-home"></i>', '/companies/r/'.$dv['byCompany']['id'], ['target'=>'_blank', 'title'=>'Xem']);
                            echo ' ',$dv['byCompany']['name'];
                        }
                        ?>
                        </td>
                        <td><?= $dv['search'] ?></td>
                        <td>
                            <i title="<?= $cpTypeList[$dv['stype']] ?? 'Unknown' ?>" class="text-muted fa fa-fw fa-<?= $cpTypeIconList[$dv['stype']] ?? 'dollar' ?>"></i>
                            <? if (isset($dvObjectTypeList[$dv['object_type']])) { ?>
                            <i title="<?= $dvObjectTypeList[$dv['object_type']]['name'] ?>" class="text-muted fa fa-fw fa-<?= $dvObjectTypeList[$dv['object_type']]['icon'] ?>"></i>
                            <? } ?>

                        <?= $dv['booking_conds'] ?></td>
                        <td class="text-right"><?
                        foreach ($dv['dvg'] as $dvg) {
                            echo trim00(number_format($dvg['price'], 2)), ' ', $dvg['currency'];
                            break;
                        }
                        ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>

        <? if ($pagination->pageSize < $pagination->totalCount) { ?>
        <div class="panel-body text-center">
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
</div>

<?
$js = <<<'TXT'
$('a.search-toggle').on('click', function(){
    $('.search-div').toggle();
    return false;
});
TXT;

$this->registerJs($js);