<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
// use yii\bootstrap\ActiveForm;

include('_tours_inc.php');

$availableFiles = [];
/*
    $db->query('UPDATE at_tours SET status="deleted" WHERE id=%i LIMIT 1', $theTour['id']);
    $db->query('UPDATE at_ct SET op_finish="canceled", op_finish_dt=%s WHERE id=%i LIMIT 1', NOW, $theCt['id']);
*/

$this->title = Yii::t('tour_pax', 'Pax list').': '.$theTour['op_code'];

// Added Thu Tran, Phuong Anh
$allowList = [1, 8162, 34595,  39748, 1351, 29296, 12952, 27388, 29123, 30554, 35071, 33415, 39063, 40217];

Yii::$app->params['body_class'] = 'sidebar-xs';

$this->params['breadcrumb'] = [
    ['Tour operation', '#'],
    ['Tours', 'tours'],
    [substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
    [$theTour['op_code'], 'tours/r/'.$theTourOld['id']],
    ['Pax list', URI],
];

?>
<style>
.hidden-print.line-through {text-decoration:line-through;}
th.hidden-print.line-through, td.hidden-print.line-through {color:#ddd; background-color:#eee;}
td.hidden-print.line-through .flag-icon {display:none;}
.table-narrow tr>th, .table-narrow tr>td {padding:8px!important;}
.table-narrow tr>th:first-child, .table-narrow tr>td:first-child {padding-left:16px!important;}
.table-narrow tr>th:last-child, .table-narrow tr>td:last-child {padding-right:16px!important;}
</style>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading hidden-print">
            <?= Yii::t('tour_pax', 'Pax list') ?>
            <small><?= Yii::t('tour_pax', 'Click a column heading to toggle print for that column') ?></small>
            <div class="heading-elements">
                <a class="heading-text" onclick="window.print(); return false;"><i class="fa fa-print"></i> <?= Yii::t('tour_pax', 'Print') ?></a>
            </div>    
        </div>
        <div class="table-responsive">
            <table id="tbl-paxlist" class="table table-condensed table-striped table-narrow">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th><?= Yii::t('tour_pax', 'Family name(s)') ?></th>
                        <th><?= Yii::t('tour_pax', 'Given name(s)') ?></th>
                        <th class="text-center"><?= Yii::t('tour_pax', 'Gender') ?></th>
                        <th class="text-center"><?= Yii::t('tour_pax', 'Date of birth') ?></th>
                        <th><?= Yii::t('tour_pax', 'Nationality') ?></th>
                        <th class="text-center"><?= Yii::t('tour_pax', 'Passport') ?></th>
                        <th class="text-center"><?= Yii::t('tour_pax', 'Issue date') ?></th>
                        <th class="text-center"><?= Yii::t('tour_pax', 'Expiry date') ?></th>
                        <th><?= Yii::t('tour_pax', 'Email') ?></th>
                        <th><?= Yii::t('tour_pax', 'Phone') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    if (!empty($theTour['pax'])) {
                        foreach ($theTour['pax'] as $cnt=>$pax) {
                            if ($pax['status'] != 'canceled') {
                                $pax['data'] = @unserialize($pax['data']);
                    ?>
                    <tr>
                        <td class="text-muted"><?= 1 + $cnt ?></td>
                        <td><?= $pax['data']['pp_name'] ?? '' ?></td>
                        <td><?= $pax['data']['pp_name2'] ?? '' ?></td>
                        <td class="text-center"><?= ucwords(Yii::t('tour_pax', $pax['data']['pp_gender'] ?? '')) ?></td>
                        <td class="text-nowrap text-center"><?= implode('/', [$pax['data']['pp_bday'], $pax['data']['pp_bmonth'], $pax['data']['pp_byear']]) ?></td>
                        <td class="text-nowrap">
                            <? if ($pax['data']['pp_country_code'] != '') { ?>
                            <span class="hidden-print flag-icon flag-icon-<?= $pax['data']['pp_country_code'] ?>"></span>
                            <?
                            foreach ($countryList as $country) {
                                if ($country['code'] == $pax['data']['pp_country_code']) {
                                    if (Yii::$app->language == 'vi') {
                                        echo $country['name_vi'];
                                    } else {
                                        echo $country['name_en'];
                                    }
                                    break;
                                }
                            }
                            ?>
                            <? } ?>
                        </td>
                        <td class="text-nowrap text-center"><?= $pax['data']['pp_number'] ?></td>
                        <td class="text-nowrap text-center"><?= implode('/', [$pax['data']['pp_iday'], $pax['data']['pp_imonth'], $pax['data']['pp_iyear']]) ?></td>
                        <td class="text-nowrap text-center"><?= implode('/', [$pax['data']['pp_eday'], $pax['data']['pp_emonth'], $pax['data']['pp_eyear']]) ?></td>
                        <td><?= $pax['data']['email'] ?></td>
                        <td><?= $pax['data']['tel'] ?></td>
                    </tr>
                    <?
                            } // if not canceled
                        }
                    } else {
                        $cnt = 0;
                        foreach ($theTour['bookings'] as $booking) {
                            foreach ($booking['people'] as $user) {
                    ?>
                    <tr>
                        <td class="text-muted"><?= ++$cnt ?></td>
                        <td><?= $user['fname'] ?></td>
                        <td><?= $user['lname'] ?></td>
                        <td class="text-center"><?= $user['gender'] ?></td>
                        <td class="text-nowrap text-center"><?= implode('/', [$user['bday'], $user['bmonth'], $user['byear']]) ?></td>
                        <td class="text-nowrap">
                            <? if ($user['country']['code'] != '') { ?>
                            <span class="hidden-print flag-icon flag-icon-<?= $user['country']['code'] ?>"></span> <?= $user['country']['name_en'] ?>
                            <? } ?>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['phone'] ?></td>
                    </tr>
                    <?
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?
$js = <<<'TXT'
// $('#bookingpaxform-pp_country_code, #bookingpaxform-address_country_code').select2();
$('#bookingpaxform-is_repeating').on('change', function(){
    var val = $(this).val();
    if (val == 'yes') {
        $('#div_pp_file').show();
    } else {
        $('#div_pp_file').hide();
    }
});
// Hide print
$('#tbl-paxlist thead th').on('click', function(){
    $(this).toggleClass('hidden-print line-through');
    var idx = $('#tbl-paxlist thead th').index($(this));
    $('#tbl-paxlist tbody tr').each(function(i) {
        $(this).find('td:eq('+idx+')').toggleClass('hidden-print line-through');
    });
});
TXT;
$this->registerJs($js);

$data = unserialize($thePax['data']);

?>
<? if (in_array(USER_ID, $allowList)): ?>
</div><!-- row --><div class="row hidden-print">
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <? if (!$theForm){ ?>
            <?= Yii::t('tour_pax', 'Pax info') ?>: <?= $thePax['name'] ?>
            <? } else { ?> 
            <? if ($thePax->isNewRecord){ ?>
            <?= Yii::t('tour_pax', 'Add new pax info') ?>
            <? } else { ?>
            <?= Yii::t('tour_pax', 'Edit pax info') ?>: <?= $thePax['name'] ?>
            <? } ?>
            <? } // if theForm ?>
        </div>
        <div class="panel-body">
            <? if (!$theForm): ?>
            <table class="table table-bordered table-xxs">
                <tbody>
                    <tr>
                        <td colspan="4">About this person</td>
                    </tr>
                    <tr>
                        <td>Display name</td>
                        <td><?= $thePax['name'] ?></td>
                        <td>Returning?</td>
                        <td><?= $thePax['is_repeating'] ?></td>
                    </tr>
                    <tr>
                        <td>Profession</td>
                        <td><?= $data['profession'] ?></td>
                        <td>Place of birth</td>
                        <td><?= $data['place_of_birth'] ?></td>
                    </tr>
                    <tr>
                        <td colspan="4">Passport information</td>
                    </tr>
                    <tr>
                        <td>Issuing country</td>
                        <td><?= strtoupper($data['pp_country_code']) ?></td>
                        <td>Passport number</td>
                        <td><?= $data['pp_number'] ?></td>
                    </tr>
                    <tr>
                        <td>Surname(s)</td>
                        <td><?= $data['pp_name'] ?></td>
                        <td>Given name(s)</td>
                        <td><?= $data['pp_name2'] ?></td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td><?= $data['pp_gender'] ?></td>
                        <td>Date of birth (d/m/y)</td>
                        <td><?= $data['pp_bday'] ?>/<?= $data['pp_bmonth'] ?>/<?= $data['pp_byear'] ?></td>
                    </tr>
                    <tr>
                        <td>Date of issue</td>
                        <td><?= $data['pp_iday'] ?>/<?= $data['pp_imonth'] ?>/<?= $data['pp_iyear'] ?></td>
                        <td>Date of expiry</td>
                        <td><?= $data['pp_eday'] ?>/<?= $data['pp_emonth'] ?>/<?= $data['pp_eyear'] ?></td>
                    </tr>
                    <tr>
                        <td colspan="4">Contact information</td>
                    </tr>
                    <tr>
                        <td>Tel</td>
                        <td><?= $data['tel'] ?></td>
                        <td>Tel 2</td>
                        <td><?= $data['tel2'] ?></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><?= $data['email'] ?></td>
                        <td>Email 2</td>
                        <td><?= $data['email2'] ?></td>
                    </tr>
                    <tr>
                        <td>Email 3</td>
                        <td><?= $data['email3'] ?></td>
                        <td>Website</td>
                        <td><?= $data['website'] ?></td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td colspan="3">
                            <?= $data['address'] ?><br>
                            <?= implode(', ', [$data['address_city_state'], strtoupper($data['address_country_code']), $data['address_postal']]) ?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <? else: ?>

            <? if (!$thePax->isNewRecord) { ?>

            <p>
                <? if ($thePax['status'] == 'canceled') { ?>
                <?= Html::a('Un-cancel pax booking', '?action=uncancel&pax='.$thePax['id']) ?>
                <? } else { ?>
                <?= Html::a('Cancel pax booking', '?action=cancel&pax='.$thePax['id']) ?>
                <? } ?>
                |
                <?= Html::a('Delete pax info', '?action=delete&pax='.$thePax['id'], ['class'=>'text-danger']) ?>
            </p>
            <? } ?>

            <? $form = ActiveForm::begin(); ?>
            <? if (1 == USER_ID) { ?>
            <fieldset>
                <legend><?= Yii::t('tour_pax', 'Possibly related contacts') ?></legend>
                <p>Click link below to link this new pax with existing contact:</p>
                <ul>
                    <?
                    foreach ($theTour['bookings'] as $booking) {
                        foreach ($booking['case']['people'] as $contact) {
                    ?>
                    <li><?= Html::a($contact['name'], '?action=link&contact='.$contact['id']) ?> <?= $contact['email'] ?></li>
                    <?
                        }
                    }
                    ?>
                </ul>
            </fieldset>
            <? } ?>

            <fieldset>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theForm, 'name')->label(Yii::t('tour_pax', 'Name of customer')) ?></div>
                    <div class="col-md-3"><?=$form->field($theForm, 'is_repeating')->label(Yii::t('tour_pax', 'Returning customer?'))->dropdownList(['no'=>'No', 'yes'=>'Yes']) ?></div>
                    <div class="col-md-3" id="div_pp_file" style="<?= $theForm->is_repeating != 'yes' ? 'display:none' : '' ?>"><?= $form->field($theForm, 'previous_tour')->label(Yii::t('tour_pax', 'Previous tour')) ?></div>
                </div>
            </fieldset>

            <fieldset>
                <legend><?= Yii::t('tour_pax', 'Passport information') ?></legend>
                <p><strong><?= Yii::t('tour_pax', 'Type each field exactly as appears in the passport. Leave passport number blank when passport is not available.') ?></strong></p>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($theForm, 'pp_country_code')->dropdownList(ArrayHelper::map($countryList, 'code', 'name_en'), ['prompt'=>Yii::t('tour_pax', '- Select -')])->label(Yii::t('tour_pax', 'Passport issuing country')) ?></div>
                    <div class="col-md-3"><?= $form->field($theForm, 'pp_number')->label(Yii::t('tour_pax', 'Passport number')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($theForm, 'pp_name')->label(Yii::t('tour_pax', 'First name(s)')) ?></div>
                    <div class="col-md-6"><?= $form->field($theForm, 'pp_name2')->label(Yii::t('tour_pax', 'Second name(s)')) ?></div>
                </div>

                <div class="row">
                    <div class="col-md-3"><?= $form->field($theForm, 'pp_gender')->dropdownList($genderList, ['prompt'=>Yii::t('tour_pax', '- Select -')])->label(Yii::t('tour_pax', 'Gender')) ?></div>
                    <div class="col-md-6 col-md-offset-3">
                        <div class="form-group">
                            <label class="control-label"><?= Yii::t('tour_pax', 'Date of birth (day/month/year)') ?></label>
                            <div class="row">
                                <div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'pp_bday')->label(false) ?></div>
                                <div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'pp_bmonth')->label(false) ?></div>
                                <div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'pp_byear')->label(false) ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"><?= Yii::t('tour_pax', 'Passport issue date (day/month/year)') ?></label>
                            <div class="row">
                                <div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'pp_iday')->label(false) ?></div>
                                <div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'pp_imonth')->label(false) ?></div>
                                <div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'pp_iyear')->label(false) ?></div>
                            </div>            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"><?= Yii::t('tour_pax', 'Passport expiry date (day/month/year)') ?></label>
                            <div class="row">
                                <div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'pp_eday')->label(false) ?></div>
                                <div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'pp_emonth')->label(false) ?></div>
                                <div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'pp_eyear')->label(false) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend><?= Yii::t('tour_pax', 'Contact information') ?></legend>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theForm, 'tel') ?></div>
                    <div class="col-md-6"><?=$form->field($theForm, 'tel2') ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theForm, 'email') ?></div>
                    <div class="col-md-6"><?=$form->field($theForm, 'email2') ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theForm, 'email3') ?></div>
                    <div class="col-md-6"><?=$form->field($theForm, 'website')->label('Website / Facebook...') ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theForm, 'profession') ?></div>
                    <div class="col-md-6"><?=$form->field($theForm, 'place_of_birth') ?></div>
                </div>
                <?=$form->field($theForm, 'address')->label(Yii::t('tour_pax', 'Address')) ?>
                <div class="row">
                    <div class="col-md-3"><?= $form->field($theForm, 'address_city_state')->label(Yii::t('tour_pax', 'City/State')) ?></div>
                    <div class="col-md-3"><?= $form->field($theForm, 'address_postal')->label(Yii::t('tour_pax', 'Postal')) ?></div>
                    <div class="col-md-6"><?= $form->field($theForm, 'address_country_code')->dropdownList(ArrayHelper::map($countryList, 'code', 'name_en'), ['prompt'=>Yii::t('tour_pax', '- Select -')])->label(Yii::t('tour_pax', 'Country')) ?></div>
                </div>
            </fieldset>
            <fieldset>
                <legend><?= Yii::t('tour_pax', 'Other notes' ) ?></legend>
                <?= $form->field($theForm, 'note')->textArea(['rows'=>5])->label(Yii::t('tour_pax', 'Health conditions, meals and other special requests')) ?>
            </fieldset>
            <div><?= Html::submitButton(Yii::t('app', 'Submit'), ['class'=>'btn btn-primary']) ?></div>
            <? ActiveForm::end() ?>

            <? endif; // if theForm ?>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading"><?= Yii::t('tour_pax', 'Danh sách đã nhập') ?></div>
        <? if (empty($theTour['pax'])) { ?>
        <div class="panel-body text-danger">
            <?= Yii::t('app', 'No data found.') ?>
        </div>
        <? } ?>
        <div class="table-responsive">
            <table class="table table-condensed table-narrow">
                <tbody>
                    <?
                    $cnt = 0;
                    foreach ($theTour['bookings'] as $booking) {
                    ?>
                    <tr>
                        <th colspan="2"><i class="fa fa-briefcase"></i> <?= $booking['case']['name'] ?></th>
                        <td width="10" class="no-padding-left"><?= Html::a('<i class="fa fa-plus"></i>', '?action=add&booking='.$booking['id'], ['title'=>Yii::t('tour_pax', 'Add new pax info')]) ?></td>
                    </tr>
                    <?
                        foreach ($theTour['pax'] as $pax) {
                            if ($pax['booking_id'] == $booking['id']) {
                                $cnt ++;
                                $pax['data'] = unserialize($pax['data']);
                    ?>
                    <tr>
                        <td class="text-center text-muted"><?= $cnt ?></td>
                        <td>
                            <i class="fa fa-<?= $pax['data']['pp_gender'] ?? '' ?>"></i>
                            <span class="flag-icon flag-icon-<?= $pax['data']['pp_country_code'] ?? '' ?>"></span>
                            <?= Html::a($pax['name'], '?action=view&pax='.$pax['id']) ?>
                            <em><?= date('Y') - $pax['data']['pp_byear'] ?? 0 ?></em>
                            <? if ($pax['status'] == 'canceled') { ?><span class="text-danger">CXL</span><? } ?>
                        </td>
                        <td class="text-muted"><?= Html::a('<i class="fa fa-edit"></i>', '?action=edit&pax='.$pax['id'], ['title'=>Yii::t('tour_pax', 'Edit pax info')]) ?></td>
                    </tr>
                    <?
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading"><?= Yii::t('t', 'Pax registration') ?></div>
        <div class="panel-body">
            <div class="text-info">
                Coming soon: pax registration data will be displayed here and user will be able to select to import the data instead of having to type.
            </div> 
        </div>
    </div>
</div>
<? endif; ?>