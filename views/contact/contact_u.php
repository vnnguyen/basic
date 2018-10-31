<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include '_contact_inc.php';
include '_inc__huanhn_pax-form.php';

Yii::$app->params['page_title']  = Yii::t('x', SEG2 == 'c' ? 'New contact' : 'Edit contact data: ' . $theContact['name']);
Yii::$app->params['page_layout'] = '-t';

$frenchDepartments = [
    ['code' => '01', 'department' => 'Ain', 'name' => '01 Ain', 'region' => 'Auvergne-Rhône-Alpes'],
    ['code' => '02', 'department' => 'Aisne', 'name' => '02 Aisne', 'region' => 'Hauts-de-France'],
    ['code' => '03', 'department' => 'Allier', 'name' => '03 Allier', 'region' => 'Auvergne-Rhône-Alpes'],
    ['code' => '04', 'department' => 'Alpes-de-Haute-Provence', 'name' => '04 Alpes-de-Haute-Provence', 'region' => 'Provence-Alpes-Côte d\'Azur'],
    ['code' => '05', 'department' => 'Hautes-Alpes', 'name' => '05 Hautes-Alpes', 'region' => 'Provence-Alpes-Côte d\'Azur'],
    ['code' => '06', 'department' => 'Alpes-Maritimes', 'name' => '06 Alpes-Maritimes', 'region' => 'Provence-Alpes-Côte d\'Azur'],
    ['code' => '07', 'department' => 'Ardèche', 'name' => '07 Ardèche', 'region' => 'Auvergne-Rhône-Alpes'],
    ['code' => '08', 'department' => 'Ardennes', 'name' => '08 Ardennes', 'region' => 'Grand Est'],
    ['code' => '09', 'department' => 'Ariège', 'name' => '09 Ariège', 'region' => 'Occitanie'],
    ['code' => '10', 'department' => 'Aube', 'name' => '10 Aube', 'region' => 'Grand Est'],
    ['code' => '11', 'department' => 'Aude', 'name' => '11 Aude', 'region' => 'Occitanie'],
    ['code' => '12', 'department' => 'Aveyron', 'name' => '12 Aveyron', 'region' => 'Occitanie'],
    ['code' => '13', 'department' => 'Bouches-du-Rhône', 'name' => '13 Bouches-du-Rhône', 'region' => 'Provence-Alpes-Côte d\'Azur'],
    ['code' => '14', 'department' => 'Calvados', 'name' => '14 Calvados', 'region' => 'Normandy'],
    ['code' => '15', 'department' => 'Cantal', 'name' => '15 Cantal', 'region' => 'Auvergne-Rhône-Alpes'],
    ['code' => '16', 'department' => 'Charente', 'name' => '16 Charente', 'region' => 'Nouvelle-Aquitaine'],
    ['code' => '17', 'department' => 'Charente-Maritime', 'name' => '17 Charente-Maritime', 'region' => 'Nouvelle-Aquitaine'],
    ['code' => '18', 'department' => 'Cher', 'name' => '18 Cher', 'region' => 'Centre-Val de Loire'],
    ['code' => '19', 'department' => 'Corrèze', 'name' => '19 Corrèze', 'region' => 'Nouvelle-Aquitaine'],
    ['code' => '2A', 'department' => 'Corse-du-Sud', 'name' => '2A Corse-du-Sud', 'region' => 'Corsica'],
    ['code' => '2B', 'department' => 'Haute-Corse', 'name' => '2B Haute-Corse', 'region' => 'Corsica'],
    ['code' => '21', 'department' => 'Côte-d\'Or', 'name' => '21 Côte-d\'Or', 'region' => 'Bourgogne-Franche-Comté'],
    ['code' => '22', 'department' => 'Côtes-d\'Armor', 'name' => '22 Côtes-d\'Armor', 'region' => 'Brittany Brittany'],
    ['code' => '23', 'department' => 'Creuse', 'name' => '23 Creuse', 'region' => 'Nouvelle-Aquitaine'],
    ['code' => '24', 'department' => 'Dordogne', 'name' => '24 Dordogne', 'region' => 'Nouvelle-Aquitaine'],
    ['code' => '25', 'department' => 'Doubs', 'name' => '25 Doubs', 'region' => 'Bourgogne-Franche-Comté'],
    ['code' => '26', 'department' => 'Drôme', 'name' => '26 Drôme', 'region' => 'Auvergne-Rhône-Alpes'],
    ['code' => '27', 'department' => 'Eure', 'name' => '27 Eure', 'region' => 'Normandy'],
    ['code' => '28', 'department' => 'Eure-et-Loir', 'name' => '28 Eure-et-Loir', 'region' => 'Centre-Val de Loire'],
    ['code' => '29', 'department' => 'Finistère', 'name' => '29 Finistère', 'region' => 'Brittany Brittany'],
    ['code' => '30', 'department' => 'Gard', 'name' => '30 Gard', 'region' => 'Occitanie'],
    ['code' => '31', 'department' => 'Haute-Garonne', 'name' => '31 Haute-Garonne', 'region' => 'Occitanie'],
    ['code' => '32', 'department' => 'Gers', 'name' => '32 Gers', 'region' => 'Occitanie'],
    ['code' => '33', 'department' => 'Gironde', 'name' => '33 Gironde', 'region' => 'Nouvelle-Aquitaine'],
    ['code' => '34', 'department' => 'Hérault', 'name' => '34 Hérault', 'region' => 'Occitanie'],
    ['code' => '35', 'department' => 'Ille-et-Vilaine', 'name' => '35 Ille-et-Vilaine', 'region' => 'Brittany Brittany'],
    ['code' => '36', 'department' => 'Indre', 'name' => '36 Indre', 'region' => 'Centre-Val de Loire'],
    ['code' => '37', 'department' => 'Indre-et-Loire', 'name' => '37 Indre-et-Loire', 'region' => 'Centre-Val de Loire'],
    ['code' => '38', 'department' => 'Isère', 'name' => '38 Isère', 'region' => 'Auvergne-Rhône-Alpes'],
    ['code' => '39', 'department' => 'Jura', 'name' => '39 Jura', 'region' => 'Bourgogne-Franche-Comté'],
    ['code' => '40', 'department' => 'Landes', 'name' => '40 Landes', 'region' => 'Nouvelle-Aquitaine'],
    ['code' => '41', 'department' => 'Loir-et-Cher', 'name' => '41 Loir-et-Cher', 'region' => 'Centre-Val de Loire'],
    ['code' => '42', 'department' => 'Loire', 'name' => '42 Loire', 'region' => 'Auvergne-Rhône-Alpes'],
    ['code' => '43', 'department' => 'Haute-Loire', 'name' => '43 Haute-Loire', 'region' => 'Auvergne-Rhône-Alpes'],
    ['code' => '44', 'department' => 'Loire-Atlantique', 'name' => '44 Loire-Atlantique', 'region' => 'Pays de la Loire'],
    ['code' => '45', 'department' => 'Loiret', 'name' => '45 Loiret', 'region' => 'Centre-Val de Loire'],
    ['code' => '46', 'department' => 'Lot', 'name' => '46 Lot', 'region' => 'Occitanie'],
    ['code' => '47', 'department' => 'Lot-et-Garonne', 'name' => '47 Lot-et-Garonne', 'region' => 'Nouvelle-Aquitaine'],
    ['code' => '48', 'department' => 'Lozère', 'name' => '48 Lozère', 'region' => 'Occitanie'],
    ['code' => '49', 'department' => 'Maine-et-Loire', 'name' => '49 Maine-et-Loire', 'region' => 'Pays de la Loire'],
    ['code' => '50', 'department' => 'Manche', 'name' => '50 Manche', 'region' => 'Normandy'],
    ['code' => '51', 'department' => 'Marne', 'name' => '51 Marne', 'region' => 'Grand Est'],
    ['code' => '52', 'department' => 'Haute-Marne', 'name' => '52 Haute-Marne', 'region' => 'Grand Est'],
    ['code' => '53', 'department' => 'Mayenne', 'name' => '53 Mayenne', 'region' => 'Pays de la Loire'],
    ['code' => '54', 'department' => 'Meurthe-et-Moselle', 'name' => '54 Meurthe-et-Moselle', 'region' => 'Grand Est'],
    ['code' => '55', 'department' => 'Meuse', 'name' => '55 Meuse', 'region' => 'Grand Est'],
    ['code' => '56', 'department' => 'Morbihan', 'name' => '56 Morbihan', 'region' => 'Brittany Brittany'],
    ['code' => '57', 'department' => 'Moselle', 'name' => '57 Moselle', 'region' => 'Grand Est'],
    ['code' => '58', 'department' => 'Nièvre', 'name' => '58 Nièvre', 'region' => 'Bourgogne-Franche-Comté'],
    ['code' => '59', 'department' => 'Nord', 'name' => '59 Nord', 'region' => 'Hauts-de-France'],
    ['code' => '60', 'department' => 'Oise', 'name' => '60 Oise', 'region' => 'Hauts-de-France'],
    ['code' => '61', 'department' => 'Orne', 'name' => '61 Orne', 'region' => 'Normandy'],
    ['code' => '62', 'department' => 'Pas-de-Calais', 'name' => '62 Pas-de-Calais', 'region' => 'Hauts-de-France'],
    ['code' => '63', 'department' => 'Puy-de-Dôme', 'name' => '63 Puy-de-Dôme', 'region' => 'Auvergne-Rhône-Alpes'],
    ['code' => '64', 'department' => 'Pyrénées-Atlantiques', 'name' => '64 Pyrénées-Atlantiques', 'region' => 'Nouvelle-Aquitaine'],
    ['code' => '65', 'department' => 'Hautes-Pyrénées', 'name' => '65 Hautes-Pyrénées', 'region' => 'Occitanie'],
    ['code' => '66', 'department' => 'Pyrénées-Orientales', 'name' => '66 Pyrénées-Orientales', 'region' => 'Occitanie'],
    ['code' => '67', 'department' => 'Bas-Rhin', 'name' => '67 Bas-Rhin', 'region' => 'Grand Est'],
    ['code' => '68', 'department' => 'Haut-Rhin', 'name' => '68 Haut-Rhin', 'region' => 'Grand Est'],
    ['code' => '69', 'department' => 'Rhône', 'name' => '69 Rhône', 'region' => 'Auvergne-Rhône-Alpes'],
    ['code' => '69M', 'department' => 'Metropolitan Lyon', 'name' => '69M Metropolitan Lyon', 'region' => 'Auvergne-Rhône-Alpes'],
    ['code' => '70', 'department' => 'Haute-Saône', 'name' => '70 Haute-Saône', 'region' => 'Bourgogne-Franche-Comté'],
    ['code' => '71', 'department' => 'Saône-et-Loire', 'name' => '71 Saône-et-Loire', 'region' => 'Bourgogne-Franche-Comté'],
    ['code' => '72', 'department' => 'Sarthe', 'name' => '72 Sarthe', 'region' => 'Pays de la Loire'],
    ['code' => '73', 'department' => 'Savoie', 'name' => '73 Savoie', 'region' => 'Auvergne-Rhône-Alpes'],
    ['code' => '74', 'department' => 'Haute-Savoie', 'name' => '74 Haute-Savoie', 'region' => 'Auvergne-Rhône-Alpes'],
    ['code' => '75', 'department' => 'Paris', 'name' => '75 Paris', 'region' => 'Île-de-France'],
    ['code' => '76', 'department' => 'Seine-Maritime', 'name' => '76 Seine-Maritime', 'region' => 'Normandy'],
    ['code' => '77', 'department' => 'Seine-et-Marne', 'name' => '77 Seine-et-Marne', 'region' => 'Île-de-France'],
    ['code' => '78', 'department' => 'Yvelines', 'name' => '78 Yvelines', 'region' => 'Île-de-France'],
    ['code' => '79', 'department' => 'Deux-Sèvres', 'name' => '79 Deux-Sèvres', 'region' => 'Nouvelle-Aquitaine'],
    ['code' => '80', 'department' => 'Somme', 'name' => '80 Somme', 'region' => 'Hauts-de-France'],
    ['code' => '81', 'department' => 'Tarn', 'name' => '81 Tarn', 'region' => 'Occitanie'],
    ['code' => '82', 'department' => 'Tarn-et-Garonne', 'name' => '82 Tarn-et-Garonne', 'region' => 'Occitanie'],
    ['code' => '83', 'department' => 'Var', 'name' => '83 Var', 'region' => 'Provence-Alpes-Côte d\'Azur'],
    ['code' => '84', 'department' => 'Vaucluse', 'name' => '84 Vaucluse', 'region' => 'Provence-Alpes-Côte d\'Azur'],
    ['code' => '85', 'department' => 'Vendée', 'name' => '85 Vendée', 'region' => 'Pays de la Loire'],
    ['code' => '86', 'department' => 'Vienne', 'name' => '86 Vienne', 'region' => 'Nouvelle-Aquitaine'],
    ['code' => '87', 'department' => 'Haute-Vienne', 'name' => '87 Haute-Vienne', 'region' => 'Nouvelle-Aquitaine'],
    ['code' => '88', 'department' => 'Vosges', 'name' => '88 Vosges', 'region' => 'Grand Est'],
    ['code' => '89', 'department' => 'Yonne', 'name' => '89 Yonne', 'region' => 'Bourgogne-Franche-Comté'],
    ['code' => '90', 'department' => 'Territoire de Belfort', 'name' => '90 Territoire de Belfort', 'region' => 'Bourgogne-Franche-Comté'],
    ['code' => '91', 'department' => 'Essonne', 'name' => '91 Essonne', 'region' => 'Île-de-France'],
    ['code' => '92', 'department' => 'Hauts-de-Seine', 'name' => '92 Hauts-de-Seine', 'region' => 'Île-de-France'],
    ['code' => '93', 'department' => 'Seine-Saint-Denis', 'name' => '93 Seine-Saint-Denis', 'region' => 'Île-de-France'],
    ['code' => '94', 'department' => 'Val-de-Marne', 'name' => '94 Val-de-Marne', 'region' => 'Île-de-France'],
    ['code' => '95', 'department' => 'Val-d\'Oise', 'name' => '95 Val-d\'Oise', 'region' => 'Île-de-France'],
    ['code' => '971', 'department' => 'Guadeloupe', 'name' => '971 Guadeloupe', 'region' => 'Guadeloupe'],
    ['code' => '972', 'department' => 'Martinique', 'name' => '972 Martinique', 'region' => 'Martinique'],
    ['code' => '973', 'department' => 'Guyane', 'name' => '973 Guyane', 'region' => 'French Guiana'],
    ['code' => '974', 'department' => 'La Réunion', 'name' => '974 La Réunion', 'region' => 'Réunion'],
    ['code' => '976', 'department' => 'Mayotte', 'name' => '976 Mayotte', 'region' => 'Mayotte'],
];
?></div><?php
$form = ActiveForm::begin();
?>
<style>
input:focus, select:focus, textarea:focus {background-color:#eef}
a.action_add_meta:focus {background-color:#eef}
.row.meta {padding-right:20px;}
.row.meta, .mb6 {margin-bottom:6px;}
.action_remove_meta {position:absolute; right:0; margin:10px 20px 0 0}
.intl-tel-input {width: 100%;}
a.action_add_meta {color:brown;}
#contacteditform-traveler_profile label {min-width:48%;}
#contacteditform-travel_preferences label {min-width:48%;}
#contacteditform-diet label {min-width:48%;}
#contacteditform-health_condition label {min-width:48%;}
#contacteditform-likes label {min-width:48%;}
#contacteditform-dislikes label {min-width:48%;}
.panel-heading li.active h6 {color:brown; font-weight:bold;}
legend {font-weight:500; text-transform:none; border:0; font-size:16px; padding-bottom:0;}
.select2-container {width:100%!important;}
.non_last_select {display: none;}
</style>
<div class="row">
<div class="col-md-8">
    <?php if ($theBooking) {?>
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?=Yii::t('x', 'NOTE: This contact will be added to booking {booking}', ['booking' => Html::a($theBooking['product']['op_code'] . ' - ' . $theBooking['product']['op_name'], '/products/op/' . $theBooking['product']['id'], ['class' => 'alert-link'])])?></div>
    <?php } elseif ($theCase) {?>
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?=Yii::t('x', 'NOTE: This contact will be added to case {name}', ['name' => Html::a($theCase['name'], '/cases/r/' . $theCase['id'], ['class' => 'alert-link'])])?></div>
    <?php }?>
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="card-title"><i class="fa fa-circle-o"></i> <?=Yii::t('x', 'Information')?></h6>
        </div>
        <div class="card-body">
            <fieldset>
                <legend><?=Yii::t('x', 'Personal information')?> (<a href="#" class="action_reorder_name">Change name order</a> - <a href="#" class="action_update_name">Update display name</a>)</legend>
                <div class="row">
                    <div class="col-md-3"><?=$form->field($theForm, 'fname')->label(Yii::t('x', 'Surname'))?></div>
                    <div class="col-md-3"><?=$form->field($theForm, 'lname')->label(Yii::t('x', 'Given name(s)'))?></div>
                    <div class="col-md-6"><?=$form->field($theForm, 'name')->label(Yii::t('x', 'Display name'))?></div>
                </div>
                <div class="row">
                    <div class="col-md-3"><?=$form->field($theForm, 'gender')->dropdownList($genderList, ['prompt' => Yii::t('app', '- Select -')])->label(Yii::t('x', 'Gender'))?></div>
                    <div class="col-md-6 offset-md-3">
                        <div class="form-group">
                            <label class="control-label"><?=Yii::t('x', 'Date of birth (day/month/year)')?></label>
                            <div class="row">
                                <div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'bday', ['inputOptions' => ['class' => 'form-control', 'type' => 'number', 'min' => 1, 'max' => 31, 'step' => 1]])->label(false)?></div>
                                <div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'bmonth', ['inputOptions' => ['class' => 'form-control', 'type' => 'number', 'min' => 1, 'max' => 12, 'step' => 1]])->label(false)?></div>
                                <div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'byear', ['inputOptions' => ['class' => 'form-control', 'type' => 'number', 'min' => 1900, 'max' => date('Y'), 'step' => 1]])->label(false)?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theForm, 'pob')->label(Yii::t('x', 'Place of birth'))?></div>
                    <div class="col-md-6"><?=$form->field($theForm, 'pob_country')->dropdownList(ArrayHelper::map($countryList, 'code', 'name'), ['prompt' => Yii::t('app', '- Select -')])->label(Yii::t('x', 'Country of birth'))?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theForm, 'country_code')->dropdownList(ArrayHelper::map($countryList, 'code', 'name'), ['prompt' => Yii::t('app', '- Select -')])->label(Yii::t('x', 'Nationality'))?></div>
                    <div class="col-md-3"><?=$form->field($theForm, 'language')->dropdownList($languageList, ['prompt' => Yii::t('app', '- Select -')])->label(Yii::t('x', 'Primary language'))?></div>
                    <div class="col-md-3"><?=$form->field($theForm, 'marital')->dropdownList($maritalStatusList, ['prompt' => Yii::t('app', '- Select -')])->label(Yii::t('x', 'Marital status'))?></div>
                </div>
            </fieldset>

            <fieldset>
                <legend><?=Yii::t('x', 'Profession')?></legend>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theForm, 'profession')->label(Yii::t('x', 'Profession'))?></div>
                    <div class="col-md-6"><?=$form->field($theForm, 'employer')->label(Yii::t('x', 'Employer'))?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?//= $form->field($theForm, 'job_title')->label(Yii::t('x', 'Job title')) ?></div>
                    <div class="col-md-6"><?//= $form->field($theForm, 'test')->dropdownList([])->label(Yii::t('x', 'Yearly income (USD, approx)')) ?></div>
                </div>
            </fieldset>

            <fieldset>
                <legend><?=Yii::t('x', 'Contact information')?></legend>
                <div id="list_tel">
                    <?php foreach ($data['tel'] as $item) {?>
                    <div class="row meta data-tel">
                        <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
                        <div class="col-sm-3">
                            <?=Html::dropdownList('name[]', $item['name'], $dataTelList, ['class' => 'form-control'])?>
                        </div>
                        <div class="col-sm-6">
                            <?=Html::textInput('value[]', $item['value'], ['class' => 'form-control', 'placeholder' => 'Value'])?>
                            <?=Html::hiddenInput('full[]', $item['full'])?>
                        </div>
                        <div class="col-sm-3">
                            <?=Html::textInput('note[]', $item['note'], ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Note')])?>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <p><a class="action_add_meta" data-meta="tel" href="#">+<?=Yii::t('x', 'Telephone/fax number')?></a></p>

                <div id="list_email">
                    <?php foreach ($data['email'] as $item) {?>
                    <div class="row meta data-email">
                        <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
                        <div class="col-sm-3">
                            <?=Html::dropdownList('name[]', $item['name'], $dataEmailList, ['class' => 'form-control'])?>
                        </div>
                        <div class="col-sm-6">
                            <?=Html::textInput('value[]', $item['value'], ['class' => 'form-control', 'placeholder' => 'Value'])?>
                        </div>
                        <div class="col-sm-3">
                            <?=Html::textInput('note[]', $item['note'], ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Note')])?>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <p><a class="action_add_meta" data-meta="email" href="#">+<?=Yii::t('x', 'Email address')?></a></p>

                <div id="list_url">
                    <?php foreach ($data['url'] as $item) {?>
                    <div class="row meta data-url">
                        <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
                        <div class="col-sm-3">
                            <?=Html::dropdownList('name[]', $item['name'], $dataUrlList, ['class' => 'form-control'])?>
                        </div>
                        <div class="col-sm-6">
                            <?=Html::textInput('value[]', $item['value'], ['class' => 'form-control', 'placeholder' => 'Value'])?>
                        </div>
                        <div class="col-sm-3">
                            <?=Html::textInput('note[]', $item['note'], ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Note')])?>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <p><a class="action_add_meta" data-meta="url" href="#">+<?=Yii::t('x', 'Website/Link')?></a></p>

                <div id="list_addr">
                    <?php foreach ($data['addr'] as $item) {?>
                    <div class="row meta data-addr">
                        <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
                        <div class="col-sm-3">
                            <?=Html::dropdownList('name[]', $item['name'], $dataAddrList, ['class' => 'form-control'])?>
                        </div>
                        <div class="col-sm-9">
                            <div class="mb6"><?=Html::textInput('addr_line_1[]', $item['addr_line_1'], ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Address line 1')])?></div>
                            <div class="mb6"><?=Html::textInput('addr_line_2[]', $item['addr_line_2'], ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Address line 2')])?></div>
                            <div class="row mb6">
                                <div class="col-sm-6"><?=Html::textInput('addr_city[]', $item['addr_city'], ['class' => 'form-control', 'placeholder' => Yii::t('x', 'City/Province')])?></div>
                                <div class="col-sm-6"><?=Html::textInput('addr_state[]', $item['addr_state'], ['class' => 'form-control', 'placeholder' => Yii::t('x', 'State/Region')])?></div>
                            </div>
                            <div class="row mb6">
                                <div class="col-sm-4"><?=Html::textInput('addr_postal[]', $item['addr_postal'], ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Postal code')])?></div>
                                <div class="col-sm-8"><?=Html::dropdownList('addr_country[]', $item['addr_country'], ArrayHelper::map($countryList, 'code', 'name'), ['class' => 'form-control', 'prompt' => Yii::t('app', '- Select -')])?></div>
                            </div>
                            <div><?=Html::textInput('note[]', $item['note'], ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Note')])?></div>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <p><a class="action_add_meta" data-meta="addr" href="#">+<?=Yii::t('x', 'Address')?></a></p>
            </fieldset>

            <fieldset>
                <legend><?=Yii::t('x', 'Passports')?></legend>
                <div id="list_passport">
                    <?php foreach ($data['passport'] as $item) {?>
                    <div class="row meta data-passport">
                        <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
                        <div class="col-sm-3">
                            <?=Html::dropdownList('name[]', $item['name'], $dataPassportList, ['class' => 'form-control'])?>
                        </div>
                        <div class="col-sm-9">
                            <div class="row mb6">
                                <div class="col-sm-6"><?=Html::dropdownList('pp_country_code[]', $item['pp_country_code'], ArrayHelper::map($countryList, 'code', 'name'), ['class' => 'form-control', 'prompt' => Yii::t('app', 'Passport issuing country')])?></div>
                                <div class="col-sm-3"><?=Html::textInput('pp_number[]', $item['pp_number'], ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Passport number')])?></div>
                            </div>
                            <div class="row mb6">
                                <div class="col-sm-6"><?=Html::textInput('pp_name1[]', $item['pp_name1'], ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Surname')])?></div>
                                <div class="col-sm-6"><?=Html::textInput('pp_name2[]', $item['pp_name2'], ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Given name(s)')])?></div>
                            </div>
                            <div class="row mb6">
                                <div class="col-sm-3"><?=Html::dropdownList('pp_gender[]', $item['pp_gender'], $genderList, ['class' => 'form-control', 'prompt' => Yii::t('x', 'Gender')])?></div>
                                <div class="col-sm-3"><?=Html::textInput('pp_bdate[]', $item['pp_bdate'], ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Date of birth')])?></div>
                                <div class="col-sm-3"><?=Html::textInput('pp_idate[]', $item['pp_idate'], ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Date of issue')])?></div>
                                <div class="col-sm-3"><?=Html::textInput('pp_edate[]', $item['pp_edate'], ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Date of expiry')])?></div>
                            </div>
                            <div><?=Html::textInput('note[]', $item['note'], ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Note')])?></div>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <p><a class="action_add_meta" data-meta="passport" href="#">+<?=Yii::t('x', 'Passport')?></a></p>
            </fieldset>

            <fieldset>
                <legend><?=Yii::t('x', 'Connections')?></legend>
                <div id="list_rel">
                    <?php foreach ($data['connection'] as $item) {?>
                    <div class="row meta data-rel">
                        <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
                        <div class="col-sm-3">
                            <input type="hidden" name="name[]" value="connection">
                            <?=Html::dropdownList('rel[]', $item['rel'], $dataRelList, ['class' => 'form-control', 'prompt' => '- Select -'])?>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control relto" name="relto[]" value="<?=$item['relto']?>" placeholder="<?=Yii::t('x', 'Type an ID, name or email')?>">
                        </div>
                        <div class="col-sm-3">
                            <?//= Html::textInput('note[]', '', ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Note')]) ?>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <p><a class="action_add_meta" data-meta="rel" href="#">+<?=Yii::t('x', 'Connection')?></a></p>
            </fieldset>
        </div>
    </div>

    <?php if (USER_ID == 1 && $profile == 'tourguide' && $theProfile) {?>
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="card-title"><?=Yii::t('x', 'Tour guide profile')?></h6>
        </div>
        <div class="card-body">
            <?php include '_contact_u__tourguide.php';?>
        </div>
    </div>
    <?php }?>

    <?php if (1 || $profile == '') {
    ?>
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="card-title"><i class="fa fa-circle-o"></i> <?=Yii::t('x', 'Traveler profile')?></h6>
        </div>
        <div class="card-body">
            <p><label><input type="checkbox" data-content="" name="" value=""> Giống với trưởng đoàn</label></p>
            <fieldset>
                <legend><?=Yii::t('x', 'Traveler profile')?></legend>
                <div class="row">
                    <div class="col-md-12"><?=$form->field($theForm, 'traveler_profile', ['enableClientValidation' => false])->checkboxList($customerProfileList, ['multiple' => 'multiple'])->label(Yii::t('x', 'Traveler profile'))?></div>
                </div>
                <div class="row">
                    <div class="col-md-12"><?=$form->field($theForm, 'traveler_profile_assoc_names')->label(Yii::t('x', 'Name(s) of association(s) this contact belongs to (comma separated)'))?></div>
                </div>
                <div class="row">
                    <div class="col-md-12"><?=$form->field($theForm, 'travel_preferences', ['enableClientValidation' => false])->checkboxList($travelPrefList, ['multiple' => 'multiple'])->label(Yii::t('x', 'Travel preferences'))?></div>
                </div>
                <div class="row">
                    <div class="col-md-12"><?=$form->field($theForm, 'diet', ['enableClientValidation' => false])->checkboxList($dietList, ['multiple' => 'multiple'])->label(Yii::t('x', 'Diet'))?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theForm, 'allergies')->label(Yii::t('x', 'Specify allergies'))?></div>
                    <div class="col-md-6"><?=$form->field($theForm, 'diet_note')->label(Yii::t('x', 'Other notes about diet'))?></div>
                </div>
                <div class="row">
                    <div class="col-md-12"><?=$form->field($theForm, 'health_condition', ['enableClientValidation' => false])->checkboxList($healthList, ['multiple' => 'multiple'])->label(Yii::t('x', 'Health condition'))?></div>
                </div>
                <div class="row">
                    <div class="col-md-12"><?=$form->field($theForm, 'health_note')->label(Yii::t('x', 'Other note about health condition'))?></div>
                </div>
                <div class="row">
                    <div class="col-md-12"><?=$form->field($theForm, 'transportation', ['enableClientValidation' => false])->checkboxList($transportationList, ['multiple' => 'multiple'])->label(Yii::t('x', 'Transportation'))?></div>
                </div>
                <div class="row">
                    <div class="col-md-12"><?=$form->field($theForm, 'transportation_note')->label(Yii::t('x', 'Other note about transportation'))?></div>
                </div>
                <div class="row">
                    <div class="col-md-12 has-select2"><?=$form->field($theForm, 'future_travel_wishlist', ['enableClientValidation' => false])->dropdownList(ArrayHelper::map($countryList, 'code', 'name'), ['multiple' => 'multiple'])->label(Yii::t('x', 'Future travel wish list'))?></div>
                </div>
                <legend><?=Yii::t('x', 'Likes & Dislikes')?></legend>
                <div class="row">
                    <label class="col-md-12">Likes</label>
                    <div class="col-md-3">
                        <?=$form->field($theForm, 'likes', ['enableClientValidation' => false])->checkboxList($likeList['Culture'], [
        'id'          => '',
        'multiple'    => 'multiple',
        'separator'   => '<br>',
        'itemOptions' => [
            'class' => 'check',
        ],
    ])->label('<label><input type="checkbox" class="likeAll"> <strong>' . Yii::t("x", "Culture") . '</strong></label>');
    ?>
                    </div>

                    <div class="col-md-3">
                        <?=$form->field($theForm, 'likes', ['enableClientValidation' => false])->checkboxList($likeList['Relax'], [
        'id'          => '',
        'multiple'    => 'multiple',
        'separator'   => '<br>',
        'itemOptions' => [
            'class' => 'check',
        ],
    ])->label('<label><input type="checkbox" class="likeAll"> <strong>' . Yii::t("x", "Relax") . '</strong></label>');
    ?>
                    </div>
                    <div class="col-md-3">
                        <?=$form->field($theForm, 'likes', ['enableClientValidation' => false])->checkboxList($likeList['Autres'], [
        'id'          => '',
        'multiple'    => 'multiple',
        'separator'   => '<br>',
        'itemOptions' => [
            'class' => 'check',
        ],
    ])->label('<label><input type="checkbox" class="likeAll"> <strong>' . Yii::t("x", "Autres") . '</strong></label>');
    ?>
                    </div>
                    <div class="col-md-3">
                        <?=$form->field($theForm, 'likes', ['enableClientValidation' => false])->checkboxList($likeList['Sportif'], [
        'id'          => '',
        'multiple'    => 'multiple',
        'separator'   => '<br>',
        'itemOptions' => [
            'class' => 'check',
        ],
    ])->label('<label><input type="checkbox" class="likeAll"> <strong>' . Yii::t("x", "Sportif") . '</strong></label>');
    ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"><?=$form->field($theForm, 'dislikes', ['enableClientValidation' => false])->checkboxList($dislikeList, ['multiple' => 'multiple'])->label(Yii::t('x', 'Dislikes'))?></div>
                </div>
            </fieldset>
            <fieldset>
                <legend><?=Yii::t('x', 'Loyalty program')?></legend>
                <div class="row">
                    <div class="col-md-12"><?=$form->field($theForm, 'rel_with_amica')->textArea(['rows' => 5])->label(Yii::t('x', 'Relationship with Amica'))?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theForm, 'ambassaddor_potentiality')->dropdownList($newCustomerTypeList, ['prompt' => Yii::t('x', '- Select -')])->label(Yii::t('x', 'Customer type'))?></div>
                    <div class="col-md-6" style="display:none;"><?=$form->field($theForm, 'customer_ranking')->label(Yii::t('x', 'Note about customer type'))?></div>

                </div>
                <div class="row col-md-2"><button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#f_modal">
                      POINTS
                    </button></div>

            </fieldset>
            <fieldset>
                <legend><?=Yii::t('x', 'Marketing')?></legend>
                <div class="row">
                    <div class="col-md-12"><?=$form->field($theForm, 'newsletter_optin')->checkbox(['label' => Yii::t('x', 'This contact does not want to receive newsletters from Amica Travel')], true)->label(false)?></div>
                </div>
            </fieldset>
        </div>
    </div>
    <?php }?>

<?php

// var_dump($this->context->html_question($arr_q['title_1']));die;
?>
    <div class="card">
        <div class="card-header bg-white">
            <h6 class="card-title"><i class="fa fa-circle-o"></i> <?=Yii::t('x', 'Other note about this contact')?></h6>
        </div>
        <div class="card-body">
            <fieldset>
                <div class="row">
                    <div class="col-md-12"><?=$form->field($theForm, 'info')->textArea(['rows' => 5])->label(false)?></div>
                </div>
            </fieldset>
        </div>
    </div>

    <?php if (!$theContact->isNewRecord && $theContact->updatedBy) {?>
    <p class="text-muted"><i class="fa fa-info-circle"></i> <?=Yii::t('x', 'Last update {time} by {user}.', ['time' => Yii::$app->formatter->asRelativetime($theContact->updated_at), 'user' => $theContact->updatedBy->name])?></p>
    <?php }?>
    <?=Html::submitButton(Yii::t('x', 'Save changes'), ['class' => 'btn btn-primary'])?>
    <?=Yii::t('x', 'or')?>
    <?=Html::a(Yii::t('x', 'Cancel'), '#')?>

</div>
<div class="col-md-4">
    <div class="">
        <label class="form-control-label"><?=Yii::t('x', 'Avatar')?></label>
        <div class="slim -thumb -thumb-rounded -thumb-slide"
             data-service="/assets/slim_1.1.1/server/async.php"
             -data-fetcher="/assets/slim_1.1.1/server/fetch.php"
             data-ratio="1:1"
             data-min-size="250,250"
             data-push="true"
             data-max-file-size="2">
            <?php if ($theContact->image != '') {?>
            <img src="<?=$theContact->image?>" alt="Avatar">
            <?php }?>
            <input type="file" name="slim[]"/>
        </div>
    </div>
</div>
<?php ActiveForm::end();?>
</div><!-- .row --><div>
<div id="data_templates" style="display:none;">

    <div class="row meta data-rel">
        <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
        <div class="col-sm-3">
            <input type="hidden" name="name[]" value="connection">
            <?=Html::dropdownList('rel[]', '', $dataRelList, ['class' => 'form-control', 'prompt' => '- Select -'])?>
        </div>
        <div class="col-sm-6">
            <input type="text" class="form-control relto" name="relto[]" value="" placeholder="<?=Yii::t('x', 'Type an ID, name or email')?>">
        </div>
        <div class="col-sm-3">
            <?//= Html::textInput('note[]', '', ['class'=>'form-control', 'placeholder'=>Yii::t('x', 'Note')]) ?>
        </div>
    </div>

    <div class="row meta data-tel">
        <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
        <div class="col-sm-3">
            <?=Html::dropdownList('name[]', '', $dataTelList, ['class' => 'form-control'])?>
        </div>
        <div class="col-sm-6">
            <?=Html::textInput('value[]', '', ['class' => 'form-control', 'placeholder' => 'Value'])?>
            <?=Html::hiddenInput('full[]', '')?>
        </div>
        <div class="col-sm-3">
            <?=Html::textInput('note[]', '', ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Note')])?>
        </div>
    </div>
    <div class="row meta data-email">
        <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
        <div class="col-sm-3">
            <?=Html::dropdownList('name[]', '', $dataEmailList, ['class' => 'form-control'])?>
        </div>
        <div class="col-sm-6">
            <?=Html::textInput('value[]', '', ['class' => 'form-control', 'placeholder' => 'Value'])?>
        </div>
        <div class="col-sm-3">
            <?=Html::textInput('note[]', '', ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Note')])?>
        </div>
    </div>
    <div class="row meta data-url">
        <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
        <div class="col-sm-3">
            <?=Html::dropdownList('name[]', '', $dataUrlList, ['class' => 'form-control'])?>
        </div>
        <div class="col-sm-6">
            <?=Html::textInput('value[]', '', ['class' => 'form-control', 'placeholder' => 'Value'])?>
        </div>
        <div class="col-sm-3">
            <?=Html::textInput('note[]', '', ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Note')])?>
        </div>
    </div>

    <div class="row meta data-addr">
        <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
        <div class="col-sm-3">
            <?=Html::dropdownList('name[]', '', $dataAddrList, ['class' => 'form-control'])?>
        </div>
        <div class="col-sm-9">
            <div class="mb6"><?=Html::textInput('addr_line_1[]', '', ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Address line 1')])?></div>
            <div class="mb6"><?=Html::textInput('addr_line_2[]', '', ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Address line 2')])?></div>
            <div class="row mb6">
                <div class="col-sm-6"><?=Html::textInput('addr_city[]', '', ['class' => 'form-control', 'placeholder' => Yii::t('x', 'City/Province')])?></div>
                <div class="col-sm-6"><?=Html::textInput('addr_state[]', '', ['class' => 'form-control', 'placeholder' => Yii::t('x', 'State/Region')])?></div>
            </div>
            <div class="row mb6">
                <div class="col-sm-4"><?=Html::textInput('addr_postal[]', '', ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Postal code')])?></div>
                <div class="col-sm-8"><?=Html::dropdownList('addr_country[]', '', ArrayHelper::map($countryList, 'code', 'name'), ['class' => 'form-control', 'prompt' => Yii::t('app', '- Select -')])?></div>
            </div>
            <div><?=Html::textInput('note[]', '', ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Note')])?></div>
        </div>
    </div>

    <div class="row meta data-passport">
        <i class="fa fa-trash-o text-danger cursor-pointer action_remove_meta"></i>
        <div class="col-sm-3">
            <?=Html::dropdownList('name[]', '', $dataPassportList, ['class' => 'form-control'])?>
        </div>
        <div class="col-sm-9">
            <div class="row mb6">
                <div class="col-sm-6"><?=Html::dropdownList('pp_country_code[]', '', ArrayHelper::map($countryList, 'code', 'name'), ['class' => 'form-control', 'prompt' => Yii::t('app', 'Passport issuing country')])?></div>
                <div class="col-sm-3"><?=Html::textInput('pp_number[]', '', ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Passport number')])?></div>
            </div>
            <div class="row mb6">
                <div class="col-sm-6"><?=Html::textInput('pp_name1[]', '', ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Surname')])?></div>
                <div class="col-sm-6"><?=Html::textInput('pp_name2[]', '', ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Given name(s)')])?></div>
            </div>
            <div class="row mb6">
                <div class="col-sm-3"><?=Html::dropdownList('pp_gender[]', '', $genderList, ['class' => 'form-control', 'prompt' => Yii::t('x', 'Gender')])?></div>
                <div class="col-sm-3"><?=Html::textInput('pp_bdate[]', '', ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Date of birth')])?></div>
                <div class="col-sm-3"><?=Html::textInput('pp_idate[]', '', ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Date of issue')])?></div>
                <div class="col-sm-3"><?=Html::textInput('pp_edate[]', '', ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Date of expiry')])?></div>
            </div>
            <div><?=Html::textInput('note[]', '', ['class' => 'form-control', 'placeholder' => Yii::t('x', 'Note')])?></div>
        </div>
    </div>

</div>
<!-- The Modal -->
<style>
    #f_modal label {padding: 5px; display: block; background: #f8f9fa}
</style>
<div class="modal fade" id="f_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"> POINTS </h4>
                <button class="close" data-dismiss="modal" type="button"> × </button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="d-flex flex-wrap">
                <?php foreach ($arr_q as $title => $options) { ?>
                    <div class="card col-md-6">
                        <div class="card-body">
                            <p style="font-weight: bold"><?= $title?></p>
                            <?php
                                $cnt = 0;
                                if ($title == '1. Khách đã từng đi với Amica Travel?') {
                                    $sql = 'SELECT user_id FROM at_referrals WHERE user_id =:user_id';
                                    $user_refs = Yii::$app->db->createCommand($sql, [
                                            ':user_id'=> $theContact['id']
                                        ])->queryAll();
                                    if ($user_refs) {
                                        $cnt = count($user_refs);
                                    }

                                }
                                if ($title == '5. Số lần giới thiệu khách') {
                                    $sql = 'SELECT user_id FROM at_booking_user WHERE user_id =:user_id';
                                    $user_tours = Yii::$app->db->createCommand($sql, [
                                            ':user_id'=> $theContact['id']
                                        ])->queryAll();
                                    if ($user_tours) {
                                        $cnt = count($user_tours);
                                    }
                                }
                             ?>
                            <?= $this->context->html_question($options, $cnt)?>
                        </div>
                    </div>
                <?php } ?>

                    <div class="card col-md-6">
                        <div class="card-body">
                            <p style="font-weight: bold"><?= 'Tổng điểm'?></p>
                            <div class="col-md-12">
                                <label >
                                    <input type="text" id="totals" class="form-control" readonly value="">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button class="btn btn-danger" data-dismiss="modal" type="button">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<script>
var departments = [
    <?php foreach ($frenchDepartments as $cnt => $department) {?>
    <?=$cnt == 0 ? '' : ','?>{name: "<?=$department['department']?>", value: "<?=$department['name']?> (<?=$department['region']?>)"}
    <?php }?>
];
</script>
<?php

$js = <<<'TXT'
$('.action_reorder_name').on('click', function(e){
    e.preventDefault()
    var n1 = $('#contacteditform-fname').val()
    var n2 = $('#contacteditform-lname').val()
    $('#contacteditform-fname').val(n2)
    $('#contacteditform-lname').val(n1)
})
$('.action_update_name').on('click', function(e){
    e.preventDefault()
    var n1 = $('#contacteditform-fname').val()
    var n2 = $('#contacteditform-lname').val()
    var nat = $('#contacteditform-country_code').val()
    if (nat == 'vn' || nat == 'la' || nat == 'kh') {
        $('#contacteditform-name').val(n1 + ' ' + n2)
    } else {
        $('#contacteditform-name').val(n2 + ' ' + n1)
    }
})


// Connections
$('.action_add_rel').on('click', function(e){
    e.preventDefault()
    $('.row.rel:last').clone(true, true).insertAfter($('.row.rel:last')).find(':input').val('')
    $('.row.rel:last').find(':input:first').focus()
})
$('.action_remove_rel').on('click', function(e){
    if (!confirm('Delete this?')) {
        return false;
    }
    $(this).closest('div.row').remove()
})

$('body').on('focus', '.relto', function(){
    $(this).autocomplete({
        serviceUrl: '/contacts/ajax?action=relto',
        // appendTo: $('#xxx'),
        // forceFixPosition: true,
        onSelect: function(suggestion){
            $(this).val(suggestion.name)
        }
    })
});

// Tab toggle
$('.list-tabs li h6').on('click', function(){
    var target = $(this).data('target')
    $('.list-tabs li').removeClass('active')
    $(this).parent().addClass('active')
    $('.tab-content').hide()
    $('.tab-content' + target).show()
})

// Copy data to passport
$('#copy-to-pp').on('click', function(){
    if ($('#contacteditform-country_code').val() != '') {
        $('#contacteditform-pp_country_code').val($('#contacteditform-country_code').val());
    }
    if ($('#contacteditform-fname').val() != '') {
        $('#contacteditform-pp_name').val($('#contacteditform-fname').val());
    }
    if ($('#contacteditform-lname').val() != '') {
        $('#contacteditform-pp_name2').val($('#contacteditform-lname').val());
    }
    if ($('#contacteditform-gender').val() != '') {
        $('#contacteditform-pp_gender').val($('#contacteditform-gender').val());
    }
    if ($('#contacteditform-bday').val() != '') {
        $('#contacteditform-pp_bday').val($('#contacteditform-bday').val());
    }
    if ($('#contacteditform-bmonth').val() != '') {
        $('#contacteditform-pp_bmonth').val($('#contacteditform-bmonth').val());
    }
    if ($('#contacteditform-byear').val() != '') {
        $('#contacteditform-pp_byear').val($('#contacteditform-byear').val());
    }
    return false;
});


$('.has-select2 select').select2()
$(document).on('click', '.action_add_meta', function(e){
    e.preventDefault()
    var meta = $(this).data('meta')
    $('#data_templates .row.data-' + meta + ':first').clone(true, true).appendTo($('#list_' + meta)).find(':input:first').focus()
    if (meta == 'tel') {
        $('#list_tel .row.meta.data-tel:last').find('input:eq(0)').intlTelInput({
            initialCountry: $('#contacteditform-country_code').val() || 'fr',
            preferredCountries: ['vn', 'fr', 'be', 'ca', 'us', 'ch', 'de', 'gb'],
            utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/js/utils.js',
        })
    }
    if (meta == 'passport') {
        $('#list_passport .row.meta.data-passport:last')
            .find('[name="pp_country_code[]"]').val($('#contacteditform-country_code').val()).end()
            .find('[name="pp_name1[]"]').val($('#contacteditform-fname').val()).end()
            .find('[name="pp_name2[]"]').val($('#contacteditform-lname').val()).end()
            .find('[name="pp_gender[]"]').val($('#contacteditform-gender').val()).end()
            .find('[name="pp_bdate[]"]').val($('#contacteditform-bday').val() + '/' + $('#contacteditform-bmonth').val() + '/' + $('#contacteditform-byear').val()).end()
            .find('[name="pp_number[]"]').focus()
    }
})

$(document).on('click', '.action_remove_meta', function(e){
    if (!confirm('Delete item?')) {
        return false
    }
    $(this).closest('.row.meta').remove()
})

$('#list_tel .row.meta.data-tel input[name="value[]"]').intlTelInput({
    initialCountry: $('#contacteditform-country_code').val() || 'fr',
    preferredCountries: ['fr', 'be', 'ca', 'vn', 'ch', 'de', 'us', 'gb'],
    utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/js/utils.js',
});

$('#w0').on('submit',function(){
    $('#list_tel .row.meta.data-tel input[name="value[]"]').each(function(i){
        var full = $(this).intlTelInput('getNumber')
        $('input[name="full[]"]:eq(' + i + ')').val(full)
    })
})

// French departments
$('body').on('focus', ':input[name="addr_state[]"]', function(){
    $(this).autocomplete({
        lookup: departments,
        onSelect: function(suggestion){
            $(this).val(suggestion.name)
            $(this).closest('.col-sm-9').find(':input[name="addr_postal[]"]').focus()
        }
    })
});

// Auto create name from fname+lname
$('#contacteditform-name').on('focus', function(){
    var nat = $('#contacteditform-country_code').val()
    if ($(this).val() == '') {
        if (nat == 'vn' || nat == 'la' || nat == 'kh') {
            $(this).val($('#contacteditform-fname').val() + ' ' + $('#contacteditform-lname').val())
        } else {
            $(this).val($('#contacteditform-lname').val() + ' ' + $('#contacteditform-fname').val())
        }
    }
})

// Auto passe date and add 10 years to passport expiry date
$(document).on('blur', 'input[name="pp_idate[]"]', function(){
    var str = $(this).val();
    var edate = $(this).closest('.row').find(':input[name="pp_edate[]"]')
    if (str.length > 0 && edate.val() == '' && str.match(/(0?[1-9]|1[0-9]|2[0-9]|3[01]).(0?[1-9]|1[012]).[0-9]{4}|(0[1-9]|1[0-9]|2[0-9]|3[01])(0[1-9]|1[012])[0-9]{4}/g)){
    // if (str.length > 0 && str.match(/(0?[1-9]|1[0-9]|2[0-9]|3[01]).(0?[1-9]|1[012]).[0-9]{4}|(0[1-9]|1[0-9]|2[0-9]|3[01])(0[1-9]|1[012])[0-9]{4}/g)){
        if (str.includes(" ") || str.includes("-") || str.includes(".")){
            str = str.replace(/[ -.]/g, '/');
        } else {
            str = str.replace(/(\d{2})(\d{2})(\d{4})/, "$1/$2/$3");
        }
        str = str.split('/');
        var mydate = new Date(str[1]+"/"+str[0]+"/"+str[2]);

        var ph = moment(mydate);
        $(this).val(ph.format('D/M/YYYY'));

        var hh = ph.add(10, 'year').subtract(1, 'days');
        edate.val(hh.format('D/M/YYYY'));
    }
});




//nguyen
$('input[type="checkbox"]').on('click',function(){
    point_f();
});
$('input[type="radio"]').on('click',function(){
    var parent_root = $(this).closest('.parent_class');
    var parent = $(this).closest('div:not(.group)');
    var group = $(this).closest('.group');

    $(parent).find('input[type="radio"]').prop('checked', false);
    $(parent).find('input[type="checkbox"]').prop('checked', false);
    $(parent).find('.non_last_select').hide();
    $(this).prop('checked', true);
    var firs_class = $(group).find('.non_last_select')[0];
    $(group).find('.disable').prop('checked', true);
    $(firs_class).show();
    point_f();
});
$('.likeAll').on('change', function(){
    var clicked = $(this).prop('checked');
    var parent = $(this).closest('div');
    $(parent).find('.check').prop('checked', clicked);
});

point_f();
function point_f()
{
    $('.disable').attr('disabled', 'disabled');
    var total_points = 0;
    $('input[type="radio"], input[type="checkbox"]').each(function(index, item){

        if($(item).prop('checked') && $(item).val() != ''){
            if($(item).val() == 'blacklist'){
                total_points = 'blacklist';
                return false;
            } else {
                total_points += parseInt($(item).val());
            }
        }
    });
    var arr_type = {
        "0-5": "Không tiềm năng",
        "6-10": "Tiếp cận được",
        "11-20": "Tiềm năng lớn",
        "21-25": "Ampo"
    };
    var text_type = '';

    if(total_points != 'blacklist') {
        $.each(arr_type, function(rang, type){
            console.log(rang.split('-'));
            var arr_r = rang.split('-');
            if(total_points >= parseInt(arr_r[0]) && total_points <= parseInt(arr_r[1])) {
                text_type = type;
                return;
            }
        });
    }
    if(text_type != '') {
        text_type = ' => [' + text_type +']';
    }
    $('#totals').val('');
    $('#totals').val(total_points + text_type);
}

TXT;
$this->registerJs($js);

$this->registerCssFile('/assets/slim_1.1.1/slim/slim.min.css', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('/assets/slim_1.1.1/slim/slim.kickstart.min.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('/assets/slim_1.1.1/slim/slim.jquery.min.js', ['depends' => 'yii\web\JqueryAsset']);

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.min.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/css/intlTelInput.css', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/js/intlTelInput.min.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery.devbridge-autocomplete/1.4.3/jquery.autocomplete.min.js', ['depends' => 'yii\web\JqueryAsset']);