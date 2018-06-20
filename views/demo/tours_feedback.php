<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = 'Tour feedback: '.$theTour['op_code'];

$this->params['breadcrumb'] = [
    ['Tour operation', '#'],
    ['Tours', 'tours'],
    [$theTour['op_code'], 'tours/r/'.$theTourOld['id']],
    ['Feedback', URI],
];

$say = [
    'smile'=>'Likes',
    'frown'=>'Dislikes',
    'meh'=>'Comments on',
];

$partCnt = 0;
$guide = 1;
$drivers = 1;
$printLogo = Yii::$app->params['print_logo'];
$printName = '';

$version = $versions['20072017'];
$action = Yii::$app->request->get('action', 'add');
?>
<style>
    body {font-size:13px; color:#222; font-family: Arial, "sans-serif"}
    em {font-style:italic;}
    strong {font-weight:bold;}
    h2 {color:#000; padding:0 0 5px; margin-bottom:16px; font:bold 14px/18px Arial; border-bottom:1px solid #999;font-family: Arial, "sans-serif"}
    .has-bg {width:100%;}
    div.has-bg {margin-bottom:32px;}
    .h-100 {height:100px;}
    .h-200 {height:200px;}
    .h-400 {height:400px;}
    table {width:100%; border-collapse:collapse;}
    table, td, th {border:1px solid #000;}
    td, th {border:1px solid #666; padding:5px;}
    th {font-weight:bold;}
    .ta-c {text-align:center;}
    p, table {margin-bottom:16px;}
    h1 {font:bold 20px/22px Arial;}
    div.wrap-table table { border: 0;}
    div.wrap-table table td {border: 0;}
    span.selected_icon {display:block; color: #0198A3;}
    .checkbox label{ display: inline-block; text-align: center; }
    .checker {background: #fff}
    table th { width: 10%; }
    table th.not_fix_width {max-width: 30%}
    .checkbox label .checker{ left: 45%; }
    tr.has-err { border: 1px solid #CD0037; box-shadow: 1px 0px 3px 1px#CD0037;}
</style>
<div class="col-md-9">
    <div style="width:260px; float:left; margin:0 32px 32px 0;">
        <img src="<?= $printLogo ?>" style="margin:0; display:block; padding:0; max-width:250px; max-height:168px;">
        <h1 style="font:bold 20px/22px Arial;">
            <span style="font-size:16px"><?=fUTF8::upper($printName)?></span>
            <br />QUESTIONNAIRE DE SATISFACTION
        </h1>
    </div>
    <p><strong>Madame, Monsieur,</strong></p>
    <p>L’équipe <strong><?= $printName ?></strong> vous souhaite la bienvenue !</p>
    <p>Nous tenons, tout d’abord, à vous remercier de votre confiance et de nous avoir choisis pour l’organisation de votre voyage.</p>
    <p>Afin d’améliorer constamment la qualité de nos prestations, nous vous serions reconnaissant de bien vouloir nous faire part de vos appréciations en répondant au questionnaire suivant.</p>
    <p>Vous remerciant pour votre participation, nous vous disons à très bientôt !</p>
    <table class="head-info" style="clear:both; margin:0;">
        <tr>
            <td width="275">Tour code: <strong><?= $theTour['op_code'] ?></strong> / ID <strong><?= $theTour['id'] ?></strong></td>
            <td width="">Votre nom et prénom: </strong></td>
        </tr>
    </table>
    <div style="">
        <form id="fbForm" method="POST" accept-charset="utf-8">

            <?php foreach ($version['questions'] as $num_q => $q){ ?>

                <h2>Partie <?= ++ $partCnt ?> :
                <?php
                    $q_title = '';
                    if ($num_q == 'q1') {
                        $q_title = 'REMARQUES SUR LES PRESTATIONS';
                    }
                    if ($num_q == 'q2') {
                        $q_title = 'REMARQUES SUR LES GUIDES';
                    }
                    if ($num_q == 'q3') {
                        $q_title = 'EVALUATION DU TRANSPORT ROUTIER';
                    }
                    if ($num_q == 'q4') {
                        $q_title = 'D\'AUTRES COMMENTAIRES, IMPRESSIONS SUR LE VOYAGE ET SUR AMICA TRAVEL';
                    }
                    echo $q_title;
                ?>
                </h2>
                <?php
                if ($num_q == 'q1' || $num_q == 'q4') {
                    echo $q['title'];
                }
                ?>
                <?php if ($num_q == 'q2') {?>
                    <div class="form-group">
                        <?= Html::label($q['note_q'], ['class' => 'control-label'])?>
                        <?= Html::textarea($num_q.'[note_q]', '', ['class' => 'form-control'])?>
                    </div>
                    <?
                    continue;
                }
                ?>
                <?php
                if ($num_q == 'q3') {?>
                    <div class="form-group">
                        <?= Html::label($q['note_q'], ['class' => 'control-label'])?>
                        <?= Html::textarea($num_q.'[note_q]', '', ['class' => 'form-control'])?>
                    </div>
                    <?
                    continue;
                }
                ?>
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="ta-c not_fix_width" width="25%"></th>
                            <?php foreach ($q['options_value'] as $op_v): ?>
                                <th class="ta-c" ><?= $op_v?></th>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <? foreach ($q['options'] as $index => $op) {
                        $arr_v = [];
                        $current_v = '';
                     ?>
                    <tr>
                        <td class="ta-c">
                            <?= Html::input('hidden', $num_q.'['.$op.']', $current_v, [])?>
                            <?= $op ?>
                        </td>
                        <?php for($i = 0; $i < count($q['options_value']); $i ++) {
                            $checked = '';
                            if (isset($arr_v[$index]) && $arr_v[$index] == $i){
                                    $checked = 'checked';
                            }
                        ?>
                        <td class="text-center selected"><div class="checkbox">
                            <label class="text-center">
                                <div class="checker border-info-600">
                                <span class="<?= $checked?>"><input class="control-info" type="checkbox"></span>
                                </div>
                            </label>
                        </div>
                        </td>
                        <?}?>
                    </tr>
                    <? } ?>
                </table> <br>
                <?php if ($q['note_q'] != ''): 
                    $note_q = '';
                ?>
                    <div class="form-group">
                        <?= Html::label($q['note_q'], ['class' => 'control-label'])?>
                    <?php if ($action != 'view'): ?>
                        <?= Html::textarea($num_q.'[note_q]', $note_q, ['class' => 'form-control'])?>
                    <?php endif ?>
                    </div>
                <?php endif ?>
            <?php } ?>
            <?php if ($action != 'view'): ?>
                <div class="text-right"><button id="saveForm" class="btn btn-primary" type="submit" name="save">Send feedback</button></div>
            <?php endif ?>
        </form>
    </div>
</div>
<?php
$js = <<<TXT
var FORM = $('#fbForm');
    $('table').on('mousedown', function(event) {
        // do your magic
        event.preventDefault();
    });
    $('td.selected .checker').click(function(e){
        // e.preventDefault();
        var tr = $(this).closest('tr');
        $(tr).removeClass('has-err');
        $(tr).find('span').removeClass('checked');

        $(this).find('span').toggleClass('checked');
        $(tr).find('input').val($(this).closest('td').index());

    });
    document.getElementById("fbForm").onsubmit = function() {
        return validate();
    };
    function validate() {
        var option_chk_val = true;
        $('table:not(.head-info) tr').each(function(index, tr){
            var status_uncheck = true;
            var tds = $(tr).find('td');
            if ($(tds).length > 0) {
                $(tds).each(function(i_td, td){
                    if ($(td).find('.checked').length > 0) {
                        status_uncheck = false;
                        return false;
                    }
                });
                if (status_uncheck) {
                    option_chk_val = false;
                    $(this).addClass('has-err');
                }
            }
        });
        if (!option_chk_val) {
            return false;
        }
        return true;
    }
TXT;

// $this->registerJsFile('/js/jquery.form.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJs($js);
?>