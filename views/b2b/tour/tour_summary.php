<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\ActiveForm;

$contacts = [
    ['name'=>'Ms Hoa Hong Nhung', 'phone'=>'(0084) 9 1476 0390'],
    ['name'=>'Mr Nguyen Duc Anh', 'phone'=>'(0084) 9 0454 2880'],
    ['name'=>'Ms Ta Thi Thu Ha', 'phone'=>'(0084) 9 1620 4869'],
    ['name'=>'Ms Doan Thi Ha', 'phone'=>'(0084) 9 7642 8448'],
    ['name'=>'Mr Jonathan Morana', 'phone'=>'(0084) 12 2294 0707'],
    ['name'=>'Mr Tran Le Hieu', 'phone'=>'(0084) 9 1555 2294'],
    ['name'=>'Secret Indochina Office', 'phone'=>'(0084) 4 3266 9052'],
];

Yii::$app->params['page_title'] = 'Tour summary for agency - '.$theTour['op_code'];
Yii::$app->params['page_breadcrumbs'] = [
    ['Tour operations', 'tours'],

];

?>
<style>
.bg-grey-100 {background-color:#f3f3f3;}
.table-xxs th, .table-xxs td {padding:6px!important;}
.ui-state-highlight {background-color:#fffff3;}
</style>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
        <?php $form = ActiveForm::begin() ?>
        <fieldset>
            <legend><?= Yii::t('si_tour_summary', 'THE TOUR') ?></legend>
            <div class="row">
                <div class="col-md-4"><?= $form->field($theForm, 'tour_company') ?></div>
                <div class="col-md-4"><?= $form->field($theForm, 'tour_code') ?></div>
                <div class="col-md-4"><?= $form->field($theForm, 'tour_name') ?></div>
            </div>
            <?= $form->field($theForm, 'tour_note')->textArea(['rows'=>5]) ?>

            <legend><?= Yii::t('si_tour_summary', 'PROGRAM IN BRIEF') ?></legend>
            <table id="table_d" class="table table-xxs">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th width="200"><?= Yii::t('si_tour_summary', 'Date') ?></th>
                        <th><?= Yii::t('si_tour_summary', 'Itinerary') ?></th>
                        <th width="200"><?= Yii::t('si_tour_summary', 'Guide & Driver') ?></th>
                        <th width="100"><?= Yii::t('si_tour_summary', 'Meals') ?></th>
                        <th width="20"></th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    <tr style="display:none;">
                        <td><i class="text-muted fa fa-arrows-v cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Move up/down') ?>"></i></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="d_date[]" value=""></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="d_name[]" value=""></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="d_guides[]" value=""></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="d_meals[]" value=""></td>
                        <td><i class="text-danger fa fa-trash-o cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
                    </tr>
        <?php
        $dayIdList = explode(',', $theTour['day_ids']);

        $cnt = 0;
        foreach ($dayIdList as $di) {
            foreach ($theTour['days'] as $ng){
                if ($di == $ng['id']) {
                    $ngay = date('j/n/Y', strtotime(' + '.$cnt.' days', strtotime($theTour['day_from'])));
                    $cnt ++;
        ?>
                    <tr>
                        <td><i class="text-muted fa fa-arrows-v cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Move up/down') ?>"></i></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="d_date[]" value="<?= $ngay ?>"></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="d_name[]" value="<?= $ng['name'] ?>"></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="d_guides[]" value="<?= $ng['guides'] ?>"></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="d_meals[]" value="<?= $ng['meals'] ?>"></td>
                        <td><i class="text-danger fa fa-trash-o cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
                    </tr>
        <?php
                }
            }
        }
        ?>
                </tbody>
            </table>
            <p class="text-right"><a class="add" data-table="table_d" href="#">+ Add new</a></p>
        </fieldset>

        <fieldset>
            <legend><?= Yii::t('si_tour_summary', 'LIST OF TRAVELLERS') ?></legend>
            <table id="table_p" class="table table-xxs">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th width="100"><?= Yii::t('si_tour_summary', 'Title') ?></th>
                        <th><?= Yii::t('si_tour_summary', 'Full name') ?></th>
                        <th width="100"><?= Yii::t('si_tour_summary', 'Age') ?></th>
                        <th><?= Yii::t('si_tour_summary', 'Rooming') ?></th>
                        <th width="20"></th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    <tr style="display:none;">
                        <td><i class="text-muted fa fa-arrows-v cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Move up/down') ?>"></i></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="p_title[]" value=""></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="p_name[]" value=""></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="p_age[]" value=""></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="p_rooming[]" value=""></td>
                        <td><i class="text-danger fa fa-trash-o cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
                    </tr>
                    <?php
                    $cnt = 0;
                    foreach ($theTour['bookings'] as $booking) {
                        foreach ($booking['pax'] as $pax) {
                    ?>
                    <tr>
                        <td><i class="text-muted fa fa-arrows-v cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Move up/down') ?>"></i></td>
                        <? if ($theTour['language'] == 'fr') { ?>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="p_title[]" value="<?= $pax['gender'] == 'male' ? 'M.' : 'Mme.' ?>"></td>
                        <? } elseif ($theTour['language'] == 'en') { ?>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="p_title[]" value="<?= $pax['gender'] == 'male' ? 'Mr.' : 'Ms.' ?>"></td>
                        <? } else { ?>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="p_title[]" value="<?= $pax['gender'] == 'male' ? 'Ông' : 'Bà' ?>"></td>
                        <? } ?>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="p_name[]" value="<?= $pax['lname'] ?> <?= $pax['fname'] ?>"></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="p_age[]" value="<?= $pax['byear'] == 0 ? '' : date('Y') - $pax['byear'] ?>"></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="p_rooming[]" value=""></td>
                        <td><i class="text-danger fa fa-trash-o cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
                    </tr>
                    <?
                        }
                    }
                    ?>
                </tbody>
            </table>
            <p class="text-right"><a class="add" data-table="table_p" href="#">+ Add new</a></p>
        </fieldset>

        <fieldset>
            <legend><?= Yii::t('si_tour_summary', 'ACCOMMODATIONS') ?></legend>
            <table id="table_h" class="table table-xxs">
<? // Gia va cac options
$cnt = 0;
$ctpx = $theTour['prices'];
$ctpx = explode(chr(10), $ctpx);
$unitp = '';
$minp = 99999;
$maxp = 0;
$optcnt = 0;
foreach ($ctpx as $ctp) {
    if (substr($ctp, 0, 7) == 'OPTION:') {
        $optcnt ++;
/*
?>
        <tr class="b-ffc">
            <th colspan="4">Option <?= $optcnt.' : '.trim(substr($ctp, 7)) ?></th>
        </tr>
<?
*/
?>
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th><?= Yii::t('si_tour_summary', 'Destination') ?></th>
                        <th><?= Yii::t('si_tour_summary', 'Hotel/Resort & Website') ?></th>
                        <!-- th><?= Yii::t('si_tour_summary', 'No. of nights') ?></th -->
                        <th><?= Yii::t('si_tour_summary', 'Room type') ?></th>
                        <th><?= Yii::t('si_tour_summary', 'Website') ?></th>
                        <th width="20"></th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    <tr style="display:none;">
                        <td><i class="text-muted fa fa-arrows-v cursor-pointer move3" title="<?= Yii::t('si_tour_summary', 'Move up/down') ?>"></i></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="h_dest[]" value=""></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="h_name[]" value=""></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="h_room[]" value=""></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="h_url[]" value=""></td>
                        <td><i class="text-danger fa fa-trash-o cursor-pointer remove3" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
                    </tr>
<?
    }
    if (substr($ctp, 0, 2) == '+ ') {
        $line = trim(substr($ctp, 2));
        $line = explode(':', $line);
        for ($i = 0; $i < 4; $i ++) if (!isset($line[$i])) $line[$i] = '';
        $cnt ++;
?>
                    <tr>
                        <td><i class="text-muted fa fa-arrows-v cursor-pointer move3" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="h_dest[]" value="<?= trim($line[0]) ?>"></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="h_name[]" value="<?= trim($line[1]) ?>"></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="h_room[]" value="<?= trim($line[2]) ?>"></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="h_url[]" value="<?= trim($line[3]) == '' ? '' : 'http://'.str_replace('http://', '', trim($line[3])) ?>"></td>
                        <td><i class="text-danger fa fa-trash-o cursor-pointer remove3" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
                    </tr>
<?
    }
}
?>
                </tbody>
            </table>
            <p class="text-right"><a class="add" data-table="table3" href="#">+ Add new</a></p>
        </fieldset>

        <fieldset>
            <legend><?= Yii::t('si_tour_summary', 'FLIGHTS') ?></legend>
            <table id="table_f" class="table table-xxs">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th><?= Yii::t('si_tour_summary', 'Route') ?></th>
                        <th><?= Yii::t('si_tour_summary', 'Number') ?></th>
                        <th><?= Yii::t('si_tour_summary', 'Departure time') ?></th>
                        <th><?= Yii::t('si_tour_summary', 'Arrival time') ?></th>
                        <th width="20"></th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    <tr style="display:none;">
                        <td><i class="text-muted fa fa-arrows-v cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Move up/down') ?>"></i></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="f_route[]" value=""></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="f_number[]" value=""></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="f_departure[]" value=""></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="f_arrival[]" value=""></td>
                        <td><i class="text-danger fa fa-trash-o cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
                    </tr>
                </tbody>
            </table>
            <p class="text-right"><a class="add" data-table="table_f" href="#">+ Add new</a></p>
        </fieldset>
        <fieldset>
            <legend><?= Yii::t('si_tour_summary', 'TRAINS') ?></legend>
            <table id="table_t" class="table table-xxs">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th><?= Yii::t('si_tour_summary', 'Train route') ?></th>
                        <th><?= Yii::t('si_tour_summary', 'Number') ?></th>
                        <th><?= Yii::t('si_tour_summary', 'Departure time') ?></th>
                        <th><?= Yii::t('si_tour_summary', 'Arrival time') ?></th>
                        <th width="20"></th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    <tr style="display:none;">
                        <td><i class="text-muted fa fa-arrows-v cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Move up/down') ?>"></i></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="t_route[]" value=""></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="t_number[]" value=""></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="t_departure[]" value=""></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="t_arrival[]" value=""></td>
                        <td><i class="text-danger fa fa-trash-o cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
                    </tr>
                </tbody>
            </table>
            <p class="text-right"><a class="add" data-table="table_t" href="#">+ Add new</a></p>
        </fieldset>

        <fieldset>
            <legend><?= Yii::t('si_tour_summary', 'TOUR GUIDE DETAILS') ?></legend>
            <table id="table_g" class="table table-xxs">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th><?= Yii::t('si_tour_summary', 'Tour guide') ?></th>
                        <th><?= Yii::t('si_tour_summary', 'Contact number') ?></th>
                        <th><?= Yii::t('si_tour_summary', 'Service time') ?></th>
                        <th width="20"></th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    <tr style="display:none;">
                        <td><i class="text-muted fa fa-arrows-v cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Move up/down') ?>"></i></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="g_name[]" value=""></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="g_tel[]" value=""></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="g_time[]" value=""></td>
                        <td><i class="text-danger fa fa-trash-o cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
                    </tr>
                    <?
                    $cnt = 0;
                    foreach ($theTour['guides'] as $guide) {
                        $cnt ++;
                        if ($guide['guide_user_id'] == 0) {
                            $guideNameParts = explode('-', $guide['guide_name']);
                            $guideName = $guideNameParts[0] ?? $guide['guide_name'];
                            $guideTel = $guideNameParts[1] ?? '';
                        } else {
                            $guideName = $guide['guide']['fname'].' '.$guide['guide']['lname'];
                            $guideTel = $guide['guide']['phone'];
                        }
                        $guideName = Inflector::transliterate($guideName);
                        $dialCode = '(0084) ';
                        $guideTel = $dialCode.substr(trim($guideTel), 1);
                    ?>
                    <tr>
                        <td><i class="text-muted fa fa-arrows-v cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Move up/down') ?>"></i></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="g_name[]" value="<?= $guideName ?>"></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="g_tel[]" value="<?= $guideTel ?>"></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="g_time[]" value="<?= date('j/n/Y', strtotime($guide['use_from_dt'])) ?> - <?= date('j/n/Y', strtotime($guide['use_until_dt'])) ?>"></td>
                        <td><i class="text-danger fa fa-trash-o cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
                    </tr>
                    <?
                    }
                    ?>
                </tbody>
            </table>
            <p class="text-right"><a class="add" data-table="table_g" href="#">+ Add new</a></p>
        </fieldset>

        <fieldset>
            <legend><?= Yii::t('si_tour_summary', 'EMERGENCY CONTACTS') ?></legend>
            <table id="table_s" class="table table-xxs">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th><?= Yii::t('si_tour_summary', 'Person in charge') ?></th>
                        <th><?= Yii::t('si_tour_summary', 'Contact number') ?></th>
                        <th width="20"></th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    <tr style="display:none;">
                        <td><i class="text-muted fa fa-arrows-v cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="s_name[]" value=""></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="s_tel[]" value=""></td>
                        <td><i class="text-danger fa fa-trash-o cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
                    </tr>
                    <?
                    $cnt = 0;
                    foreach ($contacts as $contact) {
                        $cnt ++;
                    ?>
                    <tr>
                        <td><i class="text-muted fa fa-arrows-v cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="s_name[]" value="<?= $contact['name'] ?>"></td>
                        <td><input type="text" class="form-control no-border bg-grey-100" name="s_tel[]" value="<?= $contact['phone'] ?>"></td>
                        <td><i class="text-danger fa fa-trash-o cursor-pointer" title="<?= Yii::t('si_tour_summary', 'Remove') ?>"></i></td>
                    </tr>
                    <?
                    }
                    ?>
                </tbody>
            </table>
            <p class="text-right"><a class="add" data-table="table_s" href="#">+ Add new</a></p>
        </fieldset>

        <div class="text-right"><?= Html::submitButton(Yii::t('app', 'Download PDF file'), ['class'=>'btn btn-primary']) ?></div>
        <?php ActiveForm::end() ?>
        </div>
    </div>
</div>

<?php

$js = <<<'TXT'
$('tbody.sortable').sortable({
    axis: 'y',
    containment: "parent",
    handle: 'i.fa-arrows-v',
    helper: "clone",
    placeholder: 'ui-state-highlight'
});
$( "tbody.sortable" ).on( "click", "i.fa-trash-o", function() {
    $(this).parents('tr').fadeOut(200, function(){$(this).remove();});
});
$('a.add').on('click', function(){
    var tableid = $(this).data('table');
    $('table#'+tableid+' tbody tr:first').clone().appendTo('table#'+tableid+' tbody').show();
    return false;
});
TXT;

$this->registerJs($js);
$this->registerJsFile('https://code.jquery.com/ui/1.12.0/jquery-ui.js', ['depends'=>'yii\web\JqueryAsset']);