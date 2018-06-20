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

//\fCore::expose($theDvx);

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Tra cứu dịch vụ - chi phí (<?= number_format($pagination->totalCount) ?>)</h6>
            <div class="heading-elements">
                <ul class="list-inline list-inline-separate heading-text">
                    <li><a href="/dv/c">+New</a></li>
                    <li><a href="/dv/checklist" target="_blank">Check</a></li>
                    <li><a href="/dv/help" target="_blank">Help</a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body" style="background-color:#e0f0ff;">
            <form class="form-inline search-div" style="<?= isset($_GET['form']) && $_GET['form'] == 'full' ? 'display:none' : '' ?>">
                <input type="hidden" name="form" value="compact">
                <?= Html::textInput('search', $search, ['class'=>'form-control', 'style'=>'width:85%; font-size:15px; letter-spacing:1px;', 'placeholder'=>'Tìm theo nhiều tiêu chí', 'autocomplete'=>'off']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
                <?= Html::a('Reset', '/dv') ?> &middot; <a class="search-toggle" href="#">Toggle</a>
            </form>
            <form class="form-inline search-div" style="<?= !isset($_GET['form']) || $_GET['form'] == 'compact' ? 'display:none' : '' ?>">
                <input type="hidden" name="form" value="full">
                <?= Html::dropdownList('status', '', [], ['class'=>'form-control', 'prompt'=>'- Trạng thái -']) ?>
                <?= Html::dropdownList('type', $type, $dvABCTypeList, ['class'=>'form-control', 'prompt'=>'- Loại cp -']) ?>
                <?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Tìm theo tên cp']) ?>
                <?= Html::textInput('venue', $venue, ['class'=>'form-control', 'placeholder'=>'Tìm theo điểm/NCC']) ?>
                <?= Html::textInput('tk', $tk, ['class'=>'form-control', 'placeholder'=>'Tìm theo TK']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
                <?= Html::a('Reset', '/dv') ?> &middot; <a class="search-toggle" href="#">Toggle</a>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-xxs table-striped">
                <thead>
                    <tr>
                        <th>View</th>
                        <th>Tên / Điểm / NCC</th>
                        <th>Ký hiệu</th>
                        <th>Địa điểm</th>
                        <th>Nhà cung cấp</th>
                        <th>Chi phí</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theDvx as $dv) {
$matches = [];
//preg_match_all('/\{([^}]+)\}/', $input, $matches);
preg_match_all('/\{([^\}]+)\}/', $dv['name'], $matches);

foreach ($matches[0] as $match) {
    if (substr($match, 1, 1) == '*') {
        $replace = str_replace([
            '{*', '}', '|',
            ], [
            '(<span class="text-pink text-light">', '</span>)', '/',
            ], $match);
    } else {
        $replace = '';
    }
    $dv['name'] = str_replace($match, $replace, $dv['name']);
}

$dv['name'] = trim($dv['name']);

                        $data = explode('|', $dv['data']);
                        //$data = explode(';', $data[1]);
                        /* $dv['name'] = str_replace(
                            [
                                '_1', '_2', '_3', '_4', '_5',
                            ], [
                                '<span class="text-pink">'.$data[0].'</span>',
                                '<span class="text-pink">'.$data[1].'</span>',
                                '<span class="text-pink">'.$data[2].'</span>',
                                '<span class="text-pink">'.$data[3].'</span>',
                                '<span class="text-pink">'.$data[4].'</span>',
                            ], $dv['name']); */
                        $dv['name'] = str_replace(
                            [
                                '[', ']',
                                '_s',
                            ], [
                                '<a class="text-pink link-node" href="#">', '</a>',
                                '('.Html::a($dv['supplier']['name'], '/suppliers/r/'.$dv['supplier']['id'], ['class'=>'text-pink']).')',
                            ], $dv['name']);
                        ?>
                    <tr>
                        <td class="text-nowrap">
                            <? if (in_array(USER_ID, [1, 1118, 1119198, 11134718])) { ?>
                            <?= Html::a('e', '/dv/u/'.$dv['id'], ['class'=>'text-muted', 'title'=>'Edit']) ?>
                            <?= Html::a('v', '/dv/r/'.$dv['id'], ['class'=>'text-muted', 'title'=>'View']) ?>
                            <? if (USER_ID == 1) { ?>
                            <?= Html::a('d', '/dv/d/'.$dv['id'], ['class'=>'text-muted', 'title'=>'Delete']) ?>
                            <? } ?>
                            <? } else { ?>
                            <?= Html::a('View', '/dv/r/'.$dv['id'], ['target'=>'_blank', 'class'=>'text-muted']) ?>
                            <? } ?>
                        </td>
                        <td>
                            <i class="fa fa-check-circle <?= $dv['status'] == 'on' ? 'text-success' : 'text-muted' ?>"></i>
                            <? if ($dv['note'] != '') { ?>
                            <i class="fa fa-info-circle pull-right" title="<?= Html::encode($dv['note']) ?>"></i>
                            <? } ?>
                            <? if ($dv['is_dependent'] == 'yes') { ?> &mdash; <? } ?>
                            <?= $dv['name'] ?>
                        </td>
                        <td><span class="text-bold text-pink"><?= $dv['stype'] ?>:</span><?= $dv['search'] ?> <span class="text-pink">@<?= implode(' @', explode(' ', $dv['search_loc'])) ?></span></td>
                        <td><?= Html::a($dv['venue']['name'], '/venues/r/'.$dv['venue']['id'], ['target'=>'_blank', 'class'=>'text-pink']) ?><!-- b=<?= $dv['whobooks'] ?> p=<?= $dv['whopays'] ?> x=<?= $dv['maxpax'] ?> --></td>
                        <td><?//= $dv['default_vendor'] ?></td>
                        <td class="text-right">
                            <? foreach ($dv['cp'] as $cp) { ?>
                            <?= number_format($cp['price']) ?> <span class="text-muted"><?= $cp['currency'] ?></span>
                            <? break ;} ?>
                        </td>
                    </tr>
                    <?
                    } // foreach dvx
                    ?>
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
$('a.link-node').on('click', function(){
    var text = $(this).html();
    $(this).attr('href', '/nodes/r/0?search=' + text).attr('target', '_blank');
    // return false;
})
$('a.search-toggle').on('click', function(){
    $('.search-div').toggle();
    return false;
});
TXT;

$this->registerJs($js);