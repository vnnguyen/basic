<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

include('_cases_inc.php');

Yii::$app->params['page_title'] = 'Edit source: '.$theCase['name'];
Yii::$app->params['page_breadcrumb'][] = ['View', 'cases/r/'.$theCase['id']];
Yii::$app->params['page_breadcrumb'][] = ['Edit', 'cases/u/'.$theCase['id']];

$caseHowContactedListFormatted = [];
foreach ($caseHowContactedList as $k=>$v) {
    $cnt = count(explode('/', $k));
    $v = str_repeat(' --- ', $cnt - 1). $v;
    $caseHowContactedListFormatted[$k] = $v;
}


$kaseHowFoundListFormatted = [];
foreach ($kaseHowFoundList as $k=>$v) {
    $cnt = count(explode('/', $k));
    $v = str_repeat(' --- ', $cnt - 1). $v;
    $kaseHowFoundListFormatted[$k] = $v;
}

?>
<div class="col-md-8">
    <?php $form = ActiveForm::begin(); ?>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6"><?= $form->field($theCase, 'how_contacted')->dropdownList($caseHowContactedListFormatted, ['prompt'=>'- Select -'])->label('How customer contacted us & web referral') ?></div>
                <div class="col-md-6"><?= $form->field($theCase, 'web_keyword')->label('Search or ad keywords, or referrer link') ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theCase, '_kx')->dropdownList($kaseChannelList, ['prompt'=>Yii::t('x', '- Select -')])->label(Yii::t('x', 'Channel')) ?></div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-sm-8"><?= $form->field($theCase, '_kx_cost')->label(Yii::t('x', 'K- cost')) ?></div>
                        <div class="col-sm-4"><?= $form->field($theCase, '_kx_cost_currency')->dropdownList(['USD'=>'USD'])->label(Yii::t('x', 'Currency')) ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theCase, 'company_id')->dropdownList(ArrayHelper::map($companyList, 'id', 'name'), ['prompt'=>'- Select -'])->label('If via a company, what company') ?></div>
                <div class="col-md-6"><?= $form->field($theCase, 'campaign_id')->dropdownList(ArrayHelper::map($campaignList, 'id', 'name'), ['prompt'=>'( No campaigns )'])->label('Campaign name if related to a campaign') ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theCase, 'how_found')->dropdownList($kaseHowFoundListFormatted, ['prompt'=>'- Select -'])->label('How customer knew about us') ?></div>
                <div class="col-md-6"><?= $form->field($theCase, 'ref')->label('ID of referrer user if Word of mouth') ?></div>
            </div>
            <?= $form->field($theCase, 'info')->textArea(['rows'=>5]) ?>
            <div><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="col-md-4">
    <p><strong>Chỉ dẫn</strong></p>
    <ul>
        <li>Chỉ điền tên công ty tour khi How contacted = Via tour company</li>
        <li>Chỉ điền Referrer ID khi How found = Word of mouth</li>
        <li>Chỉ điền Web referral / Web keyword khi How contacted = Web</li>
        <li>Để trống nếu không biết thông tin</li>
    </ul>
</div>