<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;


$this->title = 'Customers ('.number_format($pagination->totalCount).')';
Yii::$app->params['page_icon'] = 'group';
Yii::$app->params['page_breadcrumbs'] = [
    ['Customers'],
];

Yii::$app->params['page_layout'] = '-t sli';

$this->beginBlock('sli'); ?>
<div class="sidebar sidebar-secondary sidebar-default">
    <div class="sidebar-content">
        <!-- Sidebar search -->
        <div class="sidebar-category">
<?
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/js/bootstrap-select.min.js', ['depends'=>'yii\web\JqueryAsset']);
$js = '';

?>
            <div class="category-content">
                <form method="get" action="">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><?= Yii::t('p', 'Year') ?></label>
                                <?= Html::textInput('year', $getYear, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>Yii::t('p', 'Year')]) ?>
                            </div>
                            <div class="col-sm-6">
                                <label><?= Yii::t('p', 'Tour code') ?></label>
                                <?= Html::textInput('code', $getCode, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Tour code']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><?= Yii::t('p', 'Year') ?></label>
                                <?= Html::textInput('fname', $getFname, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'First name']) ?>
                            </div>
                            <div class="col-sm-6">
                                <label><?= Yii::t('p', 'Tour code') ?></label>
                                <?= Html::textInput('lname', $getLname, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Second name']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><?= Yii::t('p', 'Gender') ?></label>
                                <?= Html::dropdownList('gender', $getGender, ['all'=>'All genders', 'male'=>'Male', 'female'=>'Female'], ['class'=>'form-control']) ?>
                            </div>
                            <div class="col-sm-6">
                                <label><?= Yii::t('p', 'Age') ?></label>
                                <?= Html::textInput('age', $getAge, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Age, eg 20-30']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?= Yii::t('p', 'Country') ?></label>
                        <?= Html::dropdownList('country', $getCountry, ArrayHelper::map($countryList, 'code', 'name_en'), ['class'=>'form-control', 'prompt'=>'All countries']) ?>
                    </div>
                    <div class="form-group">
                        <label><?= Yii::t('p', 'Address') ?></label>
                        <?= Html::textInput('address', $getAddress, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Address']) ?>
                    </div>
                    <div class="form-group">
                        <label><?= Yii::t('p', 'Email address') ?></label>
                        <?= Html::textInput('email', $getEmail, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Email']) ?>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><?= Yii::t('p', 'Bookings') ?></label>
                                <?= Html::dropdownList('bcount', $bcount, ['0'=>'Bookings', 1=>1,2=>2,3=>3,4=>4,5=>5], ['class'=>'form-control']) ?>
                            </div>
                            <div class="col-sm-6">
                                <label><?= Yii::t('p', 'Referrals') ?></label>
                                <?= Html::dropdownList('rcount', $rcount, ['0'=>'Referrals', 1=>1,2=>2,3=>3,4=>4,5=>5], ['class'=>'form-control']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?= Yii::t('p', 'Output') ?></label>
                        <?= Html::dropdownList('output', 'view', ['view'=>'View', 'download'=>'Download'], ['class'=>'form-control']) ?>
                    </div>

                    <? if (0): ?>
                        <div class="form-group">
                        <label><?= Yii::t('p', 'Property type') ?></label>
                        <?= Html::dropdownList('type[]', $type, $typeDropdownList, ['class'=>'selectpicker form-control', 'multiple'=>'multiple', 'data-live-search'=>'true', 'title'=>Yii::t('p', 'All types'), 'data-selected-text-format'=>'count>3', 'data-size'=>12]) ?>
                    </div>
                    <div class="form-group">
                        <!-- label><?= Yii::t('p', 'Projects only') ?></label-->
                        <?= Html::dropdownList('is_project', $is_project, $isProjectList, ['class'=>'form-control']) ?>
                    </div>
                    <div class="form-group">
                        <label><?= Yii::t('p', 'Location') ?></label>
                        <?= Html::dropdownList('location[]', $location, $locationList, ['options'=>$locationListOptions, 'class'=>'selectpicker form-control', 'multiple'=>'multiple', 'data-live-search'=>'true', 'title'=>Yii::t('p', 'All locations'), 'data-selected-text-format'=>'count>3', 'data-size'=>12]) ?>
                    </div>
                    <div class="form-group">
                        <label><?= Yii::t('p', 'Address or name (use commas to separate multiple values)') ?></label>
                        <?= Html::textInput('name', $name, ['class'=>'form-control']) ?>
                    </div>
                    <div class="form-group">
                        <label><?= Yii::t('p', 'Area from - to (sqm)') ?></label>
                        <div class="row">
                            <div class="col-xs-6">
                                <?= Html::textInput('area1', $area1, ['class'=>'form-control']) ?>
                            </div>
                            <div class="col-xs-6">
                                <?= Html::textInput('area2', $area2, ['class'=>'form-control']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?= Yii::t('p', 'Price from - to (USD)') ?></label>
                        <div class="row">
                            <div class="col-xs-6">
                                <?= Html::textInput('price1', $price1, ['class'=>'form-control']) ?>
                            </div>
                            <div class="col-xs-6">
                                <?= Html::textInput('price2', $price2, ['class'=>'form-control']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label><?= Yii::t('p', 'Status') ?></label>
                                <?= Html::dropdownList('status[]', $status, $statusList, ['options'=>$statusListOptions, 'class'=>'selectpicker form-control', 'multiple'=>'multiple', 'title'=>Yii::t('p', 'All status')]) ?>
                            </div>
                            <div class="col-sm-6">
                                <label><?= Yii::t('p', 'Ranking') ?></label>
                                <?= Html::dropdownList('ranking[]', $ranking, $rankingList, ['class'=>'selectpicker form-control', 'multiple'=>'multiple', 'title'=>Yii::t('p', 'All rankings')]) ?>
                            </div>
                        </div>
                    </div>
                <? endif; ?>
                    <div class="row">
                        <div class="col-sm-6"><p><?= Html::submitButton(Yii::t('p', 'Search'), ['class'=>'btn btn-primary btn-block']) ?></p></div>
                        <div class="col-sm-6"><p><?= Html::a(Yii::t('c', 'Reset'), '/customers', ['class'=>'btn btn-warning btn-block']) ?></p></div>
                    </div>
                    <!--
                    <div class="text-center">
                        <?= Html::a(Yii::t('p', 'Save link'), '/p/search?action=add&'.Yii::$app->request->queryString) ?>
                        -
                        <?= Html::a(Yii::t('p', 'View saved links'), '/p/search') ?>
                    </div>
                    -->
                </form>
            </div>
        </div>
        <!-- /sidebar search -->
    </div>
</div>
<?

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.1.4/js/ion.rangeSlider.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);

$this->endBlock();
?>
<div class="col-md-12">
    <div class="panel panel-default">
        <? if (empty($theUsers)) { ?>
        <div class="panel-body">
        No data found.
        </div>
        <? } else { ?>
        <div class="table-responsive">
            <table class="table table-condensed table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th colspan="2">Name</th>
                        <th width="">Date of birth</th>
                        <th width="">Email</th>
                        <th width="">Phone</th>
                        <th width="">Address</th>
                        <th>Tour bookings</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theUsers as $user) { ?>
                    <tr>
                        <td class="text-nowrap">
                            <? if ($user['country_code'] != '') { ?><span class="flag-icon flag-icon-<?=$user['country_code']?>"></span><? } ?>
                        </td>
                        <td>
                            <? if ($user['gender'] == 'male') { ?><i class="fa fa-male text-indigo"></i><? } ?>
                            <? if ($user['gender'] == 'female') { ?><i class="fa fa-female text-pink"></i><? } ?>
                        </td>
                        <td><?=Html::a($user['fname'], 'users/r/'.$user['id'])?></td>
                        <td><?=Html::a($user['lname'], 'users/r/'.$user['id'])?>
                        <?
                        if (Yii::$app->user->id == 1 && $user['lname'] == '' && $user['fname'] != '') {
                            $names = explode(' ', $user['fname']);
                            if (count($names) == 2) {
                                echo Html::a($names[0].'/'.$names[1], 'users/d/'.$user['id'].'?action=name&option=12');
                                echo ' - ';
                                echo Html::a($names[1].'/'.$names[0], 'users/d/'.$user['id'].'?action=name&option=21');
                            }
                        }
                        ?>
                        </td>
                        <td><?= $user['bday'] ?>/<?= $user['bmonth'] ?>/<?= $user['byear'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['phone'] ?></td>
                        <td><?
                        foreach ($user['metas'] as $meta) {
                            if ($meta['k'] == 'address') {
                                echo $meta['v'];
                            }
                        }
                        ?>
                        </td>
                        <td>
                            <?
                            if ($user['bookings']) {
                                foreach ($user['bookings'] as $booking) {
                                    echo Html::a($booking['product']['op_code'], '/products/op/'.$booking['product']['id'], ['class'=>'text-success']);
                                    echo '&nbsp; ';
                                }
                            }
                            ?>
                        </td>
                        <td class="muted td-n">
                            <a title="<?=Yii::t('mn', 'Edit')?>" class="text-muted" href="<?=DIR?>users/u/<?=$user['id']?>"><i class="fa fa-edit"></i></a>
                        </td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>

    <? if ($pagination->totalCount > $pagination->pageSize) { ?>
    <div class="_panel-body text-center">
        <?= LinkPager::widget([
                'pagination' => $pagination,
                'firstPageLabel'=>'<<',
                'prevPageLabel'=>'<',
                'nextPageLabel'=>'>',
                'lastPageLabel'=>'>>',
            ]) ?>
    </div>
    <? } // if pagination ?>

    <? } // if theUsers ?>
</div>