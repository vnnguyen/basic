<?
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

include('_tours_inc.php');

if (!isset($for)) {
    $for = '';
}

if ($theTourOld['status'] == 'draft') {
    $this->title = 'Xác nhận mở tour mới';
    $this->params['breadcrumb'] = [
        ['Tour operation', '#'],
        ['Tours', 'tours'],
        [substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
        ['New tour', 'tours/r/'.$theTourOld['id']],
        ['Accept', URI],
    ];
} else {
    $this->title = 'Edit tour: '.$theTour['op_code'];
    $this->params['breadcrumb'] = [
        ['Tour operation', '#'],
        ['Tours', 'tours'],
        [substr($theTour['day_from'], 0, 7), 'tours?month='.substr($theTour['day_from'], 0, 7)],
        [$theTour['op_code'], 'tours/r/'.$theTourOld['id']],
        ['Edit', URI],
    ];
}

$alsoList = [
    'ngoc.pk@amicatravel.com'=>'Ngọc PK (tour Miền Nam VN)',
    'ngo.hang@amicatravel.com'=>'Hằng NT (tour B2C, tour Laos)',
    'prim.bunthol@amicatravel.com'=>'Bunthol P (tour Cambodia)',
    'ha.nvk@amicatravel.com'=>'Khang Hạ (QHKH)',
];

?>
<div class="col-md-8">
    <? $form = ActiveForm::begin(); ?>
    <? if ($for == '') { ?>
    <div class="row">
        <div class="col-md-6"><?= $form->field($theForm, 'op_code') ?></div>
        <div class="col-md-6"><?= $form->field($theForm, 'op_name') ?></div>
    </div>
    <div class="row">
        <div class="col-md-6"><?= $form->field($theForm, 'client_ref')->label('Code hãng nếu là tour hãng') ?></div>
        <div class="col-md-6"><?= $form->field($theForm, 'owner')->dropdownList(ArrayHelper::map($operatorList, 'user_id', 'name')) ?></div>
    </div>
    <? } ?>
    <?= $form->field($theForm, 'operators')->checkboxList(ArrayHelper::map($operatorList, 'user_id', 'name')) ?>
    <div><?= Html::submitButton('Submit', ['class'=>'btn btn-primary']) ?></div>
    <? ActiveForm::end(); ?>
</div>
<div class="col-md-4">
    <p><strong>Click to notify these people:</strong></p>
    <? if (in_array(USER_ID, [1, 118, 8162])) { ?>
    <? foreach ($alsoList as $email=>$name) { ?>
    <p><button data-tour="<?= $theTourOld['id'] ?>" data-email="<?= $email ?>" class="notify-also btn btn-default btn-block"><i class="fa fa-envelope"></i> <?= $name ?></button></p>
    <? } ?>
    <? } ?>
</div>
<style type="text/css">
#touracceptform-operators label {display:block;}
</style>
<?
$js = <<<'TXT'
$('button.notify-also.btn-default').click(function(){
    if ($(this).data('email') == 'ngoc.pk@amicatravel.com') {
        if (!confirm('Thực sự muốn báo tour cho chị Kim Ngọc?')) {
            return false;
        }
    }

    var btn = $(this);
    var email = $(this).data('email');
    var tour = $(this).data('tour');
    $(this).addClass('disabled')
    var jqxhr = $.ajax({
        method: 'post',
        url: '/tours/accept/' + tour,
        data: {
            action: 'also',
            email: email,
        }
    })
    .done(function() {
        btn.removeClass('btn-default').addClass('btn-primary');
    })
    .fail(function() {
        btn.removeClass('disabled')
        alert( "Error: could not notify " + email );
    })
    .always(function() {
        // alert( "complete" );
    });
});

TXT;

$this->registerJs($js);