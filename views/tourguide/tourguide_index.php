<?
use yii\helpers\Html;
use yii\widgets\LinkPager;

include('_tourguide_inc.php');

?>
<div class="col-md-12">
    <?= Html::beginForm(DIR.URI, 'get', ['class'=>'form-inline mb-20']) ?>
    <?= Html::dropdownList('orderby', $getOrderby, ['name'=>'Order by name', 'pts'=>'Order by points', 'since'=>'Order by experience', 'age'=>'Order by age'], ['class'=>'form-control']) ?>
    <?= Html::dropdownList('gender', $getGender, ['all'=>'Gender', 'male'=>'Male', 'female'=>'Female'], ['class'=>'form-control']) ?>
    <?= Html::textInput('name', $getName, ['class'=>'form-control', 'placeholder'=>'Search name']) ?>
    <?= Html::textInput('phone', $getPhone, ['class'=>'form-control', 'placeholder'=>'Search phone']) ?>
    <?= Html::textInput('language', $getLanguage, ['class'=>'form-control', 'placeholder'=>'Language']) ?>
    <?= Html::textInput('region', $getRegion, ['class'=>'form-control', 'placeholder'=>'Region']) ?>
    <?= Html::textInput('tourtype', $getTourtype, ['class'=>'form-control', 'placeholder'=>'Tour type']) ?>
    <?= Html::submitButton('Go', ['class'=>'btn btn-primary']) ?>
    <?= Html::a('Reset', '/tourguides') ?>
    <?= Html::endForm() ?>

    <? if (empty($theTourguides)) { ?>
    <p>No data found. <?=Html::a('Create the first one', 'tourguides/c')?>.</p>
    <? } else { ?>
    <div class="row">
        <? $cnt = 0; foreach ($theTourguides as $tourguide) { $cnt ++; ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div style="padding:8px; border-bottom: 1px solid #ddd" class="clearfix bg-white">
            <div style="width:60px; height:60px; float:left; margin-right:10px;">
                <? if ($tourguide['image'] == '') { ?>
                <?= Html::img('https://secure.gravatar.com/avatar/df1426bf5eec7bec99718d9381fde836?s=60&d=mm', ['style'=>'width:60px; height:60px;']) ?>
                <? } else { ?>
                <?= Html::img($tourguide['image'], ['style'=>'width:60px; height:60px;']) ?>
                <? } ?>
            </div>
            <?= $tourguide['ratings'] != 0 ? '<div class="pull-right"><span class="badge badge-success">'.$tourguide['ratings'].'</span></div>' : '' ?>
            <div>
                <strong><?= Html::a($tourguide['fname'].' '.$tourguide['lname'], '/tourguides/r/'.$tourguide['id']) ?></strong>
                <i class="fa fa-<?= $tourguide['gender'] ?> color-gender-<?= $tourguide['gender'] ?>"></i>
                <em><?= $tourguide['byear'] == '0000' ? '' : date('Y') - $tourguide['byear'] ?></em>
                <br>
                <?= $tourguide['phone'] ?>
                <?= Html::a('<i class="fa fa-edit"></i>', 'tourguides/u/'.$tourguide['id'], ['class'=>'text-muted']) ?>
                <br>
                <?= $tourguide['languages'] ?>
                <?= $tourguide['regions'] ?>
            </div>
            </div>
        </div>
        <? if ($cnt % 2 == 0) { ?>
        <div class="clearfix visible-sm-block"></div>
        <? } ?>
        <? if ($cnt % 3 == 0) { ?>
        <div class="clearfix visible-md-block"></div>
        <? } ?>
        <? if ($cnt % 4 == 0) { ?>
        <div class="clearfix visible-lg-block"></div>
        <? } ?>
        <? } ?>
    </div>

    <div class="text-center mt-20 mb-20">
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
.color-gender-male {color:blue;}
.color-gender-female {color:pink;}
</style>