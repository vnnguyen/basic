<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;

include('_inc__huanhn_pax-form.php');

Yii::$app->params['page_title'] = Yii::t('p', SEG2 == 'c' ? 'New person' : 'Edit person data: '.$thePerson['name']);
Yii::$app->params['page_layout'] = '-t';
Yii::$app->params['body_class'] = 'sidebar-xs';
Yii::$app->params['page_breadcrumbs'] = [
    ['People', 'persons'],
    SEG2 == 'c' ? null : [$thePerson['name'], 'persons/r/'.$thePerson['id']],
    [SEG2 == 'c2' ? 'New' : 'Edit'],
];



$form = ActiveForm::begin();
?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-body">
            <fieldset>
                <legend>Personal info</legend>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($theForm, 'name')->label(Yii::t('p', 'Display name')) ?></div>
                    <div class="col-md-6"><?= $form->field($theForm, 'nickname')->label(Yii::t('p', 'Nickname')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($theForm, 'fname')->label(Yii::t('p', 'Family name(s)')) ?></div>
                    <div class="col-md-6"><?= $form->field($theForm, 'lname')->label(Yii::t('p', 'Given name(s)')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-3"><?= $form->field($theForm, 'gender')->dropdownList($genderList, ['prompt'=>Yii::t('app', '- Select -')])->label(Yii::t('p', 'Gender')) ?></div>
                    <div class="col-md-6 col-md-offset-3">
                        <div class="form-group">
                            <label class="control-label"><?= Yii::t('p', 'Date of birth (day/month/year)') ?></label>
                            <div class="row">
                                <div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'bday', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>1, 'max'=>31, 'step'=>1]])->label(false) ?></div>
                                <div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'bmonth', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>1, 'max'=>12, 'step'=>1]])->label(false) ?></div>
                                <div class="col-md-4 col-xs-4"><?=$form->field($theForm, 'byear', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>1900, 'max'=>date('Y'), 'step'=>1]])->label(false) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($theForm, 'pob')->label(Yii::t('p', 'Place of birth')) ?></div>
                    <div class="col-md-6"><?= $form->field($theForm, 'pob_country')->dropdownList(ArrayHelper::map($countryList, 'code', 'name'), ['prompt'=>Yii::t('app', '- Select -')])->label(Yii::t('p', 'Country of birth')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($theForm, 'country_code')->dropdownList(ArrayHelper::map($countryList, 'code', 'name'), ['prompt'=>Yii::t('app', '- Select -')])->label(Yii::t('p', 'Nationality')) ?></div>
                    <div class="col-md-3"><?= $form->field($theForm, 'language')->dropdownList($languageList, ['prompt'=>Yii::t('app', '- Select -')])->label(Yii::t('p', 'Primary language')) ?></div>
                    <div class="col-md-3"><?= $form->field($theForm, 'marital')->dropdownList($maritalStatusList, ['prompt'=>Yii::t('app', '- Select -')])->label(Yii::t('p', 'Marital status')) ?></div>
                </div>
                <!--
                <div class="row">
                    <div class="col-md-6"><?= $form->field($theForm, 'test')->dropdownList($relationList, ['prompt'=>Yii::t('app', '- Select -')])->label(Yii::t('p', 'Type of relations')) ?></div>
                    <div class="col-md-6"><?= $form->field($theForm, 'test', ['inputOptions'=>['class'=>'form-control', 'type'=>'number', 'min'=>1, 'step'=>1]])->label(Yii::t('p', 'With (PERSON ID)')) ?></div>
                </div>
                -->
            </fieldset>

            <fieldset>
                <legend>Profession</legend>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($theForm, 'profession')->label(Yii::t('p', 'Profession')) ?></div>
                    <div class="col-md-6"><?= $form->field($theForm, 'job_title')->label(Yii::t('p', 'Job title')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($theForm, 'employer')->label(Yii::t('p', 'Employer')) ?></div>
                    <div class="col-md-6"><?//= $form->field($theForm, 'test')->dropdownList([])->label(Yii::t('p', 'Yearly income (USD, approx)')) ?></div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Contact information</legend>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theForm, 'tel')->label(Yii::t('p', 'Tel')) ?></div>
                    <div class="col-md-6"><?=$form->field($theForm, 'tel2')->label(Yii::t('p', 'Tel 2')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theForm, 'email')->label(Yii::t('p', 'Email')) ?></div>
                    <div class="col-md-6"><?=$form->field($theForm, 'email2')->label(Yii::t('p', 'Email 2')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theForm, 'email3')->label(Yii::t('p', 'Email 3')) ?></div>
                    <div class="col-md-6"><?=$form->field($theForm, 'email4')->label(Yii::t('p', 'Email 4')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theForm, 'website')->label(Yii::t('p', 'Website')) ?></div>
                    <div class="col-md-6"><?=$form->field($theForm, 'website2')->label(Yii::t('p', 'Website 2')) ?></div>
                </div>
                <legend>Address</legend>
                <div class="row">
                    <div class="col-md-12"><?=$form->field($theForm, 'addr_street')->label(Yii::t('p', 'Street/Building')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theForm, 'addr_city')->label(Yii::t('p', 'City/Province')) ?></div>
                    <div class="col-md-6"><?=$form->field($theForm, 'addr_state')->label(Yii::t('p', 'State/Region')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-3"><?=$form->field($theForm, 'addr_postal')->label(Yii::t('p', 'Postal code')) ?></div>
                    <div class="col-md-6 col-md-offset-3"><?= $form->field($theForm, 'addr_country')->dropdownList(ArrayHelper::map($countryList, 'code', 'name'), ['prompt'=>Yii::t('app', '- Select -')])->label(Yii::t('p', 'Country')) ?></div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Customer: Important information</legend>
                <div class="row">
                    <div class="col-md-12 has-select2"><?= $form->field($theForm, 'traveler_profile', ['enableClientValidation'=>false])->dropdownList($customerProfileList, ['multiple'=>'multiple'])->label(Yii::t('p', 'Traveler profile')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-12"><?=$form->field($theForm, 'traveler_profile_assoc_names')->label(Yii::t('p', 'Name(s) of association(s) this person belongs to (comma separated)')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-12 has-select2"><?= $form->field($theForm, 'travel_preferences', ['enableClientValidation'=>false])->dropdownList($travelPrefList, ['multiple'=>'multiple'])->label(Yii::t('p', 'Travel preferences')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-12 has-select2"><?= $form->field($theForm, 'diet', ['enableClientValidation'=>false])->dropdownList($dietList, ['multiple'=>'multiple'])->label(Yii::t('p', 'Diet')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?=$form->field($theForm, 'allergies')->label(Yii::t('p', 'Specify allergies')) ?></div>
                    <div class="col-md-6"><?=$form->field($theForm, 'diet_note')->label(Yii::t('p', 'Other notes about diet')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-12 has-select2"><?= $form->field($theForm, 'health_condition', ['enableClientValidation'=>false])->dropdownList($healthList, ['multiple'=>'multiple'])->label(Yii::t('p', 'Health condition')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-12"><?=$form->field($theForm, 'health_note')->label(Yii::t('p', 'Other note about health condition')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-12 has-select2"><?= $form->field($theForm, 'transportation', ['enableClientValidation'=>false])->dropdownList($transportationList, ['multiple'=>'multiple'])->label(Yii::t('p', 'Transportation')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-12"><?=$form->field($theForm, 'transportation_note')->label(Yii::t('p', 'Other note about transportaton')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-12 has-select2"><?= $form->field($theForm, 'future_travel_wishlist', ['enableClientValidation'=>false])->dropdownList(ArrayHelper::map($countryList, 'code', 'name'), ['multiple'=>'multiple'])->label(Yii::t('p', 'Future travel wish list')) ?></div>
                </div>
                <legend>Likes and dislikes</legend>
                <div class="row">
                    <div class="col-md-12 has-select2"><?= $form->field($theForm, 'likes', ['enableClientValidation'=>false])->dropdownList($likeList, ['multiple'=>'multiple'])->label(Yii::t('p', 'Likes')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-12 has-select2"><?= $form->field($theForm, 'dislikes', ['enableClientValidation'=>false])->dropdownList($dislikeList, ['multiple'=>'multiple'])->label(Yii::t('p', 'Dislikes')) ?></div>
                </div>
            </fieldset>
            <fieldset>
                <legend>Loyalty program</legend>
                <div class="row">
                    <div class="col-md-12"><?= $form->field($theForm, 'rel_with_amica')->textArea(['rows'=>5])->label(Yii::t('p', 'Relationship with Amica Travel')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-12"><?= $form->field($theForm, 'customer_ranking')->label(Yii::t('p', 'Customer type')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-12"><?= $form->field($theForm, 'ambassaddor_potentiality')->radioList(['Ampo', 'Amba'])->label(Yii::t('p', 'Ambassador potentiality')) ?></div>
                </div>

            </fieldset>
            <fieldset>
                <legend>Marketing</legend>
                <div class="row">
                    <div class="col-md-12"><?= $form->field($theForm, 'newsletter_optin')->checkbox(['label'=>Yii::t('p', 'Agreed to receive newsletter from Amica Travel')], true)->label(false) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-12"><?= $form->field($theForm, 'active_social_networks')->checkboxList(['facebook', 'linkedin', 'yahoo', 'tripadvisor'])->label(Yii::t('p', 'Presence on social networks')) ?></div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Other note about this person</legend>
                <div class="row">
                    <div class="col-md-12"><?= $form->field($theForm, 'info')->textArea(['rows'=>5])->label(false) ?></div>
                </div>
            </fieldset>
            <hr>
            <?= Html::submitButton(Yii::t('p', 'Save changes'), ['class'=>'btn btn-primary']) ?>
            <?= Yii::t('app', 'or') ?>
            <?= Html::a(Yii::t('app', 'Cancel'), '#') ?>
            
        </div>
    </div>
</div>
<?
ActiveForm::end();

$js = <<<'TXT'
$('.has-select2 select').select2()
TXT;
$this->registerJs($js);