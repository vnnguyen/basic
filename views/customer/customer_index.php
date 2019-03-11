<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\widgets\LinkPager;

include('_customer_inc.php');

Yii::$app->params['page_title'] = 'B2C - Customers ('.number_format($pagination->totalCount).')';
// Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_icon'] = 'group';
Yii::$app->params['page_breadcrumbs'] = [
    ['Customers ('.number_format($pagination->totalCount).')'],
];

Yii::$app->params['page_layout'] = '-t';

?>
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <div id="div-toggle-filters">
                <strong class="text-info"><?= Yii::t('x', 'Viewing {count} B2C customers', ['count'=>number_format($pagination->totalCount)]) ?></strong>
                &middot;
                <?php if ($name != '') { ?><strong><?= Yii::t('x', 'Name') ?>:</strong> <?= $name ?>; <?php } ?>

                <a href="#" class="action-show-filters"><?= Yii::t('x', 'Alter conditions') ?></a>
                <a href="#" class="action-cancel-filters" style="display:none;"><?= Yii::t('x', 'Cancel') ?></a>
                &middot;
                <a href="?" class="action-reset-filters"><?= Yii::t('x', 'Reset') ?></a>
            </div>
            <div id="div-filters" style="display:none">
                <hr>
                <form class="form-horizontal">
                    <div class="row">
                        <div class="col-md-6">
                            <p><span class="text-bold text-uppercase"><?= Yii::t('x', 'The customer') ?></span></p>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Name') ?>:</label>
                                <div class="col-sm-9"><?= Html::textInput('name', $name, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>Yii::t('x', 'Name')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Gender') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('gender', $gender, ['all'=>'All genders', 'male'=>'Male', 'female'=>'Female'], ['class'=>'form-control']) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Age') ?>:</label>
                                <div class="col-sm-9"><?= Html::textInput('age', $age, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Age, eg 20-30']) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Primary language') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('language', $language, $languageList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Primary language')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Nationality') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('country', $country, ArrayHelper::map($countryList, 'code', 'name_en'), ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Nationality')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Email') ?>:</label>
                                <div class="col-sm-9"><?= Html::textInput('email', $email, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>Yii::t('x', 'Email')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Telephone') ?>:</label>
                                <div class="col-sm-9"><?= Html::textInput('tel', $tel, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>Yii::t('x', 'Phone')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Address') ?>:</label>
                                <div class="col-sm-9"><?= Html::textInput('address', $address, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>'Address']) ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Customer profile') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('profile[]', $profile, $customerProfileList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Customer profile')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Travel preferences') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('preferences[]', $preferences, $travelPrefList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Travel preferences')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Likes') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('likes[]', $likes, $likeList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Likes')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Dislikes') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('dislikes[]', $dislikes, $dislikeList, ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Dislikes')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Tour code') ?>:</label>
                                <div class="col-sm-9"><?= Html::textInput('code', $code, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>Yii::t('x', 'Tour code')]) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'No. of bookings') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('bcount', $bcount, ['0'=>'Bookings', 1=>1,2=>2,3=>3,4=>4,5=>5], ['class'=>'form-control']) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'No. of referrals') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('rcount', $rcount, ['0'=>'Referrals', 1=>'1+',2=>'2+',3=>'3+',4=>'4+',5=>'5+'], ['class'=>'form-control']) ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-3 control-label"><?= Yii::t('x', 'Output') ?>:</label>
                                <div class="col-sm-9"><?= Html::dropdownList('output', 'view', ['view'=>Yii::t('x', 'View'), 'download'=>Yii::t('x', 'Download')], ['class'=>'form-control']) ?></div>
                                <?= Html::input('hidden', 'downloadToken', '', ['id' => 'download_token_value']) ?>
                            </div>
                            <?= Html::dropdownList('amba', $amba, [0=>'A - Amba', 1=>'B - Ampo'], ['class'=>'form-control', 'prompt'=>Yii::t('x', 'Ambassador potentiality')]) ?>

                            <?= Html::textInput('year', $year, ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>Yii::t('x', 'Year')]) ?>




                        </div>
                    </div>
                    <div>
                        <button class="btn btn-primary" type="submit"><?= Yii::t('x', 'Go') ?></button>
                        <a class="action-cancel-filters"><?= Yii::t('x', 'Cancel') ?></a>
                        &middot;
                        <a class="action-reset-filters" href="?"><?= Yii::t('x', 'Reset') ?></a>
                    </div>
                </form>
            </div>
        </div>

<?php

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.1.4/js/ion.rangeSlider.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/js/bootstrap-select.min.js', ['depends'=>'yii\web\JqueryAsset']);

?>
    <?php if (empty($theCustomers)) { ?>
        <div class="card-body text-danger"><?= Yii::t('x', 'No data found.') ?></div>
    <?php } else { ?>
        <div class="table-responsive">
            <table class="table table-narrow table-striped">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th width="10"></th>
                        <th colspan="2"><?= Yii::t('x', 'Name') ?></th>
                        <th class="text-center"><?= Yii::t('x', 'Birthdate') ?></th>
                        <th width=""><?= Yii::t('x', 'Email') ?></th>
                        <th width=""><?= Yii::t('x', 'Phone') ?></th>
                        <th width=""><?= Yii::t('x', 'Address') ?></th>
                        <th><?= Yii::t('x', 'Tour bookings') ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($theCustomers as $customer) { ?>
                    <tr>
                        <td class="text-nowrap">
                            <?php if ($customer['country_code'] != '') { ?><span class="flag-icon flag-icon-<?=$customer['country_code'] ?>"></span><?php } ?>
                        </td>
                        <td>
                            <?php if ($customer['gender'] == 'male') { ?><i class="fa fa-male text-primary"></i><?php } ?>
                            <?php if ($customer['gender'] == 'female') { ?><i class="fa fa-female text-pink"></i><?php } ?>
                        </td>
                        <td><?=Html::a($customer['fname'], '/contacts/'.$customer['id'])?></td>
                        <td><?=Html::a($customer['lname'], '/contacts'.$customer['id'])?></td>
                        <td class="text-center"><?= $customer['bday'] ?>/<?= $customer['bmonth'] ?>/<?= $customer['byear'] ?></td>
                        <td><?php
                        foreach ($customer['metas'] as $meta) {
                            if ($meta['format'] == 'email') {
                                echo '<div>', Yii::$app->formatter->asEmail($meta['value']), '</div>';
                            }
                        }
                            ?></td>
                        <td><?php
                        foreach ($customer['metas'] as $meta) {
                            if ($meta['format'] == 'tel') {
                                echo '<div>', $meta['value'], '</div>';
                            }
                        }
                            ?></td>
                        <td><?
                        foreach ($customer['metas'] as $meta) {
                            if ($meta['name'] == 'address') {
                                echo $meta['value'];
                            }
                        }
                        ?>
                        </td>
                        <td>
                            <?
                            if ($customer['bookings']) {
                                foreach ($customer['bookings'] as $booking) {
                                    echo Html::a($booking['product']['op_code'], '/products/op/'.$booking['product']['id'], ['class'=>'text-success']);
                                    echo '&nbsp; ';
                                }
                            }
                            ?>
                        </td>
                        <td width="15">
                            <a title="<?=Yii::t('mn', 'Edit')?>" class="text-muted" href="<?=DIR?>contacts/<?=$customer['id']?>/u"><i class="fa fa-edit"></i></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <?= LinkPager::widget([
        'pagination' => $pagination,
        'firstPageLabel'=>'<<',
        'prevPageLabel'=>'<',
        'nextPageLabel'=>'>',
        'lastPageLabel'=>'>>',
    ]) ?>

    <?php } // if theUsers ?>
</div>

<div id="domMessage" style="display:none;">
    <h2 class="text-center"><img style="width: 20px; height: 20px" src="/img/busy1.gif" /> We are processing your request.  Please be patient.</h1>
</div>
<?php
$js = <<<'TXT'
    $('.action-show-filters').on('click', function(e){
        e.preventDefault()
        $('#div-filters').show()
        $('.action-show-filters').hide()
        $('.action-cancel-filters').show()
    })
    $('.action-cancel-filters').on('click', function(e){
        e.preventDefault()
        $('#div-filters').hide()
        $('.action-show-filters').show()
        $('.action-cancel-filters').hide()
    })

    // processing download
    var downloadToken = new Date().getTime();
    $('form').submit(function(){
        // if ($('#export_fields').val().length > 0) {
        blockUIForDownload();
        // }
        return true;
    });
    var fileDownloadCheckTimer;
    function blockUIForDownload() {
        var attempts = 10000;
        var token = new Date().getTime(); //use the current timestamp as the token value
        $('#download_token_value').val(token);
        $.blockUI({
            message: $('#domMessage'),
            css: {
                borderRadius: '5px'
            }
            });
        fileDownloadCheckTimer = window.setInterval(function () {
            var cookieValue = $.cookie('downloadToken');
            attempts--;
            if (cookieValue == token || attempts == 0)
                finishDownload();
        }, 1000);
    }
    function finishDownload() {
        window.clearInterval(fileDownloadCheckTimer);
        $.cookie('downloadToken', null); //clears this cookie value
        $.unblockUI();
    }
    // end download


TXT;
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js', ['depends'=>'app\assets\MainAsset']);
$this->registerJs($js);