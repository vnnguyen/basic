<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

Yii::$app->params['page_title'] = 'Service suppliers ('.$pagination->totalCount.')';

Yii::$app->params['page_breadcrumbs'] = [
    ['Suppliers', '@web/suppliers'],
];

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <form class="form-inline">
                <?= Html::dropdownList('stype', '', [], ['class'=>'form-control', 'prompt'=>'Service type']) ?>
                <?= Html::textInput('name', $name, ['class'=>'form-control', 'placeholder'=>'Search name']) ?>
                <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
                <?= Html::a('Reset', '@web/suppliers') ?>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-condensed table-striped">
                <thead>
                    <tr>
                        <th width="">Supplier</th>
                        <th width="">Services provided</th>
                        <th width="">Venues</th>
                        <th width="">Website</th>
                        <th width="">Address</th>
                        <th width="20"></th>
                    </tr>
                </thead>
                <tbody>
                    <? if (empty($theSuppliers)) { ?><tr><td colspan="7">No data found.</td></tr><? } ?>
                    <? foreach ($theSuppliers as $supplier) { ?>
                    <tr>
                        <td>
                            <?=Html::a($supplier['name'], '@web/suppliers/r/'.$supplier['id'], ['id'=>'name-'.$supplier['id']])?>
                            <? if (USER_ID == 1111) { ?><a class="x3 text-danger" data-id="<?= $supplier['id'] ?>" href="#">xxx</a><? } ?>
                        </td>
                        <td><?= $supplier['info'] ?></td>
                        <td><?
                        if (count($supplier['venues']) > 0) {
                            $venueList = [];
                            foreach ($supplier['venues'] as $supplier2) {
                                $venueList[] = Html::a($supplier2['name'], 'venues/r/'.$supplier2['id']);
                            }
                            echo implode(', ', $venueList);
                        }
                        ?></td>
                        <td><?
                        if (count($supplier['metas']) > 0) {
                            foreach ($supplier['metas'] as $supplier2) {
                                if ($supplier2['k'] == 'website') {
                                    echo Html::a($supplier2['v'], $supplier2['v'], ['rel'=>'external']);
                                    break;
                                }
                            }
                        }
                        ?></td>
                        <td><?
                        if (count($supplier['metas']) > 0) {
                            foreach ($supplier['metas'] as $supplier2) {
                                if ($supplier2['k'] == 'address') {
                                    echo $supplier2['v'];
                                    break;
                                }
                            }
                        }
                        ?></td>
                        <td class="text-nowrap">
                            <a title="<?=Yii::t('mn', 'Edit')?>" class="text-muted" href="<?=DIR?>suppliers/u/<?=$supplier['id']?>"><i class="fa fa-edit"></i></a>
                        </td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
        <? if ($pagination->totalCount > $pagination->pageSize ) { ?>
        <div class="panel-body text-center">
        <?=LinkPager::widget(array(
            'pagination' => $pagination,
            'firstPageLabel' => '<<',
            'prevPageLabel' => '<',
            'nextPageLabel' => '>',
            'lastPageLabel' => '>>',
        )) ?>
        </div>
        <? } ?>
    </div>
</div>
<?
$js = <<<'TXT'
$('.x3').on('click', function(){
    var id = $(this).data('id');
    var x3 = $(this);
    var jqxhr = $.ajax({
        method: 'POST',
        url: '/suppliers/ajax',
        data: {action:'rename-x', id:id}
    })
    .done(function(name) {
        //alert( "success" );
        if (name != '') {
            $('#name-' + id).html(name);
            x3.remove();
        }
    })
    .fail(function() {
        alert( "error" );
    })
    .always(function() {
        //alert( "complete" );
    });
    return false;
});
TXT;

$this->registerJs($js);