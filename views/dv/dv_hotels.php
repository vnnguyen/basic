<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;


Yii::$app->params['page_title'] = 'Hotel check list';
Yii::$app->params['body_class'] = 'sidebar-xs';
$this->params['breadcrumb'] = [
    ['DV', 'dv'],
];

$js = <<<'TXT'
TXT;

$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
                <?= Html::dropdownList('type', '', ['hotel'=>'Hotels'], ['class'=>'form-control']) ?>
                <?= Html::dropdownList('dest', $dest, ArrayHelper::map($destList, 'id', 'name_en', 'country_code'), ['class'=>'form-control', 'prompt'=>'All destinations']) ?>
                <?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Name']) ?>
                <?= Html::textInput('search', $search, ['class'=>'form-control', 'placeholder'=>'Search']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
                <?= Html::a('Reset', '/dv/hotels') ?>
            </form>
            <? if (empty($theVenues)) { ?>
            <p>No data found</p>
            <? } ?>
        </div>
        <div class="table-responsive">
            <table class="table table-xxs table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th width="">Code</th>
                        <th width="">Name / Search</th>
                        <th width="">Dest</th>
                        <th width="">Supplier</th>
                        <th width="">Bookings</th>
                        <th width="">Contracts</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    $c = '';
                    $d = '';
                    foreach ($theVenues as $venue) {
                    ?>
                    <tr>
                        <td class="text-center"><?= Html::a($venue['id'], '/venues/u/'.$venue['id'], ['class'=>'text-muted']) ?></td>
                        <td class="text-pink"><?= $venue['abbr'] ?></td>
                        <td>
                            <?= Html::a($venue['name'], '/venues/r/'.$venue['id'], ['target'=>'_blank']) ?>
                            <?= $venue['search'] ?>
                        </td>
                        <td class="text-nowrap"><?= $venue['destination']['name_en'] ?></td>
                        <td class="text-nowrap"><?= $venue['supplier_id'] != 0 ? $venue['supplier']['name'] : '<i class="fa fa-warning text-danger"></i>' ?></td>
                        <td class="text-nowrap">
                        <?
                        if (empty($venue['cpt'])) {
                            echo '<i class="fa fa-warning text-danger"></i>';
                        } else {
                            foreach ($venue['cpt'] as $cpt) {
                                if (substr($cpt['dvtour_day'], 0, 4) == 2017) {
                                    $hasb[2017] = true;
                                }
                                if (substr($cpt['dvtour_day'], 0, 4) == 2016) {
                                    $hasb[2016] = true;
                                }
                            }
                            echo implode(', ', array_keys($hasb));
                        }
                        ?>
                        </td>
                        <td class="text-nowrap">
                        <?
                        if (empty($venue['dvc'])) {
                            echo '<i class="fa fa-warning text-danger"></i>';
                        } else {
                            $dvcList = [];
                            foreach ($venue['dvc'] as $dvc) {
                                $dvcList[] = Html::a($dvc['name'], '/dvc/r/'.$dvc['id']);
                            }
                            echo implode(', ', $dvcList);
                        }
                        ?>
                        </td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>

        <? if ($pagination->totalCount > $pagination->pageSize) { ?>
        <div class="panel-body text-center">
        <?= LinkPager::widget([
            'pagination' => $pagination,
            'firstPageLabel' => '<<',
            'prevPageLabel' => '<',
            'nextPageLabel' => '>',
            'lastPageLabel' => '>>',
        ]);
        ?>
        </div>
        <? } ?>
    </div>
</div>
