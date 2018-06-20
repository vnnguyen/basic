<?
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

include('_role_inc.php');

Yii::$app->params['page_title'] = 'Roles';

?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"></h6>
        </div>
        <table class="table table-condensed table-striped table-bordered">
            <thead>
                <th width="30">ID</th>
                <th>Name</th>
                <th>Short name</th>
                <th>Description</th>
                <th>Allowed list</th>
                <th width="40"></th>
            </thead>
            <tbody>
                <? foreach ($theRoles as $role) { ?>
                <tr>
                    <td class="text-muted text-center"><?= $role['id'] ?></td>
                    <td><?= Html::a($role['name'], 'roles/u/'.$role['id']) ?></td>
                    <td><?= $role['alias'] ?></td>
                    <td><?= $role['info'] ?></td>
                    <td><?= $role['id'] ?></td>
                    <td>
                        <?= Html::a('<i class="fa fa-trash-o"></i>', 'roles/d/'.$role['id'], ['class'=>'text-muted']) ?>
                    </td>
                </tr>
                <? } ?>
            </tbody>
        </table>
    </div>

</div>

<?
/*

?>
<!--div class="alert alert-info">CHÚ Ý: Mới tách thêm 2 loại ct mới là CT tour Hãng và ct tour TCG, mọi người chú ý khi tìm kiếm</div-->
<div class="col-md-12">
    <form method="get" action="" class="well well-sm form-inline">
        <select class="form-control w-auto" name="language">
            <option value="all">All languages</option>
            <? foreach ($languageList as $k => $v) { ?>
            <option value="<?=$k?>" <?=$getLanguage == $k ? 'selected="selected"' : ''?>><?=$v?></option>
            <? } ?>
        </select>
        <select class="form-control w-auto" name="type">
            <option value="private">Private tour</option>
            <option value="agent" <?=$getType == 'agent' ? 'selected="selected"' : ''?>>Tour hãng</option>
            <option value="vpc" <?=$getType == 'vpc' ? 'selected="selected"' : ''?>>VPC tour</option>
            <option value="tcg" <?=$getType == 'tcg' ? 'selected="selected"' : ''?>>TCG tour</option>
        </select>
        <select name="ub" class="form-control w-auto">
            <option value="all">Updated by</option>
            <option value="<?=Yii::$app->user->id?>" <?=$getUb == Yii::$app->user->id ? 'selected="selected"' : ''?>>Tôi (<?= Yii::$app->user->identity->name ?>)</option>
            <? foreach ($ubList as $ub) { if (Yii::$app->user->id != $ub['id']) { ?>
            <option value="<?= $ub['id'] ?>" <?=$getUb == $ub['id'] ? 'selected="selected"' : ''?>><?=$ub['lname']?>, <?=$ub['email']?></option>
            <? } } ?>
        </select>
        <select class="form-control w-auto" name="month">
            <option value="all">Start date</option>
            <? foreach ($startDateList as $role) { ?>
            <option value="<?= $role['ym'] ?>" <?= $getMonth == $role['ym'] ? 'selected="selected"' : ''?>><?= $role['ym'] ?></option>
            <? } ?>
        </select>
        <select class="form-control w-auto" name="proposal">
            <option value="all">Trạng thái bán</option>
            <option value="yes" <?=$getProposal == 'yes' ? 'selected="selected"' : ''?>>Đang bán</option>
            <option value="no" <?=$getProposal == 'no' ? 'selected="selected"' : ''?>>Chưa bán</option>
        </select>
        <select class="form-control w-auto" name="days">
            <option value="all">Days</option>
            <option value="10" <?=$getDays == '10' ? 'selected="selected"' : ''?>>1-10 ngày</option>
            <option value="20" <?=$getDays == '20' ? 'selected="selected"' : ''?>>11-20 ngày</option>
            <option value="30" <?=$getDays == '30' ? 'selected="selected"' : ''?>>21-30 ngày</option>
            <option value="31" <?=$getDays == '31' ? 'selected="selected"' : ''?>>Trên 30 ngày</option>
        </select>
        <input type="text" class="form-control w-auto" name="name" placeholder="Search name or tag" value="<?=fHTML::encode($getName)?>" />
        <select class="form-control w-auto" name="order">
            <option value="uo">Order by: Updated</option>
            <option value="day_from" <?=$getOrder == 'day_from' ? 'selected="selected"' : ''?>>Order by: Tour date</option>
            <option value="days" <?=$getOrder == 'days' ? 'selected="selected"' : ''?>>Order by: Days</option>
            <option value="pax" <?=$getOrder == 'pax' ? 'selected="selected"' : ''?>>Order by: Pax</option>
            <option value="title" <?=$getOrder == 'title' ? 'selected="selected"' : ''?>>Order by: Name</option>
        </select>
        <select class="form-control w-auto" name="sort">
            <option value="desc">Descending</option>
            <option value="asc" <?=$getSort == 'asc' ? 'selected="selected"' : ''?>>Ascending</option>
        </select>
        <button type="submit" class="btn btn-primary">Go</button>
        <?= Html::a('Reset', 'ct') ?>
    </form>
    <div class="table-responsive">
        <table class="table table-striped table-condensed table-bordered table-hover">
            <thead>
                <tr>
                    <th width="100" class="text-center">Lang/Type</th>
                    <th>Name</th>
                    <th width="80">Start date</th>
                    <th width="40" class="text-center">Days</th>
                    <th width="40" class="text-center">Pax</th>
                    <th>Price</th>
                    <th>Updated by</th>
                    <th width="40"></th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($models as $role) { ?>
                <tr>
                    <td class="text-muted text-center"><?= strtoupper($role['language']) ?> | <?= strtoupper($role['offer_type']) ?></td>
                    <td>
                        <i class="fa fa-file-text-o popovers pull-right text-muted"
                            data-trigger="hover"
                            data-title="<?= $role['title'] ?>"
                            data-placement="left"
                            data-html="true"
                            data-content="
                        <?
                        $dayIds = explode(',', $role['day_ids']);
                        if (count($dayIds) > 0) {
                            $cnt = 0;
                            foreach ($dayIds as $id) {
                                foreach ($role['days'] as $day) {
                                    if ($day['id'] == $id) {
                                        $cnt ++;
                                        echo '<strong>', $cnt, ':</strong> ', $day['name'], ' (', $day['meals'], ')<br>';
                                    }
                                }
                            }
                        }
                        ?>
                        "></i>
                        <? if ($role['offer_count'] == 0) { ?><?= Html::a('+', 'ct/propose/'.$role['id'], ['title'=>'+ New proposal']) ?><? } else { ?>
                        <?= !isset($role['cases'][0]) ?: Html::a('<i class="fa fa-briefcase"></i>', 'cases/r/'.$role['cases'][0]['id'], ['class'=>'text-warning', 'title'=>'View case: '.$role['cases'][0]['name']]) ?>
                        <?= Html::a($role['tour']['code'], 'tours/r/'.$role['tour']['id'], ['title'=>'View tour: '.$role['tour']['name'], 'style'=>'background-color:#ffc; color:#060; padding:0 5px;']) ?>
                        <? } ?>
                        <?= Html::a($role['title'], 'ct/r/'.$role['id']) ?>
                        <span class="text-muted"><?= $role['about'] ?></span>
                    </td>
                    <td><?= $role['day_from'] ?></td>
                    <td class="text-center"><?= count($role['days']) ?></td>
                    <td class="text-center"><?= $role['pax'] ?></td>
                    <td class="text-right"><?= number_format($role['price'], 0) ?> <span class="text-muted"><?= $role['price_unit'] ?></span></td>
                    <td><?= Html::a($role['updatedBy']['name'], 'users/r/'.$role['updatedBy']['id']) ?></td>
                    <td>
                        <?= Html::a('<i class="fa fa-edit"></i>', 'ct/u/'.$role['id'], ['class'=>'text-muted', 'title'=>'Edit']) ?>
                        <?= Html::a('<i class="fa fa-trash-o"></i>', 'ct/d/'.$role['id'], ['class'=>'text-muted', 'title'=>'Delete']) ?>
                    </td>
                </tr>
                <? } ?>
            </tbody>
        </table>
    </div>
    <? if ($pages->totalCount > $pages->limit) { ?>
    <div class="text-center">
    <?=LinkPager::widget([
        'pagination' => $pages,
        'firstPageLabel' => '<<',
        'prevPageLabel' => '<',
        'nextPageLabel' => '>',
        'lastPageLabel' => '>>',
    ]);?>
    </div>
    <? } ?>
</div>
<style type="text/css">
.popover {max-width:500px;}
.form-control .w-auto {width:auto;}
</style>*/