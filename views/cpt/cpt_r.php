<?php

use app\helpers\DateTimeHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

// include('_cpt_inc.php');

Yii::$app->params['body_class'] = 'bg-white';
Yii::$app->params['page_icon']  = 'money';

Yii::$app->params['page_meta_title'] = 'Cpt: ' . $theCpt['dvtour_name'];
Yii::$app->params['page_title']      = $theCpt['dvtour_name'];
if ($theCpt['venue']) {
    Yii::$app->params['page_title'] .= ' @' . Html::a($theCpt['venue']['name'], '@web/venues/r/' . $theCpt['venue_id']);
}
if ($theCpt['by_company_id']) {
    Yii::$app->params['page_title'] .= ' (' . Html::a($theCpt['company']['name'], '@web/companies/r/' . $theCpt['by_company_id']) . ')';
}

Yii::$app->params['page_breadcrumbs'][] = ['Tour', 'tours'];
Yii::$app->params['page_breadcrumbs'][] = [$theCpt['tour']['code'], 'tours/r/' . $theCpt['tour_id']];
Yii::$app->params['page_breadcrumbs'][] = ['Các chi phí tour', 'cpt?tour=' . $theCpt['tour_id']];
Yii::$app->params['page_breadcrumbs'][] = ['Xem', URI];

$action = $_GET['action'] ?  ? '';
$mttid  = $_GET['mtt-id'] ?  ? '';
$cmtid  = $_GET['cmt-id'] ?  ? '';

$ketoan = [
    '1'     => 'Ngọc Huân',
    '4065'  => 'Anh Tuấn',
    '28431' => 'Tú Phương',
    '11'    => 'Thu Hiền',
    '17'    => 'Đức Hạnh',
    '16'    => 'Thị Lan',
    '36871' => 'Diệu Linh',
    '20787' => 'Thanh Bình',
    '29739' => 'Thanh Huyền',
    '37159' => 'Thuý Nga',
    '30085' => 'Thị Ngọc',
    '34743' => 'Minh Ngọc',
    '34717' => 'Thu Huyền',
    '32206' => 'Kim Mong',
];
$check = [
    'c3' => 'TH/TOAN',
];
$lttStatusList = [
    '' => '-',
    0  => 'Dự định',
    1  => 'Đề nghị TT',
    2  => 'KTT duyệt Đề nghị TT',
    3  => 'GĐ duyệt TT',
    4  => 'Đã thanh toán',
    5  => 'Đã thanh toán, KTT xác nhận',
];

// Linked lichxe

$linkedLichxe = \common\models\Lichxe::find()
    ->select(['id', 'updated_by', 'updated_dt', 'tour_id'])
    ->where(['cpt_id' => $theCpt['dvtour_id']])
    ->with([
        'updatedBy' => function ($q) {
            return $q->select(['id', 'name' => 'nickname']);
        },
    ])
    ->asArray()
    ->one();
?>
<style type="text/css">
.label-status-on {color:#fff; background-color:#148040;}
.label-status-off {color:#fff; background-color:#999;}
</style>
<div class="col-md-8">
    <p><strong>THÔNG TIN DỊCH VỤ / CHI PHÍ</strong></p>
    <table class="table table-narrow table-bordered">
        <tbody>
            <tr><td><strong>Tour @ngày dùng</strong></td><td><?=Html::a($theCpt['tour']['code'], '@web/tours/services/' . $theCpt['tour_id'])?> @<?=Yii::$app->formatter->asDatetime($theCpt['dvtour_day'], 'php:j/n/Y D')?></td></tr>
            <tr><td><strong>DV/CPT</strong></td><td><?=$theCpt['dvtour_name']?>
                <?=$theCpt['oppr']?><?=$theCpt['venue_id'] == 0 ? '' : ' @' . $theCpt['venue']['name']?><?=$theCpt['company'] == 0 ? '' : ' (' . $theCpt['company']['name'] . ')'?> <?=$theCpt['viaCompany']['name']?>
                <?if ($linkedLichxe) {?>
                <br><i class="fa fa-car text-pink"></i> <?=Html::a('Có lịch xe (auto)!', '/tours/in-lx/' . $linkedLichxe['tour_id'] . '?action=print&lichxe=' . $linkedLichxe['id'])?>
                <?}?>
                </td>
            </tr>
            <tr><td><strong>SL x Giá =</strong></td><td><strong><?=rtrim(rtrim(number_format($theCpt['qty'], 2), '0'), '.')?></strong> <?=$theCpt['unit']?> x <strong><?=$theCpt['plusminus'] == 'plus' ? '' : '-'?><?=rtrim(rtrim(number_format($theCpt['price'], 2), '0'), '.')?></strong> <?=$theCpt['unitc']?> = <strong class="text-warning"><?=$theCpt['plusminus'] == 'plus' ? '' : '-'?><?=rtrim(rtrim(number_format($theCpt['price'] * $theCpt['qty'], 2), '0'), '.')?></strong> <?=$theCpt['unitc']?></td></tr>
            <tr><td><strong>Cập nhật</strong>:</td><td><?=$theCpt['updatedBy']['name']?> @ <?=DateTimeHelper::convert($theCpt['updated_at'], 'j/n/Y H:i')?></td></tr>
            <tr><th>Ai TT</th><td><?=$theCpt['payer']?></td></tr>
            <tr><th>Check</th><td><?=$theCpt['c3']?></td></tr>
            <tr><th>Paid in full</th><td><?=$theCpt['paid_full']?></td></tr>
        </tbody>
    </table>
    <br>

    <?if (USER_ID == 1) : ?>
    <div class="mb-20">
        <p><span class="text-semibold">TIẾT KIỆM CHI PHÍ</span> Điền số tiền tiết kiệm được</p>
        <div class="row">
            <div class="col-sm-3">
                <input type="number" class="form-control" min="0" step="1" name="a" value="">
            </div>
            <div class="col-sm-3">
                <select class="form-control" name="c">
                    <option value="USD">USD</option>
                    <option value="VND">VND</option>
                </select>
            </div>
            <div class="col-sm-3">
                <?=Html::submitButton('Save', ['class' => 'save-tkiem btn btn-primary'])?>
                <i title="Delete" class="del-tkiem fa fa-trash-o text-danger cursor-pointer"></i>
            </div>
        </div>
    </div>
    <?endif;?>


    <?if (strpos($theCpt['c3'], 'on,' . USER_ID) === 0 && $theCpt['paid_full'] != '') {?>
    <p><a href="?action=mark-unpaid" class="btn btn-danger"><i class="fa fa-refresh"></i> Đánh dấu chưa thanh toán</a> (sẽ xoá hết thông tin thanh toán)</p>
    <?}?>

    <p>
        <?if ($action != 'new-mtt') {?><?=Html::a('+Thêm', '?action=new-mtt', ['class' => 'pull-right'])?><?}?>
        <strong>THANH TOÁN THỰC TẾ</strong> (click để sửa / xoá)
    </p>

    <?if (empty($theCpt['mtt'])) {?>
    <p>Chưa có thông tin</p>
    <?} else {?>
    <table class="table table-narrow table-striped">
        <thead>
            <tr>
                <th>Ng.TT</th>
                <th>Số tiền TT</th>
                <th>TT=</th>
                <th>Tỉ giá</th>
                <th>TKGN</th>
                <th>MP</th>
                <th>TT hết</th>
                <th>Note</th>
                <th>Update</th>
                <th>Check</th>
            </tr>
        </thead>
        <tbody>

        <?foreach ($theCpt['mtt'] as $mtt) {?>
        <tr>
            <?if ($action == 'edit-mtt' && (int) $mttid == $mtt['id']) {?>
            <td colspan="10">
            <?$form = ActiveForm::begin();?>
            <fieldset>
                <legend>Sửa thông tin thanh toán</legend>
                <div class="row">
                    <div class="col-md-4"><?=$form->field($theMtt, 'payment_dt')->label('Ngày TT')?></div>
                    <div class="col-md-4"><?=$form->field($theMtt, 'amount')->label('Số tiền TT lần này tính theo ' . $theCpt['unitc'])?></div>
                    <div class="col-md-4"><?=$form->field($theMtt, 'currency')->label('TT bằng')->dropdownList(['USD' => 'USD', 'VND' => 'VND', 'EUR' => 'EUR', 'LAK' => 'LAK', 'KHR' => 'KHR', 'THB' => 'THB'], ['prompt' => '-Chọn-'])?></div>
                </div>
                <div class="row">
                    <div class="col-md-4"><?=$form->field($theMtt, 'xrate')->label('Với tỉ giá 1 ' . $theCpt['unitc'] . ' =')?></div>
                    <div class="col-md-4"><?=$form->field($theMtt, 'tkgn')?></div>
                    <div class="col-md-4"><?=$form->field($theMtt, 'mp')?></div>
                </div>
                <div class="row">
                    <div class="col-md-4"><?=$form->field($theMtt, 'paid_in_full')->label('Tình trạng CPT sau TT')->dropdownList(['yes' => 'Đã TT toàn bộ', 'no' => 'Mới TT một phần'])?></div>
                    <div class="col-md-8"><?=$form->field($theMtt, 'note')?></div>
                </div>
                <div>
                    <?=Html::a('Xoá?', '?action=delete-mtt&&mtt-id=' . $mtt['id'], ['class' => 'text-danger pull-right'])?>
                    <?=Html::submitButton('Ghi thông tin', ['class' => 'btn btn-primary'])?> hoặc <?=Html::a('Thôi, quay lại', DIR . URI)?>
                </div>
            </fieldset>

            <?ActiveForm::end();?>
            </td>
            <?} else {?>
                <?if ($mtt['status'] == 'deleted') {?>
            <td colspan="9" class="text-muted">(đã xoá) - <?=$mtt['updatedBy']['name']?> <?=$mtt['updated_dt']?></td>
                <?} else {?>
            <td><?=date('j/n', strtotime($mtt['updated_dt']))?></td>
            <td class="text-nowrap text-right"><?=Html::a(number_format($mtt['amount'], intval($mtt['amount']) == $mtt['amount'] ? 0 : 2), '?action=edit-mtt&mtt-id=' . $mtt['id'])?> <span class="text-muted"><?=$theCpt['unitc']?></span></td>
            <td><?=$mtt['currency']?></td>
            <td><?=+$mtt['xrate']?></td>
            <td><?=$mtt['tkgn']?></td>
            <td><?=$mtt['mp']?></td>
            <td><?=$mtt['paid_in_full'] == 'yes' ? 'OK' : '-'?></td>
            <td><?=Html::encode($mtt['note'])?></td>
            <td><i title="<?=Yii::$app->formatter->asRelativetime($mtt['updated_dt'])?>" class="text-muted fa fa-clock-o"></i><?=$mtt['updatedBy']['name']?></td>
            <td><?if ($mtt['check'] != '') {?><i title="<?=Yii::$app->formatter->asRelativetime(substr(strrchr($mtt['check'], ','), 1))?>" class="text-muted fa fa-clock-o"></i> <span class="label label-success">CHK</span><?}?></td>
                <?}?>
            <?}?>
        </tr>
        <?}?>
        </tbody>
    </table><br>
    <?}?>

    <?if ($action == 'new-mtt') {?>
    <hr>
    <p><strong>THÊM THÔNG TIN THANH TOÁN</strong></p>
    <?$form = ActiveForm::begin();?>
    <fieldset>
        <div class="row">
            <div class="col-md-4"><?=$form->field($theMtt, 'payment_dt')->label('Ngày TT')?></div>
            <div class="col-md-4"><?=$form->field($theMtt, 'amount')->label('Số tiền TT lần này tính theo ' . $theCpt['unitc'])?></div>
            <div class="col-md-4"><?=$form->field($theMtt, 'currency')->label('TT bằng')->dropdownList(['USD' => 'USD', 'VND' => 'VND', 'EUR' => 'EUR', 'LAK' => 'LAK', 'KHR' => 'KHR', 'THB' => 'THB'], ['prompt' => '-Chọn-'])?></div>
    </div>
        <div class="row">
            <div class="col-md-4"><?=$form->field($theMtt, 'xrate')->label('Với tỉ giá 1 ' . $theCpt['unitc'] . ' =')?></div>
            <div class="col-md-4"><?=$form->field($theMtt, 'tkgn')?></div>
            <div class="col-md-4"><?=$form->field($theMtt, 'mp')?></div>
        </div>
        <div class="row">
            <div class="col-md-4"><?=$form->field($theMtt, 'paid_in_full')->label('Tình trạng CPT sau TT')->dropdownList(['yes' => 'Đã TT toàn bộ', 'no' => 'Mới TT một phần'])?></div>
            <div class="col-md-8"><?=$form->field($theMtt, 'note')?></div>
        </div>
        <?=Html::submitButton('Ghi thông tin', ['class' => 'btn btn-primary'])?> hoặc <?=Html::a('Thôi, quay lại', DIR . URI)?>
    </fieldset>
    <?ActiveForm::end();?>
    <?} // if new-mtt ?>

    <?if ($action == 'draft-mtt') {?>
    <hr>
<p><strong>SỬA THÔNG TIN DỰ ĐỊNH THANH TOÁN</strong> (save form <a href="/cpt/thanh-toan">Thanh toán</a> để ghi nhận)</p>
    <?$form = ActiveForm::begin();?>
 <fieldset>
        <div class="row">
            <div class="col-md-4"><?=$form->field($theMtt, 'payment_dt')->label('Ngày TT')?></div>
            <div class="col-md-4"><?=$form->field($theMtt, 'amount')->label('Số tiền TT lần này tính theo ' . $theCpt['unitc'])?></div>
            <div class="col-md-4"><?=$form->field($theMtt, 'currency')->label('TT bằng')->dropdownList(['USD' => 'USD', 'VND' => 'VND', 'EUR' => 'EUR', 'LAK' => 'LAK', 'KHR' => 'KHR', 'THB' => 'THB'], ['prompt' => '-Chọn-'])?></div>
        </div>
        <div class="row">
            <div class="col-md-4"><?=$form->field($theMtt, 'xrate')->label('Với tỉ giá 1 ' . $theCpt['unitc'] . ' =')?></div>
            <div class="col-md-4"><?=$form->field($theMtt, 'tkgn')?></div>
            <div class="col-md-4"><?=$form->field($theMtt, 'mp')?></div>
        </div>
        <div class="row">
            <div class="col-md-4"><?=$form->field($theMtt, 'paid_in_full')->label('Tình trạng CPT sau TT')->dropdownList(['yes' => 'Đã TT toàn bộ', 'no' => 'Mới TT một phần'])?></div>
            <div class="col-md-8"><?=$form->field($theMtt, 'note')?></div>
        </div>
        <?=Html::a('Xoá?', '?action=delete-draft-mtt&&mtt-id=' . $mtt['id'], ['class' => 'text-danger pull-right'])?>
        <?=Html::submitButton('Ghi thông tin', ['class' => 'btn btn-primary'])?> hoặc <?=Html::a('Thôi, quay lại', '/cpt/thanh-toan')?>
    </fieldset>
    <?ActiveForm::end();?>
    <?} // if new-mtt ?>
<?
$checkMarks = [];
foreach ($check as $k => $v) {
    if ($theCpt[$k] == '') {
        $status = 'off';
        $user   = false;
        $time   = false;
    } else {
        $parts  = explode(',', $theCpt[$k]);
        $status = $parts[0];
        $user   = $ketoan[$parts[1]] ?  ? $parts[1];
        $time   = $parts[2];
    }
    $checkMarks[] = [
        'label'  => $v,
        'status' => $status,
        'user'   => $user,
        'time'   => $time,
    ];
}
?>
    <br>
    <p><strong>HOÁ ĐƠN VAT</strong> <?=$theCpt['vat_ok'] == 'ok' ? '<i class="fa fa-check text-success"></i> Đã lấy' : '<span class="text-muted">Chưa/Không lấy</span>'?></p>

    <?if (!empty($theCpt['edits'])) {
    $cpt = false;
    ?>
    <p class="text-bold text-uppercase">Edits</p>
    <ul>
        <?
    foreach ($theCpt['edits'] as $edit) {
        if (!$cpt) {
            $cpt = $theCpt;
        }
        ?>
        <li><strong><?=DateTimeHelper::convert($cpt['updated_at'], 'j/n/Y H:i')?></strong>
        <?
        foreach ($cpt as $key => $val) {
            if (isset($edit[$key]) && $val != $edit[$key] && !in_array($key, ['dvtour_id', 'id', 'created_at', 'updated_at', 'latest'])) {
                echo " [$key: $val]";
            }
        }
        ?>
        </li>
        <?
        $cpt = $edit;
    }
    ?>
        <li><strong><?=DateTimeHelper::convert($cpt['updated_at'], 'j/n/Y H:i')?></strong>
        <?
    foreach ($cpt as $key => $val) {
        if (!in_array($key, ['dvtour_id', 'id', 'created_at', 'updated_at', 'latest'])) {
            echo " [$key: $val]";
        }
    }
    ?>
        </li>
    </ul>
    <?}?>
</div>
<div class="col-md-4">
    <p><strong>CÁC GHI CHÚ</strong></p>
    <?
if (!empty($theCpt['comments'])) {
    foreach ($theCpt['comments'] as $comment) {
        if ($action == 'edit-cmt' && $cmtid == (int) $comment['id']) {
            ?>
    <div class="media">
        <div class="media-left">
            <a href="#"><img src="<?='/timthumb.php?w=100&h=100&src=' . $comment['updatedBy']['image']?>" class="img-circle img-md" alt=""></a>
        </div>
        <div class="media-body">
            <h6 class="media-heading"><?=$comment['updatedBy']['name']?> <span class="media-annotation dotted"><?=Yii::$app->formatter->asRelativeTime($comment['created_at'])?></span></h6>
            <?$form = ActiveForm::begin();?>
            <?=$form->field($theComment, 'body')->textArea(['rows' => 5])->label('Sửa ghi chú')?>
            <div class=""><?=Html::submitButton('Submit', ['class' => 'btn btn-primary'])?> hoặc <?=Html::a('Thôi, quay lại', DIR . URI)?></div>
            <?ActiveForm::end();?>
        </div>
    </div>
<?
        } else {
            ?>
    <div class="media">
        <div class="media-left">
            <a href="#"><img src="<?='/timthumb.php?w=100&h=100&src=' . $comment['updatedBy']['image']?>" class="img-circle img-md" alt=""></a>
        </div>
        <div class="media-body">
            <h6 class="media-heading"><?=$comment['updatedBy']['name']?> <span class="media-annotation dotted"><?=Yii::$app->formatter->asRelativeTime($comment['created_at'])?></span></h6>
            <?if ($comment['status'] == 'deleted') {?>
            <p class="text-muted">(Đã xoá ghi chú)</p>
            <?} else {?>
            <p><?=nl2br(Html::encode($comment['body']))?></p>
            <?} // not deleted ?>
            <?if ($comment['status'] != 'deleted' && in_array(USER_ID, [1, $comment['created_by'], $comment['updated_by']])) {?>
            <ul class="list-inline list-inline-separate text-size-small">
                <!--li>114 <a href="#"><i class="fa fa-chevron-up text-success"></i></a> <a href="#"><i class="fa fa-chevron-down text-danger"></i></a></li-->
                <li><a href="/cpt/r/<?=$theCpt['dvtour_id']?>?action=edit-cmt&cmt-id=<?=$comment['id']?>">Edit</a></li>
                <li><a class="text-danger" href="/cpt/r/<?=$theCpt['dvtour_id']?>?action=delete-cmt&cmt-id=<?=$comment['id']?>">Delete</a></li>
            </ul>
            <?} // if deleted ?>
        </div>
    </div>
    <?
        } // if edit
    }
    echo '<br>';
}
?>
    <?if ($action != 'edit-cmt') {?>
    <?$form = ActiveForm::begin();?>
    <div class="media">
        <div class="media-left">
            <a href="#"><img src="/timthumb.php?w=100&h=100&src=<?=Yii::$app->user->identity->image?>" class="img-circle img-md" alt=""></a>
        </div>
        <div class="media-body">
            <?=$form->field($theComment, 'body')->textArea(['rows' => 5])->label('Thêm ghi chú của bạn')?>
            <div class="text-right"><?=Html::submitButton('Thêm', ['class' => 'btn btn-primary'])?></div>
        </div>
    </div>
    <?ActiveForm::end();?>
    <?}?>
</div>
<?
$js = <<<'TXT'
$('.save-tkiem').on('click', function(){
    $.post( "?xh", {
        action: "save-tkiem",
        amount: $("[name=a]").val(),
        currency: $("[name=c]").val()
    })
    .done(function( data ) {
        alert( "Data Loaded: " + data );
    })
    .fail(function( data ) {
        alert( "Error saving data: " + data );
    })
    ;
});
TXT;

$this->registerJs($js);